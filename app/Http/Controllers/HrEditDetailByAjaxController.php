<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use \Cache;
use Config;
use Input;
use Session;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Payslip;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\JobType;
use App\Models\SubDepartment;
use App\Models\MaritalStatus;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\CarPolicy;
use App\Models\LeaveApplicationData;
use App\Models\LeaveApplication;



class HrEditDetailByAjaxController extends Controller
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

    public function editLeaveApplicationDetail()
    {
        $leave_application_id = Input::get('leave_application_id');
        $employee_id = Input::get('employee_id');
        $leaveApplicationCheck1 = LeaveApplicationData::where([['leave_application_id','!=', $leave_application_id],['view', '=', 'yes'], ['leave_policy_id', '=', Input::get('leave_policy_id')], ['from_date', '=', Input::get('from_date')], ['first_second_half', '=', ''], ['employee_id', '=', $employee_id]])->count();

        $leaveApplicationCheck2 = LeaveApplicationData::where([['leave_application_id','!=', $leave_application_id],['view', '=', 'yes'], ['leave_policy_id', '=', Input::get('leave_policy_id')], ['first_second_half_date', '=', Input::get('first_second_half_date')],
            ['employee_id', '=', $employee_id], ['first_second_half', '!=', '']])->count();

        if ($leaveApplicationCheck1 > 0 || $leaveApplicationCheck2 > 0):

            return "Leave Application Already Exist on Date " . Input::get('from_date') . Input::get('first_second_half_date');

        else:

            $data1['leave_policy_id'] = Input::get('leave_policy_id');
            $data1['leave_type'] = Input::get('leave_type');
            $data1['leave_day_type'] = Input::get('leave_day_type');
            $data1['reason'] = Input::get('reason');
            $data1['leave_address'] = Input::get('leave_address');
            $data1['approval_status'] = 1; // 1 pending 2 approve 3 reject
            $data1['approval_status_lm'] = 1; // 1 pending 2 approve 3 reject
            $data1['approved'] = 1;
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('leave_application')->where([['id','=', $leave_application_id]])->update($data1);


            if (Input::get('leave_day_type') == 1):

                $data['leave_policy_id'] = Input::get('leave_policy_id');
                $data['no_of_days'] = Input::get('no_of_days');
                $data['from_date'] = Input::get('from_date');
                $data['to_date'] = Input::get('to_date');
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('leave_application_data')->where([['leave_application_id','=', $leave_application_id]])->update($data);

            elseif (Input::get('leave_day_type') == 2):

                $data['leave_policy_id'] = Input::get('leave_policy_id');
                $data['no_of_days'] = Input::get('no_of_days');
                $data['first_second_half'] = Input::get('first_second_half');
                $data['first_second_half_date'] = Input::get('first_second_half_date');
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('leave_application_data')->where([['leave_application_id','=', $leave_application_id]])->update($data);
            endif;

            $employees = Cache::get('employee');
            $emp_id = '';
            $emp_name = '';
            $reporting_manager_id = '';
            if (array_key_exists($employee_id, $employees)):
                $emp_id = $employees[$employee_id]->emp_id;
                $emp_name = $employees[$employee_id]->emp_name;
                $reporting_manager_id = $employees[$employee_id]->reporting_manager_id;
            endif;

            if ($reporting_manager_id != ''):
                if (array_key_exists($reporting_manager_id, $employees)):
                    $email = $employees[$reporting_manager_id]->official_email;
                else:
                    $email = "aliasghar@innovative-net.com";
                endif;
            else:
                $email = "aliasghar@innovative-net.com";
            endif;

            $leave_types = Cache::get('leave_type');
            if (array_key_exists(Input::get('leave_type'), $leave_types)):
                $leave_type_name = $leave_types[Input::get('leave_type')]->leave_type_name;
            endif;

            $from_date = HrHelper::date_format(Input::get('from_date'));
            $to_date = HrHelper::date_format(Input::get('to_date'));
            $days = '';
            if (Input::get('no_of_days') > 1) {
                $days = 'days';
            } else {
                $days = 'day';
            }

            CommonHelper::companyDatabaseConnection(Input::get('company_id'));
            $data3['leave_application_id'] = $leave_application_id;
            $data3['leave_type_name'] = $leave_type_name;
            $data3['leave_no_of_days'] = Input::get('no_of_days');
            $data3['leave_from_date'] = Input::get('from_date')?Input::get('from_date'):Input::get('first_second_half_date');
            $data3['leave_to_date'] = Input::get('to_date')?Input::get('to_date'):Input::get('first_second_half_date');
            $data3['type'] = 0;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['username'] = Auth::user()->name;
            $data3['status'] = 1;

            DB::table('leave_email_queue')->insert($data3);
            CommonHelper::reconnectMasterDatabase();

            return "1";
        endif;
    }


