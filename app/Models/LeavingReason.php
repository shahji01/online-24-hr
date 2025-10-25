<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavingReason extends Model{
    protected $table = 'leaving_reason';
    protected $fillable = ['emr_no','last_working_date','leaving_reason','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
