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
    public function recipient_form_requires_authorization_to_create()
    {
        $recipient = Department::factory()->create();
        $component = Livewire::test(Form::class)
            ->emit('createDepartment', $recipient->id);

        $component->assertForbidden();
    }

    /** @test */
    public function recipient_form_requires_authorization_to_update()
    {
        $recipient = Department::factory()->create();
        $component = Livewire::test(Form::class)
            ->emit('updateDepartment', $recipient->id);

        $component->assertForbidden();
    }

    /** @test */
    public function recipient_index_component_responds_to_wants_create_recipient_event()
    {
        $this->withAuthorizedUser();
        $component = Livewire::test(Form::class)
            ->emit('createDepartment');

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showDepartmentFormModal');
    }

    /** @test */
    // public function recipient_index_component_responds_to_wants_edit_recipient_event()
    // {
    //     $this->withAuthorizedUser();
    //     $component = Livewire::test(Form::class)
    //         ->emit('updateDepartment');

    //     $component->assertSet('editing', true);
    //     $component->assertDispatchedBrowserEvent('closeAllModals');
    //     $component->assertDispatchedBrowserEvent('showDepartmentFormModal');
    // }

    /** @test */
    // public function recipient_index_component_create_new_record()
    // {
    //     $this->withAuthorizedUser();
    //     $data = ['name' => 'New Department', 'email' => 'new email'];
    //     $component = Livewire::test(Form::class)
    //         ->set('recipient', new Department($data));

    //     $component->call('store');
    //     $component->assertSet('editing', false);
    //     $component->assertDispatchedBrowserEvent('closeAllModals');
    //     $component->assertEmitted('recipientUpdated');

    //     $this->assertDatabaseHas(supportTableName('recipients'), $data);
    // }

    /** @test */
    // public function recipient_index_component_update_record()
    // {
    //     $this->withAuthorizedUser();
    //     $recipient = Department::factory()->create(['name' => 'New Department', 'email' => 'New email']);
    //     $component = Livewire::test(Form::class)
    //         ->set('recipient', $recipient)
    //         ->set('recipient.name', 'Updated Department')
    //         ->set('recipient.email', 'Updated email');

    //     $component->call('update');

    //     $component->assertSet('editing', false);
    //     $component->assertDispatchedBrowserEvent('closeAllModals');
    //     $component->assertEmitted('recipientUpdated');
    //     $this->assertDatabaseHas(supportTableName('recipients'), ['name' => 'Updated Department', 'email' => 'Updated email']);
    // }

    /** @test */
    // public function recipient_index_component_validates_required_fields()
    // {
    //     $this->withAuthorizedUser();
    //     $data = ['name' => ''];
    //     $component = Livewire::test(Form::class)
    //         ->set('recipient', new Department($data));

    //     $component->call('store');
    //     $component->assertHasErrors(['recipient.name' => 'required']);

    //     $component->call('update');
    //     $component->assertHasErrors(['recipient.name' => 'required']);
    // }

    /** @test */
    // public function recipient_index_component_validates_unique_fields()
    // {
    //     $this->withAuthorizedUser();
    //     $data = ['name' => 'New Name'];
    //     $recipient = Department::factory()->create($data);

    //     $component = Livewire::test(Form::class)
    //         ->set('recipient.name', $recipient->name);

    //     $component->call('store');
    //     $component->assertHasErrors(['recipient.name' => 'unique']);

    //     $component->set('recipient', $recipient)->call('update');
    //     $component->assertHasNoErrors(['recipient.name' => 'unique']);
    // }
}
