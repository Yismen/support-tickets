<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Reason;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReasonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reasons_model_interacts_with_db_table()
    {
        $data = Reason::factory()->make();

        Reason::create($data->toArray());

        $this->assertDatabaseHas(supportTableName('reasons'), $data->only([
            'name', 'department_id', 'priority', 'description'
        ]));
    }

    /** @test */
    public function reasons_model_belongs_to_one_department()
    {
        $reason = Reason::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $reason->department());
    }
}
