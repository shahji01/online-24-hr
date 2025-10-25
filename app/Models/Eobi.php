<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eobi extends Model{
    protected $table = 'eobi';
    protected $fillable = ['company_id','EOBI_name','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
