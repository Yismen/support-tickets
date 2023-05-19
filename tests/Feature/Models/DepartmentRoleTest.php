<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function departments_model_interacts_with_db_table()
    {
        $data = DepartmentRole::factory()->make();

        DepartmentRole::create($data->toArray());

        $this->assertDatabaseHas(supportTableName('department_roles'), $data->only([
            'user_id', 'department_id', 'role'
        ]));
    }

    /** @test */
    public function subjects_model_belongs_to_one_user()
    {
        $department_role = DepartmentRole::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $department_role->user());
    }

    /** @test */
    public function subjects_model_belongs_to_one_department()
    {
        $subject = DepartmentRole::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $subject->department());
    }
}
