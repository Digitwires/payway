<?php

namespace Digitwires\Payway\Classes;

use Digitwires\Payway\Exceptions\MissingInfoException;
use Digitwires\Payway\Interfaces\PaywayInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use http\Client\Request;

class PaymobGateway extends BaseController implements PaywayInterface
{
    private $paymob_api_key;
    private $paymob_integration_id;
    private $paymob_iframe_id;
    private $paymob_hmac_secret;
    public $paymob_currency;

    public function __construct()
    {
        $this->init();
        parent::__construct();
    }

    public function init()
    {
        $this->paymob_api_key = config("payway.PAYMOB_API_KEY");
        $this->paymob_integration_id = config("payway.PAYMOB_INTEGRATION_ID");
        $this->paymob_iframe_id = config("payway.PAYMOB_IFRAME_ID");
        $this->paymob_hmac_secret = config("payway.PAYMOB_HMAC_SECRET");
        $this->paymob_currency = config("payway.PAYMOB_CURRENCY");
    }

    /**
     * @throws MissingInfoException|GuzzleException
     */
    public function initPayment(
        $amount,
        $user_id = null,
        $user_first_name = null,
        $user_last_name = null,
        $user_email = null,
        $user_phone = null,
        $source = null,
        $currency = null
    ) {
        $this->setVariablesInGlobal([
            $amount,
            $user_id,
            $user_first_name,
            $user_last_name,
            $user_email,
            $user_phone,
            $source,
        ]);
        $required_fields = [
            "amount",
            "user_first_name",
            "user_last_name",
            "user_email",
            "user_phone",
        ];
        $this->checkRequiredFields($required_fields, "Paymob");

        $paymobRequestClient = new Client([
            "base_uri" => "https://accept.paymobsolutions.com/api",
        ]);

        try {
            $getToken = $paymobRequestClient->request("POST", "/auth/tokens", [
                "headers" => [
                    "Content-Type" => "application/json",
                ],
                "json" => [
                    "api_key" => $this->paymob_api_key,
                ],
            ]);

            $registerOrder = $paymobRequestClient->request(
                "POST",
                "/ecommerce/orders",
                [
                    "headers" => [
                        "Content-Type" => "application/json",
                    ],
                    "json" => [
                        "auth_token" => json_decode($getToken->getBody())
                            ->token,
                        "delivery_needed" => false,
                        "amount_cents" => $amount * 100,
                        "currency" => $this->paymob_currency,
                        "items" => [],
                    ],
                ]
            );

            $paymentKey = $paymobRequestClient->request(
                "POST",
                "/acceptance/payment_keys",
                [
                    "headers" => [
                        "Content-Type" => "application/json",
                    ],
                    "json" => [
                        "auth_token" => json_decode($getToken->getBody())
                            ->token,
                        "amount_cents" => $amount * 100,
                        "expiration" => 3600,
                        "order_id" => json_decode($registerOrder->getBody())
                            ->id,
                        "currency" => $this->paymob_currency,
                        "integration_id" => $this->paymob_integration_id,
                        "billing_data" => [
                            "apartment" => "NA",
                            "email" => $this->user_email,
                            "floor" => "NA",
                            "first_name" => $this->user_first_name,
                            "street" => "NA",
                            "building" => "NA",
                            "phone_number" => $this->user_phone,
                            "shipping_method" => "NA",
                            "postal_code" => "NA",
                            "city" => "NA",
                            "country" => "NA",
                            "last_name" => $this->user_last_name,
                            "state" => "NA",
                        ],
                    ],
                ]
            );

            return [
                "payment_id" => json_decode($registerOrder->getBody())->id,
                "payment_key" => json_decode($paymentKey->getBody())->token,
                "payment_url" =>
                    "https://accept.paymobsolutions.com/api/acceptance/iframes/$this->paymob_iframe_id?payment_token=" .
                    json_decode($paymentKey->getBody())->token,
            ];
        } catch (\Exception $e) {
            throw new MissingInfoException($e->getMessage());
        }
    }

