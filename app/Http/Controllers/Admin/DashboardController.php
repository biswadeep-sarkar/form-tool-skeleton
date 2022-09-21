<?php

namespace App\Http\Controllers\Admin;

class DashboardController extends AdminController
{
    // Required for Form Tool
    public $title = 'Dashboard';
    public $route = 'dashboard';

    public function index()
    {
        $data['title'] = 'Dashboard';

        return $this->render('dashboard', $data);
    }
}