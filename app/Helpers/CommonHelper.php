<?php
namespace App\Helpers;
use App\Models\Regions;
use DB;
use Config;
use Auth;
use Illuminate\Support\Facades\App;
use Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\MenuPrivileges;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use App\Models\Log;
use \Cache;
use DateTime;

class CommonHelper{

    public static function getSingleProjectSubTaskDetail($subTaskId,$m){
        CommonHelper::companyDatabaseConnection($m);
			$getProjectSubTaskDetail = DB::table('project_sub_tasks as pst')
                ->join('company_projects as cp', 'pst.project_id', '=', 'cp.id')
                ->join('customers as c','cp.customer_id','=','c.id')
                ->join('project_tasks as pt', 'pst.task_id', '=', 'pt.id')
                ->join('employee as e', 'pst.employee_id', '=', 'e.id')
                ->select('pst.*','c.customer_name','c.email', 'cp.project_name','cp.project_type', 'pt.task_name', 'e.emp_name', 'e.personal_email', 'e.official_email')
                ->where('pst.id', $subTaskId) // Replace $subTaskId with your actual variable or value
                ->first();
		CommonHelper::reconnectMasterDatabase();
        return $getProjectSubTaskDetail;
    }

    public static function getSingleProjectTaskDetail($taskId,$m){
        CommonHelper::companyDatabaseConnection($m);
        $getTaskDetail = DB::table('project_tasks as pt')
            ->join('employee as e', 'pt.employee_id', '=', 'e.id')
            ->join('company_projects as cp', 'pt.project_id', '=', 'cp.id')
            ->join('customers as c','cp.customer_id','=','c.id')
            ->select('pt.*','c.customer_name','c.email', 'e.emp_name', 'e.personal_email', 'e.official_email', 'cp.project_name', 'cp.project_type')
            ->where('pt.id', $taskId)
            ->first();
        CommonHelper::reconnectMasterDatabase();
        return $getTaskDetail;
    }

    public static function getProjectWiseProgressBar($projectId,$m){
        CommonHelper::companyDatabaseConnection($m);
			$getProjectWiseSummaryTask = DB::table('project_sub_tasks')
                ->select(
                    DB::raw('COUNT(*) AS totalTask'),
                    DB::raw('(SELECT COUNT(id) FROM project_sub_tasks WHERE sub_task_status = 2 and project_id = '.$projectId.') AS completedTask'),
                    DB::raw('(SELECT 
                        (COUNT(id) / (SELECT COUNT(*) FROM project_sub_tasks)) * 100 
                    FROM project_sub_tasks 
                    WHERE sub_task_status = 2 and project_id = '.$projectId.'
                    ) AS totalPercentage')
                )
                ->where('project_id',$projectId)
                ->first();
		CommonHelper::reconnectMasterDatabase();
        $data = '<div class="progress">';
        $data .= '<div class="progress-bar" role="progressbar" style="width: '.$getProjectWiseSummaryTask->totalPercentage.'%" aria-valuenow="'.$getProjectWiseSummaryTask->totalPercentage.'" aria-valuemin="0" aria-valuemax="100">'.$getProjectWiseSummaryTask->totalPercentage.'%</div>';
        $data .= '</div>';
        echo $data;
    }
    
    public static function getProjectWiseTaskSummaryPerformance($projectId,$m){
        CommonHelper::companyDatabaseConnection($m);
			$projectTask = DB::table('project_tasks')
                ->join('employee', 'project_tasks.employee_id', '=', 'employee.id')
                ->select('project_tasks.task_name', 'employee.emp_name','project_tasks.id')
                ->where('project_tasks.project_id',$projectId)
                ->get();
		CommonHelper::reconnectMasterDatabase();
        $data = '<div class="table-responsive"><table class="table table-bordered"><thead><tr><th class="text-center">Employee</th><th class="text-center">Pending</th><th class="text-center">Hold</th><th class="text-center">Inprogress</th><th class="text-center">Testing</th><th class="text-center">Reassign</th><th class="text-center">Completed</th><th class="text-center">Total</th><th class="text-center">Progress</th></tr></thead><tbody>';
        foreach($projectTask as $ptRow){
            CommonHelper::companyDatabaseConnection($m);
                $projectSubTaskSummary = DB::table('project_sub_tasks as pst')
                    ->join('employee as e', 'pst.employee_id', '=', 'e.id')
                    ->select(
                        'e.emp_name',
                        DB::raw('COUNT(CASE WHEN pst.sub_task_status = 1 THEN 1 END) AS pendingTask'),
                        DB::raw('COUNT(CASE WHEN pst.sub_task_status = 2 THEN 1 END) AS completedTask'),
                        DB::raw('COUNT(CASE WHEN pst.sub_task_status = 3 THEN 1 END) AS holdTask'),
                        DB::raw('COUNT(CASE WHEN pst.sub_task_status = 4 THEN 1 END) AS inprogressTask'),
                        DB::raw('COUNT(CASE WHEN pst.sub_task_status = 5 THEN 1 END) AS testingTask'),
                        DB::raw('COUNT(CASE WHEN pst.sub_task_status = 6 THEN 1 END) AS reassignTask'),
                        DB::raw('COUNT(*) AS totalTasks')
                    )
                    ->where('pst.task_id', $ptRow->id)
                    ->groupBy('e.id', 'e.emp_name')
                    ->get();
            CommonHelper::reconnectMasterDatabase();
            $data .= '<tr><th colspan="9">'.$ptRow->emp_name.' - '.$ptRow->task_name.'</th></tr>';
            foreach($projectSubTaskSummary as $pstsRow){
                $caculatePercentage = ($pstsRow->completedTask / $pstsRow->totalTasks) * 100;
                $data .= '<tr><td>'.$pstsRow->emp_name.'</td><td>'.$pstsRow->pendingTask.'</td><td>'.$pstsRow->holdTask.'</td><td>'.$pstsRow->inprogressTask.'</td><td>'.$pstsRow->testingTask.'</td><td>'.$pstsRow->reassignTask.'</td><td>'.$pstsRow->completedTask.'</td><td>'.$pstsRow->totalTasks.'</td><td>'.$caculatePercentage.' %</td></tr>';
            }
        }
        $data .= '</tbody></table></div>';
        echo $data;
    }

