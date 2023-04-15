<?php

namespace Dainsys\Support\Tests\Feature\Console\Commands;

use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Console\Commands\InstallCommand;

class InstallCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // public function install_command_creates_site()
    // {
    //     $this->artisan(InstallCommand::class)
    //         ->expectsConfirmation('Would you like to run the support\'s migrations now?', 'no')
    //         ->expectsConfirmation('Would you like to publish the support\'s configuration file?', 'no')
    //         ->expectsConfirmation('Would you like to publish the support\'s translation file?', 'no')
    //         ->expectsConfirmation('Would you like to publish the support\'s view files?', 'no')
    //         ->assertSuccessful();
    // }
}
