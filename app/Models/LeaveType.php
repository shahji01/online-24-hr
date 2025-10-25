<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model{
	protected $table = 'leave_type';
	protected $fillable = ['leave_type_name','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
