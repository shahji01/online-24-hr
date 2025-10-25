<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferedLeaves extends Model{
    protected $table = 'transfered_leaves';
    protected $fillable = ['id','leaves_policy_id','acc_no','sick_leaves','annual_leaves','annual_leaves','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
