<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocuments extends Model{
    protected $table = 'employee_documents';
    protected $fillable = ['emr_no','documents_upload_check','counter','file_name','file_type','file_path','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
