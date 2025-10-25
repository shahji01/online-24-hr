<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrTransferLetter extends Model{
    protected $table = 'hr_transfer_letter';
    protected $fillable = ['emr_no','transfer_date', 'approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
