<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model{
	protected $table = 'payslip';
	protected $fillable = ['ps_no','emp_id','department_id','month','year','basic_salary','total_allowance','total_deduction','net_salary','salary_status','status','username','date','time'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
