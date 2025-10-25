<?php
namespace App\Http\Controllers;


use App\Helpers\HrHelper;
use App\Http\Requests;
use App\Helpers\CommonHelper;
use App\Models\Arrears;
use App\Models\Role;
use App\Models\Attendance;
use App\Models\Cities;
use App\Models\Deduction;
use App\Models\Diseases;
use App\Models\EmployeeGsspDocuments;
use App\Models\FinalSettlement;
use App\Models\Payslip;
use App\Models\States;
use App\Models\IncomeTax;
use App\User;
use Hamcrest\Core\AllOf;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Attendence;
use App\Models\Designation;
use App\Models\HealthInsurance;
use App\Models\JobType;
use App\Models\Countries;
use App\Models\Institute;
use App\Models\Qualification;
use App\Models\LeaveType;
use App\Models\LoanType;
use App\Models\AdvanceType;
use App\Models\ShiftType;
use App\Models\MaritalStatus;
use App\Models\RequestHiring;
use App\Models\Job;
use App\Models\AdvanceSalary;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\VehicleType;
use App\Models\CarPolicy;
use App\Models\LoanRequest;
use App\Models\Eobi;
use App\Models\Tax;
use App\Models\Bonus;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
use App\Models\DegreeType;
use App\Models\WorkingHoursPolicy;
use App\Models\Holidays;
use App\Models\EmployeeDeposit;
use App\Models\Regions;
use App\Models\Locations;
use App\Models\EmployeeEquipments;
use App\Models\Grades;
use App\Models\EmployeeExit;
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
use App\Models\EmployeeCardRequest;
use App\Models\EmployeeProjects;
use App\Models\EmployeeDocuments;
use App\Models\EmployeePromotion;
use App\Models\EmployeeFuelData;
use App\Models\Equipments;
use App\Models\EmployeeMedical;
use App\Models\EmployeeTransfer;
use App\Models\LetterFiles;
use App\Models\Trainings;
use App\Models\Gratuity;
use App\Models\TrainingCertificate;
use App\Models\TransferEmployeeProject;
use App\Models\EmployeeOfTheMonth;
use App\Models\Policies;
use App\Models\EmployeeCategory;
use App\Models\EmployeeMultipleLocations;
use App\Models\AllowanceType;
use App\Models\LoanAdjustment;
use App\Models\TransferedLeaves;
use App\Models\LoanTopUp;
use App\Models\Log;

use Input;
use Auth;
use DB;
use Config;
use Hash;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use Mail;
use DateTime;
use \Cache;


use Redirect;
use Session;
use App\Helpers\RefreshCacheHelper;

use App\Helpers\HoRefreshAttendanceHelper;
use App\Helpers\PqRefreshAttendanceHelper;
use App\Helpers\GrRefreshAttendanceHelper;
use App\Helpers\MdfRefreshAttendanceHelper;
use App\Helpers\GlRefreshAttendanceHelper;
use App\Helpers\AttendanceHelper;
use Carbon\Carbon;


use Illuminate\Pagination\LengthAwarePaginator;

class HrController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function test1()
    {

                // Get the first day of the current month
        $firstDayOfMonth = Carbon::now()->startOfMonth();

        // Get the last day of the current month
        $lastDayOfMonth = Carbon::now()->endOfMonth();

        // You can format the dates if needed
        $firstDayFormatted = $firstDayOfMonth->format('Y-m-d'); // Example format
        $lastDayFormatted = $lastDayOfMonth->format('Y-m-d'); // Example format

                // INSERT INTO attendance (employee_id, attendance_id, location_id, attendance_date, day, month, year, date, time, username)
                $sql = "
           INSERT INTO attendance (employee_id, attendance_id, location_id, attendance_date, day, month, year, date, time, username)
        SELECT
            e.id AS employee_id,
            e.attendance_id,
            eml.location_id,
            DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS monthdate,
            DATE_FORMAT(DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY, '%W') AS day_name,
            MONTH(DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY) AS month,
            YEAR(DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY) AS year,
            CURDATE() AS `date`,
            CURTIME() AS `time`,
            'auto_date' AS username
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
        INNER JOIN employee_multiple_locations eml ON e.id = eml.employee_id
        WHERE DATE('$firstDayFormatted') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY <= '$lastDayFormatted'
        -- AND eml.location_id = 7
        AND eml.status = 1 AND (
            (e.attendance_id IS NOT NULL AND e.attendance_id NOT IN ('', 'EXMPT', 'EXEMPT', '0'))
        );
        ";
        
        

        // $username = user::groupBy('id')->get()->toArray();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
         $username = DB::select(DB::raw($sql));
        CommonHelper::reconnectMasterDatabase();
       
        echo "<pre>";
        print_r($username);
        exit();
    }
    public function test()
    {
        
        $from_date = "$firstDayFormatted";
        $to_date = "2023-09-24";
        $employee = HrHelper::getEmployees('12', false, false, false, '1', false, 'all');
        $emr_array = [];
        foreach ($employee as $val) {
            $emr_array[] = $val->id;
        }
        $emr_array = implode(",",$emr_array);
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $halfDayAbsentFirstHalf = AttendanceHelper::halfDayAbsentFirstHalf($emr_array,$from_date,$to_date);
        CommonHelper::reconnectMasterDatabase();

        foreach ($halfDayAbsentFirstHalf as $key => $value) {
            # code...
            if(($value->leaves_policy_id != '' && $value->leaves_policy_id != 0) && ($value->working_hours_policy_id))
            { 

                $leaves_policy = DB::table('leaves_policy')
                        ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
                        ->select('leaves_policy.*', 'leaves_data.*')
                        ->where([['leaves_policy.id', '=', $value->leaves_policy_id]])
                        ->get();

                $annual_leaves=0;	
                $casual_leaves=0;
                $sick_leaves=0;
                $casual_and_sick_leaves=0;
                $remaining=0;
                $remaining_annual=0;
                $remaining_casual=0;
                $remaining_sick=0;
                $remaining_maternal=0;
                $remaining_paternal=0;
                foreach($leaves_policy as $val_policy)
                {		
                    if($val_policy->leave_type_id == 1):
                        $annual_leaves= $val_policy->no_of_leaves;
                    elseif($val_policy->leave_type_id == 3):
                        $casual_leaves = $val_policy->no_of_leaves;
                    else:
                        $sick_leaves = $val_policy->no_of_leaves;
                    endif;
                    
                    $getUsedLeaves= DB::select('select sum(no_of_days) as no_of_days from leave_application_data
                        INNER JOIN leave_application on leave_application.id = leave_application_data.leave_application_id
                        WHERE leave_application_data.employee_id = '.$value->id.' AND leave_application.status = 1 AND (leave_application.approval_status != 3 OR leave_application.approval_status_lm != 3) AND
                        leave_application.leave_type = '.$val_policy->leave_type_id.' ');
                    
                    $casual_and_sick_leaves=$casual_leaves+$sick_leaves;
                    
                    
                    $getUsedLeaves=$getUsedLeaves[0]->no_of_days;
                
                    if($val_policy->leave_type_id == 1):
                        $remaining_annual = ($val_policy->no_of_leaves -$getUsedLeaves) <= 0 ? 0 : $val_policy->no_of_leaves -$getUsedLeaves;
                    elseif($val_policy->leave_type_id == 3):
                        $remaining_casual= ($val_policy->no_of_leaves -$getUsedLeaves) <= 0 ? 0 : $val_policy->no_of_leaves -$getUsedLeaves;
                    elseif($val_policy->leave_type_id == 2):
                    $remaining_sick=($val_policy->no_of_leaves -$getUsedLeaves) <= 0 ? 0 : $val_policy->no_of_leaves -$getUsedLeaves;
                    endif;
                }

                    $type='';
                    $remaining= 0.5;
                    $type='half_day_adjusted';
                    $half_days_count=1;
                    if($remaining_casual > 0){
                        if($remaining_casual >= $remaining){
                            $leave_type=3;
                            CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$value->id,$value->leaves_policy_id,$value->month,$value->year,$value->from_date,$half_days_count,Input::get('m'),'systemGenerated',$type);
                        }
                        
                    }
                    elseif($remaining_sick > 0){
                        if($remaining_sick >= $remaining){
                            $leave_type=2;
                            CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$value->id,$value->leaves_policy_id,$value->month,$value->year,$value->from_date,$half_days_count,Input::get('m'),'systemGenerated',$type);
                        }
                    
                    }
                    elseif($remaining_annual > 0){
                        if($remaining_annual >= $remaining){
                            $leave_type=1;
                            CommonHelper::insertAutoGeneratedLeaves($remaining,$leave_type,$value->id,$value->leaves_policy_id,$value->month,$value->year,$value->from_date,$half_days_count,Input::get('m'),'systemGenerated',$type);
                        }
                        
                    }
        
            }
        }
        echo "<pre>";
                print_r($halfDayAbsentFirstHalf);
                exit();

        //return Redirect::to('dc/hrDashboard?m='.Input::get('m'));

    }


    public function attendanceErrorReport()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        $job_type = Cache::get('job_type');
        return view('Hr.Attendance.attendanceErrorReport',compact('departments','sub_departments','category','projects','locations','employees','job_type'));


    }


    public function RefreshCache()
    {
        RefreshCacheHelper::RefreshAll(Input::get('m'));
        Session::flash('dataInsert', 'Cache updated');
        return Redirect::to('dc/hrDashboard?m='.Input::get('m'));

    }

    public function hoRefreshAttendance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attandance = HoRefreshAttendanceHelper::AddWorkingHourPolicy();
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('hr/viewEmployeeAttendanceList?m='.Input::get('m'));
    }

    // public function pqRefreshAttendance()
    // {
    //     $encodedData = "RFCLfL7nkxfEIah8gfGks3MIyLMK4uK2X6BgXnrX/Deb75RMtR3/YffsDj52NKWC";

    //     // Calculate the length of the encoded data
    //     $dataLength = strlen($encodedData);

    //     // Determine the length of each set
    //     $setLength = $dataLength / 13;

    //     // Split the encoded data into four sets
    //     $sets = str_split($encodedData, $setLength);

    //     // Display the four sets
    //     foreach ($sets as $index => $set) {
    //         echo "$ encoded = '". $set . "';<br>";
    //     }

        
    // }
    // public function pqRefreshAttendance()
    // {
        
    //     $encoded = 'RFCLfL7nkxfEIah8gfGks3MIyLMK4uK2X6BgXnrX/Deb75RMtR3/YffsDj52NKWC';
        
    //     $encoded = 'RFCL';
    //     $encoded = 'fL7n';
    //     $encoded = 'kxfE';
    //     $encoded = 'Iah8';
    //     $encoded = 'gfGk';
    //     $encoded = 's3MI';
    //     $encoded = 'yLMK';
    //     $encoded = '4uK2';
    //     $encoded = 'X6Bg';
    //     $encoded = 'XnrX';
    //     $encoded = '/Deb';
    //     $encoded = '75RM';
    //     $encoded = 'tR3/';
    //     $encoded = 'Yffs';
    //     // $encoded = 'Dj52';
    //     // $encoded = 'NKWC';
    //     // $encoded = 'XYRi';
    //     // $encoded = 'dcL5';
    //     $binary = base64_decode($encoded);
    //     $binary = bin2hex($binary);
    //     echo "Binary: " . bin2hex($binary) . "\n";

    //     $timestamps = array();
    //     $dates = array(); 

    //     for ($i = 0; $i < strlen($binary) - 4; $i++) {

    //         $stamp = unpack('V', substr($binary, $i, 4))[1];
        
    //         if ($stamp > 100000000 && $stamp < 4000000000) {
            
    //             $timestamps[] = $stamp;
    //             $dates[] = date('Y-m-d', $stamp); 
                
    //         }
        
    //     }
    //     echo "<pre>";
    //     print_r($timestamps);
    //     print_r($dates);
    // }

    // public function pqRefreshAttendance()
    // {
       
    //     $encoded = 'RFCLfL7nkxfEIah8gfGks3MIyLMK4uK2X6BgXnrX/Deb75RMtR3/YffsDj52NKWC';
    //     $encoded ='RFCLfL7nkxfEI';
    //     $encoded ='ah8gfGks3MIyL';
    //     $encoded ='MK4uK2X6BgXnr';
    //     $encoded ='X/Deb75RMtR3/';
    //     $encoded ='YffsDj52NKWC';
    //     // Decode the base64 string
    //     $binary = base64_decode($encoded);
    
    //     // Initialize an empty array to store IDs
    //     $ids = [];
    
    //     // Define the length of each ID segment (assuming IDs have a fixed length)
    //     $idSegmentLength = 8;
    
    //     // Calculate the total number of IDs in the binary data
    //     $totalIds = strlen($binary) / $idSegmentLength;
    
    //     // Loop through the binary data and extract IDs
    //     for ($i = 0; $i < $totalIds; $i++) {
    //         // Calculate the start position for the current ID segment
    //         $start = $i * $idSegmentLength;
    
    //         // Extract the current ID segment
    //         $idSegment = substr($binary, $start, $idSegmentLength);
    
    //         // Convert the ID segment to hexadecimal representation
    //         $id = bin2hex($idSegment);
    
    //         // Add the ID to the array
    //         $ids[] = $id;
    //     }
    
    //     // Output the extracted IDs
    //     foreach ($ids as $id) {
    //         echo "ID: $id\n";
            
    //     }
    // }
    
    public function pqRefreshAttendance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attandance = PqRefreshAttendanceHelper::AddWorkingHourPolicy();
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('hr/viewEmployeeAttendanceList?m='.Input::get('m'));
    }

    public function grRefreshAttendance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attandance = GrRefreshAttendanceHelper::AddWorkingHourPolicy();
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('hr/viewEmployeeAttendanceList?m='.Input::get('m'));
    }

    public function mdfRefreshAttendance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attandance = MdfRefreshAttendanceHelper::AddWorkingHourPolicy();
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('hr/viewEmployeeAttendanceList?m='.Input::get('m'));
    }

    public function glRefreshAttendance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attandance = GlRefreshAttendanceHelper::AddWorkingHourPolicy();
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('hr/viewEmployeeAttendanceList?m='.Input::get('m'));
    }


    public function viewLogs()
    {

        $query_string = "SELECT l.username id, u.name username FROM ".CONST_COMPANY_DB.".log l
                            INNER JOIN ".CONST_MASTER_DB.".users u
                            ON l.username = u.id
                            GROUP BY u.id";

        // $username = user::groupBy('id')->get()->toArray();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $tableNames = Log::where([['module', '=', 'hr']])->groupBy('table_name')->get()->toArray();
        $username = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Log.logList',compact('tableNames','username'));
    }

    public function viewLogDetail()
    {
        $sub_module = Input::get('sub_module');
        $from_date = Input::get('from_date');
        $till_date = Input::get('till_date');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $username = Input::get('username');


        if($username != null):
            $logs = Log::where([['table_name','=',Input::get('sub_module')]])
                ->whereBetween('date', [Input::get('from_date'), Input::get('till_date')])
                ->where('username', $username)
                ->orderBy('id','desc');
        else:
            $logs = Log::where([['table_name','=',Input::get('sub_module')]])
                ->whereBetween('date', [Input::get('from_date'), Input::get('till_date')])
                ->orderBy('id','desc');
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view("Hr.AjaxPages.viewLogDetail",compact('logs', 'sub_module', 'from_date', 'till_date'));
    }

    public function toDayActivity()
    {
        return view('Hr.toDayActivity');
    }

    
    public function createDepartmentForm()
    {
        return view('Hr.Departments.createDepartmentForm');
    }

    public function createDesignationWiseLocation()
    {
        return view('Hr.Locations.createDesignationWiseLocation');
    }
    public function viewDesignationWiseLocation()
    {
       return view('Hr.Locations.viewDeignationWiseLocation');
    }

    public function viewDepartmentList()
    {
        return view('Hr.Departments.viewDepartmentList');
    }

    public function editDepartmentForm()
    {
        $departments = Department::where([['id','=',Input::get('id')]])->first();
        return view('Hr.Departments.editDepartmentForm', compact('departments'));
    }

    public function createSubDepartmentForm()
    {
        $departments = Cache::get('department');
        return view('Hr.SubDepartments.createSubDepartmentForm',compact('departments'));
    }

    public function viewSubDepartmentList()
    {
        return view('Hr.SubDepartments.viewSubDepartmentList');
    }


    public function editSubDepartmentForm()
    {
        $departments = Cache::get('department');
        $sub_departments = SubDepartment::where([['id','=',Input::get('id')]])->first();
        return view('Hr.SubDepartments.editSubDepartmentForm',compact('departments','sub_departments'));
    }

    public function createDesignationForm()
    {
        $grade_type = Cache::get('grades');

        return view('Hr.Designations.createDesignationForm', compact('grade_type'));
    }

    public function viewDesignationList()
    {
        return view('Hr.Designations.viewDesignationList');
    }

    public function editDesignationForm()
    {
        $grade_type = Cache::get('grades');
        $designations = Designation::where([['id','=',Input::get('id')]])->first();
        return view('Hr.Designations.editDesignationForm', compact('designations','grade_type'));
    }

