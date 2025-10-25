<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptNote extends Model{
    protected $table = 'goods_receipt_note';
    protected $fillable = ['grn_no','grn_date','pr_no','pr_date','demand_type','supplier_id','main_description','invoice_no','status','grn_status','user_id','username','date','time','approve_username','delete_username','accounting_year'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
