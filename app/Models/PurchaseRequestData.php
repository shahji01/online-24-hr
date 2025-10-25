<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestData extends Model{
    protected $table = 'purchase_request_data';
    protected $fillable = ['purchase_request_id','demand_id','demand_data_id','accounting_year','purchase_request_no','purchase_request_date','required_date','category_id','sub_item_id','qty','purchase_request_status','store_challan_status','purchase_order_status','goods_forward_status','purchase_request_send_type','status','date','time','username','user_id','approve_username','delete_username','packing_list_status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
