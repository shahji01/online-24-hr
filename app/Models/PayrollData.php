<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollData extends Model{
    protected $table = 'payroll_data';
    protected $fillable = ['emp_id','total_days','total_present','total_absent','total_holidays','total_late_arrivals','deduction_days','month','year','month','status','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
