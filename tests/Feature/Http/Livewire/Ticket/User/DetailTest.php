<?php

namespace Dainsys\Support\Feature\Http\Livewire\Ticket\User;

use Livewire\Livewire;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Http\Livewire\Ticket\User\Detail;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ticket_detail_requires_authorization()
    {
        // $this->actingAs($this->user());

        $ticket = Ticket::factory()->create();
        $component = Livewire::test(Detail::class);

        // $component->emit('showTicket', $ticket);

        $component->assertForbidden();
    }

    /** @test */
    public function ticket_detail_component_grants_access_to_support_super_admin()
    {
        $this->actingAs($this->supportSuperAdmin());
        $ticket = Ticket::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showTicket', $ticket);

        $component->assertOk();
    }

    /** @test */
    public function ticket_detail_component_grants_access_to_authorized_users()
    {
        $this->actingAs($this->supportSuperAdmin());
        $ticket = Ticket::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showTicket', $ticket);

        $component->assertOk();
    }

    /** @test */
    public function ticket_detail_component_responds_to_wants_show_ticket_event()
    {
        $this->actingAs($this->supportSuperAdmin());
        $ticket = Ticket::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showTicket', $ticket);

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showTicketDetailModal');
    }
}
