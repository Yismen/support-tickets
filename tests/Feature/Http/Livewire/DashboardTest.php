<?php

namespace Dainsys\Support\Feature\Http\Livewire\SupportSuperAdmin;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Http\Livewire\Dashboard\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dashboard_component_renders_correctly_for_regular_users()
    {
        $this->actingAs($this->user());

        $component = Livewire::test(Index::class);

        $component->assertRedirect(route('support.my_tickets'));
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_department_agent()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAgentUser($department));

        $component = Livewire::test(Index::class);

        $component->assertOk();
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_department_admin()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAdminUser($department));

        $component = Livewire::test(Index::class);

        $component->assertOk();
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_support_super_admin()
    {
        $this->actingAs($this->supportSuperAdminUser());

        $component = Livewire::test(Index::class);

        $component->assertOk();
    }
}
