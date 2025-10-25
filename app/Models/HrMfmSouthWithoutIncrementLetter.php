<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrMfmSouthWithoutIncrementLetter extends Model{
    protected $table = 'hr_mfm_south_without_increment_letter';
    protected $fillable = ['emr_no','performance_from','performance_to','confirmation_from', 'approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
