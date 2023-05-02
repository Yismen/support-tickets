<?php

namespace Dainsys\Support\Feature\Http\Livewire\Reason;

use Livewire\Livewire;
use Dainsys\Support\Models\Reason;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Http\Livewire\Reason\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reason_form_requires_authorization_to_create_reason()
    {
        $reason = Reason::factory()->create();
        $component = Livewire::test(Form::class);

        $component->emit('createReason', $reason);

        $component->assertForbidden();
    }

    /** @test */
    public function reason_form_requires_authorization_to_update_reason()
    {
        $reason = Reason::factory()->create();
        $component = Livewire::test(Form::class);

        $component->emit('updateReason', $reason);

        $component->assertForbidden();
    }

    /** @test */
    public function reason_form_component_grants_access_to_super_admin_to_create_reason()
    {
        $this->actingAs($this->superAdmin());
        $reason = Reason::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('createReason', $reason);

        $component->assertOk();
    }

    /** @test */
    public function reason_form_component_grants_access_to_super_admin_to_update_reason()
    {
        $this->actingAs($this->superAdmin());
        $reason = Reason::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateReason', $reason);

        $component->assertOk();
    }

    /** @test */
    public function reason_form_component_grants_access_to_authorized_users_to_create_reason()
    {
        $this->actingAs($this->superAdmin());
        $reason = Reason::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('createReason', $reason);

        $component->assertOk();
    }

    /** @test */
    public function reason_form_component_grants_access_to_authorized_users_to_update_reason()
    {
        $this->actingAs($this->superAdmin());
        $reason = Reason::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateReason', $reason);

        $component->assertOk();
    }

    /** @test */
    public function reason_form_component_responds_to_create_reason_event()
    {
        $this->actingAs($this->superAdmin());
        $reason = new Reason();

        $component = Livewire::test(Form::class);
        $component->emit('createReason', $reason);

        $component->assertSet('reason', $reason);
        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showReasonFormModal');
    }

    /** @test */
    public function reason_form_component_responds_to_update_reason_event()
    {
        $this->actingAs($this->superAdmin());
        $reason = Reason::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateReason', $reason);

        $component->assertSet('reason', $reason);
        $component->assertSet('editing', true);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showReasonFormModal');
    }

    /** @test */
    public function reason_form_component_validates_required_fields_to_create_reasons()
    {
        $this->actingAs($this->superAdmin());
        $data = ['name' => '', 'department_id' => Department::factory()->create()];
        $component = Livewire::test(Form::class)
            ->set('reason', new Reason($data));

        $component->call('store');
        
        $component->assertHasErrors(['reason.name' => 'required']);
    }

    /** @test */
    public function reason_form_component_validates_required_fields_to_update_reasons()
    {
        $this->actingAs($this->superAdmin());
        $component = Livewire::test(Form::class)
            ->set('reason', Reason::factory()->create())
            ->set('reason.name', '');

        $component->call('update');
        $component->assertHasErrors(['reason.name' => 'required']);
    }

    /** @test */
    public function reason_form_component_validates_unique_fields_to_create_reasons()
    {
        $reason = Reason::factory()->create();
        $this->actingAs($this->superAdmin());
        $data = ['name' => $reason->name];
        $component = Livewire::test(Form::class)
            ->set('reason', new Reason($data));

        $component->call('store');
        $component->assertHasErrors(['reason.name' => 'unique']);
    }

    /** @test */
    public function reason_form_component_validates_unique_fields_to_update_reasons_except_on_self_record()
    {
        $reason_1 = Reason::factory()->create();
        $reason_2 = Reason::factory()->create();
        $this->actingAs($this->superAdmin());
        $component = Livewire::test(Form::class);

        $component->set('reason', $reason_1);

        $component->set('reason.name', $reason_2->name);

        $component->call('update');
        $component->assertHasErrors(['reason.name' => 'unique']);

        $component->set('reason.name', $reason_1->name);
        $component->assertHasNoErrors(['reason.name' => 'unique']);
    }

    /** @test */
    public function reason_form_component_creates_reason()
    {
        $this->actingAs($this->superAdmin());
        $reason = Reason::factory()->make();
        $component = Livewire::test(Form::class);
        $component->emit('createReason', new Reason());
        $component->set('reason.name', $reason->name);
        $component->set('reason.priority', $reason->priority);
        $component->set('reason.department_id', $reason->department_id);

        $component->call('store');

        $component->assertSet('reason.name', $reason->name);
        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertEmitted('reasonUpdated');

        $this->assertDatabasehas(Reason::class, [
            'name' => $reason->name,
            'department_id' => $reason->department_id
        ]);

        // $component->assertSessionHas('success', 'Reason created!');
    }

    /** @test */
    public function reason_form_component_updates_reason()
    {
        $this->actingAs($this->superAdmin());
        $reason = Reason::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateReason', $reason);
        $component->set('reason.name', 'new name');

        $component->call('update');

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertEmitted('reasonUpdated');

        $this->assertDatabasehas(Reason::class, [
            'id' => $reason->id,
            'name' => 'new name',
        ]);

        // $component->assertSessionHas('success', 'Reason created!');
    }
}
