<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceType extends Model{
	protected $table = 'advance_type';
	protected $fillable = ['advance_type_name','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
