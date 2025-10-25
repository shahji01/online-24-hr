<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Designation;
use App\Models\Diseases;
use App\Models\EmployeeGsspDocuments;
use App\Models\EmployeeMedical;
use App\Models\HrWarningLetter;
use App\Models\IncomeTax;
use App\Models\TFAllowance;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\In;
use Input;
use Hash;
use \Cache;

use App\Helpers\CommonHelper;
use App\Helpers\ReportHelper;
use App\Helpers\HrHelper;
use App\Helpers\AttendanceHelper;
use App\Models\Department;
use App\Models\Employee;
use App\Models\PayrollData;
use App\Models\Attendence;
use App\Models\Payroll;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\TaxSlabs;
use App\Models\TransferedLeaves;
use App\Models\JobType;
use App\Models\SubDepartment;
use App\Models\MaritalStatus;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\CarPolicy;
use App\Models\Bonus;
use App\Models\LoanRequest;
use App\Models\Tax;
use App\Models\Eobi;
use App\User;
use App\Models\RequestHiring;
use App\Models\Qualification;
use App\Models\ShiftType;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use App\Models\Holidays;
use App\Models\EmployeeDeposit;
use App\Models\LeaveApplicationData;
use App\Models\EmployeeExit;
use App\Models\Locations;
use App\Models\EmployeeCategory;
use App\Models\EmployeeCardRequest;
use App\Models\DegreeType;
use App\Models\Regions;
use App\Models\Grades;
use App\Models\EmployeeFamilyData;
use App\Models\EmployeeBankData;
use App\Models\EmployeeEducationalData;
use App\Models\EmployeeLanguageProficiency;
use App\Models\EmployeeHealthData;
use App\Models\EmployeeActivityData;
use App\Models\EmployeeWorkExperience;
use App\Models\EmployeeReferenceData;
use App\Models\EmployeeKinsData;
use App\Models\EmployeeRelativesData;
use App\Models\EmployeeOtherDetails;
use App\Models\EmployeePromotion;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeTransfer;
use App\Models\EmployeeFuelData;
use App\Models\HrTerminationFormat1Letter;
use App\Models\HrTerminationFormat2Letter;
use App\Models\HrContractConclusionLetter;
use App\Models\HrMfmSouthWithoutIncrementLetter;
use App\Models\HrMfmSouthIncrementLetter;
use App\Models\EmployeeHrAudit;
use App\Models\EmployeeEquipments;
use App\Models\Equipments;
use App\Models\LeaveApplication;
use App\Models\LetterFiles;
use App\Models\EmployeeMedicalDocuments;
use App\Models\Trainings;
use App\Models\FinalSettlement;
use App\Models\HrTransferLetter;
use App\Models\Gratuity;
use App\Models\AdvanceSalary;
use App\Models\TrainingCertificate;
use App\Models\TransferLetter;
use App\Models\PromotionLetter;
use App\Models\TransferEmployeeProject;
use App\Models\projectTransferLetter;
use App\Models\EmployeeProjects;
use App\Models\Policies;
use App\Models\Payslip;
use App\Models\Rebate;
use App\Models\EmployeeRebateDocuments;
use App\Models\LeaveType;
use App\Models\LoanType;
use App\Models\Cities;
use App\Models\AllowanceType;
use App\Models\EmployeeMultipleLocations;
use App\Models\LoanTopUp;
use App\Models\LoanAdjustment;
use App\Models\LeavesEncashment;
use DatePeriod;
use DateTime;
use DateInterval;
Use DATE_SUB;

class HrDataCallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function calculateRebateAmount()
    {
        $nature = Input::get('nature');
        $actual_investment = Input::get('actual_investment');
        $emp_code = Input::get('emp_code');

        CommonHelper::fyDatabaseConnection();
        $tax_deduction = TaxDeduction::where([['emp_code', '=', $emp_code],['status', '=', 1]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();

        if($nature == 'Charitable / Zakaat Donation us 61'):
            $max_allowed_investment = round($tax_deduction->taxable_income * 0.30);
            $rate = number_format(($tax_deduction->tax_payable / $tax_deduction->taxable_income) * 100, 2);

            if($actual_investment < 2000000 && $actual_investment < $max_allowed_investment):
                $rebate_amount = round(($rate * $actual_investment) / 100);
                $base = 'Actual Investment';
            elseif(2000000 < $actual_investment && 2000000 < $max_allowed_investment):
                $rebate_amount = round(($rate * 2000000) / 100);
                $base = '2 Million';
            else:
                $rebate_amount = round(($rate * $max_allowed_investment) / 100);
                $base = '30% of Taxable Income';
            endif;

        elseif($nature == 'Investment of Shares and Insurance us 62'):
            $max_allowed_investment = round($tax_deduction->taxable_income * 0.20);
            $rate = number_format(($tax_deduction->tax_payable / $tax_deduction->taxable_income) * 100, 2);

            if($actual_investment < 2000000 && $actual_investment < $max_allowed_investment):
                $rebate_amount = round(($rate * $actual_investment) / 100);
                $base = 'Actual Investment';
            elseif(2000000 < $actual_investment && 2000000 < $max_allowed_investment):
                $rebate_amount = round(($rate * 2000000) / 100);
                $base = '2 Million';
            else:
                $rebate_amount = round(($rate * $max_allowed_investment) / 100);
                $base = '20% of Taxable Income';
            endif;

        elseif($nature == 'Health Insurance us 62A'):
            $max_allowed_investment = round($tax_deduction->taxable_income * 0.05);
            $rate = number_format(($tax_deduction->tax_payable / $tax_deduction->taxable_income) * 100, 2);

            if($actual_investment < 2000000 && $actual_investment < $max_allowed_investment):
                $rebate_amount = round(($rate * $actual_investment) / 100);
                $base = 'Actual Investment';
            elseif(2000000 < $actual_investment && 2000000 < $max_allowed_investment):
                $rebate_amount = round(($rate * 2000000) / 100);
                $base = '0.15 Million ';
            else:
                $rebate_amount = round(($rate * $max_allowed_investment) / 100);
                $base = '5% of Taxable Income';
            endif;

        elseif($nature == 'Pension us 63'):

            CommonHelper::fyDatabaseConnection();
            $employee_data = ApiEmployeeShifts::where([['emp_code', '=', $emp_code],['status', '=', 1]])->first();
            CommonHelper::reconnectMasterDatabase();

            $dateOfBirth = date('d-m-Y',strtotime($employee_data->date_of_birth));
            $today = date("d-m-Y");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));

            if($diff->format('%y') == 40 ):
                $difference = 20;
            elseif($diff->format('%y') > 40):

                $difference  = $diff->format('%y') - 40;
                $difference = ($difference * 2) + 20;
            endif;

            $max_allowed_investment = round(($tax_deduction->taxable_income * $difference) / 100);
            $rate = number_format(($tax_deduction->tax_payable / $tax_deduction->taxable_income) * 100, 2);

            if($actual_investment < $max_allowed_investment):
                $rebate_amount = round(($rate * $actual_investment) / 100);
                $base = 'Actual Investment';
            else:
                $rebate_amount = round(($rate * $max_allowed_investment) / 100);
                $base = '20% of Taxable Income';
            endif;

        endif;
        return compact('max_allowed_investment','rate','rebate_amount', 'base');
    }

    public function filterEmployeeList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];

        $selectEmployeeGradingStatus = $_GET['selectEmployeeGradingStatus'];
        $selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];

        CommonHelper::companyDatabaseConnection($m);
        if(empty($selectEmployeeGradingStatus) && empty($selectSubDepartmentId)){
            $employeeList = Employee::get();
        }else if(empty($selectEmployeeGradingStatus) && !empty($selectSubDepartmentId)){
            $employeeList = Employee::whereBetween('date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('emp_sub_department_id','=',$selectSubDepartmentId)->get();
        }else if(!empty($selectEmployeeGradingStatus) && !empty($selectSubDepartmentId)){
            $employeeList = Employee::whereBetween('date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('emp_sub_department_id','=',$selectSubDepartmentId)->where('grading_system','=',$selectEmployeeGradingStatus)->get();
        }else if(!empty($selectEmployeeGradingStatus) && empty($selectSubDepartmentId)){
            $employeeList = Employee::whereBetween('date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('grading_system','=',$selectEmployeeGradingStatus)->get();
        }
        /*else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','1')->where('emp_sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','2')->where('emp_sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','1')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','1')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','2')->get();
        }*/
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.filterEmployeeList',compact('employeeList'));
    }

    public function viewDepartmentList(){
        Config::set(['database.connections.tenant.database' => Auth::user()->dbName]);
        Config::set(['database.connections.tenant.username' => 'root']);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->get();
        $counter = 1;
        foreach($departments as $row){
            ?>
            <tr>
                <td class="text-center"><?php echo $counter++;?></td>
                <td><?php echo $row['department_name'];?></td>
                <td></td>
            </tr>
            <?php
        }
    }


    public function checkPf(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $employee_id=Input::get('employee_id');
        $loan_amount=Input::get('loan_amount');
        $pf_amount=DB::select(DB::raw("SELECT (sum(pf_employee_fund)+sum(pf_company_fund)+sum(pf_arrears_deffered_fund)) as amount FROM pf where status=1 and employee_id='".$employee_id."' " ));
        $pf_amount_till_june=DB::table('pf')->where('employee_id',$employee_id)->where('status',1)->where('month','00')->where('year','00')->sum('amount');
        $eligible_amount=(($pf_amount[0]->amount+$pf_amount_till_june)/100)*40;

        if($loan_amount <= $eligible_amount){
            $res['success']=true;
        }else{
            $res['success']=false;
        }
        $res['eligible_amount']=number_format($eligible_amount);

        return response()->json($res);


        CommonHelper::reconnectMasterDatabase();


    }

    public function fetchAttendance(){
        CommonHelper::companyDatabaseConnection('12');

        $employee_id=Input::get('employee_id');
        $attendance_date=Input::get('attendance_date');

        $attendance=DB::table('attendance')->where('employee_id',$employee_id)->where('attendance_date',$attendance_date)->where('status',1);
        if($attendance->count()>0){
            $attendance_first=$attendance->first();
            $res['clock_in']=$attendance_first->clock_in;
            $res['clock_out']=$attendance_first->clock_out;
        }else{
            $res['clock_in']='';
            $res['clock_out']='';
        }

        return response()->json($res);

        CommonHelper::reconnectMasterDatabase();
    }

    public function viewAttendanceError()
    {

        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $location_id = Input::get('location_id');
        $job_type = Input::get('job_type');
        $all_emp_id=[];
        $dynamic_emp_id = [];
        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        
        // echo "<pre>";
        // print_r($employees);
        // exit();


        foreach ($employees as $key => $value) {
            
            $all_emp_id[]= $value->id;

        }
       
        $emrArrayToString = implode(', ', $all_emp_id);
        


        $attendanceErrorQuery = "

        SELECT  e.id,e.emp_name,a.id att_id,whp.working_hours_policy,a.working_hours_policy_id,a.clock_in,a.clock_out,a.attendance_date
        FROM ".CONST_COMPANY_DB.".attendance a 
        INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp
        ON a.working_hours_policy_id = whp.id
        INNER JOIN ".CONST_COMPANY_DB.".employee e
        ON a.employee_id = e.id
        
        WHERE 
        a.employee_id IN ($emrArrayToString)
        AND whp.status = 1 
        AND a.attendance_date BETWEEN '$from_date' AND '$to_date'
        AND 
        CASE 
        WHEN a.clock_in IS NOT NULL then
        TIME_FORMAT(a.clock_in, '%H:%i') NOT BETWEEN TIME_FORMAT(
            SUBTIME(
                TIME(start_working_hours_time) + INTERVAL working_hours_grace_time MINUTE,
                '01:00:00'
            ),
            '%H:%i'
        ) AND TIME_FORMAT(TIME(start_working_hours_time) + INTERVAL working_hours_grace_time+240 MINUTE , '%H:%i')
        WHEN a.clock_out IS NOT NULL THEN
        
        TIME_FORMAT(a.clock_out, '%H:%i') NOT BETWEEN TIME_FORMAT(
            SUBTIME(
                TIME(whp.end_working_hours_time) + INTERVAL whp.early_going_grace_time MINUTE,
                '01:00:00'
            ),
            '%H:%i'
        ) AND TIME_FORMAT(TIME(whp.end_working_hours_time) + INTERVAL early_going_grace_time+240 MINUTE , '%H:%i')
        
        ELSE
        FALSE
        end
        order by a.attendance_date,e.id
        ";
       
    
        $attendanceErrorData = DB::select($attendanceErrorQuery);
        
        
        $workingHourPolicies=DB::table('working_hours_policy')->where('status',1)->get();
        $working_hour_policies_array=[];
        foreach($workingHourPolicies as $val){
            $working_hour_policies_array[$val->id]=$val->working_hours_policy;
        }
        
      

        $departments = Cache::get('department');
        return view('Hr.Attendance.viewAttendanceError',compact('attendanceErrorData','workingHourPolicies','working_hour_policies_array','employees','from_date','to_date','all_emp_id','departments'));

        // echo "<pre>";
        // print_r($workingHourPolicies);
        // exit(); 
    }


    public function fetchAttendanceReport(){

        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $location_id = Input::get('location_id');
        $job_type = Input::get('job_type');
        $all_emp_id = [];
        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);

        foreach ($employees as $key => $value) {
            $all_emp_id[] = $value->id;
        }
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance=DB::table('attendance_logs as a')
        ->join('employee as e','e.id','=','a.employee_id')
        ->select('a.id','e.emp_id','e.emp_name','a.attendance_date','a.longitude','a.latitude','a.attendance_type','a.attendance_location','a.location_id','a.type','a.time')
        ->whereIn('a.employee_id',$all_emp_id)
        ->whereBetween('a.attendance_date',[$from_date,$to_date])
        ->orderBy('a.attendance_date', 'asc')
        ->orderBy('a.time', 'asc')
        ->orderBy('a.employee_id', 'asc')
        ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Attendance.fetchAttendanceReport',compact('attendance','from_date','to_date'));
    }

    public function viewAttendanceReport()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $location_id = Input::get('location_id');
        $job_type = Input::get('job_type');
        $all_emp_id = [];
        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        
        foreach ($employees as $key => $value) {
            $all_emp_id[] = $value->id;
        }

        $employee_id = count($employees) > 0 ? implode(',', $all_emp_id) : '0';
        
        $AttendanceReportQuery = "SELECT 
        e.emp_name,
        e.emp_id,
        att.employee_id,
        att.working_hours_policy_id,
        att.clock_in_location_id,
        att.clock_out_location_id,
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
                  IFNULL(TIME_FORMAT(att.clock_in, '%h:%i %p'),'--') AS clock_in,
                  IFNULL(TIME_FORMAT(att.clock_out, '%h:%i %p'),'--') AS clock_out,
                  
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
          IFNULL(
                IF(
                    FLOOR(
                        TIME_TO_SEC(
                            TIMEDIFF(
                                att.clock_in,
                                SEC_TO_TIME(
                                    TIME_TO_SEC(start_working_hours_time) + (working_hours_grace_time * 60)
                                )
                            )
                        ) / 60
                    ) < '0',
                    '--',
                    FLOOR(
                        TIME_TO_SEC(
                            TIMEDIFF(
                                att.clock_in,
                                SEC_TO_TIME(TIME_TO_SEC(start_working_hours_time))
                            )
                        ) / 60
                    ) - working_hours_grace_time
                ),
                '--'
            ) AS late,
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
              AND (
                (att.attendance_type = 2)
                OR ((att.attendance_type is NULL OR att.attendance_type = 1) AND NOT EXISTS (
                    SELECT 1
                    FROM ".CONST_COMPANY_DB.".attendance AS sub_att
                    WHERE
                        sub_att.employee_id = att.employee_id
                        AND sub_att.attendance_date = att.attendance_date
                        AND sub_att.attendance_type = 2
                ))
            )
             ORDER BY att.employee_id , att.attendance_date
      
            ";

        $attendance = DB::select(DB::raw($AttendanceReportQuery));
       
        //    echo "<pre>";
        //    print_r($AttendanceReportQuery);
        //    exit();
        $status_bg_color = array("Leave"=>"style='background-color: #adde80;'", "Half_day_leave"=>"style='background-color: #adde80;'", "Present"=>"style='background-color: ;'", "Off_day"=>"style='background-color: #FFC0CB;color:black;'", "Absent"=>"style='background-color: #e76e6ed9;color:white;'", "Half_day"=>"style='background-color: #ffd78d;color:black;'");
        
        $workingHourPolicies=DB::table('working_hours_policy')->where('status',1)->get();
        $working_hour_policies_array=[];
        foreach($workingHourPolicies as $val){
            $working_hour_policies_array[$val->id]=$val->working_hours_policy;
        }
        
        $locations = Cache::get('locations');
        return view('Hr.Attendance.viewAttendanceReport',compact('attendance','locations','working_hour_policies_array','status_bg_color','employees','from_date','to_date','employee_id'));
    }


    public function viewAttendanceProgress()
    {
        ini_set('max_execution_time', '600');
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = Input::get('location_id');
        $date_from = Input::get('from_date');
        $date_to = Input::get('to_date');
        $job_type = Input::get('job_type');
        $flag = Input::get('flag');

        $leave_dates_array = [];
        $month_year = explode('-', $date_to);
        $emr_array = [];
        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        
        $employees = array_filter($employees, function($value) use ($job_type) {
            // condition which makes a result belong to variable

            if($value->employment_status_id == $job_type) {
                return $value;
            }
            elseif(empty($job_type)) {
                return $value;
            }
        });
        
        foreach ($employees as $val) {
            $emr_array[] = $val->id;
        }

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $payroll_data = PayrollData::where([['attendance_from', '=', $date_from], ['attendance_to', '=', $date_to], ['status', '=', 1]])
            ->whereIn('employee_id', $emr_array)->get();
        $payroll_data_array = [];
        foreach ($payroll_data as $key1 => $val1):
            $payroll_data_array[$val1->employee_id] = $val1;
        endforeach;

        $attendance = Attendance::where([['status', '=', 1]])
            ->whereIn('employee_id', $emr_array)
            ->whereBetween('attendance_date', [$date_from, $date_to])
            ->select('id', 'employee_id', 'day', 'attendance_date', 'attendance_type', 'clock_in', 'clock_out', 'working_hours_policy_id', 'attendance_status', 'overtime_approval_status')->get()->toArray();
        $attendance_array = [];
        foreach ($attendance as $key1 => $val1):
            $attendance_array[] = $val1;
        endforeach;

        $emrArrayToString = implode(', ', $emr_array);
        $totalPresentDayP = AttendanceHelper::totalPresentDays($date_from, $date_to, $emrArrayToString);
        $totalPresentDayDw = AttendanceHelper::totalPresentDaysDw($date_from, $date_to, $emrArrayToString);
        $totalHoliday = AttendanceHelper::totalSundays($date_from, $date_to, $emrArrayToString);
        
        $totalPresentDay =   $totalPresentDayP + $totalPresentDayDw ; 
        $totalAbsentDays = AttendanceHelper::totalAbsentDays($date_from, $date_to, $emrArrayToString);
        $totalCplLeave = AttendanceHelper::cplLeave($date_from, $date_to, $emrArrayToString);
        $gez_overtime_array = AttendanceHelper::overTimeOffdayAndHoliday($date_from, $date_to, $emrArrayToString);
        $normalday_overtime = AttendanceHelper::overTimeAllday($date_from, $date_to, $emrArrayToString);

        // $totalOvertime =$totalOvertime1+$totalOvertime2 ;//array_merge($totalOvertime1,$totalOvertime2);
        $totalFullDayLeave = AttendanceHelper::totalFullDayLeave($date_from, $date_to, $emrArrayToString);
        $totalHalfDayLeave = AttendanceHelper::totalHalfDayLeave($date_from, $date_to, $emrArrayToString);
        $totalLate = AttendanceHelper::totalLate($date_from, $date_to, $emrArrayToString);
        $totalHalfDays = AttendanceHelper::totalHalfDays($date_from, $date_to, $emrArrayToString);
        $halfDaysAbsent = AttendanceHelper::halfDaysAbsent($date_from, $date_to, $emrArrayToString);
        $totalDeductionDays = AttendanceHelper::totalDeductionDays($emr_array, $totalAbsentDays, $totalLate, $totalHalfDays,$halfDaysAbsent, $totalFullDayLeave ,$totalHalfDayLeave );
        $totalLateDeduction = AttendanceHelper::totalLateDeduction($emr_array, $totalLate);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAttendanceProgress', compact('employees', 'flag','totalPresentDay', 'totalHoliday', 'totalAbsentDays', 'totalCplLeave', 'totalFullDayLeave','totalHalfDayLeave', 'totalLate', 'totalHalfDays', 'totalDeductionDays', 'totalLateDeduction', 'date_from', 'date_to', 'month_year', 'attendance_array', 'payroll_data_array','location_id','gez_overtime_array','normalday_overtime'));
    }
    
    
    public function refreshAttendanceProgress()
    {
        ini_set('max_execution_time', '600');
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = Input::get('location_id');
        $date_from = Input::get('from_date');
        $date_to = Input::get('to_date');
        $totaldayofmonth = Input::get('totaldayofmonth');

        $leave_dates_array = [];
        $month_year = explode('-', $date_to);

        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach ($employees as $val) {
            $emr_array[] = $val->id;
        }

        if(count($emr_array) == 0 ) $emr_array = 0; 

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $emrArrayToString = implode(', ', $emr_array);
        $totalPresentDayP = AttendanceHelper::totalPresentDays($date_from, $date_to, $emrArrayToString);
        $totalPresentDayDw = AttendanceHelper::totalPresentDaysDw($date_from, $date_to, $emrArrayToString);
        $totalHoliday = AttendanceHelper::totalSundays($date_from, $date_to, $emrArrayToString);

        $totalPresentDay = $totalPresentDayP + $totalPresentDayDw ;

        $totalAbsentDays = AttendanceHelper::totalAbsentDays($date_from, $date_to, $emrArrayToString);
        //$totalCplLeave = AttendanceHelper::cplLeave($date_from, $date_to, $emrArrayToString);
        $gez_overtime_array = AttendanceHelper::overTimeOffdayAndHoliday($date_from, $date_to, $emrArrayToString);
        $normalday_overtime = AttendanceHelper::overTimeAllday($date_from, $date_to, $emrArrayToString);
        // $totalOvertime = $totalOvertime1+$totalOvertime2 ;//array_merge($totalOvertime1,$totalOvertime2);
        $totalFullDayLeave = AttendanceHelper::totalFullDayLeave($date_from, $date_to, $emrArrayToString);
        $totalHalfDayLeave = AttendanceHelper::totalHalfDayLeave($date_from, $date_to, $emrArrayToString);
        $totalLate = AttendanceHelper::totalLate($date_from, $date_to, $emrArrayToString);
        $totalHalfDays = AttendanceHelper::totalHalfDays($date_from, $date_to, $emrArrayToString);
        $halfDaysAbsent = AttendanceHelper::halfDaysAbsent($date_from, $date_to, $emrArrayToString);
        $totalDeductionDays = AttendanceHelper::totalDeductionDays($emr_array, $totalAbsentDays, $totalLate, $totalHalfDays,$halfDaysAbsent, $totalFullDayLeave ,$totalHalfDayLeave );
        $totalLateDeduction = AttendanceHelper::totalLateDeduction($emr_array, $totalLate);

        $totalDeductionDays = array_key_exists($employee_id,$totalDeductionDays)? $totalDeductionDays[$employee_id] : 0 ;
        $totalHalfDayLeave = array_key_exists($employee_id,$totalHalfDayLeave)? $totalHalfDayLeave[$employee_id] : 0 ;
        $totalFullDayLeave = array_key_exists($employee_id,$totalFullDayLeave)? $totalFullDayLeave[$employee_id] : 0 ;
        $normalday_overtime = array_key_exists($employee_id,$normalday_overtime)? $normalday_overtime[$employee_id] : 0 ;
        $gez_overtime_array = array_key_exists($employee_id,$gez_overtime_array)? $gez_overtime_array[$employee_id] : 0 ;
        $totalAbsentDays = array_key_exists($employee_id,$totalAbsentDays)? $totalAbsentDays[$employee_id] : 0 ;
        $totalPresentDay = array_key_exists($employee_id,$totalPresentDay)? $totalPresentDay[$employee_id]  : 0 ;
        $totalLate = array_key_exists($employee_id,$totalLate)? $totalLate[$employee_id] : 0 ;
        $totalLateDeduction = array_key_exists($employee_id,$totalLateDeduction)? $totalLateDeduction[$employee_id] : 0 ;
        $totalHalfDays = array_key_exists($employee_id,$totalHalfDays)? $totalHalfDays[$employee_id] : 0 ;

        $totalLeaves = $totalFullDayLeave + $totalHalfDayLeave;
        $totalHolidays=isset($totalHoliday[$employee_id]) ? $totalHoliday[$employee_id] : 0;
        $limit = $totaldayofmonth - $totalLeaves - $totalHolidays;
        $totalLate = $totalLate - $totalHalfDayLeave;

        $present_days = $totalPresentDay - $totalHalfDayLeave ;// * 2) + $leavesfullday - $absent_days;
        $totalAbsentDays = $totalAbsentDays   - ($totalFullDayLeave);
        $totalAbsentDays < 0 ? $totalAbsentDays = 0 : '';

        return compact('date_from', 'date_to','limit','present_days','normalday_overtime','gez_overtime_array','totalLateDeduction', 'totalHoliday','totalAbsentDays','totalDeductionDays','totalLate','totalHalfDays','totalFullDayLeave','totalHalfDayLeave');
    }

    public function viewComparisonReport(){

        $month_from = Input::get('month_from');
        $month_to = Input::get('month_to');

        $explodeMonthYearFrom = explode('-',$month_from);
        $explodeMonthYearTo = explode('-',$month_to);

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeesFrom= Employee::whereYear('joining_date', '=', $explodeMonthYearFrom[0])
            ->whereMonth('joining_date', '=', $explodeMonthYearFrom[1])
            ->get();
        $employeesTo= Employee::whereYear('joining_date', '=', $explodeMonthYearTo[0])
            ->whereMonth('joining_date', '=', $explodeMonthYearTo[1])
            ->get();

        $allowance_from=Allowance::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1']])->get();
        $allowance_to=Allowance::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1']])->get();

        $deduction_from=Deduction::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1']])->get();
        $deduction_to=Deduction::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1']])->get();

        $loan_from=LoanRequest::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1'],['approval_status','=','2'],['loan_status','=','0']])->get();
        $loan_to=LoanRequest::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1'],['approval_status','=','2'],['loan_status','=','0']])->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewComparisonReport',compact('employeesFrom','employeesTo','month_from','month_to','allowance_from','allowance_to','deduction_from','deduction_to','loan_from','loan_to','explodeMonthYearFrom','explodeMonthYearTo'));
    }

    public function viewConcileReport(){

        $month_from = Input::get('month_from');
        $month_to = Input::get('month_to');

        $explodeMonthYearFrom = explode('-',$month_from);
        $explodeMonthYearTo = explode('-',$month_to);

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $payslipFrom = Payslip::where([["month","=",$explodeMonthYearFrom[1]],["year","=",$explodeMonthYearFrom[0]]])->get();
        $payslipTo = Payslip::where([["month","=",$explodeMonthYearTo[1]],["year","=",$explodeMonthYearTo[0]]])->get();

        $employeesFrom= Employee::whereYear('joining_date', '=', $explodeMonthYearFrom[0])
            ->whereMonth('joining_date', '=', $explodeMonthYearFrom[1])
            ->where('status','=','1')
            ->get();
        $employeesTo= Employee::whereYear('joining_date', '=', $explodeMonthYearTo[0])
            ->whereMonth('joining_date', '=', $explodeMonthYearTo[1])
            ->where('status','=','1')
            ->get();
        $employeesExitTo=EmployeeExit::whereYear('last_working_date', '=', $explodeMonthYearTo[0])
            ->whereMonth('last_working_date', '=', $explodeMonthYearTo[1])
            ->where('status','=','1')
            ->get();
        $employeesExitFrom=EmployeeExit::whereYear('last_working_date', '=', $explodeMonthYearFrom[0])
            ->whereMonth('last_working_date', '=', $explodeMonthYearFrom[1])
            ->where('status','=','1')
            ->get();
        $allowance_from=Allowance::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1']])->get();
        $allowance_to=Allowance::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1']])->get();

        $deduction_from=Deduction::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1']])->get();
        $deduction_to=Deduction::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1']])->get();

        $loan_from=LoanRequest::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1'],['approval_status','=','2'],['loan_status','=','0']])->get();
        $loan_to=LoanRequest::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1'],['approval_status','=','2'],['loan_status','=','0']])->get();

        $loan_paid_from=Payslip::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1'],['loan_amount_paid','!=',0]])->get();
        $loan_paid_to=Payslip::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1'],['loan_amount_paid','!=',0]])->get();

        $increment_from=EmployeePromotion::whereYear('promotion_date', '=', $explodeMonthYearFrom[0])
            ->whereMonth('promotion_date', '=', $explodeMonthYearFrom[1])
            ->where([['status','=','1'],['approval_status','=','1']])
            ->get();
        $increment_to=EmployeePromotion::whereYear('promotion_date', '=', $explodeMonthYearTo[0])
            ->whereMonth('promotion_date', '=', $explodeMonthYearTo[1])
            ->where([['status','=','1'],['approval_status','=','1']])
            ->get();


        $employees = Employee::select("designation_id", "emp_cnic", "emp_id", "emp_salary", "eobi_id", "tax_id", "emp_name", "date_of_birth","employee_project_id","emp_father_name","department_id","sub_department_id")
            ->orderBy('emp_id')
            ->where('status','=','1')
            ->get()->toArray();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewConcileReport',compact('increment_from','increment_to','employeesExitFrom','employeesExitTo','loan_paid_to','loan_paid_from','loan_to','loan_from','deduction_to','deduction_from','allowance_to','allowance_from','employeesTo','employeesFrom','payslipFrom','payslipTo','explodeMonthYearFrom','explodeMonthYearTo','employees'));
    }

    public function viewPayrollReport()
    {
        $getPayslipMonth = Input::get('payslip_month');
        $explodeMonthYear = explode('-',$getPayslipMonth);

        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $company_id = Input::get('m');
        $location_id = Input::get('location_id');
        $job_type = Input::get('job_type');
        
        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        $employees = array_filter($employee, function($value) use ($job_type) {
            if($value->employment_status_id == $job_type) {
                return $value;
            }
            elseif(empty($job_type))
            {
                return $value;
            }
        });

        CommonHelper::companyDatabaseConnection($company_id);
        $payroll_data = PayrollData::where([['month','=', $explodeMonthYear[1]],['year','=', $explodeMonthYear[0]],['status','=', 1]])->select('attendance_from','attendance_to');
        CommonHelper::reconnectMasterDatabase();

        $allowance_types = DB::table('allowance_types')->where([['status','=', 1]])->orderBy('id','asc')->get();
        $allowance_types_taxable_array = DB::table('allowance_types')->select('id')->where([['status','=', 1],['taxable','=', 1]])->orderBy('id','asc')->get();
        $allowance_types_taxable = [];
        foreach($allowance_types_taxable_array as $val){
            $allowance_types_taxable[] = $val->id;
        }

        $category = Cache::get('employee_category');
        $designations = Cache::get('designation');
        $locations = Cache::get('locations');

        return view('Hr.AjaxPages.viewPayrollReport',compact('company_id','employees','explodeMonthYear','getPayslipMonth','category','designations','payroll_data','locations','location_id','allowance_types'));
    }

    public function companyWisePayrollReport()
    {
        if(Auth::user()->acc_type == 'user') {die('Nice Trick @xx ');}
        $month_year = Input::get('month_year');
        $explodeMonthYear = explode('-',$month_year);
        $monthYearDay = $explodeMonthYear[0].'-'.$explodeMonthYear[1].'-01';
        if(Input::get('company_id') == 'all'):
            $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1]])->orderBy('order_by_no','asc')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1],['id','=',Input::get('company_id')]])->get()->toArray();
        endif;
        $designationArray = [];
        $DepartmentArray = [];
        $designation = Designation::where([['status', '=', 1]])->select('id','designation_name')->get();
        $Department = Department::where([['status', '=', 1]])->select('id','department_name')->get();
        foreach($designation as $key => $val):
            $designationArray[$val->id] = $val->designation_name;
        endforeach;
        foreach($Department as $key => $val):
            $DepartmentArray[$val->id] = $val->department_name;
        endforeach;
        return view('Hr.AjaxPages.companyWisePayrollReport',compact('companiesList','monthYearDay','explodeMonthYear', 'month_year','designationArray','DepartmentArray'));
    }

    public function viewRebateForm()
    {
        $emp_code = Input::get('emp_code');
        $month_year = explode('-',Input::get('month_year'));
        $rebate_type = Input::get('rebate_type');
        return view('Hr.AjaxPages.viewRebateForm',compact('emp_code','month_year','rebate_type'));
    }

    public function viewBankReportDetail()
    {

        $month_year = Input::get('month_year');
        $employee_id = Input::get('employee_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $location_id = Input::get('location_id');
        $explodeMonthYear = explode('-',$month_year);
        $bank=DB::table('banks')->where([['status','=','1'],['id','=',Input::get('bank')]])->first();
        $cheque_date=Input::get('cheque_date');
        $bank_id = Input::get('bank');
        $bank_name=DB::table('banks')->select('bank_name')->where('status',1)->where('id',$bank_id)->value('bank_name');
        $emr_array = [];
        $bankList = [$bank_name,$bank_id];
        $employees = HrHelper::getEmployees(Input::get('company_id'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach ($employees as $val) {
            $emr_array[] = $val->id;
        }

        if(count($emr_array) == 0 ) $emr_array = 0; 

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $payslip = DB::table('employee as e')
                ->select('e.id', 'e.emp_id', 'e.emp_name', 'e.gender_id', 'ebd.account_title', 'ebd.account_no', DB::raw('ROUND(p.net_salary) as net_salary'), 'p.gross_salaries')
                ->join('payslip as p', 'e.id', '=', 'p.employee_id')
                ->join('employee_bank_data as ebd', 'ebd.employee_id', '=', 'e.id')
                ->whereIn('e.id', $emr_array)
                ->whereIn('ebd.bank_name', $bankList)
                ->where('e.status', 1)
                ->where('p.status', 1)
                ->where('ebd.status', 1)
                ->where('p.month', $explodeMonthYear[1])
                ->where('p.year', $explodeMonthYear[0])
                ->orderByRaw('CAST(SUBSTRING(e.emp_id, 4) AS SIGNED)')->get();

        $total_net = DB::table('employee as e')
                ->select(DB::raw('ROUND(sum(p.net_salary)) as total_net'))
                ->join('payslip as p', 'e.id', '=', 'p.employee_id')
                ->join('employee_bank_data as ebd', 'ebd.employee_id', '=', 'e.id')
                ->whereIn('e.id', $emr_array)
                ->whereIn('ebd.bank_name', $bankList)
                ->where('e.status', 1)
                ->where('p.status', 1)
                ->where('ebd.status', 1)
                ->where('p.month', $explodeMonthYear[1])
                ->where('p.year', $explodeMonthYear[0])
                ->orderByRaw('CAST(SUBSTRING(e.emp_id, 4) AS SIGNED)')->get();

        $total_net = $total_net[0]->total_net; 
        
        CommonHelper::reconnectMasterDatabase();
        if($bank_id == 9)
        {
            return view('Hr.AjaxPages.viewBankReportDetailMeezan',compact('explodeMonthYear','payslip','total_net', 'month_year','bank','cheque_date'));

        }
        else
        {

            return view('Hr.AjaxPages.viewBankReportDetail',compact('explodeMonthYear','payslip','total_net', 'month_year','bank','cheque_date'));
        }
    }
    public function viewBonusBankReportDetail()
    {
        $month_year = Input::get('month_year');
        $cheque_date = Input::get('cheque_date');
        $explodeMonthYear = explode('-',$month_year);
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $employee_id = Input::get('employee_id');
        $bank=DB::table('banks')->where([['status','=','1'],['id','=',Input::get('bank')]])->first();

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $bonus = DB::table('bonus_issue')
            ->join('employee', 'employee.id', '=', 'bonus_issue.employee_id')
            ->join('employee_bank_data', 'employee_bank_data.emp_id', '=', 'employee.emp_id')
            ->select('employee.emp_name','employee.gender_id', 'employee_bank_data.account_title', 'employee_bank_data.account_no','bonus_issue.total_net')
            ->where([["employee_bank_data.bank_name","=",$bank->id],["bonus_issue.bonus_month","=",$explodeMonthYear[1]],["bonus_issue.bonus_pay","=",2],["bonus_issue.bonus_year","=",$explodeMonthYear[0]],['employee_bank_data.account_no','!=',''],['employee.status','=','1']]);


        $total_net = DB::table('bonus_issue')
            ->join('employee', 'employee.id', '=', 'bonus_issue.employee_id')
            ->join('employee_bank_data', 'employee_bank_data.emp_id', '=', 'employee.emp_id')
            ->select(DB::raw('SUM(bonus_issue.total_net) As total_net'))
            ->where([["employee_bank_data.bank_name","=",$bank->id],["bonus_issue.bonus_month","=",$explodeMonthYear[1]],["bonus_issue.bonus_pay","=",2],["bonus_issue.bonus_year","=",$explodeMonthYear[0]],['employee_bank_data.account_no','!=',''],['employee.status','=','1']])->get();




        if($employee_id == 'all'){
            if(!empty($department_id)){
                $bonus->whereIn('employee.department_id',$department_id);
                $total_net->whereIn('employee.department_id',$department_id);
            }
            if(!empty($sub_department_id)){
                $bonus->whereIn('employee.sub_department_id',$sub_department_id);
                $total_net->whereIn('employee.sub_department_id',$sub_department_id);
            }
            if(!empty($project_id)){
                $bonus->whereIn('employee.project_id',$project_id);
                $total_net->whereIn('employee.project_id',$project_id);
            }
            if(!empty($category_id)){
                $bonus->whereIn('employee.category_id',$category_id);
                $total_net->whereIn('employee.category_id',$category_id);
            }
        }else{
            $bonus->where('employee.id',$employee_id);
            $total_net->where('employee.id',$employee_id);
        }

        $total_net = $total_net[0]->total_net;


        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewBonusBankReportDetail',compact('total_net','bonus','department_id','sub_department_id','project_id','category_id','employee_id','explodeMonthYear', 'bank','month_year','cheque_date'));
    }


    public function viewEmployeeTrainingForm()
    {
        $getEmployee = Input::get('emp_id');
        $sub_department = Input::get('sub_department');
        $department_id = Input::get('department_id');
        $training_id = Input::get('training_id');
        $show_all=Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if($show_all=='show_all'){
            $employees=Employee::select('emp_id','emp_name','designation_id')->where([['status','=',1]])->get()->toArray();
        }
        elseif($sub_department!='' && $getEmployee=='all'){
            $employees=Employee::select('emp_id','emp_name','designation_id')->where([['status','=',1],['emp_department_id','=',$department_id],['emp_sub_department_id','=',$sub_department]])->get()->toArray();
        }
        elseif($sub_department=='' && $getEmployee=='all'){
            $employees=Employee::select('emp_id','emp_name','designation_id')->where([['status','=',1],['emp_department_id','=',$department_id]])->get()->toArray();
        }
        else{
            $employees=Employee::select('emp_id','emp_name','designation_id')->where([['status','=',1],['emp_id','=',$getEmployee]])->get()->toArray();
        }

        $training_data=DB::table('trainings')->where([['status','=',1],['id','=',$training_id]])->first();

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeTrainingForm',compact('employees','training_data'));
    }

    public function viewEmployeePfForm()
    {
        $employee_id = Input::get('employee_id');
        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $project_id=Input::get('project_id');
        $category_id=Input::get('category_id');

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $location_id = Input::get('location_id');
        $emr_array = [];

        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val){
            $emr_array[] = $val->id;
        }
        $emr_array = implode(',',$emr_array);
        
        if(empty($emr_array)) $emr_array = 0; 


        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $query_string_second_part[] = " AND id in ($emr_array)";

        $query_string_second_part[] = " AND status = 1 ";
        // $query_string_second_part[] = " AND provident_fund_id != '' ";
        $query_string_First_Part= "SELECT id,designation_id,last_working_date,confirmation_date,cnic,department_id,sub_department_id,grade_id,
                joining_date,eobi_id,emp_name,emp_father_name,
                salary,emp_id FROM employee WHERE";
        $query_string_third_part = ' ORDER BY emp_id';
        $query_string_second_part= implode(" ", $query_string_second_part);
        $query_string_second_part=  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string=$query_string_First_Part.$query_string_second_part.$query_string_third_part;
        $employees = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();


        return view('Hr.AjaxPages.viewEmployeePfForm',compact('employees'));
    }


    public function fetchEmployeesAttendanceReport()
    {
        ini_set('max_execution_time', '600');
        //$getEmployee = Input::get('emp_id');
        $from= Input::get('from');
        $to= Input::get('to');
        $show_all = Input::get('show_all');
        $explodeMonthYear = explode('-',$from);
        $monthYearDay = $from;
        $startDate = Input::get('att_month').'-1';
        $endDate = date("Y-m-t", strtotime($startDate));
        $employee_id = Input::get('employee_id');
        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $project_id=Input::get('project_id');
        $category_id=Input::get('category_id');

        $location_id = Input::get('location_id');
        $emr_array = [];

        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val){
            $emr_array[] = $val->id;
        }

        return view('Hr.AjaxPages.fetchEmployeesAttendanceReport',compact('explodeMonthYear',
            'department_id','from','to','employee_id','emr_array','startDate','endDate'));

    }

    public function viewEmployeePayrollForm()
    {
        $getEmployee = Input::get('employee_id');
        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $project_id=Input::get('project_id');
        $category_id=Input::get('category_id');
        $getPayslipMonth = Input::get('payslip_month');
        $explodeMonthYear = explode('-',$getPayslipMonth);
        $startDate = Input::get('payslip_month').'-1';
        $endDate = date("Y-m-t", strtotime($startDate));
        $location_id = Input::get('location_id');
        $job_type = Input::get('job_type');
        $company_id = Input::get('m');
        $emr_array=[];
        $emp_location_array = [];
        $totalWorkingHour = [];
        $lastDateOfMonth = date("Y-m-t", strtotime($startDate));

        $designations = Cache::get('designation');
        $category = Cache::get('employee_category');

        $employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $getEmployee);
        $employees = array_filter($employees, function($value) use ($job_type) {
            // condition which makes a result belong to variable

            if($value->employment_status_id == $job_type) {
                return $value;
            }
            elseif(empty($job_type)) {
                return $value;
            }
        });
        
        foreach($employees as $val){
            $emr_array[] = $val->id;
            $emp_location_array[$val->id] = $val->location_id;
        }
        CommonHelper::companyDatabaseConnection($company_id);

        $tf_allowances = Allowance::where([['status','=', 1],['allowance_type_id','=', 6]])->get();
        $tf_allowance_array = [];
        foreach($tf_allowances as $val){
            $tf_allowance_array[$val->employee_id] = $val->allowance_amount;
        }

        $tf_allowances_paid = TFAllowance::where([['status','=', 1],['month','=', $explodeMonthYear[1]],['year','=', $explodeMonthYear[0]]])
            ->whereIn('employee_id', $emr_array)
            ->select('employee_id', \DB::raw('SUM(amount) AS tf_amount'))
            ->groupBy('employee_id')  // Group by employee_id to use SUM() correctly
            ->get();
        $tf_allowance_paid_array = [];
        foreach($tf_allowances_paid as $val){
            $tf_allowance_paid_array[$val->employee_id] = $val->tf_amount;
        }
        CommonHelper::reconnectMasterDatabase();
        $emr_array = implode(',',$emr_array);

        if(empty($emr_array)) $emr_array = 0; 

        $empTotalWorkingHourQuery = "SELECT 
                                    CASE 
                                        WHEN whp.end_working_hours_time < whp.start_working_hours_time 
                                        THEN 
                                            ABS(ROUND(
                                                (TIME_TO_SEC(TIMEDIFF('23:59:59', whp.start_working_hours_time)) + TIME_TO_SEC(TIMEDIFF(whp.end_working_hours_time, '00:00:00'))) / 3600,
                                                0
                                            ))
                                        ELSE
                                            ABS(ROUND(
                                                TIME_TO_SEC(TIMEDIFF(whp.end_working_hours_time, whp.start_working_hours_time)) / 3600,
                                                0
                                            ))
                                    END AS totalHour,
                                    whp.start_working_hours_time,
                                    whp.end_working_hours_time,
                                    awhp.employee_id
                                FROM
                                    ".CONST_COMPANY_DB.".assign_working_hour_policies awhp
                                    INNER JOIN ".CONST_MASTER_DB.".working_hours_policy whp ON awhp.working_hours_policy_id = whp.id
                                WHERE awhp.employee_id IN ($emr_array)";
   
        $empTotalWorkingHourQueryData = DB::select(DB::raw($empTotalWorkingHourQuery));
        foreach ($empTotalWorkingHourQueryData as $key => $value) {
            $totalWorkingHour[$value->employee_id] = $value->totalHour;
        }

        $allowance_types = DB::table('allowance_types')->where([['status','=', 1],['id','!=', 6]])->get();
        $allowance_types_taxable_array = DB::table('allowance_types')->select('id')->where([['status','=', 1],['id','!=', 6],['taxable','=', 1]])->get();
        $allowance_types_taxable = [];
        foreach($allowance_types_taxable_array as $val){
            $allowance_types_taxable[] = $val->id;
        }

        $tax_id = Tax::where([['status','=', 1]])->select('id')->value('id');
        $tax = TaxSlabs::where([['status','=','1'],['tax_id', '=', $tax_id]])->get();
        $provident_fund_data = DB::table('provident_fund')->select('id','pf_mode','amount_percent')
            ->where([['status','=',1]])->orderBy('id','desc');

        return view('Hr.AjaxPages.viewEmployeePayrollForm',compact('lastDateOfMonth','category','designations','tax','provident_fund_data','employees','explodeMonthYear','company_id','totalWorkingHour',
            'department_id','getPayslipMonth','emp_location_array','getEmployee','startDate','endDate','explodeMonthYear','allowance_types','allowance_types_taxable','getPayslipMonth','tf_allowance_array','tf_allowance_paid_array'));
    }

    public function viewEmployeePayrollList(){

        if(Auth::user()->acc_type == 'user') {die('Nice Trick @xx ');}
        $department_id = Input::get('department_id');
        $getPayslipMonth = Input::get('payslip_month');
        $explodeMonthYear = explode('-',$getPayslipMonth);
        $getEmployee = Input::get('emp_id');
        $explodePaysilpMonth = explode('-',$getPayslipMonth);
        $sub_department_id = Input::get('sub_department_id');

        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department_id,Input::get('m'));

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if(Input::get('show_all') == 'show_all'){

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'joining_date','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->where([['status', '=', '1']])->orderBy('emp_id')->get();
        }
        elseif($getEmployee == 'all' && $sub_department_id == '0'){

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'joining_date','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereIn('emp_id',$all_emp_id)
                ->where([['status', '=', '1']])
                ->orderBy('emp_id')
                ->get();
        }
        elseif($getEmployee == 'all' &&  $sub_department_id != '0'){

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'joining_date','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereIn('emp_id',$all_emp_id)
                ->where([['status', '=', '1']])
                ->orderBy('emp_id')
                ->get();

        }
        else{

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'joining_date','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->where([['emp_id','=',$getEmployee],['status', '=', '1']])->orderBy('emp_id')->get();
        }
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeePayrollList',compact('employees','explodeMonthYear', 'operation_rights2','subdepartment','departments','department_id','getPayslipMonth','getEmployee','explodePaysilpMonth'));
    }

    public function viewMySalarySheetDetail(Request $request)
    {
        $category = Cache::get('employee_category');
        $designations = Cache::get('designation');
        if (Hash::check(Input::get('passwordSecret'), Auth::user()->password)) {

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $acc_emp_id = Auth()->user()->employee_id;

            $employees  = DB::table('payslip')
                ->join('employee', 'employee.id', '=', 'payslip.employee_id')
                ->select('payslip.*', 'employee.emp_name', 'employee.sub_department_id',
                    'employee.department_id','employee.salary','employee.cnic')
                ->where('payslip.employee_id',  $acc_emp_id )->get();

            CommonHelper::reconnectMasterDatabase();

            return view('Hr.AjaxPages.viewMySalarySheetDetail',compact('employees','category','designations'));
        }
        else{
            echo 'err';
        }
    }

    public function viewEmployeePayslips()
    {
        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $project_id=Input::get('project_id');
        $category_id=Input::get('category_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $month_year  = explode("-",Input::get('month_year'));
        $emr_array=[];

        $employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val):
            $emr_array[] = $val->id;
        endforeach;

        $employees = Cache::get('employee');

        CommonHelper::companyDatabaseConnection($company_id);

        $payslipData = DB::table('payslip')
            ->where([['month','=',$month_year[1]],['year','=',$month_year[0]],['status','=',1]])
            ->whereIn('employee_id', $emr_array);

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePayslips',compact('payslipData','month_year','employees','company_id'));
    }

    public function viewBonusEmployeePayslips()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year  = explode("-",Input::get('month_year'));
        $emp_id = Input::get('employee_id');
        if($emp_id == 'all'):
            $payslipBonusData = DB::table('bonus_issue')->where([['bonus_month','=',$month_year[1]],['bonus_year','=',$month_year[0]],['status','=',1],['bonus_pay','=',2]]);
        else:
            $payslipBonusData = DB::table('bonus_issue')->where([['employee_id', '=', $emp_id],['bonus_month','=',$month_year[1]],['bonus_year','=',$month_year[0]],['status','=',1],['bonus_pay','=',2]]);
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewBonusEmployeePayslips',compact('payslipBonusData','month_year'));
    }


    public function viewUserAccountDetail(){
        $currentDate = date('Y-m-d');
        $id = $_GET['id'];
        $CompanyId 	= $_GET['m'];

        CommonHelper::reconnectMasterDatabase($CompanyId);

        $user_account_detail = User::where([['id','=',$id]])->first();

        return view('Hr.AjaxPages.viewUserAccountDetail',compact('user_account_detail'));
    }

    public function viewEmployeeDetail($id, $company_id)
    {
        CommonHelper::companyDatabaseConnection($company_id);
        $employee_detail = DB::selectOne(DB::raw("SELECT e.*,(SELECT employee_promotion.designation_id FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.designation_id != ''
          ORDER BY employee_promotion.id DESC LIMIT 1) AS promoted_designation,(SELECT employee_promotion.salary FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.salary != ''
          ORDER BY employee_promotion.id DESC LIMIT 1) AS promoted_salary FROM employee AS e WHERE e.id = $id"));

        $employee_family_detail = EmployeeFamilyData::where([['employee_id','=',$id]]);
        $employee_bank_detail = EmployeeBankData::where([['employee_id','=',$id]]);
        $employee_educational_detail = EmployeeEducationalData::where([['employee_id','=',$id]]);
        $employee_language_proficiency = EmployeeLanguageProficiency::where([['employee_id','=',$id]]);
        $employee_health_data = EmployeeHealthData::where([['employee_id','=',$id]]);
        $employee_activity_data = EmployeeActivityData::where([['employee_id','=',$id]]);
        $employee_work_experience = EmployeeWorkExperience::where([['employee_id','=',$id]]);
        $employee_reference_data = EmployeeReferenceData::where([['employee_id','=',$id]]);
        $employee_kins_data = EmployeeKinsData::where([['employee_id','=',$id]]);
        $employee_relatives_data = EmployeeRelativesData::where([['employee_id','=',$id]]);
        $employee_other_details = EmployeeOtherDetails::where([['employee_id','=',$id]]);
        $employee_documents = EmployeeDocuments::where([['employee_id','=',$id],['status','=',1]]);
        $employee_equipment_assigned = EmployeeEquipments::where([['employee_id','=',$id],['status','=',1]]);

        $employee_location = EmployeeMultipleLocations::where([['status', '=', 1], ['employee_id', '=', $id]])->select('location_id','project_id')->first();

        $login_credentials = '';
        if($employee_detail->can_login == 'yes'):
            $login_credentials = DB::Table('users')->select('acc_type')->where([['company_id', '=', $company_id],['emp_id', '=', $employee_detail->id]]);
        endif;

        CommonHelper::reconnectMasterDatabase();

        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $designations = Cache::get('designation');
        $projects = Cache::get('employee_projects');
        $category = Cache::get('employee_category');
        $locations = Cache::get('locations');
        $leaves_policy = Cache::get('leaves_policy');
        $job_type = Cache::get('job_type');
        $marital_status = Cache::get('marital_status');
        $eobi = Cache::get('eobi');
        $employee_equipment = Cache::get('employee_equipments');
        $grade_type = Cache::get('grades');
        $degree_type = Cache::get('degree_type');
        $employee = Cache::get('employee');

        return view('Hr.AjaxPages.viewEmployeeDetail'
            , compact('employee_detail', 'employee_family_detail', 'employee_bank_detail', 'employee_educational_detail',
                'employee_language_proficiency', 'employee_health_data', 'employee_activity_data', 'employee_work_experience',
                'employee_reference_data', 'employee_kins_data', 'employee_relatives_data', 'employee_other_details',
                'employee_documents', 'employee_equipment_assigned','employee_location',
                'login_credentials', 'departments', 'sub_departments', 'designations', 'projects', 'category', 'locations', 'leaves_policy',
                'job_type', 'marital_status', 'eobi', 'employee_equipment', 'grade_type', 'degree_type', 'employee'));
    }

    public function viewHiringRequestDetail(){

        $array[1] ='<span class="label label-warning">Pending</span>';
        $array[2] ='<span class="label label-success">Approved</span>';
        $array[3] ='<span class="label label-danger">Rejected</span>';
        $array1[1] ="<span class='label label-success'>Active</span>";
        $array1[2] ="<span class='label label-danger'>Deleted</span>";

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hiringRequestDetail = RequestHiring::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $data['hiringRequestDetail'] = $hiringRequestDetail;
        $data['status'] 		 = $array1[$hiringRequestDetail->status];
        $data['approval_status'] = $array[$hiringRequestDetail->ApprovalStatus];
        return view('Hr.AjaxPages.viewHiringRequestDetail',$data);

        /*<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.innovative-net.com/&display=popup" target="_blank"> share this facebook </a>*/

    }

    public function viewLeavePolicyDetail()
    {
        $leaves_policy_id = Input::get('id');
        //CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $leaves_policy = LeavesPolicy::where([['id','=',Input::get('id')]])->first();
//        $leavesData =   LeavesData::where([['leaves_policy_id','=',Input::get('id')]])->get();

        $leaves_policy = Cache::get('leaves_policy');
        $leaves_data = Cache::get('leaves_data');
        $leave_type = Cache::get('leave_type');

        return view('Hr.AjaxPages.viewLeavePolicyDetail',compact('leaves_policy','leaves_data','leave_type','leaves_policy_id'));

    }


    public function viewCarPolicyCriteria()
    {
        if(Input::get('sub_department_id') == 'all'):

            $allsubDeparments = SubDepartment::select('id','sub_department_name','department_id')->where([['status','=','1'],['company_id','=',Input::get('m')]])->get();
        else:
            $allsubDeparments = SubDepartment::select('id','sub_department_name','department_id')->where([['id','=',Input::get('sub_department_id')],['status','=','1'],['company_id','=',Input::get('m')]])->get();
        endif;

        return view('Hr.AjaxPages.viewCarPolicyCriteria',compact('allsubDeparments'));
    }

    public function  viewCarPolicy()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicyData = CarPolicy::where([['id','=',Input::get('id')],['status','=','1']])->first();
        return view('Hr.AjaxPages.viewCarPolicy',compact('carPolicyData'));
    }

    public function viewLoanRequestDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loanRequest   = LoanRequest::where([['id','=',Input::get('id')]])->first();
        $loan_top_up = LoanTopUp::where([['loan_id', '=', Input::get('id')],['status', '=', 1]])->sum('loan_top_up_amount');
        $paid_amount = DB::table("payslip")
            ->select(DB::raw("SUM(loan_amount_paid) as paid_amount"))
            ->where([['employee_id','=', $loanRequest->employee_id],['loan_id' ,'=', Input::get('id')],['status','=', 1]])
            ->first();

        $loan_Detail = DB::table('payslip')
            ->select('loan_amount_paid','date','month','year')
            ->where([['loan_id','=',Input::get('id')],['employee_id','=',$loanRequest->employee_id],['status','=', 1]])
            ->get();
        $loan_adjusted_amount = LoanAdjustment::where([['loan_id','=',Input::get('id')]]);

        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');
        return view('Hr.AjaxPages.viewLoanRequestDetail',compact('loanRequest','loan_top_up','loan_adjusted_amount','employees','paid_amount', 'operation_rights2','loan_Detail'));
    }

    public function viewLoanReportDetail()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $year = explode('-',Input::get('year'));
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $location_id = Input::get('location_id');
        $month_year = date('Y-m-d');

        $employees = HrHelper::getEmployees($company_id,$department_id,$sub_department_id,$project_id,$location_id,$category_id,$employee_id);
        $left_employees = HrHelper::getLeftEmployees($company_id,$department_id,$sub_department_id,$project_id,$location_id,$category_id,$employee_id);
        CommonHelper::companyDatabaseConnection($company_id);
        $employees = array_merge($employees, $left_employees);
        $emr_array = [];
        foreach ($employees as $val):
            $emr_array[] = $val->id;
        endforeach;
        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0;

        $loan_request = DB::select(DB::raw("SELECT lr.*
          , (SELECT SUM(loan_top_up_amount) FROM loan_top_up AS lt WHERE lt.loan_id = lr.id AND lt.status = 1) AS loan_top_up
          , (SELECT SUM(amount) FROM loan_adjustment AS la WHERE la.loan_id = lr.id AND la.status = 1) AS loan_adjustment_amount
          , (SELECT SUM(loan_amount_paid) FROM payslip AS p WHERE p.employee_id = lr.employee_id AND p.loan_id = lr.id AND p.status = 1) AS paid_amount
          FROM loan_request AS lr WHERE lr.employee_id IN ($emr_array) AND lr.status = 1 ORDER BY lr.id "));

        $loan_paid = DB::select(DB::raw("SELECT p.loan_amount_paid, p.month, p.year, p.loan_id FROM payslip AS p INNER JOIN loan_request AS lr
            ON p.employee_id = lr.employee_id AND p.loan_id = lr.id WHERE p.year BETWEEN '$year[0]' AND '$year[1]' and CONCAT(p.year,p.month) >= '$year[0]07' and CONCAT(p.year,p.month) <= '$year[1]06' AND lr.employee_id IN ($emr_array) AND lr.status = 1 AND p.status = 1 ORDER BY lr.id"));
    
   
        $loan_paid_data = [];
        foreach ($loan_paid as $val):
            $loan_paid_data[$val->loan_id][$val->month] = $val;
        endforeach;

        $query = "SELECT e.id, e.emp_id, e.emp_name, e.joining_date,
            IFNULL((SELECT ep.salary FROM employee_promotion AS ep WHERE
            ep.employee_id = e.id AND ep.status = 1 AND ep.salary != '' AND ep.approval_status = 2
            ORDER BY ep.id DESC LIMIT 1),e.salary) AS salary,
            TIMESTAMPDIFF(YEAR, joining_date, '$month_year') AS years,
            TIMESTAMPDIFF(MONTH, joining_date, '$month_year')%12 AS months
            FROM employee AS e WHERE e.id IN ($emr_array)  ORDER BY e.id ASC ";
        $employee_gratuity = DB::select(DB::raw($query));

        $gratuity = [];
        foreach ($employee_gratuity as $val):
            $gratuity[$val->id][] = $val;
        endforeach;
        $employees = Cache::get('employee');
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.loanRequests.viewLoanReportDetail',compact('loan_request','loan_paid_data','gratuity','employees','operation_rights2'));
    }

    public function createEmployeeJoiningLetter(Request $request)
    {
        $department_name=Department::where('id',$request->department_id)->first()->department_name;
        $sub_department_name=SubDepartment::where('id',$request->sub_department_id)->first()->sub_department_name;
        $designation_name=Designation::where('id',$request->designation_id)->first()->designation_name;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_name=Employee::where('emp_id',$request->emp_id)->first()->emp_name;
        $date=$request->date;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.createEmployeeJoiningLetter',compact('emp_name','date','designation_name','sub_department_name','department_name'));
    }

    public function viewTaxCriteria()
    {
        if(Input::get('sub_department_id') == 'all'):

            $allsubDeparments = SubDepartment::select('id','sub_department_name','department_id')->where([['status','=','1'],['company_id','=',Input::get('m')]])->get();
        else:
            $allsubDeparments = SubDepartment::select('id','sub_department_name','department_id')->where([['id','=',Input::get('sub_department_id')],['status','=','1'],['company_id','=',Input::get('m')]])->get();
        endif;
        return view('Hr.AjaxPages.viewTaxCriteria',compact('allsubDeparments'));

    }

    public function getEmployeeDetail(){
        $emp=[];
        $emp_id=Input::get('emp_id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_salary=Employee::where('emp_id',$emp_id)->where('status',1)->first()->emp_salary;
        $designation_id=Employee::where('emp_id',$emp_id)->where('status',1)->first()->designation_id;
        CommonHelper::reconnectMasterDatabase();
        $designation_name=Designation::where('id',$designation_id)->first()->designation_name;
        $basic_salary = round($emp_salary / 3 * 2);
        $utility = round($basic_salary / 100 * 10);
        $add_basic_add_medical = $basic_salary + $utility;
        $hr = round($emp_salary - $add_basic_add_medical);
        $res['hr']=$hr;
        $res['utility']=$utility;
        $res['emp_salary']=$emp_salary;
        $res['basic_salary']=$basic_salary;
        $res['designation_name']=$designation_name;
        return response($res);

    }

    public function viewTaxesDetail()
    {
        $tax_slabs = DB::table('tax_slabs')->where([['tax_id', '=', Input::get('id')]])->orderBy('salary_range_from','asc')->get();
        return view('Hr.AjaxPages.viewTaxesDetail',compact('tax_slabs'));
    }

    public function viewTax()
    {
        $tax = Tax::where([['id','=',Input::get('id')],['company_id','=',Input::get('m')]])->first();
        return view('Hr.AjaxPages.viewTax',compact('tax'));
    }

    public function viewEmployeesBonus()
    {
        $bonus_month=Input::get('bonus_month_year');
        $monthYear=explode('-',Input::get('bonus_month_year'));
        $monthYearDay=$monthYear[0].'-'.$monthYear[1].'-15';
        $bonus_pay=Input::get('bonus_pay');
        $month=$monthYear[1];
        $year=$monthYear[0];
        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $project_id=Input::get('project_id');
        $category_id=Input::get('category_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $emr_array = [];

        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val){
            $emr_array[] = $val->id;
        }

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $all_employees = Employee::select('id','salary','emp_id','emp_name','joining_date','project_id')->whereIn('id',  $emr_array)->where('status',1)->get();



        $get_percent = Bonus::where([['id','=',Input::get('bonus_id')]])->first();

        $month_year = explode('-',Input::get('bonus_month_year'));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeesBonus',compact('all_employees','year','month','bonus_pay','get_percent','month_year','monthYearDay','bonus_month'));
    }

    public function viewEmployeesBonusReport(Request $request)
    {

        $department_id = $request->department_id;
        $sub_department_id = $request->sub_department_id;
        $location_id = $request->location_id;
        $category_id = $request->category_id;
        $employee_id = $request->employee_id;
        $project_id = $request->project_id;
        $month_year =  $request->bonus_month_year ? explode('-',$request->bonus_month_year):'';

        $emr_array = [];

        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val){
            $emr_array[] = $val->id;
        }

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $bonus = DB::Table('bonus_issue')
            ->join('employee','bonus_issue.employee_id','=','employee.id')
            ->select('bonus_issue.id','bonus_issue.bonus_year','bonus_issue.bonus_month','bonus_issue.bonus_tax','bonus_issue.total_net','bonus_issue.bonus_amount','bonus_issue.bonus_pay','employee.emp_name','employee.emp_id','employee.id as employee_id')
            ->where('bonus_issue.bonus_year',$month_year[0])
            ->where('bonus_issue.bonus_month',$month_year[1])
            ->where([['bonus_issue.bonus_amount','!=',0]])
            ->where('bonus_issue.bonus_status',1)
            ->where('bonus_issue.status',1)
            ->whereIn('bonus_issue.employee_id',$emr_array);

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeesBonusReport',compact('bonus'));
    }

//    public function viewLeaveApplicationDetail()
//    {
//        $leave_day_type = Input::get('leave_day_type');
//
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $emp = Employee::select('id','leaves_policy_id','designation_id')->where('emp_id','=',Auth::user()->emp_id)->first();
//        CommonHelper::reconnectMasterDatabase();
//
//        if(Input::get('leave_day_type') == 1):
//
//            $leave_application_data = DB::table('leave_application')
//                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
//                ->select('leave_application.leave_address','leave_application.emp_id','leave_application.approval_status','leave_application.reason','leave_application_data.no_of_days','leave_application_data.date','leave_application_data.from_date','leave_application_data.to_date')
//                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
//                ->first();
//
//            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
//            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];
//
//
//        elseif(Input::get('leave_day_type') == 2):
//
//            $leave_application_data = DB::table('leave_application')
//                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
//                ->select('leave_application.leave_address','leave_application.emp_id','leave_application.approval_status','leave_application.reason','leave_application_data.first_second_half','leave_application_data.date','leave_application_data.first_second_half_date')
//                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
//                ->first();
//
//            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
//            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];
//
//        else:
//            $leave_application_data = DB::table('leave_application')
//                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
//                ->select('leave_application.leave_address','leave_application.emp_id','leave_application.approval_status','leave_application.reason','leave_application_data.short_leave_time_from','leave_application_data.short_leave_time_to','leave_application_data.date','leave_application_data.short_leave_date')
//                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
//                ->first();
//
//            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
//            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];
//
//        endif;
//
//
//        $approval_array[1] = '<span class="label label-warning">Pending</span>';
//        $approval_array[2] = '<span class="label label-success">Approved</span>';
//        $approval_array[3] = '<span class="label label-danger">Rejected</span>';
//
//        $approval_status = $approval_array[$leave_application_data->approval_status];
//
//        $leaves_policy = DB::table('leaves_policy')
//            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
//            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//            ->select('leaves_policy.*','leaves_data.*')
//            ->where([['leaves_policy.id','=',$emp->leaves_policy_id]])
//            ->get();
//
//        $total_leaves = DB::table("leaves_data")
//            ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
//            ->where([['leaves_policy_id' ,'=', $leaves_policy[0]->leaves_policy_id]])
//            ->first();
//
//
//        $taken_leaves = DB::table("leave_application_data")
//            ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
//            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
//            ->where([['leave_application.emp_id', '=', Input::get('user_id')],['leave_application.status', '=', '1'],
//                ['leave_application.approval_status', '=', '2']])
//            ->first();
//
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $emp_data = Employee::select('emp_name', 'emp_sub_department_id', 'designation_id', 'emp_id')->where([['id', '=', $emp->id]])->orderBy('id')->first();
//        CommonHelper::reconnectMasterDatabase();
//
//        $designation_name = Designation::where([['id','=',$emp->designation_id]])->value('designation_name');
//        $getCurrentLeavePolicyYear = date('Y',strtotime($leaves_policy[0]->policy_date_from));
//        $date = strtotime($getCurrentLeavePolicyYear.' -1 year');
//        $getPreviousLeavePolicyYear = date('Y', $date);
//        $getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear.'%');
//        $getPreviousUsedAnnualLeavesBalance = 0;
//        $getPreviousUsedCasualLeavesBalance = 0;
//        if($getPreviousLeavePolicy->count() > 0 ):
//            // print_r($getPreviousLeavePolicyId->first()->id);
//            $getPreviousLeavePolicyId=$getPreviousLeavePolicy->first();
//
//            $getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',1],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
//            $getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',3],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
//
//            $getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
//                ->select(DB::raw("SUM(no_of_days) as no_of_days"))
//                ->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','1']])
//                ->first();
//            $getPreviousUsedCasualLeaves = DB::table("leave_application_data")
//                ->select(DB::raw("SUM(no_of_days) as no_of_days"))
//                ->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','3']])
//                ->first();
//
//            $getPreviousUsedAnnualLeavesBalance =  $getPreviousAnnualLeaves-$getPreviousUsedAnnualLeaves->no_of_days;
//            $getPreviousUsedCasualLeavesBalance =$getPreviousCasualLeaves-$getPreviousUsedCasualLeaves->no_of_days;
//
//        endif;
//
//        $data['getPreviousUsedAnnualLeavesBalance']=$getPreviousUsedAnnualLeavesBalance;
//        $data['getPreviousUsedCasualLeavesBalance']=$getPreviousUsedCasualLeavesBalance;
//        $data['total_leaves']=   		$total_leaves;
//        $data['taken_leaves']= 		    $taken_leaves;
//        $data['designation_name']=		$designation_name;
//        $data['leave_day_type']=	    $leave_day_type;
//        $data['leave_application_data']=$leave_application_data;
//        $data['approval_status'] = 		$approval_status;
//
//        $data['leave_type_name']        = Input::get('leave_type_name');
//        $data['leave_day_type_label'] = $leave_day_type_label;
//        $data['leaves_policy'] =        $leaves_policy;
//        return view('Hr.AjaxPages.viewLeaveApplicationDetail')->with($data);
//    }

    public function viewLeaveApplicationRequestDetail()
    {
        $id = Input::get('id');

        $leave_application_data = DB::table('leave_application AS la')
            ->join('leave_application_data AS lad', 'la.id', '=', 'lad.leave_application_id')
            ->select('la.*','lad.no_of_days','lad.from_date','lad.to_date','lad.first_second_half','lad.first_second_half_date')
            ->where([['la.id', '=', $id]])
            ->first();

        $employees = Cache::get('employee');
        $employee = $employees[$leave_application_data->employee_id];
        $leave_day_type = Input::get('leave_day_type');

        $leaves = HrHelper::viewLeaveBalances($employee->id,$employee->leaves_policy_id, 2);

        $leave_day_type_arr = [1 => 'Full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
        $leave_half_day = ['first_half' => 'First Half', 'second_half' => 'Second Half'];
        $leave_day_type_label = $leave_day_type_arr[$leave_application_data->leave_day_type];

        $leaves_policy = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $employee->leaves_policy_id]])
            ->orderBy('leaves_data.leave_type_id')->get();

        $leave_type = Cache::get('leave_type');

        return view('Hr.LeaveApplication.viewLeaveApplicationRequestDetail', compact('leave_type', 'leave_half_day','leave_day_type',
            'leave_application_data', 'employee', 'leave_day_type_label', 'leaves_policy','leaves'));
    }


    public function filterAssignWorkingHoursPolicyList()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $working_hours_policy_id = Input::get('working_hours_policy_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $fixed = Input::get('fixed');
        $explode_from_date = explode('-',$from_date);
        $explode_to_date = explode('-',$to_date);
        $location_id = Input::get('location_id');
        $emr_array = [];

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));

        $employees = HrHelper::getEmployees(Input::get('company_id'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val){
            $emr_array[] = $val->id;
        }

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $query = DB::table('assign_working_hour_policies')
            ->join('employee','employee.id','=','assign_working_hour_policies.employee_id')
            ->select('assign_working_hour_policies.id','employee.emp_id','employee.emp_name','employee.id as employee_id',
                'assign_working_hour_policies.from_date','assign_working_hour_policies.to_date','assign_working_hour_policies.working_hours_policy_id',
                'assign_working_hour_policies.fixed')->whereIn('assign_working_hour_policies.employee_id',$emr_array);

        if($fixed == 1){
            $query->where('assign_working_hour_policies.fixed',1);
        }else{
            ($from_date != '' && $to_date == '') ? $query->whereMonth('assign_working_hour_policies.from_date','>=',$explode_from_date[1])->whereYear('assign_working_hour_policies.from_date','>=',$explode_from_date[0]) : '' ;
            ($to_date != '' && $from_date == '') ? $query->whereMonth('assign_working_hour_policies.to_date','<=',$explode_to_date[1])->whereYear('assign_working_hour_policies.to_date','<=',$explode_to_date[0]) : '' ;
            ($from_date != '' && $to_date != '') ? $query->whereBetween('assign_working_hour_policies.from_date',[$from_date,$to_date])
                ->orWhere(function ($q) use($from_date,$to_date) {
                    $q->whereBetween('to_date', [$from_date,$to_date]);
                }): '' ;
        }

        $query->where('assign_working_hour_policies.status',1)->orderBy('assign_working_hour_policies.id','desc');
        $policies = $query;

        CommonHelper::reconnectMasterDatabase();
        $workingHoursPolicy = WorkingHoursPolicy::where([['status','=',1]])->get();
        //$working_hours_policy_name = DB::table('working_hours_policy')->select('working_hours_policy')->where('id',$working_hours_policy_id)->value('working_hours_policy');

        return view('Hr.AjaxPages.filterAssignWorkingHoursPolicyList',compact('workingHoursPolicy','policies','working_hours_policy_id','from_date','to_date','operation_rights2'));
    }

    public function filterAssignWorkingHoursPolicyForm()
    {
        $employee_id=Input::get('employee_id');
        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $project_id=Input::get('project_id');
        $location_id=Input::get('location_id');
        $category_id=Input::get('category_id');
        $working_hours_policy_id=Input::get('working_hours_policy_id');
        $from_date=Input::get('from_date');
        $to_date=Input::get('to_date');
        
        $all_employees = HrHelper::getEmployees(Input::get('company_id'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        

        $workingHoursPolicy = WorkingHoursPolicy::where([['status','=',1]])->get();

        return view('Hr.AjaxPages.filterAssignWorkingHoursPolicyForm',compact('all_employees','workingHoursPolicy','working_hours_policy_id','from_date','to_date'));
    }

    public function filterAssignWorkingHoursPolicyFormRoster()
    {
        $employee_id=Input::get('employee_id');
        $employee_id = implode(",",$employee_id);
        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $project_id=Input::get('project_id');
        $location_id=Input::get('location_id');
        $category_id=Input::get('category_id');
        $working_hours_policy_id=Input::get('working_hours_policy_id');
        $from_date=Input::get('from_date');
        $to_date=Input::get('to_date');
        $company_id = Input::get('company_id');
        $all_employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        

        $workingHoursPolicy = WorkingHoursPolicy::where([['status','=',1]])->get();

        return view('Hr.WorkingHoursPolicy.filterAssignWorkingHoursPolicyFormRoster',compact('all_employees','workingHoursPolicy','working_hours_policy_id','from_date','to_date','company_id'));
    }

    public function displayPolicies(){
        
       
        $emp_id=Input::get('employee_id');
        $emp_id = implode(",",$emp_id);

        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $project_id=Input::get('project_id');
        $location_id=Input::get('location_id');
        $category_id=Input::get('category_id');
        $working_hours_policy_id=Input::get('working_hours_policy_id');
        $from_date=Input::get('from_date');
        $to_date=Input::get('to_date');
        $fixed=Input::get('fixed');
        $explode_from_date=explode('-',$from_date);
        $explode_to_date=explode('-',$to_date);
        
        $employees = HrHelper::getEmployees(Input::get('company_id'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $emp_id);

        $all_emp_id=[];
        foreach($employees as $val){
            $all_emp_id[]=$val->id;
        }
        // echo "<pre>";
        // print_r($employees);
        // exit();
       
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $query=DB::table('assign_working_hour_policies')
        ->join('employee','employee.id','=','assign_working_hour_policies.employee_id')
        ->select('assign_working_hour_policies.id','employee.emp_id','employee.emp_name','employee.id as employee_id',
        'assign_working_hour_policies.from_date','assign_working_hour_policies.to_date','assign_working_hour_policies.color','assign_working_hour_policies.working_hours_policy_id',
        'assign_working_hour_policies.fixed');
        
        $query->whereIn('assign_working_hour_policies.employee_id',$all_emp_id);
           
        $query->where('assign_working_hour_policies.status',1)->orderBy('assign_working_hour_policies.id','desc');
        $policies=$query;

        CommonHelper::reconnectMasterDatabase();
        if($policies->count()>0) 
        {
            $data_arr=array();
            $i=1;
            foreach($policies->get() as $key => $val)
            {
                $working_hour_policy_name=DB::table('working_hours_policy')->select('working_hours_policy')->where('id',$val->working_hours_policy_id)->where('status',1)->value('working_hours_policy');    	
                $data_arr[$i]['id'] = $val->id;
                $data_arr[$i]['employee_id'] = $val->employee_id;
                $data_arr[$i]['emp_id'] = $val->emp_id;
                $data_arr[$i]['emp_name'] = $val->emp_name;
                $data_arr[$i]['working_hours_policy_id'] = $val->working_hours_policy_id;
                $data_arr[$i]['working_hour_policy_name'] = $working_hour_policy_name;
                $data_arr[$i]['from_date'] = $val->from_date;
                $data_arr[$i]['to_date'] = $val->to_date;
                $data_arr[$i]['color'] = $val->color;
                //'green'.substr(uniqid(),-6); // 'green'; pass colour name
                $i++;
            }
            
            $data = array(
                'status' => true,
                'msg' => 'successfully!',
                'data' => $data_arr
            );
            return response()->json($data);
        }
        else
        {
            $data = array(
                'status' => false,
                'msg' => 'Error!'				
            );
            return response()->json($data);        
        }
    }


    public function filterWorkingHoursPolicList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $m = $_GET['m'];


        if($selectVoucherStatus == '0'){
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date',[$fromDate,$toDate])->get();
        }if($selectVoucherStatus == '1'){
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date',[$fromDate,$toDate])->where('status','=','1')->get();
        }if($selectVoucherStatus == '2'){
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date',[$fromDate,$toDate])->where('status','=','2')->get();
        }else {
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date',[$fromDate,$toDate])->get();
        }
        return view('Hr.AjaxPages.filterWorkingHoursPolicList',compact('workingHoursPolicyDetail'));
    }

    public function viewLeavesBalances()
    {
        ini_set('max_execution_time', '600');
        if (Input::get('company_id') == 'All'):
            $companiesList = DB::Table('company')->select('id', 'name')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id', 'name')->where([['id', '=', Input::get('company_id')]])->get()->toArray();
        endif;

        $company_id = Input::get('company_id');
        $user_roles = CommonHelper::userRoles($company_id);
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = (count($user_roles['location_ids']) > 2 ) ? Input::get('location_id') :  $user_roles['location_ids'][0] ;
        
        $year = Input::get('year');
        $explode_year = explode('-', $year);
        
        $employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        $employees_id = [];

        foreach($employees as $val){
            $employees_id[] = $val->id;
        }
        $employees_id = implode(',',$employees_id);
        if(empty($employees_id)) $employees_id = 0; 
        $employees_leave_data;
        
        if($location_id == '2')
        {
            $employees_leave_data = ReportHelper::leaveBalanceReportPQ($employees_id,$explode_year[0],$explode_year[1]);
        }
        else
        {
            $employees_leave_data = ReportHelper::leaveBalanceReport($employees_id,$explode_year[0],$explode_year[1]);
        }

        $emr_array = [] ;

        foreach($employees_leave_data as $val){
            $emr_array[$val->id] = $val;
        }


        $sub_departments = Cache::get('department');
        return view('Hr.AjaxPages.viewLeavesBalances', compact('companiesList','location_id','sub_departments','year','emr_array','employees','employees_leave_data'));
    }

    // public function filterEmployeeAttendanceList(){
    //     $fromDateOne = date_create($_GET['fromDate']);
    //     $toDateOne = date_create($_GET['toDate']);

    //     $fromDate = date_format($fromDateOne,'n/j/yyyy');
    //     $toDate = date_format($toDateOne,'n/j/yyyy');

    //     //return $fromDate .' ---- '. $toDate;

    //     $m = $_GET['m'];

    //     $selectEmployee = $_GET['selectEmployee'];
    //     $selectEmployeeId = $_GET['selectEmployeeId'];
    //     $attendanceStatus = $_GET['attendanceStatus'];

    //     CommonHelper::companyDatabaseConnection($m);
    //     if(empty($selectEmployeeId) && empty($attendanceStatus)){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->get();
    //     }else if(!empty($selectEmployeeId) && empty($attendanceStatus)){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->get();
    //     }else if(empty($selectEmployeeId) && $attendanceStatus == '1'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('absent','=','')->get();
    //     }else if(empty($selectEmployeeId) && $attendanceStatus == '2'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('absent','!=','')->get();
    //     }else if(empty($selectEmployeeId) && $attendanceStatus == '3'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('late','!=','')->get();
    //     }else if(empty($selectEmployeeId) && $attendanceStatus == '4'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('clock_in','=',NULL)->get();
    //     }else if(empty($selectEmployeeId) && $attendanceStatus == '5'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('clock_out','=',NULL)->get();
    //     }else if(!empty($selectEmployeeId) && $attendanceStatus == '1'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('absent','=','')->get();
    //     }else if(!empty($selectEmployeeId) && $attendanceStatus == '2'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('absent','!=','')->get();
    //     }else if(!empty($selectEmployeeId) && $attendanceStatus == '3'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('late','!=','')->get();
    //     }else if(!empty($selectEmployeeId) && $attendanceStatus == '4'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('clock_in','=',NULL)->get();
    //     }else if(!empty($selectEmployeeId) && $attendanceStatus == '5'){
    //         $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('clock_out','=',NULL)->get();
    //     }
    //     CommonHelper::reconnectMasterDatabase();
    //     return view('Hr.AjaxPages.filterEmployeeAttendanceList',compact('employeeAttendanceDetail'));
    // }

    public function show_permanent_form()
    {
        $id=Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee=Employee::where([['id','=',$id],['status','=','1']])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.show_permanent_form',compact('employee','id'));
    }


    public  function viewApplicationDateWise()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);

        $emp_id = $dataFilter[0];
        $from_date = $dataFilter[1];
        $to_date =  $dataFilter[2];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance2 = DB::table('attendance')->where([['attendance.emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->get();
        //print_r($attendance2);
        $totalOffDates[] = '';

        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$emp_id]])->value('day_off');

        $day_off_emp =  explode('=>',$day_off_emp);

        $total_days_off = Attendance::select('attendance_date')
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->where([['emp_id','=',$emp_id]])
            ->wherein('day',[$day_off_emp[1],$day_off_emp[0]]);



        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;

        $monthly_holidays[] = '';
        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);
        if($get_holidays->count() > 0):
            foreach($get_holidays->get() as $value2):

                $monthly_holidays[]=$value2['holiday_date'];
            endforeach;

        else:
            $monthly_holidays =array();
        endif;
        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
        $dates = array();

        foreach ($attendance2 as $value) {
            $LikeDate = "'".'%'.$value->year."-".$value->month.'%'."'";
            CommonHelper::reconnectMasterDatabase();


            $leave_application_request_list = DB::select('select leave_application.* ,
            leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date,
            leave_application_data.no_of_days from leave_application INNER JOIN leave_application_data on
            leave_application_data.leave_application_id = leave_application.id
            WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value->emp_id.'
            AND leave_application.status = 1 AND (leave_application.approval_status = 2 OR
            leave_application.approval_status_lm = 2) AND leave_application.view = "yes"
            OR leave_application_data.to_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value->emp_id.' AND
            leave_application.status = 1  AND (leave_application.approval_status = 2 OR
            leave_application.approval_status_lm = 2) AND leave_application.view = "yes"
            OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and
            leave_application_data.emp_id = '.$value->emp_id.'');


            //   CommonHelper::reconnectMasterDatabase();

            $leaves_from_dates2 = [];
            if(!empty($leave_application_request_list)):
                foreach($leave_application_request_list as $value3):
                    $leaves_from_dates = $value3->from_date;
                    $leaves_to_dates = $value3->to_date;
                    $leaves_type=$value3->leave_type;
                    $leaves_from_dates2[] = $value3->from_date;

                    $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));


                    foreach ($period as $date) {
                        $dates[] = $date->format("Y-m-d");
                    }

                endforeach;

            endif;
        }

        $monthly_holidays = array_merge($monthly_holidays,$dates);
