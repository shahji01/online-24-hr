<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandData extends Model{
    protected $table = 'demand_data';
    protected $fillable = ['demand_id','accounting_year','demand_no','demand_date','category_id','sub_item_id','qty','required_date','sub_description','demand_status','store_challan_status','purchase_request_status','goods_forward_status','demand_send_type','status','date','time','user_id','username','approve_username','approval_date','approval_time','delete_username','delete_date','delete_time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
