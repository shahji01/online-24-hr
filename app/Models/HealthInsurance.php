<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthInsurance extends Model{
	protected $table = 'health_insurance';
	protected $fillable = ['health_insurance_name','status','username','action','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
