<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrTerminationFormat1Letter extends Model{
    protected $table = 'hr_termination_format1_letter';
    protected $fillable = ['emr_no','termination_date', 'approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
