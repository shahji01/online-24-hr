<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model{
    protected $table = 'purchase_request';
    protected $fillable = ['demand_id','region_id','department_id','accounting_year','purchase_request_type','purchase_request_no','purchase_request_date','description','purchase_request_status','status','date','time','username','user_id','approve_username','delete_username','packing_list_status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
