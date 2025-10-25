<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderData extends Model{
    protected $table = 'purchase_order_data';
    protected $fillable = ['accounting_year','region_id','purchase_order_no','purchase_order_date','purchase_request_no','purchase_request_data_record_id','purchase_request_date','category_id','sub_item_id','supplier_id','supplier_location_id','sale_tax_head','sale_tax_status','qoutation_no','qoutation_date','delivery_days','payment_terms','greaterthan_valuation','lessthan_valuation','unit','purchase_order_qty','unit_price','sub_total','sub_total_with_persent','user_id','purchase_order_status','grn_status','status','date','time','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
