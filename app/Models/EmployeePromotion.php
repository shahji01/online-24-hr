<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePromotion extends Model{
    protected $table = 'employee_promotion';
    protected $fillable = ['sub_department_id','employee_id','promotion_date','designation_id','grade_id', 'increment', 'salary','approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
