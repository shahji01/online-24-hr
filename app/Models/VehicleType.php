<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model{
    protected $table = 'vehicle_type';
    protected $fillable = ['vehicle_type_name','vehicle_type_cc','company_id','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
