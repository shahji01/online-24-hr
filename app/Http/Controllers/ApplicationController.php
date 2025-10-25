<?php

namespace App\Http\Controllers;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\apiLeaveHelper;

use App\Models\Holidays;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use App\Models\LeaveType;
use App\Models\Payslip;

use Carbon\Carbon;
use DatePeriod;
use DateTime;
use DateInterval;
use Hash;
use File;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use \Cache;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ApplicationController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function login()
    {
        $username = HrHelper::replacePrefix($_GET['username']);
        $password = $_GET['password'];
        $designation_name = '';
        $department_name = '';
        $sub_department_name = '';
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $designations = Cache::get('designation');
        $data = [];

        $user = DB::table('users')->where([['mobile_no', '=', $username]]);
        if (Hash::check($password, $user->value('password'))) {
            if ($user->count() > 0) {

                CommonHelper::companyDatabaseConnection($user->value('company_id'));
                $employee = DB::table('employee')
                    ->select('id', 'emp_id','religion','cnic','current_address', 'emp_name', 'emp_father_name', 'marital_status_id','allow_app_attendance', 'designation_id', 'sub_department_id', 'department_id', 'img_path', 'joining_date', 'contact_no', 'leaves_policy_id', 'date_of_birth','personal_email','official_email')
                    ->where([['status', '=', 1], ['emp_id', '=', $user->value('emp_id')]]);
                if ($employee->count() > 0) {
                    $employee = $employee->first();

                    $working_hours_time = DB::table(CONST_COMPANY_DB.'.employee as e')
                        ->join(CONST_COMPANY_DB.'.assign_working_hour_policies as awhp', 'e.id', '=', 'awhp.employee_id')
                        ->join(CONST_MASTER_DB.'.working_hours_policy as whp', 'whp.id', '=', 'awhp.working_hours_policy_id')
                        ->select('whp.start_working_hours_time', 'whp.end_working_hours_time')
                        ->where('e.id', $employee->id)
                        ->where('awhp.fixed', 1)
                        ->first();

                    if ($employee->designation_id != '') {
                        if (array_key_exists($employee->designation_id, $designations)) {
                            $designation_name = $designations[$employee->designation_id]->designation_name;
                        }
                    }
                    if ($employee->department_id != '') {
                        if (array_key_exists($employee->department_id, $departments)) {
                            $department_name = $departments[$employee->department_id]->department_name;
                        }
                    }
                    if ($employee->sub_department_id != '') {
                        if (array_key_exists($employee->sub_department_id, $sub_departments)) {
                            $sub_department_name = $sub_departments[$employee->sub_department_id]->sub_department_name;
                        }
                    }

                    $data['id'] = $employee->id;
                    $data['emp_id'] = $employee->emp_id;
                    $data['marital_status'] = $employee->marital_status_id==1 ? "Single" : 'Married';
                    $data['cnic'] = $employee->cnic;
                    $data['religion'] = $employee->religion;
                    $data['company_id'] = $user->value('company_id')? $user->value('company_id') : '';
                    $data['emp_name'] = $employee->emp_name ? $employee->emp_name  : '';
                    $data['password_status'] = $user->value('password_status') ?$user->value('password_status') : '';
                    $data['emp_father_name'] = $employee->emp_father_name ? $employee->emp_father_name  : '';
                    $data['contact_no'] = $employee->contact_no ? $employee->contact_no: '';
                    $data['designation'] = $designation_name ? $designation_name : '';
                    $data['department_name'] = $department_name ? $department_name: '';
                    $data['allow_app_attendance'] = $employee->allow_app_attendance ?$employee->allow_app_attendance: '';
                    $data['sub_department_name'] = $sub_department_name ? $sub_department_name: '';
                    $data['leaves_policy_id'] = $employee->leaves_policy_id  ? $employee->leaves_policy_id : '';
                    $data['email'] = $employee->official_email ? $employee->official_email: '';
                    $data['joining_date'] = $employee->joining_date ? date("d-M-Y", strtotime($employee->joining_date)) : '';
                    $data['date_of_birth'] = $employee->date_of_birth ? date("d-M-Y", strtotime($employee->date_of_birth)) : '';
                    $data['address'] = $employee->current_address;
                    $data['img_path'] = $employee->img_path ? $employee->img_path : '';
                    $data['start_working_hours_time'] = $working_hours_time ? $working_hours_time->start_working_hours_time : '';
                    $data['end_working_hours_time'] = $working_hours_time ? $working_hours_time->end_working_hours_time : '';

                    $current_date = date("Y-m-d"); 
                    $first_date_of_current_month = date("Y-m-01", strtotime($current_date));
                    $get_holidays = DB::table('holidays')->select('holiday_date')
                    ->where([['status', '=', 1]])
                    ->whereBetween('holiday_date',[$first_date_of_current_month,$current_date])
                    ->orderBy('holiday_date');
                    $monthly_holidays = [];
                    if ($get_holidays->count() > 0):
                        foreach ($get_holidays->get() as $value2):
                            $monthly_holidays[] = $value2->holiday_date;
                        endforeach;
                    else:
                        $monthly_holidays = [];
                    endif;

                    $month_days=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));

                    $absents_count=DB::table('attendance')->where('employee_id',$employee->id)
                    ->where('attendance_status','!=','day_off')
                    ->where('clock_in','')->where('clock_out','')
                    ->whereBetween('attendance_date',[$first_date_of_current_month,$current_date])
                    ->whereNotIn('attendance_date',$monthly_holidays)->count();

                    $attendance_percentage = $this->calculateAttendancePercentage(($month_days-$absents_count), $month_days);
                    $data['attendance_percentage'] = round($attendance_percentage, 2);

                    return json_encode(['message' => 'Login Successful', 'status' => true, 'data' => $data]);

                } else {
                    return json_encode(['message' => 'Employee does not exist', 'status' => false , 'data' => $data ],JSON_FORCE_OBJECT);
                }
            } else {
                return json_encode(['message' => 'Username or password is incorrect', 'status' => false  , 'data' => $data ],JSON_FORCE_OBJECT);
            }
        } else {
            return json_encode(['message' => 'Username or password is incorrect', 'status' => false  , 'data' => $data ],JSON_FORCE_OBJECT);
        }
    }

    public function calculateAttendancePercentage($attended_instances, $total_possible_instances) {
        if ($total_possible_instances <= 0) {
            return 0; // Avoid division by zero
        }
    
        $attendance_percentage = ($attended_instances / $total_possible_instances) * 100;
        return $attendance_percentage;
    }

    
    public function getAttendanceByLocation()
    {
        $employee_id = request()->employee_id;
        $location_id = request()->location_id;
        CommonHelper::companyDatabaseConnection(12);
        $attendance_in = DB::table('attendance_logs')
        ->where([
            ['status', '=', 1],
            ['employee_id', '=', $employee_id],
            ['location_id', '=', $location_id], 
            ['time', '!=', ''],
            ['type', '=', 'clock_in'], 
            ['attendance_date', '=', date("Y-m-d")]
         ])
         ->orderBy('id','desc')
         ->first();
         $attendance_out = DB::table('attendance_logs')
        ->where([
            ['status', '=', 1],
            ['employee_id', '=', $employee_id],
            ['location_id', '=', $location_id], 
            ['time', '!=', ''],
            ['type', '=', 'clock_out'], 
            ['attendance_date', '=', date("Y-m-d")]
         ])
         ->orderBy('id','desc')
         ->first();
        CommonHelper::reconnectMasterDatabase();
        if(!empty($attendance_in)) {
            $data['clock_in'] = $attendance_in->time;
        }else {
            $data['clock_in'] = NULL;
        }
        if(!empty($attendance_out)) {
            $data['clock_out'] = $attendance_out->time;
        } else {
            $data['clock_out'] = NULL;
        }
        return json_encode(['message' => 'Attendance fetched successfully', 'status' => true, 'data' => $data]);
    }

    public function getAttendance()
    {
        $employee_id = request()->employee_id;
        CommonHelper::companyDatabaseConnection(12);
        $attendance = DB::table('attendance')
        ->where([
            ['status', '=', 1],
            ['employee_id', '=', $employee_id], 
            ['clock_in', '!=', ''], 
            ['attendance_date', '=', date("Y-m-d")]
         ])->first();
        CommonHelper::reconnectMasterDatabase();
        if(!empty($attendance)) {
            $data['clock_in'] = $attendance->clock_in;
            $data['clock_out'] = $attendance->clock_out;
        } else {
            $data['clock_in'] = NULL;
            $data['clock_out'] = NULL;
        }
        return json_encode(['message' => 'Attendance fetched successfully', 'status' => true, 'data' => $data]);
    }


    public function addAttendanceByLocation()
    {
        try {
            //clock_in = 1, clock_out = 2
            //$company_id = request()->company_id;
            $employee_id = request()->employee_id;
            $location_id = request()->location_id;
            $designation_id='';
            $check = request()->get('check');
            $latitude = request()->latitude;
            $longitude = request()->longitude;
            $username = request()->username;
            $working_hours_policy_id = 0 ;
            $month_year = explode('-', date("Y-m-d"));
            $day = strtotime(date("Y-m-d"));
            $day = date('D', $day);
            $data = [];

            CommonHelper::companyDatabaseConnection(12);

            $employee_promotion = DB::table('employee_promotion')
                ->where('employee_id', $employee_id)
                ->where('approval_status', 2)
                ->where('status', 1)
                ->select('designation_id')
                ->orderByDesc('id');
            if($employee_promotion->count()>0){
                $designation_id= $employee_promotion->value('designation_id'); 
            }else{
                $designation_id= DB::table('employee')
                ->where('id', $employee_id)
                ->where('status', 1)->select('designation_id')->value('designation_id');
            }
                  

            $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
                ->where('status',1)->where('employee_id',$employee_id)->where('from_date','=',date('Y-m-d'))->where('to_date','=',date('Y-m-d'))->value('working_hours_policy_id');
            if($working_hours_policy_id == null ) {
                $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
                    ->where('status',1)->where('employee_id',$employee_id)->where('fixed','=',1)->value('working_hours_policy_id');

            }
            if($working_hours_policy_id == null ) {
                return json_encode(['message' => 'working hour policy not found. Please contact to your hr', 'status' => false , 'data' => []]);
            }

            $company_id = 12;
            $working_hour_policy_id_by_location = apiLeaveHelper::checkAttendanceLocation($employee_id, $company_id, $latitude, $longitude);
         
            if($working_hour_policy_id_by_location['result'] == false || $working_hour_policy_id_by_location['result'] == 'false') {
                return json_encode(['message' => 'You are not on your assigned location', 'status' => false , 'data' => [] ]);
            }

            $working_hour_policy_id_by_location1 = apiLeaveHelper::checkEmployeeLocation($employee_id, $company_id, $latitude, $longitude, $location_id);

            if($working_hour_policy_id_by_location1['result'] == false || $working_hour_policy_id_by_location1['result'] == 'false') {
                return json_encode(['message' => 'You are not at current your selected location', 'status' => false , 'data' => [] ]);
            }



            if($working_hour_policy_id_by_location['result'] == true || $working_hour_policy_id_by_location['result'] == 'true') {
                if($working_hour_policy_id_by_location['location_id'] == 1  || $working_hour_policy_id_by_location['location_id'] == 8  ) {
                    $working_hours_policy_id = 2;
                } else {
                    $working_hours_policy_id = 1;
                }
            }

            CommonHelper::companyDatabaseConnection(12);
            $allow_app_attendance = DB::table('employee')
                ->select('allow_app_attendance')
                ->where([['status', '=', 1], ['id', '=', $employee_id]])->value('allow_app_attendance');

            if ($allow_app_attendance == 1):
                // $this->WeekdaysAttendance($employee_id,$username);
                $attendance_date= date("Y-m-d");
                $designation_id='';
                $time= date("H:i");
                $firstDateOfAttendance = date('Y-m-01', strtotime($attendance_date));
                $lastDateOfAttendance = date('Y-m-t', strtotime($attendance_date));
               
                if ($check == 1):

                    $designation_id = DB::table('employee')
                    ->leftJoin('employee_promotion', function($join) {
                        $join->on('employee.id', '=', 'employee_promotion.employee_id');
                    })
                    ->where('employee.id', $employee_id)
                    ->select(DB::raw('COALESCE(employee_promotion.designation_id, employee.designation_id) as designation_id'))
                    ->orderByDesc('employee_promotion.id')
                    ->value('designation_id');
                    
                    
                  

                    if(DB::table('attendance_logs')->where([
                        ['employee_id','=',$employee_id],['attendance_date','=',$attendance_date],
                        ['location_id','=',$location_id],['status','=',1],
                        ['type','=','clock_in'],['time','!=',''],['attendance_location','=','client']
                    ])->count() > 0){
                        if(DB::table('attendance_logs')->where([
                            ['employee_id','=',$employee_id],['attendance_date','=',$attendance_date],
                            ['location_id','=',$location_id],['status','=',1],
                            ['type','=','clock_out'],['attendance_location','=','client']
                        ])->count() == 0){
                            return json_encode(['message' => 'You are already checked in at the current location.', 'status' => false , 'data' => [] ]);
                        }
                    }
                   
                        apiLeaveHelper::AddAllowanceOfFuelByLocation($location_id,$designation_id,$employee_id,date("Y-m-d"),$username);
                        CommonHelper::insertAttendanceLog('clock_in', $employee_id, $attendance_date, $time, 2,$working_hours_policy_id,$location_id,$latitude,$longitude,$username,'client');
                    

                elseif ($check == 2):

                    if(DB::table('attendance_logs')->where([
                        ['employee_id','=',$employee_id],['attendance_date','=',$attendance_date],
                        ['location_id','=',$location_id],['status','=',1],
                        ['type','=','clock_in'],['time','!=',''],['attendance_location','=','client']
                    ])->count() > 0){
                        CommonHelper::insertAttendanceLog('clock_out', $employee_id, $attendance_date, $time, 2,$working_hours_policy_id,$location_id,$latitude,$longitude,$username,'client');
                    }else{
                        return json_encode(['message' => 'You are not checked in at the current location.', 'status' => false , 'data' => [] ]);
                    }
                    
                    

                endif;

               

                CommonHelper::reconnectMasterDatabase();

                //CommonHelper::generateLeaves([$employee_id],$month_year[1],$month_year[0],$totalHolidays,$from_date,$to_date,$username,'12');

                return json_encode(['message' => 'Attendance Saved Successfully', 'status' => true , 'data' => [] ]);
            else:
                return json_encode(['message' => 'Remote Attendance Not Assigned. Please contact HR / Operations department', 'status' => false, 'data' => [] ]);
            endif;
        } catch (\Exception $ex) {
            return json_encode(['message' => 'Something went wrong: '.$ex, 'status' => false, 'data' => [] ]);
        }

    }


    public function addAttendance()
    {
        try {
            //clock_in = 1, clock_out = 2
            //$company_id = request()->company_id;
            $employee_id = request()->employee_id;
            $designation_id='';
            $check = request()->get('check');
            $latitude = request()->latitude;
            $longitude = request()->longitude;
            $username = request()->username;
            $working_hours_policy_id = 0;
            $month_year = explode('-', date("Y-m-d"));
            $day = strtotime(date("Y-m-d"));
            $day = date('D', $day);
            $data = [];

            CommonHelper::companyDatabaseConnection(12);

            $employee_promotion = DB::table('employee_promotion')
                ->where('employee_id', $employee_id)
                ->where('approval_status', 2)
                ->where('status', 1)
                ->select('designation_id')
                ->orderByDesc('id');
            if($employee_promotion->count()>0){
                $designation_id= $employee_promotion->value('designation_id'); 
            }else{
                $designation_id= DB::table('employee')
                ->where('id', $employee_id)
                ->where('status', 1)->select('designation_id')->value('designation_id');
            }
                  

            $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
                ->where('status',1)->where('employee_id',$employee_id)->where('from_date','=',date('Y-m-d'))->where('to_date','=',date('Y-m-d'))->value('working_hours_policy_id');
            if($working_hours_policy_id == null ) {
                $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
                    ->where('status',1)->where('employee_id',$employee_id)->where('fixed','=',1)->value('working_hours_policy_id');

            }
            if($working_hours_policy_id == null ) {
                return json_encode(['message' => 'working hour policy not found. Please contact to your hr', 'status' => false , 'data' => []]);
            }

            $company_id = 12;
            $working_hour_policy_id_by_location = apiLeaveHelper::checkAttendanceLocation($employee_id, $company_id, $latitude, $longitude);

            if($working_hour_policy_id_by_location['result'] == false || $working_hour_policy_id_by_location['result'] == 'false') {
                return json_encode(['message' => 'You are not on your assigned location', 'status' => false , 'data' => [] ]);
            }



            if($working_hour_policy_id_by_location['result'] == true || $working_hour_policy_id_by_location['result'] == 'true') {
                if($working_hour_policy_id_by_location['location_id'] == 1  || $working_hour_policy_id_by_location['location_id'] == 8  ) {
                    $working_hours_policy_id = 2;
                } else {
                    $working_hours_policy_id = 1;
                }
            }

            CommonHelper::companyDatabaseConnection(12);
            $allow_app_attendance = DB::table('employee')
                ->select('allow_app_attendance')
                ->where([['status', '=', 1], ['id', '=', $employee_id]])->value('allow_app_attendance');

            if ($allow_app_attendance == 1):
                $this->WeekdaysAttendance($employee_id,$username);
                $attendance_date= date("Y-m-d");
                $designation_id='';
                $time= date("H:i");
                $firstDateOfAttendance = date('Y-m-01', strtotime($attendance_date));
                $lastDateOfAttendance = date('Y-m-t', strtotime($attendance_date));
               
                if ($check == 1):

                    $designation_id = DB::table('employee')
                    ->leftJoin('employee_promotion', function($join) {
                        $join->on('employee.id', '=', 'employee_promotion.employee_id');
                    })
                    ->where('employee.id', $employee_id)
                    ->select(DB::raw('COALESCE(employee_promotion.designation_id, employee.designation_id) as designation_id'))
                    ->orderByDesc('employee_promotion.id')
                    ->value('designation_id');
                    
                  

                    if(DB::table('attendance_logs')->where([
                        ['employee_id','=',$employee_id],['attendance_date','=',$attendance_date],
                        ['location_id','=',$working_hour_policy_id_by_location['location_id']],['status','=',1],
                        ['type','=','clock_in'],['time','!=',''],['attendance_location','=','main']
                    ])->count() > 0){
                        return json_encode(['message' => 'You are already checked in at the current location.', 'status' => false , 'data' => [] ]);
                    }
                   
                    $data['employee_id'] = $employee_id;
                    $data['clock_in'] = $time;
                    $data['clock_in_latitude'] = $latitude;
                    $data['clock_in_longitude'] = $longitude;
                    $data['working_hours_policy_id'] = $working_hours_policy_id;
                    $data['clock_in_location_id'] = $working_hour_policy_id_by_location['location_id'];
                    $data['clock_in_username'] = $username;
                    $data['attendance_date'] = $attendance_date;
                    $data['month'] = $month_year[1];
                    $data['year'] = $month_year[0];
                    $data['day'] = $day;
                    $data['attendance_type'] = 2;
                    //$data['attendance_status'] = 1;
                    $data['status'] = 1;
                    $data['username'] = $username;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");

                    if (DB::table('attendance')->where([['status', '=', 1], ['employee_id', '=', $employee_id], ['attendance_date', '=', date("Y-m-d")]])->count() > 0):
                        if(DB::table('attendance')->where([['status', '=', 1], ['employee_id', '=', $employee_id], ['attendance_date', '=', date("Y-m-d")]]) ->where(function ($query) {
                            $query->where('clock_in', '')
                                  ->orWhereNull('clock_in');
                        })->count()>0){
                           DB::table('attendance')->where([['status', '=', 1], ['employee_id', '=', $employee_id], ['attendance_date', '=', date("Y-m-d")]])->update($data);
                        }
                        // apiLeaveHelper::AddAllowanceOfFuelByLocation($working_hour_policy_id_by_location['location_id'],$designation_id,$employee_id,date("Y-m-d"),$username);
                        CommonHelper::insertAttendanceLog('clock_in', $employee_id, $attendance_date, $time, 2,$working_hours_policy_id,$working_hour_policy_id_by_location['location_id'],$latitude,$longitude,$username,'main');
                    else:
                        DB::table('attendance')->insert($data);
                        // apiLeaveHelper::AddAllowanceOfFuelByLocation($working_hour_policy_id_by_location['location_id'],$designation_id,$employee_id,date("Y-m-d"),$username);
                        CommonHelper::insertAttendanceLog('clock_in', $employee_id, $attendance_date, $time, 2,$working_hours_policy_id,$working_hour_policy_id_by_location['location_id'],$latitude,$longitude,$username,'main');
                    endif;

                elseif ($check == 2):

                    if (DB::table('attendance')->where([['status', '=', 1], ['employee_id', '=', $employee_id], ['attendance_date', '=', date("Y-m-d")]])->exists()):
                        $data['clock_out'] =$time;
                        $data['clock_out_latitude'] = $latitude;
                        $data['clock_out_longitude'] = $longitude;
                        $data['clock_out_location_id'] = $working_hour_policy_id_by_location['location_id'];
                        $data['clock_out_username'] = $username;
                        $data['status'] = 1;
                        $data['username'] = $username;
                        $data['date'] = date("Y-m-d");
                        $data['time'] = date("H:i:s");
                        DB::table('attendance')->where([['status', '=', 1], ['employee_id', '=', $employee_id], ['attendance_date', '=', date("Y-m-d")]])->update($data);
                        CommonHelper::insertAttendanceLog('clock_out', $employee_id, $attendance_date, $time, 2,$working_hours_policy_id,$working_hour_policy_id_by_location['location_id'],$latitude,$longitude,$username,'main');
                    else:
                        $data['employee_id'] = $employee_id;
                        $data['clock_out'] = $time;
                        $data['working_hours_policy_id'] = $working_hours_policy_id;
                        $data['clock_out_latitude'] = $latitude;
                        $data['clock_out_longitude'] = $longitude;
                        $data['clock_out_location_id'] = $working_hour_policy_id_by_location['location_id'];
                        $data['clock_out_username'] = $username;
                        $data['attendance_date'] = $attendance_date;
                        $data['month'] = $month_year[1];
                        $data['year'] = $month_year[0];
                        $data['day'] = $day;
                        $data['attendance_type'] = 2;
                        //$data['attendance_status'] = 1;
                        $data['status'] = 1;
                        $data['username'] = $username;
                        $data['date'] = date("Y-m-d");
                        $data['time'] = date("H:i:s");
                        DB::table('attendance')->insert($data);
                        CommonHelper::insertAttendanceLog('clock_out', $employee_id, $attendance_date, $time, 2,$working_hours_policy_id,$working_hour_policy_id_by_location['location_id'],$latitude,$longitude,$username,'main');
                    endif;

                endif;

                $workingHourPolicyUpdate = " 
                        UPDATE ".CONST_COMPANY_DB.".attendance a
                        INNER JOIN (
                                SELECT a.employee_id, a.attendance_date, awhp.day_off, MAX(awhp.working_hours_policy_id)AS max_working_hours_policy_id
                            FROM ".CONST_COMPANY_DB.".attendance a
                            INNER JOIN ".CONST_COMPANY_DB.".assign_working_hour_policies awhp
                            ON a.employee_id = awhp.employee_id
                            INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp
                            ON whp.id = awhp.working_hours_policy_id
                            WHERE 
                            awhp.status = 1 AND (a.working_hours_policy_id IS NULL OR a.working_hours_policy_id = 0)
                            AND (
                                (a.attendance_date BETWEEN awhp.from_date AND awhp.to_date AND awhp.fixed = 0 )
                                    OR
                        
                                ( 
                                    case 
                                        
                                        when a.clock_in is not null then
                                            case 
                                            
                                            when  a.clock_in != '' then
                                                    awhp.fixed = 1 and awhp.status = 1 AND  TIME_FORMAT(whp.start_working_hours_time, '%H:%i')  BETWEEN TIME_FORMAT(SUBTIME(a.clock_in, '03:00'), '%H:%i') AND TIME_FORMAT(ADDTIME(a.clock_in, '03:00'), '%H:%i')
                                            END
                                    
                                    when a.clock_out is not null then
                                            case 
                                            
                                            when  a.clock_out != '' then
                                                    awhp.fixed = 1 and awhp.status = 1 AND  TIME_FORMAT(whp.end_working_hours_time, '%H:%i')  BETWEEN TIME_FORMAT('00:00', '%H:%i') AND TIME_FORMAT('23:59', '%H:%i')
                                            end
                        
                                    end
                                )
                        
                        
                                OR
                                (awhp.fixed = 1 AND NOT EXISTS (
                                    SELECT 1 FROM ".CONST_COMPANY_DB.".assign_working_hour_policies awhp2
                                    WHERE a.employee_id = awhp2.employee_id
                                    AND a.attendance_date BETWEEN awhp2.from_date AND awhp2.to_date
                                    AND awhp2.fixed = 0 and awhp.status = 1 
                                ))
                            )
                        
                            GROUP BY a.employee_id, a.attendance_date
                        ) subquery
                        ON a.employee_id = subquery.employee_id AND a.attendance_date = subquery.attendance_date
                        SET a.working_hours_policy_id = subquery.max_working_hours_policy_id,
                        a.attendance_status = IF( subquery.day_off LIKE CONCAT('%', DATE_FORMAT(STR_TO_DATE(a.attendance_date, '%Y-%m-%d'), '%a'), '%'), 'day_off', '')
                                ";
                                             
                DB::select(DB::raw($workingHourPolicyUpdate));


                // $from_date= $month_year[0]."-".$month_year[1]."-".'01';
                // $to_date= $month_year[0]."-".$month_year[1]."-".'31';

                // $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);
                // $totalHolidays = $get_holidays->get()->toArray();

                //  $this->addAutomatedLeaves($employee_id,$month_year[1],$month_year[0],$username);

                CommonHelper::reconnectMasterDatabase();

                //CommonHelper::generateLeaves([$employee_id],$month_year[1],$month_year[0],$totalHolidays,$from_date,$to_date,$username,'12');

                return json_encode(['message' => 'Attendance Saved Successfully', 'status' => true , 'data' => [] ]);
            else:
                return json_encode(['message' => 'Remote Attendance Not Assigned. Please contact HR / Operations department', 'status' => false, 'data' => [] ]);
            endif;
        } catch (\Exception $ex) {
            return json_encode(['message' => 'Something went wrong: '.$ex, 'status' => false, 'data' => [] ]);
        }

    }

    public function WeekdaysAttendance($employee_id,$username){

        $date = date("Y-m-d");
        $explode_date = explode('-',$date);
        $month = $explode_date[1];
        $year = $explode_date[0];
        $total_days_of_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);

        for($i = 1; $i <= $total_days_of_month; $i++) {

            $dataa['employee_id'] = $employee_id;
            $dataa['attendance_date'] = $year."-".$month."-".$i;
            $dataa['day'] = date('D', strtotime(str_replace('/', '-',$year."-".$month."-".$i)));
            $dataa['month'] = $month;
            $dataa['year'] = $year;
            $dataa['clock_in'] = '';
            $dataa['clock_out'] = '';
            $dataa['status'] = 1;
            $dataa['username'] = $username;
            $dataa['date'] = date("Y-m-d");
            $dataa['time'] = date("H:i:s");

            $check_exist = DB::Table('attendance')->where([
                ['employee_id', '=', $employee_id],
                ['attendance_date', '=',$year."-".$month."-".$i]]);
            if($check_exist->count() > 0) {
            } else {
                DB::Table('attendance')->insert($dataa);
            }
        }
    }

    
    public function viewAttendanceByLocation()
    {
        $employee_id = request()->input('employee_id');
        CommonHelper::companyDatabaseConnection(12);

        $attendance = DB::table('attendance_logs')
            ->where('employee_id', $employee_id)
            ->where('attendance_date', date('Y-m-d'))
            ->where('status', 1)
            ->where('attendance_location', 'client')
            ->whereIn('type', ['clock_in', 'clock_out'])
            ->orderBy('id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $data=[];
        $check_in='';
        $check_out='';
        $parent_id=0;
        foreach($attendance as $att){
            $location_name=DB::table('locations')->select('location_name')->where('id',$att->location_id)->value('location_name');
            if($att->type=='clock_in'){
                $check_in=$att->time;
                $check_out='';
                $parent_id=$att->id;
            }
            if($att->type=='clock_out'){
                $check_out=$att->time;
            }

            $found = false;
            foreach ($data as $key => $entry) {
                if (($entry['parent_id'] == $parent_id && $parent_id != 0) && 
                    $entry['employee_id'] == $employee_id && 
                    $entry['attendance_date'] == $att->attendance_date) {
                    
                    
                    $data[$key]['check_in'] = $check_in;
                    $data[$key]['check_out'] = $check_out;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                
                $new_input = array(
                    'parent_id' => $parent_id,
                    'employee_id' => $employee_id,
                    'client_name' => $location_name,
                    'attendance_date' => $att->attendance_date,
                    'check_in' => $check_in,
                    'check_out' => $check_out,
                );
                array_push($data, $new_input);
            }
            
        }   
        
        return json_encode(['message' => 'Attendance fetched successfully', 'status' => true, 'data' => $data]);
    }
    public function viewAttendanceReport()
    {
        $employee_id = request()->employee_id;
        $from_date = request()->from_date;
        $to_date = request()->to_date;

        $attendancequery = "SELECT att.employee_id,
                    att.working_hours_policy_id,
                    CASE
                  WHEN h.holiday_date = att.attendance_date AND h.status = 1 THEN 'Holiday'
                  WHEN att.attendance_status = 'day_off' THEN 'Off day'
                  WHEN ((att.clock_in  != '' or att.clock_out != '') AND IFNULL(lad.first_second_half_date,'0000-00-00') = '0000-00-00') THEN 'Routine'
                  WHEN (lad.first_second_half_date = '0000-00-00' AND (att.attendance_date = lad.from_date AND att.attendance_date = lad.to_date) OR (att.attendance_date BETWEEN lad.from_date AND lad.to_date)) THEN 'Leave'
                  WHEN (lad.first_second_half_date != '0000-00-00' AND att.attendance_date = lad.first_second_half_date ) THEN 'Half day leave'
                  ELSE 'Absent'
                  END AS 'Event',
                  whp.working_hours_policy,
                  att.day,
                  DATE_FORMAT(STR_TO_DATE(att.attendance_date, '%Y-%m-%d'), '%d-%M-%Y') AS attendance_date,
                  IFNULL(att.clock_in, '--') AS clock_in,
                  IFNULL(att.clock_out, '--') AS clock_out,

                  CASE
                  WHEN (lad.first_second_half_date = '0000-00-00' AND (att.attendance_date = lad.from_date AND att.attendance_date = lad.to_date) OR (att.attendance_date BETWEEN lad.from_date AND lad.to_date)) THEN 'Leave'
                  WHEN h.holiday_date = att.attendance_date AND h.status = 1 THEN 'Off day'
                  WHEN att.attendance_status = 'day_off' THEN 'Off day'
                  WHEN ((att.clock_in  != '' or att.clock_out != '') AND IFNULL(lad.first_second_half_date,'0000-00-00') = '0000-00-00') THEN 'Present'
                  WHEN (IF(half_day_time != '', TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(start_working_hours_time) + (half_day_time * 60)), '%H:%i') , 0) < att.clock_in) THEN 'Half day'
                  WHEN (lad.first_second_half_date != '0000-00-00' AND att.attendance_date = lad.first_second_half_date) THEN 'Half day leave'
                  ELSE 'Absent'
                  END AS AttStatus,

                 CASE
                WHEN att.clock_out < att.clock_in THEN
                  IFNULL(CONCAT(
                      LPAD(FLOOR((TIME_TO_SEC(TIMEDIFF('23:59:59', att.clock_in)) + TIME_TO_SEC(att.clock_out)) / 3600), 2, '0'), ':',
                      LPAD(MOD(FLOOR((TIME_TO_SEC(TIMEDIFF('23:59:59', att.clock_in)) + TIME_TO_SEC(att.clock_out)) / 60), 60), 2, '0')
                  ), '--')
              ELSE
                  IFNULL(CONCAT(
                      LPAD(FLOOR(TIME_TO_SEC(TIMEDIFF(att.clock_out, att.clock_in)) / 3600), 2, '0'), ':',
                      LPAD(MOD(FLOOR(TIME_TO_SEC(TIMEDIFF(att.clock_out, att.clock_in)) / 60), 60), 2, '0')
                  ), '--')
          END AS duration,
                  IFNULL(IF(
                      FLOOR(TIME_TO_SEC(TIMEDIFF(att.clock_in, SEC_TO_TIME(TIME_TO_SEC(start_working_hours_time) + (working_hours_grace_time * 60)))) / 60)
                      < '0' ,'--' ,
                      FLOOR(TIME_TO_SEC(TIMEDIFF(att.clock_in, SEC_TO_TIME(TIME_TO_SEC(start_working_hours_time) ))) / 60)
                  ),'--') AS late ,
                  CASE
                  WHEN att.attendance_status = 'day_off' OR (h.holiday_date = att.attendance_date AND h.status = 1) THEN '--'
                  WHEN (STR_TO_DATE(att.clock_out, '%H:%i') < SUBTIME(STR_TO_DATE(end_working_hours_time, '%H:%i'),  TIME_FORMAT(SEC_TO_TIME(whp.early_going_grace_time * 60), '%H:%i'))) THEN
                    FLOOR(
                        TIME_TO_SEC(TIMEDIFF(
                            SUBTIME(STR_TO_DATE(end_working_hours_time, '%H:%i'),
                                    TIME_FORMAT(SEC_TO_TIME(whp.early_going_grace_time * 60), '%H:%i')),
                            STR_TO_DATE(att.clock_out, '%H:%i')
                        )) / 60
                    )
                  ELSE
                   '--'
                 END early_going

            FROM ".CONST_COMPANY_DB.".attendance att
              INNER JOIN ".CONST_COMPANY_DB.".employee e ON att.employee_id = e.id
              INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp ON att.working_hours_policy_id = whp.id
              LEFT JOIN (
                SELECT DISTINCT holiday_date ,status
                FROM ".CONST_COMPANY_DB.".holidays
               ) h ON att.attendance_date = h.holiday_date
              LEFT JOIN (
                  SELECT a.attendance_date,la.employee_id, lad.from_date, lad.to_date , first_second_half_date FROM ".CONST_COMPANY_DB.".attendance a
                  INNER JOIN ".CONST_MASTER_DB.".leave_application la
                  ON a.employee_id = la.employee_id
                  INNER JOIN  ".CONST_MASTER_DB.".leave_application_data lad
                  ON la.id = lad.leave_application_id
                  WHERE
                  a.employee_id IN ($employee_id) AND
                  IF (lad.first_second_half_date = '0000-00-00',
                      a.attendance_date BETWEEN lad.from_date AND lad.to_date,
                      a.attendance_date = lad.first_second_half_date) AND
                  a.attendance_date  BETWEEN '$from_date' and '$to_date'
              ) lad ON att.employee_id = lad.employee_id  AND lad.attendance_date = att.attendance_date

              WHERE
              att.status = 1 AND att.employee_id IN ($employee_id)
              AND att.attendance_date BETWEEN '$from_date' AND '$to_date'
              ORDER BY att.attendance_date

            ";
            // return $attendancequery;

        $attendance = DB::select(DB::raw($attendancequery));

        if(count($attendance) > 0) {
            return json_encode(['message' => 'attendance data ', 'status' => true, 'data' => $attendance]);
        } else {
            return json_encode(['message' => 'Attendance data not found', 'status' => false , 'data' => $attendance ]);
        }
    }


    public function viewAttendanceRequestQuery(){
        $employee_id = request()->employee_id;
        $attendance_queries_array=[];
        CommonHelper::companyDatabaseConnection(12);
        
        $team_members = DB::table('employee')->select('employee.id','employee.emp_id','employee.emp_name')
        ->where('reporting_manager_id',$employee_id)
        ->where('employee.status',1);
        
        $ids_array=[];
        foreach($team_members->get() as $value){
            $ids_array[]=$value->id;
        }
        $attendance_queries=DB::table('attendance_queries')->whereIn('employee_id',$ids_array)->where('status',1)->orderBy('id','desc'); 
        $attendance_queries_mine=DB::table('attendance_queries')->where('employee_id',$employee_id)->where('status',1)->orderBy('id','desc');
        foreach($attendance_queries->paginate(15) as $val){
            CommonHelper::companyDatabaseConnection(12);
                $employee = DB::table('employee')->select('emp_id','emp_name')->where([['status', '=', 1], ['id', '=', $val->employee_id]])->first();
            CommonHelper::reconnectMasterDatabase();
            if($val->approval_status==2){
               $approval_status="Resolved"; 
            }else if($val->approval_status==1){
               $approval_status="Pending"; 
            }else if($val->approval_status==3){
               $approval_status="Rejected"; 
            }
           
            $new_input = array(
                'id' => $val->id,
                'emp_id' => $employee->emp_id, 
                'emp_name' => $employee->emp_name,
                'attendance_date' => $val->attendance_date,
                'clock_in' => $val->clock_in,
                'clock_out' => $val->clock_out,
                'query' => $val->query,
                'approval_status' => $approval_status
            );
            array_push($attendance_queries_array, $new_input);
        }
        
        foreach($attendance_queries_mine->paginate(15) as $val){
            CommonHelper::companyDatabaseConnection(12);
                $employee = DB::table('employee')->select('emp_id','emp_name')->where([['status', '=', 1], ['id', '=', $val->employee_id]])->first();
            CommonHelper::reconnectMasterDatabase();
            if($val->approval_status==2){
               $approval_status="Resolved"; 
            }else if($val->approval_status==1){
               $approval_status="Pending"; 
            }else if($val->approval_status==3){
               $approval_status="Rejected"; 
            }
           
            $new_input = array(
                'id' => $val->id,
                'emp_id' => $employee->emp_id, 
                'emp_name' => $employee->emp_name,
                'attendance_date' => $val->attendance_date,
                'clock_in' => $val->clock_in,
                'clock_out' => $val->clock_out,
                'query' => $val->query,
                'approval_status' => $approval_status
            );
            array_push($attendance_queries_array, $new_input);
        }
        
        
        CommonHelper::reconnectMasterDatabase();
        return json_encode(['message' => 'Time Request Fetched successfully', 'status' => true,'data' => $attendance_queries_array,'length' => $attendance_queries_mine->count()+$attendance_queries->count() ]);
    }


