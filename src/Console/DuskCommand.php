<?php
namespace STS\Percy\Console;

use Laravel\Dusk\Console\DuskCommand as BaseDuskCommand;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\RuntimeException;

class DuskCommand extends BaseDuskCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dusk 
                            {--without-tty : Disable output to TTY}
                            {--without-percy : Disable percy snapshots}';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->purgeScreenshots();

        $this->purgeConsoleLogs();

        return $this->withDuskEnvironment(function () {
            $process = $this->process();

            try {
                $process->setTty(! $this->option('without-tty'));
            } catch (RuntimeException $e) {
                $this->output->writeln('Warning: '.$e->getMessage());
            }

            return $process->run(function ($type, $line) {
                $this->output->write($line);
            });
        });
    }

    /**
     * Prepend the Percy token and wrapper command
     *
     * @return array
     */
    protected function binary()
    {
        if($this->option('without-percy')) {
            return parent::binary();
        }

        return array_merge([
            'npx',
            'percy',
            'exec',
            '--'
        ], parent::binary());
    }

    /**
     * @return array
     */
    protected function env()
    {
        return [
            'PERCY_TOKEN' => env('PERCY_TOKEN')
        ];
    }

    /**
     * @return Process
     */
    protected function process()
    {
        return (new Process(array_merge(
            $this->binary(), $this->phpunitArguments($this->processOptions())
        ), null, $this->env()))->setTimeout(null);
    }

    /**
     * @return array
     */
    protected function processOptions()
    {
        return array_diff(
            array_slice($_SERVER['argv'], 2),
            ['--without-tty', '--without-percy']
        );
    }
}