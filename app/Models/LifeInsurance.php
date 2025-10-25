<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LifeInsurance extends Model{
	protected $table = 'life_insurance';
	protected $fillable = ['life_insurance_name','status','username','action','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
