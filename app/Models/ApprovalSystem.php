<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalSystem extends Model{
    protected $table = 'approval_system';
    protected $fillable = ['emr_no','approval_check','approval_code','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
