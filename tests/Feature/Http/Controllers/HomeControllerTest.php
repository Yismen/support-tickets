<?php

namespace Dainsys\Support\Feature\Http\Controllers;

use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function regular_users_cant_view_dashboard()
    {
        $this->actingAs($this->user());

        $response = $this->get(route('support.home'));

        $response->assertRedirect(route('support.my_tickets'));
    }

    /** @test */
    public function agents_are_redirected_to_dashbard()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAgentUser($department));

        $response = $this->get(route('support.home'));

        $response->assertRedirect(route('support.dashboard'));
    }

    /** @test */
    public function agents_can_view_regular_department_dashbard()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAgentUser($department));

        $response = $this->get(route('support.dashboard'));

        $response->assertSee('Department Dashboard');
        $response->assertSee('You Are Agent');
        $response->assertDontSee('You Are Admin');
        $response->assertDontSee('Super Admin Dashboard');
    }

    /** @test */
    public function admins_can_view_department_dashbard()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAdminUser($department));

        $response = $this->get(route('support.dashboard'));

        $response->assertSee('Department Dashboard');
        $response->assertSee('You Are Admin');
        $response->assertDontSee('You Are Agent');
        $response->assertDontSee('Super Admin Dashboard');
    }

    /** @test */
    public function super_admins_can_view_correct_dashbard()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->supportSuperAdminUser());

        $response = $this->get(route('support.dashboard'));

        $response->assertSee('Super Admin Dashboard');
        $response->assertDontSee('You Are Admin');
        $response->assertDontSee('You Are Agent');
    }
}
