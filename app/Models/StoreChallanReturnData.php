<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreChallanReturnData extends Model{
    protected $table = 'store_challan_return_data';
    protected $fillable = ['store_challan_id','store_challan_data_id','store_challan_return_id','store_challan_return_no','store_challan_return_date','category_id','sub_item_id','return_qty','sub_description','store_challan_return_status','status','date','time','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
