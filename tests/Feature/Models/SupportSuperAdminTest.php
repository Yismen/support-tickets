<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\SupportSuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupportSuperAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function support_super_admin_model_interacts_with_db_table()
    {
        $data = SupportSuperAdmin::factory()->make();

        SupportSuperAdmin::create($data->toArray());

        $this->assertDatabaseHas(supportTableName('support_super_admins'), $data->only([
            'user_id'
        ]));
    }

    /** @test */
    public function support_super_admins_model_belongs_to_one_user()
    {
        $support_super_admin = SupportSuperAdmin::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $support_super_admin->user());
    }
}
