<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFuelData extends Model{
    protected $table = 'employee_fuel_data';
    protected $fillable = ['emr_no','fuel_date','from','to','km','username','status','approval_status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
