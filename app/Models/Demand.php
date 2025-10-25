<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model{
    protected $table = 'demand';
    protected $fillable = ['accounting_year','region_id','customer_id','location_id','ticket_no','priority_id','demand_type','item_demand_type','slip_no','demand_no','demand_date','department_id','description','s_d_bar_code','demand_status','status','date','time','user_id','username','approve_username','approval_date','approval_time','delete_username','delete_date','delete_time','deliver_stock_status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
