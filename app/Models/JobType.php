<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model{
	protected $table = 'job_type';
	protected $fillable = ['job_type_name','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
