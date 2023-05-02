<?php

namespace Dainsys\Support\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    public function __invoke()
    {
        return Gate::allows('view-dashboards')
            ? redirect()->route('support.dashboard')
            : redirect()->route('support.my_tickets');
    }
}
