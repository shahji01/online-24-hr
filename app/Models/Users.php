<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model{
    protected $table = 'users';
    protected $fillable = ['emp_id','employee_id','name','username','email','mobile_no','password','identity','password_status','status',
        'remember_token','timestamps','acc_type','updated_at','created_at','company_id','dbName','role_no'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
