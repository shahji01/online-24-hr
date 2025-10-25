<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holidays extends Model{
    protected $table = 'holidays';
    protected $fillable = ['id','holiday_date','holiday_name','month','year','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