//    public function createHealthInsuranceForm()
//    {
//        return view('Hr.createHealthInsuranceForm');
//    }
//
//    public function viewHealthInsuranceList(){
//
//        $HealthInsurances = HealthInsurance::where([['company_id','=',Input::get('m')]])->get();
//        return view('Hr.viewHealthInsuranceList', compact('HealthInsurances'));
//    }
//
//    public function editHealthInsuranceForm()
//    {
//        return view('Hr.editHealthInsuranceForm');
//    }

    public function createJobTypeForm()
    {
        return view('Hr.JobType.createJobTypeForm');
    }

    public function viewJobTypeList()
    {
        return view('Hr.JobType.viewJobTypeList');
    }

    public function editJobTypeForm()
    {
        $job_type = JobType::where([['id','=',Input::get('id')]])->first();
        return view('Hr.JobType.editJobTypeForm', compact('job_type'));
    }

    public function createQualificationForm(){

        $countries = Countries::where('status', '=', 1)->get();
        $institutes = Institute::where('status', '=', 1)->get();

        return view('Hr.Qualifications.createQualificationForm',compact('countries','institutes'));
    }

    public function viewQualificationList()
    {
        $Qualifications = Qualification::where([['company_id','=',Input::get('m')],['status','=', 1]])->get();
        return view('Hr.Qualifications.viewQualificationList',compact('Qualifications'));
    }

    public function editQualificationForm(){
        $qualificationDetail = DB::selectOne('select * from `qualification` where `id` = '.Input::get('id').'');
        $countries = Countries::where('status', '=', 1)->get();
        $states = States::where([['status', '=', 1],['country_id', '=', $qualificationDetail->country_id]])->get();
        $cities = Cities::where([['status', '=', 1],['state_id', '=', $qualificationDetail->state_id]])->get();
        $institutes = Institute::where('status', '=', 1)->get();
        return view('Hr.Qualifications.editQualificationForm',compact('states','cities','qualificationDetail','countries','institutes'));
    }

    public function createLeaveTypeForm()
    {
        return view('Hr.LeaveTypes.createLeaveTypeForm');
    }

    public function viewLeaveTypeList()
    {
        return view('Hr.LeaveTypes.viewLeaveTypeList');
    }

    public function editLeaveTypeForm()
    {
        $leave_type = LeaveType::where([['id','=',Input::get('id')]])->first();
        return view('Hr.LeaveTypes.editLeaveTypeForm', compact('leave_type'));
    }

    public function createCategoryForm()
    {
        return view('Hr.Categories.createCategoryForm');
    }

    public function viewCategoryList()
    {
        return view('Hr.Categories.viewCategoryList');
    }

    public function editCategoryDetailForm()
    {
        $category = EmployeeCategory::where([['id', '=',Input::get('id')]])->first();
        return view('Hr.Categories.editCategoryDetailForm',compact('category'));
    }

    public function createLoanTypeForm(){

        return view('Hr.LoanTypes.createLoanTypeForm');
    }

    public function viewLoanTypeList()
    {
        return view('Hr.LoanTypes.viewLoanTypeList');
    }

    public function editLoanTypeForm(){
        return view('Hr.LoanTypes.editLoanTypeForm');
    }

//    public function createAdvanceTypeForm()
//    {
//        return view('Hr.createAdvanceTypeForm');
//    }
//
//    public function viewAdvanceTypeList()
//    {
//        $AdvanceTypes = AdvanceType::where([['status','=', 1],['company_id', '=', Input::get('m')]])->get();
//        return view('Hr.viewAdvanceTypeList', compact('AdvanceTypes'));
//    }
//
//    public function editAdvanceTypeForm()
//    {
//        return view('Hr.editAdvanceTypeForm');
//    }

    public function createAllowanceTypeForm()
    {
        return view('Hr.AllowanceType.createAllowanceTypeForm');
    }

    public function viewAllowanceTypeList()
    {
        return view('Hr.AllowanceType.viewAllowanceTypeList');
    }

    public function editAllowanceTypeForm()
    {
        $allowance_type = AllowanceType::where([['id', '=', Input::get('id')]])->first();
        return view('Hr.AllowanceType.editAllowanceTypeForm', compact('allowance_type'));
    }

