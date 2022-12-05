<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class DashboardController extends AdminController
{
    // Required for Form Tool
    public $title = 'Dashboard';
    public $route = 'dashboard';

    public function index(Request $request)
    {
        $data['title'] = $this->title;

        return $this->render('dashboard', $data);
    }
}
