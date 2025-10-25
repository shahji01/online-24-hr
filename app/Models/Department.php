<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model{
	protected $table = 'department';
	protected $fillable = ['department_name','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