//    public function EditEmployeeCarPolicyDetail()
//    {
//
//          CommonHelper::companyDatabaseConnection(Input::get('m'));
//          DB::table('employee')->where('id', Input::get('id'))->update(['car_policy_id'=>Input::get('policy_id')]);
//
//            $policy = Employee::select('id')->where([['id','=',Input::get('id')],['car_policy_id','>','0']])->count();
//
//            if($policy == '0'):
//                $label ='';
//                $cancel_assign_btn  = ' <li role="presentation text-center">
//                       <a style="cursor:pointer;" class="edit-modal" onclick="assignCarPolicy('.Input::get('id').','.Input::get('assign_id').')">Assign</a>
//                       </li>';
//
//            else:
//                $label = 'Policy Status: <span class="label label-success">Assigned</span>';
//                $cancel_assign_btn  = ' <li role="presentation text-center">
//                       <a style="cursor:pointer;" class="edit-modal" onclick="cancelCarPolicy('.Input::get('id').','.Input::get('policy_id').')">Cancel</a>
//                       </li>';
//
//            endif;
//
//
//
//            $object = ['label'=>$label,'cancel_assign_btn'=>$cancel_assign_btn];
//          echo json_encode(['data'=>$object]);
//    }


//    public function EditEmployeeTaxDetail()
//    {
//
//
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        DB::table('employee')->where('id', Input::get('id'))->update(['tax_id'=>Input::get('tax_id')]);
//
//        $policy = Employee::select('id')->where([['id','=',Input::get('id')],['tax_id','>','0']])->count();
//
//        if($policy == '0'):
//            $label ='';
//            $cancel_assign_btn  = ' <li role="presentation text-center">
//                       <a style="cursor:pointer;" class="edit-modal" onclick="assignTax('.Input::get('id').','.Input::get('assign_id').')">Assign</a>
//                       </li>';
//
//        else:
//            $label = 'Policy Status: <span class="label label-success">Assigned</span>';
//            $cancel_assign_btn  = ' <li role="presentation text-center">
//                       <a style="cursor:pointer;" class="edit-modal" onclick="cancelTax('.Input::get('id').','.Input::get('tax_id').')">Cancel</a>
//                       </li>';
//
//        endif;
//        
//        $object = ['label'=>$label,'cancel_assign_btn'=>$cancel_assign_btn];
//        echo json_encode(['data'=>$object]);
//
//    }

//    public function NeglectEmployeeAttendance()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        DB::table('attendance')->where([['id','=',Input::get('attendance_id')]])->update(['neglect_attendance'=>Input::get('type')]);
//        CommonHelper::reconnectMasterDatabase();
//    }
//
//    public function NeglectEarlyGoing()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        DB::table('attendance')->where([['id','=',Input::get('attendance_id')]])->update(['neglect_early'=>'yes']);
//        CommonHelper::reconnectMasterDatabase();
//    }

//    function approveEmployeePayrollDetail()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//
//        foreach (Input::get('val') as $key => $value):
//            $accNo_recordeId_status = (explode("_", $value));
//            $data1['approval_status_m'] = $accNo_recordeId_status[2];
//            $data1['date'] = date("Y-m-d");
//            $data1['time'] = date("H:i:s");
//
//            DB::table('payroll_data')->where([['emp_id', '=', $accNo_recordeId_status[0]], ['id', '=', $accNo_recordeId_status[1]]])->update($data1);
////                echo '<pre>';
////            print_r($data1);
//        endforeach;
//        die;
//
//        CommonHelper::reconnectMasterDatabase();
//        Session::flash('dataInsert', 'successfully saved.');
//        return Redirect::to('hr/ViewAttendanceProgressList?m=' . Input::get('m') . '#Innovative');
//    }

//    function rejectEmployeePayrollDetail()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//
//        foreach (Input::get('val') as $key => $value):
//            $accNo_recordeId_status = (explode("_", $value));
//            $data1['approval_status_m'] = 3;
//            $data1['date'] = date("Y-m-d");
//            $data1['time'] = date("H:i:s");
//
//            DB::table('payroll_data')->where([['emp_id', '=', $accNo_recordeId_status[0]], ['id', '=', $accNo_recordeId_status[1]]])->update($data1);
////                echo '<pre>';
////            print_r($data1);
//        endforeach;
//        die;
//
//        CommonHelper::reconnectMasterDatabase();
//        Session::flash('dataInsert', 'successfully saved.');
//        return Redirect::to('hr/ViewAttendanceProgressList?m=' . Input::get('m') . '#Innovative');
//    }

}
?>