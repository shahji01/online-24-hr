<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRebateDocuments extends Model{
    protected $table = 'employee_rebate_documents';
    protected $fillable = ['emp_id','rebate_id','rebate_file_name','rebate_file_type','rebate_file_path','counter','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
