<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLocation extends Model{
    protected $table = 'customer_location';
    protected $fillable = ['accounting_year','region_id','acc_id','customer_id','location_name','contact_no','contact_person_no','email_address','irl_limit','inscope_limit','remarks','ticket_prefix','status','username','user_id','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
