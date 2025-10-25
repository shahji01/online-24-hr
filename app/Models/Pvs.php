<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pvs extends Model{
	protected $table = 'pvs';
	protected $fillable = ['cleared_date','pv_date','pv_no','slip_no','grn_no','grn_date','voucherType','cheque_no','cheque_date','post_dated','description','username','status','pv_status','date','time','clearence','auto_clearence','trail_id','clearence','auto_clearence','approve_username','delete_username'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
