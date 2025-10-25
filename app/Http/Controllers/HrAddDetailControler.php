<?php

namespace App\Http\Controllers;
use App\Helpers\AttendanceHelper;
use App\Helpers\HrHelper;
use App\Helpers\EmailHelper;
use App\Models\AttendanceImport;
use App\Models\TFAllowance;
use Carbon\Carbon;
use App\Models\Arrears;
use App\Models\Designation;
use App\Models\EmployeeCategory;
use App\Models\WorkingHoursPolicy;

use App\Models\EmployeeFuelData;
use App\Models\Grades;
use App\Models\Locations;
use App\Models\Attendance;
use App\Models\Regions;
use App\Models\Cpl;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Models\LoanRequest;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payslip;
use App\Models\Attendence;
use App\Models\EmployeeDeposit;
use App\Models\LeavesData;
use App\Models\LeavesPolicy;
use App\Models\Holidays;

use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeEquipments;
use App\Models\UsersImport;
use App\Models\EmployeeBankData;
use App\Models\EmployeePromotion;
use App\Models\TransferedLeaves;
use DateTime;
use App\Models\Role;
use App\Models\PayrollData;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\MaritalStatus;
use App\Models\JobType;
use App\Models\Eobi;
use App\Models\Rebate;
use App\Models\MenuPrivileges;
use App\Models\Tax;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\Payroll;
use App\Models\BonusIssue;
use App\Models\Bonus;
use App\Models\AdvanceSalary;
use App\Models\EmployeeTransfer;
use App\Models\IncomeTax;
use App\Models\EmployeeExit;
use App\Models\TaxSlabs;
use App\Models\HealthInsurance;
use App\Models\LifeInsurance;
use App\Models\LeaveType;
use App\Models\AdvanceType;
use App\Models\LoanType;
use App\Models\EmployeeProjects;
use App\Models\Cities;
use App\Models\DegreeType;
use App\Models\Equipments;
use App\Models\Log;
use App\Models\EmployeeFamilyData;
use App\Models\EmployeeEducationalData;
use App\Models\EmployeeLanguageProficiency;
use App\Models\EmployeeHealthData;
use App\Models\EmployeeActivityData;
use App\Models\EmployeeWorkExperience;
use App\Models\EmployeeReferenceData;
use App\Models\EmployeeKinsData;
use App\Models\EmployeeRelativesData;
use App\Models\EmployeeOtherDetails;
use App\Models\EmployeeMultipleLocations;
use App\Models\AllowanceType;
use App\Models\Users;
use App\Models\providentFundData;
use App\Models\pfProfit;
use App\Models\LeavesEncashment;

