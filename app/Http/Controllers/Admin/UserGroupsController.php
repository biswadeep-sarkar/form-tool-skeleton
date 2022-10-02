<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

//use App\Models\Admin\UsersModel;

use Biswadeep\FormTool\Core\Auth;
use Biswadeep\FormTool\Core\Doc;
use Biswadeep\FormTool\Core\BluePrint;
use Biswadeep\FormTool\Core\DataModel;

class UserGroupsController extends AdminController
{
    // Required for Form Tool
    public $title = 'Users Groups';
    public $route = 'user-groups';

    // Optional only for this class
    private $singularTitle = 'User Group';

    private $crud = null;

    private $modules = [];
    private $hide = [];
    private $disabled = [];

    public function __construct()
    {
        $model = new DataModel();
        $model->db('user_groups', 'groupId');

        // We are specifying a form name as instantiating controller class will replace our default form
        $this->crud = Doc::create($this, $model, function(BluePrint $input)
        {
            $input->text('groupName', 'Group Name')->required();
        }, 'main');
    }

    private function setupModules($id = null)
    {
        // List the controllers to remove from the modules list
        $removeControllers = ['Controller', 'AdminController'];

        // Get all the controllers/modules under Admin path
        $this->modules = $this->getModules($removeControllers);
		
        // You can modify this array to add your custom modules
        // $modules['other-route'] = ['title' => 'Other Module', 'route' => 'other-route'];

        // Let's hide or diable checkboxes as per need
        $this->hide = $this->disabled = [];

        // We don't want Administrator and other users to remove Administrator's user-groups permissions
        // Let's assume that the first row in the user_groups table is the Administrator group
        $adminGroup = DB::table('user_groups')->orderBy('groupId', 'asc')->first();
        if ($id && $adminGroup->groupId == $id) {
            $this->disabled = [
                'user-groups' => ['all', 'view', 'create', 'edit', 'delete'],
            ];
        }

        // If we want to hide some unnecessary action, we can specify it here
        $this->hide = [
            'change-password' => ['create', 'delete'],
        ];
    }

    public function index()
    {
        $data['title'] = $this->title;

        $data['crudName'] = 'main';

        return $this->render('form-tool::list.index', $data);
    }

    public function create(Request $request)
    {
        $data['title'] = 'Add ' . $this->singularTitle;

        // Setup the modules
        $this->setupModules();

        // Let's send the data to view
        $data['modules'] = $this->modules;
        $data['disabled'] = $this->disabled;
        $data['hide'] = $this->hide;

        $data['form'] = $this->crud->getForm();

        return $this->render('user_groups_form', $data);
    }

    public function store(Request $request)
    {
        // Add a field to our crud to save permission
        $this->crud->modify(function($input) {
            $input->text('permission');
        });

        $data = [
            'permission' => \json_encode($request->post('permission'))
        ];

        $this->crud->updatePostData($data);

        $response = $this->crud->store();
        
        return $response;
    }

    public function show($id)
    {
        
    }

    public function edit(Request $request, $id)
    {
        $data['title'] = 'Edit ' . $this->singularTitle;

        // Setup the modules
        $this->setupModules($id);

        // Call edit
        $this->crud->edit($id);

        // Get edit data
        $result = $this->crud->getEditData();
        $permission = \json_decode($result->permission);

        // Let's send the data to view
        $data['permission'] = $permission;
        $data['modules'] = $this->modules;
        $data['disabled'] = $this->disabled;
        $data['hide'] = $this->hide;

        $data['form'] = $this->crud->getForm();

        return $this->render('user_groups_form', $data);
    }

    public function update(Request $request, $id)
    {
        // Setup the modules also important on update to prevent below hacks
        $this->setupModules($id);

        $permission = $request->post('permission');

        // Let's prevent if someone tries to hack and remove hidden elements of disabled checkboxes
        $desiredActions = ['view', 'create', 'edit', 'delete'];
        foreach ($this->disabled as $module => $actions) {
            $values = [];
            foreach ($actions as $action) {
                if (\in_array($action, $desiredActions)) {
                    $values[$action] = 1;
                }
            }

            $permission[$module] = array_merge($permission[$module] ?? [], $values);
        }

        // Add a field to our crud to save permission
        $this->crud->modify(function($input) {
            $input->text('permission');
        });

        $data = [
            'permission' => \json_encode($permission)
        ];

        $this->crud->updatePostData($data);

        $response = $this->crud->update($id);
        
        return $response;
    }

    public function destroy($id)
    {
        // We don't want Administrator and other users to remove Administrator's group
        // Let's assume that the first row in the user_groups table is the Administrator group
        $adminGroup = DB::table('user_groups')->orderBy('groupId', 'asc')->first();        
        if (! $adminGroup) {
            return back()->with('error', 'Administrator user group not found!');
        }

        if ($adminGroup->groupId == $id) {
            return back()->with('error', "You can't delete the main administrator group!");
        }

        return $this->crud->delete($id);
    }

    public function search(Request $request)
    {
        $result = $this->crud->search();

        return $result;
    }

    private function getModules($removeControllers = [])
    {
		$separator = DIRECTORY_SEPARATOR;
		
        $basePath = base_path();
        $path = app_path().$separator.'Http'.$separator.'Controllers'.$separator.'Admin';
		
        if (! \file_exists($path)) {
            throw new \Exception('Admin path not found: '.$path);
        }

        $path = array($path.$separator.'*');

        $modules = [];
		while (\count($path)) {
			$explore = array_shift($path);
			
			foreach (\glob($explore) as $file) {
				if (\is_dir($file)) {
					$path[] = $file.$separator.'*';
                } else if (\is_file($file) && \strpos($file, '.php')) {
                    // Remove the basePath and .php from the path
                    $controller = \str_replace([$basePath.$separator, '.php'], '', $file);

                    if ($this->isInArray($controller, $removeControllers)) {
                        continue;
                    }

                    // Make the app name capital and in the namespace format of PHP
                    $controller = \ucfirst(\str_replace($separator, '\\', $controller));
					
                    // We are creating an object for every controller to get the title and route
                    $module = new $controller();
					$modules[$module->route] = ['title' => $module->title, 'route' => $module->route];
                }
			}
		}

        return $modules;
    }

    private function isInArray($needle, $haystack)
    {
        foreach ($haystack as $val) {
            // Remove everything before last \ (slash)
            $needle = \substr($needle, \strrpos($needle, DIRECTORY_SEPARATOR));
            if (false !== \strpos($needle, DIRECTORY_SEPARATOR.$val)) {
                return true;
            }
        }

        return false;
    }
}
