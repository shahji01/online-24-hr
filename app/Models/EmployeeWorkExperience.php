<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeWorkExperience extends Model{
    protected $table = 'employee_work_experience';
    protected $fillable = ['emr_no','employeer_name','position_held','career_level','work_exp_path','work_exp_name','work_exp_type',
	'started','ended','last_drawn_salary','reason_leaving','suspend_check','suspend_reason','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

