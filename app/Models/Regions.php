<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model{
    protected $table = 'regions';
    protected $fillable = ['employee_region','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

