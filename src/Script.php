<?php

namespace STS\Percy;

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
    public function getJsAgent()
    {
        return $this->jsAgent;
    }

    /**
     * @return mixed
     */
    public function getAgentOptions()
    {
        return $this->agentOptions;
    }

    /**
     * @return mixed
     */
    public function getSnapshotName()
    {
        return $this->snapshotName;
    }

    /**
     * @return mixed
     */
    public function getSnapshotOptions()
    {
        return $this->snapshotOptions;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            $this->getJsAgent(),
            sprintf(
                "const percyAgentClient = new PercyAgent('%s'); percyAgentClient.snapshot(%s, %s);",
                json_encode($this->getAgentOptions()),
                json_encode($this->getSnapshotName()),
                json_encode($this->getSnapshotOptions())
            )
        ];
    }


}