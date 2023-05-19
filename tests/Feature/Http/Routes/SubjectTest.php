<?php

namespace Dainsys\Support\Feature\Http\Routes;

use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function subjects_index_route_requires_authentication()
    {
        $response = $this->get(route('support.admin.subjects.index'));

        $response->assertRedirect(route('login'));
    }
}
