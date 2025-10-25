<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cpl extends Model{
    protected $table = 'cpl';
    protected $fillable = ['cpl','employee_id','month','year','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
