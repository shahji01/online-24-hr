<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBankData extends Model{
    protected $table = 'employee_bank_data';
    protected $fillable = ['emr_no','account_title','bank_name','account_no','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
