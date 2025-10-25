<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestHiring extends Model{
	protected $table = 'requesthiring';
	protected $fillable = ['RequestHiringNo','RequestHiringTitle','sub_department_id','job_type_id','designation_id','qualification_id','shift_type_id','RequestHiringGender','RequestHiringSalaryStart','RequestHiringSalaryEnd','RequestHiringAge','RequestHiringDescription','ApprovalStatus','status','username','date','time'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
