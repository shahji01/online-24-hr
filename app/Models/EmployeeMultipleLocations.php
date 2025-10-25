<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMultipleLocations extends Model {
    protected $table = 'employee_multiple_locations';
    protected $fillable = ['employee_id','location_id','company_id','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
