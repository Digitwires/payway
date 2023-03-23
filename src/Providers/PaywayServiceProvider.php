<?php

namespace Digitwires\Payway\Providers;

use Digitwires\Payway\Classes\PaypalGateway;
use Digitwires\Payway\Classes\PaytabsGateway;

class PaywayServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->configure();

        $transPath = "vendor/payway";
        $transPath = function_exists("resource_path")
            ? lang_path($transPath)
            : resource_path("lang/") . $transPath;

        $this->loadTranslationsFrom($transPath, "payway");

        $this->publishes(
            [
                __DIR__ . "/../../config/payway.php" => config_path(
                    "payway.php"
                ),
            ],
            "payway-config"
        );

        $this->publishes(
            [
                __DIR__ . "/../../resources/lang" => $transPath,
            ],
            "payway-lang"
        );

        $this->registerTranslations($transPath);
    }

    public function register()
    {
        $this->app->bind(PaypalGateway::class, function ($app) {
            return new PaypalGateway();
        });
        $this->app->bind(PaytabsGateway::class, function ($app) {
            return new PaytabsGateway();
        });
    }

    protected function configure()
    {
        $this->mergeConfigFrom(__DIR__ . "/../../config/payway.php", "payway");
    }

    protected function registerTranslations($transPath)
    {
        $this->loadTranslationsFrom($transPath, "payway");
    }
}
