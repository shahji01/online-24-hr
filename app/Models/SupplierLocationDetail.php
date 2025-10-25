<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierLocationDetail extends Model{
	protected $table = 'supplier_location_detail';
	protected $fillable = ['acc_id','region_id','company_id','supplier_id','location_name','location_contact_no','email_address','location_contact_person_no','status','date','time','username','user_id','accounting_year'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
