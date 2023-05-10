<?php

namespace Dainsys\Support\Feature\Http\Livewire\SupportSuperAdmin;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\SupportSuperAdmin;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Http\Livewire\SupportSuperAdmin\Index;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function support_super_admin_index_component_requires_authorization()
    {
        $this->actingAs($this->user());

        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function support_super_admins_index_route_requires_authorization()
    {
        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function support_super_admins_index_works_for_authorized_users_and_renders_correct_view()
    {
        $this->actingAs($this->supportSuperAdmin());

        $component = Livewire::test(Index::class);

        $component->assertOk();
        $component->assertViewIs('support::livewire.support_super_admin.index');
    }

    /** @test */
    public function support_super_admins_index_works_for_support_super_admin_users_and_renders_correct_view()
    {
        $this->actingAs($this->supportSuperAdmin());
        $users = UserFactory::new()->count(2)->create();

        $component = Livewire::test(Index::class);

        $component->assertViewHas('users');
        $component->assertOk();
    }

    /** @test */
    public function support_super_admins_index_shows_all_users_except_authenticated_user()
    {
        $this->actingAs($this->supportSuperAdmin());
        $user = UserFactory::new()->create();

        $component = Livewire::test(Index::class);

        $component->assertSee($user->name);
        $component->assertDontSee(auth()->user()->name);
    }

    /** @test */
    // public function support_super_admins_index_add_super_users()
    // {
    //     $this->actingAs($this->supportSuperAdmin());
    //     $regular_user = UserFactory::new()->create();

    //     $component = Livewire::test(Index::class, [
    //         'support_super_admins' => SupportSuperAdmin::pluck('user_id')->values()->toArray()
    //     ]);
    //     $component->set('support_super_admins', [0 => $regular_user->id]);

    //     $this->assertDatabaseHas(SupportSuperAdmin::class, ['user_id' => $regular_user->id]);
    // }

    /** @test */
    // public function support_super_admins_index_removes_super_users()
    // {
    //     $this->actingAs($this->supportSuperAdmin());
    //     $regular_user = UserFactory::new()->create();

    //     $component = Livewire::test(Index::class, [
    //         'support_super_admins' => SupportSuperAdmin::pluck('user_id')->values()->toArray()
    //     ]);
    //     $component->set('support_super_admins', [0 => $regular_user->id]);

    //     $this->assertDatabaseHas(SupportSuperAdmin::class, ['user_id' => $regular_user->id]);
    // }
}
