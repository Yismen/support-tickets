<?php

namespace Dainsys\Support\Feature\Http\Livewire\Ticket\User;

use Livewire\Livewire;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Http\Livewire\Ticket\User\Index;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_tickets_index_component_requires_authentication()
    {
        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function users_tickets_index_works_for_authorized_users_and_renders_correct_view()
    {
        $this->actingAs($this->user());

        $component = Livewire::test(Index::class);

        $component->assertOk();
        $component->assertViewIs('support::livewire.ticket.user.index');
        $component->assertSeeLivewire('support::ticket.user.table');
    }

    /** @test */
    public function user_tickets_index_only_shows_tickets_for_current_user()
    {
        $this->actingAs($this->user());
        $ticket_for_user = Ticket::factory()->create(['created_by' => auth()->user()->id]);
        $ticket_for_oter_user = Ticket::factory()->create(['created_by' => UserFactory::new()->create()]);

        $component = Livewire::test(Index::class);

        $component->assertSee($ticket_for_user->short_description);
        $component->assertDontSee($ticket_for_oter_user->short_description);
    }
}
