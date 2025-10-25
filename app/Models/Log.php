<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model{
    protected $table = 'log';
    protected $fillable = ['table_name','activity_id','deleted_emr_no','activity','module','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

