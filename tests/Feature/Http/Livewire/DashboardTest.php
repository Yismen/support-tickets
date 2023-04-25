<?php

namespace Dainsys\Support\Feature\Http\Livewire\SuperAdmin;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\SuperAdmin;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Http\Livewire\Dashboard;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dashboard_component_renders_correctly_for_regular_users()
    {
        $this->actingAs(UserFactory::new()->create());

        $component = Livewire::test(Dashboard::class);

        $component->assertSee('My Tickets');
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_department_agent()
    {
        $user = DepartmentRole::factory()->agent()->create()->user;
        $this->actingAs($user);

        $component = Livewire::test(Dashboard::class);

        $component->assertSee('Department Agent Dashboard');
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_department_admin()
    {
        $user = DepartmentRole::factory()->admin()->create()->user;
        $this->actingAs($user);

        $component = Livewire::test(Dashboard::class);

        $component->assertSee('Department Admin Dashboard');
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_super_admin()
    {
        $user = SuperAdmin::factory()->create()->user;
        $this->actingAs($user);

        $component = Livewire::test(Dashboard::class);

        $component->assertSee('Super Admin Dashboard');
    }
}
