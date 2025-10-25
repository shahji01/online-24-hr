<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPrivileges extends Model{
    protected $table = 'menu_privileges';
    protected $fillable = ['emp_id','employee_id','customer_id','role_id','main_modules','menu_titles','submenu_id','crud_rights',
        'location_permission','company_list','username','status','created_at','updated_at'];
    protected $primaryKey = 'id';
    public $timestamps = true;
}
