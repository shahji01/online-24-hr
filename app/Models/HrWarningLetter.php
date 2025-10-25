<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrWarningLetter extends Model{
    protected $table = 'hr_warning_letter';
    protected $fillable = ['emp_id','content','approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
