<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model{
    protected $table = 'leave_application';
    protected $fillable = ['emp_id','leave_day_type','reason','approval_status','approval_status_lm','approved','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
