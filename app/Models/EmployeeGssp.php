<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeGssp extends Model{
    protected $table = 'employee_gssp';
    protected $fillable = ['emr_no','no_of documents','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
