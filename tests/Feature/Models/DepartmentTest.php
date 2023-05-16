<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Ticket;
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

    /** @test */
    public function department_model_set_tickets_completed_attribute()
    {
        $department = Department::factory()->create();
        Ticket::factory()->completed()->create(['department_id' => $department->id]);
        Ticket::factory()->create(['department_id' => $department->id]);
        Ticket::factory()->create();

        $this->assertEquals(1, $department->tickets_completed);
    }

    /** @test */
    public function department_model_set_tickets_incompleted_attribute()
    {
        $department = Department::factory()->create();
        Ticket::factory()->completed()->create(['department_id' => $department->id]);
        Ticket::factory()->create(['department_id' => $department->id]);

        $this->assertEquals(1, $department->tickets_incompleted);
    }

    /** @test */
    public function department_model_set_completion_rate_attribute()
    {
        $department = Department::factory()->create();
        Ticket::factory()->completed()->create(['department_id' => $department->id]);
        Ticket::factory()->create(['department_id' => $department->id, 'completed_at' => now()->addDays(100)]);

        $this->assertEquals(0.5, $department->compliance_rate);
    }

    /** @test */
    public function department_model_set_compliance_rate_attribute()
    {
        $department = Department::factory()->create();
        Ticket::factory()->compliant()->create(['department_id' => $department->id]);
        Ticket::factory()->noncompliant()->create(['department_id' => $department->id]);

        $this->assertEquals(0.5, $department->compliance_rate);
    }

    /** @test */
    public function departments_model_parse_prefix_to_all_caps_and_dash_at_the_end()
    {
        $department = Department::factory()->create(['name' => 'craziness']);

        $this->assertDatabaseHas(Department::class, [
            'ticket_prefix' => 'CRAZ-'
        ]);
    }

    /** @test */
    public function department_model_sets_ticket_prefix_correctly()
    {
        $department = Department::factory()->create(['name' => 'administration', 'ticket_prefix' => null]);

        $this->assertDatabaseHas(Department::class, [
            'id' => $department->id,
            'ticket_prefix' => 'ADMI-',
        ]);
    }

    /** @test */
    public function department_model_sets_ticket_prefix_correctly_when_name_is_two_words_or_more()
    {
        $department = Department::factory()->create(['name' => 'admini service', 'ticket_prefix' => null]);

        $this->assertDatabaseHas(Department::class, [
            'id' => $department->id,
            'ticket_prefix' => 'ADSE-',
        ]);
    }

    /** @test */
    public function department_model_sets_ticket_prefix_to_a_new_value_when_prefix_exist_already()
    {
        $department_1 = Department::factory()->create(['name' => 'admini']);
        $department_2 = Department::factory()->create(['name' => 'administration']);

        $this->assertDatabaseHas(Department::class, [
            'id' => $department_1->id,
            'ticket_prefix' => 'ADMI-',
        ]);

        $this->assertDatabaseMissing(Department::class, [
            'id' => $department_2->id,
            'ticket_prefix' => 'ADMI-',
        ]);
    }
}
