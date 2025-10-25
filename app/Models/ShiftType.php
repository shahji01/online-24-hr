<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model{
	protected $table = 'shift_type';
	protected $fillable = ['shift_type_name','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
