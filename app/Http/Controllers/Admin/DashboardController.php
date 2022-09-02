<?php

namespace App\Http\Controllers\Admin;

class DashboardController extends AdminController
{
    public function index()
    {
        $data['title'] = 'Dashboard';

        return $this->render('dashboard', $data);
    }
}
