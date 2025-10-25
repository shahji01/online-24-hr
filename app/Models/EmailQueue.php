<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model{
    protected $table = 'email_queue';
    protected $fillable = ['emp_code','month','year','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;

}
