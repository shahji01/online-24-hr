<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEquipments extends Model{
    protected $table = 'employee_equipments';
    protected $fillable = ['equipment_id','emr_no','status','approval_status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

