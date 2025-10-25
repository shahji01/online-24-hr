<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavesData extends Model{
    protected $table = 'leaves_data';
    protected $fillable = ['leaves_policy_id','leave_type_id','no_of_leaves','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
