<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model{
	protected $table = 'jobs';
	protected $fillable = ['job_no','job_title','employer_id','department_id','job_type_id','apply_start_date','apply_end_date','gender','age','salary','description','status','username','date','time'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
