<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeavingReason extends Model{
    protected $table = 'employee_leaving_reason';
    protected $fillable = ['emr_no','last_working_date','leaving_reason','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
