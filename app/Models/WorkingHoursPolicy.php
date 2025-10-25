<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingHoursPolicy extends Model{
    protected $table = 'working_hours_policy';
    protected $fillable = ['working_hours_policy','start_working_hours_time','end_working_hours_time','working_hours_grace_time','end_time_for_comming_deduct_half_day','terms_conditions','short_leave_time','half_day_time','status','username','user_id','date','time','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
