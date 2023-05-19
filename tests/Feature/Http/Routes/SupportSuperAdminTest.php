<?php

namespace Dainsys\Support\Feature\Http\Routes;

use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupportSuperAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function subjects_index_route_requires_authentication()
    {
        $response = $this->get(route('support.admin.support_super_admins.index'));

        $response->assertRedirect(route('login'));
    }
}
