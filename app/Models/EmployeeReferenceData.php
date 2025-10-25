<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeReferenceData extends Model{
    protected $table = 'employee_reference_data';
    protected $fillable = ['emr_no','reference_name','reference_designation','reference_organization',
        'reference_address','reference_country','reference_contact','reference_relationship','status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
