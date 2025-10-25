<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model{
    protected $table = 'loan_request';
    protected $fillable = ['emp_id','needed_on','description','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
