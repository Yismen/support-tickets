<?php

namespace Dainsys\Support\Tests\Feature\Console\Commands;

use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Console\Commands\InstallCommand;

class InstallCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function install_command_creates_files()
    {
        $this->artisan(InstallCommand::class)
            ->expectsConfirmation('Would you like to scafold the auth ui?', 'no')
            ->expectsConfirmation('Would you like to publish the support\'s configuration file?', 'yes')
            ->expectsConfirmation('Would you like to publish the support\'s translations files?', 'yes')
            ->assertSuccessful();

        $this->assertFileExists(config_path('support.php'));
        $this->assertFileExists(resource_path('views\vendor\dainsys\support\layouts\app.blade.php'));
    }
}
