<?php

use App\User;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Mail;
Route::auth();

Route::get('logout', 'Auth\LoginController@logout', function () {
    return abort(404);
});

Route::get('login',array('as'=>'login',function () {

    if (!Auth::check()) {
        return Redirect::to('/');
    }
}));

Route::get('/error', function () {
    return 'Not Authorized';
});

Route::get('/', function () {

    if (Auth::check()) {
        $acc_type = Auth::user()->acc_type;

        if($acc_type == 'client'):
            return Redirect::to('/dc/hrDashboard?m=12');
        elseif($acc_type == 'user' || $acc_type == 'admin'):
            return Redirect::to('/dc/userDashboard?m='.Auth::user()->company_id);
        endif;
    }
    else {
        return view('auth.login');
    }
});

Route::get('/insufficientPrivileges', function () {
    return view('insufficientPrivileges');
})->name('insufficientPrivileges');

Route::get('/hikvision/device-info', 'HikvisionController@deviceInfo');
Route::get('/hikvision/attendance-logs', 'HikvisionController@attendanceLogs');

Route::group(['prefix' => 'HrReports','before' => 'csrf'], function () {

    Route::get('/viewTurnoverReportForm','HrReportsController@viewTurnoverReportForm');
    Route::get('/viewOnboardReportForm','HrReportsController@viewOnboardReportForm');
    Route::get('/viewIncrementReportForm','HrReportsController@viewIncrementReportForm');
    Route::get('/viewWarningReportForm','HrReportsController@viewWarningReportForm');
    Route::get('/viewEmployeeReportForm','HrReportsController@viewEmployeeReportForm');
    Route::get('/viewTransferReportForm','HrReportsController@viewTransferReportForm');
    Route::get('/viewMedicalReportForm','HrReportsController@viewMedicalReportForm');
    Route::get('/viewTrainingReportForm','HrReportsController@viewTrainingReportForm');
    Route::get('/viewGratuityReportForm','HrReportsController@viewGratuityReportForm');
    Route::get('/viewEmployeeExpReportForm','HrReportsController@viewEmployeeExpReportForm');

    Route::get('/viewTurnoverReport','HrReportsController@viewTurnoverReport');
    Route::get('/viewOnboardReport','HrReportsController@viewOnboardReport');
    Route::get('/viewIncrementReport','HrReportsController@viewIncrementReport');
    Route::get('/viewWarningReport','HrReportsController@viewWarningReport');
    Route::get('/viewEmployeeReport','HrReportsController@viewEmployeeReport')->middleware('isAdmin');
    Route::get('/viewTransferReport','HrReportsController@viewTransferReport');
    Route::get('/viewMedicalReport','HrReportsController@viewMedicalReport');
    Route::get('/viewTrainingReport','HrReportsController@viewTrainingReport');
    Route::get('/viewGratuityReport','HrReportsController@viewGratuityReport');
    Route::get('/viewEmployeeExpReport','HrReportsController@viewEmployeeExpReport');
});

Route::group(['prefix' => 'dc','before' => 'csrf'], function () {

    Route::get('/hrDashboard','DashboardController@hrDashboard');
    Route::get('/userDashboard','DashboardController@userDashboard');
    Route::get('/tmsDashboard','DashboardController@tmsDashboard');
    Route::get('/tmSummaryDashboard','DashboardController@tmSummaryDashboard');
    Route::get('/clientDashboard','DashboardController@clientDashboard');
    
    
});

