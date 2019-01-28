<?php

namespace STS\VisualTesting;

use Illuminate\Contracts\Support\Arrayable;

class Script implements Arrayable
{
    /** @var string */
    protected $jsAgent;

    /** @var array */
    protected $agentOptions;

    /** @var string */
    protected $snapshotName;

    /** @var array */
    protected $snapshotOptions;

    public function __construct($jsAgent, $agentOptions, $snapshotName, $snapshotOptions)
    {
        $this->jsAgent = $jsAgent;
        $this->agentOptions = $agentOptions;
        $this->snapshotName = $snapshotName;
        $this->snapshotOptions = $snapshotOptions;
    }

    /**
     * @return mixed
     */
    public function jsAgent()
    {
        return $this->jsAgent;
    }

    /**
     * @return mixed
     */
    public function agentOptions()
    {
        return $this->agentOptions;
    }

    /**
     * @return mixed
     */
    public function snapshotName()
    {
        return $this->snapshotName;
    }

    /**
     * @return mixed
     */
    public function snapshotOptions()
    {
        return $this->snapshotOptions;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            $this->jsAgent(),
            sprintf(
                "const percyAgentClient = new PercyAgent('%s'); percyAgentClient.snapshot(%s, %s);",
                json_encode($this->agentOptions()),
                json_encode($this->snapshotName()),
                json_encode($this->snapshotOptions())
            )
        ];
    }
}