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
        $component = Livewire::test(Detail::class)
            ->emit('showDepartment', $department->id);

        $component->assertForbidden();
    }

    /** @test */
    public function department_index_component_responds_to_wants_show_department_event()
    {
        $this->withAuthorizedUser();
        $component = Livewire::test(Detail::class)
            ->emit('showDepartment');

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showDepartmentDetailModal');
    }
}