//    public function viewAttendanceList()
//    {
//        $emp_id = $_GET['emp_id'];
//        $date = $_GET['date'];
//        $month_year = explode('-', $date);
//        $data = [];
//        $list = [];
//        $totalOffDates = [];
//        $monthly_holidays = [];
//        $leave_dates = [];
//        $counter = 0;
//        $holidays = [];
//        $leaves = [];
//        $leave_type = [1 => 'Annual Leave', 2 => 'Sick Leave', 3 => 'Casual Leave'];
//
//        $month = intval($month_year[1]);
//        $year = intval($month_year[0]);
//
//        $current_month = date('m');
//        if ($month == $current_month):
//            $day = date('d');
//        else:
//            $day = 31;
//        endif;
//        for ($d = 1; $d <= $day; $d++) {
//            $time = mktime(12, 0, 0, $month, $d, $year);
//            if (date('m', $time) == $month)
//                $list[] = date('Y-m-d', $time);
//        }
//
//        CommonHelper::companyDatabaseConnection('12');
//        $get_holidays = DB::table('holidays')->select('holiday_date', 'holiday_name')->where([['status', '=', 1], ['month', '=', $month], ['year', '=', $year]])->orderBy('holiday_date');
//        if ($get_holidays->count() > 0):
//            foreach ($get_holidays->get() as $value2):
//                $monthly_holidays[$value2->holiday_date] = $value2->holiday_name;
//            endforeach;
//        else:
//            $monthly_holidays = [];
//        endif;
//        $employee = DB::table('employee')->select('roster_id')->where([['emp_id', '=', $emp_id], ['status', '=', 1]])->first();
//
//        CommonHelper::companyMasterDatabaseConnection();
//        $roster_detail = DB::table('roster_master')
//            ->join('rosters', 'roster_master.id', '=', 'rosters.roster_id')
//            ->where([['roster_master.id', '=', $employee->roster_id]])
//            ->select('rosters.*')
//            ->first();
//
//        $LikeDate = "'" . '%' . $month_year[0] . "-" . sprintf("%02d", $month_year[1]) . '%' . "'";
//        $leave_application_request_list = DB::select('select leave_application_data.from_date,leave_application_data.to_date, leave_application_data.leave_type from leave_application
//                                    INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
//                                    WHERE leave_application_data.from_date LIKE ' . $LikeDate . ' AND leave_application_data.emr_no = ' . $emr_no . '
//                                    AND leave_application.status = 1 AND leave_application.approval_status = 2
//                                    OR leave_application_data.first_second_half_date LIKE ' . $LikeDate . ' AND leave_application_data.emr_no = ' . $emr_no . '');
//
//        if (!empty($leave_application_request_list)):
//            foreach ($leave_application_request_list as $value2):
//                $leaves_from_dates = $value2->from_date;
//                $leaves_to_dates = $value2->to_date;
//
//                $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates . '+1 day'));
//
//                foreach ($period as $date):
//                    $leave_dates[$date->format("Y-m-d")] = $value2->leave_type;
//                endforeach;
//            endforeach;
//        else:
//            $leave_dates = [];
//        endif;
//
//        foreach ($monthly_holidays as $key => $holiday):
//            $holidays[$key] = $holiday;
//        endforeach;
//
//        foreach ($leave_dates as $key => $leave):
//            $leaves[$key] = $leave;
//        endforeach;
//
//        CommonHelper::companyDatabaseConnection($company_id);
//
//        if ($year <= date('Y') && $month <= date('m')):
//            foreach ($list as $value):
//
//                $attendance = DB::table('attendance')
//                    ->where([['emp_id', '=', $emp_id], ['year', '=', $year], ['month', '=', $month], ['attendance_type', '=', 2],
//                        ['attendance_date', '=', $value]]);
//
//                $total_days_off = 'Sun';
//                $day = date('D', strtotime($value));
//                if ($day == $total_days_off):
//                    $totalOffDates[] = $value;
//                    $attendance_status = '';
//                endif;
//
//                if ($attendance->count() > 0):
//                    $attendance = $attendance->first();
//                    $grace_time = strtotime($roster_detail->late);
//                    $late = 0;
//                    if ($attendance->attendance_status == 1):
//                        $clock_in = strtotime($attendance->clock_in);
//                        $late = (($clock_in - $grace_time) / 60);
//                        if ($late <= 0):
//                            $late = 0;
//                        endif;
//                    endif;
//
//                    $data[$counter]['attendance_id'] = $attendance->id;
//                    $data[$counter]['attendance_date'] = ApplicationHelper::date_format($value);
//                    $data[$counter]['day'] = $day;
//                    $data[$counter]['attendance_status'] = 'Present';
//                    $data[$counter]['clock_in'] = $attendance->clock_in;
//                    $data[$counter]['clock_out'] = $attendance->clock_out;
//                    $data[$counter]['late'] = $late;
//                    $counter++;
//                else:
//                    $data[$counter]['attendance_id'] = '';
//                    $data[$counter]['attendance_date'] = ApplicationHelper::date_format($value);
//                    $data[$counter]['day'] = $day;
//                    $data[$counter]['attendance_status'] = 'Absent';
//                    $data[$counter]['clock_in'] = '';
//                    $data[$counter]['clock_out'] = '';
//                    $data[$counter]['late'] = 0;
//
//                    if (in_array($value, $totalOffDates)):
//                        $data[$counter]['attendance_status'] = 'Weekly Holiday';
//                    endif;
//                    if (array_key_exists($value, $holidays)):
//                        $data[$counter]['attendance_status'] = $holidays[$value];
//                    endif;
//                    if (array_key_exists($value, $leaves)):
//                        $data[$counter]['attendance_status'] = $leave_type[$leaves[$value]];
//                    endif;
//                    $counter++;
//                endif;
//            endforeach;
//        endif;
//        CommonHelper::reconnectMasterDatabase();
//        return json_encode(['data' => $data]);
//    }

   public function fetchAttendanceByDate()
   {
       $employee_id = request()->employee_id;
       $date = request()->date;
       CommonHelper::companyDatabaseConnection(12);

       $attendance = DB::table('attendance')->where([['status', '=', 1], ['employee_id', '=', $employee_id],['attendance_date', '=', $date]])->first();
       CommonHelper::reconnectMasterDatabase();
       if (!empty($attendance)) {
           $data['attendance_date'] = $attendance->attendance_date;
           $data['clock_in'] = $attendance->clock_in;
           $data['clock_out'] = $attendance->clock_out;
           return json_encode(['message' => 'Attendance fetched successfully', 'status' => true, 'data' => $data]);
       }else {
           $data['attendance_date'] = $date;
           $data['clock_in'] = NULL;
           $data['clock_out'] = NULL;
           return json_encode(['message' => 'Attendance fetched successfully', 'status' => true, 'data' => $data]);
       }
   }