//        $absent_dates = Attendance::select("acc_no","attendance_date","clock_in","clock_out")->where([['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]],['acc_no','=',$acc_no]])
//            ->whereNull('clock_in')
//            ->whereNotIn('attendance_date', $monthly_holidays)
//            ->orwhere([['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]],['acc_no','=',$acc_no]])
//            ->get()->toArray();
//last work
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $absent_dates = Attendance::select("emp_id","attendance_date","clock_in","clock_out")->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$emp_id],['clock_in','=',''],['clock_out','=','']])
            ->whereNotIn('attendance_date', $monthly_holidays)
            ->get()->toArray();
        CommonHelper::reconnectMasterDatabase();


//        $leave_application_request_list = DB::table('leave_application')
//            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
//            ->join('employee', 'employee.acc_no', '=', 'leave_application.emp_id')
//            ->select('leave_application.*', 'employee.emp_name','leave_application_data.*')
//            //->Where('leave_application_data.emp_id','=',$acc_no)
//            ->Where('leave_application_data.from_date','Like','%'.$monthDataFilter[1].'-'.$monthDataFilter[0].'%')
//            ->Where('leave_application.emp_id','=',$acc_no)
//            ->orWhere('leave_application_data.first_second_half_date','Like','%'.$monthDataFilter[1].'-'.$monthDataFilter[0].'%');

        // $LikeDate = "'".'%'.$monthDataFilter[1]."-".$monthDataFilter[0].'%'."'";

