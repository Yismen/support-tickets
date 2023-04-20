<?php

namespace Dainsys\Support\Tests\Feature\Console\Commands;

use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\SuperAdmin;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Console\Commands\CreateSuperUser;

class CreateSuperUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_super_user_command_creates_files()
    {
        $this->artisan(CreateSuperUser::class)
            ->expectsConfirmation('This command will convert user into a SUPER USER, capable of handling the whole application. Are you sure?', 'no')
            ->assertFailed();
    }

    /** @test */
    public function create_super_user_throws_exception_if_invalid_user_email_passed()
    {
        $this->artisan(CreateSuperUser::class)
            ->expectsConfirmation('This command will convert user into a SUPER USER, capable of handling the whole application. Are you sure?', 'yes')
            ->expectsQuestion('Please provide the email of the user to be made super admin!', 'invalid user')
            ->assertFailed();
    }

    /** @test */
    public function create_super_user_creates_super_user()
    {
        $user = UserFactory::new()->create();

        $this->artisan(CreateSuperUser::class)
            ->expectsConfirmation('This command will convert user into a SUPER USER, capable of handling the whole application. Are you sure?', 'yes')
            ->expectsQuestion('Please provide the email of the user to be made super admin!', $user->email)
            ->assertSuccessful();

        $this->assertDatabaseHas(SuperAdmin::class, ['user_id' => $user->id]);
    }
}
