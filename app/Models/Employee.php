<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model{
	protected $table = 'employee';
	protected $fillable = ['emp_no','emp_name','cnic_path','cnic_name','cnic_type', 'employee_project_id', 'emp_sub_department_id', 'emr_no', 'emp_father_name','emp_department_id','emp_date_of_birth','emp_joining_date','emp_gender','emp_cnic','emp_contact_no','emp_employement_status','emp_salary', 'emp_joining_salary','labour_law','emp_email','emp_marital_status','status','username','date','time'];
	protected $primaryKey = 'id';
	public $timestamps = false;


}

