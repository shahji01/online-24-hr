<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavesPolicy extends Model{
    protected $table = 'leaves_policy';
    protected $fillable = ['leaves_policy_name','policy_month_from','policy_month_till','policy_year_till','total_leaves',
        'fullday_deduction_rate','halfday_deduction_rate' ,'per_hour_deduction_rate' ,
        'terms_conditions' ,'username' ,'status' ,'time' ,'date' ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
