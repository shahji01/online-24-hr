<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model{
	protected $table = 'attendence';
	protected $fillable = ['emp_id','department_id','attendense_date','attendense_type','remarks','status','date','time','username'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
