<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTransfer extends Model{
    protected $table = 'employee_location';
    protected $fillable = ['emr_no','location_id','sub_department_id','promotion_id','approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
