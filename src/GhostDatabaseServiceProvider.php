<?php

namespace HiHaHo\GhostDatabase;

use HiHaHo\GhostDatabase\Console\Commands\CleanupSnapshots;
use HiHaHo\GhostDatabase\Console\Commands\Export;
use HiHaHo\GhostDatabase\Console\Commands\Flush;
use HiHaHo\GhostDatabase\Console\Commands\Import;
use Illuminate\Support\ServiceProvider;

class GhostDatabaseServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/ghost-database.php' => config_path('ghost-database.php'),
            ], 'ghost-database.config');

            $this->commands([
                Import::class,
                Export::class,
                Flush::class,
                CleanupSnapshots::class
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ghost-database.php', 'ghost-database');

        $this->app->singleton('ghost-database', function (\Illuminate\Contracts\Foundation\Application $app) {
            return $app->make(GhostDatabase::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ghost-database'];
    }
}
