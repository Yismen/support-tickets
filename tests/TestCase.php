<?php

namespace Dainsys\Support\Tests;

use Illuminate\Support\Facades\Auth;
use Dainsys\Support\Tests\Models\User;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Auth::routes();

        $this->withoutMix();
    }

    /**
     * Load the command service provider.
     *
     * @param  \Illuminate\Foundationlication $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Laravel\Ui\UiServiceProvider::class,
            \Livewire\LivewireServiceProvider::class,
            \Flasher\Laravel\FlasherServiceProvider::class,
            \OwenIt\Auditing\AuditingServiceProvider::class,
            \Rappasoft\LaravelLivewireTables\LaravelLivewireTablesServiceProvider::class,

            \Dainsys\Support\SupportServiceProvider::class,
        ];
    }

    /**
 * Define database migrations.
 *
 * @return void
 */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }

    protected function withAuthenticatedUser()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
    }

    protected function withAuthorizedUser(string $permission)
    {
        $user = User::factory()->create();
        $super_user = $user->superAdmin()->create();

        $this->actingAs($user);
    }

    protected function withSuperUser()
    {
        $user = User::factory()->create();
        $super_user = $user->superAdmin()->create();

        $this->actingAs($user);
    }

    public function withoutAuthorizedUser()
    {
        $user = User::factory()->create([
            'email' => 'some@random.com',
            'name' => 'Some Random'
        ]);

        $this->actingAs($user);
    }
}
