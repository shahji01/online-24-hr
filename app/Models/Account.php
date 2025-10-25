<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model{
	protected $table = 'accounts';
	protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
