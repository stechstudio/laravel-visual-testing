<?php
return [
    /**
     * This assumes a local NPM install of @percy/agent. If you install it globally, you'll need
     * to set the _absolute_ path to the percy-agent.js file.
     */
    'agent_path' => base_path("node_modules/@percy/agent/dist/public/percy-agent.js"),

    /**
     * Identifies the Percy client implementation.
     * See https://docs.percy.io/docs/build-your-own-sdk#section-percyagent-usage
     */
    'client_info' => 'laravel-dusk',
];