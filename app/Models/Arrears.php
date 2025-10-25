<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrears extends Model{
    protected $table = 'arrears';
    protected $fillable = ['emr_no','arrears_amount','month','year','arrear_status','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
