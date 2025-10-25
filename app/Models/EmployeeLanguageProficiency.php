<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLanguageProficiency extends Model{
    protected $table = 'employee_language_proficiency';
    protected $fillable = ['emr_no','language_name','reading_skills','writing_skills','speaking_skills','status','time','date','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
