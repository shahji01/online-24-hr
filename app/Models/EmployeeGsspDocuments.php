<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeGsspDocuments extends Model{
    protected $table = 'employee_gssp_documents';
    protected $fillable = ['emr_no','document_type','document_path','document_extension','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
