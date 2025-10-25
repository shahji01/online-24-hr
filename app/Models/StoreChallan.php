<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreChallan extends Model{
    protected $table = 'store_challan';
    protected $fillable = ['demand_id','region_id','department_id','accounting_year_id','store_challan_no','store_challan_date','store_challan_status','status','date','time','username','user_id','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
