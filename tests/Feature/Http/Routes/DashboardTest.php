<?php

namespace Dainsys\Support\Feature\Http\Routes;

use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_route_requires_authentication()
    {
        $response = $this->get(route('support.home'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_route_requires_authentication()
    {
        $response = $this->get(route('support.admin'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function dashboard_route_requires_authentication()
    {
        $response = $this->get(route('support.tickets'));

        $response->assertRedirect(route('login'));
    }
}
