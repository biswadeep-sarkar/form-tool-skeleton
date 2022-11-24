<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Biswadeep\FormTool\Core\Doc;
use Biswadeep\FormTool\Core\BluePrint;
use Biswadeep\FormTool\Core\DataModel;
use Biswadeep\FormTool\Core\Auth;

class ChangePasswordController extends AdminController
{
    // Required for Form Tool
    public $title = 'Change Password';
    public $route = 'change-password';

    public function setup()
    {
        $model = new DataModel();
        $model->db('users', 'userId');

        Doc::create($this, $model, function(BluePrint $input)
        {
            $user = Auth::user();

            $input->password('current', 'Current Password')->required()->validations([
                function ($attribute, $value, $fail) use ($user) {
                    if (! \Hash::check($value, $user->password)) {
                        return $fail('The Current Password is incorrect.');
                    }
                }
            ]);
            $input->password('password', 'New Password')->required()->validations('min:8');
            $input->password('confirm', 'Confirm Password')->required()->validations('same:password');
        
        })->saveOnly('password')->actionLog(false);
    }

    public function index()
    {
        $this->setup();

        $data['title'] = $this->title;

        $id = Auth::user()->userId;

        Doc::getCrud()->edit($id);

        return $this->render('form-tool::form.index', $data);
    }

    public function update(Request $request, $id)
    {
        $this->setup();

        $id = Auth::user()->userId;

        $response = Doc::getCrud()->update($id);
        if (isSuccess($response)) {
            Auth::refresh();
        }

        return $response;
    }
}
