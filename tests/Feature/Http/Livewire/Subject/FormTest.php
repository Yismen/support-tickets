<?php

namespace Dainsys\Support\Feature\Http\Livewire\Subject;

use Livewire\Livewire;
use Dainsys\Support\Models\Subject;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Http\Livewire\Subject\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function subject_form_requires_authorization_to_create_subject()
    {
        $subject = Subject::factory()->create();
        $component = Livewire::test(Form::class);

        $component->emit('createSubject', $subject);

        $component->assertForbidden();
    }

    /** @test */
    public function subject_form_requires_authorization_to_update_subject()
    {
        $subject = Subject::factory()->create();
        $component = Livewire::test(Form::class);

        $component->emit('updateSubject', $subject);

        $component->assertForbidden();
    }

    /** @test */
    public function subject_form_component_grants_access_to_support_super_admin_to_create_subject()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('createSubject', $subject);

        $component->assertOk();
    }

    /** @test */
    public function subject_form_component_grants_access_to_support_super_admin_to_update_subject()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateSubject', $subject);

        $component->assertOk();
    }

    /** @test */
    public function subject_form_component_grants_access_to_authorized_users_to_create_subject()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('createSubject', $subject);

        $component->assertOk();
    }

    /** @test */
    public function subject_form_component_grants_access_to_authorized_users_to_update_subject()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateSubject', $subject);

        $component->assertOk();
    }

    /** @test */
    public function subject_form_component_responds_to_create_subject_event()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $subject = new Subject();

        $component = Livewire::test(Form::class);
        $component->emit('createSubject', $subject);

        $component->assertSet('subject', $subject);
        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showSubjectFormModal');
    }

    /** @test */
    public function subject_form_component_responds_to_update_subject_event()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateSubject', $subject);

        $component->assertSet('subject', $subject);
        $component->assertSet('editing', true);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showSubjectFormModal');
    }

    /** @test */
    public function subject_form_component_validates_required_fields_to_create_subjects()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $data = ['name' => '', 'department_id' => Department::factory()->create()];
        $component = Livewire::test(Form::class)
            ->set('subject', new Subject($data));

        $component->call('store');

        $component->assertHasErrors(['subject.name' => 'required']);
    }

    /** @test */
    public function subject_form_component_validates_required_fields_to_update_subjects()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $component = Livewire::test(Form::class)
            ->set('subject', Subject::factory()->create())
            ->set('subject.name', '');

        $component->call('update');
        $component->assertHasErrors(['subject.name' => 'required']);
    }

    /** @test */
    public function subject_form_component_validates_unique_fields_to_create_subjects()
    {
        $subject = Subject::factory()->create();
        $this->actingAs($this->supportSuperAdminUser());
        $data = ['name' => $subject->name];
        $component = Livewire::test(Form::class)
            ->set('subject', new Subject($data));

        $component->call('store');
        $component->assertHasErrors(['subject.name' => 'unique']);
    }

    /** @test */
    public function subject_form_component_validates_unique_fields_to_update_subjects_except_on_self_record()
    {
        $subject_1 = Subject::factory()->create();
        $subject_2 = Subject::factory()->create();
        $this->actingAs($this->supportSuperAdminUser());
        $component = Livewire::test(Form::class);

        $component->set('subject', $subject_1);

        $component->set('subject.name', $subject_2->name);

        $component->call('update');
        $component->assertHasErrors(['subject.name' => 'unique']);

        $component->set('subject.name', $subject_1->name);
        $component->assertHasNoErrors(['subject.name' => 'unique']);
    }

    /** @test */
    public function subject_form_component_creates_subject()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $subject = Subject::factory()->make();
        $component = Livewire::test(Form::class);
        $component->emit('createSubject', new Subject());
        $component->set('subject.name', $subject->name);
        $component->set('subject.priority', $subject->priority);
        $component->set('subject.department_id', $subject->department_id);

        $component->call('store');

        $component->assertSet('subject.name', $subject->name);
        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertEmitted('subjectUpdated');

        $this->assertDatabasehas(Subject::class, [
            'name' => $subject->name,
            'department_id' => $subject->department_id
        ]);

        // $component->assertSessionHas('success', 'Subject created!');
    }

    /** @test */
    public function subject_form_component_updates_subject()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateSubject', $subject);
        $component->set('subject.name', 'new name');

        $component->call('update');

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertEmitted('subjectUpdated');

        $this->assertDatabasehas(Subject::class, [
            'id' => $subject->id,
            'name' => 'new name',
        ]);

        // $component->assertSessionHas('success', 'Subject created!');
    }
}
