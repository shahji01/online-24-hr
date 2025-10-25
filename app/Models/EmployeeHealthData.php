<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHealthData extends Model{
    protected $table = 'employee_health_data';
    protected $fillable = ['emr_no','health_type','health_check','physical_handicap','height','weight','blood_group','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
