<?php

namespace App\Http\Controllers\Routes;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class HomepageController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', []);
    }

    public function linked()
    {
        return Inertia::render('Linked', []);
    }
}
