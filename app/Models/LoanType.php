<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanType extends Model{
	protected $table = 'loan_type';
	protected $fillable = ['loan_type_name','status','username','date','time','company_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
