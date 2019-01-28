<?php

namespace STS\Percy;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use STS\Percy\Console\DuskCommand;
use STS\Percy\Console\DuskFailsCommand;

class PercyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/percy.php' => config_path('percy.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/percy.php', 'percy'
        );

        // Extend and override the base dusk commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                DuskCommand::class,
                DuskFailsCommand::class
            ]);
        }

        $this->app->singleton(Agent::class, function () {
            return new Agent(config('percy.agent_path'), config('percy.client_info'));
        });

        Browser::macro('percySnapshot', function ($name = null, $options = []) {
            return app(Agent::class)->snapshot($this, $name, $options);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [Agent::class];
    }
}