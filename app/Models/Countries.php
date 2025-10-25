<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model{
	protected $table = 'countries';
	protected $fillable = ['iso','name','nicename','iso3','numcode','phonecode','status'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
