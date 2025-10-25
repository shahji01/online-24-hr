<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login','ApplicationController@login');
Route::get('/getAttendance','ApplicationController@getAttendance');
Route::get('/getAttendanceByLocation','ApplicationController@getAttendanceByLocation');
Route::post('/addAttendance','ApplicationController@addAttendance');
Route::post('/addAttendanceByLocation','ApplicationController@addAttendanceByLocation');
Route::get('/viewAttendanceByLocation','ApplicationController@viewAttendanceByLocation');
Route::get('/fetchlocations','ApplicationController@fetchlocations');
Route::get('/deleteAttendance','ApplicationController@deleteAttendance');
Route::get('/getUserData','ApplicationController@getUserData');
Route::get('/viewAttendanceList','ApplicationController@viewAttendanceList');
Route::get('/viewAttendanceReport','ApplicationController@viewAttendanceReport');
Route::get('/viewAttendanceRequestQuery','ApplicationController@viewAttendanceRequestQuery');
Route::put('/changePassword','ApplicationController@changePassword');
Route::put('/forgotPassword','ApplicationController@forgotPassword');
Route::get('/leaveType','ApplicationController@leaveType');
Route::post('/addLeave','ApplicationController@addLeave');
Route::get('/remainingLeave','ApplicationController@remainingLeave');
Route::get('/fetchReasons','ApplicationController@fetchReasons');
Route::get('/appliedLeave','ApplicationController@appliedLeave');
Route::get('/viewLeaveApplicationRequestList','ApplicationController@viewLeaveApplicationRequestList');
Route::get('/salaryList','ApplicationController@salaryList');
Route::get('/salaryDetail','ApplicationController@salaryDetail');
Route::get('/yearlySalary','ApplicationController@yearlySalary');
Route::get('/empSalaryYear','ApplicationController@empSalaryYear');
Route::get('/fetchAttendanceRequestQueries','ApplicationController@fetchAttendanceRequestQueries');
Route::patch('/approveOrRejectAttendanceQuery','ApplicationController@approveOrRejectAttendanceQuery');
Route::post('/addAttendanceRequestQueriesDetail','ApplicationController@addAttendanceRequestQueriesDetail');
Route::get('/fetchAttendanceByDate','ApplicationController@fetchAttendanceByDate');
Route::delete('/deleteAttendanceRequest','ApplicationController@deleteAttendanceRequest');
Route::get('/leavesBalance','ApplicationController@leavesBalance');