//        $leave_application_request_list = DB::select('select leave_application.*,employee.emp_name,leave_application_data.* from leave_application
//            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
//            INNER JOIN employee on employee.acc_no = leave_application.emp_id
//            WHERE leave_application_data.from_date LIKE'.$LikeDate.'AND leave_application_data.emp_id = '.$acc_no.'
//            OR leave_application_data.first_second_half_date LIKE'.$LikeDate.' and leave_application_data.emp_id = '.$acc_no.'');
//

        //print_r($leave_application_request_list);
        //die();

        return view('Hr.AjaxPages.viewApplicationDateWise',compact('leave_application_request_list','absent_dates'));
    }

    public function viewHolidayDate()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $acc_no = $dataFilter[0];
        $month_data = $dataFilter[1];
        $monthDataFilter = explode('-',$month_data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $HolidayData = Holidays::where([['status','=',1],['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]]])->get();
        $day_off_emp =Employee::select('day_off')->where([['acc_no','=',$acc_no]])->value('day_off');
        $total_days_off = Attendance::select('attendance_date','day','month','year')->where([['day','=',$day_off_emp],['acc_no','=',$acc_no],
            ['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]]])->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHolidayDate',compact('HolidayData','total_days_off'));

    }


    public function viewOverTimeDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $acc_no = $dataFilter[0];
        $month_data = $dataFilter[1];
        $monthDataFilter = explode('-',$month_data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $totalOffDates = array();
        $day_off_emp =Employee::select('day_off')->where([['acc_no','=',$acc_no]])->value('day_off');
        $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['acc_no','=',$acc_no]]);

        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;

        $monthly_holidays = array();
        $get_holidays = Holidays::select('holiday_date')->where([['status','=',1],['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]]]);
        if($get_holidays->count() > 0) {
            foreach ($get_holidays->get() as $value2) {

                $monthly_holidays[] = $value2['holiday_date'];
            }
        }
        else{
            $monthly_holidays =array();
        }

        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
        $attData = Attendance::where([['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]],['acc_no','=',$acc_no],['clock_in','!=','']])
            ->whereIn('attendance_date', $monthly_holidays)
            ->orwhere([['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]],['acc_no','=',$acc_no],['clock_out','!=','']])
            ->whereIn('attendance_date', $monthly_holidays)
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewOverTimeDetail',compact('attData'));

    }
    public function  viewLatesDetil()
    {
        $atendance=[];
        $data = Input::get('id');
        $dataFilter = explode(',',$data);

        $emp_id = $dataFilter[0];
        $from_date = $dataFilter[1];
        $to_date =  $dataFilter[2];

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp=Employee::where('emp_id',$emp_id)->where('status',1)->first();
        $emp_working_hours_policy_id=$emp->working_hours_policy_id;
        CommonHelper::reconnectMasterDatabase();
        $working_hours_policy=WorkingHoursPolicy::where('id',$emp_working_hours_policy_id)->where('status',1)->first();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendace=DB::table('attendance')->where([['attendance.emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->get();
        foreach($attendace as $value2){
            $startTime = $working_hours_policy->start_working_hours_time;
            $endTime = strtotime("+".$working_hours_policy->working_hours_grace_time."minutes", strtotime($startTime));
            $half_day_time=strtotime("+".$working_hours_policy->half_day_time."minutes", strtotime($startTime));
            if($value2->clock_in > date('h:i', $endTime) && $value2->clock_in < date('h:i', $half_day_time)){
                $atendance[]=array('emp_name'=>$emp->emp_name,'attendance_date'=>$value2->attendance_date,'clock_in'=>$value2->clock_in,'clock_out'=>$value2->clock_out);
            }



        }
        return view('Hr.AjaxPages.viewLatesDetil',compact('atendance'));
    }

    public function  viewLateArrivalDetail()
    {

        $id = Input::get('id');

        $main_explode = explode(",",$id);
        $emp_id = $main_explode[0];

        $id_explode = explode("/",$main_explode[1]);


        $late_arrival_date = [];
        foreach ($id_explode as $key => $value) {
            $late_arrival_date[] = $value;
        }

        CommonHelper::companyDatabaseConnection(Input::get('m'));


        $emp_data =Employee::select('day_off','working_hours_policy_id')->where([['emp_id','=',$emp_id]]);

        CommonHelper::reconnectMasterDatabase();
        $working_policy_data = WorkingHoursPolicy::where([['id','=',$emp_data->value('working_hours_policy_id')]])->get()->toArray();
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $startTime =  strtotime($working_policy_data[0]['start_working_hours_time']);


        $halfdayTime = strtotime("+".$working_policy_data[0]['half_day_time']." hours", strtotime($working_policy_data[0]['start_working_hours_time']));

        $startTime2 = strtotime("+".$working_policy_data[0]['working_hours_grace_time']." minutes", strtotime($working_policy_data[0]['start_working_hours_time']));


        $lateArrivalData = Attendance::whereIn('attendance_date',$late_arrival_date)
            ->where('emp_id','=',$emp_id)
            ->get();

        $graceTime = date('H:i', $startTime);
        $graceTime2 = date('H:i', $startTime2);

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewLateArrivalDetail',compact('lateArrivalData','graceTime','graceTime2','halfDayData','halfdayTime'));
    }

    // public function viewLatesDetail(){

    //     CommonHelper::companyDatabaseConnection(Input::get('m'));

    //     $lates_detail=DB::table('attendance')-

    //     CommonHelper::reconnectMasterDatabase();
    //     return view('Hr.AjaxPages.viewLatesDetail',compact('lateArrivalData'));
    // }

    public function viewAbsentsDetail(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));


        $absents_detail=DB::table('employee')->select('id','emp_id','emp_name')->where('status',1);

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAbsentsDetail',compact('absents_detail'));
    }

    public function viewHalfDays()
    {
        $url=Input::get('modal_name');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_data=DB::table('employee')->select('id','emp_id','emp_name')->where('status',1)->get();
        $attendance_half_days=array();
        $attendance_lates=[];
        $date =date('Y-m-d');
        CommonHelper::reconnectMasterDatabase();
        foreach($employee_data as $value){
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $working_hours_policy_id=DB::table('assign_working_hour_policies')->select('id')->where('employee_id',$value->id)->value('id');
            CommonHelper::reconnectMasterDatabase();
            $working_hours_policy=WorkingHoursPolicy::where('id',$working_hours_policy_id)->where('status',1);
            if($working_hours_policy->count()>0){
                $startTime = $working_hours_policy->value('start_working_hours_time');
                $finishTime = $working_hours_policy->value('end_working_hours_time');
                $dutyEndTime = date("H:i", strtotime($finishTime));
                $endTime = strtotime("+".$working_hours_policy->value('working_hours_grace_time')."minutes", strtotime($startTime));
                $half_day_time=strtotime("+".$working_hours_policy->value('half_day_time')."minutes", strtotime($startTime));

                $half_days_absent=date('h:i', $half_day_time);
                $end_day_time=date('h:i', $endTime);
                CommonHelper::companyDatabaseConnection(Input::get('m'));
                $lates=DB::table('attendance')->select('attendance_date','clock_in','clock_out','day')->where([['attendance_date','=',$date],
                    ['clock_in','>',$end_day_time],['clock_in','<',$half_days_absent],['emp_id','=',$value->emp_id]]);

                if($lates->count()>0){

                    $lates_days_first= $lates->first();
                    $new_input = array(
                        'employee_id' => $value->id,
                        'emp_id' => $value->emp_id,
                        'emp_name' => $value->emp_name,
                        'day' =>  $lates_days_first->day,
                        'attendance_date' => $lates_days_first->attendance_date,
                        'clock_in' => $lates_days_first->clock_in
                    );
                    array_push($attendance_lates, $new_input);

                }

                $half_days=DB::table('attendance')->select('attendance_date','clock_in','clock_out','day')->where([['attendance_date','=',$date],
                    ['clock_in','>=',$half_days_absent],['emp_id','=',$value->emp_id]]);

                $half_2nd_days=DB::table('attendance')->select('attendance_date','clock_in','clock_out','day')->where([['attendance_date','=',$date],
                    ['clock_out','<',$dutyEndTime],['clock_out','!=',''],['clock_in','!=',''],['emp_id','=',$value->emp_id]]);

                if($half_days->count()>0){
                    $half_days_first= $half_days->first();
                    $new_input = array(
                        'employee_id' => $value->id,
                        'emp_id' => $value->emp_id,
                        'emp_name' => $value->emp_name,
                        'day' =>  $half_days_first->day,
                        'attendance_date' => $half_days_first->attendance_date,
                        'clock_in' => $half_days_first->clock_in,
                        'clock_out' => $half_days_first->clock_out
                    );
                    array_push($attendance_half_days, $new_input);

                }
                else if($half_2nd_days->count()>0){

                    $half_days_first= $half_2nd_days->first();
                    $new_input = array(
                        'employee_id' => $value->id,
                        'emp_id' => $value->emp_id,
                        'emp_name' => $value->emp_name,
                        'day' =>  $half_days_first->day,
                        'attendance_date' => $half_days_first->attendance_date,
                        'clock_in' => $half_days_first->clock_in,
                        'clock_out' => $half_days_first->clock_out
                    );
                    array_push($attendance_half_days, $new_input);

                }
                CommonHelper::reconnectMasterDatabase();
            }
        }


        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHalfDays',compact('attendance_half_days','attendance_lates','url'));
    }


    public function viewHalfDaysDetail()
    {
        $data = Input::get('id');

        $dataFilter = explode(',',$data);
        $emp_id = $dataFilter[0];


        $month_data = $dataFilter[1];

        $monthDataFilter = explode('-',$month_data);

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $totalOffDates =  array();
        $day_off_emp =Employee::select('day_off')->where([['emp_id','=',$emp_id]])->value('day_off');

        $total_days_off = Attendance::select('attendance_date')->where([['day','=',substr($day_off_emp,0,3)],['emp_id','=',$emp_id]]);

        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;

        $monthly_holidays = array();

        $get_holidays = Holidays::select('holiday_date')->where([['status','=',1]])->whereBetween('holiday_date',[$dataFilter[1],$dataFilter[2]]);

        if($get_holidays->count() > 0) {
            foreach ($get_holidays->get() as $value2) {

                $monthly_holidays[] = $value2['holiday_date'];
            }
        }
        else{
            $monthly_holidays =array();
        }

        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);

        $emp_data =Employee::select('day_off','working_hours_policy_id')->where([['emp_id','=',$emp_id]]);
        CommonHelper::reconnectMasterDatabase();
        $working_policy_data = WorkingHoursPolicy::where([['id','=',$emp_data->value('working_hours_policy_id')]])->get()->toArray();
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $halfdayTime = strtotime("+".$working_policy_data[0]['half_day_time']." minutes", strtotime($working_policy_data[0]['start_working_hours_time']));
        $finishTime = $working_policy_data[0]['end_working_hours_time'];
        $dutyEndTime = date("H:i", strtotime($finishTime));

//        $total_halfDay = Attendance::where([['neglect_attendance','=','no'],['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]],['acc_no','=',$acc_no],['late','>=','02:00'],['late','<','04:00']])
//            ->whereNotIn('attendance_date', $monthly_holidays)
//            ->get();

        $total_halfDay = Attendance::where([['emp_id','=',$emp_id],['clock_in','>=', date('H:i', $halfdayTime)]])
            ->whereNotIn('attendance_date', $monthly_holidays)
            ->whereBetween('attendance_date',[$dataFilter[1],$dataFilter[2]])
            ->orWhere(function($nest) use($dataFilter,$emp_id,$dutyEndTime,$monthly_holidays) {
                $nest->where([['emp_id','=',$emp_id],['clock_in','!=', ''],['clock_out','<', $dutyEndTime]])
                    ->whereNotIn('attendance_date', $monthly_holidays)
                    ->whereBetween('attendance_date',[$dataFilter[1],$dataFilter[2]]);
            })
            ->get();

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewHalfDaysDetail',compact('total_halfDay','halfdayTime'));

    }

    public function viewOvertimeHoursDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $emp_id = $dataFilter[0];
        $month_data = $dataFilter[1];

        $from_date = $dataFilter[1];
        $to_date =  $dataFilter[2];

        $monthDataFilter = explode('-',$month_data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $totalOffDates[] = '';
        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$emp_id]])->first();
        $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp->day_off],['emp_id','=',$emp_id]]);

        if($total_days_off->count() > 0):
            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;
        else:
            $totalOffDates = array();
        endif;

        $monthly_holidays[] ='';
        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);

        if($get_holidays->count() > 0):
            foreach ($get_holidays->get() as $value2):
                $monthly_holidays[] = $value2['holiday_date'];
            endforeach;
        else:
            $monthly_holidays = array();
        endif;

        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);

        $emp_data =Employee::select('working_hours_policy_id')->where([['emp_id','=',$emp_id]]);

        CommonHelper::reconnectMasterDatabase();

        $working_policy_data = WorkingHoursPolicy::where([['id','=',$emp_data->value('working_hours_policy_id')]])->first();

        $dutyEndTime = $working_policy_data->end_working_hours_time;
        $dutyStartTime =  $working_policy_data->start_working_hours_time;

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        // $time="10:09";
        $time = date('H:i', strtotime($dutyEndTime.'+40 minutes'));
        $earlyTime = date('H:i', strtotime($dutyStartTime.'-40 minutes'));

        $total_ot_hours_count = DB::table('attendance')->select('clock_in','clock_out','attendance_date','day','emp_id')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$emp_id],['clock_in','!=',''],['clock_out','!=',''],['clock_out','>',$time]])
            ->get()->toArray();

        $total_ot_hours_count_early = DB::table('attendance')->select('clock_in','clock_out','attendance_date','day','emp_id')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$emp_id],['clock_in','!=',''],['clock_in','<',$earlyTime]])
            ->get()->toArray();

        $total_ot_hours_count = array_merge($total_ot_hours_count,$total_ot_hours_count_early);

        $total_ot_hours_holidays_count = DB::table('attendance')->select('clock_in','clock_out','attendance_date','day','emp_id')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$emp_id],['clock_in','!=',''],['clock_out','!=','']])
            ->get();
        $attendanceOTHolidays = DB::table('attendance')
            ->select('attendance_date')
            ->where([['emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->whereIn('attendance_date',$monthly_holidays)
            ->get()->toArray();

        $id_array = array_column($attendanceOTHolidays, 'attendance_date');


        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewOvertimeHoursDetail',compact('total_ot_hours_count','dutyEndTime','total_ot_hours','attendanceOTHolidays','id_array','total_ot_hours_holidays_count','dutyStartTime'));
    }

    public function viewLeaveApplicationClientForm()
    {
        $employee_id = Input::get('employee_id');
        $employees = Cache::get('employee');
        $departments = Cache::get('department');
        $leave_type = Cache::get('leave_type');
        $employee = $employees[$employee_id];
        if ($employee->leaves_policy_id == 0):
            return 0;
        endif;

        $leaves = HrHelper::viewLeaveBalances($employee->id,$employee->leaves_policy_id, 1);

        $leaves_policy = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $employee->leaves_policy_id]])
            ->orderBy('leaves_data.leave_type_id')->get();

        $reasons = DB::table('reasons')->where('status',1)->get();
        return view('Hr.LeaveApplication.viewLeaveApplicationClientForm', compact('leaves','leaves_policy', 'employee',
            'reasons','departments','leave_type'));
    }

    public function addLeaveApplicationFrom(Request $request)
    {

        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $category_id = Input::get('category_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $company_id = Input::get('m');
        $emr_array = [];

        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);


        foreach($employee as $val){
            $emr_array[] = $val->id;
        }
        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0; 

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $fullDayAbsent = AttendanceHelper::fullDayAbsent($emr_array, $from_date, $to_date);
        $halfDayAbsent = AttendanceHelper::halfDayAbsent($emr_array, $from_date, $to_date);
        CommonHelper::reconnectMasterDatabase();

        $totalAbsentDay =   array_merge($fullDayAbsent , $halfDayAbsent);
        // echo "<pre>";
        // print_r($totalAbsentDay);
        // exit();
        $departments = Cache::get('department');
        return view('Hr.LeaveApplication.addLeaveApplicationFormList', compact('totalAbsentDay','departments'));
    }

    public function getDesignations(){
        $designations=[];
        $employee_grade_type = Grades::where([['id','=',Input::get('value')]])->value('employee_grade_type');
        $grades=Grades::where([['employee_grade_type','=',$employee_grade_type],['status','=',1]])->get();
        dd($grades);
        foreach($grades as $emp_grade){
            $designation=Designation::where([['id','=',$emp_grade->designation_id],['status','=',1]])->first();
            $designations[]=array('id'=>$designation->id,'designation_name'=>$designation->designation_name);
        }

        return $designations;

    }
