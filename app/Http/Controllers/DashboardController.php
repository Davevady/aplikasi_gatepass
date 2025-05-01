<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function landingPage()
    {
        $title = 'Welcome';
        return view('welcome', compact('title'));
    }

    public function index()
    {
        $title = 'Dashboard';
        return view('superadmin.index', compact('title'));
    }
}
