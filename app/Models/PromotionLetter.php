<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionLetter extends Model{
    protected $table = 'promotion_letter';
    protected $fillable = ['promotion_id','file_type','letter_uploading','date','time','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
