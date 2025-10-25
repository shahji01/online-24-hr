<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subitem extends Model{
	protected $table = 'subitem';
	protected $fillable = ['revenue_assign_head_id','accounting_year','item_code','unit_of_gram','sub_ic','main_ic_id','acc_id','reorder_level','time','date','action','username','status','type','uom','stockType','itemType','company_id','delete_username'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