//    public function getSubDepartments(){
//
//
//        $SubDepartments=SubDepartment::where([['department_id','=',Input::get('value')],['status','=',1]])->get();
//
//
//        return $SubDepartments;
//
//    }


    public  function viewHolidaysMonthWise()
    {
        $monthData = explode('-',Input::get('monthYear'));
        $year = $monthData[0];
        $month = $monthData[1];
        $m  = Input::get('m');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::where('month','=',$month)->where('year','=',$year)->where('status','=',1)->orderBy('holiday_date')->get();
        CommonHelper::reconnectMasterDatabase();
//            return view('Hr.viewHolidaysList',compact('holidays'));
        return view('Hr.AjaxPages.viewHolidaysMonthWise',compact('holidays','m'));
    }

    public function viewEmployeeDepositDetail()
    {

    }

    public function viewEmployeeListManageAttendence(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $getData['emr_no'] = Input::get('emr_no');
        $emp_data = Employee::select('emp_name','day_off')->where([['emr_no', '=', Input::get('emr_no')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $getData['emp_name'] = $emp_data['emp_name'];
        $getData['day_off'] = $emp_data['day_off'];
        $getData['sub_department_id'] = Input::get('sub_department_id');
        $monthYearDataFilter = explode('-',Input::get('month_year'));
        $getData['month'] = $monthYearDataFilter[1];
        $getData['year'] = $monthYearDataFilter[0];
        $getData['company_id'] = Input::get('m');

        return view('Hr.AjaxPages.createManuallyAttendanceForm',compact('getData'));

    }

    function  viewEmployeeExitClearanceForm()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $category_id = Input::get('category_id');
        $company_id = Input::get('company_id');

        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);

        if(!empty($employee)):
            $employee = $employee[0];
            CommonHelper::companyDatabaseConnection($company_id);
            $exit_data = EmployeeExit::where([['status', '=', 1], ['employee_id', '=', $employee_id]])->orderBy('id','desc');
            CommonHelper::reconnectMasterDatabase();
            $designations = Cache::get('designation');
            $departments = Cache::get('department');
            return view('Hr.AjaxPages.viewEmployeeExitClearanceForm', compact('employee', 'exit_data','designations','departments','company_id'));
        else:
            return 'false';
        endif;
    }

    public function viewEmployeeExitClearanceDetail()
    {
        $id = Input::get('id');
        $m 	= Input::get('m');
        $type = Input::get('type');

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $exit_employee_data = EmployeeExit::where([['id', '=', $id]])->first();
        $employee=Employee::where([['status', '!=', 2],['id', '=', $exit_employee_data->employee_id]])->select('emp_id','emp_name','designation_id', 'sub_department_id')->first();
        $designation_id = $employee->designation_id;
        $sub_department_id = $employee->sub_department_id?$employee->sub_department_id:0;
        $department_id = $employee->department_id;

        $employeeCurrentPositions = EmployeePromotion::where([['employee_id','=',$exit_employee_data->employee_id],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
        endif;

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeExitClearanceDetail',compact('exit_employee_data','employee','department_id','sub_department_id','designation_id','exit_employee_data','operation_rights2','type'));
    }

    public function viewFinalSettlementForm()
    {
        $sum_hold_salary = 0;
        $sum_hold_net_amount = 0;
        $hold_salary = [];
        $hold_salary_month = [];
        $hold_salary_year = [];
        $month_year = date('Y-m-d');

        $employee_id = Input::get('employee_id');
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        $employee_data = DB::table('employee')->select('joining_date','last_working_date')->where([['id','=',$employee_id]])->first();
        $last_working_date = $employee_data->last_working_date;

        $separation_date = explode('-', $last_working_date);

        $total_month_days = cal_days_in_month(CAL_GREGORIAN, $separation_date[1], $separation_date[0]);
        $till_date = $separation_date[0].'-'.$separation_date[1].'-'.$total_month_days;

        if($employee_data->joining_date == ''):
            $joining_date = $employee_data->joining_date;
        else:
            $joining_date = $employee_data->joining_date;
        endif;

        $loan_id = '';
        $loan_id_array = [];
        $paid_amount = 0;
        $loan_adjusted_amount = 0;
        $total_loan_amount_paid = 0;
        $loan_amount_remaining = 0;
        $actual_loan = 0;

        $query = "SELECT e.id, e.emp_id, e.emp_name, e.joining_date,
            IFNULL((SELECT ep.salary FROM employee_promotion AS ep WHERE
            ep.employee_id = e.id AND ep.status = 1 AND ep.salary != '' AND ep.approval_status = 2
            ORDER BY ep.id DESC LIMIT 1),e.salary) AS salary,
            TIMESTAMPDIFF(YEAR, joining_date, '$month_year') AS years,
            TIMESTAMPDIFF(MONTH, joining_date, '$month_year')%12 AS months
            FROM employee AS e WHERE e.id = '$employee_id' ORDER BY e.id ASC ";
        $employees_gratuity = DB::selectOne(DB::raw($query));

        $gratuity = 0;
        $years = $employees_gratuity->years;
        $months = $employees_gratuity->months;
        $salary = round($employees_gratuity->salary);
        $per_day_salary = $salary / 30;
        if($years >= 1 && $months >= 6):
            $years += 1;
        endif;
        if($years >= 1):
            $per_year_gratuity = $per_day_salary * 20;
            $gratuity = $years * $per_year_gratuity;
        endif;

        $loanRequest = LoanRequest::where([['employee_id', '=', $employee_id],['status', '=', 1],['loan_status','=',0]]);
        if($loanRequest->count() > 0):
            foreach($loanRequest->get() as $key => $val):
                $loan_top_up = LoanTopUp::where([['loan_id', '=', $val->id],['status', '=', 1]])->sum('loan_top_up_amount');
                $actual_loan = $val->loan_amount + $loan_top_up;

                $payslip = Payslip::where([['employee_id' ,'=', $employee_id],['loan_id' ,'=', $val->id]]);
                if($payslip->count() > 0):
                    $paid_amount += Payslip::where([['employee_id' ,'=', $employee_id],['loan_id' ,'=', $val->id]])
                        ->sum('loan_amount_paid');
                endif;
                $loan_adjusted_amount += DB::table('loan_adjustment')->where([['loan_id','=',$val->id],['status','=',1]])->sum('amount');
                $total_loan_amount_paid = $paid_amount + $loan_adjusted_amount;
                if($total_loan_amount_paid < $actual_loan):
                    $loan_amount_remaining += $actual_loan - $total_loan_amount_paid;
                    $loan_id_array[] = $val->id;
                endif;
            endforeach;
        endif;
        $loan_id = implode(',',$loan_id_array);

        $payslip_hold_salary = DB::select(DB::raw("SELECT net_salary,month,year FROM payslip WHERE employee_id = ".$employee_id." AND hold_salary != 0 AND status = 1 AND month_year BETWEEN '".$joining_date."' AND '".$till_date."'"));
        if(!empty($payslip_hold_salary)):
            foreach($payslip_hold_salary as $key => $val):
                $sum_hold_salary += round($val->net_salary);
                $hold_salary[] = round($val->net_salary);
                $hold_salary_month[] = $val->month;
                $hold_salary_year[] = $val->year;
            endforeach;
        endif;

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewFinalSettlementForm',compact('gratuity','sum_hold_salary','hold_salary','loan_amount_remaining',
            'loan_id','hold_salary_month','hold_salary_year', 'last_working_date','m','employee_id'));
    }

    public function viewFilteredFinalSettlementList()
    {
        //$operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $emp_id = Input::get('emp_id');

        if ($emp_id == 'All') :
            $final_settlement = DB::table('final_settlement')->where([['status', '=', 1]])->orderBy('last_working_date','asc')->get();
        else:
            $final_settlement = DB::table('final_settlement')->where([['status', '=', 1],['employee_id', '=', $emp_id]])->get();
        endif;

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewFilteredFinalSettlementList', compact('final_settlement'));
    }

    public function viewFinalSettlementDetail($id)
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $type = "";
        $final_settlement = DB::table('final_settlement')->where([['id', '=', $id]])->first();

        $employee_id = $final_settlement->employee_id;
        $explode_hold_salary = explode(',', $final_settlement->hold_salary);
        $explode_hold_salary_month = explode(',', $final_settlement->hold_salary_month);
        $explode_hold_salary_year = explode(',', $final_settlement->hold_salary_year);

        $total_salary = $final_settlement->leave_encashment_amount + array_sum($explode_hold_salary) +
            $final_settlement->other_amount + $final_settlement->salary_amount;

        $deduction = $final_settlement->leave_deduction_amount + $final_settlement->opd_excess + $final_settlement->other_deduction_amount
            + $final_settlement->loan_deduction;

        $salary_amount_payable = $total_salary - $deduction;

        $gratuity = $final_settlement->gratuity;
        if(($salary_amount_payable) < 0):
            $gratuity = $final_settlement->gratuity + $salary_amount_payable ;
            $total_amount_payable = $gratuity;
        else:
            $total_amount_payable = $salary_amount_payable + $final_settlement->gratuity;
        endif;

        $employees = Cache::get('employee');

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewFinalSettlementDetail', compact('salary_amount_payable','deduction','employee_id','gratuity','total_amount_payable','final_settlement','explode_hold_salary_month','explode_hold_salary_year','type','employees'));
    }
    
    function calculateLeaveDeductionAmount()
    {
        $employee_id = Input::get('employee_id');
        $leave_deduction_days = Input::get('leave_deduction_days');
        $last_working_date = explode('-', Input::get('last_working_date'));
        CommonHelper::companyDatabaseConnection('12');

        $employees = Cache::get('employee');
        $emp_salary = $employees[$employee_id]->new_salary;
        //$total_month_days = cal_days_in_month(CAL_GREGORIAN, $last_working_date[1], $last_working_date[0]);

        $leave_deduction_amount = 0;
        $leave_deduction_amount = round(($emp_salary / 30) * $leave_deduction_days);

        CommonHelper::reconnectMasterDatabase();

        return compact('leave_deduction_amount');
    }

    function calculateSalaryTobePaidAmount()
    {
        $employee_id = Input::get('employee_id');
        $salary_upto = Input::get('salary_upto');
        $last_working_date = Input::get('last_working_date');
        $salary_amount = 0;
        $salary_array = [];
        $data = [];
        $month_array = [];

        CommonHelper::companyDatabaseConnection('12');

        $employee = Employee::select('salary')->where([['id', '=', $employee_id]]);
        $promoted_salary = EmployeePromotion::select('salary')->where([['employee_id','=',$employee_id],['status','=',1]])->orderBy('id', 'desc');
        if($promoted_salary->count() > 0):
            $emp_salary = $promoted_salary->value('salary');
        else:
            $emp_salary = $employee->value('salary');
        endif;
        CommonHelper::reconnectMasterDatabase();

        $date1  = $last_working_date;
        $date2  = $salary_upto;

        if(date('d-m-Y', strtotime($last_working_date)) == date('d-m-Y', strtotime($salary_upto))):
            $total_month_days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($salary_upto)), date('Y', strtotime($salary_upto)));
            $datediff = round((strtotime($date2) - strtotime(date('1-m-Y', strtotime($date1)))) / (60 * 60 * 24));

            $salary_amount = round(($emp_salary / $total_month_days) * ($datediff + 1));
            $salary_array[] = round(($emp_salary / $total_month_days) * ($datediff + 1));

            $data[] = date('d',strtotime($last_working_date))."_".$total_month_days;
            $month_array[] = date('m-Y',strtotime($last_working_date));
        else:
            if(date('m-Y', strtotime($last_working_date)) == date('m-Y', strtotime($salary_upto))):
                $total_month_days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($salary_upto)), date('Y', strtotime($salary_upto)));
                $datediff = round((strtotime($date2) - strtotime($date1)) / (60 * 60 * 24));

                $salary_amount = round(($emp_salary / $total_month_days) * ($datediff));
                $salary_array[] = round(($emp_salary / $total_month_days) * ($datediff));

                $data[] = date('d',strtotime($salary_upto))- date('d',strtotime($last_working_date))."_".$total_month_days;
                $month_array[] = date('m-Y',strtotime($salary_upto));
            else:
                $output = [];
                $time = strtotime($date1);
                $last = date('m-Y', strtotime($date2));

                do {
                    $month = date('m-Y', $time);
                    $total = date('t', $time);
                    $month_array[] = $month;

                    $output[] = [
                        'month' => $month,
                        'total' => $total,
                    ];

                    $time = strtotime('+1 month', $time);
                } while ($month != $last);

                $counter = 1;
                $i = 0;
                $len = count($output);

                foreach($output as $key => $value):

                    if($counter++ == 1) {
                        $data[] = $value['total'] - date('d',strtotime($last_working_date))."_".$value['total'];
                    }
                    else if ($i == $len - 1) {
                        $last_days = ($value['total']) - ($value['total']-date('d',strtotime($salary_upto)));
                        $data[] = $last_days."_".$value['total'];
                    }
                    else {
                        $data[] =  $value['total']."_".$value['total'];
                    }
                    $i++;

                endforeach;

                foreach($data as $val2):
                    $detail = explode('_',$val2);
                    $salary_array[] = round(($emp_salary / $detail[1]) * $detail[0]);
                    $salary_amount += round(($emp_salary / $detail[1]) * $detail[0]);
                endforeach;
            endif;
        endif;
        return view('Hr.AjaxPages.viewFinalSettlementSalaryForm',compact('data','salary_array','salary_amount','month_array'));
    }

    public function checkEmrNoExist()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_count = Employee::where([['emp_id','=',Input::get('emp_id')],['status','!=',2]])->count();
        CommonHelper::reconnectMasterDatabase();

        if($employee_count > 0 ):
            echo "EMP ID. ".Input::get('emp_id')." Already Exist !";
        else:
            echo "success";
        endif;
    }

    function  viewEmployeeIdCardRequest($id='')
    {
        $emr_no = Input::get('emr_no');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee=Employee::where([['status','=',1], ['emr_no', '=', $emr_no]])->select('img_path','designation_id','joining_date', 'emp_cnic')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation=Designation::where([['status', '=' ,1],['id', '=', $employee['designation_id']]])->select('designation_name')->first();
        return view('Hr.AjaxPages.viewEmployeeIdCardRequest', compact('designation', 'employee', 'employee_card_request'));

    }

    function  viewEmployeeIdCardRequestDetail($id='')
    {
        $id = Input::get('id');
        $m 	= Input::get('m');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_card_request = EmployeeCardRequest::where([['id', '=', $id]])->select('id','fir_copy_path','fir_copy_extension',
            'emr_no','posted_at','card_status', 'status', 'approval_status',
            'card_image_extension','card_image_path')->first();
        $employee = Employee::where([['emr_no',$employee_card_request->emr_no],['status', '=', 1]])->select('emp_name', 'img_path', 'designation_id', 'emp_sub_department_id', 'joining_date', 'emp_cnic')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation = Designation::where([['status', '=', 1],['id', '=', $employee->designation_id]])->select('designation_name')->first();
        $sub_department = SubDepartment::where([['status', '=', 1],['id', '=', $employee->emp_sub_department_id]])->select('sub_department_name')->first();
        return view('Hr.AjaxPages.viewEmployeeIdCardRequestDetail', compact('designation', 'employee_card_request', 'employee', 'sub_department', 'operation_rights2'));

    }

    public function viewEmployeePreviousPromotionsDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $employee_id = Input::get('employee_id');
        $query_string_second_part[] = " AND e.id = '$employee_id'";
        $query_string_first_Part = "SELECT
          IFNULL((SELECT employee_promotion.designation_id FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.designation_id != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.designation_id) AS designation_id,
          IFNULL((SELECT employee_promotion.grade_id FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.grade_id != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.grade_id) AS grade_id,
          IFNULL((SELECT employee_promotion.salary FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.salary != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.salary) AS salary,
           IFNULL((SELECT employee_promotion.promotion_date FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.salary != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.date) AS date FROM employee AS e WHERE ";
        $query_string_third_part = ' ORDER BY e.id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
        $employee = DB::selectOne(DB::raw($query_string));

        $designation_id = $employee->designation_id;
        $grade_id = $employee->grade_id;
        $salary = $employee->salary;
        $date = $employee->date;

        $grades = Cache::get('grades');
        $designations = Cache::get('designation');

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePreviousPromotionsDetail', compact('grades','designations','designation_id',
            'grade_id','salary','date'));
    }

    public function viewEmployeeDocuments()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeDocuments = EmployeeDocuments::where([['employee_id','=',Input::get('id')],['type','=',Input::get('type')],['status','=',1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeDocuments', compact('employeeDocuments'));
    }

//    public function viewExpiryAndUpcomingAlerts()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $expireDateOne=date('Y-m-d',strtotime(now() .'+1 month'));
//        $attendance_queries=DB::table('attendance_queries')->where('status',1)->where('approval_status',1)->count();
//        $date=date("Y-m-d");
//        $emp_count=DB::table('employee')->where('status',1)->count('emp_id');
//        $present_count=DB::table('attendance')->where('attendance_date',$date)->where('clock_in','!=','')->where('status',1)->count('employee_id');
//        $absents_detail=$emp_count-$present_count;
//        //$settlementDate =date('Y-m-d',strtotime(now() .'+2 days'));
//        $cnic_expiry_date_count = Employee::where([['status','=',1],['cnic_expiry_date','<',$expireDateOne],['cnic_expiry_date','>',date('Y-m-d')],['cnic_expiry_date','!=','']])->count();
//        $upcoming_birthday_count = DB::select( DB::raw("SELECT count('date_of_birth') as upcoming_birthday_count  FROM employee where DATE_FORMAT(date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +1 month), '%m-%d') "));
//        $permanent_employee = Employee::select('employment_status_id')->where([['status','=',1],['employment_status_id','=',1]])->get();
//        // $over_age_employee_count = DB::select( DB::raw('SELECT count("date_of_birth") as over_age_employee_count FROM employee WHERE status=1 and DATEDIFF(NOW(), date_of_birth) / 365.25 >= 60'));
//        $employee_missing_images= Employee::where([['status','=',1],['img_path','=','app/uploads/employee_images/user-dummy.png']])->count();
//        // $nadra = EmployeeGsspDocuments::select('emp_id')->where([['document_type','=','Nadra']]);
//        // $settlementTermination1= HrTerminationFormat1Letter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']])->count();
//        // $settlementTermination2= HrTerminationFormat2Letter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']])->count();
//        // $settlementContract= HrContractConclusionLetter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']])->count();
//        $date =date("Y-m-d");
//        $employeesProbationExpires=Employee::select('id','emp_id','joining_date','emp_name','probation_expire_date','employment_status_id')->where([['probation_expire_date','<=',$date],['status','=',1],['employment_status_id','!=',7]])->count();
//        $employeeProbationPeriodOverDetail =DB::select( DB::raw("SELECT count('emp_id') as totalOverProbationEmp FROM employee WHERE employment_status_id = '8' AND status = '1' AND joining_date <= DATE_ADD('".date("Y-m-d")."',INTERVAL -6 MONTH)"));
//        $employee_data=DB::table('employee')->select('emp_id','id')->where('status',1)->get();
//
//        $half_days_count=0;
//        $lates_count=0;
//        // $date ='2022-08-31';
//        CommonHelper::reconnectMasterDatabase();
//        foreach($employee_data as $value){
//            CommonHelper::companyDatabaseConnection(Input::get('m'));
//            $working_hours_policy_id=DB::table('assign_working_hour_policies')->select('id')->where('employee_id',$value->id)->value('id');
//            CommonHelper::reconnectMasterDatabase();
//
//            $working_hours_policy=WorkingHoursPolicy::where('id',$working_hours_policy_id)->where('status',1);
//            if($working_hours_policy->count()>0){
//                $startTime = $working_hours_policy->value('start_working_hours_time');
//                $finishTime = $working_hours_policy->value('end_working_hours_time');
//                $dutyEndTime = date("H:i", strtotime($finishTime));
//                $endTime = strtotime("+".$working_hours_policy->value('working_hours_grace_time')."minutes", strtotime($startTime));
//                $half_day_time=strtotime("+".$working_hours_policy->value('half_day_time')."minutes", strtotime($startTime));
//
//                $half_days_absent=date('h:i', $half_day_time);
//                $end_day_time=date('h:i', $endTime);
//                CommonHelper::companyDatabaseConnection(Input::get('m'));
//                $lates=DB::table('attendance')->select('attendance_date')->where([['attendance_date','=',$date],
//                    ['clock_in','>',$end_day_time],['clock_in','<',$half_days_absent],['employee_id','=',$value->emp_id]]);
//
//                if($lates->count()>0){
//                    $lates_count++;
//                }
//
//                $half_days=DB::table('attendance')->select('attendance_date')->where([['attendance_date','=',$date],
//                    ['clock_in','>=',$half_days_absent],['employee_id','=',$value->emp_id]]);
//
//
//                $half_2nd_days=DB::table('attendance')->select('attendance_date')->where([['attendance_date','=',$date],
//                    ['clock_out','<',$dutyEndTime],['clock_out','!=',''],['clock_in','!=',''],['employee_id','=',$value->emp_id]]);
//
//                if($half_days->count()>0){
//                    $half_days_count++;
//                }
//                else if($half_2nd_days->count()>0){
//                    $half_days_count++;
//                }
//                CommonHelper::reconnectMasterDatabase();
//            }
//        }
//
//        return view('Hr.AjaxPages.viewExpiryAndUpcomingAlerts',compact('half_days_count','lates_count', 'attendance_queries','absents_detail','employeesProbationExpires', 'employeeProbationPeriodOverDetail','employee_missing_images','cnic_expiry_date_count','upcoming_birthday_count','permanent_employee'));
//    }

    public function approveOrRejectAttendanceQuery(){
        CommonHelper::companyDatabaseConnection('12');
        $id=Input::get('id');
        $status=Input::get('status');
        $data['approval_status']=$status;
        DB::table('attendance_queries')->where('id',$id)->update($data);
        CommonHelper::reconnectMasterDatabase();
    }

//    public function viewEmployeeFuelDetailForm()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $employeeData = Employee::select('emr_no', 'branch_id', 'designation_id')->where([['emr_no','=',Input::get('emr_no')],['status','=',1]])->first();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.AjaxPages.viewEmployeeFuelDetailForm', compact('employeeData'));
//    }
//
//    public function viewEmployeeFuelDetail()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $emr_no = Input::get('emr_no');
//        $employeeFuelData = EmployeeFuelData::where([['emr_no','=',Input::get('emr_no')],['status','=',1]])->orderBy('fuel_date');
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.AjaxPages.viewEmployeeFuelDetail', compact('employeeFuelData', 'emr_no'));
//    }
//
//    public function viewEmployeeFilteredFuelDetail()
//    {
//        $emr_no = Input::get('emr_no');
//        $fuel_month = Input::get('fuel_month');
//        $fuel_year = Input::get('fuel_year');
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $employeeFuelData = EmployeeFuelData::where([['emr_no','=',Input::get('emr_no')],['status','=',1],['fuel_month', '=', Input::get('fuel_month')],['fuel_year', '=', Input::get('fuel_year')]])->orderBy('fuel_date');
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.AjaxPages.viewEmployeeFilteredFuelDetail', compact('employeeFuelData', 'emr_no', 'fuel_month', 'fuel_year'));
//    }
//
//
//    public function viewUpcomingBirthdaysDetail()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $upcoming_birthdays_detail = DB::select( DB::raw("SELECT id,emp_id,emp_name,date_of_birth FROM employee where DATE_FORMAT(date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +1 month), '%m-%d') ORDER BY MONTH(date_of_birth), DAYOFMONTH(date_of_birth)"));
//        CommonHelper::reconnectMasterDatabase();
//
//        return view('Hr.AjaxPages.viewUpcomingBirthdaysDetail',compact('upcoming_birthdays_detail'));
//    }

    public function viewPermanentEmployee()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $permanent_employee = Employee::where([['status','=','1'],['employment_status_id','=',1]])->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewPermanentEmployee',compact('permanent_employee'));
    }

    public function viewEmployeeCnicExpireDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $expireDateOne=date('Y-m-d',strtotime(now() .'+1 month'));
        $cnic_expiry_date_detail = Employee::select('id','emp_name','emp_cnic','emp_id','cnic_expiry_date')
            ->where([['status','=',1],['cnic_expiry_date','<',$expireDateOne],['cnic_expiry_date','>',date('Y-m-d')],['cnic_expiry_date','!=','']]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeCnicExpireDetail',compact('cnic_expiry_date_detail'));
    }

    public function viewEmployeeAttendanceQueriesDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance_queries=DB::table('attendance_queries')->where('status',1)->where('approval_status',1)->orderBy('id','desc');
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeAttendanceQueriesDetail',compact('attendance_queries'));
    }


    // public function viewEmployeeAbsentsDetail()
    // {
    //     CommonHelper::companyDatabaseConnection(Input::get('m'));
    //     $date=date('Y-m-d');
    //     $absents_detail = Attendance::select('id','emp_id','attendance_date','clock_in','clock_out')
    //         ->where([['status','=',1],['attendance_date','<=',$date],['clock_in','=',''],['clock_out','=','']]);
    //     CommonHelper::reconnectMasterDatabase();

    //     return view('Hr.AjaxPages.viewEmployeeAbsentsDetail',compact('absents_detail'));
    // }


    public function viewEmployeeOverAgeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $over_age_employee_detail = DB::select( DB::raw("SELECT id,emp_id,date_of_birth,emp_name FROM employee WHERE status=1 and DATEDIFF(NOW(), date_of_birth) / 365.25 >= 60"));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeOverAgeDetail',compact('over_age_employee_detail'));
    }

    public function viewNonVerifiedNadraEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $nadra = EmployeeGsspDocuments::select('emp_id')->where([['document_type','=','Nadra']]);
        if($nadra->count()):
            $nonVerfiedNadraEmp = $nadra->get()->toArray();
            $nonVerfiedNadraEmpDetail = Employee::select('emp_id','emp_name')->whereNotIn('emp_id', $nonVerfiedNadraEmp)->get()->toArray();
        else:
            $nonVerfiedNadraEmpDetail =array();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewNonVerifiedNadraEmployeeDetail',compact('nonVerfiedNadraEmpDetail'));
    }
    public function viewNonVerifiedPoliceEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $police = EmployeeGsspDocuments::select('emp_id')->where([['document_type','=','Police']]);
        if($police->count()):
            $nonVerfiedPoliceEmp = $police->get()->toArray();
            $nonVerfiedPoliceEmpDetail = Employee::select('emp_id','emp_name')->whereNotIn('emp_id', $nonVerfiedPoliceEmp)->get()->toArray();
        else:
            $nonVerfiedPoliceEmpDetail =array();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewNonVerifiedPoliceEmployeeDetail',compact('nonVerfiedPoliceEmpDetail'));
    }

    public function viewEmployeeMissingImageDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_images= Employee::select('id','emp_name','emp_id')->where([['status','=',1],['img_path','=','app/uploads/employee_images/user-dummy.png']]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeMissingImageDetail',compact('employee_missing_images'));
    }

    public function viewEmployeeGsspVeriDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('emr_no','emp_name')->where([['id','=',Input::get('id')],['status','=',1]])->first();
        $viewEmployeeGsspVeriDetail = EmployeeGsspDocuments::where([['emr_no','=',$employee->emr_no]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeGsspVeriDetail',compact('viewEmployeeGsspVeriDetail'));
    }

    public function viewEmployeeWarningLetterDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $warningLetters = HrWarningLetter::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeWarningLetterDetail',compact('warningLetters'));
    }

    public function viewDemiseEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $DemiseEmployee = EmployeeExit::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDemiseEmployeeDetail',compact('DemiseEmployee'));
    }

    public function viewEmployeeProbationPeriodOverDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $probationEmployees =DB::select( DB::raw("SELECT emp_id,joining_date,emp_name FROM employee
         WHERE employment_status_id = '8' AND status = '1'
         AND joining_date <= DATE_ADD('".date("Y-m-d")."',INTERVAL -6 MONTH)"));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeProbationPeriodOverDetail',compact('probationEmployees'));
    }

    public function viewHrEmployeeAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeAuditDetail = Employee::select('emp_id','emp_name')->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrEmployeeAuditDetail',compact('employeeAuditDetail'));

    }

    public function viewHrLetters()
    {

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $emr_no = Input::get('emp_id');
        $m = Input::get('m');
        $emp_category_id = Input::get('emp_category_id');
        $employee_project_id = Input::get('employee_project_id');
        $department_id = Input::get('department_id');
        $sub_department_id_1 = Input::get('sub_department_id');
        $region_id = Input::get('region_id');
        $letter_id = Input::get('letter_id');
        $show_all = Input::get('show_all');

        if($show_all == 1){
            $employee_all_emrno = HrHelper::getAllEmployeeId(Input::get('m'),$department_id,$region_id,'show_all');
        }
        else{
            $employee_all_emrno = HrHelper::getAllEmployeeId(Input::get('m'),$emp_category_id,$region_id,$employee_project_id);
        }


        if($letter_id == 1)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            if($show_all == 1){
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->whereIn('hr_warning_letter.emp_id',$employee_all_emrno)
                    ->where('hr_warning_letter.status','=',1)
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            else if($emr_no  == 'all'){
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->whereIn('hr_warning_letter.emp_id',$employee_all_emrno)
                    ->where('hr_warning_letter.status','=',1)
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            else{
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->where([['hr_warning_letter.emp_id','=',$emr_no],['hr_warning_letter.status','=',1]])
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrWarningLetterList', compact('hr_warning_letter','operation_rights2'));
        }
        elseif ($letter_id == 2)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));

            $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');

            $employeeCurrentPositions = Employee::select('designation_id','emp_salary', 'joining_date')->where([['emp_id','=',Input::get('emp_id')],['status','!=',2]])->first();
            $designation_id = $employeeCurrentPositions->designation_id;
            $current_salary = $employeeCurrentPositions->emp_salary;
            $performance_from_date = $employeeCurrentPositions->joining_date;

            $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',Input::get('emp_id')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

            $employeeAllowances = Allowance::where([['emp_id','=',$emr_no],['status','=',1]]);
            if($employeeAllowances->count() > 0):
                $employeeAllowances = $employeeAllowances->get();
            endif;

            if($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
                $new_salary = $employeeCurrentPositionsDetail->salary;
                $performance_to_date = $employeeCurrentPositionsDetail->promotion_date;

            else:
                return '<div class="text-center" style="color: red"><table class="table table-bordered"><tr><td>Record not found!!</td></tr></table></div>';
            endif;

            if($employeeCurrentPositions->count() > 1):
                $employeeLastPositions = EmployeePromotion::select('designation_id','salary', 'promotion_date')->where([['emp_id','=',Input::get('emp_id')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
                $employeeLastPositionDetails = $employeeLastPositions->first();
                $designation_id = $employeeLastPositionDetails->designation_id;
                $current_salary = $employeeLastPositionDetails->salary;
                $performance_from_date = $employeeLastPositionDetails->promotion_date;
            endif;

            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrMfmSouthIncrementLetterList', compact( 'hr_mfm_south_increment_letter','operation_rights2','performance_from_date','performance_to_date', 'employeeAllowances', 'current_salary','new_salary', 'designation_id' ));
        }
        elseif ($letter_id == 3)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_mfm_south_without_increment_letter = HrMfmSouthWithoutIncrementLetter::where([['emp_id','=',Input::get('emp_id_no')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrMfmSouthWithoutIncrementLetterList', compact('hr_mfm_south_without_increment_letter','operation_rights2' ));
        }
        elseif ($letter_id == 4)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_contract_conclusion_letter = HrContractConclusionLetter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrContractConclusionLetterList', compact('hr_contract_conclusion_letter','operation_rights2' ));
        }
        elseif ($letter_id == 5)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_termination_format1_letter = HrTerminationFormat1Letter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTerminationFormat1LetterList', compact('hr_termination_format1_letter','operation_rights2' ));
        }
        elseif ($letter_id == 6)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_termination_format2_letter = HrTerminationFormat2Letter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTerminationFormat2LetterList', compact('hr_termination_format2_letter','operation_rights2' ));
        }
        elseif ($letter_id == 7)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_transfer_letter = HrTransferLetter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTransferLetterList', compact('hr_transfer_letter','operation_rights2' ));
        }
        elseif ($letter_id == 8)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            if($show_all == 1){
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->whereIn('hr_warning_letter.emp_id',$employee_all_emrno)
                    ->where('hr_warning_letter.status','=',1)
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            else if($emr_no  == 'all'){
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->whereIn('hr_warning_letter.emp_id',$employee_all_emrno)
                    ->where('hr_warning_letter.status','=',1)
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            else{
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->where([['hr_warning_letter.emp_id','=',$emr_no],['hr_warning_letter.status','=',1]])
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrWarningLetterList', compact('hr_warning_letter','operation_rights2'));
        }
        else
        {
            return;
        }

    }

    public function getEmployeeDateOfJoining()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date_of_joining = Employee::select('joining_date')->where([['emp_id','=',Input::get('emr_no')]])->value('joining_date');
        CommonHelper::reconnectMasterDatabase();
        $data[] = date('F d, Y', strtotime(Input::get('settlement_date')));
        $data[] = date('F d, Y', strtotime($date_of_joining));
        return $data;
    }

    public function getConclusionLettersDate()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date_of_joining = Employee::select('joining_date')->where([['emp_id','=',Input::get('emr_no')]])->value('joining_date');
        CommonHelper::reconnectMasterDatabase();
        $data[] = date('F d, Y', strtotime($date_of_joining));
        $data[] = date('F d, Y', strtotime(Input::get('conclude_date')));
        $data[] = date('F d, Y', strtotime(Input::get('settlement_date')));
        return $data;
    }

    public function getIncrementLettersDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['emp_id','=',Input::get('emr_no')],['status', '=', 1]])->orderBy('id', 'desc');
        $employeeCurrentPositions = Employee::select('designation_id','emp_salary', 'joining_date')->where([['emp_id','=',Input::get('emr_no')],['status','!=',2]])->first();
        $designation_id = $employeeCurrentPositions->designation_id;
        $current_salary = $employeeCurrentPositions->emp_salary;
        $performance_from_date = $employeeCurrentPositions->joining_date;


        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

        $employeeAllowances = Allowance::where([['emp_id','=',Input::get('emr_no')],['status','=',1]]);
        if($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        if($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id','salary', 'promotion_date')->where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails->designation_id;
            $current_salary = $employeeLastPositionDetails->salary;
            $performance_from_date = $employeeLastPositionDetails->promotion_date;
        endif;

        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
            $new_salary = $employeeCurrentPositionsDetail->salary;
            $performance_to_date = $employeeCurrentPositionsDetail->promotion_date;

        else:
            return '1';
        endif;

        CommonHelper::reconnectMasterDatabase();
        $designation_name = Designation::where([['id', '=', $designation_id], ['status', '=', '1']])->select('designation_name')->first();

        $data[] = date('F d, Y', strtotime($performance_from_date));
        $data[] = date('F d, Y', strtotime($performance_to_date));
        $data[] = date('F d, Y', strtotime(Input::get('confirmation_from')));
        $data[] = $designation_name->designation_name;
        $data[] = $new_salary - $current_salary;
//        $data[] = $current_salary;
//        $data[] = $new_salary;
//        $data[] = $employeeAllowances;
        return $data;

    }

    public function getWithoutIncrementLettersDate()
    {
        $data[] = date('F d, Y', strtotime(Input::get('performance_from')));
        $data[] = date('F d, Y', strtotime(Input::get('performance_to')));
        $data[] = date('F d, Y', strtotime(Input::get('confirmation_from')));
        return $data;
    }

    public function getTransferLettersDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_transfer_letter = HrTransferLetter::where([['emp_id','=',Input::get('emr_no')],['status', '=', 1]])->orderBy('id', 'desc');
        $employeeCurrentPositions = Employee::select('designation_id','branch_id')->where([['emp_id','=',Input::get('emr_no')],['status','!=',2]])->first();
        $designation_id = $employeeCurrentPositions->designation_id;
        $transfer_from = $employeeCurrentPositions->branch_id;

        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        $employeeCurrentLocations = EmployeeTransfer::where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

        $employeeAllowances = Allowance::where([['emp_id','=',Input::get('emr_no')],['status','=',1]]);
        if($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        if($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id','salary', 'promotion_date')->where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails->designation_id;
        endif;

        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
        endif;

        if($employeeCurrentLocations->count() > 1):
            $employeeLastLocation = EmployeeTransfer::select('location_id')->where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastLocationDetails = $employeeLastLocation->first();
            $transfer_from = $employeeLastLocationDetails->location_id;
        endif;

        if($employeeCurrentLocations->count() > 0):
            $employeeCurrentLocationsDetail = $employeeCurrentLocations->first();
            $transfer_to = $employeeCurrentLocationsDetail->location_id;
        else:
            return '1';
        endif;

        CommonHelper::reconnectMasterDatabase();
        $designation_name = Designation::where([['id', '=', $designation_id], ['status', '=', '1']])->select('designation_name')->first();
        $transfer_from = Locations::where([['id', '=', $transfer_from], ['status', '=', '1']])->select('employee_location')->first();
        $transfer_to = Locations::where([['id', '=', $transfer_to], ['status', '=', '1']])->select('employee_location')->first();


        $data[] = $transfer_from->employee_location;
        $data[] = $transfer_to->employee_location;
        $data[] = date('F d, Y', strtotime(Input::get('transfer_date')));
        $data[] = $designation_name->designation_name;

        return $data;

    }

    public function viewHrLetterFiles()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hrLettersFile = LetterFiles::where([['id','=',Input::get('id')],['status','=',1]])->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrLetterFiles', compact('hrLettersFile'));
    }

    public function viewEmployeeEquipmentsForm()
    {
        $equipment = null;
        $employeeEquipment = Equipments::where([['company_id','=',Input::get('m')],['status','=',1]])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id','emp_id', 'eobi_number', 'eobi_path')->where([['emp_id','=',Input::get('emp_id')],['status','=',1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeEquipmentsForm', compact('employee','equipment', 'employeeEquipment'));
    }

    public function viewEmployeeEquipmentsDetail()
    {
        $equipment_detail = null;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_emr_no = EmployeeEquipments::where([['id','=', Input::get('id')]])->first();

        $emr_no = $employee_emr_no->emp_id;
        $employee = Employee::select('id','eobi_number', 'eobi_path')->where([['emp_id','=',$emr_no],['status','!=',2]])->first();
        $employeeEquipment  = EmployeeEquipments::where([['emp_id','=', $emr_no]])->pluck('equipment_id')->toArray();

        if(EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emp_id','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->exists()):
            $equipment_detail = EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emp_id','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->first();
        endif;

        $employee_eobi_copy = Employee::where([['emp_id','=',$emr_no],['status','!=',2],['eobi_path', '!=', null]]);
        $employee_insurance_copy = Employee::where([['emp_id','=',$emr_no],['status','!=',2]]);

        CommonHelper::reconnectMasterDatabase();
        $equipment = Equipments::where([['status','=', 1]])->orderBy('id')->get();

        return view('Hr.AjaxPages.viewEmployeeEquipmentsDetail', compact('employeeEquipment', 'emr_no', 'equipment', 'employee', 'equipment_detail', 'employee_insurance_copy', 'employee_eobi_copy'));

    }

    public function viewEmployeePreviousAllowancesDetail()
    {
        $emr_no = Input::get('emr_no');
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowances = Allowance::where([['emp_id', '=', $emr_no]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePreviousAllowancesDetail',compact('allowances'));
    }

    public function viewHrWarningLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_warning_letter = HrWarningLetter::where([['id','=',$id]])->orderBy('id', 'desc');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrWarningLetter', compact('hr_warning_letter', 'operation_rights2'));
    }

    public function viewHrMfmSouthIncrementLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();

        $emr_no = $hr_mfm_south_increment_letter['emr_no'];
        $employeeCurrentPositions = Employee::select('designation_id','emp_salary', 'joining_date')->where([['emr_no','=',$emr_no],['status','=',1]])->first();
        $designation_id = $employeeCurrentPositions['designation_id'];
        $current_salary = $employeeCurrentPositions['emp_salary'];

        $employeeAllowances = Allowance::where([['emp_id','=',$emr_no],['status','=',1]]);
        if($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',$emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

        if($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id','salary', 'promotion_date')->where([['emp_id','=',$emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first()->toArray();
            $designation_id = $employeeLastPositionDetails['designation_id'];
            $current_salary = $employeeLastPositionDetails['salary'];
        endif;

        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
            $new_salary = $employeeCurrentPositionsDetail['salary'];

        else:
            return '<div class="text-center" style="color: red"><table class="table table-bordered"><tr><td>Record not found!!</td></tr></table></div>';
        endif;
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewHrMfmSouthIncrementLetter', compact('hr_mfm_south_increment_letter','designation_id', 'current_salary', 'new_salary', 'employeeAllowances', 'operation_rights2' ));
    }

    public function viewHrMfmSouthWithoutIncrementLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_mfm_south_without_increment_letter = HrMfmSouthWithoutIncrementLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrMfmSouthWithoutIncrementLetter', compact('hr_mfm_south_without_increment_letter', 'operation_rights2' ));
    }

    public function viewHrContractConclusionLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_contract_conclusion_letter = HrContractConclusionLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrContractConclusionLetter', compact('hr_contract_conclusion_letter', 'operation_rights2' ));
    }

    public function viewHrTerminationFormat1Letter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_termination_format1_letter = HrTerminationFormat1Letter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTerminationFormat1Letter', compact('hr_termination_format1_letter', 'operation_rights2' ));
    }

    public function viewHrTerminationFormat2Letter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_termination_format2_letter = HrTerminationFormat2Letter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTerminationFormat2Letter', compact('hr_termination_format2_letter','operation_rights2' ));
    }

    public function viewHrTransferLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_transfer_letter = HrTransferLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        $emr_no = $hr_transfer_letter['emr_no'];

        $employeeCurrentPositions = Employee::select('designation_id')->where([['emp_id','=',$emr_no],['status','=',1]])->first();
        $designation_id = $employeeCurrentPositions['designation_id'];

        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',$emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

        if($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id')->where([['emp_id','=', $emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails['designation_id'];
        endif;

        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTransferLetter', compact('hr_transfer_letter','operation_rights2', 'designation_id' ));
    }

    public function viewEmployeeCnicCopy()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[0];
        // echo "<pre>";
        // print_r($array);
        // exit();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = EmployeeDocuments::select('id','emp_id','emp_name')->where([['employee_id','=',$emr_no],['status','=',1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeCnicCopy', compact('employee'));
    }

    public function viewEmployeeExperienceDocuments()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_experience = EmployeeWorkExperience::select('id','emp_id','work_exp_path','work_exp_name', 'work_exp_type')->where([['emp_id','=',$emr_no],['status','=',1],['work_exp_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeExperienceDocuments', compact('employee_experience'));
    }

    public function checkCnicNoExist()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $employee_count = Employee::where([['emp_cnic','=',Input::get('emp_cnic')],['status','=','1']])->count();

        CommonHelper::reconnectMasterDatabase();

//        if($employee_count > 0 ):
//            echo "CNIC No. ".Input::get('emp_cnic')." Already Exist !";
//        else:
//            echo "success";
//        endif;
        echo "success";

    }

    public function viewMasterTableForm()
    {
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewMasterTableForm', compact('departments'));
    }

    public function viewDayWiseAttendence()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status','=', 1]])->select('id', 'emp_id', 'emp_name')
            ->whereIn('region_id',$regions)
            ->orderBy('id','desc')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDayWiseAttendence', compact('employees'));
    }

    public function viewMonthWiseAttendence()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])
            ->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.AjaxPages.viewMonthWiseAttendence', compact('employee_category', 'employee_regions'));
    }

    public function viewUploadFileAttendance()
    {
        return view('Hr.AjaxPages.viewUploadFileAttendance', compact('employees'));
    }

    public function viewEmployeeEobiCopy()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id','emp_id','emp_name','eobi_path', 'eobi_type')->where([['emp_id','=',$emr_no],['status','!=', 2],['eobi_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeEobiCopy', compact('employee'));
    }

    public function viewEmployeeInsuranceCopy()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id','emp_id','emp_name','insurance_path', 'insurance_type')->where([['emp_id','=',$emr_no],['status','!=', 2],['insurance_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeInsuranceCopy', compact('employee'));
    }

    public function viewEmployeeEobiDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_eobi= Employee::select('emp_name','emp_id','eobi_path')->where([['status','=',1],['eobi_path','=',null]]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeEobiDetail',compact('employee_missing_eobi'));
    }

    public function viewEmployeeInsuranceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_insurance= Employee::select('emp_name','emp_id','insurance_path')->where([['status','=',1],['insurance_path','=',null]]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeInsuranceDetail',compact('employee_missing_insurance'));
    }
    public function viewEmployeeProbationExpireDetail(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date =date("Y-m-d");
        $employeesProbationExpires=Employee::select('id','emp_id','joining_date','emp_name','probation_expire_date','employment_status_id')->where([['probation_expire_date','<=',$date],['status','=',1],['employment_status_id','!=',7]])->get();


        return view('Hr.AjaxPages.viewEmployeeProbationExpireDetail',compact('employeesProbationExpires'));
    }
    public function viewEmployeeSettlementDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $settlementDate =date('Y-m-d',strtotime(now() .'+2 days'));
        $settlementTermination1= HrTerminationFormat1Letter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']]);
        $settlementTermination2= HrTerminationFormat2Letter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']]);
        $settlementContract= HrContractConclusionLetter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']]);

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeSettlementDetail',compact('settlementTermination1','settlementTermination2','settlementContract'));
    }

    public function viewEmployeeMedicalDocuments()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_medical = EmployeeMedical::select('emp_id')->where([['id','=',Input::get('id')],['status','=',1]])->first();
        $employeeMedicalDocuments = EmployeeMedicalDocuments::where([['emp_id','=',$employee_medical->emr_no],['status','=',1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeMedicalDocuments', compact('employee_medical','employeeMedicalDocuments'));
    }

    public function getMoreEmployeesDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '!=', '2'],['status','=',1],['emp_id','>',Input::get('lastId')]])
            ->select('id','emp_sub_department_id','emp_id','emp_name','emp_salary','emp_contact_no','joining_date', 'emp_cnic','date_of_birth','status','emp_department_id')
            ->offset(0)
            ->limit(50)
            ->orderBy('emp_id','asc')->get();

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.getMoreEmployeesDetail', compact('employees'));

    }

    public function viewTrainingDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $trainingsData = Trainings::where([['status','=',1],['id', Input::get('id')]])->first();
        $TrainingCertificate = TrainingCertificate::where([['status','=',1],['training_id', Input::get('id')]])->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTrainingDetail', compact('employee_regions','employee_category','employee_locations', 'trainingsData','employee','TrainingCertificate'));

    }

    public function viewFinalSettlement()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loan_amount = '';
        $gratuityAmount = '';
        $allowance_amount = '';
        $previous_loan_amount = '';
        $emp_id = Input::get('emp_id');

        if(EmployeeExit::where([['emp_id', '=', $emp_id]])->exists()):

            $employee = Employee::where([['status', '!=', 2], ['emp_id', '=', $emp_id]])->select('designation_id','joining_date', 'emp_salary','employee_project_id')->first();
            $designation_id = $employee->designation_id;
            $salary = $employee->emp_salary;

            $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',Input::get('emp_id')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
            if($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
                $salary = $employeeCurrentPositionsDetail->salary;
            endif;

            //multiple loan
            $loan = LoanRequest::where([['emp_id', '=', $emp_id],['approval_status', '=', 2],['loan_status', '=', 0],['status', '=', 1]])->orderBy('id', 'desc');
            if($loan->count() > 0):
                foreach($loan->get() as $val):
                    if(Payroll::where([['emp_id', '=', $emp_id],['loan_id', '=', $val->id]])->exists()):
                        $payroll_deducted_amount = Payroll::where([['emp_id', '=', $emp_id],['loan_id', '=', $val->id]])->sum('loan_amount_paid');
                        if($payroll_deducted_amount < $val->loan_amount):
                            $loan_amount += $val->loan_amount - $payroll_deducted_amount;
                        endif;
                    else:
                        $loan_data = LoanRequest::select('loan_amount')->where([['id', '=', $val->id]])->first();
                        $loan_amount += $loan_data->loan_amount;
                    endif;
                endforeach;
            endif;

            $gratuity = Gratuity::where([['emp_id', Input::get('emp_id')]])->orderBy('id','desc');
            if($gratuity->exists()):
                $gratuityDetails = $gratuity->first();
                $gratuityAmount = $gratuityDetails->gratuity;
            endif;
            $exit_data = EmployeeExit::where([['status', '=', 1], ['emp_id', '=', $emp_id]])->select('leaving_type', 'last_working_date')->first();
            $final_settlement = FinalSettlement::where([['status', '=', 1], ['emp_id', '=', $emp_id]]);
            CommonHelper::reconnectMasterDatabase();
            $count = $final_settlement->count();
            $final_settlement_data = $final_settlement->first();
            return view('Hr.AjaxPages.viewFinalSettlement', compact('employee','gratuityAmount', 'allowance_amount','exit_data', 'final_settlement_data', 'count', 'salary','loan_amount','previous_loan_amount', 'designation_id'));
        else:
            return "<div class='row'>&nbsp</div><div class='text-center' style='color: red; font-size: 18px;'>Create Exit Clearance Form First</div>";
        endif;

    }


    public function viewEmployeeGratuityForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if(Input::get('show_All') == "yes"):
            $employee = Employee::where([['status', '!=', 2]])
                ->select('joining_date', 'emp_salary','emp_id','emp_name','region_id','employee_category_id','employee_project_id')->orderBy("emp_id")
                ->get()->toArray();
