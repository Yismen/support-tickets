<?php

namespace Dainsys\Support\Feature\Http\Livewire\DepartmentRole;

use Livewire\Livewire;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\DepartmentRole;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Http\Livewire\DepartmentRole\Index;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function department_role_index_component_requires_authorization()
    {
        $this->withoutAuthorizedUser();

        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function department_roles_index_route_requires_authorization()
    {
        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function department_roles_index_works_for_authorized_users_and_renders_correct_view()
    {
        $this->withAuthorizedUser('view department roles');

        $component = Livewire::test(Index::class);

        $component->assertViewIs('support::livewire.department_role.index');
        $component->assertOk();
    }

    /** @test */
    public function department_roles_index_works_for_department_role_users_and_renders_correct_view()
    {
        $this->withSuperUser();
        $users = UserFactory::new()->count(2)->create();

        $component = Livewire::test(Index::class);

        $component->assertViewHas('users');
        $component->assertOk();
    }

    /** @test */
    public function department_roles_index_shows_all_users_except_authenticated_user()
    {
        $this->withSuperUser();
        $user = UserFactory::new()->create();

        $component = Livewire::test(Index::class);

        $component->assertSee($user->name);
        $component->assertDontSee(auth()->user()->name);
    }

    /** @test */
    // public function department_roles_index_add_super_users()
    // {
    //     $this->withSuperUser();
    //     $regular_user = UserFactory::new()->create();

    //     $component = Livewire::test(Index::class, [
    //         'department_roles' => DepartmentRole::pluck('user_id')->values()->toArray()
    //     ]);
    //     $component->set('department_roles', [0 => $regular_user->id]);

    //     $this->assertDatabaseHas(DepartmentRole::class, ['user_id' => $regular_user->id]);
    // }

    /** @test */
    // public function department_roles_index_removes_super_users()
    // {
    //     $this->withSuperUser();
    //     $regular_user = UserFactory::new()->create();

    //     $component = Livewire::test(Index::class, [
    //         'department_roles' => DepartmentRole::pluck('user_id')->values()->toArray()
    //     ]);
    //     $component->set('department_roles', [0 => $regular_user->id]);

    //     $this->assertDatabaseHas(DepartmentRole::class, ['user_id' => $regular_user->id]);
    // }
}
