<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{
	protected $table = 'category';
	protected $fillable = ['acc_id','main_ic','type','status','action','trail_id','username','date','time','branch_id','tran_type','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
