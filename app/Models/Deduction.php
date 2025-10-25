<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model{
	protected $table = 'deduction';
	protected $fillable = ['employee_id','deduction_type','deduction_amount','remarks','once','month','year','status','username','date','time'];
	protected $primaryKey = 'id';
	public $timestamps = false;

}