<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeKinsData extends Model{
    protected $table = 'employee_kins_data';
    protected $fillable = ['emr_no','next_kin_name','next_kin_relation','next_kin_percentage','next_kin_address','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

	