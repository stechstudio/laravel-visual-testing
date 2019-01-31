<?php
return [

    'percy' => [
        /**
         * This assumes a local NPM install of @percy/agent. If you install it globally, you'll need
         * to set the _absolute_ path to the percy-agent.js file.
         */
        'agent_path' => env('PERCY_AGENT_PATH', base_path("node_modules/@percy/agent/dist/public/percy-agent.js")),

        /**
         * A string identifying the operating system, browser and other useful environment information, e.g. Windows; chrome.
         */
        'environment_info' => env('PERCY_ENVIRONMENT_INFO', 'laravel/' . Illuminate\Foundation\Application::VERSION),

        /**
         * An array of options to use by default with all your snapshots.
         * Can include:
         * - `widths` : An array of integers representing the browser widths at which you want to take snapshots.
         * - `minHeight` : An integer specifying the minimum height of the resulting snapshot, in pixels. Defaults to 1024px.
         */
        'snapshot_options' => [],
    ]
];