//
//    function randomPassword() {
//        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
//        $pass = array(); //remember to declare $pass as an array
//        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
//        for ($i = 0; $i < 8; $i++) {
//            $n = rand(0, $alphaLength);
//            $pass[] = $alphabet[$n];
//        }
//        return implode($pass); //turn the array into a string
//    }
//
//    public function forgotPassword(){
//        $username=request()->email;
//        $user = DB::table('users')->where([['username', '=', $username]]);
//        if($user->count()>0){
//            $password=$this->randomPassword();
//
//            $mail = new PHPMailer(true);
//            try {
//                //Server settings
//                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
//                $mail->isSMTP();                                            //Send using SMTP
//                $mail->Host       = "smrhr.com";                    //Set the SMTP server to send through
//                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
//                $mail->Username = "unisons@smrhr.com";
//                $mail->Password = '}uE9A]1eD_Qc';                       //SMTP password
//                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
//                //   $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
//
//                //Recipients
//                $mail->setFrom("unisons@smrhr.com", 'Reset Password Request');
//                $mail->addAddress($username, 'Reset Password Request');     //Add a recipient
//                //Name is optional
//
//                $messages='Your password was successfully resetted, Sign in with your new generated password '.$password;
//                //$mail->addAddress($cnic->value('professional_email'), 'Payslip');
//                $mail->addReplyTo("unisons@smrhr.com", 'Information');
//
//                //Content
//                $mail->isHTML(true);                                  //Set email format to HTML
//                $mail->Subject = 'Reset Password Request';
//                $mail->Body    = $messages;
//                //$mail->Body = view('Hr.mail',compact('details'))->render();
//                // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
//                // echo("saad")
//
//                $mail->send();
//
//                DB::table('users')->where([['username', '=', $username]])->update(
//                    array(
//                        'password'=> Hash::make($password)
//                    )
//                );
//
//                return json_encode(['message' => 'Check your email', 'status' => true ]);
//                //echo 'Message has been sent';
//            } catch (Exception $e) {
//                return json_encode(['message' => 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}', 'status' => false ]);
//                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//            }
//
//        }else{
//            return json_encode(['message' => 'Username does not exist!', 'status' => false ]);
//        }
//
//    }
//
//    public function insertAutoGeneratedLeaves($username,$no_of_days,$leave_type,$emp_id,$leaves_policy_id,$excel_month,$excel_year,$lates,$half_days_count,$company_id){
//
//        if($leave_type != ""){
//
//            $leaveApplicationData['emp_id']          = $emp_id;
//            $leaveApplicationData['leave_policy_id'] = $leaves_policy_id;
//            $leaveApplicationData['company_id']      = $company_id;
//            $leaveApplicationData['leave_type']      = $leave_type;
//            $leaveApplicationData['leave_day_type']  = 1;
//            $leaveApplicationData['name']  = $excel_year.'-'.$excel_month.'-01';
//            $leaveApplicationData['reason']          = '-';
//            $leaveApplicationData['leave_address']   = '-';
//            $leaveApplicationData['approval_status'] = 2;
//            $leaveApplicationData['approved'] = 2;
//            $leaveApplicationData['status']          = 1;
//            $leaveApplicationData['username']        = $username;
//            $leaveApplicationData['date']            = date("Y-m-d");
//            $leaveApplicationData['time']            = date("H:i:s");
//
//            $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);
//
//            $maternityLeavesData['emp_id']               = $emp_id;
//            $maternityLeavesData['leave_application_id'] = $leave_application_id;
//            $maternityLeavesData['leave_policy_id'] = $leaves_policy_id;
//            $maternityLeavesData['leave_type']           = $leave_type;
//            $maternityLeavesData['leave_day_type']       = 1;
//            $maternityLeavesData['no_of_days']           = $no_of_days;
//            $maternityLeavesData['from_date']            = $excel_year.'-'.$excel_month;
//            $maternityLeavesData['to_date']              = $excel_year.'-'.$excel_month;
//
//            //                $startDate = new DateTime(Input::get('from_date'));
//            //                $endDate = new DateTime(Input::get('to_date'));
//            //                $difference = $endDate->diff($startDate);
//
//            //                $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;
//
//            $maternityLeavesData['status']               = 1;
//            $maternityLeavesData['username']             = $username;
//            $maternityLeavesData['date']                 = date("Y-m-d");
//            $maternityLeavesData['time']                 = date("H:i:s");
//
//            DB::table('leave_application_data')->insert($maternityLeavesData);
//        }
//
//    }
//
//    public function deleteAttendance()
//    {
//        CommonHelper::companyDatabaseConnection(12);
//        DB::table('attendance')->where([['status', '=', 1], ['emp_id', '=', 101]])->delete();
//        CommonHelper::reconnectMasterDatabase();
//        return json_encode(['message' => 'Attendance deleted successfully', 'status' => true, 'data' => []]);
//    }
//
//
//    public function addAutomatedLeaves($emp_id,$month,$year,$username){
//        CommonHelper::companyDatabaseConnection('12');
//        $month_start_date=$year."-".$month."-01";
//        $month_end_date=$year."-".$month."-31";
//        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$month_start_date,$month_end_date])->where([['status','=',1]]);
//        $totalHolidays = $get_holidays->get()->toArray();
//
//        //leaves start
//
//        $employees=DB::table('employee')->select('emp_id')->where('emp_id',$emp_id)->where('status',1)->get();
//
//        foreach($employees as $value){
//            CommonHelper::companyDatabaseConnection('12');
//
//            $monthly_holidays = array();
//
//            $emp=DB::table('employee')->select('working_hours_policy_id')->where([['emp_id','=',$value->emp_id]])->first();
//            $emp_working_hours_policy_id=$emp->working_hours_policy_id;
//
//            $allow_annual_leaves=DB::table('employee')->select('annual_leaves')->where([['emp_id','=',$value->emp_id]])->value('annual_leaves');
//
//            $days_array = [];
//            $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$value->emp_id]])->value('day_off');
//            $day_off_emp =  explode('=>',$day_off_emp);
//            foreach($day_off_emp as $value2){
//                if($value2 != ''){
//                    $days_array[] = $value2;
//                }
//            }
//
//            CommonHelper::reconnectMasterDatabase();
//            $working_hours_policy=WorkingHoursPolicy::where('id',$emp_working_hours_policy_id)->where('status',1)->first();
//            $startTime = $working_hours_policy->start_working_hours_time;
//            $endTime = strtotime("+".$working_hours_policy->working_hours_grace_time."minutes", strtotime($startTime));
//            $half_day_time=strtotime("+".$working_hours_policy->half_day_time."minutes", strtotime($startTime));
//            $finishTime = $working_hours_policy->end_working_hours_time;
//            $dutyEndTime = date("H:i", strtotime($finishTime));
//
//            CommonHelper::companyDatabaseConnection('12');
//
//            $total_days_off = Attendance::select('attendance_date')
//                ->whereBetween('attendance_date',[$month_start_date,$month_end_date])
//                ->whereIn('day',$days_array)
//                ->where('emp_id','=',$value->emp_id)->get()->toArray();
//
//            $monthly_holidays = array_merge($totalHolidays,$total_days_off);
//
//
//            $half_days_absent=date('h:i', $half_day_time);
//            $end_day_time=date('h:i', $endTime);
//
//            $lates_count=DB::table('attendance')->where([['month','=',$month],['year','=',$year],
//                ['clock_in','>',$end_day_time],['clock_in','<',$half_days_absent],['emp_id','=',$value->emp_id]])
//                ->whereNotIn('attendance_date', $monthly_holidays)->count();
//
//            $half_days_count=DB::table('attendance')->where([['month','=',$month],['year','=',$year],
//                ['clock_in','>=',$half_days_absent],['emp_id','=',$value->emp_id]])
//                ->whereNotIn('attendance_date', $monthly_holidays)
//                ->orWhere(function($nest) use($monthly_holidays,$value,$month,$year,$dutyEndTime) {
//                    $nest->where([['month','=',$month],['year','=',$year],
//                        ['clock_in','!=',''],['clock_out','<',$dutyEndTime],['emp_id','=',$value->emp_id]])
//                        ->whereNotIn('attendance_date', $monthly_holidays);
//                })
//                ->count();
//
//            $leave_type='';
//            $lates=$lates_count;
//            $leaves_policy_id=DB::table('employee')->select('leaves_policy_id')->where([['emp_id','=',$value->emp_id],['status','!=',2]])->value('leaves_policy_id');
//
//            CommonHelper::reconnectMasterDatabase();
//            $late_leave = DB::table('leave_application')->where([['emp_id','=',$value->emp_id],['name','=',$year.'-'.$month.'-01']]);
//
//            if($late_leave->count() > 0){
//                DB::table('leave_application_data')->where([['emp_id','=',$value->emp_id],['leave_application_id','=',$late_leave->value('id')]])->delete();
//                DB::table('leave_application')->where([['emp_id','=',$value->emp_id],['name','=',$year.'-'.$month.'-01']])->delete();
//            }
//
//            if(($lates>=5 || $half_days_count > 0) && ($leaves_policy_id != '' && $leaves_policy_id != 0)){
//
//                $leaves_policy = DB::table('leaves_policy')
//                    ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//                    ->select('leaves_policy.*', 'leaves_data.*')
//                    ->where([['leaves_policy.id', '=', $leaves_policy_id]])
//                    ->get();
//                $remaining_annual=0;
//                $remaining_casual=0;
//                $remaining_sick=0;
//                $remaining_maternal=0;
//                $remaining_paternal=0;
//                foreach($leaves_policy as $val){
//                    if($val->leave_type_id == 1):
//                        $annual_leaves= $val->no_of_leaves;
//                    elseif($val->leave_type_id == 3):
//                        $casual_leaves = $val->no_of_leaves;
//                    else:
//                        $sick_leaves = $val->no_of_leaves;
//                    endif;
//
//                    // $getUsedLeaves =DB::table('leave_application_data')
//                    //     ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
//                    //     ->where([['leave_application.emp_id','=',$value->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
//                    //         ['leave_application.status', '=', '1'],
//                    //         ['leave_application.approval_status_lm', '=', '2'],
//                    //         ['leave_application.approval_status', '=', '2']])
//                    //     ->sum('no_of_days');
//
//                    $getUsedLeaves= DB::select('select sum(no_of_days) as no_of_days from leave_application_data
//                        INNER JOIN leave_application on leave_application.id = leave_application_data.leave_application_id
//                        WHERE leave_application_data.emp_id = '.$value->emp_id.' AND leave_application.status = 1 AND (leave_application.approval_status = 2 OR leave_application.approval_status_lm = 2) AND
//                          leave_application.leave_type = '.$val->leave_type_id.' ');
//
//                    $getUsedLeaves=$getUsedLeaves[0]->no_of_days;
//
//                    if($val->leave_type_id == 1):
//                        $remaining_annual = $val->no_of_leaves-$getUsedLeaves;
//                    elseif($val->leave_type_id == 3):
//                        $remaining_casual=$val->no_of_leaves -$getUsedLeaves;
//                    elseif($val->leave_type_id == 4):
//                        $remaining_sick=$val->no_of_leaves -$getUsedLeaves;
//                    elseif($val->leave_type_id == 5):
//                        $remaining_maternal=$val->no_of_leaves -$getUsedLeaves;
//                    elseif($val->leave_type_id == 6):
//                        $remaining_paternal=$val->no_of_leaves -$getUsedLeaves;
//                    endif;
//
//                }
//
//
//                $remaining_lates=(floor($lates/5)+ ($half_days_count/2) );
//                $remaining=$remaining_lates;
//                if($remaining_casual > 0){
//                    if($remaining_casual >= $remaining){
//                        $leave_type=3;
//                        $this->insertAutoGeneratedLeaves($username,$remaining,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                    }
//                    else{
//                        $remaining=$remaining-$remaining_casual;
//                        $leave_type='3';
//                        $this->insertAutoGeneratedLeaves($username,$remaining_casual,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//
//                        if($remaining_sick > 0){
//                            if($remaining_sick >= $remaining){
//                                $leave_type='4';
//                                $this->insertAutoGeneratedLeaves($username,$remaining,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                            }
//                            else{
//                                $remaining=$remaining-$remaining_sick;
//                                $leave_type='4';
//                                $this->insertAutoGeneratedLeaves($username,$remaining_sick,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//
//                                if($remaining_annual > 0 && $allow_annual_leaves == 1){
//                                    if($remaining_annual >= $remaining){
//                                        $leave_type='1';
//                                        $this->insertAutoGeneratedLeaves($username,$remaining,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                                    }
//                                    else{
//                                        $leave_type='1';
//                                        $this->insertAutoGeneratedLeaves($username,$remaining_annual,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                                    }
//                                }
//
//                            }
//                        }
//                        else{
//                            if($remaining_annual > 0  && $allow_annual_leaves == 1){
//                                if($remaining_annual >= $remaining){
//                                    $leave_type='1';
//                                    $this->insertAutoGeneratedLeaves($username,$remaining,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                                }
//                                else{
//                                    $leave_type='1';
//                                    $this->insertAutoGeneratedLeaves($username,$remaining_annual,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                                }
//                            }
//                        }
//                    }
//                }
//                elseif($remaining_sick > 0){
//                    if($remaining_sick >= $remaining){
//                        $leave_type=4;
//                        $this->insertAutoGeneratedLeaves($username,$remaining,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                    }
//                    else{
//                        $remaining=$remaining-$remaining_sick;
//                        $leave_type='4';
//                        $this->insertAutoGeneratedLeaves($username,$remaining_sick,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//
//                        if($remaining_annual > 0  && $allow_annual_leaves == 1){
//                            if($remaining_annual >= $remaining){
//                                $leave_type='1';
//                                $this->insertAutoGeneratedLeaves($username,$remaining,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                            }
//                            else{
//                                $leave_type='1';
//                                $this->insertAutoGeneratedLeaves($username,$remaining_annual,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                            }
//                        }
//
//                    }
//                }
//                elseif($remaining_annual > 0  && $allow_annual_leaves == 1){
//                    if($remaining_annual >= $remaining){
//                        $leave_type='1';
//                        $this->insertAutoGeneratedLeaves($username,$remaining,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                    }
//                    else{
//                        $leave_type='1';
//                        $this->insertAutoGeneratedLeaves($username,$remaining_annual,$leave_type,$value->emp_id,$leaves_policy_id,$month,$year,$lates,$half_days_count,'12');
//                    }
//                }
//            }
//        }
//    }
//
//    public function getUserData()
//    {
//        $emp_id = $_GET['emp_id'];
//        //$device_id = $_GET['device_id'];
//        $designation_name = '';
//        $department_name = '';
//        $sub_department_name = '';
//        $user = DB::table('users')->where([['emp_id', '=', $emp_id]]);
//        if ($user->count() > 0) {
//            CommonHelper::companyDatabaseConnection($user->value('company_id'));
//            $employee = DB::table('employee')
//                ->select('id', 'emp_id', 'emp_name', 'emp_father_name', 'allow_app_attendance', 'designation_id', 'emp_sub_department_id', 'emp_department_id', 'img_path', 'emp_joining_date', 'emp_contact_no', 'leaves_policy_id', 'working_hours_policy_id', 'emp_date_of_birth')
//                ->where([['status', '=', 1], ['emp_id', '=', $emp_id]]);
//
//
//            if ($employee->count() > 0) {
//
//                $roundedNetSalarySum = Payslip::where('emp_id', $emp_id)
//                    ->where('status', 1)
//                    ->sum('net_salary');
//
//                $data['total_earnings'] = round($roundedNetSalarySum);
//
//
//                $employee = $employee->first();
//                $data['emp_id'] = $employee->emp_id;
//                CommonHelper::reconnectMasterDatabase();
//                if ($employee->designation_id != '') {
//                    $designation_name = DB::table('designation')->select('designation_name')->where('id', $employee->designation_id)->where('status', 1)->value('designation_name');
//                } else {
//                    $designation_name = $employee->designation_id;
//                }
//                if ($employee->emp_department_id != '') {
//
//                    $department_name = DB::table('department')->select('department_name')->where('id', $employee->emp_department_id)->where('status', 1)->value('department_name');
//                } else {
//                    $department_name = $employee->emp_department_id;
//                }
//                if ($employee->emp_sub_department_id != '') {
//
//                    $sub_department_name = DB::table('sub_department')->select('sub_department_name')->where('id', $employee->emp_sub_department_id)->where('status', 1)->value('sub_department_name');
//                } else {
//                    $sub_department_name = $employee->emp_sub_department_id;
//                }
//
//                $data['company_id'] = $user->value('company_id');
//                //$data['api_token'] = $user->value('api_token');
//                $data['emp_name'] = $employee->emp_name;
//                $data['password_status'] = $user->value('password_status');
//                $data['emp_father_name'] = $employee->emp_father_name;
//                $data['emp_contact_no'] = $employee->emp_contact_no;
//                $data['designation'] = $designation_name;
//                $data['department_name'] = $department_name;
//                $data['allow_app_attendance'] = $employee->allow_app_attendance;
//                $data['sub_department_name'] = $sub_department_name;
//                $data['leaves_policy_id'] = $employee->leaves_policy_id;
//
//                CommonHelper::companyDatabaseConnection(12);
//                $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
//                    ->where('status',1)->where('employee_id',$emp_id)->where('from_date','=',date('Y-m-d'))->where('to_date','=',date('Y-m-d'));
//
//                if($working_hours_policy_id->count() > 0){
//                    $working_hours_policy_id=$working_hours_policy_id->orderBy('id','desc')->value('working_hours_policy_id');
//                }else{
//                    $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
//                        ->where('status',1)->where('employee_id',$emp_id)->where('from_date','<=',date('Y-m-d'))->where('to_date','>=',date('Y-m-d'));
//
//                    if($working_hours_policy_id->count() > 0){
//                        $working_hours_policy_id=$working_hours_policy_id->orderBy('id','desc')->value('working_hours_policy_id');
//                    }else{
//                        $working_hours_policy_id=$employee->working_hours_policy_id;
//                    }
//
//                }
//                CommonHelper::reconnectMasterDatabase();
//
//
//                if ($working_hours_policy_id != '' && $working_hours_policy_id != 0) {
//                    $working_hours_time = DB::table('working_hours_policy')->select('start_working_hours_time', 'end_working_hours_time')->where('id', $working_hours_policy_id)->where('status', 1)->first();
//                    $data['start_working_hours_time'] = $working_hours_time->start_working_hours_time;
//                    $data['end_working_hours_time'] = $working_hours_time->end_working_hours_time;
//                }
//                if ($employee->emp_joining_date != '') {
//                    $data['emp_joining_date'] = date("d-M-Y", strtotime($employee->emp_joining_date));
//                } else {
//                    $data['emp_joining_date'] = '';
//                }
//                if ($employee->emp_date_of_birth != '') {
//                    $data['emp_date_of_birth'] = date("d-M-Y", strtotime($employee->emp_date_of_birth));
//                } else {
//                    $data['emp_date_of_birth'] = '';
//                }
//
//                if ($employee->img_path != '') {
//                    $data['img_path'] = env('IMAGE_URL') . $employee->img_path;
//                } else {
//                    $data['img_path'] = '';
//                }
//                return json_encode(['message' => 'Login Successful', 'status' => true, 'data' => $data]);
//
//            } else {
//                return json_encode(['message' => 'Employee does not exist', 'status' => false]);
//            }
//        }
//
//    }
//
//
   public function leavesBalance()
   {

       $employee_id = request()->get('employee_id');

       CommonHelper::companyDatabaseConnection(12);
            $leaves_policy_id = DB::table('employee')->select('leaves_policy_id')->where([['status', '=', 1], ['id', '=', $employee_id]])->value('leaves_policy_id');
       CommonHelper::reconnectMasterDatabase();

       $getUsedAnnual= DB::table('leave_application')
           ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
           ->where('leave_application.employee_id','=',$employee_id)
           ->where('leave_application.view','=','yes')
           ->where('leave_application.status','=','1')
           ->where('leave_application.leave_type','=','1')
           ->where('leave_application.approval_status','!=','3')
           ->where('leave_application.approval_status_lm','!=','3')
           ->sum('leave_application_data.no_of_days');
       $getUsedCasual= DB::table('leave_application')
           ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
           ->where('leave_application.employee_id','=',$employee_id)
           ->where('leave_application.view','=','yes')
           ->where('leave_application.status','=','1')
           ->where('leave_application.leave_type','=','3')
           ->where('leave_application.approval_status','!=','3')
           ->where('leave_application.approval_status_lm','!=','3')
           ->sum('leave_application_data.no_of_days');


       $getUsedSick= DB::table('leave_application')
           ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
           ->where('leave_application.employee_id','=',$employee_id)
           ->where('leave_application.view','=','yes')
           ->where('leave_application.status','=','1')
           ->where('leave_application.leave_type','=','2')
           ->where('leave_application.approval_status','!=','3')
           ->where('leave_application.approval_status_lm','!=','3')
           ->sum('leave_application_data.no_of_days');


       $annual_leaves = DB::table("leaves_data")
           ->where([['leaves_policy_id', '=', $leaves_policy_id],['leave_type_id','=',1]])
           ->sum('no_of_leaves');
       $casual_leaves = DB::table("leaves_data")
           ->where([['leaves_policy_id', '=', $leaves_policy_id],['leave_type_id','=',3]])
           ->sum('no_of_leaves');
       $sick_leaves = DB::table("leaves_data")
           ->where([['leaves_policy_id', '=', $leaves_policy_id],['leave_type_id','=',2]])
           ->sum('no_of_leaves');

       $data['total_annual'] = ($annual_leaves);
       $data['total_casual'] = ($casual_leaves);
       $data['total_sick'] = ($sick_leaves);
       $data['used_annual'] = ($getUsedAnnual);
       $data['used_casual'] = ($getUsedCasual);
       $data['used_sick'] = ($getUsedSick);
       //$data['allow_annual'] =$allow_annual;
       return json_encode(['message' => 'Leaves Balance fetched successfully', 'status' => true, 'data' => $data]);

   }

   public function fetchReasons(){
    $data=[];
    $reasons=DB::table('reasons')->where('status',1)->get();
    foreach($reasons as $r){
        $data[]=$r->reason;
    }
    return json_encode(['message' => 'Reasons fetched successfully', 'status' => true, 'data' => $data]);
    
}
public function fetchlocations(){
    
    $locations=DB::table('locations')->select('id','location_name','latitude','longitude')->where('status',1)->get();
    
    return json_encode(['message' => 'Reasons fetched successfully', 'status' => true, 'data' => $locations]);
    
}

