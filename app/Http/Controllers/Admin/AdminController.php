<?php

namespace App\Http\Controllers\Admin;

use Biswadeep\FormTool\Core\BaseController;
use Biswadeep\FormTool\Core\Guard;
use Biswadeep\FormTool\Support\Menu;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AdminController extends BaseController
{
    use ValidatesRequests;

    protected $settings = null;

    public function __construct()
    {
        $this->settings = \app('settings');
    }

    public function render($view, $data = [])
    {
        $menu = Menu::create();

        $menu->add('dashboard', 'Dashboard', 'fa fa-dashboard');
        $menu->add('demo-pages', 'Demo Pages', 'fa fa-bars');

        $menu->addNested('Users', 'fa fa-users', function ($child) {
            $child->add('users', 'Users');
            $child->add('user-groups', 'User Groups');
        });

        $menu->add('activities-log', 'Activities Log', 'fa fa-bars');
        $menu->addNested('Settings', 'fa fa-gears', function ($child) {
            $child->add('settings', 'General');
            $child->add('change-password', 'Change Password');
        });

        $data['sidebar'] = $menu->make();

        if (false === strpos($view, 'form-tool::')) {
            $view = 'admin.'.$view;
        }

        return view($view, $data);
    }
}
