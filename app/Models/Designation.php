<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model{
	protected $table = 'designation';
	protected $fillable = ['designation_name','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