    public function verifyPayment(Request $request): array
    {
        $callbackAsString =
            $request["amount_cents"] .
            $request["created_at"] .
            $request["currency"] .
            $request["error_occured"] .
            $request["has_parent_transaction"] .
            $request["obj.id"] .
            $request["integration_id"] .
            $request["is_3d_secure"] .
            $request["is_auth"] .
            $request["is_capture"] .
            $request["is_refunded"] .
            $request["is_standalone_payment"] .
            $request["is_voided"] .
            $request["order.id"] .
            $request["owner"] .
            $request["pending"] .
            $request["source_data.pan"] .
            $request["source_data.sub_type"] .
            $request["source_data.type"] .
            $request["success"];

        $hash = hash_hmac(
            "sha512",
            $callbackAsString,
            $this->paymob_hmac_secret
        );

        if ($hash) {
            if ($request["success"]) {
                return [
                    "payment_id" => $request["order"],
                    "payment_status" => "success",
                    "message" => __("payment_success"),
                    "data" => $request,
                ];
            } else {
                return [
                    "payment_id" => $request["order"],
                    "payment_status" => "failed",
                    "message" => __("payment_failed"),
                    "data" => $request,
                ];
            }
        } else {
            return [
                "payment_id" => $request["order"],
                "payment_status" => "failed",
                "message" => __("payment_failed"),
                "data" => $request,
            ];
        }
    }

    public function refundPayment($transaction_id, $amount = null): array
    {
        $paymobRequestClient = new Client([
            "base_uri" => "https://accept.paymobsolutions.com/api",
        ]);

        try {
            $getToken = $paymobRequestClient->request("POST", "/auth/tokens", [
                "headers" => [
                    "Content-Type" => "application/json",
                ],
                "json" => [
                    "api_key" => $this->paymob_api_key,
                ],
            ]);

            $refund = $paymobRequestClient->request(
                "POST",
                "/acceptance/void_refund/refund",
                [
                    "headers" => [
                        "Content-Type" => "application/json",
                    ],
                    "json" => [
                        "auth_token" => json_decode($getToken->getBody())
                            ->token,
                        "amount_cents" => $amount * 100,
                        "transaction_id" => $transaction_id,
                    ],
                ]
            );

            return [
                "payment_id" => $transaction_id,
                "payment_status" => "success",
                "message" => __("payment_refunded"),
                "data" => json_decode($refund->getBody()),
            ];
        } catch (\Exception $e) {
            return [
                "transaction_id" => $transaction_id,
                "payment_status" => "failed",
                "message" => __("refund_failed"),
                "data" => $e->getMessage(),
            ];
        }
    }

    public function voidTransaction($transaction_id): array
    {
        $paymobRequestClient = new Client([
            "base_uri" => "https://accept.paymobsolutions.com/api",
        ]);

        try {
            $getToken = $paymobRequestClient->request("POST", "/auth/tokens", [
                "headers" => [
                    "Content-Type" => "application/json",
                ],
                "json" => [
                    "api_key" => $this->paymob_api_key,
                ],
            ]);

            $void = $paymobRequestClient->request(
                "POST",
                "/acceptance/void_refund/void?token=" .
                    json_decode($getToken->getBody())->token,
                [
                    "headers" => [
                        "Content-Type" => "application/json",
                    ],
                    "json" => [
                        "transaction_id" => $transaction_id,
                    ],
                ]
            );

            return [
                "payment_id" => $transaction_id,
                "payment_status" => "success",
                "message" => __("payment_refunded"),
                "data" => json_decode($void->getBody()),
            ];
        } catch (\Exception $e) {
            return [
                "transaction_id" => $transaction_id,
                "payment_status" => "failed",
                "message" => __("refund_failed"),
                "data" => $e->getMessage(),
            ];
        }
    }
}
