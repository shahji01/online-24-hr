<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model{
    protected $table = 'transactions';
    protected $fillable = ['acc_id','acc_code','particulars','opening_bal','acc_year_id','debit_credit','amount','voucher_no','voucher_type','v_date','date','time','action','username','delete_username','status','branch_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
