<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

use Biswadeep\FormTool\Core\Guard;
use Biswadeep\FormTool\Support\Menu;

class AdminController extends Controller
{
    public function render($view, $data = [])
    {
        $menu = Menu::create();

        $menu->add('dashboard', 'Dashboard', 'fa fa-dashboard');
        $menu->add('demo-pages', 'Demo Pages', 'fa fa-bars');

        $menu->addNested('Users', 'fa fa-users', function($child) {
            $child->add('users', 'Users');
            $child->add('user-groups', 'User Groups');
        });
        
        $menu->addNested('Settings', 'fa fa-gears', function($child) {
            $child->add('preferences', 'Preferences');
            $child->add('change-password', 'Change Password');
        });

        if (false === strpos($view, 'form-tool::'))
            $view = config('form-tool.adminURL') . '.' . $view;

        return view($view, $data);
    }
}