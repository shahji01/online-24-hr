<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainMenuTitle extends Model{
	protected $table = 'main_menu_title';
	protected $fillable = ['main_menu_id','title','title_id','status','date'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
