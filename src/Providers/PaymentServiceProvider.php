<?php

namespace Digitwires\Payments\Providers;

use Digitwires\Payments\Classes\PaypalGateway;

class PaymentServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->configure();

        $transPath = __DIR__ . "/../resources/lang";
        $this->loadTranslationsFrom($transPath, "d-payments");

        $this->publishes(
            [
                __DIR__ . "/../config/d-payments.php" => config_path(
                    "d-payments.php"
                ),
            ],
            "digitwires-payments-config"
        );

        $this->publishes(
            [
                __DIR__ . "/../resources/lang" => $transPath,
            ],
            "digitwires-payments-lang"
        );

        $this->registerTranslations($transPath);
    }

    public function register()
    {
        $this->app->bind(PaypalGateway::class, function ($app) {
            return new PaypalGateway();
        });
    }

    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . "/../config/d-payments.php",
            "d-payments"
        );
    }

    protected function registerTranslations($transPath)
    {
        $this->loadTranslationsFrom($transPath, "d-payments");
    }
}
