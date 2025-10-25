<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rebate extends Model{
    protected $table = 'rebate';
    protected $fillable = ['emp_id','month','year','type','nature','actual_investment','rebate_amount','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

