<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model{
    protected $table = 'priority';
    protected $fillable = ['accounting_year','company_id','priority_name','priority_time_limit','priority_color_code','status','username','user_id','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
