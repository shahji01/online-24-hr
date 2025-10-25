<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHrAudit extends Model{
    protected $table = 'employee_hr_audit';
    protected $fillable = ['emr_no','month','year','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
