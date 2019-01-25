<?php
namespace STS\Percy;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class PercyServiceProvider extends ServiceProvider
{
    public function register()
    {
        Browser::macro('percySnapshot', function($name, $options = []) {
            $this->script([
                file_get_contents(base_path("node_modules/@percy/agent/dist/public/percy-agent.js")),
                sprintf(
                    "const percyAgentClient = new PercyAgent('laravel-dusk'); percyAgentClient.snapshot(%s, %s)",
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