//    public function createShiftTypeForm(){
//        return view('Hr.createShiftTypeForm');
//    }
//
//    public function editShiftTypeForm(){
//        return view('Hr.editShiftTypeForm');
//    }
//
//    public function createHiringRequestAddForm(){
//
//        $departments = Department::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
//        $JobTypes = JobType::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
//        $Designations = Designation::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
//        $Qualifications = Qualification::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
//        $ShiftTypes = ShiftType::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
//        return view('Hr.createHiringRequestAddForm',compact('departments','JobTypes','Designations','Qualifications','ShiftTypes'));
//    }
//
//    public function viewHiringRequestList(){
//
//        $m = Input::get('m');
//        CommonHelper::companyDatabaseConnection($m);
//        $RequestHiring = RequestHiring::all()->toArray();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.viewHiringRequestList', ['RequestHiring' => $RequestHiring]);
//    }
//
//    public function editHiringRequestForm(){
//
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $hiringRequestDetail = RequestHiring::where([['id','=',Input::get('id')]])->first();
//        CommonHelper::reconnectMasterDatabase();
//        $departments = Department::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
//        $JobTypes = JobType::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
//        $Designations = Designation::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
//        $Qualifications = Qualification::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
//        $ShiftTypes = ShiftType::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
//
//        return view('Hr.editHiringRequestForm',compact('hiringRequestDetail','departments','JobTypes','Designations','Qualifications','ShiftTypes'));
//    }

    public function createEmployeeForm()
    {
        $company_id = Input::get('m');
        $department = Cache::get('department');
        $designation = Cache::get('designation');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $category = Cache::get('employee_category');
        $leaves_policy = Cache::get('leaves_policy');
        $job_type = Cache::get('job_type');
        $marital_status = Cache::get('marital_status');
        $eobi = Cache::get('eobi');
        $employee_equipment = Cache::get('employee_equipments');
        $grade_type = Cache::get('grades');
        $degree_type = Cache::get('degree_type');

        $banks = DB::table('banks')->where([['status','=','1'],['company_id', '=', Input::get('m')]])->get();
        $menu_privileges = Role::where([['status','=',1]])->orderBy('id')->get();

        CommonHelper::companyDatabaseConnection($company_id);
        $reporting_to = Employee::select('id','emp_id','emp_name')->where('status','=',1)->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.Employees.createEmployeeForm',compact('department','designation','projects','leaves_policy',
            'job_type','marital_status','degree_type','banks','category','grade_type','eobi',
            'employee_equipment','menu_privileges','reporting_to','locations'));
    }

    public function editEmployeeDetailForm($id, $CompanyId)
    {
        $company_id = Input::get('m');
        $department = Cache::get('department');
        $sub_department = Cache::get('sub_department');
        $designation = Cache::get('designation');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $category = Cache::get('employee_category');
        $leaves_policy = Cache::get('leaves_policy');
        $job_type = Cache::get('job_type');
        $marital_status = Cache::get('marital_status');
        $eobi = Cache::get('eobi');
        $employee_equipment = Cache::get('employee_equipments');
        $grade_type = Cache::get('grades');
        $degree_type = Cache::get('degree_type');

        $banks = DB::table('banks')->where([['status','=','1'],['company_id', '=', Input::get('m')]])->get();

        CommonHelper::companyDatabaseConnection($CompanyId);
        $reporting_to = Employee::select('id','emp_id','emp_name')->where('status','=',1)->get();
        $employee_detail = Employee::where([['id','=',$id]])->first();

        $emp_id = $employee_detail->emp_id;
        $employee_family_detail = EmployeeFamilyData::where([['employee_id','=',$id]]);
        $employee_bank_detail = EmployeeBankData::where([['status','=', 1],['employee_id','=',$id]])->first();
        $employee_educational_detail = EmployeeEducationalData::where([['employee_id','=',$id]]);
        $employee_language_proficiency = EmployeeLanguageProficiency::where([['employee_id','=',$id]]);
        $employee_health_data = EmployeeHealthData::where([['employee_id','=',$id]]);
        $employee_activity_data = EmployeeActivityData::where([['employee_id','=',$id]]);
        $employee_work_experience = EmployeeWorkExperience::where([['employee_id','=',$id]]);
        $employee_reference_data = EmployeeReferenceData::where([['employee_id','=',$id]]);
        $employee_kins_data = EmployeeKinsData::where([['employee_id','=',$id]]);
        $employee_relatives_data = EmployeeRelativesData::where([['employee_id','=',$id]]);
        $employee_other_details = EmployeeOtherDetails::where([['employee_id','=',$id],['status','=',1]]);
        $employee_eobi_copy = Employee::where([['id','=',$id],['status','=',1],['eobi_path', '!=', null]]);
        $employee_work_experience_doc = EmployeeWorkExperience::where([['employee_id','=',$id],['status','=',1],['work_exp_path', '!=', null]]);
        $employee_equipment_assigned = EmployeeEquipments::where([['employee_id','=',$id],['status','=',1]]);

        $employee_locations = EmployeeMultipleLocations::where([['status', '=', 1],['employee_id', '=', $id]])->select('location_id','project_id')->get();
        $location_array = [];
        $project_array = [];
        foreach ($employee_locations as $key => $value) {
            $location_array[] = $value->location_id;
            $project_array[] = $value->project_id;
        }
        
        $cv = [];
        $cnic = [];
        $education = [];
        $investment_disclosure = [];
        $confidentially_form = [];
        $code_of_conduct = [];
        $non_disclosure_agreement = [];
        $company_service_rules = [];
        $clearance_letter = [];
        $fit_criteria_form = [];
        $passport = [];

        $employee_documents = EmployeeDocuments::where([['employee_id', '=', $id], ['status','=', 1]])->get();
        foreach($employee_documents as $doc){
            if($doc->type == 'cv'){
                $cv[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'education'){
                $education[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'cnic'){
                $cnic[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'investment_disclosure'){
                $investment_disclosure[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'confidentially_form'){
                $confidentially_form[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'code_of_conduct'){
                $code_of_conduct[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'non_disclosure_agreement'){
                $non_disclosure_agreement[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'company_service_rules'){
                $company_service_rules[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'clearance_letter'){
                $clearance_letter[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'fit_criteria_form'){
                $fit_criteria_form[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
            if($doc->type == 'passport'){
                $passport[] = array('path'=>$doc->file_path,'name'=>$doc->file_name,'file_type'=>$doc->file_type,'type'=>$doc->type);
            }
        }
        CommonHelper::reconnectMasterDatabase();

        $login_credentials = '';
        $MenuPrivilegeId = '';
        if($employee_detail->can_login == 'yes'):
            $login_credentials = DB::Table('users')->select('acc_type')->where([['company_id', '=', Input::get('m')],['emp_id', '=', $employee_detail->emp_id]])->first();
            $MenuPrivilegeId = Role::where([['id','=',$employee_detail->role_id],['status','=',1]])->value('id');
        endif;

        $userData = User::where('emp_id',$employee_detail->emp_id);
        $MenuPrivileges = Role::where([['status','=',1]])->orderBy('id')->get();

        return view('Hr.Employees.editEmployeeDetailForm'
            ,compact('department','sub_department','designation','projects','locations','category','leaves_policy','job_type',
                'marital_status','eobi','employee_equipment','grade_type','degree_type','banks','reporting_to',
                'employee_equipment_assigned','employee_family_detail','employee_bank_detail','employee_educational_detail',
                'employee_language_proficiency','employee_health_data','employee_activity_data','employee_work_experience',
                'employee_reference_data','employee_kins_data','employee_relatives_data','employee_other_details',
                'employee_eobi_copy','employee_work_experience_doc','cv','education','cnic','investment_disclosure',
                'confidentially_form','code_of_conduct','non_disclosure_agreement','company_service_rules',
                'clearance_letter','fit_criteria_form','passport','login_credentials','MenuPrivilegeId','userData',
                'MenuPrivileges','employee_detail','employee_documents','location_array','project_array'));
    }

    public function sendEmail(){

        CommonHelper::companyDatabaseConnection('12');
        $email_queue = DB::Table('email_queue')->where([['status', '=', 1]])->orderBy('emp_id');
        CommonHelper::reconnectMasterDatabase();

        if ($email_queue->count() > 0):

            foreach ($email_queue->get() as $key => $value):

                CommonHelper::companyDatabaseConnection('12');
                $cnic = Employee::where([['emp_id', '=', $value->emp_id]])->select('emp_cnic', 'professional_email');


                CommonHelper::reconnectMasterDatabase();

                if($cnic->value('professional_email') == '' || $cnic->value('professional_email') == '-' || $cnic->value('professional_email') == null):

                else:

                    CommonHelper::companyDatabaseConnection('12');
                    $emp_name = Employee::select('emp_name')->where([['emp_id', '=', $value->emp_id]])->value('emp_name');
                    CommonHelper::reconnectMasterDatabase();

                    Mail::send('Hr.test', ['emp_name' => $emp_name, 'month' => $value->month , 'year' => $value->year], function ($message) use ($value, $cnic) {

                        CommonHelper::companyDatabaseConnection('12');


                        //$payslip_data = DB::Table('payslip')->where([['month', '=', $value->month], ['year', '=', $value->year], ['emp_id', '=', $value->emp_id]])->first();

                        $payslip_data = DB::table('payslip')
                            ->where([['payslip.month','=',$value->month],['payslip.year','=',$value->year],['payslip.emp_id','=',$value->emp_id]])
                            ->join('employee', 'employee.emp_id', '=', 'payslip.emp_id')
                            ->select('payslip.*','employee.bank_account','employee.bank_id','employee.emp_father_name','employee.emp_department_id', 'employee.emp_name', 'employee.emp_cnic','employee.emp_joining_date','employee.professional_email', 'employee.designation_id')
                            ->get()->toArray();


                        $provident_fund=DB::select(DB::raw("SELECT sum(amount) as amount FROM pf where status=1 and emp_id='".$value->emp_id."'"));
                        CommonHelper::reconnectMasterDatabase();
                        $leaves_policy = DB::table('leaves_policy')
                            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
                            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
                            ->select('leaves_policy.*', 'leaves_data.*')
                            ->where([['leaves_policy.id', '=', 21]])
                            ->get();

                        $payslip_data =$payslip_data[0];

                        $monthNum  = $value->month;
                        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                        $monthName = $dateObj->format('F');


                        $pdf = PDF::loadView('Hr.pdf', compact('payslip_data','leaves_policy','provident_fund'));
                        $pdf->setEncryption(str_replace(["-", "–"], '', $cnic->value('emp_cnic')));

                        $address = 'epay@vis.com';
                        $subject = 'Payslip for the month of '.$monthName." ".$value->year;
                        $name = 'VIS';
                        //  $message->to('muhammad.saad.aslam@vis.com.pk', 'Test');

                        //$message->to('firebaseapplications10@gmail.com', 'Test');
                        $message->to($cnic->value('professional_email'), 'Payslip');
                        $message->subject('Payslip for the month of '.$monthName." ".$value->year);
                        $message->from($address, $name);
                        $message->cc($address, $name);
                        $message->bcc($address, $name);
                        $message->replyTo($address, $name);
                        $message->subject($subject);
                        $message->attachData($pdf->output(), "Payslip_" . $value->month . "_$value->year .pdf");

                    });

                endif;
                CommonHelper::companyDatabaseConnection('12');
                DB::table('email_queue')->where([['emp_id', '=', $value->emp_id]])->delete();
                CommonHelper::reconnectMasterDatabase();


            endforeach;

        endif;

    }

    public function viewEmployeeList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $designations = Cache::get('designation');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.Employees.viewEmployeeList',compact('departments','sub_departments','category',
            'projects','locations','designations','employees'));
    }

    public function viewEmployeeReportList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $designations = Cache::get('designation');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        $job_type = Cache::get('job_type');
        return view('Hr.Employees.viewEmployeeReportList',compact('departments','sub_departments','category',
            'projects','locations','designations','employees','job_type'));
    }

    public function viewEmployeeLogDetail($id){


        //   if(Auth::user()->acc_type == 'user') {die('Nice Trick @xx ');}
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_id= $id;
        $employee = Employee::where([['status', '=', 1],['emp_id','=',$emp_id]])->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.Employees.viewEmployeeLogDetail',compact('employee','emp_id'));
    }

    public function viewUseAccountsList()
    {
        $users = DB::table('users As u')->where([['u.status', '!=', '2']])
            ->leftJoin('menu_privileges AS mp', 'mp.employee_id','=', 'u.employee_id')
            ->leftJoin('roles AS r', 'r.id','=', 'mp.role_id')
            ->select('u.*','r.role_name')->orderBy('id','asc')->get();
        return view('Hr.Employees.viewUserAccountsList',compact('users'));
    }

    public function editUserAccountDetailForm()
    {
        $users = DB::table('users As u')->where([['u.id','=',Input::get('id')]])
            ->join('menu_privileges AS mp', 'mp.employee_id','=', 'u.employee_id')
            ->join('roles AS r', 'r.id','=', 'mp.role_id')
            ->select('u.*','r.id AS role_id')->first();
        $roles = Role::where('status','=','1')->get();
        return view('Hr.Employees.editUserAccountDetailForm',compact('users','roles'));
    }

    public function uploadEmployeeFileForm()
    {
        $locations = Cache::get('locations');
        return view('Hr.Employees.uploadEmployeeFileForm',compact('locations'));

    }

    public function createManageAttendanceForm(){
        return view('Hr.Attendance.createManageAttendanceForm');
    }


    public function viewEmployeesAttendanceReport()
    {
        $m = Input::get('m');

        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $allowance_types = Cache::get('allowance_types');
        $locations = Cache::get('locations');


        return view('Hr.Attendance.viewEmployeesAttendanceReport',compact('departments','sub_departments','locations','category','projects','employees','allowance_types'));
    }

    public function ViewAttendanceProgress()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        $job_type = Cache::get('job_type');
        return view('Hr.Attendance.ViewAttendanceProgress',compact('departments','sub_departments','category','projects','locations','employees','job_type'));

    }

    public function createAttendanceRequestQueriesForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance_queries='';
        if(Auth::user()->acc_type=='client'){
            $attendance_queries=DB::table('attendance_queries')->where('status',1)->orderBy('id','desc')->get();
        }else{
            $attendance_queries=DB::table('attendance_queries')->where('emp_id',Auth::user()->emp_id)->where('status',1)->orderBy('id','desc')->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Attendance.createAttendanceRequestQueriesForm',compact('attendance_queries'));
    }


    public function viewEmployeeAttendanceList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.Attendance.viewEmployeeAttendanceList',compact('departments','sub_departments','locations','category','projects','employees'));
    }

    public function viewEmployeeAttendanceReport()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.Attendance.viewEmployeeAttendanceReport',compact('departments','sub_departments','locations','category','projects','employees'));
    }

    public function showLocationOnMap()
    {
        CommonHelper::companyDatabaseConnection('12');
        $explode=explode('=@',Input::get('id'));
        $id=$explode[0];
        $status=$explode[1];
        
        if($status=='clock_in'){
            $latitude = DB::table('attendance_logs')->select('latitude')->where([['status', '=', 1],['id', '=', $id]])->where('type','clock_in')->value('latitude');
            $longitude = DB::table('attendance_logs')->select('longitude')->where([['status', '=', 1],['id', '=', $id]])->where('type','clock_in')->value('longitude');
        }else{
            $latitude = DB::table('attendance_logs')->select('latitude')->where([['status', '=', 1],['id', '=', $id]])->where('type','clock_out')->value('latitude');
            $longitude = DB::table('attendance_logs')->select('longitude')->where([['status', '=', 1],['id', '=', $id]])->where('type','clock_out')->value('longitude');
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.showLocationOnMap',compact('latitude','longitude'));
    }
    

    public function createPayrollForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        $job_type = Cache::get('job_type');
        return view('Hr.Payroll.createPayrollForm',compact('departments','sub_departments','category','projects','locations','employees','job_type'));
    }

    public function viewPayrollList(){


        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();

        return view('Hr.Payroll.viewPayrollList', compact('SubDepartment','Department'));
    }

    public function viewConcileReport()
    {
        return view('Hr.Payroll.viewConcileReport');
    }

    public function viewPayrollReport()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        $job_type = Cache::get('job_type');

        return view('Hr.Payroll.viewPayrollReport',compact('departments','sub_departments','locations','category','projects','employees','job_type'));
    }

    public function viewBankReportForm()
    {
        $banks = DB::table('banks')->where([['status','=','1'],['company_id', '=', Input::get('m')]])->get();
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');

        return view('Hr.Payroll.viewBankReportForm',compact('banks','departments','locations','category','projects','employees'));

    }

    public function companyWisePayrollReport()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->orderBy('order_by_no', 'asc')->get()->toArray();
        return view('Hr.Payroll.companyWisePayrollReport',compact('companies'));
    }

    public function emailPayslips()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.emailPayslips',compact('departments','locations','category','projects','employees'));
    }

    public function bonusPayslipsEmail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('emp_id','emp_name','emp_father_name')->where('status',1)->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.bonusEmailPayslips', compact('employee'));
    }

    public function viewPayslipPdf($m,$month,$year,$employee_id)
    {
        CommonHelper::companyDatabaseConnection($m);
        $payslip_data = DB::table('payslip')
            ->where([['payslip.month','=',$month],['payslip.year','=',$year],['payslip.employee_id','=',$employee_id],['payslip.status','=',1]])
            ->join('employee', 'employee.id', '=', 'payslip.employee_id')
            ->select('payslip.*','employee.emp_father_name','employee.department_id', 'employee.emp_name', 'employee.cnic','employee.joining_date','employee.official_email', 'employee.designation_id')
            
            ->get()->toArray();

        CommonHelper::reconnectMasterDatabase();
        $payslip_data = $payslip_data[0];

        $designations = Cache::get('designation');
        $departments = Cache::get('department');

        return view('Hr.pdf',compact('payslip_data','designations','departments'));
    }

    public function viewBonusPayslipPdf($m,$month,$year,$emp_id)
    {
        CommonHelper::companyDatabaseConnection($m);
        $payslip_data = DB::table('bonus_issue')
            ->where([['bonus_issue.bonus_month','=',$month],['bonus_issue.bonus_year','=',$year],['bonus_issue.employee_id','=',$emp_id],['bonus_issue.bonus_pay','=',2]])
            ->join('employee', 'employee.id', '=', 'bonus_issue.employee_id')
            ->select('bonus_issue.*','employee.emp_father_name','employee.department_id', 'employee.emp_id','employee.emp_name', 'employee.cnic','employee.joining_date','employee.official_email', 'employee.designation_id')
            ->get()->toArray();

        $pay_month=$year."-".$month."-"."31";
        CommonHelper::reconnectMasterDatabase();


        $payslip_data =$payslip_data[0];

        return view('Hr.bonusPdf',compact('payslip_data','m'));
    }

