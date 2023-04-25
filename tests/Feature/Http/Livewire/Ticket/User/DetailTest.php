<?php

namespace Dainsys\Support\Feature\Http\Livewire\Ticket\User;

use Livewire\Livewire;
use Dainsys\Support\Models\Reason;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Http\Livewire\Reason\Detail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reason_detail_requires_authorization()
    {
        $reason = Reason::factory()->create();
        $component = Livewire::test(Detail::class);

        $component->emit('showReason', $reason);

        $component->assertForbidden();
    }

    /** @test */
    public function reason_detail_component_grants_access_to_super_admin()
    {
        $this->withSuperUser();
        $reason = Reason::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showReason', $reason);

        $component->assertOk();
    }

    /** @test */
    public function reason_detail_component_grants_access_to_authorized_users()
    {
        $this->withAuthorizedUser('view reasons');
        $reason = Reason::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showReason', $reason);

        $component->assertOk();
    }

    /** @test */
    public function reason_detail_component_responds_to_wants_show_reason_event()
    {
        $this->withAuthorizedUser('view reasons');
        $reason = Reason::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showReason', $reason);

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showReasonDetailModal');
    }
}
