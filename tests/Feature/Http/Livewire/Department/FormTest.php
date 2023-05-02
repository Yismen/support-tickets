<?php

namespace Dainsys\Support\Feature\Http\Livewire\Department;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Http\Livewire\Department\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function department_form_requires_authorization_to_create_department()
    {
        $department = Department::factory()->create();
        $component = Livewire::test(Form::class);

        $component->emit('createDepartment', $department);

        $component->assertForbidden();
    }

    /** @test */
    public function department_form_requires_authorization_to_update_department()
    {
        $department = Department::factory()->create();
        $component = Livewire::test(Form::class);

        $component->emit('updateDepartment', $department);

        $component->assertForbidden();
    }

    /** @test */
    public function department_form_component_grants_access_to_super_admin_to_create_department()
    {
        $this->actingAs($this->superAdmin());
        $department = Department::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('createDepartment', $department);

        $component->assertOk();
    }

    /** @test */
    public function department_form_component_grants_access_to_super_admin_to_update_department()
    {
        $this->actingAs($this->superAdmin());
        $department = Department::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateDepartment', $department);

        $component->assertOk();
    }

    /** @test */
    public function department_form_component_grants_access_to_authorized_users_to_create_department()
    {
        $this->actingAs($this->superAdmin());
        $department = Department::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('createDepartment', $department);

        $component->assertOk();
    }

    /** @test */
    public function department_form_component_grants_access_to_authorized_users_to_update_department()
    {
        $this->actingAs($this->superAdmin());
        $department = Department::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateDepartment', $department);

        $component->assertOk();
    }

    /** @test */
    public function department_form_component_responds_to_create_department_event()
    {
        $this->actingAs($this->superAdmin());
        $department = new Department();

        $component = Livewire::test(Form::class);
        $component->emit('createDepartment', $department);

        $component->assertSet('department', $department);
        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showDepartmentFormModal');
    }

    /** @test */
    public function department_form_component_responds_to_update_department_event()
    {
        $this->actingAs($this->superAdmin());
        $department = Department::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateDepartment', $department);

        $component->assertSet('department', $department);
        $component->assertSet('editing', true);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showDepartmentFormModal');
    }

    /** @test */
    public function department_form_component_validates_required_fields_to_create_departments()
    {
        $this->actingAs($this->superAdmin());
        $data = ['name' => ''];
        $component = Livewire::test(Form::class)
            ->set('department', new Department($data));

        $component->call('store');
        $component->assertHasErrors(['department.name' => 'required']);
    }

    /** @test */
    public function department_form_component_validates_required_fields_to_update_departments()
    {
        $this->actingAs($this->superAdmin());
        $component = Livewire::test(Form::class)
            ->set('department', Department::factory()->create())
            ->set('department.name', '');

        $component->call('update');
        $component->assertHasErrors(['department.name' => 'required']);
    }

    /** @test */
    public function department_form_component_validates_unique_fields_to_create_departments()
    {
        $department = Department::factory()->create();

        $this->actingAs($this->superAdmin());
        $data = ['name' => $department->name];
        $component = Livewire::test(Form::class)
            ->set('department', new Department($data));

        $component->call('store');
        $component->assertHasErrors(['department.name' => 'unique']);
    }

    /** @test */
    public function department_form_component_validates_unique_fields_to_update_departments_except_on_self_record()
    {
        $department_1 = Department::factory()->create();
        $department_2 = Department::factory()->create();

        $this->actingAs($this->superAdmin());
        $component = Livewire::test(Form::class);

        $component->set('department', $department_1);

        $component->set('department.name', $department_2->name);

        $component->call('update');
        $component->assertHasErrors(['department.name' => 'unique']);

        $component->set('department.name', $department_1->name);
        $component->assertHasNoErrors(['department.name' => 'unique']);
    }

    /** @test */
    public function department_form_component_creates_department()
    {
        $this->actingAs($this->superAdmin());
        $department = Department::factory()->make();
        $component = Livewire::test(Form::class);
        $component->emit('createDepartment', new Department());
        $component->set('department.name', $department->name);
        $component->set('department.ticket_prefix', $department->ticket_prefix);

        $component->call('store');

        $component->assertSet('department.name', $department->name);
        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertEmitted('departmentUpdated');

        $this->assertDatabasehas(Department::class, [
            'name' => $department->name
        ]);

        // $component->assertSessionHas('success', 'Department created!');
    }

    /** @test */
    public function department_form_component_updates_department()
    {
        $this->actingAs($this->superAdmin());
        $department = Department::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateDepartment', $department);
        $component->set('department.name', 'new name');

        $component->call('update');

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertEmitted('departmentUpdated');

        $this->assertDatabasehas(Department::class, [
            'id' => $department->id,
            'name' => 'new name',
        ]);

        // $component->assertSessionHas('success', 'Department created!');
    }
}
