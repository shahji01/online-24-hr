<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingStatus extends Model{
    protected $table = 'building_status';
    protected $fillable = ['building_id','building_status','remarks','status_date','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
