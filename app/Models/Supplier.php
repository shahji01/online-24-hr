<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model{
	protected $table = 'supplier';
	protected $fillable = ['acc_id','company_id','region_id','type','company_business_name','nature_of_company_trade','business_address','pec_registration_certificate','company_registration_no','company_ntn_no','company_strn_no','company_service_tax_no','eobi_registration_no','sessi_registration_no','status','action','username','user_id','date','time','accounting_year'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
