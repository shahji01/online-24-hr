<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestHiringCandidates extends Model{
    protected $table = 'requesthiring_candidates';
    protected $fillable = ['email','contact_no','expected_salary','cv_path','status','data','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
