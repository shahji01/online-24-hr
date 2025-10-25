<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use DB;
use Auth;
use Config;
use Redirect;
use Session;
use Input;
use File;
use Hash;
use \Cache;

class DeleteMasterTableRecordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function deleteMasterTableReceord()
    {

        $id = Input::get('id');
        $tableName = Input::get('tableName');
        DB::update('update ' . $tableName . ' set status = ? where id = ?', ['2', $id]);

        Cache::forget($tableName);
        Cache::rememberForever($tableName, function () use ($tableName) {
            $values = DB::table($tableName)->where([['status', '=', 1]])->orderBy('id')->get();
            $data_array = [];

            foreach ($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataDelete', 'Successfully Deleted');
    }


    public function deleteUserAccountDetail()
    {
        $id = Input::get('id');
        $tableName = Input::get('tableName');
        DB::delete('delete from '.$tableName.' where status = ? and id = ?',['1',$id]);

        Session::flash('dataDelete','Successfully Deleted.');
    }

   
}
