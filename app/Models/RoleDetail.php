<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleDetail extends Model{
	protected $table = 'role_detail';
	protected $fillable = ['role_no','menu_id','right_add','right_edit','right_delete','right_view','right_print','right_export'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
