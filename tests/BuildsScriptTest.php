<?php

namespace STS\VisualTesting\Tests;

class BuildsScriptTest extends TestCase
{
    public function test_default_config()
    {
        file_put_contents(sys_get_temp_dir() . '/percy-agent.js', "this is percy");
        config([
            'visual-testing.percy' => [
                'agent_path' => sys_get_temp_dir() . '/percy-agent.js',
                'snapshot_options' => [
                    'widths' => [500, 600]
                ]
            ]
        ]);

        $script = $this->agent()->getScript($this->browser());

        $this->assertEquals("this is percy", $script->jsAgent());
        $this->assertEquals([500, 600], $script->snapshotOptions()['widths']);
    }

    public function test_runtime_config()
    {
        config(['visual-testing.percy.snapshot_options' => [ 'widths' => [100, 200] ] ]);

        $script = $this->agent()->getScript($this->browser(), 'snapshot-name', [ 'widths' => [600, 700] ]);

        $this->assertEquals('snapshot-name', $script->snapshotName());
        $this->assertEquals([600, 700], $script->snapshotOptions()['widths']);
    }

    public function test_generated_snapshot_name()
    {
        $script = $this->agent()->getScript($this->browser());
        $this->assertEquals('/test', $script->snapshotName());

        $script = $this->agent()->getScript($this->browser());
        $this->assertEquals('/test (1)', $script->snapshotName());

        $script = $this->agent()->getScript($this->browser());
        $this->assertEquals('/test (2)', $script->snapshotName());
    }
}
