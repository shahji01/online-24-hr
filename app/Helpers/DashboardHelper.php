<?php
namespace App\Helpers;
use App\Models\EmployeeOfTheMonth;
use App\Models\EmployeePromotion;
use App\Models\AdvanceSalary;
use App\Models\LoanRequest;
use App\Models\LeaveApplication;
use DB;
use Carbon\Carbon;
use Config;
use Input;
use DateTime;
use App\Helpers\CommonHelper;
use \Cache;

use Illuminate\Support\Facades\Auth;

class DashboardHelper{

    public static function compare_dates($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    }

    public static function dashboardHomeData()
    {
        $user_roles = CommonHelper::userRoles(Input::get('m'));

        $employee_data = Cache::get('employee');
        $active_emp = array_filter($employee_data, function($value) use ($user_roles) {
            // condition which makes a result belong to variable
            $a = [];
            if(Auth::user()->acc_type == 'client') {
                return substr('1', $value->status) !== false;
            }
            elseif(Auth::user()->acc_type != 'client') {
                foreach ($user_roles['location_ids'] as $key => $values) {
                    if($value->location_id == $values && $value->status == 1) {
                        array_push($a,$value);
                    }
                }
            }
            return $a;
        });

        $work_anniversary = [];
        $upcoming_birthdays_detail = [];

        $currentDate = date("m-d");
        $futureDate = date("m-d", strtotime('+10 days'));

        foreach ($active_emp as $key => $value) {

            $joining_date =  $value->joining_date;
            $date_of_birth =   $value->date_of_birth;

            if(date('m-d' ,  strtotime($joining_date)) >= $currentDate && date('m-d' ,  strtotime($joining_date)) <= $futureDate)
            {
                $joining_datetime = new DateTime($joining_date);
                $current_datetime = new DateTime();

                $years_of_work = $current_datetime->diff($joining_datetime)->y;

                array_push($work_anniversary,array("id"=> $value->id, "emp_id" => $value->emp_id, "emp_name" => $value->emp_name, "joining_date" => $value->joining_date, "img_path" => $value->img_path,"years_of_work" => $years_of_work , "date" => date('m-d' ,  strtotime($joining_date))));
            }
            elseif(date('m-d' ,  strtotime($date_of_birth)) >= $currentDate && date('m-d' ,  strtotime($date_of_birth)) <= $futureDate)
            {

                $date_of_birthtime = new DateTime($date_of_birth);
                $current_datetime = new DateTime();
                $birth_years = $current_datetime->diff($date_of_birthtime)->y;
                array_push($upcoming_birthdays_detail,array("id"=> $value->id, "emp_id" => $value->emp_id, "emp_name" => $value->emp_name, "date_of_birth" => $value->date_of_birth, "img_path" => $value->img_path,"birth_years" => $birth_years , "date" => date('m-d' ,  strtotime($date_of_birth))));
            }
        }


        usort($work_anniversary, function($a, $b) {
            return strcmp($a['date'], $b['date']);
        });
        usort($upcoming_birthdays_detail, function($a, $b) {
            return strcmp($a['date'], $b['date']);
        });

        $employee_of_month = [];
        //        $employee_of_month = Cache::remember('employee_of_month', 24 * 60, function () {
        //            return EmployeeOfTheMonth::where([['status','=', '1'],['month','=',date('m')],['year','=',date('Y')]])->orderBy('id')->get();
        //        });
        return compact('upcoming_birthdays_detail','employee_of_month','work_anniversary');
    }

