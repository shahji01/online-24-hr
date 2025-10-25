<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreChallanData extends Model{
    protected $table = 'store_challan_data';
    protected $fillable = ['store_challan_id','demand_id','demand_data_id','lot_no','store_challan_no','store_challan_date','category_id','sub_item_id','issue_qty','rate','receiver_name','store_challan_status','status','date','time','accounting_year_id','user_id','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
