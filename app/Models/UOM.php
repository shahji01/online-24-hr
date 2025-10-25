<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UOM extends Model{
    protected $table = 'uom';
    protected $fillable = ['uom_name','status','username','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
