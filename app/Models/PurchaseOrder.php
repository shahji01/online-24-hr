<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model{
    protected $table = 'purchase_order';
    protected $fillable = ['region_id','department_id','accounting_year','purchase_order_no','purchase_order_date','purchase_request_no','purchase_request_date','delivery_days','delivery_place','payment_terms','qoutation_no','qoutation_date','supplier_id','description','purchase_order_status','status','date','time','username','user_id','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
