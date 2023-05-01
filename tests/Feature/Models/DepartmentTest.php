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
            'name', 'ticket_prefix', 'description'
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

    /** @test */
    public function departments_model_parse_prefix_to_all_caps_and_dash_at_the_end()
    {
        $department = Department::factory()->create(['ticket_prefix' => 'a1aa']);

        $this->assertDatabaseHas(Department::class, [
            'ticket_prefix' => 'A1AA-'
        ]);
    }
}
