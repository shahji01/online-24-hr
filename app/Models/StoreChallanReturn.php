<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreChallanReturn extends Model{
    protected $table = 'store_challan_return';
    protected $fillable = ['region_id','store_challan_return_no','store_challan_return_date','deliver_person_name','receiver_person_name','description','store_challan_return_status','status','date','time','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
