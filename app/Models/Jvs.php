<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jvs extends Model{
    protected $table = 'jvs';
    protected $fillable = ['jv_date','jv_no','voucherType','description','username','status','jv_status','date','time','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
