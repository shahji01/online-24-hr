<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDeposit extends Model{
    protected $table = 'employee_deposit';
    protected $fillable = ['emp_id','deduction_month','deduction_year','deposit_name','deposit_amount','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
