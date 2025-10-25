<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model{
    protected $table = 'grades';
    protected $fillable = ['employee_grade_type','category','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

