<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommonModel;
use Illuminate\Support\Facades\URL;
use Request;

class AdminController extends Controller
{
    public function render($view, $data = [])
    {
		$data['sideMenu'] = [
			'Dashboard'         => ['dashboard', 'fa fa-dashboard'],
			'Demo Pages'         => ['demo-pages', 'fa fa-bars'],
			'Demo Nested Link'          => ['fa fa-bars',
				[
					'Link 1'		=> ['#'],
					'Link 2'		=> ['#']
				]
			]
		];
        
        $activeLink = Request::segment(2);

        foreach($data['sideMenu'] as $key => &$row)	{
            if(is_array($row[1]))	{
                $flag = false;
                $active = false;
                foreach($row[1] as $key2 => $row2)	{
                    if(!isset($row2[1]) /*|| $this->access->{'can'.ucfirst($row2[1])}(ADMIN_DIR.$row2[0])*/)
                        $flag=true;
                    if($activeLink == $row2[0])
                        $active=true;
                } 
                if($flag)	{
                    if($active) 
                        $row[2] = 'active';
                    
                    foreach($row[1] as $key2 => $row2)
                        if(!isset($row2[1]) /*|| $this->access->{'can'.ucfirst($row2[1])}(ADMIN_DIR.$row2[0])*/) {
                            if($activeLink == $row2[0])
                                $row2[2] = 'active';
                    }
                }
            }
            else {
                if(!isset($row[2]) /*|| $this->access->{'can'.ucfirst($row[2])}(ADMIN_DIR.$row[0])*/)	{
                    if($activeLink == $row[0]) 
                        $row[2] = 'active';
                }
            }
        }

        if (false === strpos($view, 'form-tool::'))
            $view = config('form-tool.adminURL') . '.' . $view;

        return view($view, $data);
    }
}