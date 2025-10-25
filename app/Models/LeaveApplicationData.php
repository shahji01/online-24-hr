<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApplicationData extends Model{
    protected $table = 'leave_application_data';
    protected $fillable = ['emp_id','leave_application_id','no_of_days','from_date','to_date','first_second_half',
        'first_second_half_date','short_leave_time_from','short_leave_time_to','short_leave_date','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
