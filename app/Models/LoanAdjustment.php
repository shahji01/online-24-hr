<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanAdjustment extends Model
{
    //
    protected $table = 'loan_adjustment';
    protected $fillable = ['loan_id','month','year','amount','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
