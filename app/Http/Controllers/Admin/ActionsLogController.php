<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\Admin\CategoriesModel;
use App\Models\Admin\StoresModel;

use Biswadeep\FormTool\Core\Doc;
use Biswadeep\FormTool\Core\BluePrint;
use Biswadeep\FormTool\Core\Guard;

class ActionsLogController extends AdminController
{
    // Required for Form Tool
    public $title = 'Activities Log';
    public $route = 'activities-log';

    public function index(Request $request)
    {
        $data['title'] = $this->title;

        $query = DB::table('action_logs as a')->selectRaw('a.*, u.name')
        ->leftJoin('users as u', 'u.userId', '=', 'a.createdBy');

        $filterKeys = ['action', 'module', 'id'];
        $filteredBy = [];
        $urls = [];

        foreach ($filterKeys as $key) {
            if (! \in_array($key, $filterKeys)) {
                continue; 
            }

            $value = $request->query($key);
            $queryString = \http_build_query($request->except([$key, 'page']));
            if ($value) {
                $filteredBy[\ucfirst($key)] = [
                    'value' => $request->query($key),
                    'query' => $queryString
                ];
            }

            $urls[$key] = \url()->current().'?'.$queryString;
        }
        
        if ($request->query('action')) {
            $query->where('action', $request->query('action'));
        }
        
        if ($request->query('module')) {
            $query->where('module', $request->query('module'));
        }
        
        if ($request->query('id')) {
            $query->where(function($query) use ($request) {
                $query->where('refId', '=', $request->query('id'))
                      ->orWhere('token', '=', $request->query('id'));
            });
        }

        $data['actions'] = $query->orderByDesc('id')->paginate(50);

        $data['filteredBy'] = $filteredBy;
        $data['urls'] = $urls;

        return $this->render('actions_log.index', $data);
    }

    public function show($id)
    {
        $data['action'] = DB::table('action_logs as a')->selectRaw('a.*, u.name')
        ->leftJoin('users as u', 'u.userId', '=', 'a.createdBy')
        ->where('id', $id)->first();

        if ($data['action']) {
            $data['action']->data = \json_decode($data['action']->data, true);

            $data['action']->hasEditPermission = Guard::hasEdit($data['action']->route);
            $data['action']->hasDestroyPermission = Guard::hasDestroy($data['action']->route);
        }

        return \view('admin.actions_log.show', $data);
    }
}