<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LoanTopUp extends Model
{
    //
    protected $table = 'loan_top_up';
    protected $fillable = ['loan_id','loan_top_up_amount','needed_date','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
