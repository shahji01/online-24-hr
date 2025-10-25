<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequestTemplateData extends Model{
    protected $table = 'material_request_template_data';
    protected $fillable = ['mrt_id','accounting_year','mrt_no','category_id','sub_item_id','qty','mrt_status','status','date','time','user_id','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
