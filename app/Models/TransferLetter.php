<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferLetter extends Model{
    protected $table = 'transfer_letter';
    protected $fillable = ['emp_location_id','file_type','date','time','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
