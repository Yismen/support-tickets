<?php

namespace Dainsys\Support\Feature\Http\Routes;

use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function departments_index_route_requires_authentication()
    {
        $response = $this->get(route('support.admin.departments.index'));

        $response->assertRedirect(route('login'));
    }
}
