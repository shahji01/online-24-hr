<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fara extends Model{
	protected $table = 'fara';
	protected $fillable = ['sc_no','sc_date','demand_no','demand_date','scr_no','scr_date','pr_no','pr_date','grn_no','grn_date','main_ic_id','sub_ic_id','supp_id','qty','value','action','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
