<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model{
    protected $table = 'locations';
    protected $fillable = ['employee_location','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

