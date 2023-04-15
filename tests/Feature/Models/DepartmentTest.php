<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function departments_model_interacts_with_db_table()
    {
        $data = Department::factory()->make();

        Department::create($data->toArray());

        $this->assertDatabaseHas(supportTableName('departments'), $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function departments_model_has_many_reasons()
    {
        $department = Department::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $department->reasons());
    }

    /** @test */
    public function departments_model_has_many_tickets()
    {
        $department = Department::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $department->tickets());
    }
}
