<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function super_admin_model_interacts_with_db_table()
    {
        $data = SuperAdmin::factory()->make();

        SuperAdmin::create($data->toArray());

        $this->assertDatabaseHas(supportTableName('super_admins'), $data->only([
            'user_id'
        ]));
    }

    /** @test */
    public function super_admins_model_belongs_to_one_user()
    {
        $super_admin = SuperAdmin::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $super_admin->user());
    }
}