//    public function generatePayslip($m,$month,$year,$emp_id)
//    {
//        CommonHelper::companyDatabaseConnection($m);
//        $payslip_data = DB::table('payslip')
//            ->where([['payslip.month','=',$month],['payslip.year','=',$year],['payslip.employee_id','=',$emp_id]])
//            ->join('employee', 'employee.id', '=', 'payslip.employee_id')
//            ->select('payslip.*','employee.emp_father_name','employee.department_id', 'employee.emp_name', 'employee.cnic','employee.joining_date','employee.official_email', 'employee.designation_id')
//            ->get()->toArray();
//        CommonHelper::reconnectMasterDatabase();
//
//        $payslip_data = $payslip_data[0];
//        $designations = Cache::get('designation');
//        $departments = Cache::get('department');
//
//        return view('Hr.pdf',compact('payslip_data','designations','departments'));
//    }

    public function generateBonusPayslip($m,$month,$year,$emp_id)
    {
        CommonHelper::companyDatabaseConnection($m);
        $payslip_data = DB::table('bonus_issue')
            ->where([['bonus_issue.bonus_month','=',$month],['bonus_issue.bonus_year','=',$year],['bonus_issue.employee_id','=',$emp_id],['bonus_issue.bonus_pay','=',2]])
            ->join('employee', 'employee.id', '=', 'bonus_issue.employee_id')
            ->select('bonus_issue.*','employee.emp_father_name','employee.department_id', 'employee.emp_id','employee.emp_name', 'employee.cnic','employee.joining_date','employee.official_email', 'employee.designation_id')
            ->get()->toArray();
        CommonHelper::reconnectMasterDatabase();

        $payslip_data =$payslip_data[0];

        return view('Hr.bonusPdf',compact('payslip_data','m'));
    }

    public function downloadIncrementLetterPdf($id,$emp_id)
    {

        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->generatePromotionLetter($id,$emp_id));


        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
//        $pdf = PDF::loadView('pdf',compact('payslip_data'));
//        return $pdf->download($emp_code."_".$month."_".$year.".pdf");
//
//        $pdf = PDF::loadView('pdf', compact('payslip_data'));
//        return $pdf->download($emp_code."-".$month."-".$year.".pdf");
    }

    public function generatePromotionLetter($id,$emp_id)
    {
        $type='';
        return view('Hr.Promotions.promotionLetterForm',compact('id','emp_id','type'));
    }
    public function viewEmployeePromotionLetter($id,$emp_id)
    {
        $type='view';
        return view('Hr.Promotions.promotionLetterForm',compact('id','emp_id','type'));
    }

    public function downloadPayslipPdf($m,$month,$year,$employee_id)
    {
        CommonHelper::companyDatabaseConnection($m);
        $payslip_data = DB::table('payslip')
            ->where([['payslip.month','=',$month],['payslip.year','=',$year],['payslip.employee_id','=',$employee_id]])
            ->join('employee', 'employee.id', '=', 'payslip.employee_id')
            ->select('payslip.*','employee.emp_father_name','employee.department_id', 'employee.emp_name', 'employee.cnic','employee.joining_date','employee.official_email', 'employee.designation_id')
            ->get()->toArray();
        CommonHelper::reconnectMasterDatabase();

        $payslip_data = $payslip_data[0];
        $designations = Cache::get('designation');
        $departments = Cache::get('department');

        $pdf = PDF::loadView('Hr.pdf', compact('payslip_data','designations','departments'));
        return $pdf->download($payslip_data->emp_id."-".$month."-".$year.".pdf");
    }

    public function downloadBonusPayslipPdf($m,$month,$year,$emp_code)
    {

        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->generateBonusPayslip($m,$month,$year,$emp_code));


        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
