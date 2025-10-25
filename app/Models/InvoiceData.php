<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceData extends Model{
    protected $table = 'inv_data';
    protected $fillable = ['inv_no','inv_date','category_id','sub_item_id','description','price','qty','amount','status','inv_status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
