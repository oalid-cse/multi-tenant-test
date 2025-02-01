<?php

namespace App\Http\Controllers;

use App\Helpers\TenantHelper;
use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $tenant = TenantHelper::getCurrentTenant();

        $projects = Project::all();

        return view('home')->with(compact(
            'tenant',
            'projects'
        ));
    }
}
