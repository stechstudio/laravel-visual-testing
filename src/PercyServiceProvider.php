<?php
namespace STS\Percy;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class PercyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/percy.php' => config_path('percy.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/percy.php', 'percy'
        );

        Browser::macro('percySnapshot', function($name, $options = []) {
            $this->script([
                file_get_contents(config('percy.agent_path')),
                sprintf(
                    "const percyAgentClient = new PercyAgent('%s'); percyAgentClient.snapshot(%s, %s)",
                    config('percy.client_info'),
                    json_encode($name),
                    json_encode($options)
                )
            ]);

            // Gotta give it just a bit to breath. Otherwise we risk the browser disconnecting
            // before Percy loads and has time to take the snapshot.
            $this->pause(100);

            return $this;
        });
    }
}