<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\LeaveType;
use App\User;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use Mail;
use Input;
use Session;
use Redirect;
use \Cache;

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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// require 'vendor/autoload.php';


class HrAddDetailByAjaxController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function approveAndRejectAll()
    {
        $check = input::get('check');
        $approval_status = input::get('approval_status');
        $table_name = input::get('table_name');
        $company_id = input::get('company_id');

        CommonHelper::companyDatabaseConnection($company_id);
        foreach ($check as $key => $value) {
            DB::table($table_name)->where('id', $value)->update(['approval_status' => $approval_status]);
        }
         CommonHelper::reconnectMasterDatabase();
    }

    public function leaveApprovOrReject(request $request)
    {
        $check = input::get('check');
        $approvalType = input::get('approvalType');
        $leave_id = input::get('leave_id');
        $employee_id = input::get('employee_id');
        $company_id = input::get('company_id');

        foreach ($check as $key => $value) {
            if ($approvalType == 'approve') {

                if ($value == 1) {
                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                    $reporting_manager_id = DB::table('employee')->select('reporting_manager_id')->where('status', 1)->where('id', $employee_id[$key])->value('reporting_manager_id');
                    CommonHelper::reconnectMasterDatabase();

                    if ($reporting_manager_id != '') {

                        DB::table('leave_application')->where('id', $leave_id[$key])->update(['approval_status' => '2']);

                    } else {

                        DB::table('leave_application')->where('id', $leave_id[$key])->update(['approval_status' => '2', 'approved' => '2', 'approval_status_lm' => '2']);

                    }

                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                    $data['leave_application_id'] = $leave_id[$key];
                    $data['leave_status'] = 2;
                    $data['type'] = 2;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    $data['username'] = Auth::user()->name;
                    $data['status'] = 1;

                    DB::table('leave_email_queue')->insert($data);
                    CommonHelper::reconnectMasterDatabase();

                }

            } elseif ($approvalType == 'reject') {
                if ($value == 1) {
                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                    $reporting_manager_id = DB::table('employee')->select('reporting_manager_id')->where('status', 1)->where('id', $employee_id[$key])->value('reporting_manager_id');
                    CommonHelper::reconnectMasterDatabase();

                    if ($reporting_manager_id != '') {
                        DB::table('leave_application')->where('id', $leave_id[$key])->update(['approval_status' => '3']);

                    } else {

                        DB::table('leave_application')->where('id', $leave_id[$key])->update(['approval_status' => '3', 'approved' => '3', 'approval_status_lm' => '3']);

                    }

                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                    $data['leave_application_id'] = $leave_id[$key];
                    $data['leave_status'] = 3;
                    $data['type'] = 2;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    $data['username'] = Auth::user()->name;
                    $data['status'] = 1;

                    DB::table('leave_email_queue')->insert($data);
                    CommonHelper::reconnectMasterDatabase();

                }

            }
        }

    }
    
    public function promotionApprovOrReject(request $request)
    {
        $check = input::get('check');
        $approvalType = input::get('approvalType');
        $promotion_id = input::get('promotion_id');
        $employee_id = input::get('employee_id');
        $company_id = input::get('company_id');

        foreach ($check as $key => $value) {
            if ($approvalType == 'approve') {

                if ($value == 1) {
                  

                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                        
                    DB::table('employee_promotion')->where('id', $promotion_id[$key])->where('employee_id', $employee_id[$key])->update(['approval_status' => '2']);
                    
                    CommonHelper::reconnectMasterDatabase();

                   

                    // $data['leave_application_id'] = $leave_id[$key];
                    // $data['leave_status'] = 2;
                    // $data['type'] = 2;
                    // $data['date'] = date("Y-m-d");
                    // $data['time'] = date("H:i:s");
                    // $data['username'] = Auth::user()->name;
                    // $data['status'] = 1;

                    // DB::table('leave_email_queue')->insert($data);

                }

            } elseif ($approvalType == 'reject') {
                if ($value == 1) {
                   
                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                        DB::table('leave_application')->where('id', $promotion_id[$key])->where('employee_id', $employee_id[$key])->update(['approval_status' => '3']);
                    CommonHelper::reconnectMasterDatabase();



                }

            }
        }
    }
    
    public function addLeaveApplicationDetail()
    {
        $employee_id = Input::get('employee_id');
        $leaveApplicationCheck1 = LeaveApplicationData::where([['view', '=', 'yes'], ['leave_policy_id', '=', Input::get('leave_policy_id')], ['from_date', '=', Input::get('from_date')], ['first_second_half', '=', ''], ['employee_id', '=', $employee_id]])->count();

        $leaveApplicationCheck2 = LeaveApplicationData::where([['view', '=', 'yes'], ['leave_policy_id', '=', Input::get('leave_policy_id')], ['first_second_half_date', '=', Input::get('first_second_half_date')],
            ['employee_id', '=', $employee_id], ['first_second_half', '!=', '']])->count();

        if ($leaveApplicationCheck1 > 0 || $leaveApplicationCheck2 > 0):

            return "Leave Application Already Exist on Date " . Input::get('from_date') . Input::get('first_second_half_date');

        else:

            $data1['employee_id'] = $employee_id;
            $data1['leave_policy_id'] = Input::get('leave_policy_id');
            $data1['company_id'] = Input::get('company_id');
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

            $leave_application_id = DB::table('leave_application')->insertGetId($data1);


            if (Input::get('leave_day_type') == 1):

                $data['employee_id'] = $employee_id;
                $data['leave_application_id'] = $leave_application_id;
                $data['leave_policy_id'] = Input::get('leave_policy_id');
                $data['no_of_days'] = Input::get('no_of_days');
                $data['from_date'] = Input::get('from_date');
                $data['to_date'] = Input::get('to_date');
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('leave_application_data')->insert($data);

            elseif (Input::get('leave_day_type') == 2):

                $data['employee_id'] = $employee_id;
                $data['leave_application_id'] = $leave_application_id;
                $data['leave_policy_id'] = Input::get('leave_policy_id');
                $data['no_of_days'] = Input::get('no_of_days');
                $data['first_second_half'] = Input::get('first_second_half');
                $data['first_second_half_date'] = Input::get('first_second_half_date');
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('leave_application_data')->insert($data);
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

    // public function addLeaveApplicationDetail()
    // {

    //     $employee_id = Input::get('employee_id');
    //     $leaveApplicationCheck1 = LeaveApplicationData::where([['view', '=', 'yes'], ['leave_policy_id', '=', Input::get('leave_policy_id')], ['from_date', '=', Input::get('from_date')], ['first_second_half', '=', ''], ['employee_id', '=', $employee_id]])->count();

    //     $leaveApplicationCheck2 = LeaveApplicationData::where([['view', '=', 'yes'], ['leave_policy_id', '=', Input::get('leave_policy_id')], ['first_second_half_date', '=', Input::get('first_second_half_date')],
    //         ['employee_id', '=', $employee_id], ['first_second_half', '!=', '']])->count();

    //     if ($leaveApplicationCheck1 > 0 || $leaveApplicationCheck2 > 0):

    //         return "Leave Application Already Exist on Date " . Input::get('from_date') . Input::get('first_second_half_date');

    //     else:

    //         $leaveApplicationData['employee_id'] = $employee_id;
    //         $leaveApplicationData['leave_policy_id'] = Input::get('leave_policy_id');
    //         $leaveApplicationData['company_id'] = Input::get('company_id');
    //         $leaveApplicationData['leave_type'] = Input::get('leave_type');
    //         $leaveApplicationData['leave_day_type'] = Input::get('leave_day_type');
    //         $leaveApplicationData['reason'] = Input::get('reason');
    //         $leaveApplicationData['leave_address'] = Input::get('leave_address');
    //         $leaveApplicationData['approval_status'] = 1; // 1 pending 2 approve 3 reject
    //         $leaveApplicationData['approval_status_lm'] = 1; // 1 pending 2 approve 3 reject
    //         $leaveApplicationData['approved'] = 1;
    //         $leaveApplicationData['status'] = 1;
    //         $leaveApplicationData['username'] = Auth::user()->name;
    //         $leaveApplicationData['date'] = date("Y-m-d");
    //         $leaveApplicationData['time'] = date("H:i:s");

    //         $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

    //         /* Annual Leaves ID = 3 */
    //         if (Input::get('leave_type') == 1):

    //             $maternityLeavesData['employee_id'] = $employee_id;
    //             $maternityLeavesData['leave_application_id'] = $leave_application_id;
    //             $maternityLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //             $maternityLeavesData['leave_type'] = Input::get('leave_type');
    //             $maternityLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //             $maternityLeavesData['no_of_days'] = Input::get('no_of_days');
    //             $maternityLeavesData['from_date'] = Input::get('from_date');
    //             $maternityLeavesData['to_date'] = Input::get('to_date');

    //             //  $startDate = new DateTime(Input::get('from_date'));
    //             //                $endDate = new DateTime(Input::get('to_date'));
    //             //                $difference = $endDate->diff($startDate);

    //             //  $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;

    //             $maternityLeavesData['status'] = 1;
    //             $maternityLeavesData['username'] = Auth::user()->name;
    //             $maternityLeavesData['date'] = date("Y-m-d");
    //             $maternityLeavesData['time'] = date("H:i:s");

    //             DB::table('leave_application_data')->insert($maternityLeavesData);

    //         elseif (Input::get('leave_type') == 2):

    //             /* Full Day Leaves */
    //             if (Input::get('leave_day_type') == 1):

    //                 $annualLeavesData['employee_id'] = $employee_id;
    //                 $annualLeavesData['leave_application_id'] = $leave_application_id;
    //                 $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $annualLeavesData['leave_type'] = Input::get('leave_type');
    //                 $annualLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $annualLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $annualLeavesData['from_date'] = Input::get('from_date');
    //                 $annualLeavesData['to_date'] = Input::get('to_date');
    //                 //  $startDate = new DateTime(Input::get('from_date'));
    //                 //                    $endDate = new DateTime(Input::get('to_date'));
    //                 //                    $difference = $endDate->diff($startDate);
    //                 // $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;
    //                 $annualLeavesData['status'] = 1;
    //                 $annualLeavesData['username'] = Auth::user()->name;
    //                 $annualLeavesData['date'] = date("Y-m-d");
    //                 $annualLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($annualLeavesData);

    //                 /* Half Day Leaves */
    //             elseif (Input::get('leave_day_type') == 2):

    //                 $halfdayLeavesData['employee_id'] = $employee_id;
    //                 $halfdayLeavesData['leave_application_id'] = $leave_application_id;
    //                 $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $halfdayLeavesData['leave_type'] = Input::get('leave_type');
    //                 $halfdayLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $halfdayLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $halfdayLeavesData['first_second_half'] = Input::get('first_second_half');
    //                 $halfdayLeavesData['first_second_half_date'] = Input::get('first_second_half_date');
    //                 $halfdayLeavesData['status'] = 1;
    //                 $halfdayLeavesData['username'] = Auth::user()->name;
    //                 $halfdayLeavesData['date'] = date("Y-m-d");
    //                 $halfdayLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($halfdayLeavesData);

    //             else:
    //                 /* Short Leaves */

    //                 $shortLeavesData['employee_id'] = $employee_id;
    //                 $shortLeavesData['leave_application_id'] = $leave_application_id;
    //                 $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $shortLeavesData['leave_type'] = Input::get('leave_type');
    //                 $shortLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $shortLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $shortLeavesData['short_leave_time_from'] = Input::get('short_leave_time_from');
    //                 $shortLeavesData['short_leave_time_to'] = Input::get('short_leave_time_to');
    //                 $shortLeavesData['short_leave_date'] = Input::get('short_leave_date');
    //                 $shortLeavesData['status'] = 1;
    //                 $shortLeavesData['username'] = Auth::user()->name;
    //                 $shortLeavesData['date'] = date("Y-m-d");
    //                 $shortLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($shortLeavesData);

    //             endif;

    //         elseif (Input::get('leave_type') == 3):

    //             /* Full Day Leaves */
    //             if (Input::get('leave_day_type') == 1):

    //                 $annualLeavesData['employee_id'] = $employee_id;
    //                 $annualLeavesData['leave_application_id'] = $leave_application_id;
    //                 $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $annualLeavesData['leave_type'] = Input::get('leave_type');
    //                 $annualLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $annualLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $annualLeavesData['from_date'] = Input::get('from_date');
    //                 $annualLeavesData['to_date'] = Input::get('to_date');
    //                 //   $startDate = new DateTime(Input::get('from_date'));
    //                 //                    $endDate = new DateTime(Input::get('to_date'));
    //                 //                    $difference = $endDate->diff($startDate);

    //                 //   $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;
    //                 $annualLeavesData['status'] = 1;
    //                 $annualLeavesData['username'] = Auth::user()->name;
    //                 $annualLeavesData['date'] = date("Y-m-d");
    //                 $annualLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($annualLeavesData);

    //                 /* Half Day Leaves */
    //             elseif (Input::get('leave_day_type') == 2):

    //                 $halfdayLeavesData['employee_id'] = $employee_id;
    //                 $halfdayLeavesData['leave_application_id'] = $leave_application_id;
    //                 $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $halfdayLeavesData['leave_type'] = Input::get('leave_type');
    //                 $halfdayLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $halfdayLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $halfdayLeavesData['first_second_half'] = Input::get('first_second_half');
    //                 $halfdayLeavesData['first_second_half_date'] = Input::get('first_second_half_date');
    //                 $halfdayLeavesData['status'] = 1;
    //                 $halfdayLeavesData['username'] = Auth::user()->name;
    //                 $halfdayLeavesData['date'] = date("Y-m-d");
    //                 $halfdayLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($halfdayLeavesData);

    //             else:
    //                 /* Short Leaves */

    //                 $shortLeavesData['employee_id'] = $employee_id;
    //                 $shortLeavesData['leave_application_id'] = $leave_application_id;
    //                 $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $shortLeavesData['leave_type'] = Input::get('leave_type');
    //                 $shortLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $shortLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $shortLeavesData['short_leave_time_from'] = Input::get('short_leave_time_from');
    //                 $shortLeavesData['short_leave_time_to'] = Input::get('short_leave_time_to');
    //                 $shortLeavesData['short_leave_date'] = Input::get('short_leave_date');
    //                 $shortLeavesData['status'] = 1;
    //                 $shortLeavesData['username'] = Auth::user()->name;
    //                 $shortLeavesData['date'] = date("Y-m-d");
    //                 $shortLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($shortLeavesData);

    //             endif;

    //         else:

    //             /* Full Day Leaves */
    //             if (Input::get('leave_day_type') == 1):

    //                 $annualLeavesData['employee_id'] = $employee_id;
    //                 $annualLeavesData['leave_application_id'] = $leave_application_id;
    //                 $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $annualLeavesData['leave_type'] = Input::get('leave_type');
    //                 $annualLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $annualLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $annualLeavesData['from_date'] = Input::get('from_date');
    //                 $annualLeavesData['to_date'] = Input::get('to_date');
    //                 // $startDate = new DateTime(Input::get('from_date'));
    //                 //                    $endDate = new DateTime(Input::get('to_date'));
    //                 //                    $difference = $endDate->diff($startDate);
    //                 //
    //                 //                    $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;
    //                 $annualLeavesData['status'] = 1;
    //                 $annualLeavesData['username'] = Auth::user()->name;
    //                 $annualLeavesData['date'] = date("Y-m-d");
    //                 $annualLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($annualLeavesData);

    //                 /* Half Day Leaves */
    //             elseif (Input::get('leave_day_type') == 2):

    //                 $halfdayLeavesData['employee_id'] = $employee_id;
    //                 $halfdayLeavesData['leave_application_id'] = $leave_application_id;
    //                 $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $halfdayLeavesData['leave_type'] = Input::get('leave_type');
    //                 $halfdayLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $halfdayLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $halfdayLeavesData['first_second_half'] = Input::get('first_second_half');
    //                 $halfdayLeavesData['first_second_half_date'] = Input::get('first_second_half_date');
    //                 $halfdayLeavesData['status'] = 1;
    //                 $halfdayLeavesData['username'] = Auth::user()->name;
    //                 $halfdayLeavesData['date'] = date("Y-m-d");
    //                 $halfdayLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($halfdayLeavesData);

    //             else:
    //                 /* Short Leaves */

    //                 $shortLeavesData['employee_id'] = $employee_id;
    //                 $shortLeavesData['leave_application_id'] = $leave_application_id;
    //                 $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
    //                 $shortLeavesData['leave_type'] = Input::get('leave_type');
    //                 $shortLeavesData['leave_day_type'] = Input::get('leave_day_type');
    //                 $shortLeavesData['no_of_days'] = Input::get('no_of_days');
    //                 $shortLeavesData['short_leave_time_from'] = Input::get('short_leave_time_from');
    //                 $shortLeavesData['short_leave_time_to'] = Input::get('short_leave_time_to');
    //                 $shortLeavesData['short_leave_date'] = Input::get('short_leave_date');
    //                 $shortLeavesData['status'] = 1;
    //                 $shortLeavesData['username'] = Auth::user()->name;
    //                 $shortLeavesData['date'] = date("Y-m-d");
    //                 $shortLeavesData['time'] = date("H:i:s");

    //                 DB::table('leave_application_data')->insert($shortLeavesData);

    //             endif;
    //         endif;

    //         $employees = Cache::get('employee');
    //         $emp_id = '';
    //         $emp_name = '';
    //         $reporting_manager_id = '';
    //         if (array_key_exists($employee_id, $employees)):
    //             $emp_id = $employees[$employee_id]->emp_id;
    //             $emp_name = $employees[$employee_id]->emp_name;
    //             $reporting_manager_id = $employees[$employee_id]->reporting_manager_id;
    //         endif;

    //         if ($reporting_manager_id != ''):
    //             if (array_key_exists($reporting_manager_id, $employees)):
    //                 $email = $employees[$reporting_manager_id]->official_email;
    //             else:
    //                 $email = "aliasghar@innovative-net.com";
    //             endif;
    //         else:
    //             $email = "aliasghar@innovative-net.com";
    //         endif;

    //         $leave_types = Cache::get('leave_type');
    //         if (array_key_exists(Input::get('leave_type'), $leave_types)):
    //             $leave_type_name = $leave_types[Input::get('leave_type')]->leave_type_name;
    //         endif;

    //         $from_date = HrHelper::date_format(Input::get('from_date'));
    //         $to_date = HrHelper::date_format(Input::get('to_date'));
    //         $days = '';
    //         if (Input::get('no_of_days') > 1) {
    //             $days = 'days';
    //         } else {
    //             $days = 'day';
    //         }

    //         // if(Input::get('leave_day_type') == 1)
    //         // {
    //         //     Input::get('no_of_days');
    //         //     Input::get('from_date');
    //         // }
    //         // else
    //         // {

    //         // }
    //         CommonHelper::companyDatabaseConnection(Input::get('company_id'));
    //         $data['leave_application_id'] = $leave_application_id;
    //         $data['leave_type_name'] = $leave_type_name;
    //         $data['leave_no_of_days'] = Input::get('no_of_days');
    //         $data['leave_from_date'] = Input::get('from_date')?Input::get('from_date'):Input::get('first_second_half_date');
    //         $data['leave_to_date'] = Input::get('to_date')?Input::get('to_date'):Input::get('first_second_half_date');
    //         $data['type'] = 0;
    //         $data['date'] = date("Y-m-d");
    //         $data['time'] = date("H:i:s");
    //         $data['username'] = Auth::user()->name;
    //         $data['status'] = 1;

    //         DB::table('leave_email_queue')->insert($data);
    //         CommonHelper::reconnectMasterDatabase();
            
    //         //EmailHelper::HrEmail($email, $subject, $message);
    //         return "1";
    //     endif;
    // }

//         public function addLeaveApplicationDetail()
//     {

// 		$employee_id = Input::get('employee_id');
//         $leaveApplicationCheck1 =  LeaveApplicationData::where([['view','=','yes'],['leave_policy_id','=',Input::get('leave_policy_id')],['from_date','=', Input::get('from_date')],['first_second_half','=',''],['employee_id','=',$employee_id]])->count();
		
//         $leaveApplicationCheck2 = LeaveApplicationData::where([['view','=','yes'],['leave_policy_id','=',Input::get('leave_policy_id')],['first_second_half_date','=', Input::get('first_second_half_date')],
//             ['employee_id','=',$employee_id],['first_second_half','!=','']])->count();

//         if( $leaveApplicationCheck1 > 0 || $leaveApplicationCheck2 > 0):

//             return "Leave Application Already Exist on Date ".Input::get('from_date').Input::get('first_second_half_date');

//         else:

//             $leaveApplicationData['employee_id']          = $employee_id;
//             $leaveApplicationData['leave_policy_id'] = Input::get('leave_policy_id');
//             $leaveApplicationData['company_id']      = Input::get('company_id');
//             $leaveApplicationData['leave_type']      = Input::get('leave_type');
//             $leaveApplicationData['leave_day_type']  = Input::get('leave_day_type');
//             $leaveApplicationData['reason']          = Input::get('reason');
//             $leaveApplicationData['leave_address']   = Input::get('leave_address');
//             $leaveApplicationData['approval_status'] = 1; // 1 pending 2 approve 3 reject
//             $leaveApplicationData['approval_status_lm'] = 1; // 1 pending 2 approve 3 reject
//             $leaveApplicationData['approved'] = 1; 
//             $leaveApplicationData['status']          = 1;
//             $leaveApplicationData['username']        = Auth::user()->name;
//             $leaveApplicationData['date']            = date("Y-m-d");
//             $leaveApplicationData['time']            = date("H:i:s");

//             $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);


//             /* Annual Leaves ID = 3 */
//             if(Input::get('leave_type') == 1):

//                 $maternityLeavesData['employee_id']               = $employee_id;
//                 $maternityLeavesData['leave_application_id'] = $leave_application_id;
//                 $maternityLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                 $maternityLeavesData['leave_type']           = Input::get('leave_type');
//                 $maternityLeavesData['leave_day_type']       = Input::get('leave_day_type');
//                 $maternityLeavesData['no_of_days']           = Input::get('no_of_days');
//                 $maternityLeavesData['from_date']            = Input::get('from_date');
//                 $maternityLeavesData['to_date']              = Input::get('to_date');

// //                $startDate = new DateTime(Input::get('from_date'));
// //                $endDate = new DateTime(Input::get('to_date'));
// //                $difference = $endDate->diff($startDate);

// //                $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;

//                 $maternityLeavesData['status']               = 1;
//                 $maternityLeavesData['username']             = Auth::user()->name;
//                 $maternityLeavesData['date']                 = date("Y-m-d");
//                 $maternityLeavesData['time']                 = date("H:i:s");

//                 DB::table('leave_application_data')->insert($maternityLeavesData);

//             elseif(Input::get('leave_type') == 2):

//                 /* Full Day Leaves */
//                 if(Input::get('leave_day_type') == 1):

//                     $annualLeavesData['employee_id']               = $employee_id;
//                     $annualLeavesData['leave_application_id'] = $leave_application_id;
//                     $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $annualLeavesData['leave_type']           = Input::get('leave_type');
//                     $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
//                     $annualLeavesData['no_of_days']           = Input::get('no_of_days');
//                     $annualLeavesData['from_date']            = Input::get('from_date');
//                     $annualLeavesData['to_date']              = Input::get('to_date');
// //                    $startDate = new DateTime(Input::get('from_date'));
// //                    $endDate = new DateTime(Input::get('to_date'));
// //                    $difference = $endDate->diff($startDate);

// //                    $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;
//                     $annualLeavesData['status']               = 1;
//                     $annualLeavesData['username']             = Auth::user()->name;
//                     $annualLeavesData['date']                 = date("Y-m-d");
//                     $annualLeavesData['time']                 = date("H:i:s");

//                     DB::table('leave_application_data')->insert($annualLeavesData);

//                 /* Half Day Leaves */
//                 elseif(Input::get('leave_day_type') == 2):

//                     $halfdayLeavesData['employee_id']                   = $employee_id;
//                     $halfdayLeavesData['leave_application_id']     = $leave_application_id;
//                     $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $halfdayLeavesData['leave_type']               = Input::get('leave_type');
//                     $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
//                     $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
//                     $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
//                     $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');
//                     $halfdayLeavesData['status']                   = 1;
//                     $halfdayLeavesData['username']                 = Auth::user()->name;
//                     $halfdayLeavesData['date']                     = date("Y-m-d");
//                     $halfdayLeavesData['time']                     = date("H:i:s");

//                     DB::table('leave_application_data')->insert($halfdayLeavesData);

//                 else:
//                     /* Short Leaves */

//                     $shortLeavesData['employee_id']               = $employee_id;
//                     $shortLeavesData['leave_application_id'] = $leave_application_id;
//                     $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $shortLeavesData['leave_type']           = Input::get('leave_type');
//                     $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
//                     $shortLeavesData['no_of_days']           = Input::get('no_of_days');
//                     $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
//                     $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
//                     $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');
//                     $shortLeavesData['status']               = 1;
//                     $shortLeavesData['username']             = Auth::user()->name;
//                     $shortLeavesData['date']                 = date("Y-m-d");
//                     $shortLeavesData['time']                 = date("H:i:s");


//                     DB::table('leave_application_data')->insert($shortLeavesData);

//                 endif;

//             elseif(Input::get('leave_type') == 3):


//                 /* Full Day Leaves */
//                 if(Input::get('leave_day_type') == 1):

//                     $annualLeavesData['employee_id']               = $employee_id;
//                     $annualLeavesData['leave_application_id'] = $leave_application_id;
//                     $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $annualLeavesData['leave_type']           = Input::get('leave_type');
//                     $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
//                     $annualLeavesData['no_of_days']           = Input::get('no_of_days');
//                     $annualLeavesData['from_date']            = Input::get('from_date');
//                     $annualLeavesData['to_date']              = Input::get('to_date');
// //                    $startDate = new DateTime(Input::get('from_date'));
// //                    $endDate = new DateTime(Input::get('to_date'));
// //                    $difference = $endDate->diff($startDate);

// //                    $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;
//                     $annualLeavesData['status']               = 1;
//                     $annualLeavesData['username']             = Auth::user()->name;
//                     $annualLeavesData['date']                 = date("Y-m-d");
//                     $annualLeavesData['time']                 = date("H:i:s");

//                     DB::table('leave_application_data')->insert($annualLeavesData);

//                 /* Half Day Leaves */
//                 elseif(Input::get('leave_day_type') == 2):

//                     $halfdayLeavesData['employee_id']                   = $employee_id;
//                     $halfdayLeavesData['leave_application_id']     = $leave_application_id;
//                     $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $halfdayLeavesData['leave_type']               = Input::get('leave_type');
//                     $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
//                     $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
//                     $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
//                     $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');
//                     $halfdayLeavesData['status']                   = 1;
//                     $halfdayLeavesData['username']                 = Auth::user()->name;
//                     $halfdayLeavesData['date']                     = date("Y-m-d");
//                     $halfdayLeavesData['time']                     = date("H:i:s");

//                     DB::table('leave_application_data')->insert($halfdayLeavesData);

//                 else:
//                     /* Short Leaves */

//                     $shortLeavesData['employee_id']               = $employee_id;
//                     $shortLeavesData['leave_application_id'] = $leave_application_id;
//                     $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $shortLeavesData['leave_type']           = Input::get('leave_type');
//                     $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
//                     $shortLeavesData['no_of_days']           = Input::get('no_of_days');
//                     $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
//                     $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
//                     $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');
//                     $shortLeavesData['status']               = 1;
//                     $shortLeavesData['username']             = Auth::user()->name;
//                     $shortLeavesData['date']                 = date("Y-m-d");
//                     $shortLeavesData['time']                 = date("H:i:s");


//                     DB::table('leave_application_data')->insert($shortLeavesData);

//                 endif;

//             else:


//                 /* Full Day Leaves */
//                 if(Input::get('leave_day_type') == 1):

//                     $annualLeavesData['employee_id']               = $employee_id;
//                     $annualLeavesData['leave_application_id'] = $leave_application_id;
//                     $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $annualLeavesData['leave_type']           = Input::get('leave_type');
//                     $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
//                     $annualLeavesData['no_of_days']           = Input::get('no_of_days');
//                     $annualLeavesData['from_date']            = Input::get('from_date');
//                     $annualLeavesData['to_date']              = Input::get('to_date');
// //                    $startDate = new DateTime(Input::get('from_date'));
// //                    $endDate = new DateTime(Input::get('to_date'));
// //                    $difference = $endDate->diff($startDate);
// //
// //                    $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;
//                     $annualLeavesData['status']               = 1;
//                     $annualLeavesData['username']             = Auth::user()->name;
//                     $annualLeavesData['date']                 = date("Y-m-d");
//                     $annualLeavesData['time']                 = date("H:i:s");

//                     DB::table('leave_application_data')->insert($annualLeavesData);

//                 /* Half Day Leaves */
//                 elseif(Input::get('leave_day_type') == 2):

//                     $halfdayLeavesData['employee_id']                   = $employee_id;
//                     $halfdayLeavesData['leave_application_id']     = $leave_application_id;
//                     $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $halfdayLeavesData['leave_type']               = Input::get('leave_type');
//                     $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
//                     $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
//                     $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
//                     $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');
//                     $halfdayLeavesData['status']                   = 1;
//                     $halfdayLeavesData['username']                 = Auth::user()->name;
//                     $halfdayLeavesData['date']                     = date("Y-m-d");
//                     $halfdayLeavesData['time']                     = date("H:i:s");

//                     DB::table('leave_application_data')->insert($halfdayLeavesData);

//                 else:
//                     /* Short Leaves */

//                     $shortLeavesData['employee_id']               = $employee_id;
//                     $shortLeavesData['leave_application_id'] = $leave_application_id;
//                     $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
//                     $shortLeavesData['leave_type']           = Input::get('leave_type');
//                     $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
//                     $shortLeavesData['no_of_days']           = Input::get('no_of_days');
//                     $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
//                     $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
//                     $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');
//                     $shortLeavesData['status']               = 1;
//                     $shortLeavesData['username']             = Auth::user()->name;
//                     $shortLeavesData['date']                 = date("Y-m-d");
//                     $shortLeavesData['time']                 = date("H:i:s");

//                     DB::table('leave_application_data')->insert($shortLeavesData);

//                 endif;
//             endif;

//             // $employees = Cache::get('employee');
//             // $emp_id = '';
//             // $emp_name = '';
//             // $reporting_manager_id = '';
//             // if(array_key_exists($employee_id, $employees)):
//             //     $emp_id = $employees[$employee_id]->emp_id;
//             //     $emp_name = $employees[$employee_id]->emp_name;
//             //     $reporting_manager_id = $employees[$employee_id]->reporting_manager_id;
//             // endif;

//             // if($reporting_manager_id != ''):
//             //     if(array_key_exists($reporting_manager_id, $employees)):
//             //         $email = $employees[$reporting_manager_id]->official_email;
//             //     else:
//             //         $email = "aliasghar@innovative-net.com";
//             //     endif;
//             // else:
//             //     $email = "aliasghar@innovative-net.com";
//             // endif;

//             // $leave_types = Cache::get('leave_type');
//             // if(array_key_exists(Input::get('leave_type'), $leave_types)):
//             //     $leave_type_name = $leave_types[Input::get('leave_type')]->leave_type_name;
//             // endif;

//             // $from_date = HrHelper::date_format(Input::get('from_date'));
//             // $to_date = HrHelper::date_format(Input::get('to_date'));
//             // $days = '';
//             // if(Input::get('no_of_days') > 1){
//             //     $days = 'days';
//             // }else{
//             //     $days = 'day';
//             // }

//             // $subject = "Leave Application";
//             // $message = "Emp ID: ".$emp_id."<br/>";
//             // $message .= "Name: ".$emp_name."<br/>";
//             // $message .= "Date: ".HrHelper::date_format(date('Y-m-d'))."<br/><br/>";
//             // $message .= 'Please approve my '.$leave_type_name.' leave application of '.Input::get('no_of_days').' '.$days.' from '.$from_date.' to '.$to_date;

//             //EmailHelper::HrEmail($email, $subject, $message);
//             return "1";
//         endif;
//     }

    public function addEmployeeSixthMonthAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_and_year               = explode("-",Input::get('date'));
        $data['emp_id']               = Input::get('emr_no');
        $data['month']                = $month_and_year[0];
        $data['year']                 = $month_and_year[1];
        $data['status']               = 1;
        $data['username']             = Auth::user()->name;
        $data['date']                 = date("Y-m-d");
        $data['time']                 = date("H:i:s");
        DB::table('employee_hr_audit')->insert($data);
        CommonHelper::reconnectMasterDatabase();

    }

    public function addEmployeeTwelfthMonthAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_and_year               = explode("-",Input::get('date'));
        $data['emp_id']               = Input::get('emr_no');
        $data['month']                = $month_and_year[0];
        $data['year']                 = $month_and_year[1];
        $data['status']               = 1;
        $data['username']             = Auth::user()->name;
        $data['date']                 = date("Y-m-d");
        $data['time']                 = date("H:i:s");
        DB::table('employee_hr_audit')->insert($data);
        CommonHelper::reconnectMasterDatabase();
    }

    public function addMasterTableDetail()
    {
        $tableName = Input::get('tableName');
        $columnName = Input::get('columnName');
        $name = Input::get('name');
        $department_id = Input::get('department_id');

        if($department_id != ''):
            $data1['department_id'] = $department_id;
        endif;

        $data1[$columnName] = $name;
        $data1['company_id'] = Input::get('m');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        $lastInsertId = DB::table($tableName)->insertGetId($data1);
        return response()->json(['id'=> $lastInsertId, 'name' => $name]);

    }


    public function addManualyAttendance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emr_no = Input::get('emr_no');
        $attendance_type = Input::get('attendance_type');

        if($attendance_type == 2):

            $date = Input::get('attendance_date');
            if(DB::table('attendance')->where([['emr_no','=',$emr_no],['attendance_date', '=', $date], ['attendance_type', '=', 2]])->exists()):
                DB::table('attendance')->where([['emr_no','=',$emr_no],['attendance_date', '=', $date]])->delete();
            endif;

            $day = strtotime($date);
            $day = date('D', $day);

            $month = strtotime($date);
            $month = date('m', $month);

            $year = strtotime($date);
            $year = date('Y', $year);

            $data1['emr_no'] = $emr_no;
            $data1['attendance_date'] = $date;
            $data1['attendance_status'] = Input::get('attendance_status');
            $data1['day'] = $day;
            $data1['month'] = $month;
            $data1['year'] = $year;
        endif;

        if ($attendance_type == 1):

            $date = Input::get('date_to');

            $month = date('m', strtotime($date));
            $year = date('Y',  strtotime($date));

            if(DB::table('attendance')->where([['emr_no','=',$emr_no],['month', '=', $month],['year', '=',$year ],['attendance_type', '=', 1]])->exists()):
                DB::table('attendance')->where([['emr_no','=',$emr_no],['month', '=', $month],['year', '=',$year ]])->delete();
            endif;

            $data1['emr_no']                = $emr_no;
            $data1['attendance_from']       = Input::get('date_from');
            $data1['attendance_to']         = Input::get('date_to');
            $data1['month']                 = date('m',strtotime(Input::get('date_to')));
            $data1['year']                  = date('Y',strtotime(Input::get('date_to')));
            $data1['present_days']          = Input::get('present_days');
            $data1['absent_days']           = Input::get('absent_days');
            $data1['overtime']              = Input::get('overtime');
        endif;

        $data1['attendance_type'] = $attendance_type;
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        DB::table('attendance')->insert($data1);

        $data3['table_name']         = 'attendance';
        $data3['activity_id']        = null;
        $data3['deleted_emr_no']     = null;
        $data3['activity']           = 'Insert';
        $data3['module']             = 'hr';
        $data3['username']           = Auth::user()->name;
        $data3['date']               = date("Y-m-d");
        $data3['time']               = date("H:i:s");
        DB::table('log')->insert($data3);

        CommonHelper::reconnectMasterDatabase();
        return response()->json(['success'=> 'Attendance Submitted']);
    }
	
	public function addUserQueryDetails()
    {

        $month_year = explode('-', Input::get('date'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $data1['emp_id'] = strip_tags(Input::get('emp_id'));
        $data1['queryType'] = strip_tags(Input::get('queryType'));
        $data1['query_date'] = strip_tags(Input::get('date'));
        $data1['month'] = $month_year[1];
        $data1['year'] = $month_year[0];
        $data1['remarks'] = strip_tags(Input::get('remarks'));
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        DB::table('user_query')->insert($data1);

        CommonHelper::reconnectMasterDatabase();

    }





}
?>