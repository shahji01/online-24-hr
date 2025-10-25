<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rvs extends Model{
	protected $table = 'rvs';
	protected $fillable = ['rv_date','rv_no','description','sup_comments','maker_comments','username','status','rv_status','voucherType','maker_comments','date','time','action','branch_id','cheque_no','cheque_date','post_dated','trail_id','invoiceNo'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
