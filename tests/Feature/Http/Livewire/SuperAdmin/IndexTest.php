<?php

namespace Dainsys\Support\Feature\Http\Livewire\SuperAdmin;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\SuperAdmin;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Http\Livewire\SuperAdmin\Index;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function super_admin_index_component_requires_authorization()
    {
        $this->withoutAuthorizedUser();

        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function super_admins_index_route_requires_authorization()
    {
        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function super_admins_index_works_for_authorized_users_and_renders_correct_view()
    {
        $this->withAuthorizedUser('view super admins');

        $component = Livewire::test(Index::class);

        $component->assertOk();
        $component->assertViewIs('support::livewire.super_admin.index');
    }

    /** @test */
    public function super_admins_index_works_for_super_admin_users_and_renders_correct_view()
    {
        $this->withSuperUser();
        $users = UserFactory::new()->count(2)->create();

        $component = Livewire::test(Index::class);

        $component->assertViewHas('users');
        $component->assertOk();
    }

    /** @test */
    public function super_admins_index_shows_all_users_except_authenticated_user()
    {
        $this->withSuperUser();
        $user = UserFactory::new()->create();

        $component = Livewire::test(Index::class);

        $component->assertSee($user->name);
        $component->assertDontSee(auth()->user()->name);
    }

    /** @test */
    // public function super_admins_index_add_super_users()
    // {
    //     $this->withSuperUser();
    //     $regular_user = UserFactory::new()->create();

    //     $component = Livewire::test(Index::class, [
    //         'super_admins' => SuperAdmin::pluck('user_id')->values()->toArray()
    //     ]);
    //     $component->set('super_admins', [0 => $regular_user->id]);

    //     $this->assertDatabaseHas(SuperAdmin::class, ['user_id' => $regular_user->id]);
    // }

    /** @test */
    // public function super_admins_index_removes_super_users()
    // {
    //     $this->withSuperUser();
    //     $regular_user = UserFactory::new()->create();

    //     $component = Livewire::test(Index::class, [
    //         'super_admins' => SuperAdmin::pluck('user_id')->values()->toArray()
    //     ]);
    //     $component->set('super_admins', [0 => $regular_user->id]);

    //     $this->assertDatabaseHas(SuperAdmin::class, ['user_id' => $regular_user->id]);
    // }
}
