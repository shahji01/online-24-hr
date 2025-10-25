<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrMfmSouthIncrementLetter extends Model{
    protected $table = 'hr_mfm_south_increment_letter';
    protected $fillable = ['emr_no','confirmation_from', 'approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
