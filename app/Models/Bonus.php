<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model{
    protected $table = 'bonus';
    protected $fillable = ['bonus_name','percent_of_salary','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
