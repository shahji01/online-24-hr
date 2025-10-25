<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrTerminationFormat2Letter extends Model{
    protected $table = 'hr_termination_format2_letter';
    protected $fillable = ['emr_no', 'approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
