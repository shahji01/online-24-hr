<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model{
    protected $table = 'stock_transfer';
    protected $fillable = ['stock_transfer_no','stock_transfer_date','bilty_no','transfer_region_id','receiver_region_id','main_description','status','stock_transfer_status','username','user_id','approve_username','receiver_username','receiver_date','receiver_time','date','time','status','date','time','accounting_year'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}