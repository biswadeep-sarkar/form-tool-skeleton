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
    public $singularTitle = 'User Group';

    private $crud = null;

    private $modules = [];
    private $hide = [];
    private $disabled = [];

    public function setup()
    {
        $model = new DataModel();
        $model->db('user_groups', 'groupId');

        // We are specifying a form name as instantiating controller class will replace our default form
        $this->crud = Doc::create($this, $model, function(BluePrint $input)
        {
            $input->text('groupName', 'Group Name')->required();
        }, 'main')->actionLog(false);
    }

    private function setupModules($id = null)
    {
        $deletedAt = 'deletedAt';
        $metaColumns = \config('form-tool.table_meta_columns');
        if (isset($metaColumns['deletedAt']) && \trim($metaColumns['deletedAt'])) {
            $deletedAt = $metaColumns['deletedAt'];
        }

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
        $adminGroup = DB::table('user_groups')->whereNull($deletedAt)->orderBy('groupId', 'asc')->first();
        if ($id && $adminGroup->groupId == $id) {
            $this->disabled = [
                'user-groups' => ['all', 'view', 'create', 'edit', 'delete', 'destroy'],
            ];
        }

        // If we want to hide some unnecessary action, we can specify it here
        $this->hide = [
            'change-password' => ['create', 'delete', 'destroy'],
            'activities-log' => ['create', 'edit', 'delete', 'destroy'],
        ];
    }

    public function index()
    {
        $this->setup();
        
        $data['title'] = $this->title;

        $data['page'] = $this->crud->index();

        return $this->render('form-tool::list.index', $data);
    }

    public function bulkAction(Request $request)
    {
        $this->setup();
        
        return $this->crud->bulkAction(function($id, $action) {
            if ($action == 'delete') {
                $response = $this->checkBeforeDelete($id);
                if ($response === true) {
                    return true;
                } else {
                    return false;
                }
            }

            return true;
        });
    }

    public function create(Request $request)
    {
        $this->setup();
        
        $data['title'] = 'Add ' . $this->singularTitle;

        // Setup the modules
        $this->setupModules();

        // Let's send the data to view
        $data['modules'] = $this->modules;
        $data['disabled'] = $this->disabled;
        $data['hide'] = $this->hide;

        $data['page'] = $this->crud->create();

        return $this->render('user_groups_form', $data);
    }

    public function store(Request $request)
    {
        $this->setup();
        
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
        $this->setup();
    }

    public function edit(Request $request, $id)
    {
        $this->setup();
        
        $data['title'] = 'Edit ' . $this->singularTitle;

        // Setup the modules
        $this->setupModules($id);

        $data['page'] = $this->crud->edit($id);

        // Get edit data
        $result = $this->crud->getEditData();
        $permission = \json_decode($result->permission);

        // Let's send the data to view
        $data['permission'] = $permission;
        $data['modules'] = $this->modules;
        $data['disabled'] = $this->disabled;
        $data['hide'] = $this->hide;

        return $this->render('user_groups_form', $data);
    }

    public function update(Request $request, $id)
    {
        $this->setup();
        
        // Setup the modules also important on update to prevent below hacks
        $this->setupModules($id);

        $permission = $request->post('permission');

        // Let's prevent if someone tries to hack and remove hidden elements of disabled checkboxes
        $desiredActions = ['view', 'create', 'edit', 'delete', 'destroy'];
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
        $this->setup();
        
        $response = $this->checkBeforeDelete($id);
        if ($response !== true) {
            return $response;
        }

        return $this->crud->delete($id);
    }

    public function search(Request $request)
    {
        $this->setup();
        
        $result = $this->crud->search();

        return $result;
    }

    private function checkBeforeDelete($id)
    {
        $deletedAt = 'deletedAt';
        $metaColumns = \config('form-tool.table_meta_columns');
        if (isset($metaColumns['deletedAt']) && \trim($metaColumns['deletedAt'])) {
            $deletedAt = $metaColumns['deletedAt'];
        }

        // We don't want Administrator and other users to remove Administrator's group
        // Let's assume that the first row in the user_groups table is the Administrator group
        $adminGroup = DB::table('user_groups')->whereNull($deletedAt)->orderBy('groupId', 'asc')->first();        
        if (! $adminGroup) {
            return back()->with('error', 'Administrator user group not found!');
        }

        if ($adminGroup->groupId == $id) {
            return back()->with('error', "You can't delete the main administrator group!");
        }

        return true;
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

                    if (! isset($module->title)) {
                        throw new \Exception('$title not set or not declared as public at ['. \get_class($module) .']');
                    }
                    if (! isset($module->route)) {
                        throw new \Exception('$route not set or not declared as public at ['. \get_class($module) .']!');
                    }

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
