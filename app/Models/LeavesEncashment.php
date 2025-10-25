<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavesEncashment extends Model{
    protected $table = 'leaves_encashment';
    protected $fillable = ['employee_id','total_remaining_leaves','amount','leave_from','leave_to','username' ,'status' ,'time' ,'date' ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
