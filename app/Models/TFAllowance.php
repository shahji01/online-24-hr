<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TFAllowance extends Model{
    protected $table = 'tf_allowances';
    protected $fillable = ['employee_id','amount','month','year','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

