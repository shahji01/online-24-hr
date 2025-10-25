<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pvs_data extends Model{
	protected $table = 'pv_data';
	protected $fillable = ['pv_no','acc_id','description','debit_credit','amount','pv_status','time','date','status','branch_id','username'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
