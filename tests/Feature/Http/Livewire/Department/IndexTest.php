<?php

namespace Dainsys\Support\Feature\Http\Livewire\Department;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Http\Livewire\Department\Index;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function department_index_component_requires_authorization()
    {
        $this->actingAs($this->user());

        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function departments_index_route_requires_authorization()
    {
        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function departments_index_works_for_authorized_users_and_renders_correct_view()
    {
        $this->actingAs($this->supportSuperAdmin());

        $component = Livewire::test(Index::class);

        $component->assertOk();
        $component->assertViewIs('support::livewire.department.index');
    }

    /** @test */
    public function departments_index_works_for_support_super_admin_users_and_renders_correct_view()
    {
        $this->actingAs($this->supportSuperAdmin());

        $component = Livewire::test(Index::class);

        $component->assertOk();
    }
}
