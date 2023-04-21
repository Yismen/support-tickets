<?php

namespace Dainsys\Support\Feature\Http\Routes;

use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reasons_index_route_requires_authentication()
    {
        $response = $this->get(route('support.admin.department_roles.index'));

        $response->assertRedirect(route('login'));
    }
}
