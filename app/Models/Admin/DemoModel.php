<?php

namespace App\Models\Admin;

use Biswadeep\FormTool\Models\AdminModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DemoModel extends AdminModel
{
    use HasFactory;

    // Required for Form Tool
    public static $tableName = 'demo_pages';
    public static $primaryId = 'pageId';
}