    public static function viewExpiryAndUpcomingAlerts()
    {
        $user_roles = CommonHelper::userRoles(Input::get('m'));

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $employee_promotion =  DB::table('employee_promotion AS  ep')
            ->join('employee_multiple_locations AS  eml', 'ep.employee_id', '=', 'eml.employee_id')
            ->where([['ep.approval_status','=',1] , ['ep.status','=',1], ['eml.status','=',1]])
            ->whereIn('eml.location_id', $user_roles['location_ids'] )
            ->count();

        $advance_salary = DB::table('advance_salary AS  ad')
            ->join('employee_multiple_locations AS  eml', 'ad.employee_id', '=', 'eml.employee_id')
            ->where([['ad.approval_status','=',1] , ['ad.status','=',1], ['eml.status','=',1]])
            ->whereIn('eml.location_id', $user_roles['location_ids'] )
            ->count();

        $loan_request =  DB::table('loan_request AS  lr')
            ->join('employee_multiple_locations AS  eml', 'lr.employee_id', '=', 'eml.employee_id')
            ->where([['lr.approval_status','=',1] , ['lr.status','=',1], ['eml.status','=',1]])
            ->whereIn('eml.location_id', $user_roles['location_ids'] )
            ->count();

        $employee_data = Cache::get('employee');
        $active_emp = array_filter($employee_data, function($value) use ($user_roles) {
            // condition which makes a result belong to variable
            $a = [];
            if(Auth::user()->acc_type == 'client') {
                return substr('1', $value->status) !== false;
            }
            elseif(Auth::user()->acc_type == 'admin') {
                foreach ($user_roles['location_ids'] as $key => $values) {
                    if($value->location_id == $values && $value->status == 1) {
                        array_push($a,$value);
                    }
                }
            }
            return $a;
        });


        $inactive_employees = array_filter($employee_data, function($value) use ($user_roles) {
            // condition which makes a result belong to variable
            $a = [];
            if(Auth::user()->acc_type == 'client') {
                return $value->status == 4;
            }
            elseif(Auth::user()->acc_type == 'admin') {
                foreach ($user_roles['location_ids'] as $key => $values) {
                    if($value->location_id == $values && $value->status == 4) {
                        array_push($a,$value);
                    }
                }
            }
            return $a;
        });

        $attendance_queries = DB::table('attendance_queries')->where('status',1)->where('approval_status',1)->count();
        $date = date("Y-m-d");

        $emp_count = count($active_emp);
        $inactive_employees = count($inactive_employees) ;

        $present_count = DB::table('attendance')->where('attendance_date',$date)->where('clock_in','!=','')->where('status',1)->count('employee_id');
        $absents_detail = $emp_count - $present_count;
        $employees_id = [];
        foreach($active_emp as $val){
            $employees_id[] = $val->id;
        }
        $employees_id = implode(',',$employees_id);

        $half_days_count = AttendanceHelper::totalHalfDays($date,$date,$employees_id)? AttendanceHelper::totalHalfDays($date,$date,$employees_id) : [];
        $lates_count = AttendanceHelper::totalLate($date,$date,$employees_id)? AttendanceHelper::totalLate($date,$date,$employees_id)  : [];

        //        $expireDateOne = date('Y-m-d',strtotime(now() .'+1 month'));
        //        $date = date("Y-m-d");
        //        $cnic_expiry_date_count = array_filter($active_emp, function($value) use ($expireDateOne,$date) {
        //            return  $value->cnic_expiry_date < $expireDateOne &&  $value->cnic_expiry_date > $date;
        //        });
        //
        //        $permanent_employee = array_filter($active_emp, function($value) {
        //            // condition which makes a result belong to variable.
        //            return substr('1', $value->employment_status_id) !== false;
        //        });
        
        //        $employee_missing_images= array_filter($active_emp, function($value) {
        //            // condition which makes a result belong to variable.
        //            return 'app/uploads/employee_images/user-dummy.png' == $value->img_path;
        //        });

        $employeesProbationExpires = array_filter($active_emp, function($value) {
            // condition which makes a result belong to variable.
            return date("Y-m-d") >= $value->probation_expire_date && $value->probation_expire_date != '' && $value->employment_status_id != 7 ;
        });
        //$employeeProbationPeriodOverDetail =DB::select( DB::raw("SELECT count('emp_id') as totalOverProbationEmp FROM employee WHERE employment_status_id = '8' AND status = '1' AND joining_date <= DATE_ADD('".date("Y-m-d")."',INTERVAL -6 MONTH)"));
        CommonHelper::reconnectMasterDatabase();
        
        $emr_array = [];
        foreach($active_emp as $val):
            $emr_array[] = $val->id;
        endforeach;


        $leave_application =  DB::table('leave_application AS  la')
            ->join('leave_application_data AS  lad', 'la.id', '=', 'lad.leave_application_id')
            ->where([['la.approval_status','=',1] , ['la.status','=',1]])
            ->whereIn('la.employee_id', $emr_array )
            ->count();

        $total_count = count($employeesProbationExpires) + $inactive_employees +
            $leave_application + $employee_promotion + $advance_salary + $loan_request;
        if($total_count > 99) $total_count = '99+';

        return compact('half_days_count','lates_count', 'attendance_queries','absents_detail','inactive_employees',
            'employeesProbationExpires','total_count','employee_promotion','advance_salary','loan_request','leave_application');
    }

    public static function donutChartDataEmployee()
    {
        $user_roles = CommonHelper::userRoles(Input::get('m'));
        $employee_data = Cache::get('employee');
        $active_employees = count(array_filter($employee_data, function($value) use ($user_roles) {
            // condition which makes a result belong to variable
            $a = [];
            if(Auth::user()->acc_type == 'client') {
                return substr('1', $value->status) !== false;
            }
            elseif(Auth::user()->acc_type == 'admin') {
                foreach ($user_roles['location_ids'] as $key => $values) {
                    if($value->location_id == $values && $value->status == 1) {
                        array_push($a,$value);
                    }
                }
            }
            return $a;
        }));

        $inactive_employees = count(array_filter($employee_data, function($value) use ($user_roles) {
            // condition which makes a result belong to variable
            $a = [];
            if(Auth::user()->acc_type == 'client') {
                return $value->status == 4;
            }
            elseif(Auth::user()->acc_type == 'admin') {
                foreach ($user_roles['location_ids'] as $key => $values) {
                    if($value->location_id == $values && $value->status == 4) {
                        array_push($a,$value);
                    }
                }
            }
            return $a;
        }));
        $exit_employees = count(array_filter($employee_data, function($value) use ($user_roles) {
            // condition which makes a result belong to variable
            $a = [];
            if(Auth::user()->acc_type == 'client') {
                return $value->status == 3;
            }
            elseif(Auth::user()->acc_type == 'admin') {
                foreach ($user_roles['location_ids'] as $key => $values) {
                    if($value->location_id == $values && $value->status == 3) {
                        array_push($a,$value);
                    }
                }
            }
            return $a;
        }));

        return compact('active_employees','inactive_employees', 'exit_employees');//,'absents_detail','inactive_employees','employeesProbationExpires','total_count');

    }