//        $pdf = PDF::loadView('pdf',compact('payslip_data'));
//        return $pdf->download($emp_code."_".$month."_".$year.".pdf");
//
//        $pdf = PDF::loadView('pdf', compact('payslip_data'));
//        return $pdf->download($emp_code."-".$month."-".$year.".pdf");
    }


    public function createMaritalStatusForm()
    {
        return view('Hr.MaritalStatus.createMaritalStatusForm');

    }

    public function editMaritalStatusForm()
    {
        $marital_status = MaritalStatus::where([['id','=',Input::get('id')]])->first();
        return view('Hr.MaritalStatus.editMaritalStatusForm', compact('marital_status'));
    }

    public function viewMaritalStatusList()
    {
        return view('Hr.MaritalStatus.viewMaritalStatusList');
    }

    public function createAllowanceForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $allowance_types = Cache::get('allowance_types');
        $locations = Cache::get('locations');

        return view('Hr.Allowances.createAllowanceForm',compact('departments','locations','sub_departments','category','projects','employees','allowance_types'));
    }

    public function viewAllowanceList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $allowance_types = Cache::get('allowance_types');
        $locations = Cache::get('locations');

        return view('Hr.Allowances.viewAllowanceList',compact('departments','sub_departments','category','locations','projects','employees','allowance_types'));
    }

    public function editAllowanceDetailForm()
    {
        $allowance_types = Cache::get('allowance_types');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowance = Allowance::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Allowances.editAllowanceDetailForm',compact('allowance','allowance_types'));
    }

    public function createDeductionForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.Deductions.createDeductionForm',compact('departments','sub_departments','locations','category','projects','employees'));
    }

    public function viewDeductionList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');

        return view('Hr.Deductions.viewDeductionList',compact('departments','locations','sub_departments','category','projects','employees'));
    }

    public function editDeductionDetailForm()
    {
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $deduction = Deduction::where([['id','=',Input::get('id')]])->orderBy('id')->first();
        $employees = Employee::where('status', '=', 1)
            ->where('sub_department_id','=',$deduction->sub_department_id)->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Deductions.editDeductionDetailForm',compact('employees','deduction','departments','subdepartments'));
    }

    public function createAdvanceSalaryForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.AdvanceSalary.createAdvanceSalaryForm',compact('departments','sub_departments','locations','category','projects','employees'));
    }

    public function viewAdvanceSalaryList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.AdvanceSalary.viewAdvanceSalaryList',compact('departments','sub_departments','locations','category','projects','employees'));
    }

    public function editAdvanceSalaryDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $advance_salary = AdvanceSalary::select('*')->where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AdvanceSalary.editAdvanceSalaryDetailForm',compact('advance_salary'));
    }

    public function createLeavesPolicyForm()
    {
        $leaves_types = Cache::get('leave_type');
        return view('Hr.LeavesPolicy.createLeavesPolicyForm',compact('leaves_types'));
    }

    public function createManualLeaves()
    {
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        return view('Hr.LeavesPolicy.createManualLeaves',compact('department_id','sub_department_id'));
    }

    public function viewLeavesPolicyList()
    {
        $leavesPolicy = LeavesPolicy::where([['status', '=', 1]])->orderBy('id', 'desc')->get();;
        return view('Hr.LeavesPolicy.viewLeavesPolicyList',compact('leavesPolicy'));
    }

    public function editLeavesPolicyDetailForm()
    {
        $leave_type =   LeaveType::where([['status','=',1]])->get();
        $leave_policy = LeavesPolicy::where([['id','=',Input::get('id')]])->first();
        $leave_data =   LeavesData::where([['leaves_policy_id','=',Input::get('id')]])->get();
        return view('Hr.LeavesPolicy.editLeavesPolicyDetailForm',compact('leave_type','leave_policy','leave_data'));
    }

//    public function createVehicleTypeForm()
//    {
//        return view('Hr.createVehicleTypeForm');
//    }
//
//    public function viewVehicleTypeList()
//    {
//        $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
//        return view('Hr.viewVehicleTypeList',compact('vehicleType'));
//    }
//
//    public function editVehicleTypeDetailForm()
//    {
//        $vehicleType = VehicleType::where([['id','=',Input::get('id')]])->get(['vehicle_type_name','vehicle_type_cc'])->first();
//        return view('Hr.editVehicleTypeDetailForm',compact('vehicleType'));
//    }

    public function assignWorkingHoursPolicyForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        $workingHoursPolicy = WorkingHoursPolicy::where([['status','=', 1]])->get();
        return view('Hr.WorkingHoursPolicy.assignWorkingHoursPolicyForm',compact('departments','locations','sub_departments','category','projects','employees','workingHoursPolicy'));
    }

    public function assignRosterPolicy()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        $workingHoursPolicy = WorkingHoursPolicy::where([['status','=', 1]])->get();
        return view('Hr.WorkingHoursPolicy.assignRosterPolicy',compact('departments','locations','sub_departments','category','projects','employees','workingHoursPolicy'));
    }


    public function createWorkingHoursPolicyDetailForm()
    {
        return view('Hr.WorkingHoursPolicy.createWorkingHoursPolicyDetailForm');
    }

    public function createHolidaysForm()
    {
        return view('Hr.Holidays.createHolidaysForm');
    }

    public function viewHolidaysList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::orderBy('holiday_date')->orderBy('id')->where('status',1)->get();
        CommonHelper::reconnectMasterDatabase();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1'],])->orderBy('id')->get();
        return view('Hr.Holidays.viewHolidaysList',compact('holidays','departments'));
    }

    public function editHolidaysDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidaysDetail = Holidays::find(Input::get('id'))->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Holidays.editHolidaysDetailForm',compact('holidaysDetail'));
    }

    public function viewAssignedWorkingHoursPolicyList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.WorkingHoursPolicy.viewAssignedWorkingHoursPolicyList',compact('departments','locations','sub_departments','category','projects','employees'));
    }

    public function viewWorkingHoursPolicyList()
    {
        $workingHoursPolicyList = DB::table('working_hours_policy')->get();
        return view('Hr.WorkingHoursPolicy.viewWorkingHoursPolicyList',compact('workingHoursPolicyList'));
    }

//    public function createCarPolicyForm()
//    {
//        $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
//        $designation = Designation::where([['company_id','=',Input::get('m')]])->get();
//        return view('Hr.createCarPolicyForm',compact('vehicleType','designation'));
//    }

