<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Biswadeep\FormTool\Core\Doc;
use Biswadeep\FormTool\Core\BluePrint;
use Biswadeep\FormTool\Core\DataModel;
use App\Http\InputTypes\StatusType;
use App\Http\InputTypes\YesNoCheckbox;
use App\Models\Admin\DemoModel;

class DemoController extends AdminController
{
    // Required for Form Tool
    public $title = 'Demo Pages';
    public $route = 'demo-pages';
    public $singularTitle = 'Demo Page';

    protected $crud = null;

    public function setup()
    {
        $model = new DataModel();
        $model->db('test', 'testID')->token('testToken');

        $this->crud = Doc::create($this, DemoModel::class, function (BluePrint $input) {
            $input->text('title')->required();
            $input->text('slug')->slug();
            $input->textarea('content');
            $input->text('author', 'Author Name');
            $input->image('image')->path('demo');
            $input->select('status')->noFirst()->options([
                1 => 'Active',
                0 => 'Inactive'
            ]);
        })->run();
    }

    public function index(Request $request)
    {
        $this->setup();

        $data['title'] = $this->title;

        $data['page'] = $this->crud->index();

        return $this->render('form-tool::list.index', $data);
    }

    public function bulkAction(Request $request)
    {
        $this->setup();

        return $this->crud->bulkAction();
    }

    public function create(Request $request)
    {
        $this->setup();

        $data['title'] = 'Add '.$this->singularTitle;

        $data['page'] = $this->crud->create();

        return $this->render('form-tool::form.index', $data);
    }

    public function store(Request $request)
    {
        $this->setup();

        // Needed if you are not using run()
        // return $this->crud->store();
    }

    public function show(Request $request, $id = null)
    {
        $this->setup($id);
    }

    public function edit(Request $request, $id = null)
    {
        $this->setup();

        $data['title'] = 'Edit '.$this->singularTitle;

        $data['page'] = $this->crud->edit($id);

        return $this->render('form-tool::form.index', $data);
    }

    public function update(Request $request, $id = null)
    {
        $this->setup();

        // Needed if you are not using run()
        // return $this->crud->udpate($id);
    }

    public function destroy(Request $request, $id = null)
    {
        $this->setup();

        // Needed if you are not using run()
        // return $this->crud->delete($id);
    }

    public function search(Request $request)
    {
        $this->setup();

        $result = $this->crud->search();

        return $result;
    }
}
