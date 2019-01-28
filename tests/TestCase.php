<?php
namespace STS\VisualTesting\Tests;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser;
use STS\VisualTesting\Agent;
use STS\VisualTesting\VisualTestingServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [VisualTestingServiceProvider::class];
    }

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        // Always need a dummy agent file in place
        file_put_contents(sys_get_temp_dir() . '/percy-agent.js', "");
        config(['visual-testing.percy.agent_path' => sys_get_temp_dir() . '/percy-agent.js']);
    }

    /**
     * @return Agent
     */
    protected function agent()
    {
        return resolve(Agent::class);
    }

    /**
     * @return Browser|\Mockery\MockInterface
     */
    protected function browser()
    {
        $browser = \Mockery::mock(Browser::class);
        $browser->driver = \Mockery::mock(RemoteWebDriver::class);
        $browser->driver->shouldReceive('getCurrentURL')->andReturn($browser::$baseUrl . '/test');

        return $browser;
    }
}