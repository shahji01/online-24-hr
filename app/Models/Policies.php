<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policies extends Model{
    protected $table = 'policies';
    protected $fillable = ['category_id','title','file_name','file_type','file_path','counter','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
