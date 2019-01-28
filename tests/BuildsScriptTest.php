<?php

namespace STS\Percy\Tests;

class BuildsScriptTest extends TestCase
{
    public function test_default_config()
    {
        file_put_contents(sys_get_temp_dir() . '/percy-agent.js', "this is percy");
        config([
            'percy' => [
                'agent_path' => sys_get_temp_dir() . '/percy-agent.js',
                'client_info' => 'testing',
                'environment_info' => 'phpunit',
                'snapshot_options' => [
                    'widths' => [500, 600]
                ]
            ]
        ]);

        $script = $this->agent()->getScript($this->browser());

        $this->assertEquals("this is percy", $script->getJsAgent());
        $this->assertEquals("testing", $script->getAgentOptions()['clientInfo']);
        $this->assertEquals("phpunit", $script->getAgentOptions()['environmentInfo']);
        $this->assertEquals([500, 600], $script->getSnapshotOptions()['widths']);
    }

    public function test_runtime_config()
    {
        config(['percy.snapshot_options' => [ 'widths' => [100, 200] ] ]);

        $script = $this->agent()->getScript($this->browser(), 'snapshot-name', [ 'widths' => [600, 700] ]);

        $this->assertEquals('snapshot-name', $script->getSnapshotName());
        $this->assertEquals([600, 700], $script->getSnapshotOptions()['widths']);
    }

    public function test_generated_snapshot_name()
    {
        $script = $this->agent()->getScript($this->browser());
        $this->assertEquals('/test', $script->getSnapshotName());

        $script = $this->agent()->getScript($this->browser());
        $this->assertEquals('/test (1)', $script->getSnapshotName());

        $script = $this->agent()->getScript($this->browser());
        $this->assertEquals('/test (2)', $script->getSnapshotName());
    }
}