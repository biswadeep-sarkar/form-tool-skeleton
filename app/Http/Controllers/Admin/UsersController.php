<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

//use App\Models\Admin\UsersModel;

use Biswadeep\FormTool\Core\Doc;
use Biswadeep\FormTool\Core\DataModel;
use Biswadeep\FormTool\Core\Auth;
use App\Http\InputTypes\StatusType;

class UsersController extends AdminController
{
    // Required for Form Tool
    public $title = 'Users';
    public $route = 'users';

    // Optional only for this class
    private $singularTitle = 'User';

    private $crud = null;

    public function __construct()
    {
        $model = new DataModel();
        $model->db('users', 'userId');

        $this->crud = Doc::create($this, $model, function($input)
        {
            $input->text('name')->required();
            $input->select('groupId', 'Group')->required()->options('user_groups.groupId.groupName');
            $input->text('email')->required()->unique()->validations('email');
            $input->password('password')->required()->validations('min:8');
            $input->custom(StatusType::class, 'status');
        });
    }

    private function modifyEdit($id)
    {
        // You can't simply change password and status of yourself
        if (Auth::user()->userId == $id) {
            $this->crud->modify(function($input)
            {
                $input->remove('password');
                $input->remove('status');
            });
        }
        else {
            $this->crud->modify(function($input)
            {
                $input->modify('password')->required(false)->help("You can ignore password if you don't want to change");
            });
        }
    }

    public function index()
    {
        $data['title'] = $this->title;

        return $this->render('form-tool::crud.data_table', $data);
    }

    public function create(Request $request)
    {
        $data['title'] = 'Add ' . $this->singularTitle;

        return $this->render('form-tool::crud.data_form', $data);
    }

    public function store(Request $request)
    {
        return $this->crud->store();
    }

    public function show($id)
    {
        
    }

    public function edit(Request $request, $id)
    {
        $data['title'] = 'Edit ' . $this->singularTitle;

        $this->modifyEdit($id);

        $this->crud->edit($id);

        return $this->render('form-tool::crud.data_form', $data);
    }

    public function update(Request $request, $id)
    {
        $this->modifyEdit($id);

        $data = $request->post();
        if (isset($data['groupId'])) {
            // Let's prevent Admin or other users to change Administrator group if we have only one Administrator
            $oldData = DB::table('users')->where('userId', $id)->first();
            if ($oldData->groupId != $data['groupId']) {
                // Let's assume that the first row in the user_groups table is the Administrator group
                $adminGroup = DB::table('user_groups')->orderBy('groupId', 'asc')->first();
                if (! $adminGroup) {
                    return back()->with('error', 'Administrator user group not found!');
                }

                if ($oldData->groupId == $adminGroup->groupId) {
                    $users = DB::table('users')->where('groupId', $adminGroup->groupId)->count();
                    if ($users <= 1) {
                        return back()->with('error', "You need to add or assign someone as Administrator to change this user's group!");
                    }
                }
            }
        }

        $response = $this->crud->update($id);

        if (isSuccess($response)) {
            Auth::refresh();
        }

        return $response;
    }

    public function destroy($id)
    {
        // We need to prevent deleting all the administrator users
        // Let's assume that the first row in the user_groups table is the Administrator group
        $adminGroup = DB::table('user_groups')->orderBy('groupId', 'asc')->first();
        if (! $adminGroup) {
            return back()->with('error', 'Administrator user group not found!');
        }

        $user = DB::table('users')->where('userId', $id)->first();
        if (! $user) {
            return back()->with('error', 'User not found!');
        }

        // Is user belongs to Administrator's user group
        if ($user->groupId == $adminGroup->groupId) {
            // Count number of users with administrator's group
            $users = DB::table('users')->where('groupId', $adminGroup->groupId)->count();
            if ($users <= 1) {
                return back()->with('error', 'You need to add one more Administrator to delete this Administrator user!');
            }
        }

        return $this->crud->destroy($id);
    }

    public function search(Request $request)
    {
        $result = $this->crud->search();

        return $result;
    }
}
