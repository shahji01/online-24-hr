<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PayrollData;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\WorkingHoursPolicy;
use App\Models\Holidays;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use DB;


class cronAttendanceProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:attendance';

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
        
        CommonHelper::companyDatabaseConnection('12');
        $attendance_queue = DB::Table('attendance_queue')->where([['status', '=', 1]])->orderBy('employee_id');
        CommonHelper::reconnectMasterDatabase();

        if ($attendance_queue->count() > 0){
             
             foreach ($attendance_queue->get() as $key_queue => $value_queue){
                 $employee_id=$value_queue->employee_id;
                 $from_date=$value_queue->year.'-'.$value_queue->month.'-01';
                 $to_date=$value_queue->year.'-'.$value_queue->month.'-31';
                 $month=$value_queue->month;
                 $year=$value_queue->year;
                 CommonHelper::companyDatabaseConnection('12');
                 $attendance = DB::table('attendance')
                ->join('employee', 'employee.id', '=', 'attendance.employee_id')
                ->select('employee.emp_name', 'employee.emp_id','employee.grade_id','attendance.employee_id','attendance.month','attendance.year','attendance.attendance_date','attendance.working_hours_policy_id')
                ->where('attendance.employee_id','=',$employee_id)
                ->whereBetween('attendance_date',[$from_date,$to_date])
                ->Where('employee.status',1)
                ->groupBy('attendance.employee_id');

            if($attendance->count()>0){
                           
                $counter=1;
                $leaves_no_days = array();
                $monthly_holidays = array();
                $totalOffDates = array();
                
                foreach($attendance->get() as $value){
                    
                    CommonHelper::companyDatabaseConnection('12');

                    $get_deduction = PayrollData::where([['employee_id','=',$value->employee_id],['month','=',$month],['year','=',$year],['status','=',1]]);

                    $approvedPayrollData = PayrollData::where([['employee_id','=',$value->employee_id],['month','=',$month],['year','=',$year],['approval_status_m','=',2],['status','=',1]]);
                    $total_days_off = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])
                    ->where('attendance_status','day_off')->where('employee_id','=',$value->employee_id);
                    $totalOffDates=array();
                    if($total_days_off->count() > 0):
        
                        foreach($total_days_off->get()->toArray() as $offDates):
                            $totalOffDates[] = $offDates['attendance_date'];
                        endforeach;
        
                    else:
                        $totalOffDates =array();
                    endif;
                    
                    CommonHelper::reconnectMasterDatabase();
                    $working_policy = WorkingHoursPolicy::where([['id','=',$value->working_hours_policy_id]]);
                    $working_policy_data = $working_policy->get()->toArray();
                    $countPresent = cal_days_in_month(CAL_GREGORIAN,$month,$year);
                    
                    CommonHelper::companyDatabaseConnection('12');
                           
                            $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);

                            $totalHolidays = $get_holidays->get()->toArray();
                            $monthly_holidays = array_merge($totalHolidays,$totalOffDates);

                            $total_LateAbsent = Attendance::where([['month','=',$value->month],['year','=',$value->year],['employee_id','=',$value->employee_id]])
                                ->whereNotIn('attendance_date', $monthly_holidays)
                                ->count();

                            $employee_id =  $value->employee_id;
                            $total_present = Attendance::select('attendance_date')->whereBetween('attendance_date', [$from_date, $to_date])
                                ->where(function ($q) use ($employee_id) {
                                    $q->where([['employee_id','=',$employee_id],['clock_in','!=','']])->orWhere([['employee_id','=',$employee_id],['clock_out','!=','']]);
                                })
                                ->whereNotIn('attendance_date', $monthly_holidays)
                                ->count();
                            
                            $attendance2 = DB::table('attendance')
                                ->select('employee_id','month','year','attendance_date','clock_in','clock_out','working_hours_policy_id')
                                ->where([['attendance.employee_id','=',$employee_id]])
                                ->whereBetween('attendance_date',[$from_date,$to_date])
                                ->get();

                            $dates = array();


                            CommonHelper::reconnectMasterDatabase();
                            foreach($attendance2 as $value2):

                                $LikeDate = "'".'%'.$value2->year."-".$value2->month.'%'."'";

                                $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date,leave_application_data.no_of_days from leave_application INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id 
                                WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.employee_id = '.$value2->employee_id.' AND leave_application.status = 1 AND (leave_application.approval_status = 2 OR leave_application.approval_status_lm = 2) AND leave_application.view = "yes" 
                                OR leave_application_data.to_date LIKE '.$LikeDate.' AND leave_application_data.employee_id = '.$value2->employee_id.' AND leave_application.status = 1  AND (leave_application.approved = 2) AND leave_application.view = "yes" 
                                OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.employee_id = '.$value2->employee_id.'');


                                $leaves_from_dates2 = [];

                                if(!empty($leave_application_request_list)):
                                    foreach($leave_application_request_list as $value3):
                                        $leaves_from_dates = $value3->from_date;
                                        $leaves_to_dates = $value3->to_date;
                                        $leaves_from_dates2[] = $value3->from_date;
                                        $leaves_no_days[] = $value3->no_of_days;

                                        $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));

                                        foreach ($period as $date) {
                                            $dates[] = $date->format("Y-m-d");

                                        }
                                    endforeach;

                                endif;

                              
                               
                                $monthly_holidays_absents = array_merge($monthly_holidays,$dates);

                                CommonHelper::companyDatabaseConnection('12');
                                $total_absent_holidays1 = Attendance::select('attendance_date')->where([['employee_id','=',$value2->employee_id],['clock_in','=',''],['clock_out','=','']])->whereBetween('attendance_date',[$from_date,$to_date])
                                    ->whereNotIn('attendance_date', $monthly_holidays_absents)
                                    ->get()->toArray();
                                CommonHelper::reconnectMasterDatabase();

                            endforeach;

                            
                           count($total_absent_holidays1);
                           
                           CommonHelper::companyDatabaseConnection('12');
                            $attendance222 = DB::table('attendance')
                                ->where([['attendance.employee_id','=',$employee_id]])
                                ->whereBetween('attendance_date',[$from_date,$to_date]);
                            CommonHelper::reconnectMasterDatabase();
                            
                            count($totalHolidays);
                            $leaves = array();

                            foreach($attendance2 as $value2):
                                $LikeDate = "'".'%'.$value2->year."-".$value2->month.'%'."'";

                                $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date,leave_application_data.no_of_days from leave_application
                                INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                                WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.employee_id = '.$value2->employee_id.' AND leave_application.status = 1  AND
                                 leave_application.view = "yes"
                                 OR leave_application_data.to_date LIKE '.$LikeDate.' AND leave_application_data.employee_id = '.$value2->employee_id.' AND leave_application.status = 1  AND
                                 leave_application.view = "yes"
                                OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.employee_id = '.$value2->employee_id.'');
                                $dates2 = array();


                                $leaves_from_dates2 = [];

                                if(!empty($leave_application_request_list)):
                                    foreach($leave_application_request_list as $value3):
                                        $leaves_from_dates = $value3->from_date;
                                        $leaves_to_dates = $value3->to_date;
                                        $leaves_from_dates2[] = $value3->from_date;
                                        $leaves_no_days[] = $value3->no_of_days;

                                        $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));

                                        foreach ($period as $date2) {
                                            $dates2[] = $date2->format("Y-m-d");

                                        }
                                    endforeach;

                                endif;


                                CommonHelper::reconnectMasterDatabase();
                                $monthly_holidays_absents = array_merge($monthly_holidays,$dates2);

                                CommonHelper::companyDatabaseConnection('12');
                                $total_absent_holidays = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])->where([['employee_id','=',$value->employee_id],['clock_in','=',''],['clock_out','=','']])
                                    ->whereNotIn('attendance_date', $monthly_holidays_absents)
                                    ->get()->toArray();
                                CommonHelper::reconnectMasterDatabase();

                                if(in_array($value2->attendance_date,$dates2)):
                                    $leaves[] = $value2->attendance_date;
                                endif;
                            endforeach;

                           count($leaves);
                           count($leave_application_request_list);

                           count($leave_application_request_list);
                            
                       
                            $lates=0;
                            $late_deduct=0;
                            $half_days=0;
                            $half_days_count=0;
                            $grace_time='';


                            
                            foreach($attendance2 as $value2):
                                $working_hours_policy=DB::table('working_hours_policy')->where('id',$value2->working_hours_policy_id)->first();
                                $continue=1;
                                $continue_days_off=1;
                                $startTime = $working_hours_policy->start_working_hours_time;
                                $endTime = strtotime("+".$working_hours_policy->working_hours_grace_time."minutes", strtotime($startTime));
                                $finishTime = $working_hours_policy->end_working_hours_time;
                                $dutyEndTime = date("H:i", strtotime($finishTime));
                                
                                if($value2->clock_in > date('h:i', $endTime) || $value2->clock_out < $dutyEndTime){
                                    foreach($totalHolidays as $key => $mon_hol){
                                        if($value2->attendance_date == $mon_hol['holiday_date']){
                                            $continue=0;
                                            break;
                                        }else{
                                            $continue=1;
                                        }
                                    }
                                    foreach($total_days_off as $key => $mon_hol){
                                        if($value2->attendance_date == $mon_hol['attendance_date']){
                                            $continue_days_off=0;
                                            break;
                                        }else{
                                            $continue_days_off=1;
                                        }
                                    }
                                    
                                    
                                    if($continue==1 && $continue_days_off ==1){
                                        
                                    if($value2->clock_in > date('h:i', $endTime)){    
                                        $lates++;
                                        $late_deduct+=$working_hours_policy->deduction_amount_late_day;
                                    }    
                                    $half_day_time=strtotime("+".$working_hours_policy->half_day_time."minutes", strtotime($startTime));
                                    if($value2->clock_in >= date('h:i', $half_day_time)){
                                      
                                        $half_days++;
                                        $lates--;
                                        $late_deduct=$late_deduct-$working_hours_policy->deduction_amount_late_day;
                                        $half_days_count++;
                                    }elseif($value2->clock_out < $dutyEndTime && $value2->clock_in != ''){
                                        $half_days++;
                                        $half_days_count++;
                                    }

                                    
                                    }
                                    
                                }
                            endforeach;
                            $lates;
                            
                            $deduction=($late_deduct-$half_days_count)*$working_hours_policy->deduction_amount_late_day;
                            
                            $half_days;
                                
                            if($get_deduction->count()> 0): 
                                $overtime = $get_deduction->value('overtime'); 
                            else: 
                                $overtime=0; 
                            endif;
                            
                            $overtime;
                        
                            $deduction_days;
                            
                            
                            echo $total_present."===".$total_absent_holidays;
                        
            
                }
            
            }
            }   
        }
    }

}