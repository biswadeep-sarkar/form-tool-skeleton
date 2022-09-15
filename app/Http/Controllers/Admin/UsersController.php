<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Biswadeep\FormTool\Core\Auth;

//use App\Models\Admin\UsersModel;

use Biswadeep\FormTool\Core\Doc;
use Biswadeep\FormTool\Core\DataModel;
use App\Http\InputTypes\StatusType;

class UsersController extends AdminController
{
    // Required for Form Tool
    public $title = 'Users';
    public $route = 'users';

    // Optional only for this class
    private $singularTitle = 'User';

    public function __construct()
    {
        $model = new DataModel();
        $model->db('users', 'userId');

        Doc::create($this, $model, function($input)
        {
            $input->text('name')->required();
            $input->text('email')->required()->unique();
            $input->password('password')->required()->validations('min:8');
            $input->custom(StatusType::class, 'status');
        });
    }

    private function modifyEdit($id)
    {
        // You can't simply change password and status of yourself
        if (Auth::user()->userId == $id) {
            Doc::modify(function($input)
            {
                $input->remove('password');
                $input->remove('status');
            });
        }
        else {
            Doc::modify(function($input)
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
        return Doc::getCrud()->store();
    }

    public function show($id)
    {
        
    }

    public function edit(Request $request, $id)
    {
        $data['title'] = 'Edit ' . $this->singularTitle;

        $this->modifyEdit($id);

        Doc::getCrud()->edit($id);

        return $this->render('form-tool::crud.data_form', $data);
    }

    public function update(Request $request, $id)
    {
        $this->modifyEdit($id);

        $response = Doc::getCrud()->update($id);
        if (isSuccess($response)) {
            Auth::refresh();
        }

        return $response;
    }

    public function destroy($id)
    {
        return Doc::getCrud()->destroy($id);
    }

    public function search(Request $request)
    {
        $result = Doc::getCrud()->search();

        return $result;
    }
}
