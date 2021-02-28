<?php

namespace Citadelle\ReferentielApi;


use Citadelle\ReferentielApi\app\Console\Commands\Referentiel;
use Illuminate\Support\ServiceProvider;

class ReferentielApiServiceProvider extends ServiceProvider
{

    protected $commands = [
        Referentiel::class,
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->addCustomConfigurationValues();
    }

    public function addCustomConfigurationValues()
    {
        // add filesystems.disks for the log viewer
        config([
            'logging.channels.citadelle' => [
                'driver' => 'single',
                'path' => storage_path('logs/citadelle-api.log'),
                'level' => 'debug',
            ]
        ]);

    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/referentiel.php', 'citadelle.referentiel'
        );

        // register the artisan commands
        $this->commands($this->commands);
    }
}
