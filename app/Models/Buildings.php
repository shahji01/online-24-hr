<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buildings extends Model{
    protected $table = 'buildings';
    protected $fillable = ['building_name','building_code','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
