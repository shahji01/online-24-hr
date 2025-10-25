<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model{
	protected $table = 'cities';
	protected $fillable = ['city','username','status','date','time'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