//    public function viewAttendanceReport(){
//
//        $day_off_array = [];
//        $status='';
//        //$explodeMonthYear = explode("-",request()->month_year);
//        $employee_id = request()->emp_id;
//
//
//        $from_date = request()->from_date;
//        $to_date = request()->to_date;
//
//        $explodeMonthYear = explode("-",$from_date);
//
//        CommonHelper::companyDatabaseConnection('12');
//
//        $employee = DB::table('employee')->select('emp_name','emp_sub_department_id','emp_id','working_hours_policy_id')->where([['emp_id','=',$employee_id]])->first();
//        $day_off_emp =Employee::select('day_off')->where([['emp_id','=',$employee_id]])->value('day_off');
//
//        $dayoff = explode("=>",$day_off_emp);
//
//        $total_days_off = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])->whereIn('day',[$dayoff[0],$dayoff[1]])->where('emp_id','=',$employee_id);
//
//
//        if($total_days_off->count() > 0):
//
//            foreach($total_days_off->get()->toArray() as $offDates):
//                $totalOffDates[] = $offDates['attendance_date'];
//            endforeach;
//
//        else:
//            $totalOffDates =array();
//        endif;
//        $get_holidays = Holidays::select('holiday_date')->where([['status','=',1],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]]);
//        if($get_holidays->count() > 0):
//            foreach($get_holidays->get() as $value2):
//
//                $monthly_holidays[]=$value2['holiday_date'];
//                $public_holidays[]=$value2['holiday_date'];
//            endforeach;
//
//        else:
//            $monthly_holidays =array();
//            $public_holidays = array();
//        endif;
//
//        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
//
//        CommonHelper::reconnectMasterDatabase();
//        $duty_time =  CommonHelper::getMasterTableValueById('12','working_hours_policy','start_working_hours_time',$employee->working_hours_policy_id);
//
//        $duty_end_time =  CommonHelper::getMasterTableValueById('12','working_hours_policy','end_working_hours_time',$employee->working_hours_policy_id);
//
//
//        $LikeDate = "'".'%'.$explodeMonthYear[0]."-".$explodeMonthYear[1].'%'."'";
//
//
//        $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date from leave_application
//        INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
//        WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$employee_id.' AND leave_application.status = 1 AND (leave_application.approval_status = 2 OR leave_application.approval_status_lm = 2) AND
//            leave_application.view = "yes"
//        OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.emp_id = '.$employee_id.'');
//
//
//        $dates=[];
//        $leaves_from_dates2 = [];
//        if(!empty($leave_application_request_list)):
//            foreach($leave_application_request_list as $value3):
//                $leaves_from_dates = $value3->from_date;
//                $leaves_to_dates = $value3->to_date;
//                $leaves_type=$value3->leave_type;
//                $leaves_from_dates2[] = $value3->from_date;
//
//                $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));
//
//                foreach ($period as $date) {
//                    $dates[] = $date->format("Y-m-d");
//                }
//                //$leave_type_name = HrHelper::getMasterTableValueById('1','leave_type','leave_type_name',$value3->leave_type);
//            endforeach;
//
//        endif;
//        $monthly_holidays_absents = array_merge($monthly_holidays,$dates);
//        //print_r($monthly_holidays_absents);
//
//
//
//
//        CommonHelper::companyDatabaseConnection('12');
//        $total_absent_holidays = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$employee_id],['clock_in','=',''],['clock_out','=','']])
//            ->whereNotIn('attendance_date', $monthly_holidays_absents)
//            ->get()->toArray();
//
//
//
//
//
//
//
//        $status='';
//        $emp_attendance=DB::table('attendance')
//            ->join('employee','employee.emp_id','=','attendance.emp_id')
//            ->select('attendance.attendance_date','attendance.clock_in','attendance.clock_out','employee.emp_id','employee.emp_name')
//            ->whereBetween('attendance.attendance_date',[$from_date,$to_date])
//            ->where('employee.emp_id','=',$employee_id)->where('attendance.status','=',1)
//            ->get();
//        $attendance_dates=[];
//        foreach($emp_attendance as $key => $value){
//
//            CommonHelper::companyDatabaseConnection(12);
//            $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
//                ->where('status',1)->where('employee_id',$value->emp_id)->where('from_date','=',$value->attendance_date)->where('to_date','=',$value->attendance_date);
//
//            if($working_hours_policy_id->count() > 0){
//                $working_hours_policy_id=$working_hours_policy_id->orderBy('id','desc')->value('working_hours_policy_id');
//            }else{
//                $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
//                    ->where('status',1)->where('employee_id',$value->emp_id)->where('from_date','<=',$value->attendance_date)->where('to_date','>=',$value->attendance_date);
//
//                if($working_hours_policy_id->count() > 0){
//                    $working_hours_policy_id=$working_hours_policy_id->orderBy('id','desc')->value('working_hours_policy_id');
//                }else{
//                    $working_hours_policy_id=$employee->working_hours_policy_id;
//                }
//
//            }
//            CommonHelper::reconnectMasterDatabase();
//
//            $working_hours_policy=DB::table('working_hours_policy')->where('id',$working_hours_policy_id)->where('status',1)->first();
//            $startTime = $working_hours_policy->start_working_hours_time;
//            $finishTime = $working_hours_policy->end_working_hours_time;
//            $dutyEndTime = date("H:i", strtotime($finishTime));
//            $endTime = strtotime("+".$working_hours_policy->working_hours_grace_time."minutes", strtotime($startTime));
//            $half_day_time=strtotime("+".$working_hours_policy->half_day_time."minutes", strtotime($startTime));
//            $half_days_absent=date('h:i', $half_day_time);
//            $end_day_time=date('h:i', $endTime);
//
//            CommonHelper::companyDatabaseConnection(12);
//            $half_days=DB::table('attendance')->select('attendance_date')->where([['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]],
//                ['clock_in','>=',$half_days_absent],['attendance_date','=',$value->attendance_date],['emp_id','=',$employee_id]])
//                ->whereNotIn('attendance_date', $monthly_holidays);
//
//
//
//
//            $totalHalfDaysDates =array();
//            if($half_days->count() > 0):
//
//                foreach($half_days->get() as $day):
//                    $totalHalfDaysDates[] = $day->attendance_date;
//                endforeach;
//
//            else:
//                $totalHalfDaysDates =array();
//            endif;
//
//
//            $half_2nd_days=DB::table('attendance')->select('attendance_date')->where([['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]],
//                ['clock_out','<',$dutyEndTime],['attendance_date','=',$value->attendance_date],['clock_in','!=',''],['emp_id','=',$employee_id]])
//                ->whereNotIn('attendance_date', $monthly_holidays)
//                ->whereNotIn('attendance_date', $totalHalfDaysDates);
//            // $dutyEndTime
//
//            $total2ndHalfDaysDates =array();
//            if($half_2nd_days->count() > 0):
//
//                foreach($half_2nd_days->get() as $day):
//                    $total2ndHalfDaysDates[] = $day->attendance_date;
//                endforeach;
//
//            else:
//                $total2ndHalfDaysDates =array();
//            endif;
//
//
//
//
//            $status='';
//
//            if(in_array($value->attendance_date, $monthly_holidays)){
//                $status='Holiday';
//            }else if(in_array($value->attendance_date,$leaves_from_dates2)){
//                $status = 'Leave';
//            }
//            else if(in_array($value->attendance_date,$totalHalfDaysDates) || in_array($value->attendance_date,$total2ndHalfDaysDates)){
//
//                $status = 'Half Day';
//
//
//            }
//            else if(!in_array($value->attendance_date, $monthly_holidays)){
//                if(!in_array($value->attendance_date,$totalHalfDaysDates) && !in_array($value->attendance_date,$total2ndHalfDaysDates)){
//                    if($value->clock_in != ''){
//                        $time1 = strtotime($value->clock_in);
//                        if($time1 > $endTime){
//                            $status = 'Late';
//                        }
//                    }
//                }
//            }
//            else if($value->clock_in != '' || $value->clock_out != ''){
//
//                $status = 'Present';
//
//            }
//
//            else if(in_array($value->attendance_date,$dates)){
//                $status = 'Leave';
//            }
//            else{
//                $status = 'Absent';
//            }
//
//            $new_input = array(
//                'emp_id' => $value->emp_id,
//                'emp_name' => $value->emp_name,
//                'attendance_date' => $value->attendance_date,
//                'clock_in' => $value->clock_in,
//                'clock_out' => $value->clock_out,
//                'status' => $status,
//            );
//            array_push($attendance_dates, $new_input);
//        }
//        return json_encode(['message' => 'Employee Attendance', 'status' => true, 'data' => $attendance_dates]);
//    }


    public function changePassword()
    {
        $emp_id = $_GET['emp_id'];
        $password = Hash::make($_GET['password']);

        $data['password'] = $password;
        $data['password_status'] = 1;
        $data['updated_at'] = date("Y-m-d");

        $user = DB::table('users')->where([['emp_id', '=', $emp_id], ['status', '=', 1]])->update($data);
        if ($user):
            return json_encode(['message' => 'Password changed successfully', 'status' => true, 'data' => []]);
        else:
            return json_encode(['message' => 'Something went wrong', 'status' => false, 'data' => []]);
        endif;
    }


    public function leaveType()
    {
        $data = leaveType::select('id','leave_type_name')->where('status', 1)->where('id','<=', 3)->get();
        return json_encode(['data' => $data ]);
    }

    public function addLeave()
    {
        $employee_id = request()->employee_id;
        CommonHelper::companyDatabaseConnection('12');
		   $leaves_policy_id =DB::table('employee')->select('leaves_policy_id')->where('id',$employee_id)->where('status',1)->value('leaves_policy_id');
        CommonHelper::reconnectMasterDatabase();
        $leave_type_id = request()->leave_type_id;
        $date_from = request()->date_from;
        $date_to = request()->date_to;
        $reason = request()->reason;
        $email = request()->email;
        $leaveAddress = request()->leave_address;
        $from_date = strtotime($date_from);
        $to_date = strtotime($date_to);
        $datediff = $to_date - $from_date;

        if ($date_from > $date_to) {
            return json_encode(['message' => 'To date can not be less than from date']);
        }

        $total_Days = round($datediff / (60 * 60 * 24)) + 1;

        $LeaveCheck = apiLeaveHelper::totalAppliedAndApprovedLeave($employee_id, $leave_type_id, $date_from, $date_to, $total_Days);
     
        if ($LeaveCheck['message']=='success') {
            
            try {
                
                $leaveApplicationData['employee_id'] = $employee_id;
                $leaveApplicationData['leave_policy_id'] = $LeaveCheck['data'];
                $leaveApplicationData['company_id'] = 12;
                $leaveApplicationData['leave_type'] = $leave_type_id;
                $leaveApplicationData['leave_day_type'] = 1;
                $leaveApplicationData['reason'] = $reason;
                $leaveApplicationData['leave_address'] = $leaveAddress;
                $leaveApplicationData['approval_status'] = 1; // 1 pending 2 approve 3 reject
                $leaveApplicationData['approval_status_lm'] = 1; // 1 pending 2 approve 3 reject
                $leaveApplicationData['approved'] = 1;
                $leaveApplicationData['status'] = 1;
                $leaveApplicationData['username'] = $email;
                $leaveApplicationData['date'] = date("Y-m-d");
                $leaveApplicationData['time'] = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

            } catch (\Exception $e) {

                return json_encode(['message' => $e->getMessage(), 'status' => false]);
            }

            if ($leave_application_id) {

                try {

       
                    $leavedata['employee_id'] = $employee_id;
                    $leavedata['leave_application_id'] = $leave_application_id;
                    $leavedata['leave_policy_id'] = $LeaveCheck['data'];
                    // $leavedata['leave_type'] = $leave_type_id;
                    // $leavedata['leave_day_type'] = 1;
                    $leavedata['no_of_days'] = $total_Days;
                    $leavedata['from_date'] = $date_from;
                    $leavedata['to_date'] = $date_to;
                    $leavedata['status'] = 1;
                    $leavedata['username'] = $email;
                    $leavedata['date'] = date("Y-m-d");
                    $leavedata['time'] = date("H:i:s");

                    $leave_application_data = DB::table('leave_application_data')->insert($leavedata);
                } catch (\Exception $e) {

                    return json_encode(['message' => $e->getMessage(), 'status' => false]);

                }

                if ($leave_application_data) {
                    
                    CommonHelper::companyDatabaseConnection(12);
            
                    $request_email_queue['employee_id']=$employee_id;
                    $request_email_queue['record_id']=$leave_application_id;
                    $request_email_queue['leave_type']=$leave_type_id;
                    $request_email_queue['leave_day_type']=1;
                    $request_email_queue['no_of_days']=$total_Days;
                    
                    $request_email_queue['from_date']=$date_from;
                    $request_email_queue['to_date']=$date_to;
                    
                    
                    $request_email_queue['type'] = 'leave';
                    $request_email_queue['sent_to'] = 'hr';
                    $request_email_queue['status'] = 1;
                    $request_email_queue['username'] = $email;
                    $request_email_queue['date'] = date("Y-m-d");
                    $request_email_queue['time'] = date("H:i:s");
                    
                    $exists=DB::Table('request_email_queue')->where('employee_id',$employee_id)->where('record_id',$leave_application_id);
                    if($exists->count()>0){
                        DB::Table('request_email_queue')->where('employee_id',$employee_id)->where('record_id',$leave_application_id)->update($request_email_queue); 
                    }else{
                        DB::Table('request_email_queue')->insert($request_email_queue); 
                    } 
                    
                    CommonHelper::reconnectMasterDatabase();
                    
                    
                    return json_encode(['message' => 'Data successfully inserted', 'status' => true]);

                } else {
                    return json_encode(['message' => 'something went wrong in leave application data', 'status' => false]);

                }
            } else {
                return json_encode(['message' => 'something went wrong in leave application', 'status' => false]);

            }

        } else {
            return json_encode(['message' => $LeaveCheck['message'], 'status' => false]);

        }

    }


    public function viewLeaveApplicationRequestList(){
        $employee_id = request()->employee_id;
       
        CommonHelper::companyDatabaseConnection(12);
        
        $team_lead_exists = DB::table('employee')
           ->where([['reporting_manager_id','=',$employee_id],['status','=',1]]);
        CommonHelper::reconnectMasterDatabase();
        
        $team_members=[];
        if($team_lead_exists->exists()){
            foreach($team_lead_exists->get() as $value){
                $team_members[]=$value->id;
            }
        }   
        
        $leave_application_request_list_mine = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
            ->select('leave_application.*','leave_application_data.no_of_days','leave_application_data.from_date','leave_application_data.to_date','leave_application_data.first_second_half_date','leave_application.approval_status','leave_application.approval_status_lm')
            ->where('leave_application.employee_id','=',$employee_id)
            ->where('leave_application.view','=','yes')
            ->where('leave_application.status','=','1')
            ->orderBy('leave_application.approval_status')
            ->orderBy('leave_application_data.from_date','desc')
            ;
        
        $leave_application_request_list_team = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
            ->select('leave_application.*','leave_application_data.no_of_days','leave_application_data.from_date','leave_application_data.to_date','leave_application_data.first_second_half_date')
            ->whereIn('leave_application.employee_id',$team_members)
            ->where('leave_application.view','=','yes')
             ->where('leave_application.status','=','1')
             ->where('leave_application.approval_status',2)
             ->orderBy('leave_application.approval_status')
            ->orderBy('leave_application_data.from_date','desc')
            ;
        
        $mineLeaves=[];
        if($leave_application_request_list_mine->count()>0){
            foreach($leave_application_request_list_mine->paginate(15) as $value){
                
                $approval_status='';
                $approval_status_lm='';
                CommonHelper::companyDatabaseConnection(12);
                $employee = DB::table('employee')->select('emp_id','emp_name')->where([['status', '=', 1], ['id', '=', $value->employee_id]])->first();
                CommonHelper::reconnectMasterDatabase();
                if($value->leave_day_type==1){
                   $from_date=HrHelper::date_format($value->from_date);
                   $to_date=HrHelper::date_format($value->to_date);
                   $leave_day_type='Full Day';
                }else{
                    $from_date=HrHelper::date_format($value->first_second_half_date);
                    $to_date=HrHelper::date_format($value->first_second_half_date);
                    $leave_day_type='Half Day';
                }
                if($value->leave_type==1){
                    $leave_type='Annual';
                }elseif($value->leave_type==2){
                    $leave_type='Sick';
                }elseif($value->leave_type==3){
                    $leave_type='Casual';
                }
                if($value->approval_status==2){
                   $approval_status="Approved"; 
                }else if($value->approval_status==1){
                   $approval_status="Pending"; 
                }else if($value->approval_status==3){
                   $approval_status="Rejected"; 
                }
                if($value->approval_status_lm==2){
                   $approval_status_lm="Approved"; 
                }else if($value->approval_status_lm==1){
                   $approval_status_lm="Pending"; 
                }else if($value->approval_status_lm==3){
                   $approval_status_lm="Rejected"; 
                }
                $new_input = array(
                    'id' => $value->id,
                    'emp_id'  => $employee->emp_id, 
                    'emp_name' => $employee->emp_name, 
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'no_of_days' => $value->no_of_days,
                    'leave_day_type' => $leave_day_type,
                    'leave_type' => $leave_type,
                    'approval_status' => $approval_status,
                    'approval_status_lm' => $approval_status_lm
                   
                );
                array_push($mineLeaves, $new_input);
                
            }
        }
        
        
        $teamLeaves=[];
        if($leave_application_request_list_team->count()>0){
            foreach($leave_application_request_list_team->paginate(15) as $value){
                $approval_status='';
                $approval_status_lm='';
                CommonHelper::companyDatabaseConnection(12);
                $employee = DB::table('employee')->select('emp_id','emp_name')->where([['status', '=', 1], ['id', '=', $value->employee_id]])->first();
                CommonHelper::reconnectMasterDatabase();
                if($value->leave_day_type==1){
                   $from_date=HrHelper::date_format($value->from_date);
                   $to_date=HrHelper::date_format($value->to_date);
                   $leave_day_type='Full Day';
                }else{
                    $from_date=HrHelper::date_format($value->first_second_half_date);
                    $to_date=HrHelper::date_format($value->first_second_half_date);
                    $leave_day_type='Half Day';
                }
                if($value->leave_type==1){
                    $leave_type='Annual';
                }elseif($value->leave_type==2){
                    $leave_type='Sick';
                }elseif($value->leave_type==3){
                    $leave_type='Casual';
                }
                if($value->approval_status==2){
                   $approval_status="Approved"; 
                }else if($value->approval_status==1){
                   $approval_status="Pending"; 
                }else if($value->approval_status==3){
                   $approval_status="Rejected"; 
                }
                if($value->approval_status_lm==2){
                   $approval_status_lm="Approved"; 
                }else if($value->approval_status_lm==1){
                   $approval_status_lm="Pending"; 
                }else if($value->approval_status_lm==3){
                   $approval_status_lm="Rejected"; 
                }
                $new_input = array(
                    'id' => $value->id,
                    'emp_id' => $employee->emp_id, 
                    'emp_name' => $employee->emp_name, 
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'no_of_days' => $value->no_of_days,
                    'leave_day_type' => $leave_day_type,
                    'leave_type' => $leave_type,
                    'approval_status' => $approval_status,
                    'approval_status_lm' => $approval_status_lm
                    
                    
                );
                array_push($teamLeaves, $new_input);
                
            }
        }
        
        
        
        $data['mineLeaves']=$mineLeaves;
        $data['mineLeavesLength']=$leave_application_request_list_mine->count();
        $data['teamLeavesLength']=$leave_application_request_list_team->count();
        $data['teamLeaves']=$teamLeaves;
        
        return json_encode(['message' => 'Leaves fetched successfully', 'status' => true, 'data' => $data]);
        
    }


    public function remainingLeave()
    {
        $data =  [];
        $emp_id = request()->employee_id;

        $policyLeaves = apiLeaveHelper::policyLeaves($emp_id);
       
        foreach ($policyLeaves as $key => $value) {
            
            $empAppliedLeaves = apiLeaveHelper::empAppliedLeaves($emp_id,$value->leave_type_id,$value->no_of_leaves,$value->leaves_policy_id);
            $data[] = $empAppliedLeaves;
        }



        if(count($data) > 0)
        {
            $data[] = ['leave_name' => 'Total', 'no_of_leaves' => array_sum(array_column($data, 'no_of_leaves')), 'used_leaves' => array_sum(array_column($data, 'used_leaves')), 'remaining_leaves' => array_sum(array_column($data, 'remaining_leaves')) ];

            return json_encode([ 'message' => 'Employee remaining leave', 'status' => true,'data' => $data ]);

        }
        else
        {
            return json_encode([ 'message' => 'Record not found', 'status' => false , 'data' => [] ]);
        }


    }

    public function appliedLeave()
    {
        
        $employee_id=request()->get('employee_id');
        $data = apiLeaveHelper::empLeavesList($employee_id);

        if($data->count() > 0)
        {
            return json_encode([ 'message' => 'Employee applied list', 'status' => true,'data' => $data ]);

        }
        else
        {
            return json_encode([ 'message' => 'Record not found', 'status' => false , 'data' => [] ]);
        }

    }

    public function salaryList()
    {
        $employee_id = request()->employee_id;
        $year = request()->year;

        CommonHelper::companyDatabaseConnection('12');
        $data = Payslip::select(
            DB::raw("
            MONTHNAME(month_year) month_name ,
            FORMAT(net_salary,0) salary ,
            CONCAT(year,'-',month,'-','01',' - ',month_year) monthdate,
            CONCAT(month,emp_id,year) Detailkey   
                ")
            )->where(['status' => 1 , 'employee_id' =>  $employee_id , 'year' => $year])->get();
        
        CommonHelper::reconnectMasterDatabase();

        if($data->count() > 0)
        {
             return json_encode([ 'message' => 'Employee payslip list', 'status' => true,'data' => $data ]);

        }
        else
        {
            return json_encode([ 'message' => 'Record not found', 'status' => false , 'data' => [] ]);
        }

    }

    public function salaryDetail()
    {
        $key = request()->key;
      
        
        CommonHelper::companyDatabaseConnection('12');

        $data = Payslip::select(
            DB::raw("
            CONCAT(MONTHNAME(month_year),', ',year) month_year ,
            FORMAT(net_salary,0) net_salary ,
            FORMAT(gross_with_allowances,0) 'Gross_Salary' ,
          
            
            FORMAT(fix_medical,0) Medical,
            FORMAT(fuel_allowance,0) Fuel,
            FORMAT(mobile,0) Mobile,
           
            FORMAT(basic_salary,0) Basic,
            FORMAT(fix_medical,0) Medical,
            FORMAT(hr_utility_allowance,0) 'House_Rent',
            
            FORMAT(pf_employee_fund,0) d_pf_employee_fund,
            FORMAT(eobi_amount,0) d_eobi_amount,
            FORMAT(tax_amount,0) d_tax_amount,
            ifnull(FORMAT(other_deduct,0),0) d_other_deduction,   
            ifnull(FORMAT(penalty,0),0) d_penalty_deduction,   
            ifnull(FORMAT(lateness,0),0) d_lateness_deduction,   
            ifnull(FORMAT(advance_salary_amount,0),0) d_advance_salary_amount_deduction,   
            ifnull(FORMAT(lwp_deduction,0),0) d_lwp_deduction_deduction,   
            FORMAT(total_deduction,0) d_deduction,
            CONCAT(emp_id,year) yearlyKey 


                ")
            )->whereRaw("CONCAT(month,emp_id,year) = '$key'")->get();
        
           
        CommonHelper::reconnectMasterDatabase();

        if($data->count() > 0)
        {
             return json_encode([ 'message' => 'Employee payslip detail', 'status' => true,'data' => $data ]);

        }
        else
        {
            return json_encode([ 'message' => 'Record not found', 'status' => false , 'data' => [] ]);
        }

    }

    public function yearlySalary()
    {
        $key = request()->key;
      
        
        CommonHelper::companyDatabaseConnection('12');

        $data = Payslip::select(
            DB::raw("
            CONCAT(MONTHNAME(month_year),', ',2022) month_year ,
            FORMAT(SUM(gross_with_allowances),0) 'Gross_Salary' ,
            FORMAT(SUM(basic_salary),0) Basic,
            FORMAT(SUM(fix_medical),0) Medical,
            FORMAT(SUM(hr_utility_allowance),0) 'House_Rent',
            FORMAT(SUM(pf_employee_fund),0) d_pf_employee_fund,
            FORMAT(SUM(eobi_amount),0) d_eobi_amount,
            FORMAT(SUM(tax_amount),0) d_tax_amount,
            ifnull(FORMAT(SUM(other_deduct),0),0) d_other_deduction,   
            ifnull(FORMAT(SUM(penalty),0),0) d_penalty_deduction,   
            ifnull(FORMAT(SUM(lateness),0),0) d_lateness_deduction,   
            ifnull(FORMAT(SUM(advance_salary_amount),0),0) d_advance_salary_amount_deduction,   
            ifnull(FORMAT(SUM(lwp_deduction),0),0) d_lwp_deduction_deduction,   
            FORMAT(SUM(total_deduction),0) d_deduction
                ")
            )->whereRaw("CONCAT(emp_id,year) = '$key'")->get();
        
           
        CommonHelper::reconnectMasterDatabase();

        if($data->count() > 0)
        {
            return json_encode([ 'message' => 'Employee yearly salary detail', 'status' => true,'data' => $data ]);
        }
        else
        {
            return json_encode([ 'message' => 'Record not found', 'status' => false , 'data' => [] ]);
        }

    }
    public function empSalaryYear()
    {
        # code...
        CommonHelper::companyDatabaseConnection('12');
     
        $employee_id = request()->employee_id;
        $data = Payslip::select('year','emp_id')->where([['employee_id','=',$employee_id],['status','=',1]])->groupBy('year')->get();


        CommonHelper::reconnectMasterDatabase();

        if($data->count() > 0)
        {
            return json_encode([ 'message' => 'employee salary year', 'status' => true,'data' => $data ]);
        }
        else
        {
            return json_encode([ 'message' => 'Record not found', 'status' => false , 'data' => [] ]);
        }
    }


    public function fetchAttendanceRequestQueries(){
        $emp_id = request()->emp_id;
        $attendance_queries_array=[];
        $acc_type=Db::table('users')->select('acc_type')->where('emp_id',$emp_id)->where('status',1)->value('acc_type');
        CommonHelper::companyDatabaseConnection(12);

        if($acc_type=='client'){
            $attendance_queries=DB::table('attendance_queries')->where('status',1)->orderBy('approval_status');
        }else{
            $team_members = Employee::select('emp_id','emp_name')
                ->where('reporting_manager',$emp_id)
                ->where('status',1);
            $employee_ids=[];
            $employee_ids[]=$emp_id;
            foreach($team_members->get() as $value):
                $employee_ids[]=$value->emp_id;
            endforeach;
            if($team_members->count()>0){
                $attendance_queries=DB::table('attendance_queries')->where('status',1)->whereIn('emp_id',$employee_ids)->orderBy('approval_status');
            }else{
                $attendance_queries=DB::table('attendance_queries')->where('status',1)->where('emp_id',$emp_id)->orderBy('approval_status');
            }


        }

        foreach($attendance_queries->paginate(25) as $val){
            CommonHelper::companyDatabaseConnection(12);
            $employee = DB::table('employee')->select('emp_id','emp_name')->where([['status', '=', 1], ['emp_id', '=', $val->emp_id]])->first();
            CommonHelper::reconnectMasterDatabase();
            if($val->approval_status==2){
                $approval_status="Resolved";
            }else if($val->approval_status==1){
                $approval_status="Pending";
            }else if($val->approval_status==3){
                $approval_status="Rejected";
            }


            $new_input = array(
                'id' => $val->id,
                'emp_id' => $employee->emp_id,
                'emp_name' => $employee->emp_name,
                'attendance_date' => $val->attendance_date,
                'clock_in' => $val->clock_in,
                'clock_out' => $val->clock_out,
                'query' => $val->query,
                'approval_status' => $approval_status

            );
            array_push($attendance_queries_array, $new_input);
        }

        CommonHelper::reconnectMasterDatabase();
        return json_encode(['message' => 'Time Request Fetched successfully', 'status' => true,'data' => $attendance_queries_array,'length' => $attendance_queries->count() ]);
    }


    public function approveOrRejectAttendanceQuery(){
        CommonHelper::companyDatabaseConnection('12');
        $id=request()->id;
        $status=request()->status;
        $username=request()->username;

        if($id==''){
            return json_encode(['message' => 'Not found', 'status' => false]);
        }
        $emp_query=DB::table('attendance_queries')->where('id',$id);
        if($emp_query->count()>0){
            if($status==0){
                $emp_query_first=$emp_query->first();
                if($emp_query_first->approval_status == 1){
                    DB::table('attendance_queries')->where('id',$id)->delete();
                }
            }
            else if($status==2){
                $emp_query_first=$emp_query->first();
                $data1['clock_out']=$emp_query_first->clock_out;
                $data1['clock_in']=$emp_query_first->clock_in;
                $data1['attendance_date']=$emp_query_first->attendance_date;
                $data1['manual_attendance']=1;
                $exist=DB::table('attendance')->where('attendance_date',$emp_query_first->attendance_date)->where('emp_id',$emp_query_first->emp_id);
                if($exist->count()>0){
                    DB::table('attendance')->where('attendance_date',$emp_query_first->attendance_date)->where('emp_id',$emp_query_first->emp_id)->update($data1);
                }else{
                    $data1['emp_id']=$emp_query_first->emp_id;
                    DB::table('attendance')->where('attendance_date',$emp_query_first->attendance_date)->insert($data1);
                }
                $leave_adjustments['emp_id']=$emp_query_first->emp_id;
                $leave_adjustments['month']=date("m",strtotime($emp_query_first->attendance_date));
                $leave_adjustments['year']=date("Y",strtotime($emp_query_first->attendance_date));
                $leave_adjustments['status'] = 1;
                $leave_adjustments['username'] = $username;
                $leave_adjustments['date'] = date("Y-m-d");
                $leave_adjustments['time'] = date("H:i:s");
                $excel_month=date("m",strtotime($emp_query_first->attendance_date));
                $excel_year=date("Y",strtotime($emp_query_first->attendance_date));

                $exists=DB::Table('leave_adjustments_queue')->where('emp_id',$emp_query_first->emp_id)->where('month',$excel_month)->where('year',$excel_year);
                if($exists->count()>0){
                    DB::Table('leave_adjustments_queue')->where('emp_id',$emp_query_first->emp_id)->where('month',$excel_month)->where('year',$excel_year)->update($leave_adjustments);
                }else{
                    DB::Table('leave_adjustments_queue')->insert($leave_adjustments);
                }


            }
            if($status != 0){
                $data['approval_status']=$status;
                DB::table('attendance_queries')->where('id',$id)->update($data);


            }

        }

        return json_encode([
            'message' => 'Time Request '. ($status == 2 ? 'Approved' : 'Rejected') .' successfully',
            'status' => true
        ]);
        CommonHelper::reconnectMasterDatabase();
    }


    public function addAttendanceRequestQueriesDetail()
    {

        CommonHelper::companyDatabaseConnection(12);
        $data1['employee_id'] = Input::get('employee_id');
        $data1['emp_id'] = DB::table('employee')->select('emp_id')->where('id',Input::get('employee_id'))->value('emp_id');
        $data1['attendance_date'] = Input::get('attendance_date');
        $username=Input::get('username');
        if(Input::get('clock_out') != ''){
            $clock_out=date("H:i", strtotime(Input::get('clock_out')));
        }else{
            $clock_out='';
        }
        if(Input::get('clock_in') !=''){
            $clock_in=date("H:i", strtotime(Input::get('clock_in')));
        }else{
            $clock_in='';
        }

        $data1['clock_out'] = $clock_out;
        $data1['clock_in'] = $clock_in;
        $data1['query'] = Input::get('queryMessage');
        $data1['status'] = 1;
        $data1['username'] = $username;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $last_id=DB::table('attendance_queries')->insertGetId($data1);


        $request_email_queue['employee_id']=Input::get('employee_id');
        $request_email_queue['record_id']=$last_id;
        $request_email_queue['attendance_date']=Input::get('attendance_date');
        $request_email_queue['clock_in']=$clock_in;
        $request_email_queue['clock_out']=$clock_out;
        $request_email_queue['query']=Input::get('queryMessage');
        $request_email_queue['type'] = 'attendance';
        $request_email_queue['sent_to'] = 'hr';
        $request_email_queue['status'] = 1;
        $request_email_queue['username'] = $username;
        $request_email_queue['date'] = date("Y-m-d");
        $request_email_queue['time'] = date("H:i:s");

        $exists=DB::Table('request_email_queue')->where('employee_id',Input::get('employee_id'))->where('record_id',$last_id);
        if($exists->count()>0){
            DB::Table('request_email_queue')->where('employee_id',Input::get('employee_id'))->where('record_id',$last_id)->update($request_email_queue);
        }else{
            DB::Table('request_email_queue')->insert($request_email_queue);
        }


        CommonHelper::reconnectMasterDatabase();

        return json_encode(['message' => 'Time Request submitted successfully', 'status' => true , 'data' => [] ],JSON_FORCE_OBJECT);
    }

    public function deleteAttendanceRequest(){
        $id = request()->id;
        CommonHelper::companyDatabaseConnection(12);
        DB::table('attendance_queries')->where([['id', '=', $id]])->delete();
        CommonHelper::reconnectMasterDatabase();
        if($id != ''){
            return json_encode(['message' => 'Request deleted successfully', 'status' => true]);
        }else{
            return json_encode(['message' => 'Id not found', 'status' => true]);
        }

    }

}