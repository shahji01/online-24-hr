<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeActivityData extends Model{
    protected $table = 'employee_activity_data';
    protected $fillable = ['emr_no','institution_name','position_held','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