use Hash;
use File;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use Mail;
use \Cache;
use App\Helpers\RefreshCacheHelper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class HrAddDetailControler extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function savepolicies(Request $request){
        $employee_id= $request->employee_id;
        $working_hours_policy_array=[];
        $working_hours_policy=DB::table('working_hours_policy')->select('id','days_off')->where('status',1)->get();
        foreach($working_hours_policy as $val){
            $working_hours_policy_array[$val->id]=['day_off'=>$val->days_off];
        }
         
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
         foreach($employee_id as $key => $value){
             
            $data['employee_id']=$value;
            
            //$data['fixed']=$request->get('fixed_input')[$key];
            $data['from_date']=$request->get('from_date');
            $data['to_date']=$request->get('to_date');
            $data['color']=$request->get('color');
            $data['day_off']=$working_hours_policy_array[$request->get('working_hours_policy_id')]['day_off'];
            $data['working_hours_policy_id']=$request->get('working_hours_policy_id');
            $data['status'] = 1;
            $data['username'] = Auth::user()->name;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            
            $exist=DB::table('assign_working_hour_policies')->where('employee_id',$value)
            ->where('status',1)->where('from_date',$request->get('from_date'))
            ->where('to_date',$request->get('to_date'));
            
            if($exist->count()>0){
                DB::table('assign_working_hour_policies')->where('employee_id',$value)
                ->where('from_date',$request->get('from_date'))->update($data);
                
                CommonHelper::LogGenerator('assign_working_hour_policies',$value,'update','row is updated','hr',Auth::user()->id);

            }else{
                $assignWorkingHourPoliciesLastId = DB::table('assign_working_hour_policies')->insertGetId($data);
                    CommonHelper::LogGenerator('assign_working_hour_policies',$assignWorkingHourPoliciesLastId,'insert','row is inserted','hr',Auth::user()->id);
            }
            
            if($request->get('from_date') != '' && $request->get('to_date') != ''){
                $fromDate = new DateTime($request->get('from_date'));
                $toDate = new DateTime($request->get('to_date'));
                
                $currentDate = clone $fromDate; 
                
                while ($currentDate <= $toDate) {
                    $currentDateFormatted = $currentDate->format('Y-m-d');
                    
                    $day_off_emp = explode('=>', $working_hours_policy_array[$request->get('working_hours_policy_id')]['day_off']);    

                    // $date = \DateTime::createFromFormat('Y-m-d', $dateSingle);
                    $day_type = '';
                    
                    if(in_array($currentDate->format('D'),$day_off_emp)){
                        $day_type = 'day_off';
                    }
                    
                    
                    DB::table('attendance')
                        ->where('employee_id', $value)
                        ->where('attendance_date', $currentDateFormatted)
                        ->update([
                            'working_hours_policy_id' => $request->get('working_hours_policy_id'),
                            'attendance_status' => $day_type
                        ]);
                    
                    $currentDate->add(new \DateInterval('P1D'));
                }
            }
            
                
                  
           
             
         }
        CommonHelper::reconnectMasterDatabase();
        $data = array(
            'status' => true,
            'msg' => 'Policy added successfully!'
        );
        return response()->json($data);
        
        
        // Session::flash('dataInsert', 'successfully saved.');
        // return Redirect::to('hr/assignWorkingHoursPolicyForm?m=' . Input::get('m'));
    }
    
     public function bulkInsertionByCsv(request $request)
    {
        $location_id = Input::get('location_id');


        $fileMimes = array(
            // 'text/x-comma-separated-values',
            // 'text/comma-separated-values',
            // 'application/octet-stream',
            // 'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            // 'application/excel',
            // 'application/vnd.msexcel',
            // 'text/plain'
        );

        // Validate whether selected file is a CSV file
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

            $row = 0;
            // add you row number for skip
            // hear we pass 1st row for skip in csv
            $skip_row_number = array("1");

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);

            // Parse data from CSV file line by line
            // Parse data from CSV file line by line
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {

                if (in_array($row, $skip_row_number)) {
                    continue;
                    // skip row of csv
                } else {

                    if ($getData[0] && $getData[3] && $getData[4]) {

                        (!empty($getData[1])) ? $designation = Designation::Where('designation_name', '=', '$getData[1]')->value('id') : null;
                        (!empty($getData[2])) ? $grade = Grades::Where('employee_grade_type', '=', '$getData[2]')->value('id') : null;

                        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                        $salary;
                        $designation_id;
                        $grade_id;
                        $increment;
                        $promotion_date;

    
                        // $promotion_date = \DateTime::createFromFormat('d/m/Y', $getData[3])->format('Y-m-d');
                        $date = DateTime::createFromFormat('n/j/Y', $getData[3]); // Use 'n/j/Y' format for month/day/year
                        $promotion_date = $date->format('Y-n-j'); // Format as 'YYYY-n-j'
                      
                        
                        $empdata = DB::table(CONST_COMPANY_DB.'.employee as e')
                        ->join(CONST_COMPANY_DB.'.employee_multiple_locations as eml','e.id', '=', 'eml.employee_id')
                        ->select('e.id','e.emp_name','e.salary','e.designation_id','e.grade_id')->where([ ['e.emp_id' ,'=', $getData[0] ] , ['e.status' ,'=', 1] , ['eml.location_id' ,'=', $location_id] , ['eml.status' ,'=', 1] ] )->get();
                           
                        if (Count($empdata) > 0) {
                         
                        $emp = $empdata[0];
                        $id = $emp->id;


                            $empDataPromotion = EmployeePromotion::where([
                                ['employee_id', '=', $id],
                                ['promotion_date', '!=', $promotion_date],

                            ])
                                ->orderBy('promotion_date', 'desc')->take(1)->get();

                            if (!$empDataPromotion->isEmpty()) {
                                $salary = $empDataPromotion->pluck('salary');
                                $salary = $salary[0];
                                $designation_id = $empDataPromotion->pluck('designation_id');
                                $designation_id = $designation_id[0];
                                $grade_id = $empDataPromotion->pluck('grade_id');
                                $grade_id = $grade_id[0];
                                $increment = $empDataPromotion->pluck('salary');
                                $increment = $increment[0];

                            } else {
                                $salary = $emp->salary;
                                $designation_id = $emp->designation_id;
                                $grade_id = $emp->grade_id;
                                $increment = $emp->salary;

                            }
                        }
                       
                        $promotion = EmployeePromotion::updateOrCreate(['employee_id' => $id, 'promotion_date' => $promotion_date], [
                            'employee_id' => $id,
                            'designation_id' => (empty($getData[1])) ? $designation_id : $designation,
                            'grade_id' => (empty($getData[2])) ? $grade_id : $grade,
                            'promotion_date' => $promotion_date,
                            'increment' => (int) $getData[4],
                            'salary' => (int) $salary + (int) $getData[4],
                            'username' => Auth::user()->name,
                            'approval_status' => 1,
                            'status' => 1,
                            'date' => date("Y-m-d"),
                            'time' => date("H:i:s"),

                        ]);

                        $actionType;
                        $remark;
                        if ($promotion->wasRecentlyCreated) {
                            $actionType = 'insert';
                            $remark = 'row is inserted';

                        }
                        else
                        {
                            $actionType = 'update';
                            $remark = 'row is updated';

                        }

                        CommonHelper::LogGenerator('employee_promotion',$promotion->id,$actionType,$remark,'hr',Auth::user()->id);


                        //  (!empty($getData[1]))?
                    }
                    // Get row data
                }

            }

            // Close opened CSV file
            fclose($csvFile);

            CommonHelper::reconnectMasterDatabase();
            Session::flash('dataInsert', 'Successfully Saved.');

        } else {
            Session::flash('dataInsert', 'Please upload csv file');

        }
        return Redirect::to('hr/bulkIncrementForm?m=' . Input::get('company_id'));

    }
    
     public function addLeaveApplicationDetailByCsv()
    {

        $company_id = Input::get('company_id');
        $location_id = Input::get('location_id');
        $fileMimes = array(
            // 'text/x-comma-separated-values',
            // 'text/comma-separated-values',
            // 'application/octet-stream',
            // 'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            // 'application/excel',
            // 'application/vnd.msexcel',
            // 'text/plain'
        );

        // Validate whether selected file is a CSV file
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

            $row = 0;
            // add you row number for skip
            // hear we pass 1st row for skip in csv
            $skip_row_number = array("1");

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);
            $a = 0 ;
        
            // Parse data from CSV file line by line
            // Parse data from CSV file line by line
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {

                if (in_array($row, $skip_row_number)) {
                    continue;
                    // skip row of csv
                } else {

                    if ($getData[0] && $getData[1] && $getData[2] && $getData[4] && $getData[5] && $getData[6]) {

                       $leavetype =  preg_replace('/\s+/', '', $getData[1]);
                        $leavetype = DB::select(DB::raw("select * from ".CONST_MASTER_DB.".leave_type where status = 1 and leave_type_name like '%$leavetype%'"));

                      
                        if (!empty($leavetype[0]->id)) {
                           
                            $emp_id = preg_replace('/\s+/', '', $getData[0]);

                            $emp = DB::table(CONST_COMPANY_DB.'.employee as e')
                            ->join(CONST_COMPANY_DB.'.employee_multiple_locations as eml','e.id', '=', 'eml.employee_id')
                            ->select('e.id','e.emp_name')->where([ ['e.emp_id' ,'=', $emp_id ] , ['e.status' ,'=', 1] , ['eml.location_id' ,'=', $location_id] , ['eml.status' ,'=', 1] ] )->get();
                             
                              
                            if (Count($emp) > 0) {
                             
                            $emp = $emp[0];
                            $employee_id = $emp->id;
                            
                           
                                $fromDate;
                                $toDate;
                                $leavespolicyid = 1;
                                $leavetypeid = $leavetype[0]->id;
                                $fromDate = date('Y-m-d',strtotime(str_replace('-','/', $getData[4])));
                                $toDate = date('Y-m-d',strtotime(str_replace('-','/', $getData[5])));
                                $a++;
                                
                                $first_second_half_date = 0;

                                $halfOrFull = strtolower(str_replace(' ', '_', trim($getData[2])));
                                if ($halfOrFull == 'half_day_leave') {
                                    $first_second_half_date = $fromDate;
                                    $checkHalfOrFullDay = 2;

                                } else {
                                    $checkHalfOrFullDay = 1;
                                }

                                $firstOrSecondHalf = strtolower(str_replace(' ', '_', trim($getData[3])));
                                $reasonId;

                                $reasonId = DB::select(DB::raw("select * from ".CONST_MASTER_DB.".reasons where status = 1 and reason like '%$getData[6]%'"));

                                if (empty($reasonId)) {
                                    $reasonId['reason'] = trim($getData[6]);
                                    $reasonId['status'] = 1;

                                    $reasonId = DB::table('reasons')->insertGetId($reasonId);

                                    $reasonId = DB::select(DB::raw("select * from ".CONST_MASTER_DB.".reasons where status = 1 and id = $reasonId "));

                                    $reasonId = $reasonId[0]->reason ;


                                } else {
                                    $reasonId = $reasonId[0]->reason;
                                }

                                if ($fromDate <= $toDate) {
                                 
                                    $datediff = strtotime($toDate) - strtotime($fromDate);
                                    $totalDays = round($datediff / (60 * 60 * 24));
                                   
                                    if($totalDays > 0)
                                    {
                                        // echo 'echo in ';
                                      $totalDays = $totalDays + 1;
                                    }
                                    
                                    if($totalDays == 0)
                                    {
                                        $totalDays = 1 ;

                                    }
                                    if($leavetype[0]->id != 1 &&  $checkHalfOrFullDay == 2)
                                    {
                                        $totalDays = 0.5 ;
                                        
                                    }
                                   



                                    $leaveApplicationCheck1 = LeaveApplicationData::where([['view', '=', 'yes'], ['leave_policy_id', '=', $leavespolicyid], ['from_date', '=', $fromDate], ['first_second_half', '=', ''], ['employee_id', '=', $employee_id]])->count();

                                    $leaveApplicationCheck2 = LeaveApplicationData::where([['view', '=', 'yes'], ['leave_policy_id', '=', $leavespolicyid], ['first_second_half_date', '=', $first_second_half_date],
                                        ['employee_id', '=', $employee_id], ['first_second_half', '!=', '']])->count();

                              

                                    if ($leaveApplicationCheck1 > 0 || $leaveApplicationCheck2 > 0):
                                        // echo '<pre>';
                                        // print_r($employee_id);
                                        // print_r( $fromDate );
                                        // exit();
                                        // return "Leave Application Already Exist on Date ".$fromDate.$first_second_half_date;
                                          continue;

                                    else:
                                        // echo '<pre>';
                                        // print_r('else');
                                        // exit();
                                        $leaveApplicationData['employee_id'] = $employee_id;
                                        $leaveApplicationData['leave_policy_id'] = $leavespolicyid;
                                        $leaveApplicationData['company_id'] = $company_id;
                                        $leaveApplicationData['leave_type'] = $leavetypeid;
                                        $leaveApplicationData['leave_day_type'] = $checkHalfOrFullDay;
                                        $leaveApplicationData['reason'] = $reasonId;
                                        $leaveApplicationData['leave_address'] = $getData[7];
                                        $leaveApplicationData['approval_status'] = 2; // 1 pending 2 approve 3 reject
                                        $leaveApplicationData['approval_status_lm'] = 2; // 1 pending 2 approve 3 reject
                                        $leaveApplicationData['approved'] = 1;
                                        $leaveApplicationData['status'] = 1;
                                        $leaveApplicationData['username'] = Auth::user()->name;
                                        $leaveApplicationData['date'] = date("Y-m-d");
                                        $leaveApplicationData['time'] = date("H:i:s");

                                       $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);
                                    
                                        /* Annual Leaves ID = 1 */
                                        if ($leavetypeid == 1):

                                            $maternityLeavesData['employee_id'] = $employee_id;
                                            $maternityLeavesData['leave_application_id'] = $leave_application_id;
                                            $maternityLeavesData['leave_policy_id'] = $leavespolicyid;
                                            $maternityLeavesData['no_of_days'] = $totalDays;
                                            $maternityLeavesData['from_date'] = $fromDate;
                                            $maternityLeavesData['to_date'] = $toDate;
                                            $maternityLeavesData['status'] = 1;
                                            $maternityLeavesData['username'] = Auth::user()->name;
                                            $maternityLeavesData['date'] = date("Y-m-d");
                                            $maternityLeavesData['time'] = date("H:i:s");

                                            DB::table('leave_application_data')->insert($maternityLeavesData);

                                        elseif ($leavetypeid == 2):

                                            /* Full Day Leaves */
                                            if ($checkHalfOrFullDay == 1):

                                                $annualLeavesData['employee_id'] = $employee_id;
                                                $annualLeavesData['leave_application_id'] = $leave_application_id;
                                                $annualLeavesData['leave_policy_id'] = $leavespolicyid;
                                                $annualLeavesData['no_of_days'] = $totalDays;
                                                $annualLeavesData['from_date'] = $fromDate;
                                                $annualLeavesData['to_date'] = $toDate;
                                                $annualLeavesData['status'] = 1;
                                                $annualLeavesData['username'] = Auth::user()->name;
                                                $annualLeavesData['date'] = date("Y-m-d");
                                                $annualLeavesData['time'] = date("H:i:s");

                                                DB::table('leave_application_data')->insert($annualLeavesData);

                                                /* Half Day Leaves */
                                            elseif ($checkHalfOrFullDay == 2):

                                                $halfdayLeavesData['employee_id'] = $employee_id;
                                                $halfdayLeavesData['leave_application_id'] = $leave_application_id;
                                                $halfdayLeavesData['leave_policy_id'] = $leavespolicyid;
                                                $halfdayLeavesData['no_of_days'] = $totalDays;
                                                $halfdayLeavesData['first_second_half'] = $firstOrSecondHalf;
                                                $halfdayLeavesData['first_second_half_date'] = $first_second_half_date;
                                                $halfdayLeavesData['status'] = 1;
                                                $halfdayLeavesData['username'] = Auth::user()->name;
                                                $halfdayLeavesData['date'] = date("Y-m-d");
                                                $halfdayLeavesData['time'] = date("H:i:s");
                                                $annualLeavesData['from_date'] = 0;
                                                $annualLeavesData['to_date'] = 0;
                                                DB::table('leave_application_data')->insert($halfdayLeavesData);

                                            endif;

                                        elseif ($leavetypeid == 3):

                                            /* Full Day Leaves */
                                            if ($checkHalfOrFullDay == 1):

                                                $annualLeavesData['employee_id'] = $employee_id;
                                                $annualLeavesData['leave_application_id'] = $leave_application_id;
                                                $annualLeavesData['leave_policy_id'] = $leavespolicyid;
                                                $annualLeavesData['no_of_days'] = $totalDays;
                                                $annualLeavesData['from_date'] = $fromDate;
                                                $annualLeavesData['to_date'] = $toDate;
                                                $annualLeavesData['status'] = 1;
                                                $annualLeavesData['username'] = Auth::user()->name;
                                                $annualLeavesData['date'] = date("Y-m-d");
                                                $annualLeavesData['time'] = date("H:i:s");

                                                DB::table('leave_application_data')->insert($annualLeavesData);

                                                /* Half Day Leaves */
                                            elseif ($checkHalfOrFullDay == 2):

                                                $halfdayLeavesData['employee_id'] = $employee_id;
                                                $halfdayLeavesData['leave_application_id'] = $leave_application_id;
                                                $halfdayLeavesData['leave_policy_id'] = $leavespolicyid;
                                                $halfdayLeavesData['no_of_days'] = $totalDays;
                                                $halfdayLeavesData['first_second_half'] = $firstOrSecondHalf;
                                                $halfdayLeavesData['first_second_half_date'] = $first_second_half_date;
                                                $halfdayLeavesData['status'] = 1;
                                                $halfdayLeavesData['username'] = Auth::user()->name;
                                                $halfdayLeavesData['date'] = date("Y-m-d");
                                                $halfdayLeavesData['time'] = date("H:i:s");
                                                $annualLeavesData['from_date'] = 0;
                                                $annualLeavesData['to_date'] = 0;
                                                DB::table('leave_application_data')->insert($halfdayLeavesData);

                                            endif;

                                            else:

                                                /* Full Day Leaves */
                                                if($checkHalfOrFullDay == 1):

                                                    $annualLeavesData['employee_id']               = $employee_id;
                                                    $annualLeavesData['leave_application_id'] = $leave_application_id;
                                                    $annualLeavesData['leave_policy_id'] = $leavespolicyid;
                                                    $annualLeavesData['no_of_days']           = $totalDays;
                                                    $annualLeavesData['from_date']            = $fromDate;
                                                    $annualLeavesData['to_date']              = $toDate;
                                                    $annualLeavesData['status']               = 1;
                                                    $annualLeavesData['username']             = Auth::user()->name;
                                                    $annualLeavesData['date']                 = date("Y-m-d");
                                                    $annualLeavesData['time']                 = date("H:i:s");

                                                    DB::table('leave_application_data')->insert($annualLeavesData);

                                                /* Half Day Leaves */
                                                elseif($checkHalfOrFullDay == 2):

                                                    $halfdayLeavesData['employee_id']                   = $employee_id;
                                                    $halfdayLeavesData['leave_application_id']     = $leave_application_id;
                                                    $halfdayLeavesData['leave_policy_id'] = $leavespolicyid;
                                                    $halfdayLeavesData['no_of_days']               = $totalDays;
                                                    $halfdayLeavesData['first_second_half']        = $firstOrSecondHalf;
                                                    $halfdayLeavesData['first_second_half_date']   = $first_second_half_date;
                                                    $halfdayLeavesData['status']                   = 1;
                                                    $halfdayLeavesData['username']                 = Auth::user()->name;
                                                    $halfdayLeavesData['date']                     = date("Y-m-d");
                                                    $halfdayLeavesData['time']                     = date("H:i:s");
                                                    $annualLeavesData['from_date'] = 0;
                                                    $annualLeavesData['to_date'] = 0;
                                                    DB::table('leave_application_data')->insert($halfdayLeavesData);

                                            endif;
                                        endif;

                                    endif;
                                }

                            }
                        }

                    }
                }
            }
            fclose($csvFile);
            // exit();/
            CommonHelper::reconnectMasterDatabase();
            Session::flash('dataInsert', 'Successfully Saved');

        } else {
            Session::flash('dataEdit', 'Please upload csv file');

        }
        return Redirect::to('hr/createLeaveApplicationForm?m=' . Input::get('company_id'));

    }
    
    public function addEmployeeAdvanceDetailCsv()
    {
        $location_id = Input::get('location_id');
        $company_id = Input::get('company_id');
        $fileMimes = array(
            // 'text/x-comma-separated-values',
            // 'text/comma-separated-values',
            // 'application/octet-stream',
            // 'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            // 'application/excel',
            // 'application/vnd.msexcel',
            // 'text/plain'
        );

        // Validate whether selected file is a CSV file
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) 
        {

            $row = 0;
            // add you row number for skip
            // hear we pass 1st row for skip in csv
            $skip_row_number = array("1");

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);
            $a = 0 ;
        
            // Parse data from CSV file line by line
            // Parse data from CSV file line by line
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) 
            {
                

                if (in_array($row, $skip_row_number)) 
                {
                    continue;
                    // skip row of csv
                } 
                else 
                {

                    if ($getData[0] && $getData[1] && $getData[2] && $getData[3] && $getData[4]) 
                    {
                       
                        if (!empty($getData[0]) && !empty($getData[1]) && !empty($getData[2]) && !empty($getData[3]) && !empty($getData[4]) ) 
                        {
                           
                            $emp_id = preg_replace('/\s+/', '', $getData[0]);

                            $emp = DB::table(CONST_COMPANY_DB.'.employee as e')
                                    ->join(CONST_COMPANY_DB.'.employee_multiple_locations as eml','e.id', '=', 'eml.employee_id')
                                    ->select('e.id','e.emp_name')->where([ ['e.emp_id' ,'=', $emp_id ] , ['e.status' ,'=', 1] , ['eml.location_id' ,'=', $location_id] , ['eml.status' ,'=', 1] ] )->get();
                            
                            $emp = count($emp) > 0 ? $emp[0] : null;
                            
                            if ($emp) 
                            {
                                    $employee_id = $emp->id;
                                  //  echo $date = DateTime::createFromFormat('d/m/Y',  $getData[2]);
                                  //  echo $date = $date->format('Y-m-d');

                                    $date = DateTime::createFromFormat('n/j/Y', $getData[2]); // Use 'n/j/Y' format for month/day/year
                                    $date = $date->format('Y-n-j'); // Format as 'YYYY-n-j'
                                  
                                    $amount = (float)str_replace(',', '', $getData[1]); 
                                    $month_year = explode('-', $getData[3] );
                                    $month  =  $month_year[1] ;
                                    $year   =  $month_year[0] ;
                                    $reason =  $getData[4];

                                CommonHelper::companyDatabaseConnection(Input::get('company_id'));

                                
                                        $data['employee_id'] = $employee_id;
                                        $data['advance_salary_amount'] = $amount;
                                        $data['salary_needed_on'] = $date;
                                        $data['deduction_month'] = $month;
                                        $data['deduction_year'] =  $year;
                                        $data['detail'] = $reason;
                                        $data['approval_status'] = 1;
                                        $data['username'] = Auth::user()->name;
                                        $data['status'] = 1;
                                        $data['date'] = date("Y-m-d");
                                        $data['time'] = date("H:i:s");

                                        $AdvanceSalary = AdvanceSalary::updateOrCreate(
                                            [
                                                ['employee_id', '=', $employee_id],
                                                ['salary_needed_on', '=', $date],
                                                ['deduction_month', '=', $month],
                                                ['deduction_year', '=', $year]
                                            ],
                                            $data
                                        );
                                        
                                        CommonHelper::LogGenerator('AdvanceSalary',  $AdvanceSalary->id, 'insert', 'row is inserted', 'hr', Auth::user()->id);
                                   
                                        CommonHelper::reconnectMasterDatabase();

                             

                                }

                            }
                        }

                    }
                
            }
            fclose($csvFile);
            CommonHelper::reconnectMasterDatabase();
            Session::flash('dataInsert', 'Successfully Saved');

        }
        else 
        {
            Session::flash('dataEdit', 'Please upload csv file');

        }
        return Redirect::to('hr/viewAdvanceSalaryList?m=' . Input::get('company_id'));

    }
    
     public function addEmployeeAllowanceDetailCsv()
    {

        $location_id = Input::get('location_id');
        $company_id = Input::get('company_id');
        $fileMimes = array(
            // 'text/x-comma-separated-values',
            // 'text/comma-separated-values',
            // 'application/octet-stream',
            // 'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            // 'application/excel',
            // 'application/vnd.msexcel',
            // 'text/plain'
        );

        // Validate whether selected file is a CSV file
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

            $row = 0;
            // add you row number for skip
            // hear we pass 1st row for skip in csv
            $skip_row_number = array("1");

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);
            $a = 0 ;
        
            // Parse data from CSV file line by line
            // Parse data from CSV file line by line
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {

                if (in_array($row, $skip_row_number)) {
                    continue;
                    // skip row of csv
                } else {

                    if ($getData[0] && $getData[1] && $getData[2] && $getData[3]) {

                       
                        $allowancetype = DB::select(DB::raw("select * from ".CONST_MASTER_DB.".allowance_types where status = 1 and allowance_type like '%$getData[1]%'"));
                       

                        if (!empty($allowancetype[0]->id)) {
                           
                            $emp_id = preg_replace('/\s+/', '', $getData[0]);

                            $emp = DB::table(CONST_COMPANY_DB.'.employee as e')
                                    ->join(CONST_COMPANY_DB.'.employee_multiple_locations as eml','e.id', '=', 'eml.employee_id')
                                    ->select('e.id','e.emp_name')->where([ ['e.emp_id' ,'=', $emp_id ] , ['e.status' ,'=', 1] , ['eml.location_id' ,'=', $location_id] , ['eml.status' ,'=', 1] ] )->get();
                            $emp = $emp[0];
                            
                            $employee_id = $emp->id;
                            if (!empty($employee_id)) {
                               
                                $date = 0;
                                $allowancetypeid = $allowancetype[0]->id;
                                $onec = 0;


                                if($getData[4])
                                {

                                    $date = date('Y-m-d',strtotime(str_replace('-','/', $getData[4])));

                                    $onec = 1;
                                }
                              

                                $month_year = 0 ;
                                CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                                
                                        $data['employee_id'] = $employee_id;
                                        $data['allowance_type_id'] = $allowancetypeid;
                                        $data['allowance_amount'] = $getData[2];
                                        $data['remarks'] = $getData[3];
                                        
                                            ($date != 0) ? $month_year = explode('-', $date ): '';
                                            $data['once'] = ($date != 0) ? $onec : 0 ;
                                            $data['month'] = ($date != 0) ?$month_year[1]:'';
                                            $data['year'] = ($date != 0) ? $month_year[0]:'';
                                       
                                        $data['username'] = Auth::user()->name;
                                        $data['status'] = 1;
                                        $data['date'] = date("Y-m-d");
                                        $data['time'] = date("H:i:s");
                                        $AllowanceLastId = Allowance::insertGetId($data);
                                        CommonHelper::LogGenerator('Allowance', $AllowanceLastId, 'insert', 'row is inserted', 'hr', Auth::user()->id);
                                   
                                CommonHelper::reconnectMasterDatabase();



                                }

                            }
                        }

                    }
                
            }
            fclose($csvFile);
            CommonHelper::reconnectMasterDatabase();
            Session::flash('dataInsert', 'Successfully Saved');

        } else {
            Session::flash('dataEdit', 'Please upload csv file');

        }
        return Redirect::to('hr/viewAllowanceList?m=' . Input::get('company_id'));


    }
    
     public function addEmployeeDeductionDetailCsv(request $request)
    {
        
        $location_id = Input::get('location_id');
        $company_id = Input::get('company_id');
        $fileMimes = array(
            // 'text/x-comma-separated-values',
            // 'text/comma-separated-values',
            // 'application/octet-stream',
            // 'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            // 'application/excel',
            // 'application/vnd.msexcel',
            // 'text/plain'
        );

        // Validate whether selected file is a CSV file
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

            $row = 0;
            // add you row number for skip
            // hear we pass 1st row for skip in csv
            $skip_row_number = array("1");

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);
            $a = 0 ;
        
            // Parse data from CSV file line by line
            // Parse data from CSV file line by line
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {

                if (in_array($row, $skip_row_number)) {
                    continue;
                    // skip row of csv
                } else {

                    if ($getData[0] && $getData[1] && $getData[2] && $getData[3]) {

                       

                        if (!empty($getData[1]) && !empty($getData[2])) {
                           
                            $emp_id = preg_replace('/\s+/', '', $getData[0]);

                            $emp = DB::table(CONST_COMPANY_DB.'.employee as e')
                                    ->join(CONST_COMPANY_DB.'.employee_multiple_locations as eml','e.id', '=', 'eml.employee_id')
                                    ->select('e.id','e.emp_name')->where([ ['e.emp_id' ,'=', $emp_id ] , ['e.status' ,'=', 1] , ['eml.location_id' ,'=', $location_id] , ['eml.status' ,'=', 1] ] )->get();
                            
                            $emp = count($emp) > 0 ? $emp[0] : null;
                            if ($emp) {
                               
                            $employee_id = $emp->id;
                                $date = 0;
                                $deductiontypeid = $getData[1];
                                $onec = 0;


                                if($getData[4])
                                {

                                    $date = date('Y-m-d',strtotime(str_replace('-','/', $getData[4])));

                                    $onec = 1;
                                }
                              

                                $month_year = 0 ;
                                CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                                $amount = (float)str_replace(',', '', $getData[2]); 

                                
                                        $data['employee_id'] = $employee_id;
                                        $data['deduction_type'] = $deductiontypeid;
                                        $data['deduction_amount'] = $amount;
                                        $data['remarks'] = $getData[3];
                                        
                                            ($date != 0) ? $month_year = explode('-', $date ): '';
                                            $month = ($date != 0) ?$month_year[1]:'' ;
                                            $year = ($date != 0) ?$month_year[0]:'' ;
                                            $onec = ($date != 0) ? $onec : 0 ;
                                            $data['once'] =  $onec;
                                            $data['month'] = $month;
                                            $data['year'] =  $year;
                                       
                                        $data['username'] = Auth::user()->name;
                                        $data['status'] = 1;
                                        $data['date'] = date("Y-m-d");
                                        $data['time'] = date("H:i:s");
                                        $DeductionLastId = Deduction::updateOrCreate(
                                            [
                                                ['employee_id', '=', $employee_id],
                                                ['month', '!=', ''],
                                                ['year', '!=', ''],
                                                ['once', '!=', '0'],
                                                ['month', '=', $month],
                                                ['year', '=', $year]
                                            ],
                                            $data
                                        );
                                        
                                        CommonHelper::LogGenerator('Deduction',  $DeductionLastId->id, 'insert', 'row is inserted', 'hr', Auth::user()->id);
                                   
                                        CommonHelper::reconnectMasterDatabase();

                             

                                }

                            }
                        }

                    }
                
            }
            fclose($csvFile);
            CommonHelper::reconnectMasterDatabase();
            Session::flash('dataInsert', 'Successfully Saved');

        } else {
            Session::flash('dataEdit', 'Please upload csv file');

        }
        return Redirect::to('hr/viewDeductionList?m=' . Input::get('company_id'));


    }
    
     public function addLoanRequestDetailCsv()
    {

        $location_id = Input::get('location_id');

        $company_id = Input::get('company_id');
        $fileMimes = array(
            // 'text/x-comma-separated-values',
            // 'text/comma-separated-values',
            // 'application/octet-stream',
            // 'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            // 'application/excel',
            // 'application/vnd.msexcel',
            // 'text/plain'
        );

        // Validate whether selected file is a CSV file
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

            $row = 0;
            // add you row number for skip
            // hear we pass 1st row for skip in csv
            $skip_row_number = array("1");

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);
            $a = 0;

            // Parse data from CSV file line by line
            // Parse data from CSV file line by line
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {
        
                if (in_array($row, $skip_row_number)) {
                    continue;
                    // skip row of csv
                } else {

                    if ($getData[0] && $getData[1] && $getData[2] && $getData[5] && $getData[6] && $getData[7]) {

                        $loantype = DB::select(DB::raw("select * from ".CONST_MASTER_DB.".loan_type where status = 1 and loan_type_name	 like '%$getData[1]%'"));

                        if (!empty($loantype[0]->id)) {

                        if (!empty($getData[1])) {

                            $emp_id = preg_replace('/\s+/', '', $getData[0]);

                            $emp = DB::select(DB::raw("select e.* from ".CONST_COMPANY_DB.".employee e
                            inner join ".CONST_COMPANY_DB.".employee_multiple_locations eml 
                            on eml.employee_id = e.id
                            where e.status = 1 and e.emp_id = '$emp_id' and eml.location_id = '$location_id' and eml.status = 1"));
                     
                            if(count($emp)> 0 )
                            {
                              
                                $emp = $emp[0];
                                
                                
                                $employee_id = $emp->id;
                                if (!empty($employee_id)) {

                                    $date = 0;
                                    $loantypeid = $loantype[0]->id;
                                    
                                //    $month_data = explode("-", $getData[4]);
                                    $deduction_start_date = explode("-", $getData[6]);


                                    $data['employee_id'] = $employee_id;
                                    $data['loan_type_id'] = $loantypeid;
                                    $data['year'] = $deduction_start_date[0];
                                    $data['month'] = $deduction_start_date[1];
                                    $data['needed_on_date'] = $getData[4];
                                    $data['loan_amount'] = $getData[2];
                                    $data['out_standing_amount'] = $getData[3];
                                    $data['per_month_deduction'] = $getData[5];
                                    $data['deduction_start_date'] = $deduction_start_date[0] . '-' . $deduction_start_date[1] . '-01';
                                    $data['deduction_start_month'] = $deduction_start_date[1];
                                    $data['deduction_start_year'] = $deduction_start_date[0];
                                    $data['description'] = $getData[7];
                                    $data['status'] = 1;
                                    $data['username'] = Auth::user()->name;
                                    $data['date'] = date("Y-m-d");
                                    $data['time'] = date("H:i:s");

                                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                                    
                                    $loanId = LoanRequest::where([
                                        ['employee_id', '=', $employee_id],
                                        ['needed_on_date', '=', $getData[4]],
                                        ['deduction_start_date', '=', $deduction_start_date[0] . '-' . $deduction_start_date[1] . '-01']
                                    ])->value('id');

                                    if($loanId)
                                    {
                                        LoanRequest::where([['id', '=', $loanId]])->update($data);
                                        CommonHelper::LogGenerator('Loan_Request', $loanId, 'update', 'row is updated', 'hr', Auth::user()->id);

                                    }
                                    else
                                    {
                                        $LoanRequestLastId = LoanRequest::insertGetId($data);
                                        CommonHelper::LogGenerator('Loan_Request', $LoanRequestLastId, 'insert', 'row is inserted', 'hr', Auth::user()->id);
                                    }
                                    
                                    CommonHelper::reconnectMasterDatabase();
                                    
                                }
                            }

                        }
                    }
                    }

                }

            }
            fclose($csvFile);

            CommonHelper::reconnectMasterDatabase();
            Session::flash('dataInsert', 'Successfully Saved');

        } else {
            Session::flash('dataEdit', 'Please upload csv file');

        }
        return Redirect::to('hr/viewLoanRequestList?m=' . Input::get('company_id'));

    }

    public function addDepartmentDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('department_name') as $key => $val):
            $data['department_name'] = trim($val,' ');
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            Department::insert($data);
        endforeach;

        Cache::forget('department');
        Cache::rememberForever('department', function () {
            $values = Department::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewDepartmentList?m='.$company_id);
    }

    public function addSubDepartmentDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('department_id') as $key => $val):
            $data['department_id'] = $val;
            $data['sub_department_name'] = Input::get('sub_department_name')[$key];
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            SubDepartment::insert($data);
        endforeach;


        Cache::forget('sub_department');
        Cache::rememberForever('sub_department', function () {
            $values = SubDepartment::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewSubDepartmentList?m='.$company_id);
    }

    public function addDesignationDetail()
    {
         $company_id = Input::get('company_id');
        foreach (Input::get('designation_name') as $key => $val):
            $data['designation_name'] = $val;
            $data['grade_id'] = Input::get('grade_id')[$key];
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            Designation::insert($data);
        endforeach;
       
        Cache::forget('designation');
        Cache::rememberForever('designation', function () {
            $values = Designation::where([['status', '=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach ($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewDesignationList?m=' . $company_id);
    }

    public function addHealthInsuranceDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('healthInsurance_name') as $key => $val):
            $data['health_insurance_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            HealthInsurance::insert($data);
        endforeach;

        Cache::forget('health_insurance');
        Cache::rememberForever('health_insurance', function () {
            $values = HealthInsurance::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewHealthInsuranceList?m='.$company_id);
    }

    public function addLifeInsuranceDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('life_insurance_name') as $key => $val):
            $data['life_insurance_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            LifeInsurance::insert($data);
        endforeach;

        Cache::forget('life_insurance');
        Cache::rememberForever('life_insurance', function () {
            $values = LifeInsurance::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLifeInsuranceList?m='.$company_id);
    }

    public function addJobTypeDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('job_type_name') as $key => $val):
            $data['job_type_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            JobType::insert($data);
        endforeach;

        Cache::forget('job_type');
        Cache::rememberForever('job_type', function () {
            $values = JobType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewJobTypeList?m='.$company_id);
    }

    public function addLeaveTypeDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('leave_type_name') as $key => $val):
            $data['leave_type_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            LeaveType::insert($data);
        endforeach;

        Cache::forget('leave_type');
        Cache::rememberForever('leave_type', function () {
            $values = LeaveType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLeaveTypeList?m='.$company_id);
    }

    public function addLoanTypeDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('loan_type_name') as $key => $val):
            $data['loan_type_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            LoanType::insert($data);
        endforeach;

        Cache::forget('loan_type');
        Cache::rememberForever('loan_type', function () {
            $values = LoanType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLoanTypeList?m='.$company_id);
    }

    public function addAdvanceTypeDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('advance_type_name') as $key => $val):
            $data['advance_type_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            AdvanceType::insert($data);
        endforeach;

        Cache::forget('advance_type');
        Cache::rememberForever('advance_type', function () {
            $values = AdvanceType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewAdvanceTypeList?m='.$company_id);
    }

    public function addAllowanceTypeDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('allowance_type') as $key => $val):
            $data['allowance_type'] = $val;
            $data['name'] = strtolower(preg_replace('/\s+/', '', "$".$val));
            $data['taxable'] = Input::get('taxable')[$key];
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            AllowanceType::insert($data);
        endforeach;

        Cache::forget('allowance_types');
        Cache::rememberForever('allowance_types', function () {
            $values = AllowanceType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewAllowanceTypeList?m='.$company_id);
    }


    public function addShiftTypeDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('shift_type_name') as $key => $val):
            $data['shift_type_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            ShiftType::insert($data);
        endforeach;

        Cache::forget('shift_type');
        Cache::rememberForever('shift_type', function () {
            $values = ShiftType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewShiftTypeList?m='.$company_id);
    }

    public function addEmployeeGradesDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('employee_grade_type') as $key => $val):
            $data['employee_grade_type'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            Grades::insert($data);
        endforeach;

        Cache::forget('grades');
        Cache::rememberForever('grades', function () {
            $values = Grades::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewEmployeeGradesList?m='.$company_id);
    }

    
    public function addDesignationWiseLocationDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('location_name') as $key => $val):
            $data['location_id'] = $val;
            $data['designation_id'] = Input::get('designation_name')[$key];
            $data['fuel_amount'] = Input::get('fuel_amount')[$key];
            $data['username'] = Auth::user()->name;
            $data['date'] = date("Y-m-d");
            $data['status'] = 1;
            DB::table('designation_wise_locations')->insert($data);
        endforeach;

        

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewDesignationWiseLocation?m='.$company_id);
    }

    public function addLocationsDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('location_name') as $key => $val):
            $data['location_name'] = $val;
            $data['location_code'] = Input::get('location_code')[$key];
            $data['city_id'] = Input::get('city_id')[$key];
            $data['fuel_amount'] = Input::get('fuel_amount')[$key];
            $data['latitude'] = Input::get('latitude')[$key];
            $data['longitude'] = Input::get('longitude')[$key];
            $data['address'] = Input::get('address')[$key];
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            Locations::insert($data);
        endforeach;

        Cache::forget('locations');
        Cache::rememberForever('locations', function () {
            $values = Locations::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLocationsList?m='.$company_id);
    }

    public function addEmployeeDegreeTypeDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('degree_type_name') as $key => $val):
            $data['degree_type_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            DegreeType::insert($data);
        endforeach;

        Cache::forget('degree_type');
        Cache::rememberForever('degree_type', function () {
            $values = DegreeType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewEmployeeDegreeTypeList?m='.$company_id);
    }

    public function addMaritalStatusDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('marital_status_name') as $key => $val):
            $data['marital_status_name'] = $val;
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            MaritalStatus::insert($data);
        endforeach;

        Cache::forget('marital_status');
        Cache::rememberForever('marital_status', function () {
            $values = MaritalStatus::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewMaritalStatusList?m='.$company_id);
    }

    public function addProjectsDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('project_name') as $key => $val):
            $data['project_name'] = $val;
            $data['project_code'] = Input::get('project_name')[$key];
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            EmployeeProjects::insert($data);
        endforeach;

        Cache::forget('employee_projects');
        Cache::rememberForever('employee_projects', function () {
            $values = EmployeeProjects::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewProjectsList?m='.$company_id);
    }

    public function addCitiesDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('city') as $key => $val):
            $data['city'] = $val;
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            Cities::insert($data);
        endforeach;

        Cache::forget('cities');
        Cache::rememberForever('cities', function () {
            $values = Cities::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewCityList?m='.$company_id);
    }

    public function addCategoryDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('employee_category_name') as $key => $val):
            $data['employee_category_name'] = $val;
            $data['company_id'] = $company_id;
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            EmployeeCategory::insert($data);
        endforeach;

        Cache::forget('employee_category');
        Cache::rememberForever('employee_category', function () {
            $values = EmployeeCategory::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewCategoryList?m='.$company_id);
    }

    public function addLeavesPolicyDetail()
    {
        $company_id = Input::get('company_id');
        $data1['leaves_policy_name'] = strip_tags(Input::get('leaves_policy_name'));
        $data1['policy_date_from'] = Input::get('policy_date_from');
        $data1['policy_date_till'] = Input::get('policy_date_till');
        $data1['total_leaves'] = Input::get('totalLeaves');
        $data1['terms_conditions'] = Input::get('terms_conditions');
        $data1['fullday_deduction_rate'] = Input::get('full_day_deduction_rate');
        $data1['halfday_deduction_rate'] = Input::get('half_day_deduction_rate');
        $data1['per_hour_deduction_rate'] = Input::get('per_hour_deduction_rate');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['time'] = date("H:i:s");
        $data1['date'] = date("Y-m-d");
        $last_id = LeavesPolicy::insertGetId($data1);

        Cache::forget('leaves_policy');
        Cache::rememberForever('leaves_policy', function () {
            $values = LeavesPolicy::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        foreach (Input::get('leaves_type_id') as $key => $val):
            $data2['leaves_policy_id'] = $last_id;
            $data2['leave_type_id'] = $val;
            $data2['no_of_leaves'] = Input::get('no_of_leaves')[$key];
            $data2['username'] = Auth::user()->name;;
            $data2['status'] = 1;
            $data2['time'] = date("H:i:s");
            $data2['date'] = date("Y-m-d");
            LeavesData::insert($data2);
        endforeach;

        Cache::forget('leaves_data');
        Cache::rememberForever('leaves_data', function () {
            $values = LeavesData::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLeavesPolicyList?m='.$company_id);
    }

    public function addEOBIDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('EOBI_name') as $key => $val):
            $data['EOBI_name'] = $val;
            $data['EOBI_amount'] = Input::get('EOBI_amount')[$key];
            $data['month_year'] = Input::get('month_year')[$key];
            $data['company_id'] = $company_id;
            $data['username'] = Auth::user()->name;;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            Eobi::insert($data);
        endforeach;

        Cache::forget('eobi');
        Cache::rememberForever('eobi', function () {
            $values = Eobi::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewEOBIList?m='.$company_id);
    }

    public function addEquipmentDetail()
    {
        $company_id = Input::get('company_id');
        foreach (Input::get('equipment_name') as $key => $val):
            $data['equipment_name'] = strip_tags($val);
            $data['username'] = Auth::user()->name;
            $data['company_id'] = $company_id;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            Equipments::insert($data);
        endforeach;

        Cache::forget('employee_equipments');
        Cache::rememberForever('employee_equipments', function () {
            $values = Equipments::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewEquipmentsList?m='.$company_id);
    }


    public function addTrainingDetail()
    {
        $company_id = Input::get('company_id');
        CommonHelper::companyDatabaseConnection($company_id);
        $data['training_date'] = Input::get('training_date');
        $data['topic_name'] = Input::get('training_topic');
        $data['training_hours'] = Input::get('training_hour');
        $data['status'] = 1;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        DB::table('trainings')->insert($data);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewTrainingList?m='.$company_id);
    }

    function addEmployeeTrainingDetail(Request $request){
        $m=Input::get('m');
        CommonHelper::companyDatabaseConnection($m);

        if (Input::get('check')){
            foreach ($request->input('check') as $key => $val){
                $emp_and_training = (explode("_", $val));

                $data['emp_id']=$emp_and_training[0];
                $data['training_id']=$emp_and_training[1];
                $data['status']=1;
                $data['created_at']=date("Y-m-d");
                $data['updated_at']=date("Y-m-d");

                DB::table('employee_training')->where([['emp_id', '=', $emp_and_training[0]], ['training_id', '=', $emp_and_training[1]]])->delete();
                CommonHelper::LogGenerator('employee_training',$emp_and_training[0],'delete','row is deleted','hr',Auth::user()->id);

                $employee_trainingLastId = DB::table('employee_training')->insertGetId($data);

                CommonHelper::LogGenerator('employee_training',$employee_trainingLastId,'insert','row is inserted','hr',Auth::user()->id);


            }
            Session::flash('dataInsert', 'successfully saved.');

        }
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('hr/viewEmpTrainingList?m=' . Input::get('m') . '#vis');

    }


    public function addQualificationDetail()
    {
        $qualificationSection = Input::get('qualificationSection');
        foreach ($qualificationSection as $row) {
            $qualification_name = Input::get('qualification_name_' . $row . '');
            $institute_name = Input::get('institute_name_' . $row . '');
            $country = Input::get('country_' . $row . '');
            $state = Input::get('state_' . $row . '');
            $city = Input::get('city_' . $row . '');
            $institute = Input::get('institute_name_' . $row . '');
            $data2['qualification_name'] = strip_tags($qualification_name);
            $data2['institute_id'] = strip_tags($institute);
            $data2['country_id'] = strip_tags($country);
            $data2['state_id'] = strip_tags($state);
            $data2['city_id'] = strip_tags($city);
            $data2['username'] = Auth::user()->name;
            $data2['status'] = 1;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['company_id'] = $_GET['m'];
            DB::table('qualification')->insert($data2);


        }
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewQualificationList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#vis');
    }


    public function addHiringRequestDetail()
    {
        $d = Input::get('dbName');
        $companyId = Input::get('company_id');
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $jobTitle = Input::get('job_title');
        $jobTypeId = Input::get('job_type_id');
        $subDepartmentId = Input::get('sub_department_id');
        $designationId = Input::get('designation_id');
        $qualificationId = Input::get('qualification_id');
        $shiftTypeId = Input::get('shift_type_id');
        $gender = Input::get('gender');
        $salaryStart = Input::get('salary_start');
        $salaryEnd = Input::get('salary_end');
        $age = Input::get('age');
        $jobDescription = Input::get('job_description');
        $location = Input::get('location');
        $experience = Input::get('experience');
        $career_level = Input::get('career_level');
        $apply_before_date = Input::get('apply_before_date');

        $str = DB::selectOne("select max(convert(substr(`RequestHiringNo`,4,length(substr(`RequestHiringNo`,4))-4),signed integer))
        reg from `requesthiring` where substr(`RequestHiringNo`,-4,2) = " . date('m') . "
        and substr(`RequestHiringNo`,-2,2) = " . date('y') . "")->reg;
        $RequestHiringNo = 'rhn' . ($str + 1) . date('my');

        $data1['RequestHiringNo'] = strip_tags($RequestHiringNo);
        $data1['RequestHiringTitle'] = strip_tags($jobTitle);
        $data1['sub_department_id'] = strip_tags($subDepartmentId);
        $data1['job_type_id'] = strip_tags($jobTypeId);
        $data1['designation_id'] = strip_tags($designationId);
        $data1['qualification_id'] = strip_tags($qualificationId);
        $data1['shift_type_id'] = strip_tags($shiftTypeId);
        $data1['location'] = strip_tags($location);
        $data1['experience'] = strip_tags($experience);
        $data1['career_level'] = strip_tags($career_level);
        $data1['apply_before_date'] = strip_tags($apply_before_date);
        $data1['RequestHiringGender'] = strip_tags($gender);
        $data1['RequestHiringSalaryStart'] = strip_tags($salaryStart);
        $data1['RequestHiringSalaryEnd'] = strip_tags($salaryEnd);
        $data1['RequestHiringAge'] = strip_tags($age);
        $data1['RequestHiringDescription'] = $jobDescription;
        $data1['ApprovalStatus'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('requesthiring')->insert($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewHiringRequestList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $companyId . '#vis');
    }

    function addEmployeeDetail(Request $request)
    {
        
        $company_id = Input::get('company_id');
        $username = Auth::user()->name;
        $status = 1;
        $date = date("Y-m-d");
        $time = date("H:i:s");


        CommonHelper::companyDatabaseConnection($company_id);
        
        $emp_id = Input::get('emp_id');
        $attendance_id = Input::get('attendance_id');
        $emp_name = Input::get('emp_name');
        $emp_father_name = Input::get('emp_father_name');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $designation_id = Input::get('designation_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $grade_id = Input::get('grade_id');
        $marital_status_id = Input::get('marital_status_id');
        $employment_status_id = Input::get('employment_status_id');
        $cpl_ot = Input::get('cpl_ot');
        $gender_id = Input::get('gender_id');
        $nationality = Input::get('nationality');
        $religion = Input::get('religion');
        $date_of_birth = Input::get('date_of_birth');
        $joining_date = Input::get('joining_date');
        $confirmation_date = Input::get('confirmation_date');
        $resign_date = Input::get('resign_date');
        $last_working_date = Input::get('last_working_date');
        $cnic = Input::get('cnic');
        $cnic_expiry_date = Input::get('cnic_expiry_date');
        $life_time_cnic = Input::get('life_time_cnic');
        $reporting_manager_id = Input::get('reporting_manager_id');
        $contact_no = HrHelper::replacePrefix(Input::get('contact_no'));
        $emergency_number = HrHelper::replacePrefix(Input::get('emergency_number'));
        $emergency_contact_person = Input::get('emergency_contact_person');
        $office_no = Input::get('office_no');
        $personal_email = Input::get('personal_email');
        $official_email = Input::get('official_email');
        $salary = Input::get('salary');
        $eobi_id = Input::get('eobi_id');
        $eobi_number = Input::get('eobi_number');
        $leaves_policy_id = Input::get('leaves_policy_id');
        $current_address = Input::get('current_address');
        $permanent_address = Input::get('permanent_address');
        $role_id = Input::get('role_id');
        $ip_district = Input::get('ip_district');
        $ip_number = Input::get('ip_number');

        $data['emp_id'] = $emp_id;
        $data['attendance_id'] = $attendance_id;
        $data['emp_name'] = $emp_name;
        $data['emp_father_name'] = $emp_father_name;
        $data['department_id'] = $department_id;
        $data['sub_department_id'] = $sub_department_id;
        $data['designation_id'] = $designation_id;
        $data['category_id'] = $category_id;
        $data['project_id'] = $project_id;
        $data['grade_id'] = $grade_id;
        $data['marital_status_id'] = $marital_status_id;
        $data['employment_status_id'] = $employment_status_id;
        $data['cpl_or_ot'] = $cpl_ot;
        $data['gender_id'] = $gender_id;
        $data['nationality'] = $nationality;
        $data['religion'] = $religion;
        $data['date_of_birth'] = $date_of_birth;
        $data['joining_date'] = $joining_date;
        $data['confirmation_date'] = $confirmation_date;
        $data['resign_date'] = $resign_date;
        $data['last_working_date'] = $last_working_date;
        $data['cnic'] = $cnic;
        $data['cnic_expiry_date'] = $cnic_expiry_date;
        $data['life_time_cnic'] = $life_time_cnic;
        $data['reporting_manager_id'] = $reporting_manager_id;
        $data['contact_no'] = $contact_no;
        $data['emergency_number'] = $emergency_number;
        $data['emergency_contact_person'] = $emergency_contact_person;
        $data['office_no'] = $office_no;
        $data['personal_email'] = $personal_email;
        $data['official_email'] = $official_email;
        $data['salary'] = $salary;
        $data['eobi_id'] = $eobi_id;
        $data['eobi_number'] = $eobi_number;
        $data['leaves_policy_id'] = $leaves_policy_id;
        $data['current_address'] = $current_address;
        $data['permanent_address'] = $permanent_address;
        $data['role_id'] = $role_id;
        $data['username'] = $username;
        $data['status'] = $status;
        $data['date'] = $date;
        $data['time'] = $time;
        $data['ip_district'] = $ip_district;
        $data['ip_number'] = $ip_number;

        //employee image
        if ($request->file('fileToUpload_1')):
            $file_name = $emp_name . '_' . time() . '.' . $request->file('fileToUpload_1')->getClientOriginalExtension();
            $path = 'app/' . $request->file('fileToUpload_1')->storeAs('uploads/employee_images', $file_name);
            $data['img_path'] = $path;
        else:
            $path = 'app/uploads/employee_images/user-dummy.png';
            $data['img_path'] = $path;
        endif;

        //eobi image
        if ($request->file('eobi_path')):
            $file_name1 = $emp_name . '_' . time() . '.' . $request->file('eobi_path')->getClientOriginalExtension();
            $path1 = 'app/' . $request->file('eobi_path')->storeAs('uploads/employee_eobi_copy', $file_name1);
            $data['eobi_path'] = $path1;
            $data['eobi_type'] = $request->file('eobi_path')->getClientOriginalExtension();
        else:
            $data['eobi_path'] = null;
            $data['eobi_type'] = null;
        endif;

        $last_insert_id = Employee::insertGetId($data);
        
        CommonHelper::LogGenerator('employee',$last_insert_id,'insert','row is inserted','hr',Auth::user()->id);
        
        HrHelper::remainingLeaveAfterConfirmation($company_id,$last_insert_id);


        //multiple locations
         EmployeeMultipleLocations::where([['employee_id', '=', $last_insert_id]])->update(['status' => 2 ]);
      
         foreach (Input::get('location_id') as $location_id_key => $location_id_value) {
            # code...
            $location['employee_id'] = $last_insert_id;
            $location['location_id'] = $location_id_value;
            $location['company_id'] = $company_id;
            $location['project_id'] = $project_id;
            $location['username'] = $username;
            $location['status'] = $status;
            $location['date'] = $date;
            $location['time'] = $time;
            $EmployeeMultipleLocationslastid = EmployeeMultipleLocations::insertGetId($location);
            CommonHelper::LogGenerator('EmployeeMultipleLocations',$EmployeeMultipleLocationslastid,'insert','row is inserted','hr',Auth::user()->id);
        }
            
        if (Input::get('bank_account_check') == 'on'):
            $bankData['employee_id'] = $last_insert_id;
            $bankData['account_no'] = Input::get('account_no');
            $bankData['bank_name'] = Input::get('bank_name');
            $bankData['account_title'] = Input::get('account_title');
            $bankData['username'] = $username;
            $bankData['status'] = $status;
            $bankData['date'] = $date;
            $bankData['time'] = $time;
            $EmployeeBankDataLastId = EmployeeBankData::insertGetId($bankData);
            CommonHelper::LogGenerator('Employee_Bank_Data',$EmployeeBankDataLastId,'insert','row is inserted','hr',Auth::user()->id);
                
        endif;

        //family data
        if (!empty(Input::get('family_data'))):
            foreach (Input::get('family_data') as $familyRow):
                $familyData['employee_id'] = $last_insert_id;
                $familyData['family_name'] = Input::get('family_name_' . $familyRow . '');
                $familyData['family_relation'] = Input::get('family_relation_' . $familyRow . '');
                $familyData['family_emergency_contact'] = Input::get('family_emergency_' . $familyRow . '');
                $familyData['username'] = $username;
                $familyData['status'] = $status;
                $familyData['date'] = $date;
                $familyData['time'] = $time;
               $EmployeeFamilyDataLastId = EmployeeFamilyData::insertGetId($familyData);
                CommonHelper::LogGenerator('Employee_family_Data',$EmployeeFamilyDataLastId,'insert','row is inserted' ,'hr',Auth::user()->id);
               
            endforeach;
        endif;

        //Educational data
        if (!empty(Input::get('education_data'))):
            foreach (Input::get('education_data') as $educationalRow):
                $educationalData['employee_id'] = $last_insert_id;
                $educationalData['institute_name'] = Input::get('institute_name_' . $educationalRow . '');
                $educationalData['year_of_admission'] = Input::get('year_of_admission_' . $educationalRow . '');
                $educationalData['year_of_passing'] = Input::get('year_of_passing_' . $educationalRow . '');
                $educationalData['degree_type'] = Input::get('degree_type_' . $educationalRow . '');
                $educationalData['username'] = $username;
                $educationalData['status'] = $status;
                $educationalData['date'] = $date;
                $educationalData['time'] = $time;
                $EmployeeEducationalDataLastId = EmployeeEducationalData::insertGetId($educationalData);
                CommonHelper::LogGenerator('Employee_Educational_Data',$EmployeeEducationalDataLastId,'insert','row is inserted' ,'hr',Auth::user()->id);
               
            endforeach;
        endif;

        //Language data
        if (!empty(Input::get('language_data'))):
            foreach (Input::get('language_data') as $languageRow):
                $languageData['employee_id'] = $last_insert_id;
                $languageData['language_name'] = Input::get('language_name_' . $languageRow . '');
                $languageData['reading_skills'] = Input::get('reading_skills_' . $languageRow . '');
                $languageData['writing_skills'] = Input::get('writing_skills_' . $languageRow . '');
                $languageData['speaking_skills'] = Input::get('speaking_skills_' . $languageRow . '');
                $languageData['username'] = $username;
                $languageData['status'] = $status;
                $languageData['date'] = $date;
                $languageData['time'] = $time;
                $EmployeeLanguageProficiencyLastId = EmployeeLanguageProficiency::insertGetId($languageData);
                CommonHelper::LogGenerator('Employee_Language_Proficiency',$EmployeeLanguageProficiencyLastId,'insert','row is inserted','hr',Auth::user()->id);
               
            endforeach;
        endif;

        //Health data
        if (!empty(Input::get('health_data'))):
            foreach (Input::get('health_data') as $healthRow):
                $healthData['employee_id'] = $last_insert_id;
                $healthData['health_type'] = Input::get('health_type_' . $healthRow . '');
                $healthData['health_check'] = Input::get('health_check_' . $healthRow . '');
                $healthData['physical_handicap'] = Input::get('physical_handicap');
                $healthData['height'] = Input::get('height');
                $healthData['weight'] = Input::get('weight');
                $healthData['blood_group'] = Input::get('blood_group');
                $healthData['username'] = $username;
                $healthData['status'] = $status;
                $healthData['date'] = $date;
                $healthData['time'] = $time;
                $EmployeeHealthDataLastId = EmployeeHealthData::insertGetId($healthData);
                 CommonHelper::LogGenerator('Employee_Health_Data',$EmployeeHealthDataLastId,'insert','row is inserted' ,'hr',Auth::user()->id);
               
            endforeach;
        endif;

        //Activity data
        if (!empty(Input::get('activity_data'))):
            foreach (Input::get('activity_data') as $activityRow):
                $activityData['employee_id'] = $last_insert_id;
                $activityData['institution_name'] = Input::get('institution_name_' . $activityRow . '');
                $activityData['position_held'] = Input::get('position_held_' . $activityRow . '');
                $activityData['username'] = $username;
                $activityData['status'] = $status;
                $activityData['date'] = $date;
                $activityData['time'] = $time;
                $EmployeeActivityDataLastId = EmployeeActivityData::insertGetId($activityData);
                CommonHelper::LogGenerator('Employee_Activity_Data',$EmployeeActivityDataLastId,'insert','row is inserted','hr',Auth::user()->id);
               
            endforeach;
        endif;

        //work experience data
        $counter = 1;
        if (!empty(Input::get('work_experience_data'))):
            foreach (Input::get('work_experience_data') as $workExperienceRow):

                if ($request->hasFile('work_exp_path_1')):
                    $extension = $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $file_name3 = $emp_id . '_' . $counter . '_' . time() . '.' . $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $path3 = 'app/' . $request->file('work_exp_path_' . $workExperienceRow . '')->storeAs('uploads/employee_experience_documents', $file_name3);

                    $workExperienceData['work_exp_path'] = $path3;
                    $workExperienceData['work_exp_name'] = $file_name3;
                    $workExperienceData['work_exp_type'] = $extension;
                else:
                    $workExperienceData['work_exp_path'] = null;
                    $workExperienceData['work_exp_name'] = null;
                    $workExperienceData['work_exp_type'] = null;
                endif;

                $counter++;
                $workExperienceData['employee_id'] = $last_insert_id;
                $workExperienceData['employeer_name'] = Input::get('employeer_name_' . $workExperienceRow . '');
                $workExperienceData['started'] = Input::get('started_' . $workExperienceRow . '');
                $workExperienceData['ended'] = Input::get('ended_' . $workExperienceRow . '');
                $workExperienceData['suspend_check'] = Input::get('suspend_check_1');
                $workExperienceData['suspend_reason'] = Input::get('suspend_reason_1');
                $workExperienceData['username'] = $username;
                $workExperienceData['status'] = $status;
                $workExperienceData['date'] = $date;
                $workExperienceData['time'] = $time;
                $EmployeeWorkExperienceLastId = EmployeeWorkExperience::insertGetId($workExperienceData);
                CommonHelper::LogGenerator('Employee_Work_Experience',$EmployeeWorkExperienceLastId,'insert','row is inserted','hr',Auth::user()->id);
               
            endforeach;
        endif;

        //Reference data
        if (!empty(Input::get('reference_data'))):
            foreach (Input::get('reference_data') as $referenceRow):
                $referenceData['employee_id'] = $last_insert_id;
                $referenceData['reference_name'] = Input::get('reference_name_' . $referenceRow . '');
                $referenceData['reference_designation'] = Input::get('reference_designation_' . $referenceRow . '');
                $referenceData['reference_organization'] = Input::get('reference_organization_' . $referenceRow . '');
                $referenceData['reference_contact'] = Input::get('reference_contact_' . $referenceRow . '');
                $referenceData['reference_relationship'] = Input::get('reference_relationship_' . $referenceRow . '');
                $referenceData['username'] = $username;
                $referenceData['status'] = $status;
                $referenceData['date'] = $date;
                $referenceData['time'] = $time;
                $EmployeeReferenceDataLastId = EmployeeReferenceData::insertGetId($referenceData);
                CommonHelper::LogGenerator('Employee_Reference_Data',$EmployeeReferenceDataLastId,'insert','row is inserted','hr',Auth::user()->id);
               
            endforeach;
        endif;

        //kins data
        if (!empty(Input::get('kins_data'))):
            foreach (Input::get('kins_data') as $kinsRow):
                $kinsData['employee_id'] = $last_insert_id;
                $kinsData['next_kin_name'] = Input::get('next_kin_name_' . $kinsRow . '');
                $kinsData['next_kin_relation'] = Input::get('next_kin_relation_' . $kinsRow . '');
                $kinsData['username'] = $username;
                $kinsData['status'] = $status;
                $kinsData['date'] = $date;
                $kinsData['time'] = $time;
                $EmployeeKinsDataLastId = EmployeeKinsData::insertGetId($kinsData);
                CommonHelper::LogGenerator('Employee_Kins_Data',$EmployeeKinsDataLastId,'insert','row is inserted','hr',Auth::user()->id);
               
            endforeach;
        endif;

        //relatives data
        if (!empty(Input::get('relatives_data'))):
            foreach (Input::get('relatives_data') as $relativesRow):
                $relativesData['employee_id'] = $last_insert_id;
                $relativesData['relative_name'] = Input::get('relative_name_' . $relativesRow . '');
                $relativesData['relative_position'] = Input::get('relative_position_' . $relativesRow . '');
                $relativesData['username'] = $username;
                $relativesData['status'] = $status;
                $relativesData['date'] = $date;
                $relativesData['time'] = $time;
                $EmployeeRelativesDataLastId = EmployeeRelativesData::insertGetId($relativesData);
                CommonHelper::LogGenerator('Employee_Relatives_Data',$EmployeeRelativesDataLastId,'insert','row is inserted','hr',Auth::user()->id);
               
            endforeach;
        endif;

        //other details
        $otherDetails['employee_id'] = $last_insert_id;
        $otherDetails['crime_check'] = Input::get('crime_check');
        $otherDetails['crime_detail'] = Input::get('crime_detail');
        $otherDetails['additional_info_check'] = Input::get('additional_info_check');
        $otherDetails['additional_info_detail'] = Input::get('additional_info_detail');
        $otherDetails['username'] = $username;
        $otherDetails['status'] = $status;
        $otherDetails['date'] = $date;
        $otherDetails['time'] = $time;
        $EmployeeOtherDetailsLastId = EmployeeOtherDetails::insertGetId($otherDetails);
        CommonHelper::LogGenerator('Employee_Other_Details',$EmployeeOtherDetailsLastId,'insert','row is inserted','hr',Auth::user()->id);
               
        //Documents uploading
        $counter = 0;
        if($request->documents_upload_check){
            if ($request->file('media')) {
                foreach ($request->file('media') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'cv';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted','hr',Auth::user()->id);
       
                    }
                }
            }
            if ($request->file('edu_degrees')) {
                foreach ($request->file('edu_degrees') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'education';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted','hr',Auth::user()->id);
       
                    }
                }
            }
            if ($request->file('passport_photos')) {
                foreach ($request->file('passport_photos') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'passport';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted','hr',Auth::user()->id);
       
                    }
                }
            }
            if ($request->file('clearance_letter')) {
                foreach ($request->file('clearance_letter') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'clearance_letter';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted','hr',Auth::user()->id);
       
                    }
                }
            }
            if ($request->file('fit_and_criteria_form')) {
                foreach ($request->file('fit_and_criteria_form') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'fit_criteria_form';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted' ,'hr',Auth::user()->id);
       
                    }
                }
            }
            if ($request->file('company_service_rules')) {
                foreach ($request->file('company_service_rules') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'company_service_rules';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted','hr',Auth::user()->id);
       
                    }
                }
            }
            if ($request->file('code_of_conduct')) {
                foreach ($request->file('code_of_conduct') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'code_of_conduct';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted','hr',Auth::user()->id);
       
                    }
                }
            }
            if ($request->file('non_disclosure_agreement')) {
                foreach ($request->file('non_disclosure_agreement') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'non_disclosure_agreement';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted' ,'hr',Auth::user()->id);
       
                    }
                }
            }

            if ($request->file('confidentially_form')) {
                foreach ($request->file('confidentially_form') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'confidentially_form';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted' ,'hr',Auth::user()->id);
       
                    }
                }
            }

            if ($request->file('cnic_path_1')) {

                $counter++;
                $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                $path = $media->storeAs('uploads/employee_documents', $file_name);

                $fileUploadData['employee_id'] = $last_insert_id;
                $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                $fileUploadData['file_name'] = $file_name;
                $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                $fileUploadData['file_path'] = $path;
                $fileUploadData['type'] = 'cnic_copy';
                $fileUploadData['counter'] = $counter;
                $fileUploadData['username'] = $username;
                $fileUploadData['status'] = $status;
                $fileUploadData['date'] = $date;
                $fileUploadData['time'] = $time;
                $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted' ,'hr',Auth::user()->id);
       
            }

            if ($request->file('investment_disclosure')) {
                foreach ($request->file('investment_disclosure') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $last_insert_id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'investment_disclosure';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentsLastId = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('Employee_Documents',$EmployeeDocumentsLastId,'insert','row is inserted','hr',Auth::user()->id);
       
                    }
                }
            }
        }

        if($request->employee_items_check && !empty(Input::get('equipment_id'))):
            EmployeeEquipments::where('employee_id', $last_insert_id)->delete();

            foreach (Input::get('equipment_id') as $key => $val):
                if (strip_tags($val) == 11):
                    $data1['model_number'] = Input::get('model_number');
                    $data1['mobile_number'] = Input::get('mobile_number');
                    $data1['sim_number'] = Input::get('sim_number');
                endif;

                $data1['equipment_id'] = strip_tags($val);
                $data1['employee_id'] = $last_insert_id;
                $data1['approval_status'] = 1;
                $data1['username'] = $username;
                $data1['status'] = $status;
                $data1['date'] = $date;
                $data1['time'] = $time;
                $EmployeeEquipmentsLastId = EmployeeEquipments::insertGetId($data1);
                        CommonHelper::LogGenerator('Employee_Equipments',$EmployeeEquipmentsLastId,'insert','row is inserted','hr',Auth::user()->id);
       
            endforeach;
        endif;

        CommonHelper::reconnectMasterDatabase();
        if (Input::get('can_login')):
            $employee_password = Input::get('password');
            $employee_account_type = Input::get('account_type');

            $dataCredentials['emp_id'] = $emp_id;
            $dataCredentials['employee_id'] = $last_insert_id;
            $dataCredentials['name'] = $emp_name;
            $dataCredentials['username'] = $official_email;
            $dataCredentials['email'] = $official_email;
            $dataCredentials['mobile_no'] = $contact_no;
            $dataCredentials['password'] = Hash::make($employee_password);
            $dataCredentials['acc_type'] = $employee_account_type;
            $dataCredentials['updated_at'] = $date;
            $dataCredentials['created_at'] = $date;
            $dataCredentials['company_id'] = $company_id;
            $userLastId = Users::insertGetId($dataCredentials);
            CommonHelper::companyDatabaseConnection($company_id);

            CommonHelper::LogGenerator('users',$userLastId,'insert','row is inserted','hr',Auth::user()->id);
            CommonHelper::reconnectMasterDatabase();
            $roles = Role::where([['id','=',$role_id]]);

            if($roles->count() > 0):

                MenuPrivileges::where('employee_id', $last_insert_id)->delete();
                $MenuPrivileges                         = new MenuPrivileges();
                $MenuPrivileges->emp_id                 = $emp_id;
                $MenuPrivileges->role_id                = $role_id;
                $MenuPrivileges->employee_id            = $last_insert_id;
                $MenuPrivileges->main_modules           = $roles->value('main_modules');
                $MenuPrivileges->submenu_id             = $roles->value('submenu_id');
                $MenuPrivileges->menu_titles            = $roles->value('menu_titles');
                $MenuPrivileges->crud_rights            = $roles->value('crud_rights');
                $MenuPrivileges->company_list           = $roles->value('company_list');
                $MenuPrivileges->status                 = $status;
                $MenuPrivileges->username               = $username;
                $MenuPrivileges->created_at             = $date;
                $MenuPrivileges->updated_at             = $date;
                $MenuPrivileges->save();
                CommonHelper::companyDatabaseConnection($company_id);
                CommonHelper::LogGenerator('Menu_Privileges',$MenuPrivileges->id,'insert','row is inserted','hr',Auth::user()->id);
            endif;
        endif;
        
        RefreshCacheHelper::EmployeeCache();
        //AttendanceHelper::addEmptyAttendanceRecords();
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewEmployeeList?m='.Input::get('company_id'));
    }

   public function uploadEmployeeFileDetail(Request $request)
    {
        $location_id = Input::get('location_id');
        $company_id = Auth::user()->company_id;
        $data = Excel::toArray(new UsersImport, request()->file('employeeFile'));
        $counters = 1;

        // if (trim(trim($data[0][1][0]) == 'Employee ID' && trim($data[0][1][1] == 'Employee Name') &&
        //     trim($data[0][1][2]) == 'Father / Husband Name' && trim($data[0][1][3]) == 'Department' && trim($data[0][1][4]) == 'Sub Department' &&
        //     trim($data[0][1][5]) == 'Designation' && trim($data[0][1][6]) == 'Marital Status' && trim($data[0][1][7]) == 'Employment Status' &&
        //     trim($data[0][1][8]) == 'Gender' && trim($data[0][1][9]) == 'CNIC' &&
        //     trim($data[0][1][10]) == 'Date of Birth' && trim($data[0][1][11]) == 'Place of Birth' && trim($data[0][1][12]) == 'Nationality' && trim($data[0][1][13]) == 'Joining Date' && trim($data[0][1][14]) == 'Contact Number' && trim($data[0][1][15]) == 'Landline Number' && trim($data[0][1][16]) == 'Official Email' && trim($data[0][1][17]) == 'Compensation' && trim($data[0][1][18]) == 'Religion' && trim($data[0][1][19]) == 'Eobi' && trim($data[0][1][20]) == 'Leaves Policy')):


        foreach ($data[0] as $value) {
            
            
            if ($counters == 1) {
            
                $EmployeeProjects = 0 ;
                if ($value[0] == '' || $value[0] == 'Employee ID' || $value[0] == 'Employee ID ') continue;
                if ($value[0] != '') {
                
                    $department_id = '';
                    if ($value[4] != '') {
                        $department = Department::select('id')->where([['status', '=', 1], ['department_name', '=', trim($value[4], ' ')]]);
                        if ($department->count() > 0) {
                            $department_id = $department->value('id');
                        } else {
                            $department_id = Department::insertGetId(
                                [
                                    'department_name' => trim($value[4]),
                                    'status' => 1,
                                    'company_id' => $company_id,
                                    'username' => Auth::user()->name,
                                    'date' => date("Y-m-d"),
                                    'time' => date("H:i:s")
                                ]
                            );
                        }
                    }
                    if ($value[28] != '') {
                        $EmployeeProjects = EmployeeProjects::select('id')->where([['status', '=', 1], ['project_name', '=', trim($value[28], ' ')]]);
                        if ($EmployeeProjects->count() > 0) {
                            $EmployeeProjects = $EmployeeProjects->value('id');
                        } else {
                            $EmployeeProjects = EmployeeProjects::insertGetId(
                                [
                                    'project_name' => trim($value[28]),
                                    'project_code' => trim($value[28]),
                                    'status' => 1,
                                    'company_id' => $company_id,
                                    'username' => Auth::user()->name,
                                    'date' => date("Y-m-d"),
                                    'time' => date("H:i:s")
                                ]
                            );
                        }
                    }
                    $sub_department_id = '';
                    if ($value[5] != '') {
                        $sub_department = SubDepartment::select('id')->where([['status', '=', 1], ['sub_department_name', '=', trim($value[5])]]);
                        if ($sub_department->count() > 0) {
                            $sub_department_id = $sub_department->value('id');
                        } else {
                            $sub_department_id = SubDepartment::insertGetId(
                                [
                                    'sub_department_name' => trim($value[5]),
                                    'department_id' => $department_id,
                                    'status' => 1,
                                    'company_id' => $company_id,
                                    'username' => Auth::user()->name,
                                    'date' => date("Y-m-d"),
                                    'time' => date("H:i:s")
                                ]
                            );
                        }
                    }

                    $designation_id = '';
                    if ($value[6] != '') {
                        $designation = Designation::select('id')->where([['status', '=', 1], ['designation_name', '=', trim($value[6])]]);
                        if ($designation->count() > 0) {
                            $designation_id = $designation->value('id');
                        } else {
                            $designation_id = Designation::insertGetId(
                                [
                                    'designation_name' => trim($value[6]),
                                    'status' => 1,
                                    'company_id' => $company_id,
                                    'username' => Auth::user()->name,
                                    'date' => date("Y-m-d"),
                                    'time' => date("H:i:s")
                                ]
                            );
                        }
                    }

                    $category_id = '';
                    if ($value[7] != '') {
                        $category = EmployeeCategory::select('id')->where([['status', '=', 1], ['employee_category_name', '=', trim($value[7])]]);
                        if ($category->count() > 0) {
                            $category_id = $category->value('id');
                        } else {
                            $category_id = EmployeeCategory::insertGetId(
                                [
                                    'employee_category_name' => trim($value[7]),
                                    'status' => 1,
                                    'company_id' => $company_id,
                                    'username' => Auth::user()->name,
                                    'date' => date("Y-m-d"),
                                    'time' => date("H:i:s")
                                ]
                            );
                        }
                    }

                    $grade_id = '';
                    if ($value[8] != '') {
                        $grade = DB::table('grades')->select('id')->where([['status', '=', 1], ['employee_grade_type', '=', trim($value[8])]]);
                        if ($grade->count() > 0) {
                            $grade_id = $grade->value('id');
                        } else {
                            $grade_id = DB::table('grades')->insertGetId(
                                [
                                    'employee_grade_type' => trim($value[8]),
                                    'status' => 1,
                                    'company_id' => $company_id,
                                    'username' => Auth::user()->name,
                                    'date' => date("Y-m-d"),
                                    'time' => date("H:i:s")
                                ]
                            );
                        }
                    }

                    $marital_status_id = '';
                    if ($value[9] != '') {
                        $marital_status = MaritalStatus::select('id')->where([['status', '=', 1], ['marital_status_name', '=', $value[9]], ['company_id', '=', $company_id]]);
                        if ($marital_status->count() > 0) {
                            $marital_status_id = $marital_status->value('id');
                        } else {
                            $marital_status_id = MaritalStatus::insertGetId(
                                [
                                    'marital_status_name' => trim($value[9]),
                                    'status' => 1,
                                    'company_id' => $company_id,
                                    'username' => Auth::user()->name,
                                    'date' => date("Y-m-d"),
                                    'time' => date("H:i:s")
                                ]
                            );
                        }
                    }

                    $employment_status_id = '';
                    if ($value[10] != '') {
                        $job_type = JobType::select('id')->where([['status', '=', 1], ['job_type_name', '=', $value[10]], ['company_id', '=', $company_id]]);
                        if ($job_type->count() > 0) {
                            $employment_status_id = $job_type->value('id');
                        } else {
                            $employment_status_id = JobType::insertGetId(
                                [
                                    'job_type_name' => trim($value[10]),
                                    'status' => 1,
                                    'company_id' => $company_id,
                                    'username' => Auth::user()->name,
                                    'date' => date("Y-m-d"),
                                    'time' => date("H:i:s")
                                ]
                            );
                        }
                    }

                    $date_of_birth = '';
                    if ($value[14] != '') {
                        $excel_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[14]));
                        $excel_emp_date = Carbon::parse($excel_date);
                        $excel_date = explode(' ', $excel_emp_date);
                        $excel_emp_date = $excel_date[0];
                        $day = date('d', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $month = date('m', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $year = date('Y', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $date_of_birth = $year . "-" . $month . "-" . $day;
                    }

                    $joining_date = '';
                    if ($value[15] != '') {
                        $excel_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[15]));
                        $excel_emp_date = Carbon::parse($excel_date);
                        $excel_date = explode(' ', $excel_emp_date);
                        $excel_emp_date = $excel_date[0];
                        $day = date('d', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $month = date('m', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $year = date('Y', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $joining_date = $year . "-" . $month . "-" . $day;
                    }

                    $confirmation_date = '';
                    if ($value[16] != '') {
                        $excel_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[16]));
                        $excel_emp_date = Carbon::parse($excel_date);
                        $excel_date = explode(' ', $excel_emp_date);
                        $excel_emp_date = $excel_date[0];
                        $day = date('d', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $month = date('m', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $year = date('Y', strtotime(str_replace('/', '-', $excel_emp_date)));
                        $confirmation_date = $year . "-" . $month . "-" . $day;
                    }

                    $cnic_expiry_date = '';
                    $life_time_cnic = '';
                    if ($value[18] != '') {
                        if ($value[18] == 'LIFE TIME') {
                            $life_time_cnic = 1;
                    
                        } else {
                            
                            $excel_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[18]));
                            $excel_emp_date = Carbon::parse($excel_date);
                            $excel_date = explode(' ', $excel_emp_date);
                            $excel_emp_date = $excel_date[0];
                            $day = date('d', strtotime(str_replace('/', '-', $excel_emp_date)));
                            $month = date('m', strtotime(str_replace('/', '-', $excel_emp_date)));
                            $year = date('Y', strtotime(str_replace('/', '-', $excel_emp_date)));
                            $cnic_expiry_date = $year . "-" . $month . "-" . $day;
            
                        }
                    }
                    
            
                    $data2['emp_id'] = ($value[0] == '' ? '' : $value[0]);
                    $data2['attendance_id'] = ($value[1] == '' ? '' : $value[1]);
                    $data2['emp_name'] = ($value[2] == '' ? '' : $value[2]);
                    $data2['emp_father_name'] = ($value[3] == '' ? '' : $value[3]);
                    $data2['department_id'] = $department_id;
                    $data2['sub_department_id'] = $sub_department_id;
                    $data2['designation_id'] = $designation_id;
                    $data2['project_id'] = ($EmployeeProjects == '' ? '' : $EmployeeProjects) ; //$designation_id;
                    $data2['category_id'] = $category_id;
                    $data2['grade_id'] = $grade_id;
                    $data2['marital_status_id'] = $marital_status_id;
                    $data2['employment_status_id'] = $employment_status_id;
                    $data2['gender_id'] = ($value[11] == 'Male' ? '1' : '0');
                    $data2['nationality'] = ($value[12] == '' ? '' : $value[12]);
                    $data2['religion'] = ($value[13] == '' ? '' : $value[13]);
                    $data2['date_of_birth'] = $date_of_birth;
                    $data2['joining_date'] = $joining_date;
                    $data2['confirmation_date'] = $confirmation_date;
                    $data2['cnic'] = ($value[17] == '' ? '' : $value[17]);
                    $data2['cnic_expiry_date'] = $cnic_expiry_date;
                    $data2['cnic_expiry_date'] = $life_time_cnic;
                    $data2['contact_no'] = ($value[19] == '' ? '' : strval($value[19]));
                    $data2['emergency_number'] = ($value[20] == '' ? '' : strval($value[20]));
                    $data2['emergency_contact_person'] = ($value[21] == '' ? '' : $value[21]);
                    $data2['office_no'] = ($value[22] == '' ? '' : $value[22]);
                    $data2['personal_email'] = ($value[23] == '' ? '' : $value[23]);
                    $data2['official_email'] = ($value[24] == '' ? '' : $value[24]);
                    $data2['salary'] = ($value[25] == '' ? '' : $value[25]);
                    $data2['current_address'] = ($value[26] == '' ? '' : $value[26]);
                    $data2['permanent_address'] = ($value[27] == '' ? '' : $value[27]);
                    $data2['leaves_policy_id'] = 1;
                    $data2['eobi_id'] = 1;
                    $data2['username'] = Auth::user()->name;
                    $data2['status'] = 1;
                    $data2['date'] = date("Y-m-d");
                    $data2['time'] = date("H:i:s");

                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                    $employeeCount = Employee::join('employee_multiple_locations', 'employee.id', '=', 'employee_multiple_locations.employee_id')
                                    ->where('employee.emp_id', '=', "$value[0]")
                                    ->where('employee_multiple_locations.location_id', '=', $location_id)
                                    ->where('employee_multiple_locations.status', '=', 1)
                                    ->count();
                        

                    if ($employeeCount > 0) {
                        
                        
                        $employeeIds = Employee::join('employee_multiple_locations', 'employee.id', '=', 'employee_multiple_locations.employee_id')
                                        ->where('employee.emp_id', '=', "$value[0]")
                                        ->where('employee_multiple_locations.location_id', '=', $location_id)
                                        ->where('employee_multiple_locations.status', '=', 1)
                                        ->pluck('employee.id');
                        Employee::where([['id','=', $employeeIds]])->update($data2);
                        $id = Employee::where('id','=',$employeeIds)->select('id')->value('id');
                
                    }
                    else {
                        
                    $id = Employee::insertGetId($data2);
                    }

                    
                    $location = [];
                    $location['status'] = 2;
                    EmployeeMultipleLocations::where([['employee_id','=',$id]])->update($location);
                    $location = [];
                    $location['employee_id'] = $id;
                    $location['project_id'] =  ($EmployeeProjects == '' ? '' : $EmployeeProjects);
                    $location['location_id'] = $location_id;
                    $location['username'] = Auth::user()->name;
                    $location['status'] = 1;
                    $location['date'] = date("Y-m-d");
                    $location['time'] = date("H:i:s");
                    $last_insert_location_id = EmployeeMultipleLocations::insertGetId($location);


                    if($value[29] != '' && $value[28] != '')
                    {
                        $employeeBankDetail = [];


                        $employeeBankDetail['status'] = 2;
                        EmployeeBankData::where([['employee_id','=',$id]])->update($employeeBankDetail);
                        $employeeBankDetail = [];
                        $employeeBankDetail['employee_id'] = $id;
                        $employeeBankDetail['bank_name'] = ($value[28] == '' ? '' : $value[28]);
                        $employeeBankDetail['account_no'] = ($value[29] == '' ? '' :  str_replace(' ', '',$value[29]) );
                        $employeeBankDetail['account_title'] = ($value[2] == '' ? '' : $value[2]);
                        $employeeBankDetail['username'] = Auth::user()->name;
                        $employeeBankDetail['status'] = 1;
                        $employeeBankDetail['date'] = date("Y-m-d");
                        $employeeBankDetail['time'] = date("H:i:s");
                        $EmployeeBankDataLastId = EmployeeBankData::insertGetId($employeeBankDetail);
                    }   
                    
                    CommonHelper::reconnectMasterDatabase();
                }
            }
        }

        RefreshCacheHelper::RefreshAll(Input::get('company_id'));

        Session::flash('dataInsert', 'Successfully saved');
        return Redirect::to('hr/uploadEmployeeFileForm?m=' . Input::get('company_id'));
    }

    function addManageAttendenceDetail()
    {

        FinanceHelper::companyDatabaseConnection(Input::get('m'));
        $sub_department_id = Input::get('sub_department_id_1');
        $attendence_date = Input::get('attendence_date');
        $emp_id = Input::get('emp_id_');
        foreach ($emp_id as $row1) {
            $attendence_type = Input::get('attendence_status_' . $row1 . '');
            $attendence_remarks = Input::get('attendence_remarks_' . $row1 . '');

            $data1['emp_id'] = strip_tags($row1);
            $data1['sub_department_id'] = strip_tags($sub_department_id);
            $data1['attendence_date'] = strip_tags($attendence_date);
            $data1['attendence_type'] = strip_tags($attendence_type);
            $data1['remarks'] = strip_tags($attendence_remarks);
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $attendance = Attendence::where([['attendence_date', '=', Input::get('attendence_date')], ['emp_id', '=', $row1]]);

            if ($attendance->count() > 0):
                DB::table('attendence')->where([['attendence_date', '=', Input::get('attendence_date')], ['emp_id', '=', $row1]])->update($data1);
            else:
                DB::table('attendence')->insert($data1);
            endif;
        }

        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeAttendanceList?m=' . Input::get('m') . '#vis');
    }

    function addPfProfitDetail(Request $request){
        $m=Input::get('m');
        $emp_id=$request->input('emp_id');
        $pf_profit_amount=$request->input('pf_profit_amount');
        $pf_year_from=$request->input('year_from');
        $pf_year_to=$request->input('year_to');

        CommonHelper::companyDatabaseConnection($m);
        $pf_profit['year_from'] = $pf_year_from;
        $pf_profit['year_to'] = $pf_year_to;
        $pf_profit['emp_id'] = $emp_id;
        $pf_profit['profit'] = $pf_profit_amount;
        $pf_profit['status'] = 1;

        DB::table('pf_profit')->insert($pf_profit);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewPfProfitList?m=' . Input::get('m') . '#vis');



    }

    function addPfWithrawalDetail(Request $request){

        $m=Input::get('m');
        $employee_id=$request->input('employee_id');
        $pf_withdrawal_amount=$request->input('pf_withdrawal_amount');
        $pf_amount=0;
        CommonHelper::companyDatabaseConnection($m);


        $provident_fund=DB::select(DB::raw("SELECT sum(amount) as amount FROM pf where status=1 and employee_id='".$employee_id."'"));
        $pf_withdrawal=DB::select(DB::raw("SELECT sum(amount) as amount FROM pf_withdrawal where status=1 and employee_id='".$employee_id."'"));
        $pf_amount=$provident_fund[0]->amount-$pf_withdrawal[0]->amount;

        if($pf_amount != '' || $pf_amount != 0){
            if($pf_amount >= $pf_withdrawal_amount){
                $month_data = (explode("-", $request->input('month_year')));
                $pf['year'] = $month_data[0];
                $pf['month'] = $month_data[1];
                $pf['month_year'] =$request->input('month_year');
                $pf['employee_id'] = $employee_id;
                $pf['amount'] = $pf_withdrawal_amount;
                $pf['status'] = 1;

                $last_id  = DB::table('pf_withdrawal')->insertGetId($pf);

                CommonHelper::LogGenerator('pf_withdrawal',$last_id,'insert','row is inserted','hr',Auth::user()->id);
                
            }else{
                Session::flash('dataDelete', 'Provident Fund is less than Withdrawal amount !');
                return Redirect::to('hr/createPfWithdrawalForm?m=' . Input::get('m') . '#vis');
            }
        }else{
            Session::flash('dataDelete', 'Provident Fund is 0 ');
            return Redirect::to('hr/createPfWithdrawalForm?m=' . Input::get('m') . '#vis');
        }

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewPfWithdrawalList?m=' . Input::get('m') . '#vis');
    }

    function addPfDetail(Request $request)
    {
        $m=Input::get('m');
        CommonHelper::companyDatabaseConnection($m);

        foreach ($request->input('employeeId') as $key => $val){


            if($request->input('amount')[$key] != ''){


                $data1['emp_id'] = $request->input('emp_id')[$key];
                $data1['employee_id'] = $val;
                $data1['amount'] = ($request->input('amount')[$key] == '' ? null : $request->input('amount')[$key]);
                $data1['status'] = 1;
                $data1['month'] = '00';
                $data1['year'] = '00';
                $data1['type'] = $request->input('type')[$key];
                DB::table('pf')->where([['employee_id', '=', $val], ['year', '=', null], ['month', '=', null]])->delete();
                
                CommonHelper::LogGenerator('pf',$val,'delete','row is deleted','hr',Auth::user()->id);
                
                $last_id = DB::table('pf')->insertGetId($data1);

                CommonHelper::LogGenerator('pf',$last_id,'insert','row is inserted','hr',Auth::user()->id);

            }

            if($request->input('type')[$key] == 'loan'):
                $month_year = explode('-', date("Y-m-d"));
                $loanRequest['employee_id'] =  $val;
                $loanRequest['loan_type_id'] =  12;
                $loanRequest['pf_loan_paid'] = $request->input('total_loan_paid')[$key];
                $loanRequest['loan_amount'] = $request->input('total_pf_loan')[$key];

                if($request->input('needed_on_date')[$key] != ''){
                    $month_data = (explode("-", $request->input('needed_on_date')[$key]));
                    $loanRequest['year'] = $month_data[0];
                    $loanRequest['month'] = $month_data[1];
                }
                $loanRequest['per_month_deduction'] = $request->input('per_month_deduction')[$key];
                $loanRequest['username'] = Auth::user()->name;
                $loanRequest['pf_id'] = $last_id;
                $loanRequest['status'] = 1;
                $loanRequest['date'] = date("Y-m-d");
                $loanRequest['time'] = date("H:i:s");
                LoanRequest::where([['employee_id', '=', $val],['loan_type_id','=',12], ['year', '=', $month_data[0]], ['month', '=', $month_data[1]]])->delete();

                CommonHelper::LogGenerator('LoanRequest',$val,'delete','row is deleted','hr',Auth::user()->id);

                $loanrequestlastId = DB::table('loan_request')->insertGetId($loanRequest);

                CommonHelper::LogGenerator('loan_request',$loanrequestlastId,'insert','row is inserted','hr',Auth::user()->id);

            endif;

            if($request->input('type')[$key] == 'permanent'):
                $pf_withdrawal['employee_id'] = $val;
                $pf_withdrawal['amount'] = $request->input('total_pf_loan')[$key];
                $pf_withdrawal['status'] = 1;

                DB::table('pf_withdrawal')->where([['employee_id', '=', $val], ['year', '=', null], ['month', '=', null]])->delete();
                
                CommonHelper::LogGenerator('pf_withdrawal',$val,'delete','row is deleted','hr',Auth::user()->id);

                $pf_withdrawallastId = DB::table('pf_withdrawal')->insertGetId($pf_withdrawal);
                
                CommonHelper::LogGenerator('pf_withdrawal',$pf_withdrawallastId,'insert','row is inserted','hr',Auth::user()->id);

            endif;

            if($request->input('profit')[$key] != ''):

                $pf_profit['employee_id'] = $val;
                $pf_profit['year_from'] = $request->input('profit_year_from')[$key];
                $pf_profit['year_to'] = $request->input('profit_year_till')[$key];
                $pf_profit['profit'] = $request->input('profit')[$key];
                $pf_profit['pf_id'] = $last_id;
                $pf_profit['status'] = 1;

                DB::table('pf_profit')->where([['employee_id', '=', $val], ['year_from', '=', $request->input('profit_year_from')[$key]], ['year_to', '=', $request->input('profit_year_till')[$key]]])->delete();
                
                CommonHelper::LogGenerator('pf_profit',$val,'delete','row is deleted','hr',Auth::user()->id);

                $pf_profitlastId = DB::table('pf_profit')->insertGetId($pf_profit);

                CommonHelper::LogGenerator('pf_profit',$pf_profitlastId,'insert','row is inserted','hr',Auth::user()->id);

            endif;


        }
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewPfForEmployee?m=' . Input::get('m') . '#vis');
    }

    public function addPayrollDetail(Request $request)
    {
        $m = Input::get('m');
        $explodeMonthYear = explode("-",Input::get('payslip_month'));
        
        $total_month_days = cal_days_in_month(CAL_GREGORIAN, $explodeMonthYear[1], $explodeMonthYear[0]);
        $payMonth = $explodeMonthYear[1];
        $payYear = $explodeMonthYear[0];
        $month_year = $explodeMonthYear[0].'-'.$explodeMonthYear[1].'-'.$total_month_days;

        CommonHelper::companyDatabaseConnection($m);
        foreach(Input::get('emp_id') as $key => $row1):

            $employee_id = Input::get('employeeId')[$key];

            if(Input::get('provident_fund_check')[$key]):

                $pf['emp_id'] = $row1;
                $pf['employee_id'] = $employee_id;
                $pf['month'] = $explodeMonthYear[1];
                $pf['year'] = $explodeMonthYear[0];
                $pf['month_year'] = $explodeMonthYear[0].'-'.$explodeMonthYear[1].'-'.$total_month_days;
                $pf['pf_employee_fund'] = Input::get('pf_employee_fund')[$key];
                $pf['pf_company_fund'] = Input::get('pf_employer_fund')[$key];
                $pf['pf_arrears_deffered_fund'] = Input::get('pf_arrears_deferred')[$key];
                $pf['amount'] = Input::get('pf_amount')[$key];
                $pf['status'] = 1;

                if(DB::table('pf')->where([['employee_id', '=', $employee_id], ['year', '=', $explodeMonthYear[0]], ['month', '=', $explodeMonthYear[1]]])->exists()):

                    DB::table('pf')->where([['employee_id', '=', $employee_id], ['year', '=', $explodeMonthYear[0]], ['month', '=', $explodeMonthYear[1]]])->delete();
                    CommonHelper::LogGenerator('pf',$employee_id,'delete','row is deleted','hr',Auth::user()->id);

                endif;

                $pfLastId = DB::table('pf')->insertGetId($pf);
                CommonHelper::LogGenerator('pf',$pfLastId,'insert','row is inserted','hr',Auth::user()->id);
            endif;

            $hold_salary_array = Input::get('hold_salary') ? Input::get('hold_salary') : [];

            if(array_key_exists($employee_id, $hold_salary_array)):
                $hold_salary = 1;
            else:
                $hold_salary = 0;
            endif;

            $loan_amount = Input::get('loan_amount')[$key];
            $account_no = Input::get('account_no')[$key];
            $account_title = Input::get('account_title')[$key];
            $loan_id = Input::get('loan_id')[$key];

            $payment_mode = Input::get('payment_mode')[$key];

            $data['emp_id'] = $row1;
            $data['employee_id'] = $employee_id;
            $data['department_id'] = Input::get('department_id')[$key];
            $data['sub_department_id'] = Input::get('sub_department_id')[$key];
            $data['location_id'] = Input::get('location_id')[$key];
            $data['designation_id'] = Input::get('designation_id')[$key];
            $data['category_id'] = Input::get('category_id')[$key];
            $data['project_id'] = Input::get('project_id')[$key];
            $data['grade_id'] = Input::get('grade_id')[$key];
            $data['month'] = $payMonth;
            $data['year'] = $payYear;
            $data['month_year'] = $month_year;
            $data['present'] = Input::get('present')[$key];
            $data['standard_gross'] = Input::get('standard_gross')[$key];
            $data['gross_salaries'] = Input::get('gross_salary')[$key];
            $data['basic_salary'] = Input::get('basic_salary')[$key];
            $data['hr_utility_allowance'] = Input::get('hr_utility_allowance')[$key];
            $data['gez_overtime'] = round(Input::get('gez_overtime')[$key]);
            $data['attendance_allowance'] = round(Input::get('attendance_allowance')[$key]);
            $data['fix_medical'] = Input::get('fix_medical')[$key];
            $data['fuel_allowance'] = Input::get('otherfuelallowance')[$key];
            $data['food_allowance'] = Input::get('food')[$key];
            $data['leave_encash_allowance'] = Input::get('leaveencash')[$key];
            $data['holiday_allowance'] = Input::get('holiday')[$key];
            $data['target_allowance'] = Input::get('distance+targetincentive')[$key];
            $data['station_visit_allowance'] = Input::get('stationvisitallowance')[$key];
            $data['out_station_visit_allowance'] = Input::get('outstationvisitallowance')[$key];
            $data['installment_allowance'] = Input::get('installment/depreciationallow')[$key];
            $data['reimbursement_allowance'] = Input::get('reimbursement')[$key];
            $data['expense_allowance'] = Input::get('expense')[$key];
            $data['commission_allowance'] = Input::get('commission')[$key];
            $data['car_allowance'] = Input::get('carallowance')[$key];

            $data['allowance_tf'] = Input::get('allowance_tf')[$key];
            $data['allowance_mics'] = Input::get('micsallowance')[$key];
            $data['allowance_incentives'] = Input::get('incentives')[$key];
           
            $data['mobile'] = Input::get('mobileallowance')[$key];
            $data['maintenance'] = Input::get('maintenance')[$key];
            $data['school_fees'] = Input::get('schoolfee')[$key];
            $data['overtime'] = Input::get('overtime')[$key];
            $data['other_allowance'] = Input::get('other')[$key];
            $data['deferred_salary'] = Input::get('arrearsofdeferredsalaryorincrement')[$key];
            $data['arrears_amount'] = Input::get('arrears')[$key];
            $data['bonus_amount'] = Input::get('bonus_amount')[$key];
            $data['total_allowance'] = Input::get('overall_allowances')[$key];
            $data['gross_with_allowances'] = Input::get('gross_with_allowance')[$key];
            $data['tax_amount'] = Input::get('income_tax')[$key];
            $data['pf_employee_fund'] = Input::get('pf_employee_fund')[$key];
            $data['pf_amount'] = Input::get('pf_amount')[$key];;
            $data['pf_company_fund'] = Input::get('pf_employer_fund')[$key];
            $data['pf_arrears_deffered_fund'] = Input::get('pf_arrears_deferred')[$key];
            $data['advance_salary_amount'] = Input::get('advance_salary_amount')[$key];
            $data['deduction_days'] = Input::get('deduction_days')[$key];
            $data['lwp_deduction'] = Input::get('leave_deduction_amount')[$key];
            $data['lateness'] = Input::get('late_deduction')[$key];
            $data['penalty'] = Input::get('penalty')[$key];
            $data['tf_deduction'] = Input::get('tf_deduction')[$key];
            $data['eobi_amount'] = Input::get('eobi_deduct')[$key];
            $data['other_deduct'] = Input::get('other_deduction')[$key];
            $data['total_deduction'] = Input::get('total_deduction')[$key];
            $data['net_salary'] = Input::get('net_salary')[$key];
            $data['account_no'] = $account_no;
            $data['account_title'] = $account_title;
            $data['payment_mode'] = $payment_mode;
            $data['hold_salary'] = $hold_salary;
            $data['loan_amount_paid'] = strip_tags($loan_amount);
            $data['loan_id'] = strip_tags($loan_id);
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");

            if(Payslip::where([['employee_id', '=', $employee_id], ['year', '=', $payYear], ['month', '=', $payMonth]])->exists()):
                Payslip::where([['employee_id', '=', $employee_id], ['year', '=', $payYear], ['month', '=', $payMonth]])->update(['status' => 2]);
                CommonHelper::LogGenerator('Payslip',$employee_id,'delete','row is deleted','hr',Auth::user()->id);
            endif;
            $PayslipLastId = Payslip::insertGetId($data);
            CommonHelper::LogGenerator('Payslip',$PayslipLastId,'insert','row is inserted','hr',Auth::user()->id);

            $data1['emp_id'] = $row1;
            $data1['employee_id'] = $employee_id;
            $data1['month'] = $payMonth;
            $data1['year'] = $payYear;
            $data1['month_year'] = $month_year;
            $data1['taxable_income'] = Input::get('annual_salary')[$key];
            $data1['salary_paid'] = Input::get('salary_paid')[$key];
            $data1['annual_salary'] = Input::get('gross_paid')[$key];
            $data1['balance_taxable_income'] = Input::get('tax_balance')[$key];
            $data1['rebate_amount'] = Input::get('rebate_amount')[$key];
            $data1['balance_tax_amount'] = Input::get('tax_balance')[$key];
            $data1['tax_percent'] = Input::get('tax_percent')[$key];
            $data1['amount'] = Input::get('income_tax')[$key];
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            if(IncomeTax::where([['employee_id', '=', $employee_id], ['year', '=', $payYear], ['month', '=', $payMonth]])->exists()):
                IncomeTax::where([['employee_id', '=', $employee_id], ['year', '=', $payYear], ['month', '=', $payMonth]])->delete();
                CommonHelper::LogGenerator('IncomeTax',$employee_id,'delete','row is deleted','hr',Auth::user()->id);
            endif;
            $IncomeTaxLastId = IncomeTax::insertGetId($data1);
            CommonHelper::LogGenerator('IncomeTax',$IncomeTaxLastId,'insert','row is inserted','hr',Auth::user()->id);
        endforeach;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/createPayrollForm?m='.$m);
    }

    public function addEmailPayslipDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('month_year'));
        if (Input::get('check_list')):
            foreach (Input::get('check_list') as $key => $value):
                $empId_and_Email = (explode("<>", $value));
                $data1['employee_id'] = $empId_and_Email[1];
                $data1['month'] = $month_year[1];
                $data1['year'] = $month_year[0];
                $data1['type'] = 'salary';
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");
                DB::table('email_queue')->insert($data1);

            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved.');
        return Redirect::to('hr/emailPayslips?m=' . Input::get('m') . '#vis');
    }
    public function addBonusEmailPayslipDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('month_year'));
        if (Input::get('check_list')):
            foreach (Input::get('check_list') as $key => $value):
                $empId_and_Email = (explode("<>", $value));
                $data1['employee_id'] = $empId_and_Email[1];
                $data1['month'] = $month_year[1];
                $data1['year'] = $month_year[0];
                $data1['type'] = 'bonus';
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");
                DB::table('email_queue')->insert($data1);

            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved.');
        return Redirect::to('hr/bonusPayslipsEmail?m=' . Input::get('m') . '#vis');
    }

    // public function addEmployeeAllowanceDetail()
    // {
    //     CommonHelper::companyDatabaseConnection(Input::get('company_id'));
    //     foreach (Input::get('allowance_type') as $key => $val):

    //         $data1['emp_id'] = strip_tags(Input::get('emp_id'));
    //         $data1['allowance_type_id'] = strip_tags($val);
    //         $data1['allowance_amount'] = strip_tags(Input::get('allowance_amount')[$key]);
    //         $data1['username'] = Auth::user()->name;
    //         $data1['status'] = 1;
    //         $data1['date'] = date("Y-m-d");
    //         $data1['time'] = date("H:i:s");
    //         DB::table('allowance')->insert($data1);
    //     endforeach;
    //     CommonHelper::reconnectMasterDatabase();
    //     Session::flash('dataInsert', 'successfully saved.');
    //     return Redirect::to('hr/viewAllowanceList?m=' . Input::get('company_id') . '#vis');
    // }

    public function addEmployeeAllowanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('employee_id') as $key => $val):
            if(Input::get('check_input')[$key] == 1):
                $data['employee_id'] = $val;
                $data['allowance_type_id'] = Input::get('allowance_type_id')[$key];
                $data['allowance_amount'] = Input::get('allowance_amount')[$key];
                $data['remarks'] = Input::get('remarks');
                if(Input::get('month_year') != ''):
                    $month_year = explode('-', Input::get('month_year'));
                    $data['once'] = Input::get('once');
                    $data['month'] = $month_year[1];
                    $data['year'] = $month_year[0];
                endif;
                $data['username'] = Auth::user()->name;
                $data['status'] = 1;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");
                $AllowanceLastId = Allowance::insertGetId($data);
                CommonHelper::LogGenerator('Allowance',$AllowanceLastId,'insert','row is inserted' ,'hr',Auth::user()->id);
            endif;
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewAllowanceList?m='.Input::get('company_id'));
    }


   public function addEmployeeDeductionDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('employee_id') as $key => $val):
            if(Input::get('check_input')[$key] == 1):
                $data['employee_id'] = $val;
                $data['deduction_type'] = Input::get('deduction_type_id')[$key];
                $data['remarks'] = Input::get('remarks');
                $data['deduction_amount'] = Input::get('deduction_amount')[$key];
                if(Input::get('month_year') != ''):
                    $month_year = explode('-', Input::get('month_year'));
                    $data['once'] = Input::get('once');
                    $data['month'] = $month_year[1];
                    $data['year'] = $month_year[0];
                endif;
                $data['username'] = Auth::user()->name;
                $data['status'] = 1;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");
                $DeductionLastId = Deduction::insertGetId($data);
             
                CommonHelper::LogGenerator('Deduction',$DeductionLastId,'insert','row is inserted','hr',Auth::user()->id);
            endif;
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewDeductionList?m='.Input::get('company_id'));
    }
    
    public function addAdvanceSalaryDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $implode_date = explode("-", Input::get('deduction_month_year'));

        $data1['employee_id'] = Input::get('employee_id');
        $data1['advance_salary_amount'] = Input::get('advance_salary_amount');
        $data1['salary_needed_on'] = Input::get('salary_needed_on');
        $data1['deduction_year'] = $implode_date[0];
        $data1['deduction_month'] = $implode_date[1];
        $data1['detail'] = Input::get('advance_salary_detail');
        $data1['username'] = Auth::user()->name;
        $data1['approval_status'] = 1;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");


        $advanceSalaryLastId = DB::table('advance_salary')->insertGetId($data1);
        
        CommonHelper::LogGenerator('advance_salary',$advanceSalaryLastId,'insert','row is inserted' ,'hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAdvanceSalaryList?m=' . Input::get('company_id') . '#vis');

    }


    public function addWorkingHoursPolicyDetail()
    {
        $days_off_1 = Input::get('days_off_1');
        $days = '';
        foreach ($days_off_1 as $value) {
            $days .= $value . '=>';
        }
        $data['days_off'] = $days;
        $data['ot_cpl'] = strip_tags(Input::get('ot_cpl'));

        $data['working_hours_policy'] = strip_tags(Input::get('working_hours_policy'));
        $data['start_working_hours_time'] = strip_tags(Input::get('start_working_hours_time'));
        $data['end_working_hours_time'] = strip_tags(Input::get('end_working_hours_time'));
        $data['working_hours_grace_time'] = strip_tags(Input::get('working_hours_grace_time'));
        $data['half_day_time'] = strip_tags(Input::get('half_day_time'));
        $data['deduction_amount_late_day'] = strip_tags(Input::get('late_deduction'));
        $data['early_going_grace_time'] = strip_tags(Input::get('early_going_grace_time'));
        $data['overtime_after_minutes'] = strip_tags(Input::get('overtime_after_minutes'));
        $data['username'] = Auth::user()->name;
        $data['status'] = 1;
        $data['company_id'] = Input::get('m');
        $data['time'] = date("H:i:s");
        $data['date'] = date("Y-m-d");

        $workingHoursPolicyLastId = DB::table('working_hours_policy')->insertGetId($data);

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        CommonHelper::LogGenerator('working_hours_policy',$workingHoursPolicyLastId,'insert','row is inserted','hr',Auth::user()->id);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewWorkingHoursPolicyList?m='.Input::get('m'));
    }

    public function addManuallyLeaves()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $leave_policy = Employee::where([['emp_id', '=', Input::get('emr_no')], ['leaves_policy_id', '!=', 0]]);
        if ($leave_policy->count() == 0) {
            Session::flash('dataDelete', 'Please Select Leaves Policy For User !');
            return Redirect::to('hr/createManualLeaves?m=' . Input::get('m') . '');
        }

        $emp_leave_policy = $leave_policy->first();
        CommonHelper::reconnectMasterDatabase();
        $policy_date_from = LeavesPolicy::where([['id', '=', $emp_leave_policy->leaves_policy_id]])->value('policy_date_from');

        $casual_leaves = LeavesData::where([['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id], ['leave_type_id', '=', '3']])->value('no_of_leaves');
        $annual_leaves = LeavesData::where([['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id], ['leave_type_id', '=', '1']])->value('no_of_leaves');
        $sick_leaves = LeavesData::where([['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id], ['leave_type_id', '=', '2']])->value('no_of_leaves');

        $leaves [1] = $annual_leaves - Input::get('annual_leaves');
        $leaves [3] = $casual_leaves - Input::get('casual_leaves');
        $leaves [2] = $sick_leaves - Input::get('sick_leaves');

        TransferedLeaves::where([['emr_no', '=', Input::get('emr_no')], ['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id]])->delete();
        LeaveApplication::where([['view', '=', 'no'], ['emr_no', '=', Input::get('emr_no')], ['leave_policy_id', '=', $emp_leave_policy->leaves_policy_id]])->delete();
        LeaveApplicationData::where([['view', '=', 'no'], ['emr_no', '=', Input::get('emr_no')], ['leave_policy_id', '=', $emp_leave_policy->leaves_policy_id]])->delete();

        foreach ($leaves as $key => $value) {
            if ($key == 1) {

                $leaveApplicationData['emp_id'] = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type'] = $key;
                $leaveApplicationData['leave_day_type'] = 1;
                $leaveApplicationData['reason'] = "-";
                $leaveApplicationData['leave_address'] = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['approved'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status'] = 1;
                $leaveApplicationData['username'] = Auth::user()->name;
                $leaveApplicationData['date'] = date("Y-m-d");
                $leaveApplicationData['time'] = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

                $annualLeavesData['emp_id'] = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type'] = $key;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['leave_day_type'] = 1;
                $annualLeavesData['no_of_days'] = $value;
                $annualLeavesData['from_date'] = date("Y-m-d");
                $annualLeavesData['to_date'] = date("Y-m-d");
                $annualLeavesData['status'] = 1;
                $annualLeavesData['username'] = Auth::user()->name;
                $annualLeavesData['date'] = date("Y-m-d");
                $annualLeavesData['time'] = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);

            } elseif ($key == 2) {
                $leaveApplicationData['emp_id'] = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type'] = $key;
                $leaveApplicationData['leave_day_type'] = 1;
                $leaveApplicationData['reason'] = "-";
                $leaveApplicationData['leave_address'] = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['approved'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status'] = 1;
                $leaveApplicationData['username'] = Auth::user()->name;
                $leaveApplicationData['date'] = date("Y-m-d");
                $leaveApplicationData['time'] = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

                $annualLeavesData['emp_id'] = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type'] = $key;
                $annualLeavesData['leave_day_type'] = 1;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['no_of_days'] = $value;
                $annualLeavesData['from_date'] = date("Y-m-d");
                $annualLeavesData['to_date'] = date("Y-m-d");
                $annualLeavesData['status'] = 1;
                $annualLeavesData['username'] = Auth::user()->name;
                $annualLeavesData['date'] = date("Y-m-d");
                $annualLeavesData['time'] = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);
            } elseif ($key == 3) {
                $leaveApplicationData['emp_id'] = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type'] = $key;
                $leaveApplicationData['leave_day_type'] = 1;
                $leaveApplicationData['reason'] = "-";
                $leaveApplicationData['leave_address'] = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['approved'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status'] = 1;
                $leaveApplicationData['username'] = Auth::user()->name;
                $leaveApplicationData['date'] = date("Y-m-d");
                $leaveApplicationData['time'] = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);
                $annualLeavesData['emp_id'] = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type'] = $key;
                $annualLeavesData['leave_day_type'] = 1;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['no_of_days'] = $value;
                $annualLeavesData['from_date'] = date("Y-m-d");
                $annualLeavesData['to_date'] = date("Y-m-d");
                $annualLeavesData['status'] = 1;
                $annualLeavesData['username'] = Auth::user()->name;
                $annualLeavesData['date'] = date("Y-m-d");
                $annualLeavesData['time'] = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);
            }


        }

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManualLeaves?m=' . Input::get('m') . '');


    }


    public function addVehicleTypeDetail()
    {

        foreach (Input::get('vehicle_type') as $key => $val):

            $data1['vehicle_type_name'] = strip_tags($val);
            $data1['vehicle_type_cc'] = strip_tags(Input::get('vehicle_cc')[$key]);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('vehicle_type')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewVehicleTypeList?m=' . Input::get('company_id') . '#vis');


    }

    public function addCarPolicyDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('designation_id') as $key => $val):

            $data1['designation_id'] = $val;
            $data1['vehicle_type_id'] = Input::get('vehicle_type_id')[$key];
            $data1['policy_name'] = Input::get('policy_name')[$key];
            $data1['start_salary_range'] = Input::get('start_salary_range')[$key];
            $data1['end_salary_range'] = Input::get('end_salary_range')[$key];
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('car_policy')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewCarPolicyList?m=' . Input::get('company_id') . '#vis');

    }

    public function addLoanRequestDetail()
    {
        $employees = Cache::get('employee');
        $loan_types = Cache::get('loan_type');

        $month_data = explode("-", Input::get('needed_on_date'));
        $deduction_start_date = explode("-", Input::get('deduction_start_date'));

        $employee_id = Input::get('employee_id');
        $loan_type_id = Input::get('loan_type_id');
        $loan_amount = Input::get('loan_amount');

        $data['employee_id'] = $employee_id;
        $data['loan_type_id'] = $loan_type_id;
        $data['year'] = $month_data[0];
        $data['month'] = $month_data[1];
        $data['needed_on_date'] = Input::get('needed_on_date');
        $data['loan_amount'] = $loan_amount;
        $data['out_standing_amount'] = Input::get('out_standing_amount');
        $data['per_month_deduction'] = Input::get('per_month_deduction');
        $data['deduction_start_date'] = $deduction_start_date[0].'-'.$deduction_start_date[1].'-01';
        $data['deduction_start_month'] = $deduction_start_date[1];
        $data['deduction_start_year'] = $deduction_start_date[0];
        $data['description'] = Input::get('loan_description');
        $data['status'] = 1;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        
        $LoanRequestLastId = LoanRequest::insertGetId($data);
        
        CommonHelper::LogGenerator('Loan_Request',$LoanRequestLastId,'insert','row is inserted','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();

        $email = "aliasghar@innovative-net.com";
        $subject = "Loan Application";
        $message = "Emp ID: ".$employees[$employee_id]->emp_id."<br/>";
        $message .= "Name: ".$employees[$employee_id]->emp_name."<br/>";
        $message .= "Date: ".HrHelper::date_format(date('Y-m-d'))."<br/><br/>";
        $message .= 'Please approve my '.$loan_types[$loan_type_id]->loan_type_name.' Request Amount of '.$loan_amount;

        EmailHelper::HrEmail($email, $subject, $message);

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLoanRequestList?m='.Input::get('company_id'));
    }
    
    public function updateAttendanceRequestQueriesDetail(){
        
        CommonHelper::companyDatabaseConnection('12');
        $id = Input::get('id');
        $emp_id =  Input::get('emp_id');
        $employee_id =  Input::get('employee_id');
        $query =  Input::get('query');
        $clock_out =  Input::get('clock_out');
        $clock_in =  Input::get('clock_in');
        $attendance_date =  Input::get('attendance_date');
        $data1['attendance_date'] = $attendance_date;
        $clock_out=date("H:i", strtotime($clock_out));
        $data1['clock_in'] = $clock_in;
        $data1['clock_out'] = $clock_out;
        $data1['query'] = $query;
        
        DB::table('attendance_queries')->where('id',$id)->where('approval_status',1)->where('employee_id',$employee_id)->update($data1);
        CommonHelper::reconnectMasterDatabase();
        
        return "success";
    }

    public function addAttendanceRequestQueriesDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if(Input::get('employee_id') == ''){
            Session::flash('dataDelete', 'Login with an employee account first.');
            return Redirect::to('hr/createAttendanceRequestQueriesForm?m=' . Input::get('m') . '#vis');
        }
        $data1['emp_id'] = Input::get('emp_id');
        $data1['employee_id'] = Input::get('employee_id');
        $data1['query'] = Input::get('query');
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        DB::table('attendance_queries')->insert($data1);


        //$emp_name = DB::table('employee')->select('emp_name')->where([['id', '=', Input::get('employee_id')]])->value('emp_name');

        CommonHelper::reconnectMasterDatabase();

        // $time=date('h:i:s a', strtotime(date("H:i:s")));
        // $messages =  'Query day '.HrHelper::date_format(date("Y-m-d")).' & time '.$time;
        // $desc=Input::get('query');

        // $professional_email='hrdept@unisons.com.pk';
        // //$professional_email='khizer@innovative-net.com';    
        // $details = [
        //     'subject'=>'Attendance Request',
        //     'title' =>$emp_name,
        //     'body' => $messages,
        //     'link' => 'http://unisons.smrhr.com/hr/createAttendanceRequestQueriesForm?m=12',
        //     'desc' => $desc
        // ];

        // $mail = new PHPMailer(true);
        // try {
        //     //Server settings
        //     //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        //     $mail->isSMTP();                                            //Send using SMTP
        //     $mail->Host       = "smrhr.com";                    //Set the SMTP server to send through
        //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        //     $mail->Username = "unisons@smrhr.com";
        //     $mail->Password = '}uE9A]1eD_Qc';                       //SMTP password
        //     $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //     //   $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //     //Recipients
        //     $mail->setFrom("unisons@smrhr.com", 'Attendance Request');
        //     $mail->addAddress($professional_email, 'Attendance Request');     //Add a recipient
        //     //Name is optional


        //     //$mail->addAddress($cnic->value('professional_email'), 'Payslip');
        //     $mail->addReplyTo("unisons@smrhr.com", 'Information');

        //     //Content
        //     $mail->isHTML(true);                                  //Set email format to HTML
        //     $mail->Subject = 'Attendance Request';
        //     // $mail->Body    = $messages;
        //     $mail->Body = view('Hr.mail',compact('details'))->render();
        //     // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        //     // echo("saad")

        //     $mail->send();
        //     //echo 'Message has been sent';
        // } catch (Exception $e) {
        //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        // }




        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createAttendanceRequestQueriesForm?m=' . Input::get('m') . '#vis');
    }

//    public function addTaxesDetail()
//    {
//
//        foreach (Input::get('tax_name') as $key => $val):
//
//            $data1['tax_name'] = $val;
//            $data1['salary_range_from'] = Input::get('salary_range_from')[$key];
//            $data1['salary_range_to'] = Input::get('salary_range_to')[$key];
//            $data1['tax_mode'] = Input::get('tax_mode')[$key];
//            $data1['tax_percent'] = Input::get('tax_percent')[$key];
//            $data1['tax_month_year'] = Input::get('tax_month_year')[$key];
//            $data1['status'] = 1;
//            $data1['company_id'] = Input::get('company_id');
//            $data1['username'] = Auth::user()->name;;
//            $data1['date'] = date("Y-m-d");
//            $data1['time'] = date("H:i:s");
//
//            DB::table('tax')->insert($data1);
//
//        endforeach;
//        Session::flash('dataInsert', 'successfully saved.');
//        return Redirect::to('hr/viewTaxesList?m=' . Input::get('company_id') . '#vis');
//
//    }

    public function addTaxesDetail()
    {
        $data['tax_name'] = Input::get('tax_name');
        $data['tax_month_year'] = Input::get('tax_month_year');
        $data['status'] = 1;
        $data['username'] = Auth::user()->name;;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        $data['company_id'] = Input::get('company_id');
        $last_id = DB::table('tax')->insertGetId($data);

        foreach (Input::get('salary_range_from') as $key => $val):

            $data1['slab_name'] = Input::get('tax_name');
            $data1['tax_id'] = $last_id;
            $data1['salary_range_from'] = $val;
            $data1['salary_range_to'] = Input::get('salary_range_to')[$key];
            $data1['tax_mode'] = Input::get('tax_mode')[$key];
            $data1['tax_percent'] = Input::get('tax_percent')[$key];
            $data1['tax_amount'] = Input::get('tax_amount')[$key];
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('tax_slabs')->insert($data1);

        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewTaxesList?pageType=viewlist&&parentCode=53&&m=' . Input::get('company_id') . '#vis');
    }

    public function addBonusDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('Bonus_name') as $key => $val):
            $data1['bonus_name'] = $val;
            $data1['percent_of_salary'] = Input::get('percent_of_salary')[$key];
            $data1['type'] = Input::get('type')[$key] == 1 ? 'Percent' : 'Amount';
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('bonus')->insert($data1);

        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewBonusList?m='.Input::get('company_id').'#vis');

    }

    public function addIncomeTaxDetail(Request $request){


        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_id=$request->emp_id;
        $amount=$request->amount;

        DB::table('income_tax')->where('emp_id',$emp_id)->delete();
        $data1['emp_id']=$emp_id;
        $data1['amount']=$amount;
        $data1['status']=1;
        DB::table('income_tax')->insert($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewIncomeTaxList?m=' . Input::get('m') . '');

    }

    function addEmployeeBonusDetail(Request $request)
    {
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('bonus_month_year'));


        if (Input::get('check_list')):

            foreach (Input::get('check_list') as $key => $value):
//                $emp_and_bonus = (explode("_", $value));
                  
                $annual_salary=0;
                $annual_with_bonus=0;
                $bonus_tax=0;
                $total_net=0;
                $tax_payable=0;
                $tax_payable_with_bonus=0;

                $date = Carbon::createFromFormat('m/Y',$month_year[1].'/'.$month_year[0])->subMonth();
                $newDate = $date->format('m/Y');
                $previous_date = explode('/',$newDate);

                $income_tax = DB::table('income_tax')->where([["month","=",$previous_date[0]],["year","=",$previous_date[1]],
                    ["employee_id","=",Input::get('employee_id')[$key]]])->first();
                if(!empty($income_tax)){
                    $annual_salary=$income_tax->annual_salary;
                    $annual_with_bonus=$income_tax->annual_salary+$value;
                }
                else{
                   $annual_salary=0;
                }
                  
                if($annual_salary != 0){
                    CommonHelper::reconnectMasterDatabase();
                    $tax_id = Tax::where([['status','=', 1]])->select('id')->value('id');
                    $tax = TaxSlabs::where([['status','=','1'],['tax_id', '=', $tax_id]])->get();
                    foreach($tax as $value1):
                        if($annual_salary > $value1->salary_range_from && $annual_salary <= $value1->salary_range_to):
                            $tax_percent = $value1->tax_percent;
                            $tax_amount = $value1->tax_amount;
                            $balance_taxable_income = $annual_salary - $value1->salary_range_from;
                            $tax_payable = (($balance_taxable_income / 100) * $tax_percent) + $tax_amount;

                        endif;
                    endforeach;
                    foreach($tax as $value2):
                        if($annual_with_bonus > $value2->salary_range_from && $annual_with_bonus <= $value2->salary_range_to):
                            $tax_percent = $value2->tax_percent;
                            $tax_amount = $value2->tax_amount;
                            $balance_taxable_income = $annual_with_bonus - $value2->salary_range_from;
                            $tax_payable_with_bonus = (($balance_taxable_income / 100) * $tax_percent) + $tax_amount;

                        endif;
                    endforeach;

                    $bonus_tax=$tax_payable_with_bonus-$tax_payable;

                }
                $total_net=$value-$bonus_tax;
                CommonHelper::companyDatabaseConnection(Input::get('m'));

                $data1['emp_id'] = Input::get('emp_id')[$key];
                $data1['employee_id'] = Input::get('employee_id')[$key];
                $data1['bonus_id'] = Input::get('bonus_id');
                $data1['bonus_amount'] = $value;
                $data1['bonus_tax']=$bonus_tax;
                $data1['total_net']=$total_net;
                $data1['bonus_month'] = $month_year[1];
                $data1['bonus_year'] = $month_year[0];
                $data1['bonus_pay'] = Input::get('bonus_pay')[$key];
                $data1['username'] = Auth::user()->name;
                $data1['bonus_status'] = 1;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");
                DB::table('bonus_issue')->where([['employee_id','=',Input::get('employee_id')[$key]],['bonus_month','=',$month_year[1]],
                    ['bonus_year','=',$month_year[0]]])->delete();
                DB::table('bonus_issue')->insert($data1);
            endforeach;

        endif;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/IssueBonusDetailForm?m=' . Input::get('m') . '');
    }

    function addHolidaysDetail()
    {
        $company_id = Input::get('company_id');
        
        CommonHelper::companyDatabaseConnection($company_id);
        $data['holiday_name'] = Input::get('holiday_reason');
        $data['holiday_date'] = Input::get('holiday_date');
        $data['year'] = Input::get('year');
        $dateObj   = DateTime::createFromFormat('!m', Input::get('month'));
        $month = $dateObj->format('m');
        $data['month'] = $month;
        $data['username'] = Auth::user()->name;
        $data['status'] = 1;
        $data['date']    = date("Y-m-d");
        $data['time']    = date("H:i:s");
        $holidaysLastId = DB::table('holidays')->insertGetId($data);

        CommonHelper::LogGenerator('holidays',$holidaysLastId,'insert','row is inserted','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewHolidaysList?m='.$company_id);
    }

    public function addAttendanceProgressDetail(request $request)
    {
        

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        
        foreach (Input::get('employee_id') as $key => $value):
            $month = Input::get('month_' . $value);
            $year = Input::get('year_' . $value);

            DB::table('payroll_data')->where([['employee_id', '=', $value], ['month', '=', Input::get('month_' . $value)], ['year', '=', Input::get('year_' . $value)]])->delete();
            
            $attendanceProgress["employee_id"] = $value;
            $attendanceProgress["emp_id"] = Input::get('emp_id_' . $value);
            $attendanceProgress["attendance_type"] = Input::get('attendance_type_' . $value);
            $attendanceProgress["attendance_from"] = Input::get('attendance_from_' . $value);
            $attendanceProgress["attendance_to"] = Input::get('attendance_to_' . $value);
            $attendanceProgress["total_days"] = Input::get('total_days_' . $value);
            $attendanceProgress["total_holidays"] = Input::get('total_holidays_' . $value);
            $attendanceProgress["present_days"] = Input::get('present_days_' . $value);
            $attendanceProgress["absent_days"] = Input::get('absent_days_' . $value);
            $attendanceProgress["deduction_days"] = Input::get('deduction_days_' . $value);
            $attendanceProgress["total_leaves_count"] = Input::get('total_leave_' . $value);
            $attendanceProgress["halfdayleave"] = Input::get('half_days_leave_' . $value);
            $attendanceProgress["fulldayleave"] = Input::get('full_days_leave_' . $value);
            $attendanceProgress["overtime"] = Input::get('overtime_' . $value);
            $attendanceProgress["total_late_arrivals"] = Input::get('Late_' . $value);
            $attendanceProgress["total_halfday_count"] = Input::get('half_days_' . $value);
            $attendanceProgress["gez_overtime"] = Input::get('gez_overtime_' . $value);
            $attendanceProgress["month"] = $month;
            $attendanceProgress["year"] = $year;
            $attendanceProgress['username'] = Auth::user()->name;
            $attendanceProgress['status'] = 1;
            $attendanceProgress['date'] = date("Y-m-d");
            $attendanceProgress['time'] = date("H:i:s");
            DB::table('payroll_data')->insert($attendanceProgress);

   
            $cpl = '';
            $cpl = Input::get('cpl_'.$value);

            if($cpl != '' && $cpl != 0):
                $data['employee_id'] = $value;
                $data['cpl'] = $cpl;
                $data['month'] = $month;
                $data['year'] = $year;
                $data['username'] = Auth::user()->name;
                $data['status'] = 1;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                CommonHelper::reconnectMasterDatabase();
                Cpl::where([['employee_id','=', $value],['month','=', $month], ['year','=', $year]])->delete();
                Cpl::insert($data);
                CommonHelper::companyDatabaseConnection(Input::get('m'));
            endif;

        endforeach;


        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/ViewAttendanceProgress?m=' . Input::get('m') . '#vis');

    }

    public function addEmployeeDepositDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $employeeDeposit = new EmployeeDeposit;

        $month_and_year = explode('-', $request->to_be_deduct_on_date);
        $employeeDeposit->sub_department_id = $request->sub_department_id;
        $employeeDeposit->acc_no = $request->employee_id;
        $employeeDeposit->deposit_name = $request->deposit_name;
        $employeeDeposit->deposit_amount = $request->deposit_amount;
        $employeeDeposit->deduction_month = $month_and_year[1];
        $employeeDeposit->deduction_year = $month_and_year[0];
        $employeeDeposit->username = Auth::user()->name;
        $employeeDeposit->status = 1;
        $employeeDeposit->date = date("Y-m-d");
        $employeeDeposit->time = date("H:i:s");

        $employeeDeposit->save();
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeDepositList?m=' . Input::get('company_id') . '#vis');
    }

    public function addManualyAttendance()
    {
        //echo "<pre>";
        //print_r(Input::get('attendance_date')); die;

        $name_array = $_POST['attendance_date'];
        $month='';
        $year='';
        $day='';
        $from_date='';
        $to_date='';
        $emp_id='';

        for ($i = 0; $i < count($name_array); $i++) {
            if($i==0){
                $emp_id=Input::get('acc_no')[$i];
                $from_date=$year."-".$month."-".'01';
            }
            if((count($name_array)-1) == $i){
                $to_date= $year."-".$month."-".'31';
            }

            $manualyAttData['emp_id'] = Input::get('acc_no')[$i];
            $manualyAttData['emp_name'] = Input::get('emp_name')[$i];
            $manualyAttData['day'] = Input::get('day')[$i];
            $manualyAttData['month'] = Input::get('month')[$i];
            $manualyAttData['year'] = Input::get('year')[$i];
            $manualyAttData['manual_attendance'] = 1;
            $manualyAttData['attendance_date'] = Input::get('attendance_date')[$i];
            $manualyAttData['clock_in'] = Input::get('clock_in')[$i];
            $manualyAttData['clock_out'] = Input::get('clock_out')[$i];
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            DB::table('attendance')->where([['emp_id','=',Input::get('acc_no')[$i]],['attendance_date','=',Input::get('attendance_date')[$i]]])->delete();
            DB::table('attendance')->insert($manualyAttData);
            CommonHelper::reconnectMasterDatabase();

        }



        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);
        $totalHolidays = $get_holidays->get()->toArray();

        //leaves start
        CommonHelper::companyDatabaseConnection($m);

        $monthly_holidays = array();

        CommonHelper::companyDatabaseConnection($m);
        $emp=DB::table('employee')->select('working_hours_policy_id')->where([['emp_id','=',$emp_id]])->first();
        $emp_working_hours_policy_id=$emp->working_hours_policy_id;

        $days_array = [];
        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$emp_id]])->value('day_off');
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


        CommonHelper::companyDatabaseConnection($m);

        $total_days_off = Attendance::select('attendance_date')
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->whereIn('day',$days_array)
            ->where('emp_id','=',$emp_id)
            ->get()
            ->toArray();

        $monthly_holidays = array_merge($totalHolidays,$total_days_off);


        $half_days_absent=date('h:i', $half_day_time);
        $end_day_time=date('h:i', $endTime);

        $lates_count=DB::table('attendance')->where([['month','=',$month],['year','=',$year],
            ['clock_in','>',$end_day_time],['clock_in','<',$half_days_absent],['emp_id','=',$emp_id]])
            ->whereNotIn('attendance_date', $monthly_holidays)->count();

        $half_days_count=DB::table('attendance')->where([['month','=',$month],['year','=',$year],
            ['clock_in','>=',$half_days_absent],['emp_id','=',$emp_id]])
            ->whereNotIn('attendance_date', $monthly_holidays)->count();

        $leave_type='';
        $lates=$lates_count;
        $leaves_policy_id=DB::table('employee')->select('leaves_policy_id')->where([['emp_id','=',$emp_id],['status','!=',2]])->value('leaves_policy_id');

        CommonHelper::reconnectMasterDatabase();
        $late_leave = DB::table('leave_application')->where([['emp_id','=',$emp_id],['name','=',$year.'-'.$month.'-01']]);

        if($late_leave->count() > 0){
            DB::table('leave_application_data')->where([['emp_id','=',$emp_id],['leave_application_id','=',$late_leave->value('id')]])->delete();
            DB::table('leave_application')->where([['emp_id','=',$emp_id],['name','=',$year.'-'.$month.'-01']])->delete();
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
                    ->where([['leave_application.emp_id','=',$emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
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
            if($remaining_casual > 0 && $remaining_casual >= (floor($lates/5)+ ($half_days_count/2) )){
                $leave_type=3;
            }elseif($remaining_sick > 0 && $remaining_sick >= (floor($lates/5)+ ($half_days_count/2) )){
                $leave_type=4;
            }elseif($remaining_annual > 0 && $remaining_annual >= (floor($lates/5)+ ($half_days_count/2) )){
                $leave_type=1;
            }

            $leaveApplicationData['emp_id']          = $emp_id;
            $leaveApplicationData['leave_policy_id'] = $leaves_policy_id;
            $leaveApplicationData['company_id']      = Input::get('company_id');
            $leaveApplicationData['leave_type']      = $leave_type;
            $leaveApplicationData['leave_day_type']  = 1;
            $leaveApplicationData['name']  = $year.'-'.$month.'-01';
            $leaveApplicationData['reason']          = '-';
            $leaveApplicationData['leave_address']   = '-';
            $leaveApplicationData['approval_status'] = 2;
            $leaveApplicationData['approved'] = 2;
            $leaveApplicationData['status']          = 1;
            $leaveApplicationData['username']        = Auth::user()->name;
            $leaveApplicationData['date']            = date("Y-m-d");
            $leaveApplicationData['time']            = date("H:i:s");

            $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

            $maternityLeavesData['emp_id']               = $emp_id;;
            $maternityLeavesData['leave_application_id'] = $leave_application_id;
            $maternityLeavesData['leave_policy_id'] = $leaves_policy_id;
            $maternityLeavesData['leave_type']           = $leave_type;
            $maternityLeavesData['leave_day_type']       = 1;
            $maternityLeavesData['no_of_days']           = (floor($lates/5)+ ($half_days_count/2));
            $maternityLeavesData['from_date']            = $year.'-'.$month;
            $maternityLeavesData['to_date']              = $year.'-'.$month;

            //                $startDate = new DateTime(Input::get('from_date'));
            //                $endDate = new DateTime(Input::get('to_date'));
            //                $difference = $endDate->diff($startDate);

            //                $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;

            $maternityLeavesData['status']               = 1;
            $maternityLeavesData['username']             = Auth::user()->name;
            $maternityLeavesData['date']                 = date("Y-m-d");
            $maternityLeavesData['time']                 = date("H:i:s");

            DB::table('leave_application_data')->insert($maternityLeavesData);
        }


        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#vis');
    }

    public function addEmployeeAttendanceFileDetail()
    {
        $data = Excel::toArray(true, request()->file('employeeAttendanceFile'));


        $counter1 = 1;
        $counter2 = 1;

        if (trim($data[0][3][0]) == 'S. No.' && trim($data[0][3][1]) == 'Employee Name' && trim($data[0][3][2]) == 'Designation' &&
            trim($data[0][3][3]) == 'Location/Site' && trim($data[0][3][4]) == 'EMR' && trim($data[0][3][5]) == 'Present Days' &&
            trim($data[0][3][6]) == 'Absent Days' && trim($data[0][3][7]) == 'Leaves (Sick, Casual, Annual)' &&
            trim($data[0][3][8]) == 'Total Over Time' && trim($data[0][3][9]) == 'Gez Overtime' && trim($data[0][3][10]) == 'Bank Account Number' &&
            trim($data[0][3][11]) == 'Remarks'
        ):

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            foreach ($data as $value):
                if ($counter1 == 1 || $counter1 == 2 || $counter1 == 3):
                    foreach ($value as $value2):
                        if ($value2[4] == '' || $value2[4] == 'EMR' || $value2[4] == 'EMR ') continue;
                        DB::table('attendance')->where([['attendance_type', '=', 1], ['month', '=', date('m', strtotime(Input::get('date_to')))], ['year', '=', date('Y', strtotime(Input::get('date_to')))], ['emr_no', '=', $value2[4]]])->delete();
                        DB::table('payroll_data')->where([['month', '=', date('m', strtotime(Input::get('date_to')))], ['year', '=', date('Y', strtotime(Input::get('date_to')))], ['emr_no', '=', $value2[4]]])->delete();

                        $data1['emr_no'] = $value2[4];
                        $data1['present_days'] = $value2[5];
                        $data1['absent_days'] = $value2[6];
                        $data1['overtime'] = $value2[8];
                        $data1['gez_overtime'] = $value2[9];
                        $data1['attendance_from'] = Input::get('date_from');
                        $data1['attendance_to'] = Input::get('date_to');
                        $data1['month'] = date('m', strtotime(Input::get('date_to')));
                        $data1['year'] = date('Y', strtotime(Input::get('date_to')));
                        $data1['username'] = Auth::user()->name;
                        $data1['attendance_type'] = 1;
                        $data1['status'] = 1;
                        $data1['date'] = date("Y-m-d");
                        $data1['time'] = date("H:i:s");

                        DB::table('attendance')->insert($data1);

                        $account_no = EmployeeBankData::where([['emr_no', '=', $value2[4]], ['status', '=', 1]]);
                        if ($account_no->count() > 0):
                            $accno['account_no'] = $value2[10];
                            EmployeeBankData::where([['emr_no', '=', $value2[4]]])->update($accno);

                        else:

                            $data2['emr_no'] = $value2[4];
                            $data2['account_title'] = "-";
                            $data2['bank_name'] = "-";
                            $data2['account_no'] = $value2[10];
                            $data2['username'] = Auth::user()->name;
                            $data2['status'] = 1;
                            $data2['date'] = date("Y-m-d");
                            $data2['time'] = date("H:i:s");
                            DB::table('employee_bank_data')->insert($data2);
                        endif;

                    endforeach;
                endif;
                $counter1++;
            endforeach;

            CommonHelper::reconnectMasterDatabase();

        else:
            Session::flash('errorMsg', 'Please upload file with the given format.');
            return Redirect::to('hr/createManageAttendanceForm?m=' . Input::get('m') . '#vis');
        endif;


        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?m=' . Input::get('m') . '#vis');

    }

    public function addEmployeeExitClearanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $employee_id = Input::get('employee_id');
        $data1['employee_id'] = $employee_id;
        $data1['leaving_type'] = Input::get('leaving_type');
        $data1['supervisor_name'] = Input::get('supervisor_name');
        $data1['signed_by_supervisor'] = Input::get('signed_by_supervisor');
        $data1['last_working_date'] = Input::get('last_working_date');
        $data1['department_id'] = Input::get('department');
        $data1['designation_id'] = Input::get('designation');
        $data1['branch'] = Input::get('branch');
        $data1['room_key'] = Input::get('room_key');
        $data1['room_key_remarks'] = Input::get('room_key_remarks');
        $data1['mobile_sim'] = Input::get('mobile_sim');
        $data1['mobile_sim_remarks'] = Input::get('mobile_sim_remarks');
        $data1['mfm_employee_card'] = Input::get('mfm_employee_card');
        $data1['mfm_employee_card_remarks'] = Input::get('mfm_employee_card_remarks');
        $data1['client_access_card'] = Input::get('client_access_card');
        $data1['client_access_card_remarks'] = Input::get('client_access_card_remarks');
        $data1['medical_insurance_card'] = Input::get('medical_insurance_card');
        $data1['medical_insurance_card_remarks'] = Input::get('medical_insurance_card_remarks');
        $data1['eobi_card'] = Input::get('eobi_card');
        $data1['eobi_card_remarks'] = Input::get('eobi_card_remarks');
        $data1['biometric_scan'] = Input::get('biometric_scan');
        $data1['biometric_scan_remarks'] = Input::get('biometric_scan_remarks');
        $data1['exit_interview'] = Input::get('exit_interview');
        $data1['exit_interview_remarks'] = Input::get('exit_interview_remarks');
        $data1['laptop'] = Input::get('laptop');
        $data1['laptop_remarks'] = Input::get('laptop_remarks');
        $data1['desktop_computer'] = Input::get('desktop_computer');
        $data1['desktop_computer_remarks'] = Input::get('desktop_computer_remarks');
        $data1['email_account_deactivated'] = Input::get('email_account_deactivated');
        $data1['email_account_deactivated_remarks'] = Input::get('email_account_deactivated_remarks');
        $data1['advance_loan'] = Input::get('advance_loan');
        $data1['advance_loan_remarks'] = Input::get('advance_loan_remarks');
        $data1['extra_leaves'] = Input::get('extra_leaves');
        $data1['extra_leaves_remarks'] = Input::get('extra_leaves_remarks');
        $data1['final_settlement'] = Input::get('final_settlement');
        $data1['final_settlement_remarks'] = Input::get('final_settlement_remarks');
        $data1['note'] = Input::get('note');
        $data1['laptop_other_devices']=Input::get('laptop_other_devices');
        $data1['cr_manuals']=Input::get('cr_manuals');
        $data1['other']=Input::get('other');
        $data1['cr_reference_books']=Input::get('cr_reference_books');
        $data1['laptop_other_devices_remarks']=Input::get('laptop_other_devices_remarks');
        $data1['cr_manuals_remarks']=Input::get('cr_manuals_remarks');
        $data1['cr_reference_books_remarks']=Input::get('cr_reference_books_remarks');
        $data1['other_remarks']=Input::get('other_remarks');
        $data1['exit_from_attendance_system']=Input::get('exit_from_attendance_system');
        $data1['inactivate_vis_login']=Input::get('inactivate_vis_login');
        $data1['network_login']=Input::get('network_login');
        $data1['inactivate_vis_ratio_login']=Input::get('inactivate_vis_ratio_login');
        $data1['change_email_password']=Input::get('change_email_password');
        $data1['omit_name']=Input::get('omit_name');
        $data1['email_diverted_to_supervisor']=Input::get('email_diverted_to_supervisor');
        $data1['remove_name_from_web']=Input::get('remove_name_from_web');
        $data1['inactivate_vista_plus_login']=Input::get('inactivate_vista_plus_login');
        $data1['remove_from_vis_whatsapp']=Input::get('remove_from_vis_whatsapp');
        $data1['inactivate_own_cloud_login']=Input::get('inactivate_own_cloud_login');
        $data1['leaving_statement']=Input::get('leaving_statement');

        $data1['status'] = 1;
        $data1['approval_status'] = 2;
        $data1['company_id'] = Input::get('company_id');
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_exit')->where([['employee_id', $employee_id]])->delete();
     
        CommonHelper::LogGenerator('employee_exit',$employee_id,'delete','row is deleted','hr',Auth::user()->id);

        $employee_exitLastId = DB::table('employee_exit')->insertGetId($data1);
        
        CommonHelper::LogGenerator('employee_exit',$employee_exitLastId,'insert','row is inserted','hr',Auth::user()->id);

        Employee::where([['id','=',$employee_id],['status','!=','2']])->update(['status' => 3]);

        CommonHelper::LogGenerator('Employee',$employee_id,'update','row is updated','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewEmployeeExitClearanceList?m=' . Input::get('company_id'));
    }

    public function addFinalSettlementDetail()
    {
        $employee_id = Input::get('employee_id');
        $data['employee_id'] = $employee_id;
        $data['last_working_date'] = Input::get('last_working_date');
        $data['salary_upto'] = Input::get('salary_upto');
        $data['salary_amount'] = Input::get('salary_amount');
        $data['salary_cheque_no'] = Input::get('salary_cheque_no');
        $data['salary_cheque_date'] = Input::get('salary_cheque_date');
        $data['other_amount_narration'] = Input::get('other_amount_narration');
        $data['other_amount'] = Input::get('other_amount');
        $data['leave_encashment_days'] = Input::get('leave_encashment_days');
        $data['leave_encashment_amount'] = Input::get('leave_encashment_amount');
        $data['hold_salary'] = Input::get('hold_salary');
        $data['hold_salary_month'] = Input::get('hold_salary_month');
        $data['hold_salary_year'] = Input::get('hold_salary_year');
        $data['leave_deduction_days'] = Input::get('leave_deduction_days');
        $data['leave_deduction_amount'] = Input::get('leave_deduction_amount');
        $data['loan_deduction'] = Input::get('loan_deduction');
        $data['gratuity'] = Input::get('gratuity');
        $data['loan_id'] = Input::get('loan_id');
        $data['opd_excess'] = Input::get('opd_excess');
        $data['other_deduction_type'] = Input::get('other_deduction_type');
        $data['other_deduction_amount'] = Input::get('other_deduction_amount');
        $data['status'] = 1;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        $emp_status['status'] = 3;
        $active_status['status'] = 2;
       
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
       
        Employee::where([['id', '=', $employee_id]])->update($emp_status);

        CommonHelper::LogGenerator('Employee',$employee_id,'update','row is updated','hr',Auth::user()->id);

        $final_settlementLastId = DB::table('final_settlement')->insertGetId($data);

        CommonHelper::LogGenerator('final_settlement',$final_settlementLastId,'insert','row is inserted','hr',Auth::user()->id);

        Session::flash('dataInsert','Successfully Saved');
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('hr/viewFinalSettlementList?m='.Input::get('company_id'));
    }


    // final settlement end


//    public function addEmployeeIdCardRequestDetail(Request $request)
//    {
//        $counter = 0;
//        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
//
//        if ($request->hasFile('fir_copy')):
//            $counter++;
//            $extension = $request->file('fir_copy')->getClientOriginalExtension();
//            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('fir_copy')->getClientOriginalExtension();
//            $path = $request->file('fir_copy')->storeAs('uploads/employee_id_card_fir_copy', $file_name);
//            $data1['fir_copy_path'] = 'app/' . $path;
//            $data1['fir_copy_extension'] = $extension;
//        endif;
//
//        if ($request->hasFile('card_image')):
//            $counter++;
//            $extension = $request->file('card_image')->getClientOriginalExtension();
//            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('card_image')->getClientOriginalExtension();
//            $path = $request->file('card_image')->storeAs('uploads/employee_id_card_images', $file_name);
//            $data1['card_image_path'] = 'app/' . $path;
//            $data1['card_image_extension'] = $extension;
//        endif;
//
//        if (Input::get('card_replacement') == 0) {
//            $data1['fir_copy_path'] = null;
//            $data1['fir_copy_extension'] = null;
//        }
//
//        $emr_no = Input::get('employee_id');
//
//        $data1['emr_no'] = Input::get('emr_no');
//        $data1['posted_at'] = Input::get('posted_at');
//        $data1['card_replacement'] = Input::get('card_replacement');
//        $data1['replacement_type'] = Input::get('replacement_type');
//        $data1['payment'] = Input::get('payment');
//        $data1['username'] = Auth::user()->name;
//        $data1['approval_status'] = 1;
//        $data1['status'] = 1;
//        $data1['card_status'] = 1;
//        $data1['date'] = date("Y-m-d");
//        $data1['time'] = date("H:i:s");
//
//        DB::table('employee_card_request')->where([['emr_no', $emr_no]])->delete();
//
//        DB::table('employee_card_request')->insert($data1);
//        CommonHelper::reconnectMasterDatabase();
//
//        Session::flash('dataInsert', 'successfully saved.');
//        return Redirect::to('hr/viewEmployeeIdCardRequestList?m=' . Input::get('company_id') . '#vis');
//    }

    public function addEmployeePromotionDetail(Request $request)
    {
        $employee_id = Input::get('employee_id');
        $company_id = Input::get('company_id');

        $data['employee_id'] = $employee_id;
        $data['designation_id'] = Input::get('designation_id');
        $data['grade_id'] = Input::get('grade_id');
        $data['increment'] = Input::get('increment');
        $data['salary'] = Input::get('salary');
        $data['promotion_date'] = Input::get('promotion_date');
        $data['status'] = 1;
        $data['approval_status'] = 1;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        CommonHelper::companyDatabaseConnection($company_id);
        $id = DB::table('employee_promotion')->insertGetId($data);
        
        CommonHelper::LogGenerator('employee_promotion',$id,'insert','row is inserted','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewEmployeePromotionsList?m='.$company_id);
    }

    public function addEmployeeFuelDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        if (!empty(Input::get('fuel_data'))):
            foreach (Input::get('fuel_data') as $fuel_rows):

                $fuel_date = Input::get('fuel_date_' . $fuel_rows . '');

                if (EmployeeFuelData::where([['emr_no', '=', Input::get('emr_no')], ['fuel_date', '=', $fuel_date], ['status', '=', 1]])->exists()) {
                    DB::table('employee_fuel_data')->where([['emr_no', '=', Input::get('emr_no')], ['fuel_date', '=', $fuel_date], ['status', '=', 1]])->delete();
                }
                $data['emr_no'] = Input::get('emr_no');
                $data['fuel_date'] = $fuel_date;
                $data['from'] = Input::get('from_' . $fuel_rows . '');
                $data['to'] = Input::get('to_' . $fuel_rows . '');
                $data['km'] = Input::get('km_' . $fuel_rows . '');
                $data['fuel_month'] = date('m', strtotime($fuel_date));
                $data['fuel_year'] = date('Y', strtotime($fuel_date));

                $data['approval_status'] = 1;
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('employee_fuel_data')->insert($data);
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeFuel?m=' . Input::get('company_id') . '#vis');
    }

    public function addHrLetters()
    {
          CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $letter_id = Input::get('letter_id');

        if ($letter_id == 1) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_warning_letter')->insertGetId($data1);

            CommonHelper::LogGenerator('hr_warning_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);

            return Redirect::to('hdc/viewHrWarningLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');

        }

        if ($letter_id == 2) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['confirmation_from'] = Input::get('confirmation_from');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_mfm_south_increment_letter')->insertGetId($data1);
          
            CommonHelper::LogGenerator('hr_mfm_south_increment_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);
          
            return Redirect::to('hdc/viewHrMfmSouthIncrementLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');

        }

        if ($letter_id == 3) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['performance_from'] = Input::get('performance_from');
            $data1['performance_to'] = Input::get('performance_to');
            $data1['confirmation_from'] = Input::get('confirmation_from');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_mfm_south_without_increment_letter')->insertGetId($data1);
           
            CommonHelper::LogGenerator('hr_mfm_south_without_increment_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);

            return Redirect::to('hdc/viewHrMfmSouthWithoutIncrementLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');

        }
        if ($letter_id == 4) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['conclude_date'] = Input::get('conclude_date');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_contract_conclusion_letter')->insertGetId($data1);
          
            CommonHelper::LogGenerator('hr_contract_conclusion_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);

            return Redirect::to('hdc/viewHrContractConclusionLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');

        }
        if ($letter_id == 5) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_termination_format1_letter')->insertGetId($data1);
           
            CommonHelper::LogGenerator('hr_termination_format1_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);
           
            return Redirect::to('hdc/viewHrTerminationFormat1Letter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');


        }
        if ($letter_id == 6) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_termination_format2_letter')->insertGetId($data1);
           
            CommonHelper::LogGenerator('hr_termination_format2_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);
           
            return Redirect::to('hdc/viewHrTerminationFormat2Letter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');

        }

        if ($letter_id == 7) {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['transfer_date'] = Input::get('transfer_date');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_transfer_letter')->insertGetId($data1);

            CommonHelper::LogGenerator('hr_transfer_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);


            return Redirect::to('hdc/viewHrTransferLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');

        }
        if ($letter_id == 7) {
            $data1['emp_id'] = Input::get('emp_id');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['transfer_date'] = Input::get('transfer_date');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_transfer_letter')->insertGetId($data1);

            CommonHelper::LogGenerator('hr_transfer_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);

            return Redirect::to('hdc/viewHrTransferLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');

        }
        if ($letter_id == 8) {
            $data1['emp_id'] = Input::get('emp_id');
            $data1['letter_content1'] = Input::get('letter_content1');
            //$data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_warning_letter')->insertGetId($data1);

            CommonHelper::LogGenerator('hr_warning_letter',$last_id,'insert','row is inserted','hr',Auth::user()->id);

            return Redirect::to('hdc/viewHrWarningLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#vis');

        }

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
    }


    public function AddLettersFile(Request $request)
    {


        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        /*Image uploading start*/

        $extension = $request->file('letter_file')->getClientOriginalExtension();
        $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('letter_file')->getClientOriginalExtension();
        $path = 'app/' . $request->file('letter_file')->storeAs('uploads/employee_hr_letters', $file_name);

        /*Image uploading end*/

        $data1['emr_no'] = Input::get('emr_no');
        $data1['letter_type'] = Input::get('letter_type');
        $data1['letter_path'] = $path;
        $data1['file_type'] = $extension;
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");


        DB::table('letter_files')->insert($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/uploadLettersFile?&&m=' . Input::get('company_id') . '#vis');


    }

    public function addEmployeeEquipmentDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        DB::table('employee_equipments')->where('emp_id', Input::get('emp_id'))->delete();
        foreach (Input::get('equipment_id') as $key => $val):

            if (strip_tags($val) == 11):
                $data['model_number'] = Input::get('model_number');
                $data['mobile_number'] = Input::get('mobile_number');
                $data['sim_number'] = Input::get('sim_number');
            endif;

            $data['equipment_id'] = strip_tags($val);
            $data['emp_id'] = Input::get('emp_id');
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['approval_status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");

            $last_insert_id = DB::table('employee_equipments')->insertGetId($data);

            if (strip_tags($val) == 9):

                if ($request->file('insurance_path')):
                    $file_name1 = Input::get('emp_id') . '_' . time() . '.' . $request->file('insurance_path')->getClientOriginalExtension();
                    $path1 = 'app/' . $request->file('insurance_path')->storeAs('uploads/employee_insurance_copy', $file_name1);
                    //   $data1['insurance_path'] = $path1;
                    //   $data1['insurance_type'] = $request->file('insurance_path')->getClientOriginalExtension();
                endif;

                $data1['insurance_number'] = Input::get('insurance_number');

                //  DB::table('employee')->where('emp_id', Input::get('emp_id'))->update($data1);
            endif;

            if (strip_tags($val) == 10):

                if ($request->file('eobi_path')):
                    $file_name1 = Input::get('emp_id') . '_' . time() . '.' . $request->file('eobi_path')->getClientOriginalExtension();
                    $path1 = 'app/' . $request->file('eobi_path')->storeAs('uploads/employee_eobi_copy', $file_name1);
                    $data2['eobi_path'] = $path1;
                    $data2['eobi_type'] = $request->file('eobi_path')->getClientOriginalExtension();
                endif;

                //  $data2['eobi_number'] = Input::get('eobi_number');

                //   DB::table('employee')->where('emp_id', Input::get('emp_id'))->update($data2);
            endif;

        endforeach;


        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeEquipmentsList?m=' . Input::get('company_id') . '#vis');
    }

    public function addDiseaseDetail()
    {
        foreach (Input::get('disease_type') as $key => $val):
            $data1['disease_type'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = Input::get('company_id');
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('diseases')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDiseasesList?m=' . Input::get('company_id') . '#vis');
    }

    public function addEmployeeMedicalDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $emr_no = Input::get('emr_no');

        $counter = 0;
        if ($request->file('medical_file_path')) {
            foreach ($request->file('medical_file_path') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'EmrNo_' . $emr_no . '_employee_medical_file_' . time() . '_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_medical_documents', $file_name);

                    $fileUploadData['emr_no'] = $emr_no;
                    $fileUploadData['medical_file_name'] = $file_name;
                    $fileUploadData['medical_file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['medical_file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('employee_medical_documents')->insert($fileUploadData);
                }
            }
        }

        $data1['emr_no'] = $emr_no;
        $data1['disease_type_id'] = Input::get('disease_type_id');
        $data1['disease_date'] = Input::get('disease_date');
        $data1['amount'] = Input::get('amount');
        $data1['cheque_number'] = Input::get('cheque_number');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_medical')->insert($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeMedicalList?m=' . Input::get('company_id') . '#vis');
    }


    public function addEmployeeGratuityDetail()
    {

        $acc_no = (unserialize(base64_decode(Input::get('emr_no'))));

        foreach ($acc_no as $value):


            $data1['emr_no'] = $value;
            $data1['from_date'] = Input::get('from_date_' . $value);
            $data1['to_date'] = Input::get('till_date_' . $value);
            $data1['year_month'] = Input::get('year_month_' . $value);
            $data1['gratuity'] = Input::get('gratuity_' . $value);
            $data1['employee_category_id'] = Input::get('emp_category_id_' . $value);
            $data1['region_id'] = Input::get('region_id_' . $value);
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            DB::table('gratuity')->where('emr_no', $value)->delete();
            DB::table('gratuity')->insert($data1);
            CommonHelper::reconnectMasterDatabase();
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createEmployeeGratuityForm?m=' . Input::get('m') . '');

    }

    public function uploadOvertimeAndFuelFile()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month = date('m', strtotime(Input::get('month_year')));
        $year = date('Y', strtotime(Input::get('month_year')));
        echo "<pre>";
        $data = Excel::toArray(true, request()->file('overtimeAndFuelFile'));


        $counter1 = 1;
        foreach ($data as $value):
            foreach ($value as $value2):
                if ($counter1 == 1):

                    if ($value2[4] == '' || $value2[4] == 'EMR #' || $value2[4] == 'EMR # ') continue;

                    DB::table('overtime')->where([['month', '=', $month], ['year', '=', $year], ['emr_no', '=', $value2[4]]])->delete();

                    $overtime['employee_category_id'] = Input::get('emp_category_id');
                    $overtime['region_id'] = Input::get('region_id');
                    $overtime['emr_no'] = $value2[4];
                    $overtime['month'] = $month;
                    $overtime['year'] = $year;
                    $overtime['gross_salary'] = round($value2[5]);
                    $overtime['ot_claimed_hours'] = $value2[6];
                    $overtime['ot_verified_hours'] = $value2[7];
                    $overtime['per_hour_ot_rate'] = round($value2[8]);
                    $overtime['ot_for_month'] = round($value2[9]);
                    $overtime['bank_account_no'] = $value2[10];
                    $overtime['username'] = Auth::user()->name;
                    $overtime['status'] = 1;
                    $overtime['date'] = date("Y-m-d");
                    $overtime['time'] = date("H:i:s");

                    DB::table('overtime')->insert($overtime);

                    $account_no = EmployeeBankData::where([['emr_no', '=', $value2[4]], ['status', '=', 1]]);
                    if ($account_no->count() > 0):
                        $accno['account_no'] = $value2[10];
                        EmployeeBankData::where([['emr_no', '=', $value2[4]]])->update($accno);

                    else:
                        $data2['emr_no'] = $value2[4];
                        $data2['account_title'] = "-";
                        $data2['bank_name'] = "-";
                        $data2['account_no'] = $value2[10];
                        $data2['username'] = Auth::user()->name;
                        $data2['status'] = 1;
                        $data2['date'] = date("Y-m-d");
                        $data2['time'] = date("H:i:s");
                        DB::table('employee_bank_data')->insert($data2);
                    endif;

                elseif ($counter1 == 2):

                    if ($value2[5] == '' || $value2[5] == 'EMR ' || $value2[5] == 'EMR') continue;

                    DB::table('fuel')->where([['month', '=', $month], ['year', '=', $year], ['emr_no', '=', $value2[5]]])->delete();

                    $fuel['employee_category_id'] = Input::get('emp_category_id');
                    $fuel['region_id'] = Input::get('region_id');
                    $fuel['emr_no'] = $value2[5];
                    $fuel['month'] = $month;
                    $fuel['year'] = $year;
                    $fuel['monthly_salary'] = round($value2[6]);
                    $fuel['km'] = round($value2[7]);
                    $fuel['rate'] = round($value2[8]);
                    $fuel['amount'] = round($value2[9]);
                    $fuel['bank_account_no'] = $value2[10];
                    $fuel['username'] = Auth::user()->name;
                    $fuel['status'] = 1;
                    $fuel['date'] = date("Y-m-d");
                    $fuel['time'] = date("H:i:s");

                    DB::table('fuel')->insert($fuel);

                    $account_no = EmployeeBankData::where([['emr_no', '=', $value2[5]], ['status', '=', 1]]);
                    if ($account_no->count() > 0):
                        $accno['account_no'] = $value2[10];
                        EmployeeBankData::where([['emr_no', '=', $value2[5]]])->update($accno);

                    else:
                        $data2['emr_no'] = $value2[5];
                        $data2['account_title'] = "-";
                        $data2['bank_name'] = "-";
                        $data2['account_no'] = $value2[10];
                        $data2['username'] = Auth::user()->name;
                        $data2['status'] = 1;
                        $data2['date'] = date("Y-m-d");
                        $data2['time'] = date("H:i:s");
                        DB::table('employee_bank_data')->insert($data2);
                    endif;

                elseif ($counter1 == 3):

                    if ($value2[1] == '' || $value2[1] == 'Name ' || $value2[1] == 'Name') continue;

                    DB::table('drivers_allowance')->where([['month', '=', $month], ['year', '=', $year], ['emp_name', '=', $value2[1]]])->delete();

                    $driver['employee_category_id'] = Input::get('emp_category_id');
                    $driver['region_id'] = Input::get('region_id');
                    $driver['month'] = $month;
                    $driver['year'] = $year;
                    $driver['emp_name'] = $value2[1];
                    $driver['designation'] = $value2[2];
                    $driver['location'] = $value2[3];
                    $driver['cost_center'] = $value2[4];
                    $driver['psgl'] = $value2[5];
                    $driver['hours'] = round($value2[6]);
                    $driver['salary'] = round($value2[7]);
                    $driver['rate'] = round($value2[8]);
                    $driver['ot_labour_law'] = round($value2[10]);
                    $driver['allowance_on_holiday'] = round($value2[11]);
                    $driver['allowance_on_workingday'] = round($value2[12]);
                    $driver['parking_charges'] = round($value2[13]);
                    $driver['out_of_city'] = round($value2[14]);
                    $driver['puncture'] = round($value2[15]);
                    $driver['mobile_charges'] = $value2[16];
                    $driver['total_allowance'] = round($value2[17]);
                    $driver['bank_account_no'] = $value2[18];
                    $driver['username'] = Auth::user()->name;
                    $driver['status'] = 1;
                    $driver['date'] = date("Y-m-d");
                    $driver['time'] = date("H:i:s");

                    DB::table('drivers_allowance')->insert($driver);

                endif;
            endforeach;
            $counter1++;
        endforeach;

      

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/uploadOtAndFuelFile?m=' . Input::get('m') . '#vis');

    }
    
    public function AssignWorkingHoursPolicyDetail(Request $request)
    {
        $employee_id= $request->employee_id;

        $working_hours_policy_array = [];
        $working_hours_policy = DB::table('working_hours_policy')->select('id','days_off')->where('status',1)->get();
        foreach($working_hours_policy as $val){
            $working_hours_policy_array[$val->id] = ['day_off'=>$val->days_off];
        }

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        foreach($employee_id as $key => $value){

            if($request->get('check_input')[$key] == 1){
                $data['employee_id'] = $value;
                if($request->get('fixed_input')[$key] == 1){
                    $data['fixed'] = $request->get('fixed_input')[$key];
                    $data['from_date'] = '';
                    $data['to_date'] = '';
                }else{
                    $data['fixed'] = $request->get('fixed_input')[$key];
                    $data['from_date'] = $request->get('from_date')[$key];
                    $data['to_date'] = $request->get('to_date')[$key];
                }
                $data['day_off'] = $working_hours_policy_array[$request->get('working_hours_policy')[$key]]['day_off'];
                $data['working_hours_policy_id'] = $request->get('working_hours_policy')[$key];
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                $exist = DB::table('assign_working_hour_policies')->where('employee_id',$value)->where('status',1)->where('fixed',$request->get('fixed_input')[$key])
                    ->where('from_date',$request->get('from_date')[$key])->where('to_date',$request->get('to_date')[$key]);

                if($exist->count() > 0){
                    DB::table('assign_working_hour_policies')->where('employee_id',$value)
                        ->where('from_date',$request->get('from_date')[$key])->update($data);

                    CommonHelper::LogGenerator('assign_working_hour_policies',$value,'update','row is updated','hr',Auth::user()->id);

                }else{
                    $assignWorkingHourPoliciesLastId = DB::table('assign_working_hour_policies')->insertGetId($data);
                    CommonHelper::LogGenerator('assign_working_hour_policies',$assignWorkingHourPoliciesLastId,'insert','row is inserted','hr',Auth::user()->id);
                }
            }
        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/assignWorkingHoursPolicyForm?m=' . Input::get('m'));
    }

    public function addEmployeeTransferLeave()
    {
        $duplicate_leave_policy = DB::table('transfered_leaves')->where([['leaves_policy_id', '=', Input::get('leaves_policy_id')], ['status', '=', 1]])->first();
        if (Input::get('assign_all_emp') != '' && count($duplicate_leave_policy) == '0') {
            $empCode = unserialize(base64_decode(Input::get('empCode')));

            foreach ($empCode as $value):
                DB::table('transfered_leaves')->where([['leaves_policy_id', '=', Input::get('leaves_policy_id')], ['emr_no', '=', $value]])->delete();
                $data['emr_no'] = $value;
                $data['leaves_policy_id'] = Input::get('leaves_policy_id');
                $data['casual_leaves'] = (Input::get('casualLeaves_' . $value) < 1 ? 0 : Input::get('casualLeaves_' . $value));
                $data['sick_leaves'] = 0;
                $data['annual_leaves'] = (Input::get('annualLeaves_' . $value) < 1 ? true : Input::get('annualLeaves_' . $value));
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");
                DB::table('transfered_leaves')->insert($data);
            endforeach;

            $companiesList = DB::Table('company')->select('id', 'name')->get()->toArray();
            foreach ($companiesList as $companyData):
                CommonHelper::companyDatabaseConnection($companyData->id);
                $employees = Employee::select('emr_no')->where([['status', '=', 1]])->get()->toArray();
                foreach ($employees as $employeesValue):

                    if (in_array($employeesValue['emr_no'], $empCode)):
                        DB::Table('employee')->where([['emr_no', '=', $employeesValue['emr_no']]])->update(array('leaves_policy_id' => Input::get('leaves_policy_id')));
                    endif;

                endforeach;
                CommonHelper::reconnectMasterDatabase();
            endforeach;
            Session::flash('dataInsert', 'successfully saved.');
            return Redirect::to('hr/employeeTransferLeaves?m=' . Input::get('company_id') . '#vis');
        } else {
            return Redirect::to('hr/employeeTransferLeaves?m=' . Input::get('company_id') . '#vis');
        }
    }
     public function addManualyAttendances(Request $request)
    {

        $to_date = explode('-', Input::get('to_date'));
        $month = $to_date[1];
        $year = $to_date[0];
        $name_array = $_POST['attendance_date'];
        $temp_id='';
        $emp_ids_array=[];
       
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        for ($i = 0; $i < count($name_array); $i++) {
            // echo Input::get('clock_in')[$i];
            // echo "<br>";
            // echo Input::get('clock_out')[$i];

            if($temp_id != Input::get('employee_id')[$i]){
                array_push($emp_ids_array,Input::get('employee_id')[$i]);
            }
            $temp_id=Input::get('employee_id')[$i];

            if (Input::get('clock_in')[$i] == ''):
                $check_in_time_24 = '';
            else:
                if(Input::get('absent')[$i] == '1' ){
                    $check_in_time_24 = date("H:i", strtotime(Input::get('clock_in')[$i]));
                }
                else{
                    $check_in_time_24 = '';
                }

            endif;

            if (Input::get('clock_out')[$i] == ''):
                $check_out_time_24 = '';
            else:
                if(Input::get('absent')[$i] == '1' ){
                    $check_out_time_24 = date("H:i", strtotime(Input::get('clock_out')[$i]));
                }
                else{
                    $check_out_time_24 = '';
                }

            endif;
            
        //   $working_hours_policy_id= DB::select('select working_hours_policy_id from assign_working_hour_policies
        //         WHERE status=1 AND employee_id = '.Input::get('employee_id')[$i].' AND 
        //         ((from_date <= '.Input::get('attendance_date')[$i].' AND to_date >= '.Input::get('attendance_date')[$i].') OR
        //         (fixed=1)) ORDER BY id DESC');
        
        $employee_id = Input::get('employee_id')[$i];
        $attendance_date = Input::get('attendance_date')[$i];
        
        $working_hours_policy_id = DB::table('assign_working_hour_policies')->select('working_hours_policy_id')
            ->where(function ($q) use ($employee_id,$attendance_date) {
                $q->where([['employee_id','=',$employee_id],['status','=',1],['from_date','<=',$attendance_date],['to_date','>=',$attendance_date]])
                ->orWhere([['employee_id','=',$employee_id],['status','=',1],['fixed','=',1]]);
            })
            ->orderBy('id','desc')->value('working_hours_policy_id');
            
            $day_off = DB::table('assign_working_hour_policies')->select('day_off')
                ->where(function ($q) use ($employee_id,$attendance_date) {
                    $q->where([['employee_id','=',$employee_id],['status','=',1],['from_date','<=',$attendance_date],['to_date','>=',$attendance_date]])
                    ->orWhere([['employee_id','=',$employee_id],['status','=',1],['fixed','=',1]]);
                })
                ->orderBy('id','desc')->value('day_off');
           
            
            $day_off_emp =  explode('=>',$day_off);
            $looping_date=date('D',strtotime(Input::get('attendance_date')[$i]));
               
            if(in_array($looping_date,$day_off_emp)){
                $manualyAttData['attendance_status'] = 'day_off';
            }else{
                $manualyAttData['attendance_status'] = '';
            }
                
            $manualyAttData['employee_id'] = Input::get('employee_id')[$i];
            $manualyAttData['working_hours_policy_id'] = $working_hours_policy_id;
            // $manualyAttData['emp_name'] = Input::get('emp_name')[$i];
            $manualyAttData['day'] = Input::get('day')[$i];
            $manualyAttData['month'] = $month;
            $manualyAttData['year'] = $year;
            $manualyAttData['manual_attendance'] = 1;
            $manualyAttData['attendance_date'] = Input::get('attendance_date')[$i];
            $manualyAttData['clock_in'] = $check_in_time_24;
            $manualyAttData['clock_out'] = $check_out_time_24;
            $manualyAttData['status'] = 1;
            $manualyAttData['remarks'] = Input::get('remarks');
            $manualyAttData['username'] = Auth::user()->name;
            $manualyAttData['date'] = date("Y-m-d");
            $manualyAttData['time'] = date("H:i:s");

           

            //DB::table('attendance')->insert($manualyAttData);


            $attendance = DB::table('attendance')->where([['attendance_date', '=', Input::get('attendance_date')[$i]], ['employee_id', '=', Input::get('employee_id')[$i]]]);

            if ($attendance->count() > 0):
                DB::table('attendance')->where([['attendance_date', '=', Input::get('attendance_date')[$i]], ['employee_id', '=', Input::get('employee_id')[$i]]])->update($manualyAttData);
            else:
                DB::table('attendance')->insert($manualyAttData);
            endif;

            //print_r($manualyAttData);
            

        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('m') . '#vis');
    }

    public function uploadAttendanceFile(Request $request)
    {
        ini_set('max_execution_time', 7000);
        $empidForCheck = 0;
        $totalOffdayWithMonth = [];
        $m = Input::get('m');
        $temp = '';
        $excel_month = '';
        $excel_year = '';
        $emp_ids_arr = [];
        $check = true;
        $location_id = Input::get('location_id');
        
        CommonHelper::companyDatabaseConnection($m);
    
        if ($request->hasFile('sample_file')) {
            $file = Excel::toArray(new UsersImport, request()->file('sample_file'));

            $attidIndex = 0;
            $dateIndex = 0;
            $stateIndex = 0;
            $firstRow = true;
            $sDate = '';
            $attIdArray = [];
            $allDates = [];
            
                // Extract the second element (index 1) from each sub-array
                $values = array_column($file[0], 1);

                // Remove empty values (empty strings)
                $values = array_filter($values, function($value) {
                    return is_numeric($value);
                });

                // Find the smallest and greatest values
                if (is_numeric(min($values))) 
                {
    
                    $firstDateOfAttendance = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(min($values))->format('Y-m-d');
                }
                else
                {
                    $firstDateOfAttendance = min($values);
                }
                if (is_numeric(max($values))) 
                {
    
                    $lastDateOfAttendance = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(max($values))->format('Y-m-d');
                }
                else
                {
                    $lastDateOfAttendance = max($values);
                }                   
               


            foreach ($file[0] as $key => $value) {
          
                if (empty($value[0])) {
                    break;
                }
                if (in_array('ID', $value)) {
                    $attidIndex = array_search('ID', $value);
                    $dateIndex = array_search('DATE', $value);
                    $clockInIndex = array_search('CLOCK_IN', $value);
                    $clockOutIndex = array_search('CLOCK_OUT', $value);
                    $sDate = '';
                    $attIdArray = [];
                    $allDates = [];
                    $firstRow = true;

                    $firstDayOfPreviousMonth = date('Y-m-01', strtotime('first day of last month'));
                    $lastDayOfNextMonth = date('Y-m-t', strtotime('last day of next month'));
                    
                    $previousAndFutureMonth = " INSERT IGNORE INTO attendance (employee_id,  attendance_date, day, month, year, date, time, username,attendance_type)
                                SELECT
                                    e.id AS employee_id,
                                    DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS monthdate,
                                    DATE_FORMAT(DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY, '%W') AS day_name,
                                    MONTH(DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY) AS month,
                                    YEAR(DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY) AS year,
                                    CURDATE() AS `date`,
                                    CURTIME() AS `time`,
                                    'auto_date' AS username,
                                    1
                                FROM (
                                    SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                                ) AS a
                                CROSS JOIN (
                                    SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                                ) AS b
                                CROSS JOIN (
                                    SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                                ) AS c
                                CROSS JOIN employee e
                                WHERE NOT EXISTS (
                                    SELECT 1
                                    FROM attendance a
                                    WHERE a.employee_id = e.id
                                        AND a.attendance_date = DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY
                                )
                                    AND DATE('$firstDayOfPreviousMonth') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY <= '$lastDayOfNextMonth'
                                    AND (e.attendance_id IS NOT NULL AND e.attendance_id NOT IN ('', 'EXMPT', 'EXEMPT', '0'));";

                    DB::select(DB::raw($previousAndFutureMonth));
                      
                    break ;
                }




            }
            
            foreach ($file[0] as $key => $value) 
            {
                if (empty($value[0])) {
                    break;
                }
    
               
                $checkintimeWithOneHourextra = 0 ;                                
                $checkintimeWithOneHourextraout = 0 ;  
    
                if (!in_array('ID', $value)) {
                    
                   
                   
                    $empDataSingle = DB::table('employee as e')
                        ->select('e.id', 'e.emp_name')
                        ->where([['e.status', '=', 1], ['e.emp_id', '=', $value[$attidIndex]]])
                        ->get();
                       
                   
                
                    
                        if (is_numeric($value[$dateIndex])) {
    
                        
    
                            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[$dateIndex])->format('Y-m-d');
                            if(!empty($value[$clockInIndex]))
                            {

                                $clock_in = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[$clockInIndex])->format('H:i');
                            }
                            else
                            {
                                $clock_in = '';
                            }

                            if(!empty($value[$clockOutIndex]))
                            {

                                $clock_out = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[$clockOutIndex])->format('H:i');
                            }
                            else
                            {
                                $clock_out = '';
                            }
                            
                            
                        }else{
                            $date = $value[$dateIndex];
                            $clock_in = $value[$clockInIndex];
                            $clock_out = $value[$clockOutIndex];
                        } 
                    


                     

                        if (count($empDataSingle) > 0 && $date) {
                            $employee_id = $empDataSingle[0]->id;
                            
                            attendance::where([
                                ['employee_id', '=', $employee_id],
                                ['attendance_date', '=', $date],
                                //['attendance_type', '!=', 2]
                            ])->update([
                                'clock_in' => $clock_in,
                                'clock_out' => $clock_out,
                                'attendance_type' => 1
                            ]);
                            
                        }
                }
            }

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
                
                CommonHelper::reconnectMasterDatabase();

                Session::flash('dataInsert', 'Attendance Imported Successfully !');
                return Redirect::to('hr/createManageAttendanceForm?m=' . Input::get('m') . '');
        }
        
    } 

    public function insertAutoGeneratedLeaves($no_of_days,$leave_type,$emp_id,$leaves_policy_id,$excel_month,$excel_year,$lates,$half_days_count,$company_id){

        if($leave_type != ""){

            $leaveApplicationData['emp_id']          = $emp_id;
            $leaveApplicationData['leave_policy_id'] = $leaves_policy_id;
            $leaveApplicationData['company_id']      = $company_id;
            $leaveApplicationData['leave_type']      = $leave_type;
            $leaveApplicationData['leave_day_type']  = 1;
            $leaveApplicationData['name']  = $excel_year.'-'.$excel_month.'-01';
            $leaveApplicationData['reason']          = '-';
            $leaveApplicationData['leave_address']   = '-';
            $leaveApplicationData['approval_status'] = 2;
            $leaveApplicationData['approved'] = 2;
            $leaveApplicationData['status']          = 1;
            $leaveApplicationData['username']        = Auth::user()->name;
            $leaveApplicationData['date']            = date("Y-m-d");
            $leaveApplicationData['time']            = date("H:i:s");

            $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

            $maternityLeavesData['emp_id']               = $emp_id;
            $maternityLeavesData['leave_application_id'] = $leave_application_id;
            $maternityLeavesData['leave_policy_id'] = $leaves_policy_id;
            $maternityLeavesData['leave_type']           = $leave_type;
            $maternityLeavesData['leave_day_type']       = 1;
            $maternityLeavesData['no_of_days']           = $no_of_days;
            $maternityLeavesData['from_date']            = $excel_year.'-'.$excel_month;
            $maternityLeavesData['to_date']              = $excel_year.'-'.$excel_month;

            //                $startDate = new DateTime(Input::get('from_date'));
            //                $endDate = new DateTime(Input::get('to_date'));
            //                $difference = $endDate->diff($startDate);

            //                $maternityLeavesData['no_of_days']           = $difference->format("%a")+1;

            $maternityLeavesData['status']               = 1;
            $maternityLeavesData['username']             = Auth::user()->name;
            $maternityLeavesData['date']                 = date("Y-m-d");
            $maternityLeavesData['time']                 = date("H:i:s");

            DB::table('leave_application_data')->insert($maternityLeavesData);
        }

    }



    public function addEmployeeOfTheMonthDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('month_year'));

        $data1['emp_id'] = Input::get('emp_id');
        $data1['month'] = $month_year[1];
        $data1['year'] = $month_year[0];
        $data1['remarks'] = Input::get('remarks');
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_of_the_month')->insert($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeOfTheMonth?m=' . Input::get('m') . '');
    }

    public function uploadPolicyFileDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $counter = 0;
        $title = Input::get('title');
        $category_id = Input::get('category_id');
        if ($request->file('policy_file')):
            foreach ($request->file('policy_file') as $media):
                if (!empty($media)):
                    $counter++;
                    $file_name = $title . ' ' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/policies', $file_name);

                    $data['category_id'] = $category_id;
                    $data['title'] = $title;
                    $data['file_name'] = $file_name;
                    $data['file_type'] = $media->getClientOriginalExtension();
                    $data['file_path'] = 'app/' . $path;
                    $data['status'] = 1;
                    $data['counter'] = $counter;
                    $data['username'] = Auth::user()->name;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    DB::table('policies')->insert($data);
                endif;
            endforeach;
        endif;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/uploadPolicyFile?m='.Input::get('company_id'));
    }

    public function addProvidentFundDetail()
    {

        foreach (Input::get('pf_name') as $key => $val):

            $data1['name'] = $val;
            $data1['pf_mode'] = Input::get('pf_mode')[$key];
            $data1['amount_percent'] = Input::get('amount_percent')[$key];
            $data1['status'] = 1;
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('provident_fund')->insert($data1);

        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewProvidentFundList?m=' . Input::get('company_id') . '#vis');

    }

    public function addProvidentFundDisburse()
    {

        $month_year = Input::get('month_year');
        $month_data_2nd = (explode("-", $month_year));

        $year = $month_data_2nd[0];
        $month = $month_data_2nd[1];

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $providentFundData['provident_fund_id'] = Input::get('provident_fund_id');
        $providentFundData['emp_id'] = Input::get('emp_id');
        $providentFundData['pf_amount'] = Input::get('disburse_amount');
        $providentFundData['pf_employee_fund'] = round(Input::get('disburse_amount') / 2);
        $providentFundData['pf_company_fund'] = round(Input::get('disburse_amount') / 2);
        $providentFundData['amount_type'] = 'minus';
        $providentFundData['month'] = $month;
        $providentFundData['year'] = $year;
        $providentFundData['username'] = Auth::user()->name;
        $providentFundData['status'] = 1;
        $providentFundData['date'] = date("Y-m-d");
        $providentFundData['time'] = date("H:i:s");
        DB::table('provident_fund_data')->insert($providentFundData);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/providentFundReport?m=' . Input::get('company_id') . '#vis');


    }

    public function addPfOpeningBalanceDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $provident_fund = Employee::where([['emp_id', '=', Input::get('emp_id')]]);

        if ($provident_fund->value('provident_fund_id') == 0) {
            return "Please Select Provident Fund Policy For User !";
        } else {
            $provident_fund_data = $provident_fund->first();

        }

        ProvidentFundData::where([['emp_id', '=', Input::get('emp_id')], ['view', '=', 'no'], ['provident_fund_id', '=', $provident_fund_data->provident_fund_id]])->delete();
        $providentFundData['provident_fund_id'] = $provident_fund_data->provident_fund_id;
        $providentFundData['emp_id'] = Input::get('emp_id');
        $providentFundData['pf_amount'] = Input::get('opening_balance');
        $providentFundData['pf_employee_fund'] = round(Input::get('opening_balance') / 2);
        $providentFundData['pf_company_fund'] = round(Input::get('opening_balance') / 2);
        $providentFundData['amount_type'] = 'plus';
        $providentFundData['view'] = 'no';
        $providentFundData['username'] = Auth::user()->name;
        $providentFundData['status'] = 1;
        $providentFundData['date'] = date("Y-m-d");
        $providentFundData['time'] = date("H:i:s");
        DB::table('provident_fund_data')->insert($providentFundData);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createPfOpeningBalance?m=' . Input::get('m') . '');
    }

    public function addRebateDetail(Request $request)
    {
        $month_year = explode('-',Input::get('month_year'));
        $employee_id = Input::get('employee_id');
        $data1['employee_id'] = $employee_id;
        $data1['month'] = $month_year[1];
        $data1['year'] = $month_year[0];
        $data1['type'] = Input::get('type');
        $data1['nature'] = Input::get('nature');
        $data1['rebate_amount'] = Input::get('rebate_amount');
        $data1['actual_investment'] = Input::get('actual_investment');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $last_id = DB::table('rebate')->insertGetId($data1);
        
        CommonHelper::LogGenerator('rebate',$last_id,'insert','row is inserted','hr',Auth::user()->id);

        $counter = 0;
        if ($request->file('rebate_file_path')):
            foreach ($request->file('rebate_file_path') as $media):
                if (!empty($media)):
                    $counter++;
                    $file_name = 'EmpId_' . $employee_id . '_employee_rebate_file_' . time() .'_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_rebate_documents', $file_name);
                    $fileUploadData['rebate_id'] = $last_id;
                    $fileUploadData['employee_id'] = $employee_id;
                    $fileUploadData['rebate_file_name'] = $file_name;
                    $fileUploadData['rebate_file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['rebate_file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                     $employee_rebate_documentsLastid = DB::table('employee_rebate_documents')->insertGetId($fileUploadData);
                    CommonHelper::LogGenerator('employee_rebate_documents',$employee_rebate_documentsLastid,'insert','row is inserted','hr',Auth::user()->id);
                endif;
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/createRebateForm?m='.Input::get('company_id'));
    }

    public function addStopPaymentDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $loan_id = Input::get('loan_id');
        $month_year = explode('-',Input::get('month_year'));
        $data['stop_payment_month'] = $month_year[1];
        $data['stop_payment_year'] = $month_year[0];
        $data['reason'] = Input::get('reason');
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        LoanRequest::where([['id', '=', $loan_id]])->update($data);
        
        CommonHelper::LogGenerator('Loan_Request',$loan_id,'update','row is updated','hr',Auth::user()->id);
   
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLoanRequestList?m='.Input::get('company_id'));
    }

    public function addLoanTopUpDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $loan_id = Input::get('loan_id');
        foreach (Input::get('loan_top_up_amount') as $key => $val):
            $data['loan_top_up_amount'] = strip_tags($val);
            $data['loan_id'] = $loan_id;
            $data['needed_date'] = strip_tags(Input::get('needed_date')[$key]);
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            if(isset(Input::get('id')[$key])):
                $id = Input::get('id')[$key];
                DB::table('loan_top_up')->where([['id', '=', $id]])->update($data);
                CommonHelper::LogGenerator('loan_top_up',$id,'update','row is updated','hr',Auth::user()->id);
            else:
                $loanTopUpLastId = DB::table('loan_top_up')->insertGetId($data);
                CommonHelper::LogGenerator('loan_top_up',$loanTopUpLastId,'insert','row is inserted','hr',Auth::user()->id);
            endif;
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLoanRequestList?m='.Input::get('company_id'));
    }

    public function addLoanAdjustmentDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $loan_id = Input::get('loan_id');
        foreach (Input::get('amount') as $key => $val):
            $month_year = explode('-',Input::get('month_year')[$key]);
            $data['amount'] = strip_tags($val);
            $data['loan_id'] = $loan_id;
            $data['month'] = $month_year[1];
            $data['year'] = $month_year[0];
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");
            if(isset(Input::get('id')[$key])):
                $id = Input::get('id')[$key];
                CommonHelper::LogGenerator('loan_adjustment',$id,'update','row is updated','hr',Auth::user()->id);
            else:
                $loanAdjustmentLastId = DB::table('loan_adjustment')->insertGetId($data);
                CommonHelper::LogGenerator('loan_adjustment',$loanAdjustmentLastId,'insert','row is inserted','hr',Auth::user()->id);
            endif;
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLoanRequestList?m='.Input::get('company_id'));
    }

    public function addEmployeeTransferDetail()
    {
        $company_id = Input::get('company_id');
        CommonHelper::companyDatabaseConnection($company_id);
        foreach (Input::get('employee_id') as $key => $val):
            if(Input::get('check_input')[$key] == 1):
                EmployeeMultipleLocations::where([['employee_id','=', $val],['status', '=', 1]])->update(['status' => 3]);
                $data1['employee_id'] = $val;
                $data1['project_id'] = Input::get('project_id')[$key];
                $data1['location_id'] = implode(',', Input::get('location_id_'.$val));
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['transfer_date'] = date("Y-m-d");
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");
                
                $EmployeeMultipleLocationslastid = EmployeeMultipleLocations::insertGetId($data1);

                CommonHelper::LogGenerator('EmployeeMultipleLocations',$EmployeeMultipleLocationslastid,'insert','row is inserted','hr',Auth::user()->id);
                
                $data2['reporting_manager_id'] = Input::get('reporting_manager_id')[$key];
                $data2['username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                Employee::where([['id', '=', $val]])->update($data2);
                
                                CommonHelper::LogGenerator('Employee',$val,'update','row is updated','hr',Auth::user()->id);

            endif;
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/createEmployeeTransferForm?m='.Input::get('company_id'));
    }
    
    public function addChangeReportingManagerDetail()
    {
        $company_id = Input::get('company_id');
        CommonHelper::companyDatabaseConnection($company_id);
        foreach (Input::get('employee_id') as $key => $val):
            if(Input::get('check_input')[$key] == 1):
                $data2['reporting_manager_id'] = Input::get('reporting_manager_id')[$key];
                $data2['username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                Employee::where([['id', '=', $val]])->update($data2);
                CommonHelper::LogGenerator('Employee',$val,'update','row is updated','hr',Auth::user()->id);

            endif;
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/createEmployeeTransferForm?m='.$company_id);
    }

    public function addIncrementsDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('employee_id') as $value):

            if (Input::get('increment_' . $value) > 0):

                $promotion = EmployeePromotion::updateOrCreate(['employee_id' => $value, 'promotion_date' => Input::get('promotion_date_' . $value)], [
                    'employee_id' => $value,
                    'designation_id' => Input::get('designation_id_' . $value),
                    'grade_id' => Input::get('grade_id_' . $value),
                    'promotion_date' => Input::get('promotion_date_' . $value),
                    'increment' => Input::get('increment_' . $value),
                    'salary' => Input::get('salary_' . $value),
                    'username' => Auth::user()->name,
                    'approval_status' => 1,
                    'status' => 1,
                    'date' => date("Y-m-d"),
                    'time' => date("H:i:s"),

                ]);
                if ($promotion->wasRecentlyCreated):
                    $actionType = 'insert';
                    $remark = 'row is inserted';

                else:
                    $actionType = 'update';
                    $remark = 'row is updated';
                endif;

                CommonHelper::LogGenerator('employee_promotion',$promotion->id,$actionType,$remark,'hr',Auth::user()->id);

            endif;
        endforeach;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewEmployeePromotionsList?m='.Input::get('company_id'));
    }
    
    public function addTaxCertificateDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', date("Y-m-d"));
        if (Input::get('check_list')):
            foreach (Input::get('check_list') as $key => $value):
                $id_and_Email = (explode("<>", $value));
                $data['employee_id'] = $id_and_Email[1];
                $data['tax_year'] = $id_and_Email[2];
                $data['month'] = $month_year[1];
                $data['year'] = $month_year[0];
                $data['username'] = Auth::user()->employee_id;
                $data['status'] = 1;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('tax_certificate_queue')->insert($data);
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewTaxCertificateList?m='.Input::get('m'));
    }
    
    public function addLeavesEncashmentDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if (Input::get('check_list')):
            foreach (Input::get('check_list') as $key => $value):
                $data['employee_id'] = $value;
                $data['total_remaining_leaves'] = Input::get('total_remaining_leaves')[$key];
                $data['amount'] = Input::get('amount')[$key];
                $data['leave_from'] = Input::get('leave_from');
                $data['leave_to'] = Input::get('leave_to');
                $data['username'] = Auth::user()->name;
                $data['status'] = 1;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");
                LeavesEncashment::where([['employee_id','=', $value],['leave_from','=', Input::get('leave_from')],['leave_to','=', Input::get('leave_to')]])->delete();
                LeavesEncashment::insert($data);
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/leavesEncashmentForm?m='.Input::get('m'));
    }

    public function addTFAllowanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('month_year'));
        if (Input::get('employee_id')):
            foreach (Input::get('employee_id') as $key => $value):
                if(Input::get('amount')[$key] > 0):
                    $data['employee_id'] = $value;
                    $data['amount'] = Input::get('amount')[$key];
                    $data['month'] = $month_year[1];
                    $data['year'] = $month_year[0];
                    $data['username'] = Auth::user()->name;
                    $data['status'] = 1;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    TFAllowance::insert($data);
                endif;
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/createTFAllowanceForm?m='.Input::get('m'));
    }

}