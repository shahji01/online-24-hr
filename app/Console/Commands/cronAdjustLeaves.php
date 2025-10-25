<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Input;
use Redirect;
use Response;
use DB;
use Config;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use Mail;
use DateTime;
use Auth;
use Hash;

use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Holidays;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class cronAdjustLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:AdjustLeaves';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
                 
//        CommonHelper::companyDatabaseConnection('12');
//        $leave_adjustments_queue = DB::Table('leave_adjustments_queue')->where('status',1)->get();
//        CommonHelper::reconnectMasterDatabase();
//
//        foreach($leave_adjustments_queue as $val){
//            $employee_id=$val->employee_id;
//            CommonHelper::companyDatabaseConnection('12');
//            $from_date= $val->year."-".$val->month."-".'01';
//            $to_date= $val->year."-".$val->month."-".'31';
//
//
//
//            $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);
//
//
//            $totalHolidays = array();
//
//            if($get_holidays->count() > 0):
//                foreach($get_holidays->get()->toArray() as $offDates):
//                    $totalHolidays[] = $offDates['holiday_date'];
//                endforeach;
//            else:
//                $totalHolidays = array();
//            endif;
//
//
//
//            //leaves start
//
//
//            $monthly_holidays = array();
//
//            $total_days_off = array();
//            $total_Off_dates = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])
//                ->where('attendance_status','day_off')->where('employee_id','=',$employee_id);
//
//            if($total_Off_dates->count() > 0):
//                foreach($total_Off_dates->get()->toArray() as $offDates):
//                    $total_days_off[] = $offDates['attendance_date'];
//                endforeach;
//            else:
//                $total_days_off = array();
//            endif;
//
//            $attendance = DB::table('attendance')
//            ->join('employee', 'employee.id','=', 'attendance.employee_id')
//            ->whereBetween('attendance.attendance_date',[$from_date,$to_date])
//            ->where([['attendance.employee_id','=',$employee_id]])->orderBy('attendance.attendance_date')->get();
//
//            CommonHelper::reconnectMasterDatabase();
//
//
//            foreach($attendance as $att){
//
//                $working_hours_policy=WorkingHoursPolicy::where('id',$att->working_hours_policy_id)->where('status',1);
//                $startTime = $working_hours_policy->value('start_working_hours_time');
//                $endTime = strtotime("+".$working_hours_policy->value('working_hours_grace_time')."minutes", strtotime($startTime));
//                $half_day_time=strtotime("+".$working_hours_policy->value('half_day_time')."minutes", strtotime($startTime));
//                $finishTime = $working_hours_policy->value('end_working_hours_time');
//                $dutyEndTime = date("H:i", strtotime($finishTime));
//
//                CommonHelper::companyDatabaseConnection('12');
//
//                $monthly_holidays = array_merge($totalHolidays,$total_days_off);
//
//                $half_days_absent=date('H:i', $half_day_time);
//                $end_day_time=date('H:i', $endTime);
//
//
//               $half_days_count=1;
//
//
//
//                $leave_type='';
//
//                $leaves_policy_id=DB::table('employee')->select('leaves_policy_id')->where([['id','=',$val->employee_id],['status','!=',2]])->value('leaves_policy_id');
//
//                $employment_status_id=DB::table('employee')->select('employment_status_id')->where([['id','=',$val->employee_id],['status','!=',2]])->value('employment_status_id');
//
//
//                    $half_day_on_current_date_count=DB::table('attendance')->where([
//                        ['clock_in','>',$half_days_absent],['employee_id','=',$val->employee_id]])
//                    ->where('attendance_date',$att->attendance_date)
//                        ->whereNotIn('attendance_date', $monthly_holidays)
//                    ->count();
//
//                CommonHelper::reconnectMasterDatabase();
//                $late_leave = DB::table('leave_application')
//                ->select('leave_application.id','leave_application.employee_id')
//                ->join('leave_application_data','leave_application.id','=','leave_application_data.leave_application_id')
//                ->where([['leave_application.employee_id','=',$val->employee_id],
//                ['leave_application_data.first_second_half_date','=',$att->attendance_date],
//                ['leave_application.username','=','systemGenerated']
//                ])
//                 ->orWhere(function ($q) use($att,$val) {
//                                                  $q->where([['leave_application.employee_id','=',$val->employee_id],
//                ['leave_application_data.from_date','=',$att->attendance_date],
//                ['leave_application.username','=','systemGenerated']
//                ]);
//                                              });
//
//                if($late_leave->count() > 0){
//                    foreach($late_leave->get() as $leave_val){
//                      DB::table('leave_application_data')->where([['employee_id','=',$val->employee_id],['leave_application_id','=',$leave_val->id]])->delete();
//                      DB::table('leave_application')->where([['employee_id','=',$val->employee_id],['id','=',$leave_val->id]])->delete();
//                    }
//
//                  }
//
//                if(($half_day_on_current_date_count > 0))
//                {
//                  if(($leaves_policy_id != '' && $leaves_policy_id != 0) && ($employment_status_id == 4 && $working_hours_policy->count() > 0))
//                  {
//                    $leaves_policy = DB::table('leaves_policy')
//                        ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//                        ->select('leaves_policy.*', 'leaves_data.*')
//                        ->where([['leaves_policy.id', '=', $leaves_policy_id]])
//                        ->get();
//                    $annual_leaves=0;
//                    $casual_leaves=0;
//                    $sick_leaves=0;
//                    $casual_and_sick_leaves=0;
//                    $remaining=0;
//                    $remaining_annual=0;
//                    $remaining_casual=0;
//                    $remaining_sick=0;
//                    $remaining_maternal=0;
//                    $remaining_paternal=0;
//                    foreach($leaves_policy as $val_policy){
//                        if($val_policy->leave_type_id == 1):
//                            $annual_leaves= $val_policy->no_of_leaves;
//                        elseif($val_policy->leave_type_id == 3):
//                            $casual_leaves = $val_policy->no_of_leaves;
//                        else:
//                            $sick_leaves = $val_policy->no_of_leaves;
//                        endif;
//
//                    $getUsedLeaves= DB::select('select sum(no_of_days) as no_of_days from leave_application_data
//                        INNER JOIN leave_application on leave_application.id = leave_application_data.leave_application_id
//                        WHERE leave_application_data.employee_id = '.$val->employee_id.' AND leave_application.status = 1 AND (leave_application.approval_status != 3 OR leave_application.approval_status_lm != 3) AND
//                        leave_application.leave_type = '.$val_policy->leave_type_id.' ');
//
//                        $casual_and_sick_leaves=$casual_leaves+$sick_leaves;
//
//
//                        $getUsedLeaves=$getUsedLeaves[0]->no_of_days;
//
//                        if($val_policy->leave_type_id == 1):
//                            $remaining_annual = ($val_policy->no_of_leaves -$getUsedLeaves) <= 0 ? 0 : $val_policy->no_of_leaves -$getUsedLeaves;
//                        elseif($val_policy->leave_type_id == 3):
//                            $remaining_casual= ($val_policy->no_of_leaves -$getUsedLeaves) <= 0 ? 0 : $val_policy->no_of_leaves -$getUsedLeaves;
//                        elseif($val_policy->leave_type_id == 2):
//                        $remaining_sick=($val_policy->no_of_leaves -$getUsedLeaves) <= 0 ? 0 : $val_policy->no_of_leaves -$getUsedLeaves;
//                        endif;
//
//                    }
//
//                    if(
//
//                        ($half_day_on_current_date_count > 0)
//                    ){
//
//                    $type='';
//                    if($half_day_on_current_date_count > 0) {
//                        $remaining= 0.5;
//                        $type='half_day_adjusted';
//                    }
//                    if($remaining_casual > 0){
//                        if($remaining_casual >= $remaining){
//                            $leave_type=3;
//                            CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$val->employee_id,$leaves_policy_id,$att->month,$att->year,$att->attendance_date,$half_days_count,Input::get('company_id'),'systemGenerated',$type);
//                        }
//
//                    }
//                    elseif($remaining_sick > 0){
//                        if($remaining_sick >= $remaining){
//                            $leave_type=2;
//                            CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$val->employee_id,$leaves_policy_id,$att->month,$att->year,$att->attendance_date,$half_days_count,Input::get('company_id'),'systemGenerated',$type);
//                        }
//
//                    }
//                    elseif($remaining_annual > 0){
//                        if($remaining_annual >= $remaining){
//                            $leave_type=1;
//                            CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$val->employee_id,$leaves_policy_id,$att->month,$att->year,$att->attendance_date,$half_days_count,Input::get('company_id'),'systemGenerated',$type);
//                        }
//
//                    }
//
//                    }
//
//
//                }
//
//
//                }
//
//            }
//
//            CommonHelper::companyDatabaseConnection('12');
//                DB::Table('leave_adjustments_queue')->where('id',$val->id)->delete();
//            CommonHelper::reconnectMasterDatabase();
//
//        }
    
    }
}
