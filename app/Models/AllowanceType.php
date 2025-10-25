<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowanceType extends Model{
    protected $table = 'allowance_types';
    protected $fillable = ['allowance_type','taxable','status','username','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
