<?php

namespace Digitwires\Payway\Classes;

use Digitwires\Payway\Interfaces\PaywayInterface;
use GuzzleHttp\Client;
use http\Client\Request;
use Illuminate\Support\Facades\Cache;

class PaytabsGateway extends BaseController implements PaywayInterface
{
    private $paytabs_profile_id;
    private $paytabs_base_url;
    private $paytabs_server_key;
    private $paytabs_checkout_lang;
    private $verify_url;
    private $cancel_url;

    public function __construct()
    {
        $this->init();
        parent::__construct();
    }

    public function init()
    {
        $this->paytabs_profile_id = config("payway.PAYTABS_PROFILE_ID");
        $this->paytabs_base_url = config("payway.PAYTABS_BASE_URL");
        $this->paytabs_server_key = config("payway.PAYTABS_SERVER_KEY");
        $this->paytabs_checkout_lang = config("payway.PAYTABS_CHECKOUT_LANG");
        $this->verify_url = config("payway.PAYTABS_VERIFY_URL");
        $this->cancel_url = config("payway.PAYTABS_CANCEL_URL");
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
        $this->setVariablesInGlobal([
            "user_id" => $user_id,
            "user_first_name" => $user_first_name,
            "user_last_name" => $user_last_name,
            "user_email" => $user_email,
            "user_phone" => $user_phone,
            "source" => $source,
            "currency" => $currency,
            "amount" => $amount,
        ]);
        $required_fields = ["amount"];
        $this->checkRequiredFields($required_fields, "PayTabs");

        $uniqueId = uniqid();

        $paypalRequestClient = new Client([
            "base_uri" => $this->paytabs_base_url,
        ]);
        $response = $paypalRequestClient->request("POST", "/payment/request", [
            "headers" => [
                "Content-Type" => "application/json",
                "Authorization" => $this->paytabs_server_key,
            ],
            "json" => [
                "profile_id" => $this->paytabs_profile_id,
                "tran_type" => "sale",
                "tran_class" => "ecom",
                "cart_id" => $uniqueId,
                "cart_description" => "Payment for order #{$uniqueId}",
                "cart_currency" => $this->currency,
                "cart_amount" => $this->amount,
                "return" => $this->verify_url,
                "paypage_lang" => $this->paytabs_checkout_lang,
                "customer_details" => [
                    "name" =>
                        $this->user_first_name . " " . $this->user_last_name,
                    "email" => $this->user_email,
                    "phone" => $this->user_phone,
                ],
                "valu_down_payment" => 0,
                "tokenise" => 1,
            ],
        ]);

        if (!isset($response["code"])) {
            Cache::forever($uniqueId, $response["tran_ref"]);
            return [
                "payment_id" => $response["tran_ref"],
                "redirect_url" => $response["redirect_url"],
            ];
        }
        return [
            "success" => false,
            "message" => $response["message"],
        ];
    }

    public function verifyPayment(Request $request): array
    {
        $payment_id =
            $request->tranRef != null
                ? $request->tranRef
                : Cache::get($request["tranRef"]);

        $paypalRequestClient = new Client([
            "base_uri" => $this->paytabs_base_url,
        ]);

        $response = $paypalRequestClient->request("POST", "/payment/query", [
            "headers" => [
                "Content-Type" => "application/json",
                "Authorization" => $this->paytabs_server_key,
            ],
            "json" => [
                "profile_id" => $this->paytabs_profile_id,
                "tran_ref" => $payment_id,
            ],
        ]);

        if (
            isset($response["payment_result"]["response_status"]) &&
            $response["payment_result"]["response_status"] == "A"
        ) {
            return [
                "success" => true,
                "payment_id" => $payment_id,
                "message" => __("messages.payment_success"),
                "process_data" => $response,
            ];
        } else {
            return [
                "success" => false,
                "payment_id" => $payment_id,
                "message" => __("messages.payment_failed"),
                "process_data" => $response,
            ];
        }
    }
}
