<?php

namespace Digitwires\Payway\Providers;

use Digitwires\Payway\Classes\PaypalGateway;

class PaywayServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->configure();

        $transPath = __DIR__ . "/../resources/lang";
        $this->loadTranslationsFrom($transPath, "d-payway");

        $this->publishes(
            [
                __DIR__ . "/../config/d-payway.php" => config_path(
                    "d-payway.php"
                ),
            ],
            "digitwires-payway-config"
        );

        $this->publishes(
            [
                __DIR__ . "/../resources/lang" => $transPath,
            ],
            "digitwires-payway-lang"
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
        $this->mergeConfigFrom(__DIR__ . "/../config/d-payway.php", "d-payway");
    }

    protected function registerTranslations($transPath)
    {
        $this->loadTranslationsFrom($transPath, "d-payway");
    }
}
