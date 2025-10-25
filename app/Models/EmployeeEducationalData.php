<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducationalData extends Model{
    protected $table = 'employee_educational_data';
    protected $fillable = ['emr_no','institute_name','year_of_admission','year_of_passing','degree_type','major_subjects','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

