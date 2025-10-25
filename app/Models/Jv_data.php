<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jvs_data extends Model{
    protected $table = 'jv_data';
    protected $fillable = ['jv_no','acc_id','description','debit_credit','amount','jv_status','time','date','status','branch_id','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
