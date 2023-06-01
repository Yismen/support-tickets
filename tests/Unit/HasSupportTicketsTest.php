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
    public function user_is_support_super_admin()
    {
        $not_valid_user = $this->user();
        $valid_user = $this->user();
        $support_super_admin = $valid_user->supportSuperAdmin()->create();

        $this->assertTrue($valid_user->isSupportSuperAdmin());
        $this->assertFalse($not_valid_user->isSupportSuperAdmin());
    }

    /** @test */
    public function user_is_department_admin()
    {
        $user = $this->user();
        $valid_department = Department::factory()->create();
        $department_role = DepartmentRole::factory()->admin()->create(['user_id' => $user->id, 'department_id' => $valid_department->id]);

        $this->assertTrue($user->isDepartmentAdmin($valid_department));
    }

    /** @test */
    public function user_is_department_agent()
    {
        $user = $this->user();
        $valid_department = Department::factory()->create();
        $department_role = DepartmentRole::factory()->agent()->create(['user_id' => $user->id, 'department_id' => $valid_department->id]);

        $this->assertTrue($user->isDepartmentAgent($valid_department));
    }
}
