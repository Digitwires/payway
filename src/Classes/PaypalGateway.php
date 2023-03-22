<?php

namespace Digitwires\Payments\Classes;

use Digitwires\Payments\Interfaces\PaymentInterface;
use GuzzleHttp\Client;
use http\Client\Request;

class PaypalGateway extends BaseController implements PaymentInterface
{
    private $client_id;
    private $client_secret;
    private $mode;
    private $verify_url;
    private $cancel_url;
    public $currency;

    public function __construct()
    {
        $this->init();
        parent::__construct();
    }

    public function init()
    {
        $this->client_id = config("d-payments.PAYPAL_CLIENT_ID");
        $this->client_secret = config("d-payments.PAYPAL_CLIENT_SECRET");
        $this->mode = config("d-payments.PAYPAL_MODE");
        $this->currency = config("d-payments.PAYPAL_CURRENCY");
        $this->verify_url = config("d-payments.PAYPAL_VERIFY_URL");
        $this->cancel_url = config("d-payments.PAYPAL_CANCEL_URL");
    }

    public function pay(
        $amount,
        $user_id = null,
        $user_first_name = null,
        $user_last_name = null,
        $user_email = null,
        $user_phone = null,
        $source = null,
        $currency = null
    ) {
        $this->setVariablesInGlobal(
            $user_id,
            $user_first_name,
            $user_last_name,
            $user_email,
            $user_phone,
            $source,
            $currency,
            $amount
        );
        $required_fields = ["amount"];
        $this->checkRequiredFields($required_fields, "Paypal");

        if ($this->mode == "sandbox") {
            $env = "https://api.sandbox.paypal.com";
        } else {
            $env = "https://api.paypal.com";
        }

        $paypalRequestClient = new Client([
            "base_uri" => $env,
        ]);

        try {
            $response = $paypalRequestClient->request(
                "POST",
                "/v2/checkout/orders",
                [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "Authorization" =>
                            "Basic " .
                            base64_encode(
                                $this->client_id . ":" . $this->client_secret
                            ),
                        "Prefer" => "return=representation",
                    ],
                    "json" => [
                        "intent" => "CAPTURE",
                        "purchase_units" => [
                            [
                                "amount" => [
                                    "currency_code" => $this->currency,
                                    "value" => $this->amount,
                                ],
                            ],
                        ],
                        "application_context" => [
                            "cancel_url" => $this->cancel_url,
                            "return_url" => $this->verify_url,
                        ],
                    ],
                ]
            );

            $response = json_decode($response->getBody(), true);
            return [
                "payment_id" => $response["result"]["id"],
                "redirect_url" => collect($response["result"]["links"])
                    ->where("rel", "approve")
                    ->firstOrFail()["href"],
            ];
        } catch (\Exception $e) {
            return [
                "status" => false,
                "message" => __("messages.payment_failed"),
                "error" => $e,
            ];
        }
    }

    public function verifyPayment(Request $request): array
    {
        if ($this->mode == "sandbox") {
            $env = "https://api.sandbox.paypal.com";
        } else {
            $env = "https://api.paypal.com";
        }

        $paypalRequestClient = new Client([
            "base_uri" => $env,
        ]);

        $paymentId = request("paymentId");

        try {
            $response = $paypalRequestClient->request(
                "POST",
                "/v2/checkout/orders/" . $paymentId . "/capture",
                [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "Authorization" =>
                            "Basic " .
                            base64_encode(
                                $this->client_id . ":" . $this->client_secret
                            ),
                    ],
                ]
            );
            if ($response->getStatusCode() == 201) {
                return [
                    "success" => true,
                    "message" => __("messages.payment_success"),
                    "data" => $response,
                ];
            } else {
                return [
                    "success" => false,
                    "message" => __("messages.payment_failed"),
                    "data" => $response,
                ];
            }
        } catch (\Exception $e) {
            return [
                "success" => false,
                "payment_id" => $request["token"],
                "message" => __("messages.payment_failed"),
                "error" => $e,
            ];
        }
    }
}
