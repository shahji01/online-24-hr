<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMedical extends Model{
    protected $table = 'employee_medical';
    protected $fillable = ['emr_no','disease_type_id', 'disease_date', 'status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
