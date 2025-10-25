<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use DB;
use Config;
use Redirect;
use Session;
use Input;
use Auth;

class DeleteCompanyRecordsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteRowCompanyRecords()
    {

        CommonHelper::companyDatabaseConnection(Input::get('companyId'));;
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        DB::update('update '.$tableName.' set status = ? where id = ?',['2',$recordId]);
        CommonHelper::LogGenerator($tableName,$recordId,'delete','row is deleted','hr',Auth::user()->id);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','Successfully Deleted');
    }

    public function repostOneTableRecords()
    {
        FinanceHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        $column = Input::get('column');
        DB::update('update '.$tableName.' set '.$column.' = ? where id = ?',['1',$recordId]);
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Repost.');
    }

    public function approveOneTableRecords()
    {
        FinanceHelper::companyDatabaseConnection(Input::get('companyId'));
        $recordId = Input::get('recordId');
        $tableName =Input::get('tableName');
        $column = Input::get('column');
        DB::update('update '.$tableName.' set '.$column.' = ? where id = ?',['2',$recordId]);
        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataDelete','successfully Approved.');
    }
}

