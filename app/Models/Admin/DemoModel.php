<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Biswadeep\FormTool\Models\BaseModel;

class DemoModel extends BaseModel
{
    use HasFactory;

    // Required for Form Tool
    public static $tableName = 'demo_pages';
    public static $primaryId = 'pageId';
}