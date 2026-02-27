<?php

namespace LaravelIran\BaleMessenger;

use Illuminate\Support\ServiceProvider;
use LaravelIran\BaleMessenger\Contracts\BaleClientInterface;

/**
 * سرویس‌پروایدر پکیج بله
 */
class BaleMessengerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bale.php', 'bale');

        $this->app->singleton(BaleClient::class, function ($app) {
            return new BaleClient(
                $app['config']->get('bale.token'),
                $app['config']->get('bale.options', [])
            );
        });

        $this->app->bind(BaleClientInterface::class, BaleClient::class);

        $this->app->singleton(BaleService::class, function ($app) {
            return new BaleService($app->make(BaleClient::class));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/bale.php' => config_path('bale.php'),
        ], 'bale-config');
    }

    public function provides(): array
    {
        return [
            BaleClient::class,
            BaleClientInterface::class,
            BaleService::class,
        ];
    }
}
