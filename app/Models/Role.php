<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{
	protected $table = 'roles';
	protected $fillable = ['role_name','main_modules','menu_titles','submenu_id','crud_rights',
		'location_permission','company_list','username','status','created_at','updated_at'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
