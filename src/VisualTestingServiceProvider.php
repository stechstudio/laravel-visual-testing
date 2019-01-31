<?php

namespace STS\VisualTesting;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Dusk\Browser;
use STS\VisualTesting\Console\DuskCommand;
use STS\VisualTesting\Console\DuskFailsCommand;

class VisualTestingServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/visual-testing.php' => config_path('visual-testing.php'),
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
            __DIR__ . '/../config/visual-testing.php', 'visual-testing'
        );

        // Extend and override the base dusk commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                DuskCommand::class,
                DuskFailsCommand::class
            ]);
        }

        $this->app->singleton(Agent::class, function () {
            return new Agent(
                config('visual-testing.percy.agent_path'),
                $this->clientInfo(),
                config('visual-testing.percy.environment_info'),
                config('visual-testing.percy.snapshot_options')
            );
        });

        Browser::macro('snapshot', function ($name = null, $options = []) {
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

    /**
     * @return string
     */
    protected function clientInfo()
    {
        return "stechstudio/laravel-visual-testing/" . $this->packageVersion();
    }

    /**
     * @return string
     */
    protected function packageVersion()
    {
        if(!file_exists(base_path("composer.lock"))) {
            return '';
        }

        $composer = json_decode(file_get_contents(base_path("composer.lock")), true);

        return collect($composer['packages'])
            ->merge($composer['packages-dev'])
            ->where("name", "stechstudio/laravel-visual-testing")
            ->pluck("version")
            ->first();
    }
}
