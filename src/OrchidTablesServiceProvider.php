<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables;

use Exception;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;
use Orchid\Screen\Cell;
use Orchid\Screen\Field;
use Orchid\Screen\LayoutFactory;

class OrchidTablesServiceProvider extends ServiceProvider
{
    protected Dashboard $dashboard;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     * @throws Exception
     */
    public function boot(Dashboard $dashboard): void
    {
        $this->dashboard = $dashboard;
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'platform');

        $this->registerResources();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/orchid-tables.php', 'orchid-tables');

        config('orchid-tables.mixins.can') && $this->addCanMixins(config('orchid-tables.mixins.can'));
        config('orchid-tables.mixins.cell') && $this->addCellMixins(config('orchid-tables.mixins.cell'));
        config('orchid-tables.mixins.layout') && $this->addLayoutMixins(config('orchid-tables.mixins.layout'));

        // Register the service the package provides.
        $this->app->singleton('orchid-tables', function ($app) {
            return new OrchidTables();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['orchid-tables'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__ . '/../config/orchid-tables.php' => config_path('orchid-tables.php'),
        ], 'orchid-tables.config');

        $this->publishes([
            __DIR__ . '/../public/' => base_path('public/vendor/lintaba-orchid-tables/')
        ], ['orchid-tables-assets','laravel-assets']);
    }

    /**
     * Registering resources.
     *
     * @throws Exception
     */
    private function registerResources(): self
    {
        View::composer('platform::app', function () {
            $this->dashboard
                ->registerResource(
                    'scripts',
                    mix('/js/bulkselect.js', 'vendor/lintaba-orchid-tables')
                )
                ->registerResource(
                    'stylesheets',
                    mix('/css/bulkselect.css', 'vendor/lintaba-orchid-tables')
                );
        });

        return $this;
    }

    protected function addCanMixins($mixin): void
    {
        $mix = $this->app->make($mixin);

        Cell::mixin($mix);
        Field::mixin($mix);
        LayoutFactory::mixin($mix);
    }

    protected function addCellMixins($mixin): void
    {
        $mix = $this->app->make($mixin);

        Cell::mixin($mix);
    }

    protected function addLayoutMixins($mixin): void
    {
        $mix = $this->app->make($mixin);

        LayoutFactory::mixin($mix);
    }
}
