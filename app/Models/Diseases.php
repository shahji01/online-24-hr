<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diseases extends Model{
    protected $table = 'diseases';
    protected $fillable = ['disease_type','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