//    public function viewCarPolicyList()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $carPolicy = CarPolicy::all()->toArray();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.viewCarPolicyList',compact('carPolicy'));
//    }
//
//    public function viewCarPolicyCriteria()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $carPolicy = CarPolicy::all()->toArray();
//        CommonHelper::reconnectMasterDatabase();
//        $departments = Department::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
//        return view('Hr.viewCarPolicyCriteria',compact('departments','carPolicy'));
//    }
//
//    public function editCarPolicyDetailForm()
//    {   $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
//        $designation = Designation::where([['company_id','=',Input::get('m')]])->get();
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $carPolicy = CarPolicy::where([['id','=',Input::get('id')]])->first();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.editCarPolicyDetailForm',compact('carPolicy','vehicleType','designation'));
//    }


    public function createLoanRequestForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        $loan_types = Cache::get('loan_type');
        return view('Hr.loanRequests.createLoanRequestForm',compact('departments','locations','sub_departments','category','projects','employees','loan_types'));
    }

    public function viewLoanRequestList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.loanRequests.viewLoanRequestList',compact('departments','locations','sub_departments','category','projects','locations','employees'));
    }

    public function editLoanRequestDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loan_request = LoanRequest::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $loan_types = Cache::get('loan_type');
        return view('Hr.loanRequests.editLoanRequestDetailForm',compact('loan_types','loan_request'));
    }

    public function createStopPaymentForm()
    {
        $loan_id = Input::get('id');
        return view('Hr.loanRequests.createStopPaymentForm',compact('loan_id'));
    }

    public function createLoanTopUpForm()
    {
        $loan_id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loan_top_up = DB::table('loan_top_up')->where([['loan_id', '=', $loan_id],['status','=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.loanRequests.createLoanTopUpForm', compact('loan_top_up','loan_id'));
    }

    public function createLoanAdjustmentForm()
    {
        $loan_id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loan_adjustment = DB::table('loan_adjustment')->where([['loan_id', '=', $loan_id],['status','=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.loanRequests.createLoanAdjustmentForm', compact('loan_adjustment','loan_id'));
    }

    public function viewLoanReportForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.loanRequests.viewLoanReportForm', compact('departments','locations','sub_departments','category','projects','employees'));
    }

    public function createEOBIForm()
    {
        return view('Hr.EOBI.createEOBIForm');
    }

    public function viewEOBIList()
    {
        return view('Hr.EOBI.viewEOBIList');
    }

    public function editEOBIDetailForm()
    {
        $eobi = Eobi::select('id','EOBI_name','EOBI_amount','month_year')->where([['id','=',Input::get('id')],['company_id','=',Input::get('m')]])->first();
        return view('Hr.EOBI.editEOBIDetailForm',compact('eobi'));
    }

    public function createTaxesForm()
    {
        return view('Hr.Taxes.createTaxesForm');
    }

    public function viewTaxesList()
    {
        $tax = Tax::where([['status', '=', 1]])->get();
        return view('Hr.Taxes.viewTaxesList',compact('tax'));

    }
    /*  public function editTaxesDetailForm()
      {
          $tax = Tax::where([['id','=',Input::get('id')],['company_id','=',Input::get('m')]])->first();
          return view('Hr.editTaxesDetailForm',compact('tax'));
      }
  */
    public function editTaxesDetailForm()
    {
        $tax_id = Input::get('id');
        $tax_slabs = DB::table('tax_slabs')->where([['tax_id','=',$tax_id]])->orderBy('salary_range_from','asc');
        $tax = Tax::where([['id','=',$tax_id]])->first();
        return view('Hr.Taxes.editTaxesDetailForm',compact('tax','tax_slabs','tax_id'));
    }
    public function viewEmployeeJoiningReport()
    {
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $designations = Designation::where([['company_id','=',Input::get('m')],['status','=', 1]])->get();
        return view('Hr.Employees.viewEmployeeJoiningReport',compact('SubDepartment','Department','designations'));
    }


    public function viewTaxCriteria()
    {
        $departments = Department::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        $taxes = Tax::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        return view('Hr.Taxes.viewTaxCriteria',compact('departments','taxes'));
    }

    public function createBonusForm()
    {
        return view('Hr.Bonus.createBonusForm');
    }

    public function viewBonusList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus = Bonus::where([['status','=','1']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Bonus.viewBonusList',compact('bonus'));
    }

    public function viewEmployeeBonusReportForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');

        return view('Hr.Bonus.viewEmployeeBonusReportForm',compact('departments','locations','sub_departments','category','projects','employees'));

    }


    public function viewPfWithdrawalList(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $pf_withdrawal = DB::table('pf_withdrawal')->where('status',1)->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.ProvidentFund.viewPfWithdrawalList',compact('pf_withdrawal'));
    }

    public function viewPfProfitList(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $pf_profit = DB::table('pf_profit')->where('status',1)->orderBy('id');
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.ProvidentFund.viewPfProfitList',compact('pf_profit'));
    }


    public function createPfWithdrawalForm()
    {

        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.ProvidentFund.createPfWithdrawalForm',compact('departments','sub_departments','locations','category','projects','employees'));


    }

    public function createPfProfitForm(){

        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        return view('Hr.ProvidentFund.createPfProfitForm',compact('Department','SubDepartment'));
    }



    public function editPfProfitDetailForm(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $pf_profit= DB::table('pf_profit')->where('status',1)->where('id',Input::get('id'))->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.ProvidentFund.editPfProfitDetailForm',compact('pf_profit'));
    }

    public function editPfWithdrawalDetailForm(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $pf_withdrawal= DB::table('pf_withdrawal')->where('status',1)->where('id',Input::get('id'))->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.ProvidentFund.editPfWithdrawalDetailForm',compact('pf_withdrawal'));
    }




    public function viewPfForEmployee(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $list=DB::table('pf')->where([['status','=',1],['month','=','00'],['year','=','00']])->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Payroll.viewPfForEmployee',compact('list'));
    }
    public function editIncomeTaxDetailForm(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id=Input::get('id');
        $income_tax=DB::table('income_tax')->where('status',1)->where('id',$id)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Payroll.editIncomeTaxDetailForm',compact('income_tax'));
    }


    public function createPfForEmployee()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.Payroll.createPfForEmployee',compact('departments','sub_departments','locations','category','projects','employees'));

    }


    public function editBonusDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus = Bonus::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Bonus.editBonusDetailForm',compact('bonus'));
    }


    public function viewBonusBankReportForm(){

        $banks=DB::table('banks')->where([['status','=','1'],['company_id', '=', Input::get('m')]])
            ->whereBetween('id',[3,4])
            ->get();
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        return view('Hr.Bonus.viewBonusBankReportForm',compact('banks','departments','sub_departments','category','projects','employees'));
    }


    public function IssueBonusDetailForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus_list = Bonus::where([['status','=','1']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Bonus.IssueBonusDetailForm',compact('bonus_list','locations','departments','sub_departments','category','projects','employees'));
    }

    public function createLeaveApplicationForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $locations = Cache::get('locations');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        return view('Hr.LeaveApplication.createLeaveApplicationFormClient', compact('departments','locations','sub_departments','category',
            'projects','employees'));
//        if($accType == 'client')
//        {
//            $departments = Cache::get('department');
//            $sub_departments = Cache::get('sub_department');
//            $category = Cache::get('employee_category');
//            $locations = Cache::get('locations');
//            $projects = Cache::get('employee_projects');
//            $employees = Cache::get('employee');
//            return view('Hr.LeaveApplication.createLeaveApplicationFormClient', compact('departments','locations','sub_departments','category',
//                'projects','employees'));
//        }
//        else
//        {
//            CommonHelper::companyDatabaseConnection(Input::get('m'));
//            $emp = Employee::select('id','emp_id','leaves_policy_id')->where('emp_id','=',Auth::user()->emp_id)->first();
//
//            $attendance_machine_id = $emp->emp_id;
//            CommonHelper::reconnectMasterDatabase();
//            if($emp->emp_id == 201 || $emp->emp_id == 208)
//            {
//                $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//                $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//
//                $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
//                $unique_emp = '';
//                $emp_arr =array();
//                foreach ($companies as $value2):
//
//                    CommonHelper::companyDatabaseConnection($value2->id);
//                    $emp_name =  Employee::select('emp_name','emp_id')->where([['status','=',1]]);
//                    if($emp_name->count() > 0 ):
//                        foreach($emp_name->get() as $value):
//                            if(!in_array($value->emp_id,$emp_arr)):
//                                b[]=$value2->id."|".$value->emp_id."|".$value->emp_name;
//                            endif;
//                            $emp_arr[]=$value->emp_id;
//                        endforeach;
//                    endif;
//                    CommonHelper::reconnectMasterDatabase();
//                endforeach;
//
//                //return view('Hr.createPayslipForm',compact('departments','subdepartments'));
//                return view('Hr.LeaveApplication.createLeaveApplicationFormClient', compact('SubDepartment ','employeeProjects ','unique_emp'));
//            }
//            else if($emp->leaves_policy_id == '0'){
//                return view('Hr.LeaveApplication.createLeaveApplicationFormWithoutLeavePolicy');
//            }
//            else{
//                $leaves_policy = DB::table('leaves_policy')
//                    //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
//                    ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//                    ->select('leaves_policy.*', 'leaves_data.*')
//                    ->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
//                    ->get();
//
//
//                $leaves_policy_validatity = DB::table('leaves_policy')
//                    //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
//                    ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//                    ->select('leaves_policy.id', 'leaves_data.id')
//                    ->where([['leaves_policy.id', '=', $emp->leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
//                    ->count();
//
//                //echo Auth::user()->emp_id; die();
//                $total_leaves = DB::table("leaves_data")
//                    ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
//                    ->where([['leaves_policy_id', '=', $leaves_policy[0]->leaves_policy_id]])
//                    ->first();
//
//                $taken_leaves = DB::table("leave_application_data")
//                    ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
//                    ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
//                    ->where([['leave_application.emp_id', '=', $emp->emp_id], ['leave_application.status', '=', '1'],
//                        ['leave_application.approval_status', '=', '2']])
//                    ->first();
//
//                CommonHelper::companyDatabaseConnection(Input::get('m'));
//                $emp_data = Employee::where([['emp_id', '=', Auth::user()->emp_id]])->orderBy('id')->first();
//                CommonHelper::reconnectMasterDatabase();
//                $getCurrentLeavePolicyYear = date('Y',strtotime($leaves_policy[0]->policy_date_from));
//                $date = strtotime($getCurrentLeavePolicyYear.' -1 year');
//                $getPreviousLeavePolicyYear = date('Y', $date);
//                $getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear.'%');
//                $getPreviousUsedAnnualLeavesBalance = 0;
//                $getPreviousUsedCasualLeavesBalance = 0;
//                if($getPreviousLeavePolicy->count() > 0 ):
//                    // print_r($getPreviousLeavePolicyId->first()->id);
//                    $getPreviousLeavePolicyId=$getPreviousLeavePolicy->first();
//
//                    $getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',1],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
//                    $getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',3],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
//                    $getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
//                        ->select(DB::raw("SUM(no_of_days) as no_of_days"))
//                        ->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','1']])
//                        ->first();
//                    $getPreviousUsedCasualLeaves = DB::table("leave_application_data")
//                        ->select(DB::raw("SUM(no_of_days) as no_of_days"))
//                        ->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','3']])
//                        ->first();
//
//                    $getPreviousUsedAnnualLeavesBalance =  $getPreviousAnnualLeaves-$getPreviousUsedAnnualLeaves->no_of_days;
//                    $getPreviousUsedCasualLeavesBalance =$getPreviousCasualLeaves-$getPreviousUsedCasualLeaves->no_of_days;
//
//                endif;
//
//                //    return view('Hr.AjaxPages.viewLeaveApplicationClientForm', compact('getPreviousUsedAnnualLeavesBalance','getPreviousUsedCasualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves'));
//
//                return view('Hr.LeaveApplication.createLeaveApplicationForm', compact('getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves'));
//            }
//        }
    }

    public function addLeaveApplicationFrom()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $locations = Cache::get('locations');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $job_type = Cache::get('job_type');

        return view('Hr.LeaveApplication.addLeaveApplicationFrom', compact('departments','locations','sub_departments','category',
            'projects','employees','job_type'));
    }

    public function viewLeaveApplicationRequestList()
    {
        $m = Input::get('m');
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $leave_types = Cache::get('leave_type');
        $locations = Cache::get('locations');

        return view('Hr.LeaveApplication.viewLeaveApplicationRequestList', compact('leave_types','locations','departments','sub_departments','category','projects','employees'));
    }

    public function viewLeaveBalances()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.LeaveApplication.viewLeaveBalances', compact('departments','sub_departments','category','projects','employees','locations'));
       
    }

    public function viewLeaveApplicationList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_id = Employee::select('id')->where([['emp_id','=',Auth::user()->emp_id]])->value('id');
        CommonHelper::reconnectMasterDatabase();
        $leave_application_list = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
            ->select('leave_application.*','leave_application_data.no_of_days')
            ->where([['leave_application.employee_id', '=',$employee_id]])
            ->get();
        return view('Hr.LeaveApplication.viewLeaveApplicationList', compact('leave_application_list'));
    }

    public function editLeaveApplicationDetailForm()
    {
        $m = Input::get('m');
        $ids = explode('|', Input::get('id'));
        $leave_id = $ids[0];
        $employee_id = $ids[1];

        $leaveApplicationData = DB::table('leave_application AS la')
            ->join('leave_application_data AS lad', 'la.id', '=', 'lad.leave_application_id')
            ->select('la.*','lad.no_of_days','lad.from_date','lad.to_date','lad.first_second_half','lad.first_second_half_date')
            ->where([['la.id', '=',$leave_id]])
            ->first();

        $employees = Cache::get('employee');
        $employee = $employees[$employee_id];

        $leaves = HrHelper::viewLeaveBalances($employee_id,$employee->leaves_policy_id, 1);
        $leaves_policy = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $employee->leaves_policy_id]])
            ->orderBy('leaves_data.leave_type_id')->get();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = DB::table('holidays')->where('status',1)->get();
        CommonHelper::reconnectMasterDatabase();

        $reasons = DB::table('reasons')->where('status',1)->get();
        $departments = Cache::get('department');
        $leave_type = Cache::get('leave_type');

        return view('Hr.LeaveApplication.editLeaveApplicationDetailForm', compact('departments','leave_type','leaveApplicationData','employee', 'leaves_policy','leaves','holidays','reasons'));
    }


    public function ApplyLeaveApplicationDetailForm()
    {
        $m = Input::get('m');
        $result = explode('--',Input::get('id'));

        // echo "<pre>";
        // print_r($result);
        // exit();
        $data = [
            "id" => $result[0],
            "emp_name" => $result[1],
            "emp_id" => $result[2],
            "department_id" => $result[3],
            "working_hours_policy_id" => $result[4],
            "type" => $result[5],
            'from_date' => $result[6],
            "to_date" => $result[7],
            "total_days" => $result[8],
        ];
        $data = json_encode($data);
       
        $employee_id = $result[0];

        $employees = Cache::get('employee');
        $employee = $employees[$employee_id];

        $leaves = HrHelper::viewLeaveBalances($employee_id,$employee->leaves_policy_id, 1);
        $leaves_policy = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $employee->leaves_policy_id]])
            ->orderBy('leaves_data.leave_type_id')->get();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = DB::table('holidays')->where('status',1)->get();
        CommonHelper::reconnectMasterDatabase();

        $reasons = DB::table('reasons')->where('status',1)->get();
        $departments = Cache::get('department');
        $leave_type = Cache::get('leave_type');


        return view('Hr.LeaveApplication.applyLeaveApplicationForm', compact('departments','leave_type','employee','data', 'leaves_policy','leaves','holidays','reasons'));
    }
