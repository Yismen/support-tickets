<?php

namespace Dainsys\Support\Feature\Http\Livewire\Reason;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Http\Livewire\Reason\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reason_index_component_requires_authorization()
    {
        $this->actingAs($this->user());

        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function reasons_index_route_requires_authorization()
    {
        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function reasons_index_works_for_authorized_users_and_renders_correct_view()
    {
        $this->actingAs($this->superAdmin());

        $component = Livewire::test(Index::class);

        $component->assertOk();
        $component->assertViewIs('support::livewire.reason.index');
    }

    /** @test */
    public function reasons_index_works_for_super_admin_users_and_renders_correct_view()
    {
        $this->actingAs($this->superAdmin());

        $component = Livewire::test(Index::class);

        $component->assertOk();
    }
}
