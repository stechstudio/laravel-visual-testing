<?php

namespace STS\Percy;

use Laravel\Dusk\Browser;

class Agent
{
    /** @var string */
    protected $jsAgentPath;

    /** @var string */
    protected $clientInfo;

    /** @var string */
    protected $environmentInfo;

    /** @var array */
    protected $options;

    /** @var array */
    protected $generatedNames = [];

    /**
     * @param string $jsAgentPath
     * @param string $clientInfo
     * @param $environmentInfo
     * @param array $options
     */
    public function __construct($jsAgentPath, $clientInfo, $environmentInfo, $options = [])
    {
        $this->jsAgentPath = $jsAgentPath;
        $this->clientInfo = $clientInfo;
        $this->environmentInfo = $environmentInfo;
        $this->options = $options;
    }

    /**
     * @param Browser $browser
     * @param $name
     * @param array $options
     *
     * @return Browser
     */
    public function snapshot(Browser $browser, $name = null, $options = [])
    {
        $browser->script([
            file_get_contents($this->jsAgentPath),
            sprintf(
                "const percyAgentClient = new PercyAgent('%s'); percyAgentClient.snapshot(%s, %s)",
                json_encode([
                    'clientInfo' => $this->clientInfo,
                    'environmentInfo' => $this->environmentInfo
                ]),
                json_encode($this->name($browser, $name)),
                json_encode($this->options($options))
            )
        ]);

        // Gotta give it just a bit to breath. Otherwise we risk the browser disconnecting
        // before Percy loads and has time to take the snapshot.
        $browser->pause(100);

        return $browser;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function options($options = [])
    {
        return array_merge($this->options, $options);
    }

    /**
     * @param Browser $browser
     * @param $name
     *
     * @return string
     */
    protected function name(Browser $browser, $name = null)
    {
        return $name == null
            ? $this->generateName($browser)
            : $name;
    }

    /**
     * @param Browser $browser
     *
     * @return string
     */
    protected function generateName(Browser $browser)
    {
        $name = str_replace($browser::$baseUrl, '', $browser->driver->getCurrentURL());

        $this->generatedNames[$name] = array_get($this->generatedNames, $name, -1) + 1;

        return $this->generatedNames[$name] == 0
            ? $name
            : $name . " (" . $this->generatedNames[$name] . ")";
    }
}