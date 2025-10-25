<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceSalary extends Model{
    protected $table = 'advance_salary';
    protected $fillable = ['emp_id','employee_id','advance_salary_amount','salary_needed_on','deduction_month','deduction_year','detail','username','approval_status','time','date','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
