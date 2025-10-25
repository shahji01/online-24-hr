<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeCategory extends Model{
    protected $table = 'employee_category';
    protected $fillable = ['employee_category_name','company_id','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
