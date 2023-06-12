<?php

namespace Dainsys\Support\Feature\Http\Livewire\Ticket\Index;

use Livewire\Livewire;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Http\Livewire\Ticket\Form;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ticket_form_requires_authorization_to_create_ticket()
    {
        $ticket = Ticket::factory()->create();
        $component = Livewire::test(Form::class);

        $component->emit('createTicket', $ticket);

        $component->assertForbidden();
    }

    /** @test */
    public function ticket_form_requires_authorization_to_update_ticket()
    {
        $ticket = Ticket::factory()->create();
        $component = Livewire::test(Form::class);

        $component->emit('updateTicket', $ticket);

        $component->assertForbidden();
    }

    /** @test */
    public function ticket_form_component_allows_any_user_to_create_ticket()
    {
        $this->actingAs($this->user());
        $ticket = Ticket::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('createTicket', $ticket);

        $component->assertOk();
    }

    /** @test */
    public function ticket_form_allows_users_to_update_their_tickets()
    {
        $this->actingAs($this->user());
        $ticket_1 = Ticket::factory()->create(['created_by' => auth()->user()->id]);
        $ticket_2 = Ticket::factory()->create(['created_by' => UserFactory::new()->create()]);

        $component = Livewire::test(Form::class);
        $component->emit('updateTicket', $ticket_1);

        $component->assertOk();
    }

    /** @test */
    public function ticket_form_prevent_users_from_updating_tickets_for_other_users()
    {
        $this->actingAs($this->user());
        $ticket_1 = Ticket::factory()->create(['created_by' => auth()->user()->id]);
        $ticket_2 = Ticket::factory()->create(['created_by' => UserFactory::new()->create()]);

        $component = Livewire::test(Form::class);
        $component->emit('updateTicket', $ticket_2);

        $component->assertForbidden();
    }

    /** @test */
    public function ticket_form_component_responds_to_create_ticket_event()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $ticket = new Ticket();

        $component = Livewire::test(Form::class);
        $component->emit('createTicket', $ticket);

        $component->assertSet('ticket', $ticket->load('department'));
        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showTicketFormModal');
    }

    /** @test */
    public function ticket_form_component_responds_to_update_ticket_event()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $ticket = Ticket::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateTicket', $ticket);

        $component->assertSet('ticket', $ticket);
        $component->assertSet('editing', true);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showTicketFormModal');
    }

    /** @test */
    public function ticket_form_component_validates_required_fields_to_create_tickets()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $data = ['department_id' => Department::factory()->create()];
        $component = Livewire::test(Form::class)
            ->set('ticket.subject_id', null);

        $component->call('store');
        $component->assertHasErrors(['ticket.subject_id' => 'required']);
    }

    /** @test */
    public function ticket_form_component_validates_required_fields_to_update_tickets()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $component = Livewire::test(Form::class)
            ->set('ticket.department_id', '')
            ->set('ticket.subject_id', '');

        $component->call('update');
        $component->assertHasErrors(['ticket.subject_id' => 'required']);
    }

    /** @test */
    public function ticket_form_component_creates_ticket()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $ticket = Ticket::factory()->make();
        $component = Livewire::test(Form::class);
        $component->emit('createTicket', new Ticket());
        $component->set('ticket.subject_id', $ticket->subject_id);
        $component->set('ticket.description', $ticket->description);
        $component->set('ticket.department_id', $ticket->department_id);

        $component->call('store');

        $component->assertSet('ticket.subject_id', $ticket->subject_id);
        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertEmitted('ticketUpdated');

        $this->assertDatabasehas(Ticket::class, [
            'subject_id' => $ticket->subject_id,
            'description' => $ticket->description,
            'department_id' => $ticket->department_id
        ]);

        // $component->assertSessionHas('success', 'Ticket created!');
    }

    /** @test */
    public function ticket_form_component_updates_ticket()
    {
        $this->actingAs($this->supportSuperAdminUser());
        $ticket = Ticket::factory()->create();

        $component = Livewire::test(Form::class);
        $component->emit('updateTicket', $ticket);
        $component->set('ticket.description', 'new description');

        $component->call('update');

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertEmitted('ticketUpdated');

        $this->assertDatabasehas(Ticket::class, [
            'id' => $ticket->id,
            'description' => 'new description',
        ]);

        // $component->assertSessionHas('success', 'Ticket created!');
    }
}
