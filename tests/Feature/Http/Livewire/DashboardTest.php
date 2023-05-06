<?php

namespace Dainsys\Support\Feature\Http\Livewire\SuperAdmin;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Http\Livewire\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // public function dashboard_component_renders_correctly_for_regular_users()
    // {
    //     $this->actingAs($this->user());

    //     $component = Livewire::test(Dashboard::class);

    //     $component->assertSee('My Tickets');
    // }

    /** @test */
    public function dashboard_component_renders_correctly_for_department_agent()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAgent($department));

        $component = Livewire::test(Dashboard::class);

        $component->assertSee('Department Dashboard');
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_department_admin()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAdmin($department));

        $component = Livewire::test(Dashboard::class);

        $component->assertSee('Department Dashboard');
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_super_admin()
    {
        $this->actingAs($this->superAdmin());

        $component = Livewire::test(Dashboard::class);

        $component->assertSee('Super Admin Dashboard');
    }
}
