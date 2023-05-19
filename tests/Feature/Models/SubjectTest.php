<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Subject;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function subjects_model_interacts_with_db_table()
    {
        $data = Subject::factory()->make();

        Subject::create($data->toArray());

        $this->assertDatabaseHas(supportTableName('subjects'), $data->only([
            'name', 'department_id', 'priority', 'description'
        ]));
    }

    /** @test */
    public function subjects_model_belongs_to_one_department()
    {
        $subject = Subject::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $subject->department());
    }
}
