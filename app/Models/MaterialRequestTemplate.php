<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequestTemplate extends Model{
    protected $table = 'material_request_template';
    protected $fillable = ['accounting_year','template_name','mrt_no','description','mrt_status','status','date','time','user_id','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