    public static function getIPAddress() {  
        //whether ip is from the share internet  
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
        //whether ip is from the proxy  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
        }  
        //whether ip is from the remote address  
        else{  
            $ip = $_SERVER['REMOTE_ADDR'];  
        }  
        return $ip;  
    }

    public static function addAttendanceAfterLogin($employeeId,$date,$time){
        $getDetail = DB::table('attendance')->where('employee_id',$employeeId)->where('attendance_date',$date)->get();
        if(count($getDetail) == 0){
            $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
                ->where(function ($q) use ($employeeId,$date) {
                    $q->where([['employee_id','=',$employeeId],['status','=',1],['from_date','<=',$date],['to_date','>=',$date]])
                    ->orWhere([['employee_id','=',$employeeId],['status','=',1],['fixed','=',1]]);
                })
                ->orderBy('id','desc')->value('working_hours_policy_id');
                
                $day_off = DB::table('assign_working_hour_policies')->select('day_off')
                    ->where(function ($q) use ($employeeId,$date) {
                        $q->where([['employee_id','=',$employeeId],['status','=',1],['from_date','<=',$date],['to_date','>=',$date]])
                        ->orWhere([['employee_id','=',$employeeId],['status','=',1],['fixed','=',1]]);
                    })
                    ->orderBy('id','desc')->value('day_off');
            
                
                $day_off_emp =  explode('=>',$day_off);
                $looping_date = date('D',strtotime($date));
                
                if(in_array($looping_date,$day_off_emp)){
                    $data['attendance_status'] = 'day_off';
                }else{
                    $data['attendance_status'] = '';
                }
            
            $date2 = new DateTime($date);

            // Format the date components
            $shortDayName = $date2->format('D');   // Short day name (e.g., Mon, Tue)
            $month = $date2->format('m');          // Short month name (e.g., Jan, Feb)
            $year = $date2->format('Y');
            $data = array(
                'employee_id' => $employeeId,
                'location_id' => 0,
                'attendance_date' => $date,
                'day' => $shortDayName,
                'working_hours_policy_id' => $working_hours_policy_id,
                'month' => $month,
                'year' => $year,
                'clock_in' => $time,
                'clock_in_location_id' => 0,
                'clock_out_location_id' => 0,
                'status' => 1,
                'remarks' => '-',
                'manual_attendance' => 1,
                'neglect_attendance' => 'no',
                'neglect_early' => 'no',
                'username' => Auth::user()->username,
                'date' => date('Y-m-d'),
                'time' => date("H:i:s")
            );
            
            DB::table('attendance')->insert($data);
        }
    }
    public static function changeDateFormat($param1){
        $date = date_create($param1);
        return date_format($date,"d-M-Y");
    }
    public static function changeDateFormatWithoutYear($param1){
        $date = date_create($param1);
        return date_format($date,"d-M");
    }
    public static function trim_all( $str , $what = NULL , $with = ' ' ){
        if( $what === NULL ){
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space
            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }
        return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
    }

    public static function number_to_word( $num = '' ){
        $num    = ( string ) ( ( int ) $num );
        if( ( int ) ( $num ) && ctype_digit( $num ) ){
            $words  = array( );
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );

            foreach( $num_levels as $num_part ){
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
                if( $tens < 20 ){
                    $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
                }else{
                    $tens   = ( int ) ( $tens / 10 );
                    $tens   = ' ' . $list2[$tens] . ' ';
                    $singles    = ( int ) ( $num_part % 10 );
                    $singles    = ' ' . $list1[$singles] . ' ';
                }
                $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
            }
            $commas = count( $words );
            if( $commas > 1 ){
                $commas = $commas - 1;
            }
            $words  = implode( ', ' , $words );
            //Some Finishing Touch
            //Replacing multiples of spaces with one space
            $words  = trim( str_replace( ' ,' , ',' , static::trim_all( ucwords( $words ) ) ) , ', ' );
            if( $commas ){
                $words  = str_replace_last( ',' , ' and' , $words );
            }
            return $words;
        }else if( ! ( ( int ) $num ) ){
            return 'Zero';
        }
        return '';
    }

    public static function insertAutoGeneratedLeaves($no_of_days,$leave_type,$emp_id,$leaves_policy_id,$excel_month,$excel_year,$attendance_date,$half_days_count,$company_id,$username,$type)
    {

        if($leave_type != ""){

            if($no_of_days == 1){
                $leave_day_type=1;
            }else{
                $leave_day_type=2;
            }

            $leaveApplicationData['employee_id']          = $emp_id;
            $leaveApplicationData['leave_policy_id'] = $leaves_policy_id;
            $leaveApplicationData['company_id']      = $company_id;
            $leaveApplicationData['leave_type']      = $leave_type;
            $leaveApplicationData['leave_day_type']  = $leave_day_type;
            $leaveApplicationData['name']  = '';
            $leaveApplicationData['reason']          = '-';
            $leaveApplicationData['leave_address']   = '-';
            $leaveApplicationData['approval_status'] = 1;
            $leaveApplicationData['approval_status_lm'] = 1;
            $leaveApplicationData['approved'] = 1;
            $leaveApplicationData['status']          = 1;
            $leaveApplicationData['type']          = $type;
            $leaveApplicationData['username']        = $username;
            $leaveApplicationData['date']            = date("Y-m-d");
            $leaveApplicationData['time']            = date("H:i:s");

            $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

            $maternityLeavesData['employee_id']               = $emp_id;
            $maternityLeavesData['leave_application_id'] = $leave_application_id;
            $maternityLeavesData['leave_policy_id'] = $leaves_policy_id;
            $maternityLeavesData['no_of_days']           = $no_of_days;

            if($leave_day_type == 1){
                $maternityLeavesData['from_date']              = $attendance_date;
                $maternityLeavesData['to_date']              = $attendance_date;
            }else{
                $maternityLeavesData['first_second_half_date']  = $attendance_date;
                $maternityLeavesData['first_second_half']        = 'first_half';
            }



            //                $startDate = new DateTime(Input::get('from_date'));
            //                $endDate = new DateTime(Input::get('to_date'));
            //                $difference = $endDate->diff($startDate);

            //                $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;

            $maternityLeavesData['status']               = 1;
            $maternityLeavesData['username']             = $username;
            $maternityLeavesData['date']                 = date("Y-m-d");
            $maternityLeavesData['time']                 = date("H:i:s");

            DB::table('leave_application_data')->insert($maternityLeavesData);
        }

    }

    public static function LogGenerator($param1,$param2,$param3,$param4,$param5,$param6){


        $log = new Log;
        $log->table_name = $param1;
        $log->activity_id = $param2;
        $log->activity = $param3;
        $log->remark = $param4;
        $log->module = $param5;
        $log->username = $param6;
        $log->date = date("Y-m-d");
        $log->time = date("H:i:s");
        $log->save();
    }
    public static function companyDBName($param1){
        return $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
    }



    public static function companyDatabaseConnection($param1){
        static::reconnectMasterDatabase();
        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');
    }

    public static function reconnectMasterDatabase(){
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
    }



    public static function displayExportButton($param1,$param2,$param3){
        ?>
        <button style="color:white;" class="btn btn-sm btn-warning btn-export" onclick="exportView('<?php echo $param1?>','<?php echo $param2?>','<?php echo $param3?>')" style="<?php echo $param2;?>">
            <i class="fas fa-print"></i>&nbsp; Export CSV
        </button>
        <?php
    }

    public static function displayPrintButtonInView($param1,$param2,$param3){
        ?>
        <button type="button" class="btn btn-sm btn-info btn-rounded" onclick="printView('<?php echo $param1?>','<?php echo $param2?>','<?php echo $param3?>')" style="<?php echo $param2;?>">
            <i class="fas fa-print"></i>&nbsp; Print
        </button>
        <?php
    }

    public static function displayPrintButtonInBlade($param1,$param2,$param3){
        ?>

        <button type="button" class="btn btn-sm btn-info" onclick="printView('<?php echo $param1?>','<?php echo $param2?>','<?php echo $param3?>')" style="<?php echo $param2;?>">
            <i class="fas fa-print"></i>&nbsp; Print
        </button>
        <?php
    }
    public static function displayPrintButtonWithImageInBlade($param1,$param2,$param3){
        ?>

        <button type="button" class="btn btn-sm btn-info" onclick="printViewWithImage('<?php echo $param1?>','<?php echo $param2?>','<?php echo $param3?>')" style="<?php echo $param2;?>">
            <i class="fas fa-print"></i>&nbsp; Print
        </button>
        <?php
    }

    public static function generateLeaves($employee,$month,$year,$totalHolidays,$from_date,$to_date,$username,$m){
        // foreach($employees as $value){
        CommonHelper::companyDatabaseConnection($m);

        $monthly_holidays = array();

        $emp=DB::table('employee')->select('working_hours_policy_id')->where([['emp_id','=',$employee]])->first();
        $emp_working_hours_policy_id=$emp->working_hours_policy_id;

        $allow_annual_leaves=DB::table('employee')->select('annual_leaves')->where([['emp_id','=',$employee]])->value('annual_leaves');

        $days_array = [];
        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$employee]])->value('day_off');
        $day_off_emp =  explode('=>',$day_off_emp);
        foreach($day_off_emp as $value2){
            if($value2 != ''){
                $days_array[] = $value2;
            }
        }

        CommonHelper::reconnectMasterDatabase();
        $working_hours_policy=WorkingHoursPolicy::where('id',$emp_working_hours_policy_id)->where('status',1)->first();
        $startTime = $working_hours_policy->start_working_hours_time;
        $endTime = strtotime("+".$working_hours_policy->working_hours_grace_time."minutes", strtotime($startTime));
        $half_day_time=strtotime("+".$working_hours_policy->half_day_time."minutes", strtotime($startTime));
        $finishTime = $working_hours_policy->end_working_hours_time;
        $dutyEndTime = date("H:i", strtotime($finishTime));

        CommonHelper::companyDatabaseConnection($m);

        $total_days_off = Attendance::select('attendance_date')
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->whereIn('day',$days_array)
            ->where('emp_id','=',$employee)->get()->toArray();



        $monthly_holidays = array_merge($totalHolidays,$total_days_off);


        $half_days_absent=date('h:i', $half_day_time);
        $end_day_time=date('h:i', $endTime);

        $lates_count=DB::table('attendance')->where([['month','=',$month],['year','=',$year],
            ['clock_in','>',$end_day_time],['clock_in','<',$half_days_absent],['emp_id','=',$employee]])
            ->whereNotIn('attendance_date', $monthly_holidays)->count();

        $half_days_count=DB::table('attendance')->where([['month','=',$month],['year','=',$year],
            ['clock_in','>=',$half_days_absent],['emp_id','=',$employee]])
            ->whereNotIn('attendance_date', $monthly_holidays)
            ->orWhere(function($nest) use($monthly_holidays,$employee,$month,$year,$dutyEndTime) {
                $nest->where([['month','=',$month],['year','=',$year],
                    ['clock_in','!=',''],['clock_out','<',$dutyEndTime],['emp_id','=',$employee]])
                    ->whereNotIn('attendance_date', $monthly_holidays);
            })
            ->count();

        $leave_type='';
        $lates=$lates_count;
        $leaves_policy_id=DB::table('employee')->select('leaves_policy_id')->where([['emp_id','=',$employee],['status','!=',2]])->value('leaves_policy_id');

        CommonHelper::reconnectMasterDatabase();
        $late_leave = DB::table('leave_application')->where([['emp_id','=',$employee],['name','=',$year.'-'.$month.'-01']]);

        if($late_leave->count() > 0){
            DB::table('leave_application_data')->where([['emp_id','=',$employee],['leave_application_id','=',$late_leave->value('id')]])->delete();
            DB::table('leave_application')->where([['emp_id','=',$employee],['name','=',$year.'-'.$month.'-01']])->delete();
        }

        if(($lates>=5 || $half_days_count > 0) && ($leaves_policy_id != '' && $leaves_policy_id != 0)){

            $leaves_policy = DB::table('leaves_policy')
                ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
                ->select('leaves_policy.*', 'leaves_data.*')
                ->where([['leaves_policy.id', '=', $leaves_policy_id]])
                ->get();
            $remaining_annual=0;
            $remaining_casual=0;
            $remaining_sick=0;
            $remaining_maternal=0;
            $remaining_paternal=0;
            foreach($leaves_policy as $val){
                if($val->leave_type_id == 1):
                    $annual_leaves= $val->no_of_leaves;
                elseif($val->leave_type_id == 3):
                    $casual_leaves = $val->no_of_leaves;
                else:
                    $sick_leaves = $val->no_of_leaves;
                endif;

                $getUsedLeaves =DB::table('leave_application_data')
                    ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                    ->where([['leave_application.emp_id','=',$employee],['leave_application.leave_type','=',$val->leave_type_id ],
                        ['leave_application.status', '=', '1'],
                        ['leave_application.approval_status_lm', '=', '2'],
                        ['leave_application.approval_status', '=', '2']])
                    ->sum('no_of_days');



                if($val->leave_type_id == 1):
                    $remaining_annual = $val->no_of_leaves-$getUsedLeaves;
                elseif($val->leave_type_id == 3):
                    $remaining_casual=$val->no_of_leaves -$getUsedLeaves;
                elseif($val->leave_type_id == 4):
                    $remaining_sick=$val->no_of_leaves -$getUsedLeaves;
                elseif($val->leave_type_id == 5):
                    $remaining_maternal=$val->no_of_leaves -$getUsedLeaves;
                elseif($val->leave_type_id == 6):
                    $remaining_paternal=$val->no_of_leaves -$getUsedLeaves;
                endif;

            }


            $remaining_lates=(floor($lates/5)+ ($half_days_count/2) );
            $remaining=$remaining_lates;
            if($remaining_casual > 0){
                if($remaining_casual >= $remaining){
                    $leave_type=3;
                    CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                }
                else{
                    $remaining=$remaining-$remaining_casual;
                    $leave_type='3';
                    CommonHelper::insertAutoGeneratedLeaves($remaining_casual,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);

                    if($remaining_sick > 0){
                        if($remaining_sick >= $remaining){
                            $leave_type='4';
                            CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                        }
                        else{
                            $remaining=$remaining-$remaining_sick;
                            $leave_type='4';
                            CommonHelper::insertAutoGeneratedLeaves($remaining_sick,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);

                            if($remaining_annual > 0 && $allow_annual_leaves == 1){
                                if($remaining_annual >= $remaining){
                                    $leave_type='1';
                                    CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                                }
                                else{
                                    $leave_type='1';
                                    CommonHelper::insertAutoGeneratedLeaves($remaining_annual,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                                }
                            }

                        }
                    }
                    else{
                        if($remaining_annual > 0  && $allow_annual_leaves == 1){
                            if($remaining_annual >= $remaining){
                                $leave_type='1';
                                CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                            }
                            else{
                                $leave_type='1';
                                CommonHelper::insertAutoGeneratedLeaves($remaining_annual,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                            }
                        }
                    }
                }
            }
            elseif($remaining_sick > 0){
                if($remaining_sick >= $remaining){
                    $leave_type=4;
                    CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                }
                else{
                    $remaining=$remaining-$remaining_sick;
                    $leave_type='4';
                    CommonHelper::insertAutoGeneratedLeaves($remaining_sick,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);

                    if($remaining_annual > 0  && $allow_annual_leaves == 1){
                        if($remaining_annual >= $remaining){
                            $leave_type='1';
                            CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                        }
                        else{
                            $leave_type='1';
                            CommonHelper::insertAutoGeneratedLeaves($remaining_annual,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                        }
                    }

                }
            }
            elseif($remaining_annual > 0  && $allow_annual_leaves == 1){
                if($remaining_annual >= $remaining){
                    $leave_type='1';
                    CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                }
                else{
                    $leave_type='1';
                    CommonHelper::insertAutoGeneratedLeaves($remaining_annual,$leave_type,$employee,$leaves_policy_id,$month,$year,$lates,$half_days_count,$username,$m);
                }
            }



            // if($remaining_casual > 0 && $remaining_casual >= (floor($lates/5)+ ($half_days_count/2) )){
            //         $leave_type=3;
            // }elseif($remaining_sick > 0 && $remaining_sick >= (floor($lates/5)+ ($half_days_count/2) )){
            //         $leave_type=4;
            // }elseif($remaining_annual > 0 && $remaining_annual >= (floor($lates/5)+ ($half_days_count/2) )){
            //         $leave_type=1;
            // }else{
            //      $leave_type='';
            // }


        }
        // }
    }


    // public static function insertAutoGeneratedLeaves($no_of_days,$leave_type,$emp_id,$leaves_policy_id,$excel_month,$excel_year,$lates,$half_days_count,$username,$company_id){

    //     if($leave_type != ""){

    //                     $leaveApplicationData['emp_id']          = $emp_id;
    //                     $leaveApplicationData['leave_policy_id'] = $leaves_policy_id;
    //                     $leaveApplicationData['company_id']      = $company_id;
    //                     $leaveApplicationData['leave_type']      = $leave_type;
    //                     $leaveApplicationData['leave_day_type']  = 1;
    //                     $leaveApplicationData['name']  = $excel_year.'-'.$excel_month.'-01';
    //                     $leaveApplicationData['reason']          = '-';
    //                     $leaveApplicationData['leave_address']   = '-';
    //                     $leaveApplicationData['approval_status'] = 2;
    //                     $leaveApplicationData['approved'] = 2;
    //                     $leaveApplicationData['status']          = 1;
    //                     $leaveApplicationData['username']        = $username;
    //                     $leaveApplicationData['date']            = date("Y-m-d");
    //                     $leaveApplicationData['time']            = date("H:i:s");

    //                     $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

    //                     $maternityLeavesData['emp_id']               = $emp_id;
    //                     $maternityLeavesData['leave_application_id'] = $leave_application_id;
    //                     $maternityLeavesData['leave_policy_id'] = $leaves_policy_id;
    //                     $maternityLeavesData['leave_type']           = $leave_type;
    //                     $maternityLeavesData['leave_day_type']       = 1;
    //                     $maternityLeavesData['no_of_days']           = $no_of_days;
    //                     $maternityLeavesData['from_date']            = $excel_year.'-'.$excel_month;
    //                     $maternityLeavesData['to_date']              = $excel_year.'-'.$excel_month;

    //     //                $startDate = new DateTime(Input::get('from_date'));
    //     //                $endDate = new DateTime(Input::get('to_date'));
    //     //                $difference = $endDate->diff($startDate);

    //     //                $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;

    //                     $maternityLeavesData['status']               = 1;
    //                     $maternityLeavesData['username']             = $username;
    //                     $maternityLeavesData['date']                 = date("Y-m-d");
    //                     $maternityLeavesData['time']                 = date("H:i:s");

    //                     DB::table('leave_application_data')->insert($maternityLeavesData);
    //     		}

    // }


    public static function getCompanyName($param1){
        static::reconnectMasterDatabase();
        echo $companyName = DB::selectOne('select `name` from `company` where `id` = '.$param1.'')->name;
    }

    public static function headerPrintSectionInPrintView($param1){
        $current_date = date('Y-m-d');
        ?>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                <label class="heading">Printed On Date&nbsp;:&nbsp;</label><label class="heading"><?php echo static::changeDateFormat($current_date);?></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                        >
                        <label class="headingCompanyName"><?php echo static::getCompanyName($param1);?></label>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                <?php $nameOfDay = date('l', strtotime($current_date)); ?>
                <label class="heading" >Printed On Day&nbsp;:&nbsp;</label><label class="heading"><?php echo '&nbsp;'.$nameOfDay;?></label>

            </div>
        </div>
        <div style="line-height:5px;">&nbsp;</div>

        <?php
    }

    public static function masterTableButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12){
        ?>
        <a onclick="showDetailModelMasterTable('<?php echo $param1?>','<?php echo $param9?>','<?php echo $param2?>','<?php echo $param3;?>','<?php echo $param4;?>','<?php echo $param5;?>','<?php echo $param6;?>','<?php echo $param10?>')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>
        <?php if($param2 == 2){?>
            <button class="delete-modal btn btn-xs btn-primary" onclick="repostCompanyMasterTableRecord('<?php echo $param12?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')">
                <span class="glyphicon glyphicon-refresh"> Repost</span>
            </button>

        <?php }else{?>
            <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('<?php echo $param7?>','<?php echo $param3 ?>','<?php echo $param8 ?>','<?php echo $param1?>')">
                <span class="glyphicon glyphicon-edit"> Edit</span>
            </button>
            <button class="delete-modal btn btn-xs btn-danger" onclick="deleteCompanyMasterTableRecord('<?php echo $param11?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')">
                <span class="glyphicon glyphicon-trash"> Delete</span>
            </button>
        <?php }?>
        <?php
    }

    public static function checkMasterTableVoucherDetailStatus($param1){
        if($param1 == 1){
            echo 'Active';
        }else if($param1 == 0){
            echo 'In-Active';
        }
    }

    public static function voucherStatusSelectList(){
        return '<option value="0">All Vouchers</option><option value="1">Pending Vouchers</option><option value="2">Approve Vouchers</option><option value="3">Delete Vouchers</option>';
    }

    public static function accountHeadSelectList($param1){
        static::companyDatabaseConnection($param1);
        $accountList = DB::table('accounts')->get();
        static::reconnectMasterDatabase();
        ?>
        <datalist id="selectAccountHead">
            <?php foreach ($accountList as $row){?>
            <option data-id="<?php echo $row->id;?>" value="<?php echo $row->name;?>">
                <?php }?>
        </datalist>
        <?php
    }


    public static function getMasterTableValueByIdTwo($param1,$param2,$param3,$param4){
        static::reconnectMasterDatabase();
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `company_id` = '.$param1.' and id = '.$param4.'' )->$param3;
        static::companyDatabaseConnection($param1);
        return $detailName;
    }

    public static function getMasterTableValueById($param1,$param2,$param3,$param4){

        //return $param1.'P1--'.$param2.'P2--'.$param3.'P3--'.$param4.'P4--';
        return $detailName = DB::selectOne('select '.$param3.' from ' .$param2.' where company_id = '.$param1.' and id = '.$param4.'' )->$param3;
    }

    public static function getMasterTableValueByIdWithoutCompanyId($param1,$param2,$param3){
        return $detailName = DB::selectOne('select  '.$param2.' from ' .$param1.' where id = '.$param3.'' )->$param2;
    }



    public static function getCompanyDatabaseTableValueById($param1,$param2,$param3,$param4){
        static::companyDatabaseConnection($param1);
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where id = '.$param4.'' );

        if($detailName):
            $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where id = '.$param4.'' )->$param3;
        else:
            $detailName = '<span style="color:red">Deleted</span>';
        endif;
        static::reconnectMasterDatabase();
        return $detailName;
    }


    public static function insertAttendanceLog($type, $employee_id, $attendance_date, $time, $attendance_type,$working_hours_policy_id,$location_id,$latitude,$longitude,$username,$loc){
        
        $parent_id=0;
        if($type=='clock_out'){
            $last_clock_in=DB::table('attendance_logs')->where('employee_id',$employee_id)->where('status',1)
            ->where('location_id',$location_id)->where('attendance_location',$loc)
            ->where('attendance_type',$attendance_type)->where('attendance_date',$attendance_date)
            ->where('type','clock_in')->orderBy('id','desc')->first();
            if(!empty($last_clock_in)){
                $parent_id=$last_clock_in->id;
            }
        }else{
            $parent_id=0;
        }
        
        DB::table('attendance_logs')->insert([
            'type' => $type,
            'employee_id' => $employee_id,
            'attendance_date' => $attendance_date,
            'time' => $time,
            'parent_id' => $parent_id,
            'attendance_type' => $attendance_type,
            'attendance_location' =>$loc,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'username' => $username,
            'working_hours_policy_id' => $working_hours_policy_id,
            'location_id' => $location_id,
            'status' => 1
        ]);
    }

    public static function getCompanyDatabaseTableValueByColumnNameValue($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where '.$param4.' = '.$param5.'' )->$param3;
        static::reconnectMasterDatabase();
        return $detailName;
    }

    public static function categoryList($param1,$param2){
        echo '<option value="">Select Category</option>';
        static::companyDatabaseConnection($param1);
        $categoryList = new Category;
        $categoryList = $categoryList::where([['status', '=', '1'], ])->orderBy('id')->get();
        static::reconnectMasterDatabase();
        foreach($categoryList as $row){
            ?>
            <option value="<?php echo $row['id'];?>" <?php if($param2 == $row['id']){echo 'selected';}?>><?php echo $row['main_ic'];?></option>
            <?php
        }
    }

    public static function uomList($param1,$param2){
        echo '<option value="">Select UOM</option>';
        $uomList = DB::table('uom')->where([['status', '=', '1'],['company_id', '=', $param1], ])->orderBy('id')->get();
        foreach($uomList as $row){?>
            <option value="<?php echo $row->id;?>" <?php if($param2 == $row->id){echo 'selected';}?>><?php echo $row->uom_name;?></option><?php
        }
    }

    public static function subItemList($param1,$param2,$param3){
        echo '<option value="">Select Item</option>';
        static::companyDatabaseConnection($param1);
        $subItemList = new Subitem;
        $subItemList = $subItemList::where([['status', '=', '1'],['main_ic_id', '=', $param3], ])->orderBy('id')->get();
        static::reconnectMasterDatabase();
        foreach($subItemList as $row){
            ?>
            <option value="<?php echo $row['id'];?>" <?php if($param2 == $row['id']){echo 'selected';}?>><?php echo $row['sub_ic'];?></option>
            <?php
        }
    }

    public static function newMasterTableButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12){
        ?>
        <li><a onclick="showDetailModelMasterTable('<?php echo $param1?>','<?php echo $param9?>','<?php echo $param2?>','<?php echo $param3;?>','<?php echo $param4;?>','<?php echo $param5;?>','<?php echo $param6;?>','<?php echo $param10?>')"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>
        <?php if($param2 == 2){?>
            <li><a onclick="repostCompanyMasterTableRecord('<?php echo $param12?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')"><span class="glyphicon glyphicon-eye-open"></span> Repost</a></li>
        <?php }else{?>
            <li><a onclick="showMasterTableEditModel('<?php echo $param7?>','<?php echo $param3 ?>','<?php echo $param8 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>
            <li><a onclick="deleteCompanyMasterTableRecord('<?php echo $param11?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
        <?php }
    }

    public static function getAccountIdByMasterTable($param1,$param2,$param3){
        static::companyDatabaseConnection($param1);
        $accountId = DB::selectOne('select `acc_id` from `'.$param3.'` where `id` = '.$param2.'')->acc_id;
        static::reconnectMasterDatabase();
        return $accountId;
    }

    public static function getAllPurchaseQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $purchaseBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '3'])
            ->where('date','<=',$param5 )
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalPurchaseBalance = 0;
        foreach ($purchaseBalance as $row){
            $totalPurchaseBalance += $row->qty;
        }
        return $totalPurchaseBalance;
    }

    public static function getAllIssueQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $sendBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '2'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalSendBalance = 0;
        foreach ($sendBalance as $row){
            $totalSendBalance += $row->qty;
        }
        return $totalSendBalance;
    }

    public static function getAllCashSaleQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $cashSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '5'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalCashSaleBalance = 0;
        foreach ($cashSaleBalance as $row){
            $totalCashSaleBalance += $row->qty;
        }
        return $totalCashSaleBalance;
    }

    public static function getAllCreditSaleQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $creditSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '6'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalCreditSaleBalance = 0;
        foreach ($creditSaleBalance as $row){
            $totalCreditSaleBalance += $row->qty;
        }
        return $totalCreditSaleBalance;
    }

    public static function getAllStoreChallanReturQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $storeReturnBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '4'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalStoreReturnBalance = 0;
        foreach ($storeReturnBalance as $row){
            $totalStoreReturnBalance += $row->qty;
        }
        return $totalStoreReturnBalance;
    }

    public static function getTotalGRNAmountByGRNNo($param1,$param2,$param3,$param4,$param5,$param6){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table($param3)
            ->select(DB::raw("SUM($param4) as $param4"))
            ->where([$param5 => $param2,'status' => '1',$param6 => 2])
            ->groupBy(DB::raw($param5))
            ->get();
        static::reconnectMasterDatabase();
        $totalAmount = 0;
        foreach ($dataRecord as $row){
            $totalAmount += $row->$param4;
        }
        return $totalAmount;
    }

    public static function loadPurchaseOrderQtyItemId($param1,$param2){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table('purchase_order_data')
            ->where('id','=',$param2)
            ->first();
        //->where(['category_id' => $param3,'sub_item_id' => $param4,'purchase_request_no' => $param2,'status' => 1,'purchase_request_status' => 2])
        //->first();
        static::reconnectMasterDatabase();
        return $dataRecord->purchase_order_qty;
    }

    public static function getDemandNoByPrNo($param1,$param2,$param3,$param4){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table('purchase_request_data')
            ->where(['category_id' => $param3,'sub_item_id' => $param4,'purchase_request_no' => $param2,'status' => 1,'purchase_request_status' => 2])
            ->first();
        static::reconnectMasterDatabase();
        return $dataRecord->demand_no;
    }

    public static function getDemandDateByPrNo($param1,$param2,$param3,$param4){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table('purchase_request_data')
            ->where(['category_id' => $param3,'sub_item_id' => $param4,'purchase_request_no' => $param2,'status' => 1,'purchase_request_status' => 2])
            ->first();
        static::reconnectMasterDatabase();
        return $dataRecord->demand_date;
    }

    public static function getInvoiceNoByGRNNo($param1,$param2){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table('goods_receipt_note')
            ->where(['grn_no' => $param2,'status' => 1,'grn_status' => 2])
            ->first();
        static::reconnectMasterDatabase();
        return $dataRecord->invoice_no;
    }

    public static function getTotalInvoiceAmountByInvoiceNo($param1,$param2,$param3,$param4,$param5,$param6){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table($param3)
            ->select(DB::raw("SUM($param4) as $param4"))
            ->where([$param5 => $param2,'status' => '1',$param6 => 2])
            ->groupBy(DB::raw($param5))
            ->get();
        $invoiceDetail = DB::table('invoice')->where('inv_no','=',$param2)->where('status','=',1)->first();
        static::reconnectMasterDatabase();
        $totalAmount = 0;
        foreach ($dataRecord as $row){
            $totalAmount += $row->$param4;
        }
        $calculatedTotalDiscount = $totalAmount*$invoiceDetail->inv_against_discount/100;
        $calculatedTotalAmount = $totalAmount - $calculatedTotalDiscount;
        return $calculatedTotalAmount;
    }

    public static function getImages($dir) {
        $retval = [];
        // Validate directory to prevent directory traversal attacks
        $dir = rtrim($dir, '/') . '/';
        $fulldir = $_SERVER['DOCUMENT_ROOT'] . $dir;
    
        // Check if directory exists
        if (!is_dir($fulldir)) {
            die("Directory '$dir' not found");
        }
    
        // Get list of files in the directory
        $files = scandir($fulldir);
    
        foreach ($files as $entry) {
            // Skip hidden files and directories
            if ($entry[0] === '.') {
                continue;
            }
    
            $filePath = $fulldir . $entry;
    
            // Check if it's a file
            if (is_file($filePath)) {
                // Determine MIME type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimetype = finfo_file($finfo, $filePath);
                finfo_close($finfo);
    
                // Check if MIME type is an image
                if (strpos($mimetype, 'image/') === 0) {
                    $retval[] = [
                        'file' => $dir . $entry,
                        'size' => getimagesize($filePath)
                    ];
                }
            }
        }
    
        return $retval;
    }


    public static function operations_rights()
    {
        if(Auth::user()->acc_type != 'client'):

            $user_rights = MenuPrivileges::where([['employee_id','=',Auth::user()->employee_id]]);
            $crud_permission[]='';
            if($user_rights->count() > 0):
                $crud_rights  = explode(",",$user_rights->value('crud_rights'));

                $link = Request::segment(1)."/".Request::segment(2);
                $getTitle = $user_rights = Menu::where([['m_controller_name','=',$link]])->value('m_main_title');

                if(in_array('view_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "view";
                endif;
                if(in_array('edit_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "edit";
                endif;
                if(in_array('repost_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "repost";
                endif;
                if(in_array('delete_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "delete";
                endif;
                if(in_array('print_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "print";
                endif;
                if(in_array('export_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "export";
                endif;
                if(in_array('approve_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "approve";
                endif;
                if(in_array('reject_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "reject";
                endif;

            endif;

        else:
            $crud_permission[] = "view";
            $crud_permission[] = "edit";
            $crud_permission[] = "repost";
            $crud_permission[] = "delete";
            $crud_permission[] = "print";
            $crud_permission[] = "export";
            $crud_permission[] = "approve";
            $crud_permission[] = "reject";

        endif;

        return $crud_permission;
    }

    public static function operations_rights_ajax_pages($url)
    {
        if(Auth::user()->acc_type != 'client'):

            $user_rights = MenuPrivileges::where([['employee_id','=',Auth::user()->employee_id]]);
            $crud_permission[]='';
            if($user_rights->count() > 0):
                $crud_rights  = explode(",",$user_rights->value('crud_rights'));
                $getTitle = $user_rights = Menu::where([['m_controller_name','=',$url]])->value('m_main_title');

                if(in_array('view_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "view";
                endif;
                if(in_array('edit_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "edit";
                endif;
                if(in_array('repost_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "repost";
                endif;
                if(in_array('delete_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "delete";
                endif;
                if(in_array('print_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "print";
                endif;
                if(in_array('export_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "export";
                endif;
                if(in_array('approve_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "approve";
                endif;
                if(in_array('reject_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "reject";
                endif;

            endif;

        else:
            $crud_permission[] = "view";
            $crud_permission[] = "edit";
            $crud_permission[] = "repost";
            $crud_permission[] = "delete";
            $crud_permission[] = "print";
            $crud_permission[] = "export";
            $crud_permission[] = "approve";
            $crud_permission[] = "reject";

        endif;

        return $crud_permission;
    }

    public static function userRoles($company_id)
    {
        $employee_id = Auth::user()->employee_id;
        $menu_privileges = DB::table('menu_privileges')->select('location_permission')->where([['employee_id','=', $employee_id],['status', '=', 1]])->orderBy('id','desc')->first();

        if(Auth::user()->acc_type == 'user'):

            static::companyDatabaseConnection($company_id);
            $employee = DB::table('employee AS e')->join('employee_multiple_locations AS eml', 'e.id','=', 'eml.employee_id')
                ->where([['e.status','=', 1],['eml.status','=', 1],['e.id','=', $employee_id]])
                ->select('e.department_id','e.sub_department_id','e.category_id','eml.project_id','eml.location_id')
                ->first();
            static::reconnectMasterDatabase();

            $department_ids = explode(',',$employee->department_id);
            $sub_department_ids = explode(',',$employee->sub_department_id);
            $category_ids = explode(',',$employee->category_id);
            $location_ids = explode(',',$employee->location_id);
            $project_ids = explode(',',$employee->project_id);

        elseif(Auth::user()->acc_type == 'admin' && $menu_privileges->location_permission != 0):

            $department = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM department WHERE status = 1"));
            $department_ids = explode(',',$department[0]->ids);

            $sub_department = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM sub_department WHERE status = 1"));
            $sub_department_ids = explode(',',$sub_department[0]->ids);

            $employee_category = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM employee_category WHERE status = 1"));
            $category_ids = explode(',',$employee_category[0]->ids);

            $employee_projects = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM employee_projects WHERE status = 1"));
            $project_ids = explode(',',$employee_projects[0]->ids);

            $location_ids = explode(',',$menu_privileges->location_permission);

        elseif(Auth::user()->acc_type == 'client'):

            $department = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM department WHERE status = 1"));
            $department_ids = explode(',',$department[0]->ids);

            $sub_department = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM sub_department WHERE status = 1"));
            $sub_department_ids = explode(',',$sub_department[0]->ids);

            $employee_category = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM employee_category WHERE status = 1"));
            $category_ids = explode(',',$employee_category[0]->ids);

            $employee_projects = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM employee_projects WHERE status = 1"));
            $project_ids = explode(',',$employee_projects[0]->ids);

            $locations = DB::select(DB::raw("SELECT GROUP_CONCAT(id) AS ids FROM locations WHERE status = 1"));
            $location_ids = explode(',',$locations[0]->ids);

        endif;

        empty($department_ids) ? $department_ids = [] : '';
        empty($sub_department_ids) ? $sub_department_ids = [] : '';
        empty($category_ids) ? $category_ids = [] : '';
        empty($project_ids) ? $project_ids = [] : '';
        empty($location_ids) ? $location_ids = [] : '';
        return compact('department_ids','sub_department_ids','category_ids','project_ids','location_ids');
    }
    
    public static function convertNumberToWords($number)
    {
        $num = str_replace(array(',', ' '), '' , trim($number));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',
            'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
        );
        $list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');
        $list3 = array('', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion');
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }



}
?>