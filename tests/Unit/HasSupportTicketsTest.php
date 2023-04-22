<?php

namespace Dainsys\Support\Tests\Unit;

use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Models\DepartmentRole;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasSupportTicketsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_is_super_admin()
    {
        $not_valid_user = UserFactory::new()->create();
        $valid_user = UserFactory::new()->create();
        $super_admin = $valid_user->superAdmin()->create();

        $this->assertTrue($valid_user->isSuperAdmin());
        $this->assertFalse($not_valid_user->isSuperAdmin());
    }

    /** @test */
    public function user_is_department_admin()
    {
        $user = UserFactory::new()->create();
        $valid_department = Department::factory()->create();
        $department_role = DepartmentRole::factory()->admin()->create(['user_id' => $user->id, 'department_id' => $valid_department->id]);

        $this->assertTrue($user->isDepartmentAdmin());
    }

    /** @test */
    public function user_is_department_agent()
    {
        $user = UserFactory::new()->create();
        $valid_department = Department::factory()->create();
        $department_role = DepartmentRole::factory()->agent()->create(['user_id' => $user->id, 'department_id' => $valid_department->id]);

        $this->assertTrue($user->isDepartmentAgent());
    }

    /** @test */
//     public function user_belongs_to_department()
//     {
//         $user = UserFactory::new()->create();
//         // $department = Department::factory()->create();
//         // $department_role = DepartmentRole::factory()->admin()->create(['user_id' => $user->id, 'department_id' => $department->id]);
// dd($user->department());
//         $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $user->department());
//     }
}
