<?php

namespace Dainsys\Support\Tests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Database\Factories\UserFactory;
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
            \Asantibanez\LivewireCharts\LivewireChartsServiceProvider::class,
            \Dainsys\Support\SupportServiceProvider::class,
            \Maatwebsite\Excel\ExcelServiceProvider::class,
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

    protected function supportSuperAdminUser(): User
    {
        $user = $this->user();
        $user->supportSuperAdmin()->create();

        return $user;
    }

    protected function departmentAdminUser(Department $department): User
    {
        $user = $this->user();

        $user->departmentRole()->create(['department_id' => $department->id, 'role' => DepartmentRolesEnum::Admin->value]);

        return $user;
    }

    protected function departmentAgentUser(Department $department): User
    {
        $user = $this->user();

        $user->departmentRole()->create(['department_id' => $department->id, 'role' => DepartmentRolesEnum::Agent->value]);

        return $user;
    }

    protected function user(array $attributes = []): User
    {
        return UserFactory::new()->create($attributes);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
