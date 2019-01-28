<?php

namespace STS\Percy;

use Laravel\Dusk\Browser;

class Agent
{
    /** @var string */
    protected $jsAgentPath;

    /** @var string */
    protected $clientInfo;

    /**
     * @param $jsAgentPath
     * @param $clientInfo
     */
    public function __construct($jsAgentPath, $clientInfo)
    {
        $this->jsAgentPath = $jsAgentPath;
        $this->clientInfo = $clientInfo;
    }

    /**
     * @param Browser $browser
     * @param $name
     * @param array $options
     *
     * @return Browser
     */
    public function snapshot(Browser $browser, $name, $options = [])
    {
        $browser->script([
            file_get_contents($this->jsAgentPath),
            sprintf(
                "const percyAgentClient = new PercyAgent('%s'); percyAgentClient.snapshot(%s, %s)",
                $this->clientInfo,
                json_encode($name),
                json_encode($options)
            )
        ]);

        // Gotta give it just a bit to breath. Otherwise we risk the browser disconnecting
        // before Percy loads and has time to take the snapshot.
        $browser->pause(100);

        return $browser;
    }
}