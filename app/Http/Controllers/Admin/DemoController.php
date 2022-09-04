<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Biswadeep\FormTool\Core\Crud;
use App\Models\Admin\DemoModel;

class DemoController extends AdminController
{
    // Required for Form Tool
    public $title = 'Demo Pages';
    public $route = 'demo-pages';

    // Optional only for this class
    private $singularTitle = 'Demo Page';

    public function __construct()
    {
        Crud::createModel($this, DemoModel::class, function($input)
        {
            $input->text('title')->required();
            $input->text('slug')->slug();
            $input->textarea('content');
            $input->text('author', 'Author Name');
            $input->image('image')->path('demo');
            $input->select('status')->noFirst()->options([
                1 => 'Active',
                0 => 'Inactive'
            ]);
        });
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
        
    }

    public function show($id)
    {
        
    }

    public function edit(Request $request, $id)
    {
        $data['title'] = 'Edit ' . $this->singularTitle;

        return $this->render('form-tool::crud.data_form', $data);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        
    }
}
