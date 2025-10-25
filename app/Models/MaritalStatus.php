<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model{
	protected $table = 'marital_status';
	protected $fillable = ['marital_status_name','status','username','action','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