Route::group(['prefix' => 'ddc','before' => 'csrf'], function () {
      Route::get('/basicInfo','DashboardDataCallController@basicInfo');
	  Route::get('/checkingPassword','DashboardDataCallController@checkingPassword');
	  Route::get('/filterUserDashboard','DashboardDataCallController@filterUserDashboard');
	  Route::post('/addEducationDetails','DashboardDataCallController@addEducationDetails');
	  Route::get('/filterTeamVise','DashboardDataCallController@filterTeamVise'); 
	  Route::get('/viewDashboardComponent','DashboardDataCallController@viewDashboardComponent');
      Route::get('/filterTMSDashboard','DashboardDataCallController@filterTMSDashboard');
      Route::get('/filterTMSDashboardData','DashboardDataCallController@filterTMSDashboardData');
      Route::get('/filterTMSummaryDashboard','DashboardDataCallController@filterTMSummaryDashboard');
      Route::get('/filterTMSummaryDashboardData','DashboardDataCallController@filterTMSummaryDashboardData');
      Route::get('/filterClientSummaryDashboardData','DashboardDataCallController@filterClientSummaryDashboardData');
      
      Route::get('/getProjectTaskSummary','DashboardDataCallController@getProjectTaskSummary');
      Route::get('/getProjectSubTaskSummary','DashboardDataCallController@getProjectSubTaskSummary');

      Route::get('/employeeAssignProjectDetail','DashboardDataCallController@employeeAssignProjectDetail');
      Route::get('/employeeAssignProjectTaskDetail','DashboardDataCallController@employeeAssignProjectTaskDetail');
      Route::get('/employeeAssignProjectSubTaskDetail','DashboardDataCallController@employeeAssignProjectSubTaskDetail');



      Route::get('/employeeProjectTaskDashboardDetail','DashboardDataCallController@employeeProjectTaskDashboardDetail');
      Route::get('/employeeProjectSubTaskDashboardDetail','DashboardDataCallController@employeeProjectSubTaskDashboardDetail');
        
});

Route::group(['prefix' => 'cj'], function () {

    Route::get('/loanCronJob','CronJobController@loanCronJob');
    Route::get('/arrearCronJob','CronJobController@arrearCronJob');
    Route::get('/abnormalitiesCheck','CronJobController@abnormalitiesCheck');
});

Route::group(['prefix' => 'log','before' => 'csrf'], function () {
    Route::get('/viewLog','LogController@viewLog');
});

Route::group(['prefix' => 'ldc','before' => 'csrf'], function () {
    Route::get('/viewLogDetail','LogDataCallController@viewLogDetail');
    Route::get('/viewActivityDetail','LogDataCallController@viewActivityDetail');
});