//            if(Input::get('emr_no') == 'all_emp'):
//                $employee = Employee::where([['region_id','=',Input::get('region_id')],['employee_category_id','=',Input::get('emp_category_id')],
//                    ['status', '!=', 2]])
//                    ->select('joining_date', 'emp_salary','emr_no','emp_name','region_id','employee_category_id')->orderBy("emr_no")
//                    ->get()->toArray();
//            else:
//
//                $employee = Employee::where([['status', '!=', 2], ['emr_no', '=',  Input::get('emr_no')]])
//                    ->select('joining_date', 'emp_salary','emr_no','emp_name','region_id','employee_category_id')->orderBy("emr_no")
//                    ->get()->toArray();
//            endif;

        elseif(Input::get('employee_project_id') !== '0'):
            $employee = Employee::where([['region_id','=',Input::get('region_id')],['employee_category_id','=',Input::get('emp_category_id')],['status', '!=', 2],['employee_project_id','=', Input::get('employee_projest_id')]])->select('joining_date', 'emp_salary','emp_id','emp_name','region_id','employee_category_id','employee_project_id')->orderBy("emp_id")->get()->toArray();
        else:
            $employee = Employee::where([['region_id','=',Input::get('region_id')],['employee_category_id','=',Input::get('emp_category_id')],['status', '!=', 2]])->select('joining_date', 'emp_salary','emp_id','emp_name','region_id','employee_category_id','employee_project_id')->orderBy("emp_id")->get()->toArray();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeGratuityForm', compact('employee'));

    }


    public function viewDashboardDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '!=', '2']])->count();
        $employees_onboard = Employee::where([['status', '=', '1']])->count();
        $employees_exit = EmployeeExit::where([['status', '=', '1'],['approval_status', '=', 2]])->count();

        CommonHelper::reconnectMasterDatabase();
        $projects  = EmployeeProjects::where([['status', '=', '1']])->count();
        $departments =  Department::where([['status', '=', '1']])->count();
        $subDepartments = SubDepartment::where([['status', '=', '1']])->count();
        return compact('employees','employees_onboard','employees_exit','projects','departments','subDepartments');
    }

    public function viewAdvanceSalaryDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $advance_salary = AdvanceSalary::select('*')->where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');
        return view('Hr.AjaxPages.viewAdvanceSalaryDetail',compact('advance_salary','employees'));
    }

    public function viewAllowanceDetail()
    {
        $company_id =Input::get('m');

        CommonHelper::companyDatabaseConnection($company_id);
        $allowance = Allowance::where([['employee_id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');
        $allowance_types = Cache::get('allowance_types');

        return view('Hr.AjaxPages.viewAllowanceDetail',compact('allowance','employees','allowance_types','company_id'));
    }

    public function viewDeductionDetail()
    {
        $id=Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $deduction = Deduction::where([['id','=',$id]])->orderBy('id')->first();

        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');

        return view('Hr.AjaxPages.viewDeductionDetail',compact('deduction','employees'));
    }

    // public function viewHolidaysDetail()
    // {
    //     CommonHelper::companyDatabaseConnection(Input::get('m'));
    //     $holidays = Holidays::where([['id','=',Input::get('id')]])->orderBy('id')->first();
    //     CommonHelper::reconnectMasterDatabase();
    //     return view('Hr.AjaxPages.viewHolidaysDetail',compact('holidays'));
    // }

    public function viewEmployeePromotionDetailForLog()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeePromotions = EmployeePromotion::where([['id','=',Input::get('id')],['status','=',1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePromotionDetailForLog', compact('employeeData','employeePromotions'));

    }

    public function viewEmployeeTransferDetailForLog()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeTransfers = EmployeeTransfer::where([['id','=',Input::get('id')],['status','=',1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeTransferDetailForLog', compact('employeeData','employeeTransfers'));
    }

//    public function viewLeaveApplicationRequestDetailForLog()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $leaveApplication = LeaveApplication::where([['id','=',Input::get('id')]])->first();
//        $leaveApplicationData = LeaveApplicationData::where([['leave_application_id','=',Input::get('id')]])->first();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.AjaxPages.viewLeaveApplicationRequestDetailForLog', compact('leaveApplication','leaveApplicationData'));
//    }

    public function employeeGetLeavesBalances()
    {

        if(Input::get('company_id') == 'all'):

            $companiesList = DB::Table('company')->select('id','name')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id','name')->where([['id','=',Input::get('company_id')]])->get()->toArray();

        endif;
        $LeavePolicy = LeavesPolicy::where([['status','=',1]])->get();
        return view('Hr.AjaxPages.employeeGetLeavesBalances',compact('companiesList','LeavePolicy'));
    }

    public function viewRangeWiseLeaveApplicationsRequests()
    {

        $m = Input::get('m');
        $gm_Approvals = Input::get('gm_Approvals');
        echo $department_id = Input::get('department_id');
        echo $sub_department_id_1 = Input::get('sub_department_id_1');
        $all_employee =  HrHelper::getAllEmployeeId($m,$department_id,$sub_department_id_1);
        if(input::get('employee_id') == 'all' && $gm_Approvals == 1):
            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*','leave_application_data.no_of_days')
                ->where([['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.view','=','yes'],['leave_application.status','=',1],['leave_application.approval_status_lm','=',Input::get('LeavesStatus')]])
                ->whereIn('leave_application.emp_id', $all_employee)
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'),Input::get('toDate')])
                ->orwhere([['first_second_half_date','>=', Input::get('fromDate')],['first_second_half_date','<=',Input::get('toDate')],
                    ['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.view','=','yes']])
                ->get();

        elseif(input::get('employee_id') == 'all' && $gm_Approvals != 1):

            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.view','=','yes'],['leave_application.status','=',1]])
                ->whereIn('leave_application.emp_id', $all_employee)
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'),Input::get('toDate')])
                ->orwhere([['first_second_half_date','>=', Input::get('fromDate')],['first_second_half_date','<=',Input::get('toDate')],
                    ['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.view','=','yes']])
                ->get();
        elseif(input::get('employee_id') != 'all' && $gm_Approvals == 1):

            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.view','=','yes'],['leave_application.emp_id','=',Input::get('employee_id')],['leave_application.status','=',1],['leave_application.approval_status_lm','=',Input::get('LeavesStatus')]])
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'),Input::get('toDate')])
                ->orwhere([['first_second_half_date','>=', Input::get('fromDate')],['first_second_half_date','<=',Input::get('toDate')],
                    ['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.view','=','yes']])
                ->get();
        elseif(input::get('employee_id') != 'all' && $gm_Approvals != 1):

            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.view','=','yes'],['leave_application.emp_id','=',Input::get('employee_id')],['leave_application.status','=',1]])
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'),Input::get('toDate')])
                ->orwhere([['first_second_half_date','>=', Input::get('fromDate')],['first_second_half_date','<=',Input::get('toDate')],
                    ['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.view','=','yes']])
                ->get();
        else:
            $leave_application_request_list = DB::table('leave_application')
                ->select('leave_application.*')
                ->where([['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.emp_id','=',Input::get('employee_id')],['leave_application.view','=','yes'],['leave_application.status','=',1]])
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->whereBetween('leave_application_data.from_date', [Input::get('fromDate'),Input::get('toDate')])
                ->orwhere([['first_second_half_date','>=', Input::get('fromDate')],['first_second_half_date','<=',Input::get('toDate')],
                    ['leave_application.approval_status','=',Input::get('LeavesStatus')],['leave_application.emp_id','=',Input::get('employee_id')],['leave_application.view','=','yes']])->get();
        endif;

        return view('Hr.AjaxPages.viewRangeWiseLeaveApplicationsRequests', compact('leave_application_request_list'));
    }

    public function viewTransferLetter(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $transfer_letter = transferLetter::where('emp_location_id','=',$id)->get();
        $EmployeeTransfer = EmployeeTransfer::where('id','=',$id)->first();
        $employee = Employee::where('emp_id','=',$EmployeeTransfer->emr_no)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTransferLetter', compact('transfer_letter','employee'));

    }

    public function viewPromotionLetter(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $promotion_letter = PromotionLetter::where('promotion_id','=',$id)->get();
        $EmployeePromotion = EmployeePromotion::where('id','=',$id)->first();
        $employee = Employee::where('id','=',$EmployeePromotion->employee_id)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewPromotionLetter', compact('promotion_letter','employee','EmployeePromotion'));
    }

    public function viewPreviousEmployeeProject(){
        $emr_no =Input::get('emr_no');
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        $transferEmployeeProject = transferEmployeeProject::where([['status','=',1],['emr_no','=',$emr_no]])->first();
        $check_employee_salary = EmployeePromotion::where([['emr_no','=',Input::get('emr_no')],['status','=',1]])->first();

        if(count($transferEmployeeProject) != '0' && count($check_employee_salary) == '0' ){
            $employee = DB::table('transfer_employee_project')
                ->join('employee','transfer_employee_project.emr_no','=','employee.emr_no')
                ->select('employee.emp_salary','employee.designation_id','employee.grade_id','transfer_employee_project.employee_project_id','transfer_employee_project.date')
                ->where([['transfer_employee_project.emr_no','=',$emr_no],['transfer_employee_project.status','=',1],['employee.status','=',1]])
                ->orderBy('transfer_employee_project.id','desc')
                ->first();
            $salary = $employee->emp_salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }
        else if(count($transferEmployeeProject) == '0' && count($check_employee_salary) != '0' ){
            $employee = DB::table('employee_promotion')
                ->join('employee','employee_promotion.emr_no','=','employee.emr_no')
                ->select('employee_promotion.salary','employee_promotion.designation_id','employee_promotion.grade_id','employee.employee_project_id','employee_promotion.date')
                ->where('employee.emr_no','=',$emr_no)
                ->where('employee_promotion.status','=',1)
                ->where('employee.status','=',1)
                ->orderBy('employee_promotion.id','desc')
                ->first();
            $salary = $employee->salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }
        else if(count($transferEmployeeProject) != '0' && count($check_employee_salary) != '0' ){
            $employee = DB::table('employee_promotion')
                ->join('transfer_employee_project','employee_promotion.emr_no','=','transfer_employee_project.emr_no')
                ->select('employee_promotion.salary','employee_promotion.designation_id','employee_promotion.grade_id','transfer_employee_project.employee_project_id','transfer_employee_project.date')
                ->where('transfer_employee_project.emr_no','=',$emr_no)
                ->where('employee_promotion.status','=',1)
                ->where('transfer_employee_project.status','=',1)
                ->orderBy('transfer_employee_project.id','desc')
                ->first();
            $salary = $employee->salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }
        else{
            $employee = Employee::where([['emr_no','=',$emr_no],['status','=',1]])->first();
            $salary = $employee->emp_salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewPreviousEmployeeProject', compact('salary','designation_id','grade_id','employee_project','date'));
    }

    public function checkManualLeaves(){
        $value = Input::get('value');
        $leave_type = Input::get('leave_type');
        $m = Input::get('m');
        $emr_no = Input::get('emr_no');
        $error_status = Input::get('error_status');
        CommonHelper::companyDatabaseConnection($m);
        $emp_leave_policy = Employee::where([['emp_id','=',$emr_no],['leaves_policy_id','!=',0]])->first();
        CommonHelper::reconnectMasterDatabase();
        $LeavesData = LeavesData::where([['leaves_policy_id','=',$emp_leave_policy->leaves_policy_id],['leave_type_id','=',$leave_type]])->value('no_of_leaves');
        if($value > $LeavesData){
            echo 'Your'.' '.$error_status.' '.'is greater than your leave policy';
        }
        else{
            echo 'done';
        }

    }

    public function getPendingLeaveApplicationDetail()
    {
        $getPendingLeaveApp = LeaveApplication::select('emp_id','id')->where([[Input::get('type'),'=',1]])->orderBy('id','desc')->offset(0)->limit(1);

        if($getPendingLeaveApp->count() == 0):
            return 0;
        endif;

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp = Employee::select('id','leaves_policy_id','designation_id')->where([['emp_id', '=',$getPendingLeaveApp->value('emp_id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $leave_day_type = Input::get('leave_day_type');

        if(Input::get('leave_day_type') == 1):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emp_id','leave_application.leave_address','leave_application.approval_status','leave_application.approval_status_lm','leave_application.reason','leave_application.status','leave_application_data.no_of_days','leave_application_data.date','leave_application_data.from_date','leave_application_data.to_date')
                ->where([['leave_application.id','=',$getPendingLeaveApp->value('id')]])->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        elseif(Input::get('leave_day_type') == 2):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emp_id','leave_application.leave_address','leave_application.approval_status','leave_application.approval_status_lm','leave_application.reason','leave_application.status','leave_application_data.first_second_half','leave_application_data.date','leave_application_data.first_second_half_date')
                ->where([['leave_application.id','=',$getPendingLeaveApp->value('id')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        else:
            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emp_id','leave_application.approval_status','leave_application.approval_status_lm','leave_application.leave_address','leave_application.reason','leave_application.status','leave_application_data.short_leave_time_from','leave_application_data.short_leave_time_to',
                    'leave_application_data.date','leave_application_data.short_leave_date')
                ->where([['leave_application.id','=',$getPendingLeaveApp->value('id')]])->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        endif;

        $approval_array[1] = '<span class="label label-warning">Pending</span>';
        $approval_array[2] = '<span class="label label-success">Approved</span>';
        $approval_array[3] = '<span class="label label-danger">Rejected</span>';

        $approval_status = $approval_array[$leave_application_data->approval_status];
        $approval_status_lm = $approval_array[$leave_application_data->approval_status_lm];
        CommonHelper::reconnectMasterDatabase();
        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*','leaves_data.*')
            ->where([['leaves_policy.id','=',$emp->leaves_policy_id]])
            ->get();


        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $emp_data = Employee::select('emp_name', 'emp_sub_department_id', 'designation_id', 'emp_id','leaves_policy_id')->where([['id', '=', $emp->id]])->orderBy('id')->first();

        CommonHelper::reconnectMasterDatabase();

        $designation_name = Designation::where([['id','=',$emp->designation_id]])->value('designation_name');
        $data['designation_name']       = $designation_name;
        $data['leave_day_type']         = $leave_day_type;
        $data['leave_application_data'] = $leave_application_data;
        $data['approval_status']        = $approval_status;
        $data['approval_status_lm']      = $approval_status_lm;
        $data['emp_data']               = $emp_data;
        $data['leave_type_name']        = Input::get('leave_type_name');
        $data['leave_day_type_label']   = $leave_day_type_label;
        $data['leaves_policy']          = $leaves_policy;
        $data['leave_application_id']   =  $getPendingLeaveApp->value('id');

        return view('Hr.AjaxPages.getPendingLeaveApplicationDetail')->with($data);
    }

    public function viewProjectLetter(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $project_letter = projectTransferLetter::where([['emp_project_id','=',$id],['status','=',1]])->get();
        $TransferEmployeeProject = TransferEmployeeProject::where('id','=',$id)->first();
        $employee = Employee::where('emr_no','=',$TransferEmployeeProject->emr_no)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewProjectLetter', compact('project_letter','employee'));
    }
    public function viewManualAttendanceForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.AjaxPages.viewManualAttendanceForm',compact('locations','departments','sub_departments','category','projects','employees'));

    }
    public function viewEmployeeManualAttendance()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = Input::get('location_id');
        $working_hours_policy_id=Input::get('working_hours_policy_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $company_id = Input::get('m');
        $employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);

        $period = new DatePeriod(new DateTime($from_date), new DateInterval('P1D'), new DateTime($to_date. '+1 day'));
        foreach ($period as $date) {
            $dates[$date->format("Y-m-d")] = $date->format("Y-m-d");
        }

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeManualAttendance',compact('dates','employees'));
    }

    public function viewUploadAttendanceFileForm()
    {
        $locations = Cache::get('locations');
        return view('Hr.AjaxPages.viewUploadAttendanceFileForm',compact('locations'));
    }

    public  function viewLeaveApplicationDateWise()
    {

        $data = Input::get('id');
        $dataFilter = explode(',',$data);

        $emp_id = $dataFilter[0];
        $from_date = $dataFilter[1];
        $to_date =  $dataFilter[2];
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $attendance2 = DB::table('attendance')->where([['attendance.emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->get();
        //print_r($attendance2);
        $totalOffDates[] = '';

        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$emp_id]])->value('day_off');
        // $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['emp_id','=',$emp_id]]);
        $day_off_emp =  explode('=>',$day_off_emp);

        $total_days_off = Attendance::select('attendance_date')
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->where([['emp_id','=',$emp_id]])
            ->wherein('day',[$day_off_emp[1],$day_off_emp[0]]);

        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;

        $monthly_holidays[] = '';
        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);
        if($get_holidays->count() > 0):
            foreach($get_holidays->get() as $value2):

                $monthly_holidays[]=$value2['holiday_date'];
            endforeach;

        else:
            $monthly_holidays =array();
        endif;
        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
        $dates = array();
        foreach ($attendance2 as $value) {
            CommonHelper::reconnectMasterDatabase();

            $LikeDate = "'".'%'.$value->year."-".$value->month.'%'."'";

            $leave_application_request_list = DB::select('select leave_application.*,leave_application_data.* from leave_application
                            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                            WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value->emp_id.' AND leave_application.status = 1
                            AND leave_application.view = "yes"
                            OR leave_application_data.to_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value->emp_id.' AND leave_application.status = 1  AND
                            leave_application.view = "yes"
                            OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.emp_id = '.$value->emp_id.'
                            ');

            //   CommonHelper::reconnectMasterDatabase();

            $leaves_from_dates2 = [];
            if(!empty($leave_application_request_list)):
                foreach($leave_application_request_list as $value3):
                    $leaves_from_dates = $value3->from_date;
                    $leaves_to_dates = $value3->to_date;
                    $leaves_type=$value3->leave_type;
                    $leaves_from_dates2[] = $value3->from_date;

                    $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));


                    foreach ($period as $date) {
                        $dates[] = $date->format("Y-m-d");
                    }

                endforeach;

            endif;
        }

        $monthly_holidays = array_merge($monthly_holidays,$dates);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewLeaveApplicationDateWise',compact('leave_application_request_list'));
    }

    public function  viewEarlyGoingDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $emp_id = $dataFilter[0];
        $month_data = $dataFilter[1];

        $monthDataFilter = explode('/',$month_data);
        // print_r($monthDataFilter);
        //     die;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $totalOffDates[] = '';
        $emp_data =Employee::select('day_off','working_hours_policy_id')->where([['emp_id','=',$emp_id]]);
        CommonHelper::reconnectMasterDatabase();
        $working_policy_data = WorkingHoursPolicy::where([['id','=',$emp_data->value('working_hours_policy_id')]])->get()->toArray();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $day_off_emp = $emp_data->value('day_off');
        $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['emp_id','=',$emp_id]]);

        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;
        $monthly_holidays[] ='';
        $get_holidays = Holidays::select('holiday_date')->where([['status','=',1]])->whereBetween('holiday_date',[$monthDataFilter[1],$monthDataFilter[2]]);
        if($get_holidays->count() > 0) {
            foreach ($get_holidays->get() as $value2) {
                $monthly_holidays[] = $value2['holiday_date'];
            }
        }
        else{
            $monthly_holidays =array();
        }
        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
        $endWorkTime = strtotime($working_policy_data[0]['end_working_hours_time']);
        $dutyEndTime2 = date('H:i', $endWorkTime);

        $ealryGoingData = Attendance::where([['emp_id','=',$emp_id],['clock_out','<', $dutyEndTime2],['clock_in','!=',''],['clock_out','!=','']])
            ->whereNotIn('attendance_date', $monthly_holidays)
            ->whereBetween('attendance_date',[$monthDataFilter[1],$monthDataFilter[2]])
            ->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEarlyGoingDetail',compact('ealryGoingData','dutyEndTime2'));
    }

    function  attendanceProgressFilteredList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-',Input::get('month_year'));
        $accType = Input::get('accType');
        $acc_emp_id = Input::get('acc_emp_id');
        if($accType == 'user'){
            $attendanceProgress = DB::table('payroll_data')
                ->join('employee', 'employee.id', '=', 'payroll_data.employee_id')
                ->select('employee.sub_department_id', 'payroll_data.*')
                ->where([['payroll_data.status', '=', '1'],['payroll_data.month', '=', $month_year[1]],['employee.id',$acc_emp_id],
                    ['payroll_data.year', '=', $month_year[0]]])
                ->orderBy('employee..sub_department_id', 'asc')
                ->orderBy('employee.emp_id', 'asc')
                ->orderBy('payroll_data.month', 'desc')
                ->orderBy('payroll_data.year', 'desc');

        }
        else{
            $attendanceProgress = DB::table('payroll_data')
                ->join('employee', 'employee.id', '=', 'payroll_data.employee_id')
                ->select('employee.sub_department_id', 'payroll_data.*')
                ->where([['payroll_data.status', '=', '1'],['payroll_data.month', '=', $month_year[1]],
                    ['payroll_data.year', '=', $month_year[0]]])
                ->orderBy('employee.sub_department_id', 'asc')
                ->orderBy('employee.emp_id', 'asc')
                ->orderBy('payroll_data.month', 'desc')
                ->orderBy('payroll_data.year', 'desc');
        }


        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAttendanceProgressFilteredList',compact('attendanceProgress'));

    }

    public function viewPendingRequests()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $pomotion = EmployeePromotion::where([['status', '=', '1'],['approval_status', '=', 1]])->count();
        $loan=DB::table('loan_request')->where('approval_status',1)->where('status',1)->count();
        CommonHelper::reconnectMasterDatabase();
        $leaves = LeaveApplication::where([['status', '=', '1'],['approval_status', '=', 1]])->count();
        return view('Hr.AjaxPages.viewPendingRequests',compact('loan', 'pomotion','loan','leaves'));
    }

    public function viewPoliciesDetail()
    {
        $policies = Policies::where([['id', '=', Input::get('id')]]);
        return view('Hr.AjaxPages.viewPoliciesDetail',compact('policies'));
    }

    public function viewProvidentFundReport()
    {
        if(Input::get('company_id') == 'all'):

            $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1]])->orderBy('order_by_no','asc')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1],['id','=',Input::get('company_id')]])->get()->toArray();

        endif;

        return view('Hr.AjaxPages.viewProvidentFundReport',compact('companiesList'));


    }

    public function viewEmployeeProvidentFundReport()
    {

        $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1],['id','=',Input::get('m')]])->get()->toArray();
        $SubDepartment = DB::Table('sub_department')->select('id','sub_department_name')->where([['id','=', Input::get('sub_department_id')]])->first();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $Employees = Employee::select('emp_name','emp_id','provident_fund_id')->where([['acc_no','=',Input::get('employee_id')]])->first();
        CommonHelper::reconnectMasterDatabase();

