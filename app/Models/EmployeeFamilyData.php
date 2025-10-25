<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFamilyData extends Model{
    protected $table = 'employee_family_data';
    protected $fillable = ['emr_no','family_name','family_relation','family_age','family_occupation','family_organization','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
