<?php

namespace Dainsys\Support\Tests\Unit;

use Dainsys\Support\Tests\TestCase;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasSupportTicketsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_is_not_super_admin()
    {
        $user = UserFactory::new()->create();

        $this->assertFalse($user->isSuperAdmin());
    }

    /** @test */
    public function users_is_super_admin()
    {
        $user = UserFactory::new()->create();
        $super_admin = $user->superAdmin()->create();

        $this->assertTrue($user->isSuperAdmin());
    }
}