Route::group(['prefix' => 'tms', 'before' => 'csrf'], function () {

    // Routes for Company Projects
    Route::prefix('company-projects')->group(function () {
        Route::get('/', 'CompanyProjectController@index')->name('company-projects.index');
        Route::get('/create', 'CompanyProjectController@create')->name('company-projects.create');
        Route::post('/store', 'CompanyProjectController@store')->name('company-projects.store');
        Route::post('/status', 'CompanyProjectController@status')->name('company-projects.status');
        Route::get('/{id}/edit', 'CompanyProjectController@edit')->name('company-projects.edit');
        Route::post('/update', 'CompanyProjectController@update')->name('company-projects.update');
        Route::post('/destroy/{id}', 'CompanyProjectController@destroy')->name('company-projects.destroy');
        Route::get('/assignProjectToEmployeeForm', 'CompanyProjectController@assignProjectToEmployeeForm')->name('company-projects.assignProjectToEmployeeForm');
        Route::get('/updateAssignEmployeesDetail','CompanyProjectController@updateAssignEmployeesDetail')->name('company-projects.updateAssignEmployeesDetail');;
    });

    // Routes for Project Tasks
    Route::prefix('project-tasks')->group(function () {
        Route::get('/', 'ProjectTaskController@index')->name('project-tasks.index');
        Route::get('/create', 'ProjectTaskController@create')->name('project-tasks.create');
        Route::post('/store', 'ProjectTaskController@store')->name('project-tasks.store');
        Route::post('/status', 'ProjectTaskController@status')->name('project-tasks.status');
        Route::get('/{id}/edit', 'ProjectTaskController@edit')->name('project-tasks.edit');
        Route::post('/update', 'ProjectTaskController@update')->name('project-tasks.update');
        Route::post('/destroy/{id}', 'ProjectTaskController@destroy')->name('project-tasks.destroy');
        Route::get('/viewProjectTaskDetail','ProjectTaskController@viewProjectTaskDetail')->name('project-tasks.viewProjectTaskDetail');
        Route::get('/updateDetailAgainstTask','ProjectTaskController@updateDetailAgainstTask')->name('project-tasks.updateDetailAgainstTask');
        Route::get('/taskStart','ProjectTaskController@taskStart')->name('project-tasks.taskStart');
        Route::get('/taskStop','ProjectTaskController@taskStop')->name('project-tasks.taskStop');
        Route::get('/taskSendTesting','ProjectTaskController@taskSendTesting')->name('project-tasks.taskSendTesting');
        Route::get('/taskCompleted','ProjectTaskController@taskCompleted')->name('project-tasks.taskCompleted');
        Route::get('/taskReassign','ProjectTaskController@taskReassign')->name('project-tasks.taskReassign');

        Route::post('/project_task_comment', 'ProjectTaskController@projectTaskComment')->name('project-tasks.project_task_comment');
        Route::get('/loadCommentAgainstTask', 'ProjectTaskController@loadCommentAgainstTask')->name('project-tasks.loadCommentAgainstTask');
        Route::get('/project_task_reply_against_comment', 'ProjectTaskController@projectTaskReplyAgainstComment')->name('project-tasks.project_task_reply_against_comment');
        
        Route::get('/project-task-calender','ProjectTaskController@projectTaskCalender')->name('project-tasks.project-task-calender');
        Route::get('/tasks', 'ProjectTaskController@getCalendarTasks')->name('project-tasks.tasks');
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', 'CustomerController@index')->name('customers.index');
        Route::get('/create', 'CustomerController@create')->name('customers.create');
        Route::post('/store', 'CustomerController@store')->name('customers.store');
        Route::post('/status', 'CustomerController@status')->name('customers.status');
        Route::get('/{id}/edit', 'CustomerController@edit')->name('customers.edit');
        Route::post('/update', 'CustomerController@update')->name('customers.update');
        Route::post('/destroy/{id}', 'CustomerController@destroy')->name('customers.destroy');
    });

    Route::prefix('project-sub-task')->group(function () {
        Route::get('/', 'ProjectSubTaskController@index')->name('project-sub-task.index');
        Route::get('/create', 'ProjectSubTaskController@create')->name('project-sub-task.create');
        Route::post('/store', 'ProjectSubTaskController@store')->name('project-sub-task.store');
        Route::post('/status', 'ProjectSubTaskController@status')->name('project-sub-task.status');
        Route::get('/{id}/edit', 'ProjectSubTaskController@edit')->name('project-sub-task.edit');
        Route::post('/update', 'ProjectSubTaskController@update')->name('project-sub-task.update');
        Route::post('/destroy/{id}', 'ProjectSubTaskController@destroy')->name('project-sub-task.destroy');
        Route::get('/loadTaskAgainstProjectId', 'ProjectSubTaskController@loadTaskAgainstProjectId')->name('project-sub-task.loadTaskAgainstProjectId');
        Route::get('/viewProjectSubTaskDetail','ProjectSubTaskController@viewProjectSubTaskDetail')->name('project-sub-task.viewProjectSubTaskDetail');
        Route::get('/subTaskStart','ProjectSubTaskController@subTaskStart')->name('project-sub-task.subTaskStart');
        Route::get('/subTaskStop','ProjectSubTaskController@subTaskStop')->name('project-sub-task.subTaskStop');
        Route::get('/subTaskSendTesting','ProjectSubTaskController@subTaskSendTesting')->name('project-sub-task.subTaskSendTesting');
        Route::get('/subTaskCompleted','ProjectSubTaskController@subTaskCompleted')->name('project-sub-task.subTaskCompleted');
        Route::get('/subTaskReassign','ProjectSubTaskController@subTaskReassign')->name('project-sub-task.subTaskReassign');
        Route::get('/updateDetailAgainstSubTask','ProjectSubTaskController@updateDetailAgainstSubTask')->name('project-sub-task.updateDetailAgainstSubTask');
        Route::post('/project_sub_task_comment', 'ProjectSubTaskController@projectSubTaskComment')->name('project-sub-task.project_sub_task_comment');
        Route::get('/loadCommentAgainstSubTask', 'ProjectSubTaskController@loadCommentAgainstSubTask')->name('project-sub-task.loadCommentAgainstSubTask');
        Route::get('/project_sub_task_reply_against_comment', 'ProjectSubTaskController@projectSubTaskReplyAgainstComment')->name('project-sub-task.project_sub_task_reply_against_comment');
        
    });

});

require('modules/users.php');
require('modules/selectlist.php');
require('modules/hr.php');
