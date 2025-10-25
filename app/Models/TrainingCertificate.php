<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCertificate extends Model{
    protected $table = 'training_certificate';
    protected $fillable = ['training_id','file_type','certificate_uploading','date','time','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
