<?php

namespace Dainsys\Support\Feature\Http\Livewire\Department;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Http\Livewire\Department\Detail;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function department_detail_requires_authorization()
    {
        $department = Department::factory()->create();
        $component = Livewire::test(Detail::class);

        $component->emit('showDepartment', $department);

        $component->assertForbidden();
    }

    /** @test */
    public function department_detail_component_grants_access_to_super_admin()
    {
        $this->withSuperUser();
        $department = Department::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showDepartment', $department);

        $component->assertOk();
    }

    /** @test */
    public function department_detail_component_grants_access_to_authorized_users()
    {
        $this->withAuthorizedUser('view departments');
        $department = Department::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showDepartment', $department);

        $component->assertOk();
    }

    /** @test */
    public function department_detail_component_responds_to_wants_show_department_event()
    {
        $this->withAuthorizedUser('view departments');
        $department = Department::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showDepartment', $department);

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showDepartmentDetailModal');
    }
}
