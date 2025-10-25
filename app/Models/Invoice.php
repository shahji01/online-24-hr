<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model{
    protected $table = 'invoice';
    protected $fillable = ['inv_no','dc_no','vehicle_no','jvNo','rvNo','invoiceType','inv_against_discount','inv_date','consignee','main_description','inv_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
