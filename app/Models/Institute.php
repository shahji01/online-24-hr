<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institute extends Model{
	protected $table = 'institute';
	protected $fillable = ['institute_name','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
