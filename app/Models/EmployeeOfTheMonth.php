<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOfTheMonth extends Model{
    protected $table = 'employee_of_the_month';
    protected $fillable = ['emp_id','month','year','remarks','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
