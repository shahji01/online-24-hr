<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model{
	protected $table = 'allowance';
	protected $fillable = ['employee_id','allowance_type','allowance_amount','status','username','date','time'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