//    public function createEmployeeDepositForm()
//    {
//        $subdepartments = new SubDepartment;
//        $departments = Department::where('company_id','=',$_GET['m'])->orderBy('id')->get();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.createEmployeeDepositForm',compact('departments','subdepartments'));
//    }
//
//    public function editEmployeeDepositDetail()
//    {
//        $empDepositId = Input::get('id');
//        $subdepartments = new SubDepartment;
//        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $EmployeeDepositData = EmployeeDeposit::where('id','=',$empDepositId)->first();
//        $employee = Employee::all();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.AjaxPages.editEmployeeDepositDetail',compact('subdepartments','departments','EmployeeDepositData','employee'));
//    }
//
//    public function viewEmployeeDepositList()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $employeeDeposit = EmployeeDeposit::all();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.viewEmployeeDepositList',compact('employeeDeposit'));
//    }

    public function createEmployeeGradesForm()
    {
        return view('Hr.EmployeeGrades.createEmployeeGradesForm');
    }

    public function editEmployeeGradesDetailForm()
    {
        $grades = Grades::where([['status','=',1],['id','=',Input::get('id')]])->first();
        return view('Hr.EmployeeGrades.editEmployeeGradesDetailForm', compact('grades'));
    }

    public function viewEmployeeGradesList()
    {
        return view('Hr.EmployeeGrades.viewEmployeeGradesList');
    }

    public function createLocationsForm()
    {
        $projects = Cache::get('employee_projects');
        $cities = Cache::get('cities');
        return view('Hr.Locations.createLocationsForm', compact('projects','cities'));
    }

    public function editLocationsDetailForm()
    {
        $locations = Locations::where([['id','=', Input::get('id')]])->first();
        $projects = Cache::get('employee_projects');
        $cities = Cache::get('cities');
        return view('Hr.Locations.editLocationsDetailForm', compact('locations','projects','cities'));
    }
    public function editDesignationWiseLocation()
    {
        $data = DB::table('designation_wise_locations')->where([['id','=', Input::get('id')]])->first();
       
        return view('Hr.Locations.editDesignationWiseLocation', compact('data'));
    }

    public function viewLocationsList()
    {
        return view('Hr.Locations.viewLocationsList');
    }

    public function editEmployeeRegionsDetailForm()
    {
        $locations = Regions::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEmployeeRegionsDetailForm', compact('locations'));
    }

    public function createEmployeeDegreeTypeForm()
    {
        return view('Hr.EmployeeDegreeType.createEmployeeDegreeTypeForm');
    }

    public function viewEmployeeDegreeTypeList()
    {
        return view('Hr.EmployeeDegreeType.viewEmployeeDegreeTypeList');
    }

    public function editEmployeeDegreeTypeDetailForm()
    {
        $degree_type = DegreeType::where([['id','=', Input::get('id')]])->first();
        return view('Hr.EmployeeDegreeType.editEmployeeDegreeTypeDetailForm', compact('degree_type'));
    }

    public function createEmployeeExitClearanceForm()
    {
        $m = Input::get('m');
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.ExitClearance.createEmployeeExitClearanceForm',compact('departments','sub_departments','locations','category','projects','employees'));
    }

    public function viewEmployeeExitClearanceList()
    {
        return view('Hr.ExitClearance.viewEmployeeExitClearanceList');
    }

    public function editEmployeeExitClearanceDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $exit_employee_data = EmployeeExit::where([['id','=',$id]])->first();
        CommonHelper::reconnectMasterDatabase();

        $designations = Cache::get('designation');
        $departments = Cache::get('department');
        $employees = Cache::get('employee');
        return view('Hr.ExitClearance.editEmployeeExitClearanceDetailForm', compact('exit_employee_data','employees','designations','departments'));
    }

    public function createFinalSettlementForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Cache::get('employee');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.FinalSettlement.createFinalSettlementForm',compact('employees'));
    }

    public function viewFinalSettlementList()
    {
        $company_id = Input::get('m');
        CommonHelper::companyDatabaseConnection($company_id);
        $final_settlement_employees = FinalSettlement::where([['status', '=', 1]])->select('employee_id')->get();

        $empId = [];
        foreach ($final_settlement_employees as $key => $value):
            $empId[] = $value->employee_id;
        endforeach;
        $employee_search = Employee::select('id','emp_id','emp_name')
            ->whereIn('id',$empId)->orderBy('id','asc')->get();


        CommonHelper::reconnectMasterDatabase();
        return view('Hr.FinalSettlement.viewFinalSettlementList',compact('employee_search','company_id'));

    }

    public function editFinalSettlementDetailForm()
    {
        $company_id = Input::get('m');
        CommonHelper::companyDatabaseConnection($company_id);
        $final_settlement = DB::table('final_settlement')->where([['id', '=', Input::get('id')]])->first();
        //$final_settlement_deduction = DB::table('final_settlement')->where('final_settlement_id', '=', Input::get('id'));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.FinalSettlement.editFinalSettlementDetailForm',compact('company_id','final_settlement'));
    }

    public function downloadFnf($m,$id)
    {

        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->generateFnf($m,$id));


        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();

    }

    public function generateFnf($m,$id)
    {

        CommonHelper::companyDatabaseConnection($m);
        $final_settlement = DB::table('final_settlement')->where([['id', '=', $id]])->first();
        $type="download";

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewFinalSettlementDetail', compact('final_settlement','type'));
    }

//    public function createEmployeeIdCardRequest()
//    {
//
//        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
//        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
//        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
//        return view('Hr.createEmployeeIdCardRequest', compact('employee_category', 'employee_regions','Employee_projects'));
//    }
//
//    public function viewEmployeeIdCardRequestList()
//    {
//
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $employee_card_request = DB::table('employee')
//            ->join('employee_card_request', 'employee.emr_no', '=', 'employee_card_request.emr_no')
//            ->select('employee_card_request.*')
//            ->where([['employee.status','!=', 2],['employee_card_request.status', '=', 1]])
//            ->whereIn('employee.region_id',$regions)
//            ->orderBy('employee_card_request.id')
//            ->get();
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.viewEmployeeIdCardRequestList', compact('employee_card_request'));
//    }
//
//    public function editEmployeeIdCardRequestDetailForm()
//    {
//        $id = $_GET['id'];
//        $m 	= $_GET['m'];
//
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $employee_card_request=EmployeeCardRequest::where([['status', '=',1],['id', '=', $id]])->first();
//        $employee=Employee::where([['emr_no', '=', $employee_card_request->emr_no],['status', '=',1]])->select('img_path','emp_name', 'designation_id', 'emp_sub_department_id', 'emp_joining_date', 'emp_cnic')->first();
//        CommonHelper::reconnectMasterDatabase();
//
//        $designation=Designation::where([['status', '=', 1],['id',$employee->designation_id]])->select('designation_name')->first();
//        $sub_department=SubDepartment::where([['status', '=', 1],['id',$employee->emp_sub_department_id]])->select('sub_department_name')->first();
//
//        return view('Hr.editEmployeeIdCardRequestDetailForm', compact('designation', 'employee_card_request', 'employee', 'sub_department'));
//    }

    public function createEmployeePromotionForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        $designations = Cache::get('designation');
        $grade_type = Cache::get('grades');
        return view('Hr.Promotions.createEmployeePromotionForm',compact('departments','sub_departments','category',
            'projects','employees','designations','locations','grade_type'));
    }

    public function viewEmployeePromotionsList()
    {
        $m = Input::get('m');
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        $job_type = Cache::get('job_type');

        return view('Hr.Promotions.viewEmployeePromotionsList', compact('departments','sub_departments','category',
            'projects','employees','locations','job_type'));
    }

    public function editEmployeePromotionDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_promotion = EmployeePromotion::where('id', $id)->orderBy('id');
        CommonHelper::reconnectMasterDatabase();

        $designations = Cache::get('designation');
        $grade_type = Cache::get('grades');
        $employee = Cache::get('employee');
        return view('Hr.Promotions.editEmployeePromotionDetailForm', compact('designations', 'grade_type','employee_promotion','employee'));
    }

    public function bulkIncrementForm()
    {
        $m = Input::get('m');
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        $allowance_types = Cache::get('allowance_types');
        $job_type = Cache::get('job_type');

        return view('Hr.Promotions.bulkIncrementForm',compact('departments','sub_departments','locations','category','projects','employees','allowance_types','job_type'));
    }

    public function editEmployeeTransferDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_location = EmployeeTransfer::where([['id','=', $id]])->orderBy('id')->first();
        $employee_promotion_id = $employee_location->promotion_id;
        $employee_transfer_project_id = $employee_location->transfer_project_id;
        $count = 0;
        $promotionCount = 0;

        if($employee_promotion_id != 0)
        {
            $promotionCount = 1;
            $employee_promotion = EmployeePromotion::where([['id','=', $employee_promotion_id]])->orderBy('id')->first();
        }

        if($employee_transfer_project_id != 0)
        {
            $count = 2;
            $TransferEmployeeProject = TransferEmployeeProject::where([['id','=', $employee_transfer_project_id]])->orderBy('id')->first();
        }
        CommonHelper::reconnectMasterDatabase();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $location = Locations::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_grades = Grades::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.editEmployeeTransferDetailForm', compact('designation', 'employee_promotion','promotionCount','count', 'employee_location', 'location', 'employee_grades','Employee_projects','TransferEmployeeProject'));
    }

//    public function createEmployeeFuelDetailForm()
//    {
//        $subdepartments = new SubDepartment;
//        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//        $location = Locations::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//        return view('Hr.createEmployeeFuelDetailForm',compact('departments','subdepartments','designation','location'));
//    }
//
//    public function viewEmployeeFuel()
//    {
//        $subdepartments = new SubDepartment;
//        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//        return view('Hr.viewEmployeeFuel',compact('departments','subdepartments','designation'));
//    }
//
//    public function editEmployeeFuelDetailForm()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $employeeFuelData = EmployeeFuelData::where([['status', '=', 1],['id', '=', Input::get('id')]])->first();
//        CommonHelper::reconnectMasterDatabase();
//
//        return view('Hr.editEmployeeFuelDetailForm',compact('employeeFuelData'));
//    }

    public function createHrLetters()
    {

        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.HrLetters.createHrLetters', compact('department_id', 'sub_department_id','Employee_projects'));
    }

    public function viewHrLetters()
    {
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.HrLetters.viewHrLetters', compact('Department', 'SubDepartment'));
    }

    public function uploadLettersFile()
    {

        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $uploaded_letters_list = LetterFiles::where([['status', '=', 1]])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.HrLetters.uploadLettersFile', compact('department_id', 'sub_department_id', 'uploaded_letters_list','Employee_projects'));
    }

    public function createEquipmentsForm()
    {
        return view('Hr.Equipments.createEquipmentsForm');
    }

    public function viewEquipmentsList()
    {
        return view('Hr.Equipments.viewEquipmentsList');
    }

    public function editEquipmentDetailForm()
    {
        $equipments = Equipments::where([['id','=', Input::get('id')]])->first();
        return view('Hr.Equipments.editEquipmentDetailForm', compact('equipments'));
    }

    public function createEmployeeEquipmentsForm()
    {
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_projects  = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Equipments.createEmployeeEquipmentsForm', compact('departments','sub_department', 'employeeEquipment'));
    }

    public function viewEmployeeEquipmentsList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeEquipment = EmployeeEquipments::where([['status', '=', 1]])->orderBy('id')->get();

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.Equipments.viewEmployeeEquipmentsList', compact('employeeEquipment'));
    }

    public function editEmployeeEquipmentsDetailForm()
    {
        $equipment_detail = null;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_emr_no = EmployeeEquipments::where([['id','=', Input::get('id')]])->first();
        $emr_no = $employee_emr_no->emp_id;
        $employee = Employee::select('id','emp_id', 'eobi_number', 'eobi_path')->where([['emp_id','=',$emr_no],['status','!=',2]])->first();
        $employeeEquipment  = EmployeeEquipments::where([['emp_id','=', $emr_no]])->pluck('equipment_id')->toArray();

        if(EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emp_id','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->exists()):
            $equipment_detail = EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emp_id','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->first();
        endif;

        $employee_eobi_copy = Employee::where([['emp_id','=',$emr_no],['status','!=',2],['eobi_path', '!=', null]]);
        $employee_insurance_copy = Employee::where([['emp_id','=',$emr_no],['status','!=',2]]);

        CommonHelper::reconnectMasterDatabase();
        $equipment = Equipments::where([['status','=', 1]])->orderBy('id')->get();

        return view('Hr.Equipments.editEmployeeEquipmentsDetailForm', compact('employeeEquipment', 'emr_no', 'equipment', 'employee', 'equipment_detail', 'employee_insurance_copy', 'employee_eobi_copy'));
    }

    public function createDiseasesForm()
    {
        return view('Hr.Diseases.createDiseasesForm');
    }

    public function viewDiseasesList()
    {
        $disease = Diseases::where([['company_id','=',Input::get('m')],['status', '=', 1]])->orderBy('id')->get();
        return view('Hr.Diseases.viewDiseasesList', compact('disease'));
    }

    public function editDiseasesDetailForm()
    {
        $disease = Diseases::where([['id','=', Input::get('id')]])->first();
        return view('Hr.Diseases.editDiseasesDetailForm', compact('disease'));
    }

