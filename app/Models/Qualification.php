<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model{
	protected $table = 'qualification';
	protected $fillable = ['qualification_name','institute_id','country_id','state_id','city_id','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
