<?php

namespace Dainsys\Support\Tests;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Dainsys\Support\Tests\Models\User;
use Spatie\Permission\Models\Permission;
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
            \Spatie\Permission\PermissionServiceProvider::class,
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
        $permission = Permission::create(['name' => $permission]);

        $user->givePermissionTo($permission);

        $this->actingAs($user);
    }

    protected function withSuperUser()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'support super admin']);

        $user->assignRole($role);

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