    public static function verticalBarChartDataEmployeeSalary()
    {
        $user_roles = CommonHelper::userRoles(Input::get('m'));

        $totalSalaryQuery ="
        SELECT l.id ,ROUND(SUM(IFNULL(pa.tax_amount,0))) tax_amount,l.location_name ,ROUND(SUM(IFNULL(pa.net_salary,0))) pay  FROM ".CONST_COMPANY_DB.".payslip pa
        INNER JOIN ".CONST_COMPANY_DB.".employee_multiple_locations eml
        ON eml.employee_id = pa.employee_id
        INNER JOIN ".CONST_MASTER_DB.".locations l
        ON eml.location_id = l.id

        WHERE eml.status = 1 and 
        pa.status = 1
        GROUP BY eml.location_id
        ";
        $totalSalaryQueryData = DB::select(DB::raw($totalSalaryQuery));

        $totalPaySalary = array_filter($totalSalaryQueryData, function($value) use ($user_roles) {
            // condition which makes a result belong to variable
            $a = [];
            if(Auth::user()->acc_type == 'client') {
                return $value;
            }
            elseif(Auth::user()->acc_type == 'admin') {
                foreach ($user_roles['location_ids'] as $key => $values) {
                    if($value->id == $values ) {
                        array_push($a,$value);
                    }
                }
            }
            return $a;
        });

        return compact('totalPaySalary');//,'inactive_employees', 'exitEmp');//,'absents_detail','inactive_employees','employeesProbationExpires','total_count');
    }

    public static function lineChartDataEmployeeSalary()
    {
        $user_roles = CommonHelper::userRoles(Input::get('m'));

        $totalSalaryMonthlyQuery = DB::table(CONST_COMPANY_DB.'.payslip as pa')
        ->select('l.id', 'l.location_name', \DB::raw('ROUND(SUM(IFNULL(pa.net_salary, 0))) as pay'), \DB::raw('CONCAT(YEAR(pa.month_year), " - ", MONTHNAME(pa.month_year)) as month_year'))
        ->join(CONST_COMPANY_DB.'.employee_multiple_locations as eml', 'eml.employee_id', '=', 'pa.employee_id')
        ->join('locations as l', 'eml.location_id', '=', 'l.id')
        ->where('eml.status', '=', 1)
        ->where('pa.status', '=', 1)
        ->groupBy('pa.month_year', 'eml.location_id')
        ->orderBy('eml.location_id')
        ->orderBy('pa.month_year')
        ->get()
        ->toArray();





        $totalPaySalaryMonthly = array_filter($totalSalaryMonthlyQuery, function($value) use ($user_roles) {
            // condition which makes a result belong to variable
            if(Auth::user()->acc_type == 'client') {
                return $value;
            }
            elseif(Auth::user()->acc_type == 'admin') {
                foreach ($user_roles['location_ids'] as $key => $values) {
                    if($value->id == $values ) {
                        return $value;

                    }
                }
            }
        });


    

        $uniqueMonthYear = array_unique(array_map(function($item) {
            return $item->month_year;
        }, $totalPaySalaryMonthly));
        
        $currentMonthYear = date('Y - F');
        (count($uniqueMonthYear) == 0 )? $uniqueMonthYear = [$currentMonthYear]: '' ;

        $date = DateTime::createFromFormat('Y - F', reset($uniqueMonthYear));
        $date->modify('-1 month');
        $uniqueMonthYear = array_merge([$date->format('Y - F')], $uniqueMonthYear);
        
        $location = array_unique(array_map(function($item) {
            return $item->id;
        }, $totalPaySalaryMonthly));
        
        $data = [];
        foreach ($location as $keylocation => $valuelocation) {
            foreach ($uniqueMonthYear as $keyuniqueMonthYear => $valueuniqueMonthYear) {
                $data[$valuelocation][$valueuniqueMonthYear] = 0;
            }
        }
        
        foreach ($totalPaySalaryMonthly as $obj) {
            if (isset($data[$obj->id][$obj->month_year])) {
                $data[$obj->id][$obj->month_year] = $obj->pay;
            }
        }
        
        $replacedArray = array_map('array_values', $data);
        $dataset = [];
        
        foreach ($totalPaySalaryMonthly as $result) {
            if (!array_key_exists($result->id, $dataset)) {
                $dataset[$result->id] = [
                    'backgroundColor' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
                    'borderColor' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
                    'data' => $replacedArray[$result->id],
                    'fill' => false,
                    'label' => $result->location_name,
                ];
            }
        }
        
        $dataset = [$dataset, $uniqueMonthYear];
        
    
        return compact('dataset');
    }

}