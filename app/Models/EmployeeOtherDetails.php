<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOtherDetails extends Model{
    protected $table = 'employee_other_details';
    protected $fillable = ['emr_no','crime_check','crime_detail','additional_info_check','additional_info_detail','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