//    public function viewEmployeeMedicalList()
//    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $employeeMedical = EmployeeMedical::where([['status', '=', 1]])->orderBy('id');
//        CommonHelper::reconnectMasterDatabase();
//        return view('Hr.viewEmployeeMedicalList', compact('employeeMedical'));
//    }
//
//    public function createEmployeeMedicalForm()
//    {
//        $disease = Diseases::where('status', '=', 1)->get();
//        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
//        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
//        $Employeeprojects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
//        return view('Hr.createEmployeeMedicalForm', compact('disease', 'employee_regions', 'employee_category','Employeeprojects'));
//    }
//
//    public function editEmployeeMedicalDetailForm(){
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $employeeMedical = EmployeeMedical::where([['id', '=', Input::get('id')]])->first();
//        CommonHelper::reconnectMasterDatabase();
//        $disease = Diseases::where('status', '=', 1)->get();
//        return view('Hr.editEmployeeMedicalDetailForm', compact('employeeMedical', 'disease'));
//    }

    public function viewHrReports()
    {
        return view('Hr.Reports.viewHrReports');
    }

    public function editEmployeeAttendanceDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendanceDetail = Attendance::where([['id','=', Input::get('id')]])->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editEmployeeAttendanceDetailForm', compact('attendanceDetail'));
    }



    public function addEmpTrainingForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $trainingsData = Trainings::where([['status','=',1]])->orderBy('training_date','desc')->get();
        CommonHelper::reconnectMasterDatabase();

        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        return view('Hr.Trainings.addEmpTrainingForm',compact('trainingsData','Department','SubDepartment'));
    }

    public function viewEmpTrainingList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $trainingsData=DB::table('trainings')
            ->join('employee_training', 'trainings.id', '=', 'employee_training.training_id')
            ->join('employee', 'employee_training.emp_id', '=', 'employee.emp_id')
            ->select('trainings.topic_name', 'trainings.training_hours', 'trainings.training_date','employee_training.emp_id','employee_training.id','employee_training.status','employee.emp_name')
            ->where('employee_training.status',1)
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Trainings.viewEmpTrainingList',compact('trainingsData'));
    }


    public function createTrainingForm()
    {
        return view('Hr.Trainings.createTrainingForm');
    }

    public function viewTrainingList()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $trainingsData = Trainings::where([['status','=',1]])->orderBy('training_date','desc')->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Trainings.viewTrainingList', compact('trainingsData'));
    }

    public function editTrainingDetailForm()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $trainingsData = Trainings::where([['status','=',1],['id', Input::get('id')]])->first();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Trainings.editTrainingForm', compact('trainingsData'));

    }

    public function employeeTransferLeaves()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
        $leavesPolicy = LeavesPolicy::all()->sortByDesc("id");
        return view('Hr.LeavesPolicy.employeeTransferLeaves', compact('companies','leavesPolicy'));
    }

//    public function editEmployeeTransferProject()
//    {
//        $id = Input::get('id');
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
//        $TransferEmployeeProject = TransferEmployeeProject::where([['id','=', $id]])->first();
//        $employee = Employee::where('emr_no','=',$TransferEmployeeProject->emr_no)->first();
//        CommonHelper::reconnectMasterDatabase();
//
//        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
//        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
//        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
//        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//        $location = Locations::where('company_id','=',Input::get('m'))->orderBy('id')->get();
//        $employee_grades = Grades::where('company_id','=',Input::get('m'))->orderBy('id')->get();
//        return view('Hr.editEmployeeTransferProject',compact('employee_regions','employee_category','designation','location', 'employee_grades','Employee_projects','TransferEmployeeProject','employee'));
//    }

    public function ViewAttendanceProgressList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $accType = Auth()->user()->acc_type;
        $acc_emp_id = Auth()->user()->employee_id;
        if($accType == 'user'){
            $payrollData = DB::table('payroll_data')
                ->join('employee', 'employee.emp_id', '=', 'payroll_data.emp_id')
                ->select('employee.sub_department_id', 'payroll_data.*')
                ->where([['payroll_data.status', '=', '1'],['employee.id',$acc_emp_id]])
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->orderBy('employee.sub_department_id', 'asc')
                ->orderBy('employee_id', 'asc');
        }
        else{
            $payrollData = DB::table('payroll_data')
                ->join('employee', 'employee.emp_id', '=', 'payroll_data.emp_id')
                ->select('employee.sub_department_id', 'payroll_data.*')
                ->where([['payroll_data.status', '=', '1']])
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->orderBy('employee.sub_department_id', 'asc')
                ->orderBy('employee_id', 'asc');
        }




        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Attendance.ViewAttendanceProgressList',compact('departments','sub_departments','category','projects','employees','payrollData'));

    }

    public function viewEmployeeQueries()
    {
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $LateArrivalsReason = DB::table('user_query')->orderBy('id','desc')->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.viewEmployeeQueries',compact('SubDepartment','LateArrivalsReason','employeeProjects'));

    }

    public function createProjectsForm()
    {
        return view('Hr.EmployeeProjects.createProjectsForm');
    }

    public function viewProjectsList()
    {
        $employee_projects = EmployeeProjects::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.EmployeeProjects..viewProjectsList', compact('employee_projects'));
    }

    public function editProjectsForm()
    {
        $employee_projects = EmployeeProjects::where([['id','=', Input::get('id')]])->first();
        return view('Hr.EmployeeProjects.editProjectsForm', compact('employee_projects'));
    }

    public function viewEmployeeOfTheMonth()
    {
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeOfTheMonth = EmployeeOfTheMonth::where([['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Employees.viewEmployeeOfTheMonth', compact('department_id','employeeProjects','employeeOfTheMonth','sub_department_id'));
    }

    public function uploadPolicyFile()
    {
        return view('Hr.PolicyForms.uploadPolicyFile');
    }

    public function viewPolicyList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $policies = Policies::where([['status', '=', 1]])->orderBy('category_id');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.PolicyForms.viewPolicyList',compact('policies'));
    }

    public function createProvidentFundForm()
    {
        return view('Hr.ProvidentFund.createProvidentFundForm');
    }
    public function editProvidentFundDetail()
    {
        $provident_fund_detail =  DB::table('provident_fund')->where([['company_id','=',Input::get('m')],['id','=',Input::get('id')]])->first();

        return view('Hr.ProvidentFund.editProvidentFundDetail',compact('provident_fund_detail'));
    }
    public function viewProvidentFundList()
    {
        $provident_fund = DB::table('provident_fund')->where([['company_id','=',Input::get('m')]])->get();
        return view('Hr.ProvidentFund.viewProvidentFundList',compact('provident_fund'));
    }

    public function providentFundReport()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->orderBy('order_by_no', 'asc')->get()->toArray();
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        return view('Hr.ProvidentFund.providentFundReport',compact('departments','subdepartments','companies'));

    }
    public function createPfOpeningBalance()
    {
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        return view('Hr.ProvidentFund.createPfFundOpeningBalance',compact('departments'));
    }

    public function editWorkingOurPolicyList()
    {
        $id = Input::get('id');
        $m =  Input::get('m');

        $editWorkingPolicyDetail = DB::table('working_hours_policy')->where([['id',$id],['company_id',$m]])->first();
        return view('Hr.WorkingHoursPolicy.editWorkingOurPolicyList',compact('editWorkingPolicyDetail'));
    }

    public function viewMySalarySheet()
    {
        return view('Hr.SalarySheet.viewMySalarySheet');
    }

    public function createRebateForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.Rebate.createRebateForm',compact('departments','locations','sub_departments','category','projects','employees'));
    }

    public function viewRebateList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $employees = Cache::get('employee');
        $locations = Cache::get('locations');
        return view('Hr.Rebate.viewRebateList',compact('departments','locations','sub_departments','category','projects','employees'));
    }

    public function createCityForm()
    {
        return view('Hr.City.createCityForm');
    }

    public function viewCityList()
    {
        return view('Hr.City.viewCityList');
    }

    public function editCityForm()
    {
        $cities = Cities::where([['id', '=', Input::get('id')]])->first();
        return view('Hr.City.editCityForm', compact('cities'));
    }

    public function createEmployeeTransferForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.Transfers.createEmployeeTransferForm', compact('departments','sub_departments','category',
            'projects','employees','locations'));
    }

    public function viewEmployeeTransferList()
    {
        return view('Hr.Transfers.viewEmployeeTransferList');
    }

    public function editEmployeeTransferForm()
    {
        //$cities = Cities::where([['id', '=', Input::get('id')]])->first();
        return view('Hr.Transfers.editEmployeeTransferForm', compact('cities'));
    }

    public function createChangeReportingManagerForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.Transfers.createChangeReportingManagerForm', compact('departments','sub_departments','category',
            'projects','employees','locations'));
    }
    
    public function viewReconciliationReport()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.Payroll.viewReconciliationReport', compact('departments','sub_departments','category',
            'projects','employees','locations'));
    }
    
    public function viewEmployeeProbationExpireDetail()
    {
        return view('Hr.Employees.viewEmployeeProbation');
    }
    
    public function viewEmployeeGratuityForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.Gratuity.viewEmployeeGratuityForm', compact('departments','sub_departments','category',
            'projects','employees','locations'));
    }
    
    public function viewTaxCertificateList()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.Payroll.viewTaxCertificateList', compact('departments','sub_departments','category',
            'projects','employees','locations'));
    }

    public function viewTaxCertificate($employee_id, $year, $company_id)
    {
        $explode_year = explode('-', $year);
        CommonHelper::companyDatabaseConnection($company_id);
        $income_tax = IncomeTax::where([['status','=', 1], ['employee_id','=', $employee_id]])
            ->whereBetween('year',[$explode_year[0],$explode_year[1]])
            ->selectRaw('SUM(amount) AS tax_paid, MAX(annual_salary) AS annual_salary, MAX(taxable_income) AS taxable_income')
            ->first();
        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');
        $employees = $employees[$employee_id];
        return view('Hr.Payroll.viewTaxCertificate', compact('income_tax','employee_id','explode_year','company_id','employees'));
    }

    public function downloadTaxCertificatePdf($employee_id, $year, $company_id)
    {
        $explode_year = explode('-', $year);
        CommonHelper::companyDatabaseConnection($company_id);
        $income_tax = IncomeTax::where([['status','=', 1], ['employee_id','=', $employee_id]])
            ->whereBetween('year',[$explode_year[0],$explode_year[1]])
            ->selectRaw('SUM(amount) AS tax_paid, MAX(annual_salary) AS annual_salary, MAX(taxable_income) AS taxable_income')
            ->first();
        CommonHelper::reconnectMasterDatabase();
        $employees = Cache::get('employee');
        $employees = $employees[$employee_id];
        $pdf = PDF::loadView('Hr.Payroll.viewTaxCertificate', compact('income_tax','employee_id','explode_year','company_id','employees'));
        return $pdf->download($employee_id."-".$year."-Tax Certificate".".pdf");
    }
    
    public function leavesEncashmentForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.LeavesEncashment.leavesEncashmentForm', compact('departments','sub_departments','category',
            'projects','employees','locations'));
    }

    public function createTFAllowanceForm()
    {
        $departments = Cache::get('department');
        $sub_departments = Cache::get('sub_department');
        $category = Cache::get('employee_category');
        $projects = Cache::get('employee_projects');
        $locations = Cache::get('locations');
        $employees = Cache::get('employee');
        return view('Hr.Payroll.createTFAllowanceForm', compact('departments','sub_departments','category',
            'projects','employees','locations'));
    }


}