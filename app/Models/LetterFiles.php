<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterFiles extends Model{
    protected $table = 'letter_files';
    protected $fillable = ['emr_no','letter_type','letter_path','file_type','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

