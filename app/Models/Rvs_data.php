<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rvs_data extends Model{
	protected $table = 'rv_data';
	protected $fillable = ['rv_no','acc_id','description','debit_credit','amount','rv_status','time','date','status','branch_id','username'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
