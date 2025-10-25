<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipments extends Model{
    protected $table = 'employee_equipments';
    protected $fillable = ['equipment_name','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

