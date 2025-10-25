<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssigneItemRatesCustomerWise extends Model{
    protected $table = 'assigne_item_rates_customer_wise';
    protected $fillable = ['region_id','customer_id','location_id','category_id','subitem_id','minimum_rates','status','aircw_status','date','time','user_id','username','approved_username','deleted_username','accounting_year'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
