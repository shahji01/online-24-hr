<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProjects extends Model{
    protected $table = 'employee_projects';
    protected $fillable = ['project_name','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

