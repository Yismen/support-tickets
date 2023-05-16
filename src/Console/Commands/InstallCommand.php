<?php

namespace Dainsys\Support\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Dainsys Support';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'support:assets', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'support:views']);
        $this->call('vendor:publish', ['--tag' => 'livewire-charts:public']);
        $this->call('migrate');

        if ($this->confirm('Would you like to scafold the auth ui?')) {
            $this->call('ui:auth');
        }

        if ($this->confirm('Would you like to publish the support\'s configuration file?')) {
            $this->call('vendor:publish', ['--tag' => 'support:config']);
        }

        if ($this->confirm('Would you like to publish the support\'s translations files?')) {
            $this->call('vendor:publish', ['--tag' => 'support:translations']);
        }

        $this->info('All done!');

        return 0;
    }
}
