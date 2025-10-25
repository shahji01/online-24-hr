<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class projectTransferLetter extends Model{
    protected $table = 'project_transfer_letter';
    protected $fillable = ['emp_project_id','file_type','letter_uploading','date','time','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