//        print_r($Employees);
//        die();
        return view('Hr.AjaxPages.viewEmployeeProvidentFundReport',compact('companiesList','SubDepartment','Employees'));

    }

    public function disburseProvidentFundForm()
    {

        $acc_no_and_name = explode('|',Input::get('id'));

        CommonHelper::companyDatabaseConnection($acc_no_and_name[3]);
        $total_pf_amount = DB::table("provident_fund_data")
            ->select(DB::raw("SUM(pf_amount) as pf_amount"))
            ->where([['emp_id','=',$acc_no_and_name[0]],['amount_type','=','plus']])
            ->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.disburseProvidentFundForm',compact('total_pf_amount','acc_no_and_name'));

    }

    public function deleteLeavesDataPolicyRows(){

        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table('leaves_policy')
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);

        DB::table('leaves_data')
            ->where('leaves_policy_id', Input::get('recordId'))
            ->update($updateDetails);
    }

    public function viewHolidayDetails(){
        $id = Input::get('id');
        $explode_data = explode("_",$id);
        $emp_id = $explode_data[0];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$explode_data[0]]])->value('day_off');


        $day_off_emp =  explode('=>',$day_off_emp);

        $total_days_off = Attendance::select('attendance_date')
            ->whereBetween('attendance_date',[$explode_data[1],$explode_data[2]])
            ->where('emp_id','=',$explode_data[0])
            ->whereIn('day',[$day_off_emp[1],$day_off_emp[0]])
            ->orderBy('attendance_date','asc')
            ->get()
            ->toArray();

        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$explode_data[1],$explode_data[2]])->where([['status','=',1]]);

        $totalHolidays = $get_holidays->get()->toArray();
        $monthly_holidays = array_merge($totalHolidays,$total_days_off);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewHolidayDetails',compact('monthly_holidays','totalHolidays','emp_id'));
    }

    function viewEmployeeRebateDocuments()
    {
        $id = Input::get('id');
        $m = Input::get('m');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $rebate_documents = EmployeeRebateDocuments::where([['rebate_id', '=', $id],['status', '=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeRebateDocuments', compact('rebate_documents','m'));
    }

    public function viewDepartmentListDetail()
    {
        $departments = Department::where([['company_id','=',Input::get('m')],['status', '=', 1]])->get();
        return view('Hr.Departments.viewDepartmentListDetail',compact('departments'));
    }

    public function viewSubDepartmentListDetail()
    {
        $departments = Cache::get('department');
        $sub_department = SubDepartment::where([['company_id','=',Input::get('m')],['status','=', 1]])->orderBy('id')->get();
        return view('Hr.SubDepartments.viewSubDepartmentListDetail', compact('departments','sub_department'));
    }

    public function viewDesignationListDetail()
    {
        $queryDg = "SELECT d.* ,IFNULL(g.employee_grade_type,'please put grade against designation') employee_grade_type FROM `designation` d
            left join grades g
            on d.grade_id = g.id
            where d.status = 1";
        $designations =  DB::select(DB::raw($queryDg));
        return view('Hr.Designations.viewDesignationListDetail', compact('designations'));
    }

    public function viewJobTypeListDetail()
    {
        $job_type = JobType::where([['company_id','=',Input::get('m')],['status', '=', 1]])->get();
        return view('Hr.JobType.viewJobTypeListDetail', compact('job_type'));
    }

    public function viewLeaveTypeListDetail()
    {
        $leave_type = LeaveType::where([['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.LeaveTypes.viewLeaveTypeListDetail', compact('leave_type'));
    }

    public function viewLoanTypeListDetail()
    {
        $loan_type = LoanType::where([['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.LoanTypes.viewLoanTypeListDetail', compact('loan_type'));
    }

    public function viewProjectsListDetail()
    {
        $employee_projects = EmployeeProjects::where([['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.EmployeeProjects.viewProjectsListDetail', compact('employee_projects'));
    }

    
    public function viewDesignationWiseLocationsListDetail()
    {
        $data=DB::table('designation_wise_locations as dwl')
        ->Join('locations as l','dwl.location_id','=','l.id')
        ->Join('designation as d','dwl.designation_id','=','d.id')
        ->select('dwl.*','d.designation_name','l.location_name')
        ->where('dwl.status',1)
        ->get();
        return view('Hr.Locations.viewDesignationWiseLocationsListDetail', compact('data'));
    }

    public function viewLocationsListDetail()
    {
        $locations = Locations::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $projects = Cache::get('employee_projects');
        $cities = Cache::get('cities');
        return view('Hr.Locations.viewLocationsListDetail', compact('locations','projects','cities'));
    }

    public function viewCityListDetail()
    {
        $cities = Cities::where([['status','=',1]])->get();
        return view('Hr.City.viewCityListDetail', compact('cities'));
    }

    public function viewCategoryListDetail()
    {
        $category = EmployeeCategory::where([['status','=',1]])->get();
        return view('Hr.Categories.viewCategoryListDetail', compact('category'));
    }

    public function viewMaritalStatusListDetail()
    {
        $marital_status = MaritalStatus::where([['status','=', 1],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        return view('Hr.MaritalStatus.viewMaritalStatusListDetail', compact('marital_status'));
    }

    public function viewEOBIListDetail()
    {
        $eobi = Eobi::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.EOBI.viewEOBIListDetail', compact('eobi'));
    }

    public function viewEmployeeGradesListDetail()
    {
        $grades = Grades::where([['status','=',1],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        return view('Hr.EmployeeGrades.viewEmployeeGradesListDetail', compact('grades'));
    }

    public function viewEmployeeDegreeTypeListDetail()
    {
        $degree_type = DegreeType::where([['status','=',1],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        return view('Hr.EmployeeDegreeType.viewEmployeeDegreeTypeListDetail', compact('degree_type'));
    }

    public function viewEquipmentsListDetail()
    {
        $equipments = Equipments::where([['status','=', 1]])->get();
        return view('Hr.Equipments.viewEquipmentsListDetail', compact('equipments'));
    }

    public function viewHolidayCalender()
    {
        $month_year = Input::get('month_year');
        $m = Input::get('m');
        return view('Hr.AjaxPages.viewHolidayCalender', compact('month_year','m'));
    }

    public function viewHolidaysDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::where([['id','=',Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHolidaysDetail',compact('holidays','operation_rights2'));
    }

    public function viewAllowanceTypeListDetail()
    {
        $allowance_type = AllowanceType::where([['status','=', 1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.AllowanceType.viewAllowanceTypeListDetail', compact('allowance_type'));
    }

    public function viewAllowanceFormDetail()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $allowance_type = Input::get('allowance_type');
        $allowance_amount = Input::get('allowance_amount');
        $company_id = Input::get('company_id');

        $allowance_types = Cache::get('allowance_types');
        $location_id = Input::get('location_id');

        $employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);

        return view('Hr.Allowances.viewAllowanceFormDetail', compact('employees','allowance_type','allowance_types','allowance_amount'));
    }

    public function viewAllowanceListDetail()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = Input::get('location_id');
        $company_id = Input::get('company_id');
        $deduction_type = Input::get('deduction_type');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));

        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach ($employee as $val):
            $emr_array[] = $val->id;
        endforeach;
        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0; 

        CommonHelper::companyDatabaseConnection($company_id);
        
        $query_string_second_part[] = " AND a.employee_id IN ($emr_array)";
        if(!empty($deduction_type)) $query_string_second_part[] = "  AND a.allowance_type_id ='$deduction_type'";
        $query_string_second_part[] = " AND e.status = 1";
        $query_string_second_part[] = " AND a.status = 1";
        $query_string_First_Part = "SELECT a.*,e.emp_id,e.emp_name,e.department_id, e.category_id,e.project_id
          FROM employee AS e INNER JOIN allowance a ON a.employee_id = e.id WHERE ";
        $query_string_third_part = ' ORDER BY a.id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $allowance = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();

        $departments = Cache::get('department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $allowance_types = Cache::get('allowance_types');
        return view('Hr.Allowances.viewAllowanceListDetail',compact('allowance','departments','category',
            'projects','allowance_types','company_id','operation_rights2'));
    }

    public function viewDeductionListDetail()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $deduction_type = Input::get('deduction_type');
        $company_id = Input::get('company_id');
        $location_id = Input::get('location_id');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $emr_array = [];

        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);
        foreach ($employee as $val):
            $emr_array[] = $val->id;
        endforeach;
        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0; 

        CommonHelper::companyDatabaseConnection($company_id);

        $query_string_second_part[] = " AND d.employee_id IN ($emr_array)";
        if(!empty($deduction_type)) $query_string_second_part[] = "  AND d.deduction_type ='$deduction_type'";
        $query_string_second_part[] = " AND e.status = 1";
        $query_string_second_part[] = " AND d.status = 1";
        $query_string_First_Part = "SELECT d.*,e.emp_id,e.emp_name,e.department_id, e.category_id, e.project_id
          FROM employee AS e INNER JOIN deduction d ON e.id = d.employee_id WHERE ";
        $query_string_third_part = ' ORDER BY d.id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $deduction = DB::select(DB::raw($query_string));

        CommonHelper::reconnectMasterDatabase();
        $departments = Cache::get('department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employee = Cache::get('employee');

        return view('Hr.Deductions.viewDeductionListDetail',compact('deduction','employee','departments','category',
            'projects','deduction_type','company_id','operation_rights2'));
    }

    public function viewDeductionFormDetail()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $deduction_type = Input::get('deduction_type');
        $deduction_amount = Input::get('deduction_amount');
        $company_id = Input::get('company_id');
        $location_id = Input::get('location_id');

        $employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);
        return view('Hr.Deductions.viewDeductionFormDetail', compact('employees','deduction_type','deduction_amount'));
    }

    public function viewEmployeeFilteredList()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $rights_url = Input::get('rights_url');
        $company_id = Input::get('company_id');
        $status = Input::get('status');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages($rights_url);

        $employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id,$status);

        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $designations = Cache::get('designation');
        $locations = Cache::get('locations');

        return view('Hr.Employees.viewEmployeeFilteredList', compact('employees', 'departments', 'sub_departments',
            'category', 'designations', 'locations', 'operation_rights2','company_id'));
    }

    public function viewEmployeeReportFilteredList()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $rights_url = Input::get('rights_url');
        $company_id = Input::get('company_id');
        $jobType = Input::get('jobType');
        $joining = Input::get('joining_date');
        $status = Input::get('status');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages($rights_url);

        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id, $status);

        $employees = (!empty($jobType)) ? array_filter($employee, function($value) use ($jobType) {
            return $value->employment_status_id == $jobType;
        }) : $employee;


        $employees = (empty($joining)) ? $employees : array_filter($employees, function($value) use ($joining) {
            
            $currentYear = date('Y');
            $joiningYear = date('Y', strtotime($value->joining_date));
        
            switch ($joining) {
                case 'last_year_5':
                    return $joiningYear == ($currentYear - 5);
                case 'last_year_4':
                    return $joiningYear == ($currentYear - 4);
                case 'last_year_3':
                    return $joiningYear == ($currentYear - 3);
                case 'last_year_2':
                    return $joiningYear == ($currentYear - 2);
                case 'last_year':
                    return $joiningYear == ($currentYear - 1);
                case 'last_month':
                    return strtotime($value->joining_date) >= strtotime('first day of last month');
                case 'last_week':
                    return strtotime($value->joining_date) >= strtotime('monday last week');
                default:
                    return false; // Exclude employees that don't match any filter
            }
        });
        
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $designations = Cache::get('designation');
        $locations = Cache::get('locations');

        return view('Hr.Employees.viewEmployeeReportFilteredList', compact('employees', 'departments', 'sub_departments',
            'category', 'designations', 'locations', 'operation_rights2','company_id'));
    }

    public function viewFilteredLoanRequestList()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $status = Input::get('status');
        $company_id = Input::get('company_id');
        $rights_url = Input::get('rights_url');
        $employees = Cache::get('employee');

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages($rights_url);
        $emr_array = [];

        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);
        foreach ($employee as $val):
            $emr_array[] = $val->id;
        endforeach;
        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0; 

        CommonHelper::companyDatabaseConnection($company_id);
        $query_string_second_part[] = " AND employee_id IN ($emr_array)";
        if ($status != '' && ($status == 1 || $status == 0)) $query_string_second_part[] = " AND lr.loan_status = '$status'";
        $query_string_second_part[] = " AND lr.status = 1 ";
        $query_string_First_Part = "SELECT lr.*, (SELECT SUM(loan_top_up_amount) FROM loan_top_up AS lt WHERE lt.loan_id = lr.id AND lt.status = 1) AS loan_top_up
          , (SELECT SUM(amount) FROM loan_adjustment AS la WHERE la.loan_id = lr.id AND la.status = 1) AS loan_adjustment_amount
          , (SELECT SUM(loan_amount_paid) FROM payslip AS p WHERE p.employee_id = lr.employee_id AND p.loan_id = lr.id AND p.status = 1) AS paid_amount
          FROM loan_request AS lr WHERE ";
        $query_string_third_part = ' ORDER BY lr.id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $loan_request = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.loanRequests.viewFilteredLoanRequestList', compact('company_id','loan_request', 'employees', 'operation_rights2'));
    }

    public function viewEmployeeTransferFormDetail()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $rights_url = Input::get('rights_url');
        $company_id = Input::get('company_id');
        $query_string_second_part = [];
        $employees = [];
        $employee_location_array = [];
        $employee_location_array2 = [];
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages($rights_url);

        CommonHelper::companyDatabaseConnection($company_id);
        if (!empty($location_id)):
            if ($employee_id != 'all') $query_string_second_part[] = " AND e.id = '$employee_id'";
            if (!empty($department_id)) $query_string_second_part[] = " AND e.department_id = '$department_id'";
            if (!empty($sub_department_id)) $query_string_second_part[] = " AND e.sub_department_id = '$sub_department_id'";
            if (!empty($category_id)) $query_string_second_part[] = " AND e.category_id = '$category_id'";
            if (!empty($project_id)) $query_string_second_part[] = " AND eml.project_id = '$project_id'";
            $query_string_second_part[] = " AND e.status = '1'";
            $query_string_second_part[] = " AND eml.status = '1'";
            $query_string_first_Part = "SELECT e.id,e.emp_id,e.emp_name,eml.location_id,eml.project_id,e.designation_id,e.reporting_manager_id,
              (SELECT ep.designation_id FROM employee_promotion AS ep WHERE
              ep.employee_id = e.id AND ep.status = 1 AND ep.designation_id != ''
              ORDER BY ep.id DESC LIMIT 1) AS promoted_designation FROM employee_multiple_locations AS eml JOIN
              employee AS e ON e.id = eml.employee_id WHERE";
            $query_string_third_part = ' ORDER BY e.id';
            $query_string_second_part = implode(" ", $query_string_second_part);
            $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
            $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
            $employee_locations = DB::select(DB::raw($query_string));
            if(!empty($employee_locations)):
                foreach($employee_locations as $key => $val):
                    $employee_location_array[$val->id] = explode(',', $val->location_id);
                    $employee_location_array2 = explode(',', $val->location_id);
                    if(in_array($location_id, $employee_location_array2)):
                        $employees[] = $val;
                    endif;
                endforeach;
            endif;
        else:
            if ($employee_id != 'all') $query_string_second_part[] = " AND e.id = '$employee_id'";
            if (!empty($department_id)) $query_string_second_part[] = " AND e.department_id = '$department_id'";
            if (!empty($sub_department_id)) $query_string_second_part[] = " AND e.sub_department_id = '$sub_department_id'";
            if (!empty($category_id)) $query_string_second_part[] = " AND e.category_id = '$category_id'";
            if (!empty($project_id)) $query_string_second_part[] = " AND eml.project_id = '$project_id'";
            $query_string_second_part[] = " AND e.status = '1'";
            $query_string_second_part[] = " AND eml.status = '1'";
            $query_string_first_Part = "SELECT e.id,e.emp_id,e.emp_name,eml.location_id,eml.project_id,e.designation_id,e.reporting_manager_id,
              (SELECT ep.designation_id FROM employee_promotion AS ep WHERE
              ep.employee_id = e.id AND ep.status = 1 AND ep.designation_id != ''
              ORDER BY ep.id DESC LIMIT 1) AS promoted_designation FROM employee_multiple_locations AS eml JOIN
              employee AS e ON e.id = eml.employee_id WHERE";
            $query_string_third_part = ' ORDER BY e.id';
            $query_string_second_part = implode(" ", $query_string_second_part);
            $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
            $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
            $employees = DB::select(DB::raw($query_string));

            foreach($employees as $key => $val):
                $employee_location_array[$val->id] = explode(',', $val->location_id);
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();

        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $designations = Cache::get('designation');
        $locations = Cache::get('locations');
        $reporting_to = Cache::get('employee');
        return view('Hr.Transfers.viewEmployeeTransferFormDetail', compact('employees', 'employee_location_array','departments', 'sub_departments', 'category',
            'projects', 'designations','locations','reporting_to','operation_rights2'));
    }

    public function viewFilteredLeaveRequestList()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $approval_status = Input::get('approval_status');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $company_id = Input::get('company_id');
        $rights_url = Input::get('rights_url');
        $emr_array = [];

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages($rights_url);
        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);
        foreach ($employee as $val):
            $emr_array[] = $val->id;
        endforeach;
        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0; 

        $query_string_second_part[] = " AND la.employee_id IN($emr_array)";
        $query_string_second_part[] = " AND ((lad.from_date BETWEEN '$from_date' AND '$to_date') OR (lad.first_second_half_date BETWEEN '$from_date' AND '$to_date')) ";
        $query_string_second_part[] = " AND la.status != 2 ";
        if(!empty($approval_status)) $query_string_second_part[] = " AND la.approval_status = '$approval_status' ";
        $query_string_First_Part = "SELECT la.*,lad.no_of_days,lad.from_date,lad.to_date,lad.first_second_half,lad.first_second_half_date
            FROM leave_application AS la INNER JOIN leave_application_data AS lad ON lad.leave_application_id = la.id WHERE ";
        $query_string_third_part = " ORDER BY lad.from_date";
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $leave_application_request_list = DB::select(DB::raw($query_string));
        $employees = Cache::get('employee');
        $leave_types = Cache::get('leave_type');

        return view('Hr.LeaveApplication.viewFilteredLeaveRequestList', compact('operation_rights2','leave_application_request_list','leave_types','employees','company_id'));
    }

    public function viewChangeReportingManagerFormDetail()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $rights_url = Input::get('rights_url');
        $company_id = Input::get('company_id');
        $query_string_second_part = [];
        $employees = [];
        $employee_location_array = [];
        $employee_location_array2 = [];
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages($rights_url);

        CommonHelper::companyDatabaseConnection($company_id);
        if (!empty($location_id)):
            if ($employee_id != 'all') $query_string_second_part[] = " AND e.id = '$employee_id'";
            if (!empty($department_id)) $query_string_second_part[] = " AND e.department_id = '$department_id'";
            if (!empty($sub_department_id)) $query_string_second_part[] = " AND e.sub_department_id = '$sub_department_id'";
            if (!empty($category_id)) $query_string_second_part[] = " AND e.category_id = '$category_id'";
            if (!empty($project_id)) $query_string_second_part[] = " AND eml.project_id = '$project_id'";
            $query_string_second_part[] = " AND e.status = '1'";
            $query_string_second_part[] = " AND eml.status = '1'";
            $query_string_first_Part = "SELECT e.id,e.emp_id,e.emp_name,eml.location_id,eml.project_id,e.designation_id,e.reporting_manager_id,
              (SELECT ep.designation_id FROM employee_promotion AS ep WHERE
              ep.employee_id = e.id AND ep.status = 1 AND ep.designation_id != ''
              ORDER BY ep.id DESC LIMIT 1) AS promoted_designation FROM employee_multiple_locations AS eml JOIN
              employee AS e ON e.id = eml.employee_id WHERE";
            $query_string_third_part = ' ORDER BY e.id';
            $query_string_second_part = implode(" ", $query_string_second_part);
            $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
            $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
            $employee_locations = DB::select(DB::raw($query_string));
            if(!empty($employee_locations)):
                foreach($employee_locations as $key => $val):
                    $employee_location_array[$val->id] = explode(',', $val->location_id);
                    $employee_location_array2 = explode(',', $val->location_id);
                    if(in_array($location_id, $employee_location_array2)):
                        $employees[] = $val;
                    endif;
                endforeach;
            endif;
        else:
            if ($employee_id != 'all') $query_string_second_part[] = " AND e.id = '$employee_id'";
            if (!empty($department_id)) $query_string_second_part[] = " AND e.department_id = '$department_id'";
            if (!empty($sub_department_id)) $query_string_second_part[] = " AND e.sub_department_id = '$sub_department_id'";
            if (!empty($category_id)) $query_string_second_part[] = " AND e.category_id = '$category_id'";
            if (!empty($project_id)) $query_string_second_part[] = " AND eml.project_id = '$project_id'";
            $query_string_second_part[] = " AND e.status = '1'";
            $query_string_second_part[] = " AND eml.status = '1'";
            $query_string_first_Part = "SELECT e.id,e.emp_id,e.emp_name,eml.location_id,eml.project_id,e.designation_id,e.reporting_manager_id,
              (SELECT ep.designation_id FROM employee_promotion AS ep WHERE
              ep.employee_id = e.id AND ep.status = 1 AND ep.designation_id != ''
              ORDER BY ep.id DESC LIMIT 1) AS promoted_designation FROM employee_multiple_locations AS eml JOIN
              employee AS e ON e.id = eml.employee_id WHERE";
            $query_string_third_part = ' ORDER BY e.id';
            $query_string_second_part = implode(" ", $query_string_second_part);
            $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
            $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
            $employees = DB::select(DB::raw($query_string));

            foreach($employees as $key => $val):
                $employee_location_array[$val->id] = explode(',', $val->location_id);
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();

        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $designations = Cache::get('designation');
        $locations = Cache::get('locations');
        $reporting_to = Cache::get('employee');
        return view('Hr.Transfers.viewChangeReportingManagerFormDetail', compact('employees', 'employee_location_array','departments', 'sub_departments', 'category',
            'projects', 'designations','locations','reporting_to','operation_rights2'));
    }

    public function viewFilteredAdvanceSalaryList()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $month_year = !empty(Input::get('month_year')) ? explode('-',Input::get('month_year')): '';
        $company_id = Input::get('company_id');
        $rights_url = Input::get('rights_url');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages($rights_url);
        $emr_array = [];

        $employee = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id,$location_id, $category_id,$employee_id);
        foreach ($employee as $val):
            $emr_array[] = $val->id;
        endforeach;
        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0; 

        CommonHelper::companyDatabaseConnection($company_id);
        $query_string_second_part[] = " AND employee_id IN ($emr_array)";
        (!empty($month_year))? $query_string_second_part[] = " AND deduction_month = '$month_year[1]' ":'';
        (!empty($month_year))? $query_string_second_part[] = " AND deduction_year = '$month_year[0]' ":'';
        $query_string_second_part[] = " AND status = 1 ";
        $query_string_First_Part = "SELECT * FROM advance_salary WHERE ";
        $query_string_third_part = " ORDER BY id";
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $advance_salary = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');
        return view('Hr.AdvanceSalary.viewFilteredAdvanceSalaryList', compact('advance_salary', 'employees','operation_rights2'));
    }

    public function viewEmployeeExitClearanceListDetail()
    {
        $company_id = Input::get('company_id');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection($company_id);
        $employee_exit = DB::table('employee AS e')
            ->join('employee_exit AS ee', 'e.id', '=', 'ee.employee_id')
            ->select('ee.*','e.emp_id','e.emp_name','e.last_working_date')
            ->where([['e.status','=', 3],['ee.status', '=', 1]])
            ->orderBy('ee.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $designations = Cache::get('designation');
        $departments = Cache::get('department');
        return view('Hr.ExitClearance.viewEmployeeExitClearanceListDetail', compact('operation_rights2','employee_exit', 'designations','departments','company_id'));
    }

    public function viewEmployeePromotionsListDetail()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $approval_status = Input::get('approval_status');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $job_type = Input::get('job_type');
        $emr_array = [];
        
        
        $employees = HrHelper::getEmployees( $company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        $employee = array_filter($employees, function($value) use ($job_type) {
            if($value->employment_status_id == $job_type) {
                return $value;
            }
            elseif(empty($job_type))
            {
                return $value;
            }

        });
        
        foreach ($employee as $val):
            $emr_array[] = $val->id;
        endforeach;
        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0; 
        

        CommonHelper::companyDatabaseConnection($company_id);
        $query_string_second_part[] = " AND employee_id IN ($emr_array)";
        $query_string_second_part[] = " AND status = 1 ";
        if(!empty($approval_status)) $query_string_second_part[] = " AND approval_status = '$approval_status' ";
        $query_string_First_Part = "SELECT * FROM employee_promotion WHERE ";
        $query_string_third_part = " ORDER BY id";
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
        $employee_promotions = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        $designations = Cache::get('designation');
        $employees = Cache::get('employee');

        return view('Hr.Promotions.viewEmployeePromotionsListDetail', compact('employee_promotions','designations','employees',
            'operation_rights2','company_id'));
    }

    public function viewIncrementsFormDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $location_id = Input::get('location_id');
        $increment_type = Input::get('increment_type');
        $job_type = Input::get('job_type');
        $emr_array = [];
        
        
        $employee = HrHelper::getEmployees( $company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        $employees = array_filter($employee, function($value) use ($job_type) {
            if($value->employment_status_id == $job_type) {
                return $value;
            }
            elseif(empty($job_type))
            {
                return $value;
            }

        });
        
        foreach($employees as $val):
            $emr_array[] = $val->id;
        endforeach;

        $emr_array = implode(',',$emr_array);
        if(empty($emr_array)) $emr_array = 0; 

        
        $query_string_second_part[] = " AND e.id IN ($emr_array)";
        CommonHelper::companyDatabaseConnection($company_id);
        $query_string_first_Part = "SELECT e.id, e.emp_id, e.emp_name,
          IFNULL((SELECT employee_promotion.designation_id FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.designation_id != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.designation_id) AS designation_id,
          IFNULL((SELECT employee_promotion.grade_id FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.grade_id != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.grade_id) AS grade_id,
          IFNULL((SELECT employee_promotion.salary FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.salary != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.salary) AS salary FROM employee AS e WHERE ";
        $query_string_third_part = ' ORDER BY e.id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
        $employees = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();

        $designations = Cache::get('designation');
        $grades = Cache::get('grades');
        return view('Hr.AjaxPages.viewIncrementsFormDetail',compact('employees','designations','grades','operation_rights2',
            'increment_type','company_id'));
    }

    public function viewEmployeeRebateDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $location_id = Input::get('location_id');
        $emr_array = [];

        $employees = HrHelper::getEmployees( $company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val):
            $emr_array[] = $val->id;
        endforeach;

        CommonHelper::companyDatabaseConnection($company_id);
        $rebate = Rebate::where([['status', '=', 1]])->whereIn('employee_id', $emr_array)->orderBy('employee_id','asc');
        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');
        return view('Hr.Rebate.viewEmployeeRebateDetail',compact('rebate','employees','company_id'));
    }

    public function viewFinalSettlementListDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $company_id = Input::get('company_id');
        $employee_id = Input::get('employee_id');

        CommonHelper::companyDatabaseConnection($company_id);
        if(!empty($employee_id)) $query_string_second_part[] = " AND employee_id = '$employee_id'";
        $query_string_second_part[] = " AND status = 1";
        $query_string_first_Part = "SELECT * FROM final_settlement WHERE ";
        $query_string_third_part = ' ORDER BY id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
        $final_settlement = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();

        $employees = Cache::get('employee');
        return view('Hr.FinalSettlement.viewFinalSettlementListDetail',compact('final_settlement','employees','company_id','operation_rights2'));
    }

    public function viewReconciliationReportDetail()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $month_year = Input::get('month_year');
        $explode_month_year = explode('-',$month_year);
        $prev_month = strtotime($month_year.'-01'.' -1 month');

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));

        $emr_array = [];
        $left_emr_array = [];

        $employees = HrHelper::getEmployees( $company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val):
            $emr_array[] = $val->id;
        endforeach;

        $left_employees = HrHelper::getLeftEmployees( $company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($left_employees as $val):
            $left_emr_array[] = $val->id;
        endforeach;
        CommonHelper::companyDatabaseConnection($company_id);

        $payroll_data = PayrollData::where([['status','=', 1],['month', '=', $explode_month_year[1]],
            ['year', '=', $explode_month_year[0]]])->select('attendance_from','attendance_to')->first();

        $query_string_second_part = [];
        if(!empty($location_id)) $query_string_second_part[] = " AND location_id = '$location_id'";
        $query_string_second_part[] = " AND status = '1'";
        $query_string_second_part[] = " AND month = ".date('m',$prev_month). " AND year = ". date('Y',$prev_month);
        $query_string_first_Part = "SELECT SUM(standard_gross) AS salary FROM payslip WHERE ";
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part;
        $gross_salary_pre_month = DB::selectOne(DB::raw($query_string));

        $query_string_second_part = [];
        if(!empty($location_id)) $query_string_second_part[] = " AND location_id = '$location_id'";
        $query_string_second_part[] = " AND status = '1'";
        $query_string_second_part[] = " AND month = '$explode_month_year[1]' AND year = '$explode_month_year[0]' ";
        $query_string_first_Part = "SELECT SUM(standard_gross) AS salary FROM payslip WHERE ";
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part;
        $gross_salary_curr_month = DB::selectOne(DB::raw($query_string));

        $new_employees = Employee::where([['status','=', 1]])
            ->whereBetween('joining_date',[$payroll_data['attendance_from'],$payroll_data['attendance_to']])
            ->select('id','emp_id','emp_name','salary','joining_date')->whereIn('id', $emr_array)->get();

        $left_employees = Employee::whereBetween('last_working_date',[$payroll_data['attendance_from'],$payroll_data['attendance_to']])
            ->select('id','emp_id','emp_name','salary','last_working_date')->whereIn('id', $left_emr_array)->get();

        $employee_promotions = DB::table('employee_promotion AS ep')
            ->join('employee AS e','e.id','=', 'ep.employee_id')
            ->where([['ep.status','=', 1],['ep.approval_status','=', 2]])
            ->whereIn('e.id', $emr_array)
            ->whereBetween('promotion_date',[$payroll_data['attendance_from'],$payroll_data['attendance_to']])
            ->select('e.id','e.emp_id','e.emp_name','ep.increment')
            ->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Payroll.viewReconciliationReportDetail',compact('new_employees','left_employees',
            'employee_promotions','gross_salary_pre_month','gross_salary_curr_month','prev_month','month_year','operation_rights2'));
    }

    public function viewEmployeeGratuityFormDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $location_id = Input::get('location_id');
        $month_year = Input::get('month_year');
        $emr_array = [];

        $employees = HrHelper::getEmployees( $company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val):
            $emr_array[] = $val->id;
        endforeach;
        if(empty($emr_array)): $emr_array = 0; else: $emr_array = implode(',',$emr_array); endif;

        CommonHelper::companyDatabaseConnection($company_id);

        $query = "SELECT e.id, e.emp_id, e.emp_name, e.joining_date,
            IFNULL((SELECT ep.salary FROM employee_promotion AS ep WHERE
            ep.employee_id = e.id AND ep.status = 1 AND ep.salary != '' AND ep.approval_status = 2
            ORDER BY ep.id DESC LIMIT 1),e.salary) AS salary,
            TIMESTAMPDIFF(YEAR, joining_date, '$month_year') AS years,
            TIMESTAMPDIFF(MONTH, joining_date, '$month_year')%12 AS months
            FROM employee AS e WHERE e.id IN ($emr_array)  ORDER BY e.id ASC ";
        $employees_gratuity = DB::select(DB::raw($query));

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Gratuity.viewEmployeeGratuityFormDetail', compact('employees_gratuity'));
    }
    
    public function checkLoanData()
    {
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $month_year = date('Y-m-d');
        $payroll_paid_amount = 0;
        $loan_remaining_amount = 0;

        CommonHelper::companyDatabaseConnection($company_id);
        $loan_request  = LoanRequest::where([['approval_status','=',2],['loan_status','=',0],['employee_id', '=', $employee_id],['status', '=', 1]])
            ->select('id','per_month_deduction','loan_amount','stop_payment_year','stop_payment_month');
        if($loan_request ->count() > 0):
            $loan_id = $loan_request->value('id');

            $LoanTopUp = LoanTopUp::where([['loan_id', '=', $loan_id],['status', '=', 1]])->sum('loan_top_up_amount');
            $total_loan = $loan_request ->value('loan_amount') + $LoanTopUp;
            $loan_adjusted_amount = LoanAdjustment::where([['loan_id','=',$loan_id],['status','=', 1]])->sum('amount');

            $payslip_loan = Payslip::where([['employee_id' ,'=', $employee_id],['loan_id' ,'=', $loan_id],['status', '=', 1]])->select('id');
            if($payslip_loan->count() > 0):
                $payroll_paid_amount += Payslip::where([['employee_id' ,'=', $employee_id],['loan_id' ,'=', $loan_id],['status', '=', 1]])
                    ->sum('loan_amount_paid');
            endif;
            $total_paid_amount = $payroll_paid_amount + $loan_adjusted_amount;
            $loan_remaining_amount = $total_loan - $total_paid_amount;
        endif;

        $query = "SELECT e.id, e.emp_id, e.emp_name, e.joining_date,
            IFNULL((SELECT ep.salary FROM employee_promotion AS ep WHERE
            ep.employee_id = e.id AND ep.status = 1 AND ep.salary != '' AND ep.approval_status = 2
            ORDER BY ep.id DESC LIMIT 1),e.salary) AS salary,
            TIMESTAMPDIFF(YEAR, joining_date, '$month_year') AS years,
            TIMESTAMPDIFF(MONTH, joining_date, '$month_year')%12 AS months
            FROM employee AS e WHERE e.id = '$employee_id' ORDER BY e.id ASC ";
        $employees_gratuity = DB::selectOne(DB::raw($query));

        $gratuity = 0;
        $years = $employees_gratuity->years;
        $months = $employees_gratuity->months;
        $salary = round($employees_gratuity->salary);
        $per_day_salary = $salary / 30;
        if($years >= 1 && $months >= 6):
            $years += 1;
        endif;
        if($years >= 1):
            $per_year_gratuity = $per_day_salary * 20;
            $gratuity = $years * $per_year_gratuity;
        endif;
        $per_gratuity = number_format($gratuity * 0.7);
        $gratuity = number_format($gratuity);
        $loan_remaining_amount = number_format($loan_remaining_amount);
        $joining_date = HrHelper::date_format($employees_gratuity->joining_date);
        CommonHelper::reconnectMasterDatabase();
        return compact('loan_remaining_amount','gratuity','per_gratuity','joining_date');
    }

    public function viewEmploymentCertificate($id)
    {
        $employees = Cache::get('employee');
        $employee = $employees[$id];

        $job_types = Cache::get('job_type');
        $employment_status = '';
        if(array_key_exists($employee->employment_status_id, $job_types))
            $employment_status = $job_types[$employee->employment_status_id]->job_type_name;

        $designation = $employee->new_designation;
        $vowel = array('a','e','i','o','u');
        $prefix = in_array(strtolower(substr($designation ,0,1)),$vowel)? "an" : "a"; // logic to add prefix
        $designation = $prefix." ".$designation; // updated word

        return view('Hr.Employees.viewEmploymentCertificate',compact('employee','prefix','designation','employment_status'));
    }

    public function viewEmployeeTaxCertificateDetail()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $year = Input::get('year');
        $explode_year = explode('-', $year);
        $emp_array = [];

        $employees = HrHelper::getEmployees( $company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        foreach($employees as $val):
            $emp_array[] = $val->id;
        endforeach;
        if(empty($emr_array)): $emr_array = []; endif;

        CommonHelper::companyDatabaseConnection($company_id);
        $income_tax = IncomeTax::where([['status','=', 1]])
            ->whereIn('employee_id', $emp_array)->whereBetween('year',[$explode_year[0],$explode_year[1]])->orderBy('employee_id')->get()->toArray();
        CommonHelper::reconnectMasterDatabase();

        foreach($income_tax as $val):
            $income_tax_data[$val['employee_id']] = $val;
        endforeach;

        return view('Hr.Payroll.viewEmployeeTaxCertificateDetail',compact('employees','year','income_tax_data','company_id'));
    }
    
    public function leavesEncashmentFormDetail()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = Input::get('location_id');
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');
        $year = Input::get('year');
        $explode_year = explode('-', $year);
        $leave_from = $explode_year[0].'-'.env('LEAVE_FROM');
        $leave_to = $explode_year[1].'-'.env('LEAVE_TO');

        $departments = Cache::get('department');

        $employees = HrHelper::getEmployees($company_id, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);
        $emp_array = [];
        foreach($employees as $val){
            $emp_array[] = $val->id;
        }
        $emp_array = implode(',',$emp_array);
        if(empty($emp_array)) $emp_array = 0;

        $total_annual_leaves = 0;
        $total_casual_leaves = 0;
        $total_sick_leaves = 0;

        $taken_leaves = DB::select("SELECT le.emp_name,le.id,le.emp_id,le.department_id,le.salary,
        IFNULL(SUM(le.totalleave), 0) total_used_leaves,
        SUM(IF(le.leave_type = 3, le.totalleave, 0)) AS taken_casual_leaves,
        SUM(IF(le.leave_type = 2, le.totalleave, 0)) AS taken_sick_leaves,
        SUM(IF(le.leave_type = 1, le.totalleave, 0)) AS taken_annual_leaves
        FROM(SELECT e.emp_name,e.id,e.emp_id,e.department_id,
        IFNULL((SELECT ep.salary FROM ".CONST_COMPANY_DB.".employee_promotion AS ep WHERE
        ep.employee_id = e.id AND ep.status = 1 AND ep.salary != '' AND ep.approval_status = 2
        ORDER BY ep.id DESC LIMIT 1),e.salary) AS salary,la.leave_type,
        IFNULL(SUM(lad.no_of_days), 0) totalleave
        FROM ".CONST_COMPANY_DB.".employee e LEFT JOIN ".CONST_MASTER_DB.".leave_application la
        ON e.id = la.employee_id left JOIN ".CONST_MASTER_DB.".leave_application_data lad
        ON la.id = lad.leave_application_id WHERE e.id in ($emp_array) AND la.status != 2 AND lad.status != 2 AND
        (la.approval_status = 2 or la.approval_status is NULL ) AND
        (IF(first_second_half_date != '0/0/0000' , first_second_half_date BETWEEN '$leave_from' AND '$leave_to',
        (from_date BETWEEN '$leave_from' AND '$leave_to' OR to_date BETWEEN '$leave_from' AND '$leave_to')))
        GROUP BY e.id,la.leave_type ORDER BY e.id) le GROUP by le.id");

        CommonHelper::companyDatabaseConnection($company_id);
        $leave_encashment = LeavesEncashment::where([['status','=', 1]])
            ->whereBetween('leave_from',[$leave_from,$leave_to])->orWhereBetween('leave_to',[$leave_from,$leave_to])
            ->whereIn('employee_id', explode(',',$emp_array))->get()->toArray();
        $leave_encashment_data = [];
        foreach($leave_encashment as $key => $val):
            $leave_encashment_data[$val['employee_id']] = $val;
        endforeach;
        CommonHelper::reconnectMasterDatabase();

        $leaves_policy = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.status', '=', 1]])
            ->get();

        foreach ($leaves_policy as $val):

            if ($val->leave_type_id == 1):
                $total_annual_leaves = $val->no_of_leaves;
            elseif ($val->leave_type_id == 2):
                $total_sick_leaves = $val->no_of_leaves;
            elseif ($val->leave_type_id == 3):
                $total_casual_leaves = $val->no_of_leaves;
            endif;

        endforeach;

        return view('Hr.LeavesEncashment.leavesEncashmentFormDetail',compact('leave_encashment_data','taken_leaves','total_annual_leaves','total_sick_leaves',
            'total_casual_leaves','departments','company_id','leave_from','leave_to'));
    }

    public function viewTFAllowanceForm()
    {
        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $project_id = Input::get('project_id');
        $category_id = Input::get('category_id');
        $location_id = Input::get('location_id');
        $month_year = Input::get('month_year');
        $explode_month_year = explode('-',$month_year);

        $emp_array = [];
        $employees = HrHelper::getEmployees(Input::get('m'), $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee_id);

        foreach ($employees as $val) {
            $emp_array[] = $val->id;
        }
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $tf_allowance = Allowance::leftJoin('tf_allowances AS tf', function($join) use ($explode_month_year) {
            $join->on('tf.employee_id', '=', 'allowance.employee_id')
                ->where('tf.month', '=', $explode_month_year[1])
                ->where('tf.year', '=', $explode_month_year[0]);
        })
            // ->leftJoin(\DB::raw('(SELECT employee_id, SUM(allowance_amount) as total_allowance_amount FROM allowance GROUP BY employee_id) as total_allowance'), function($join) {
            //     $join->on('total_allowance.employee_id', '=', 'allowance.employee_id');
            // })
            ->leftJoin(\DB::raw('(SELECT employee_id, SUM(allowance_amount) as total_allowance_amount FROM allowance WHERE allowance_type_id = 6 GROUP BY employee_id) as total_allowance'), function($join) {
                $join->on('total_allowance.employee_id', '=', 'allowance.employee_id');
            })
            ->where([
                ['allowance.status', '=', 1],
                ['allowance.allowance_type_id', '=', 6],
            ])
            ->whereIn('allowance.employee_id', $emp_array)
            ->select(
                'allowance.employee_id',
                'tf.month',
                'tf.year',
                \DB::raw('COALESCE(total_allowance.total_allowance_amount, 0) as total_allowance_amount'),
                \DB::raw('SUM(tf.amount) as tf_paid')
            )
            ->groupBy('allowance.employee_id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');

//        echo "<pre>"; print_r($tf_allowance); die;
        return view('Hr.Payroll.viewTFAllowanceForm', compact('tf_allowance','employees','month_year'));
    }


}

?>