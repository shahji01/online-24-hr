<?php
namespace App\Helpers;
use App\Models\EmployeeOfTheMonth;
use DB;
use Carbon\Carbon;
use Config;
use Input;
use DateTime;
use App\Helpers\CommonHelper;
use App\Models\Attendance;
use App\Models\EmployeeBankData;
use App\Models\Employee_projects;
use App\Models\Employee;
use App\Models\Holidays;
use App\Models\TransferEmployeeProject;
use Illuminate\Support\Facades\Auth;
use \Cache;

class HrHelper{


    /*company_id*/ /*table name*/ /*column name*/ /*column_id*/

    public static function takenLeavesLeaveTypeWise(){
        return 'abc';
    }

    public static function totalLateForThisRange($param1,$param2,$param3,$param4){
        $totalLateForThisRange = 0;
        CommonHelper::companyDatabaseConnection($param1);
        $fromDateOne = date_create($param2);
        $toDateOne = date_create($param3);
        $fromDate = date_format($fromDateOne,'n/j/yyyy');
        $toDate = date_format($toDateOne,'n/j/yyyy');
        $countTotalLateForThisRange = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$param4)->where('late','!=','')->get();
        CommonHelper::reconnectMasterDatabase();
        $totalLateForThisRange = count($countTotalLateForThisRange);

        return $totalLateForThisRange;
    }

    public static function totalAbsentForThisRange($param1,$param2,$param3,$param4){
        $totalAbsentForThisRange = 0;
        CommonHelper::companyDatabaseConnection($param1);
        $fromDateOne = date_create($param2);
        $toDateOne = date_create($param3);
        $fromDate = date_format($fromDateOne,'n/j/yyyy');
        $toDate = date_format($toDateOne,'n/j/yyyy');
        $countTotalAbsentForThisRange = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$param4)->where('absent','!=','')->get();
        CommonHelper::reconnectMasterDatabase();
        $totalAbsentForThisRange = count($countTotalAbsentForThisRange);

        return $totalAbsentForThisRange;
    }

    public static function totalLateForThisAccountingYear(){
        return '0';
    }

    public static function totalAbsentForThisAccountingYear(){
        return '0';
    }

    /*company_id*/ /*table name*/ /*column name*/ /*column_id*/
    public static function getMasterTableValueById($param1,$param2,$param3,$param4){


        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' );

        if($detailName):
            return $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' )->$param3;
        else:
            return ;
        endif;
    }


    public static function getMasterTableValueByIdAndColumn($param1,$param2,$param3,$param4,$param5){
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' );

        if($detailName):
            return $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and '.$param5.' = '.$param4.'' )->$param3;
        else:
            return ;
        endif;
    }
    public static function getCompanyTableValueByIdAndColumn($param1,$param2,$param3,$param4,$param5){

        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');

        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where  '.$param5.' = '.$param4.'' );

        if($detailName):
            $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where '.$param5.' = '.$param4.'' )->$param3;
        else:
            $detailName = '';
        endif;
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        return $detailName;

    }


    public static function getCompanyTableValueById($param1,$param2,$param3,$param4){

        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');

        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and id = '.$param4.'' );

        if($detailName):
            $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and id = '.$param4.'' )->$param3;
        else:
            $detailName = '<span style="color:red">Deleted</span>';
        endif;
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        return $detailName;

    }

    public static function getStatusLabel($param)
    {
        $array[1] ="<span class='badge badge-pill badge-success'>&nbsp;Active&nbsp;</span>";
        $array[2] ="<span class='badge badge-pill badge-danger'>Deleted</span>";
        $array[3] ="<span class='badge badge-pill badge-light'>Exit</span>";
        $array[4] ="<span class='badge badge-pill badge-warning'>InActive</span>";
        echo $array[$param];
    }

    public static function getLeaveStatusLabel($param)
    {
        $array[1] ="<span class='badge badge-pill badge-success'>&nbsp;Active&nbsp;</span>";
        $array[2] ="<span class='badge badge-pill badge-danger'>Deleted</span>";
        $array[3] ="<span class='badge badge-pill badge-danger'>Expired</span>";
        echo $array[$param];
    }

    public static function getApprovalStatusLabel($param)
    {
        $array[1] ='<span style="color: white;" class="badge badge-pill badge-warning">Pending</span>';
        $array[2] ='<span style="color: white;" class="badge badge-pill badge-success">Approved</span>';
        $array[3] ='<span style="color: white;" class="badge badge-pill badge-danger">Rejected</span>';
        echo $array[$param];
    }

    public static function date_format($str)
    {
        return ($str == '') ? '' : date("d-M-Y", strtotime($str));
    }

    public static function hr_date_format($str)
    {
        $myDateTime = date_create_from_format('Y-m-d',$str);
        $new_date = $myDateTime->format('F d, Y');
        return $new_date;
    }

    public static function getIdCardStatus($param)
    {
        $array[1] ='<span class="label label-warning">Pending</span>';
        $array[2] ='<span class="label label-info">Printed</span>';
        $array[3] ='<span class="label label-success">Delivered</span>';
        echo $array[$param];
    }

    public static function getEmployeeBankData($param1,$param2,$param3)
    {
        CommonHelper::companyDatabaseConnection($param1);
        $EmployeeBankData =  EmployeeBankData::where([['status','=',$param2],['emr_no','=',$param3]])->value('account_no');
        CommonHelper::reconnectMasterDatabase();
        return $EmployeeBankData;
    }
    
    public static function getEmployee($m,$employee_id)
    {
        CommonHelper::companyDatabaseConnection($m);

        if(!empty($employee_id)) $query_string_second_part[] = " AND id = '$employee_id'";
        
        $query_string_second_part[] = " AND status = '1'";

        $query_string_first_Part = "SELECT id, emp_id, emp_name, department_id, sub_department_id, category_id, project_id,cnic FROM employee WHERE";
        $query_string_third_part = ' ORDER BY id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
        $employees = DB::select(DB::raw($query_string));

        CommonHelper::reconnectMasterDatabase();
        return $employees;
    }

    public static function getAllEmployees($m,$department_id,$sub_department_id,$project_id,$category_id)
    {
        CommonHelper::companyDatabaseConnection($m);
        
        if(!empty($department_id)) $query_string_second_part[] = " AND department_id = '$department_id'";
        if(!empty($sub_department_id)) $query_string_second_part[] = " AND sub_department_id = '$sub_department_id'";
        if(!empty($category_id)) $query_string_second_part[] = " AND category_id = '$category_id'";
        if(!empty($project_id)) $query_string_second_part[] = " AND project_id = '$project_id'";
        $query_string_second_part[] = " AND status = '1'";

        $query_string_first_Part = "SELECT id, emp_id, emp_name, department_id, sub_department_id, category_id, project_id,cnic FROM employee WHERE";
        $query_string_third_part = ' ORDER BY id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
        $employees = DB::select(DB::raw($query_string));

        CommonHelper::reconnectMasterDatabase();
        return $employees;
    }
    
    public static function getEmployees($m, $department_id, $sub_department_id, $project_id, $location_id, $category_id, $employee, $status = 1) {
        $user_roles = CommonHelper::userRoles($m);
        $employee_id = Auth::user()->employee_id;
        $acc_type = Auth::user()->acc_type;
        
        CommonHelper::companyDatabaseConnection($m);
        
        $query = DB::table('employee AS e')
            ->select(
                'e.id', 'e.emp_id', 'e.permanent_address', 'e.attendance_id',
                DB::raw('GROUP_CONCAT(l.location_name) AS location_id'),
                'e.emp_name', 'e.emp_father_name', 'e.sub_department_id', 'e.department_id',
                'e.category_id', 'eml.project_id', 'e.confirmation_date', 'e.eobi_id',
                'e.date_of_birth', 'e.joining_date', 'e.cnic', 'e.contact_no',
                'e.employment_status_id', 'e.last_working_date', 'e.status',
                'e.reporting_manager_id', 'e.personal_email',
                DB::raw('COALESCE(ep.designation_id, e.designation_id) AS designation_id'),
                DB::raw('COALESCE(ep.grade_id, e.grade_id) AS grade_id'),
                DB::raw('COALESCE(ep.salary, e.salary) AS salary')
            )
            ->join('employee_multiple_locations AS eml', 'e.id', '=', 'eml.employee_id')
            ->leftJoin('online24_hr_master.locations AS l', 'l.id', '=', 'eml.location_id')
            ->leftJoin(DB::raw(
                "(SELECT employee_id,
                        MAX(CASE WHEN status = 1 AND approval_status = 2 THEN designation_id END) AS designation_id,
                        MAX(CASE WHEN status = 1 AND approval_status = 2 THEN grade_id END) AS grade_id,
                        MAX(CASE WHEN status = 1 AND approval_status = 2 THEN salary END) AS salary
                  FROM employee_promotion
                  GROUP BY employee_id) AS ep"
            ), 'e.id', '=', 'ep.employee_id')
            ->where('eml.status', '1')
            ->where('e.status', $status);
    
        if (!empty($department_id)) $query->where('e.department_id', $department_id);
        if (!empty($sub_department_id)) $query->where('e.sub_department_id', $sub_department_id);
        if (!empty($category_id)) $query->where('e.category_id', $category_id);
        if (!empty($project_id)) $query->where('eml.project_id', $project_id);
        
        if ($acc_type != 'user' && $employee != 'all') {
            $query->whereIn('e.id', explode(',', $employee));
        }
    
        if ($acc_type == 'client' && !empty($location_id)) {
            $query->whereIn('eml.location_id', [0, $location_id]);
        }
    
        if ($acc_type == 'admin') {
            $locationFilter = !empty($location_id) ? [0, $location_id] : array_merge([0], $user_roles['location_ids']);
            $query->whereIn('eml.location_id', $locationFilter);
        }
        
        if ($acc_type == 'user') {
            $query->where(function($q) use ($employee_id, $employee) {
                if ($employee == 'all') {
                    $q->where('e.id', $employee_id)
                      ->orWhere('e.reporting_manager_id', $employee_id);
                } else {
                    $q->where('e.id', $employee_id);
                }
            });
        }
        
        $query->groupBy('e.id')->orderBy('e.id');
        
        $employees = $query->get()->map(function ($row) {
            $row->emp_id = preg_replace("/[^0-9]/", "", $row->emp_id);
            return $row;
        })->sortBy('emp_id')->values()->all();
        
        CommonHelper::reconnectMasterDatabase();
        return $employees;
    }
    
    public static function getLeftEmployees($m,$department_id,$sub_department_id,$project_id,$location_id,$category_id,$employee)
    {
        $user_roles = CommonHelper::userRoles($m);
        $employee_id = Auth::user()->employee_id;
        $acc_type = Auth::user()->acc_type;
        CommonHelper::companyDatabaseConnection($m);

        if(!empty($department_id)) $query_string_second_part[] = " AND e.department_id = '$department_id'";
        if(!empty($sub_department_id)) $query_string_second_part[] = " AND e.sub_department_id = '$sub_department_id'";
        if(!empty($category_id)) $query_string_second_part[] = " AND e.category_id = '$category_id'";
        if(!empty($project_id)) $query_string_second_part[] = " AND eml.project_id = '$project_id'";
        if($acc_type != 'user' && $employee != 'all') $query_string_second_part[] = " AND e.id = '$employee'";

        if($acc_type == 'client' && !empty($location_id))
            $query_string_second_part[] = " AND eml.location_id = '$location_id' ";

        if($acc_type == 'admin' && empty($location_id))
            $query_string_second_part[] = " AND eml.location_id IN (".implode(',',$user_roles['location_ids']).") ";

        if($acc_type == 'admin' && !empty($location_id))
            $query_string_second_part[] = " AND eml.location_id = '$location_id' ";

        if($acc_type == 'user' && $employee != 'all')
            $query_string_second_part[] = " AND e.id = '$employee_id' ";

        if($acc_type == 'user' && $employee == 'all')
            $query_string_second_part[] = " AND  (e.id = '$employee_id' or e.reporting_manager_id = '$employee_id' )";

        $query_string_second_part[] = " AND e.status IN (3,4)";
        $query_string_second_part[] = " AND eml.status = '1'";

        $query_string_first_Part= "SELECT e.id,e.emp_id, eml.location_id,e.emp_name,e.emp_father_name,e.sub_department_id,e.department_id,
          e.category_id, eml.project_id,e.confirmation_date,e.eobi_id, e.date_of_birth,e.joining_date,e.cnic,e.contact_no
          ,e.last_working_date, e.status,e.reporting_manager_id,e.personal_email,
          IFNULL((SELECT employee_promotion.designation_id FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.designation_id != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.designation_id) AS designation_id,
          IFNULL((SELECT employee_promotion.grade_id FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.grade_id != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.grade_id) AS grade_id,
          IFNULL((SELECT employee_promotion.salary FROM employee_promotion WHERE
          employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.salary != '' AND employee_promotion.approval_status = 2
          ORDER BY employee_promotion.id DESC LIMIT 1),e.salary) AS salary
          FROM employee AS e
          INNER JOIN employee_multiple_locations AS eml ON e.id = eml.employee_id WHERE ";
        $query_string_third_part = ' ORDER BY e.id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
        $employees = DB::select(DB::raw($query_string));

        CommonHelper::reconnectMasterDatabase();
        return $employees;
    }

    public static function getAllEmployeeId($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
        $employeeEmrnoArray = [];
        if($param2 != '' && $param3 != ''){
            $employee_emrno = Employee::select('emp_id')->where([['emp_department_id','=',$param2],['emp_sub_department_id','=',$param3],['status','!=',2]])
                ->get();
        }
        else if($param2 != '' && $param3 == ''){
            $employee_emrno = Employee::select('emp_id')->where([['emp_department_id','=',$param2],['status','!=',2]])
                ->get();
        }
        else{
            $employee_emrno = Employee::select('emp_id')->where('status','!=',2)
                ->get();
        }

        CommonHelper::reconnectMasterDatabase();
        foreach ($employee_emrno as $value){
            $employeeEmrnoArray[] = $value->emp_id;
        }
        return $employeeEmrnoArray;
    }

    public static function getEmployeeData($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
        $employee_data = Employee::select($param2)->where($param3,'=',$param4)->get();
        foreach ($employee_data as $value){
            return $value->$param2;
        }
    }

    public static function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if (($number %100) >= 11 && ($number%100) <= 13)
            return $abbreviation = $number. 'th';
        else
            return $abbreviation = $number. $ends[$number % 10];
    }

    public static function getActiveProjectId($param1,$param2){
        $projectdataArray = [];
        CommonHelper::companyDatabaseConnection($param1);
        $TransferEmployeeProject = TransferEmployeeProject::where([['emr_no','=',$param2],['status','=',1],['active','=',1]]);
        $employee = Employee::where([['emr_no','=',$param2],['status','=',1],['active','=',1]]);
        CommonHelper::reconnectMasterDatabase();

        if($TransferEmployeeProject->count() > 0){
            $projectData = $TransferEmployeeProject->get();
        }
        else{
            if($employee->count() > 0){
                $projectData = $employee->get();
            }
        }

        foreach($projectData as $value){
            $projectdataArray[] = $value->employee_project_id;

        }
        return $projectdataArray;
    }

    public static function getProjectName($param1,$param2,$param3){

        $projectName = DB::table($param1)->select($param2)->whereIn('id',$param3)->get();

        foreach($projectName as $value){
            echo $value->$param2;
            return;
        }

    }

    public static function checkTRColor($param1,$param2,$param3){
        //return $param1.' - '.print_r($param2).' - '.print_r($param3);
        if(in_array($param1,$param2)){
            return '#FFC0CB';
        }else if(in_array($param1,$param3)) {
            return '#adde80';
        }else{
            return 'white';
        }
    }
    public static function get_menu_title($param1){
        print_r($param1);
        return;
    }


    public static function getAllEmpIds($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param5);

        $all_emp_ids = '';
        $query=DB::table('employee')->select('emp_id');
        if($param4 != 'show_all'){
            if($param1 != ''){
                $query->where('emp_department_id',$param1);
            }
            if($param2 != ''){
                $query->where('emp_sub_department_id',$param2);
            }
            if($param3 != 'all'){
                $query->where('emp_id',$param3);
            }
        }else{
            $query->where('status',1);
        }

        $all_emp_ids=$query;


        if($all_emp_ids == ''){
            return 'null';
        }else{
            CommonHelper::reconnectMasterDatabase();
            $emp_ids = [];
            foreach($all_emp_ids->get() as $value){
                $emp_ids[] = $value->emp_id;
            }
            return $emp_ids;
        }
    }

    public static function getAllEmpId($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param3);

        $all_emp_ids = '';
        if($param1 != '' && $param2 != ''){
            $all_emp_ids = DB::table('employee')->select('emp_id')->where([['emp_department_id',$param1],['emp_sub_department_id',$param2],['status','!=',2]]);
        }
        else if($param1 != '' && $param2 == ''){
            $all_emp_ids = DB::table('employee')->select('emp_id')->where([['emp_department_id',$param1],['status','!=',2]]);
        }



        if($all_emp_ids == ''){
            return 'null';
        }else{
            CommonHelper::reconnectMasterDatabase();
            $emp_ids = [];
            foreach($all_emp_ids->get() as $value){
                $emp_ids[] = $value->emp_id;
            }
            return $emp_ids;
        }
    }

    public static function getIncomeTax($payable_wihtoutdays_taxable,$pre_salary,$grossSalary,$pre_taxable,$pre_payslip_tax,$paid_previous_tax,$add_tax_amount,$emp_join_date,$emp_month,$payslip_month,$pay_month,$emp_year,$pay_year){

//      echo $payable_wihtoutdays_taxable.'-';
//      echo "<br>";
//      echo $pre_salary.'-';
//       echo "<br>";
//      echo  $grossSalary.'-';
//       echo "<br>";
//      echo $pre_taxable.'-';
//      echo "<br>";
//      echo $pre_payslip_tax.'-';
//      echo "<br>";
//      echo $paid_previous_tax.'-';
//      echo "<br>";
//      echo $add_tax_amount.'-';
//      echo "<br>";
//      echo $emp_join_date.'-';
//      echo "<br>";
//      echo $emp_month.'-';
//      echo "<br>";
//      echo $payslip_month.'-';
//      echo "<br>";
//      echo  $pay_month.'-';
//      echo "<br>";
//      echo $emp_year.'-';
//      echo "<br>";
//      echo $pay_year.'-';

        $paid_previous_tax+=$pre_payslip_tax;
        $income_tax=0;
        $yearly_tax=0;
        $array=[];
        $tax_slabs = DB::table('tax_slabs')->where([['status','=','1'],['tax_id', '=',1 ]])->get();
        $payable_salary_taxable=0;
        $month=0;



        $to = Carbon::createFromFormat('Y-m-d', $emp_join_date);
        $from = Carbon::createFromFormat('Y-m', $payslip_month);
        $diff_in_months = $to->diffInMonths($from);

        if($diff_in_months >= 12){
            $payable_salary_taxable = ($payable_wihtoutdays_taxable*12);

            ($payable_salary_taxable+=$add_tax_amount) /1.1;
            $month=12;

        }
        else{
            if(($pay_month >= 7 && $emp_month <= 6) || ($pay_month >= 7 && $emp_month > $pay_month) ){
                $payable_salary_taxable = ($payable_wihtoutdays_taxable*12);
                ($payable_salary_taxable+=$add_tax_amount) /1.1;
                $month=12;
            }
            elseif($emp_month <=6 && $pay_year > $emp_year){
                $payable_salary_taxable = ($payable_wihtoutdays_taxable*12);
                ($payable_salary_taxable+=$add_tax_amount) /1.1;
                $month=12;
            }
            else{
                switch ($emp_month) {
                    case 1:
                        $month=6;
                        break;
                    case 2:
                        $month=5;
                        break;
                    case 3:
                        $month=4;
                        break;
                    case 4:
                        $month=3;
                        break;
                    case 5:
                        $month=2;
                        break;
                    case 6:
                        $month=1;
                        break;
                    case 7:
                        $month=12;
                        break;
                    case 8:
                        $month=11;
                        break;
                    case 9:
                        $month=10;
                        break;
                    case 10:
                        $month=9;
                        break;
                    case 11:
                        $month=8;
                        break;
                    case 12:
                        $month=7;
                        break;
                    default:
                        $month=12;
                        break;
                }
                $payable_salary_taxable = ($payable_wihtoutdays_taxable*$month);
                ($payable_salary_taxable+=$add_tax_amount) /1.1;
            }
        }

        $date1 = $pay_year.'-'.$pay_month;
        if($pay_month >=7 && $pay_month <=12){
            $pay_year++;
        }
        $date2 = $pay_year.'-06';

        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1)+1;
        $divided_tax = 0;
        $tax=0;

        foreach($tax_slabs as $value):
            if($pre_taxable != 0){


                $payable_salary_taxable=$pre_taxable;
                $payable_salary_taxable+=$add_tax_amount;

            }

            if($payable_salary_taxable > $value->salary_range_from && $payable_salary_taxable <= $value->salary_range_to):

                $payable_salary_taxable = ($payable_wihtoutdays_taxable*12);
                ($payable_salary_taxable+=$add_tax_amount) /1.1;
                $tax_percent = $value->tax_percent;
                $tax_amount = $value->tax_amount;
                if($pre_taxable != 0){
                    $payable_salary_taxable=$pre_taxable;
                    $payable_salary_taxable+=$add_tax_amount;


                }
                if($pre_salary != 0){
                    if($grossSalary != $pre_salary){
                        $pre_salary=$pre_salary*12;
                        $grossSalary=$grossSalary*12;
                        $payable_salary_taxable=($payable_salary_taxable-$pre_salary)+$grossSalary;

                    }
                }

                $income_tax = round((($payable_salary_taxable - $value->salary_range_from) / 100) * $tax_percent) + $tax_amount;
                $yearly_tax=$income_tax;
                $income_tax=$income_tax-$paid_previous_tax;
                $divided_tax = round($income_tax/$diff);
                $tax= ($divided_tax/12)*$month;

                break;
            endif;

        endforeach;


//echo $payable_salary_taxable;

        //   $payable_salary_taxable = ($payable_wihtoutdays_taxable*12)/1.1;

        $divided_tax = 0;
        foreach($tax_slabs as $value):
            if($payable_salary_taxable > $value->salary_range_from && $payable_salary_taxable <= $value->salary_range_to):

                $tax_percent = $value->tax_percent;
                $tax_amount = $value->tax_amount;

                $income_tax = round((($payable_salary_taxable - $value->salary_range_from) / 100) * $tax_percent) + $tax_amount;

                $divided_tax = round($income_tax/12);

            endif;
        endforeach;
        echo $payable_salary_taxable;
        // echo $divided_tax;
        //  return array('tax'=>$tax,'payable_salary_taxable'=>$payable_salary_taxable,'balance_amount_tax'=>$income_tax,'yearly_tax'=>$yearly_tax);
        return array('diff'=>$diff,'tax'=>$divided_tax,'payable_salary_taxable'=>$payable_salary_taxable,'balance_amount_tax'=>$income_tax,'yearly_tax'=>$yearly_tax);
    }

    public static function getAuthorizedInputFieldsFour(){
        CommonHelper::companyDatabaseConnection('12');
		$employees = Employee::select('id','emp_id','emp_name')->get();
		CommonHelper::reconnectMasterDatabase();
        ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterUserDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        <?php
    }

    public static function getAuthorizedInputFieldsFive(){
        CommonHelper::companyDatabaseConnection('12');
		$employees = Employee::select('id','emp_id','emp_name')->get();
		CommonHelper::reconnectMasterDatabase();
        ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterTMSDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        <?php
    }

    public static function getAuthorizedInputFieldsSix(){
        CommonHelper::companyDatabaseConnection('12');
		$employees = Employee::select('id','emp_id','emp_name')->get();
		CommonHelper::reconnectMasterDatabase();
        ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterTMSummaryDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        <?php
    }

    public static function getAuthorizedInputFields()
    {
		CommonHelper::companyDatabaseConnection('12');
		$team_members = Employee::select('id','emp_id','emp_name')->where('reporting_manager_id',Auth::user()->employee_id);
		CommonHelper::reconnectMasterDatabase();

        if(Auth::user()->acc_type != 'user'):
            $employees = static::getEmployees('12','','','','','','all');
            ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterUserDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

        <?php

        elseif(Auth::user()->acc_type == 'user' && $team_members->count() > 0):
            $employees = static::getEmployees('12','','','','','','all');
            ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterUserDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option <?php if($value->id == Auth::user()->employee_id): ?> selected <?php endif; ?>value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>


            <?php

        else:
        ?>
            <script>
                $('#dashboardUser').hide();
            </script>
            <div style="display: none;">
                <select style="display: none;" class="form-control" id="emp_id2" onchange="filterUserDashBoard(this.value)" >
                    <option value="<?=Auth::user()->employee_id?>"><?=Auth::user()->name?></option>
                </select>
            </div>

            <?php
        endif;
    }

    public static function getAuthorizedInputFieldsTwo()
    {
		CommonHelper::companyDatabaseConnection('12');
		$team_members = Employee::select('id','emp_id','emp_name')->where('reporting_manager_id',Auth::user()->employee_id);
		CommonHelper::reconnectMasterDatabase();

        if(Auth::user()->acc_type != 'user'):
            $employees = static::getEmployees('12','','','','','','all');
            ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterTMSDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

        <?php

        elseif(Auth::user()->acc_type == 'user' && $team_members->count() > 0):
            $employees = static::getEmployees('12','','','','','','all');
            ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterTMSDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option <?php if($value->id == Auth::user()->employee_id): ?> selected <?php endif; ?>value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>


            <?php

        else:
        ?>
            <script>
                $('#dashboardUser').hide();
            </script>
            <div style="display: none;">
                <select style="display: none;" class="form-control" id="emp_id2" onchange="filterTMSDashBoard(this.value)" >
                    <option value="<?=Auth::user()->employee_id?>"><?=Auth::user()->name?></option>
                </select>
            </div>

            <?php
        endif;
    }

    public static function getAuthorizedInputFieldsThree()
    {
		CommonHelper::companyDatabaseConnection('12');
		$team_members = Employee::select('id','emp_id','emp_name')->where('reporting_manager_id',Auth::user()->employee_id);
		CommonHelper::reconnectMasterDatabase();

        if(Auth::user()->acc_type != 'user'):
            $employees = static::getEmployees('12','','','','','','all');
            ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterTMSummaryDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

        <?php

        elseif(Auth::user()->acc_type == 'user' && $team_members->count() > 0):
            $employees = static::getEmployees('12','','','','','','all');
            ?>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterTMSummaryDashBoard(this.value)" >
                        <?php foreach($employees as $value): ?>
                            <option <?php if($value->id == Auth::user()->employee_id): ?> selected <?php endif; ?>value="<?php echo $value->id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>


            <?php

        else:
        ?>
            <script>
                $('#dashboardUser').hide();
            </script>
            <div style="display: none;">
                <select style="display: none;" class="form-control" id="emp_id2" onchange="filterTMSummaryDashBoard(this.value)" >
                    <option value="<?=Auth::user()->employee_id?>"><?=Auth::user()->name?></option>
                </select>
            </div>

            <?php
        endif;
    }

    public static function checkHolidayStatus($day, $month, $year, $hijri, $m)
    {
        $holiday_date = $year.'-'.$month.'-'.$day;
        CommonHelper::companyDatabaseConnection($m);
        $holiday = Holidays::where([['holiday_date', '=', $holiday_date],['status', '=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        if($holiday->count() > 0):
            $id = $holiday->value('id');
            $date = new DateTime($holiday->value('holiday_date'));
            $now = new DateTime();

            if($date < $now) :
                $style = 'style="background-color: red; color: white !important"';
            else:
                $style = 'style="background-color: #5fd65f;"';
            endif;
            echo "<td $style class='mouse-hover' onclick='functionModal(".$id.")'>".$day." ".$hijri."</td>";
        else:
            echo "<td class='mouse-hover' onclick='addHolidaysDetail(".$day.",".$month.",".$year.")'>".$day." ".$hijri."</td>";
        endif;
    }
    
    public static function getLoanStatusLabel($param)
    {
        $array[1] ="<span style='color: white;' class='badge badge-pill badge-success'>&nbsp;Paid&nbsp;</span>";
        $array[0] ="<span style='color: white;' class='badge badge-pill badge-warning'>&nbsp;Not Paid&nbsp;</span>";
        echo $array[$param];
    }

    public static function getLoanStopPaymentLabel($param)
    {
        echo "<span style='color: white;' class='badge badge-pill badge-danger'>&nbsp; $param &nbsp;</span>";
    }
    
       public static function viewLeaveBalances($employee_id, $leave_policy_id, $flag)
    {
        //flag 1 = create page, 2 = only view page
        $totalAnnualLeaves = 0;
        $totalCasualLeaves = 0;
        $totalSickLeaves = 0;
        $totalSpecialLeaves = 0;
        $totalCplLeaves = 0;

        $takenAnnualLeaves = 0;
        $takenCasualLeaves = 0;
        $takenSickLeaves = 0;
        $takenSpecialLeaves = 0;
        $takenCplLeaves = 0;

        $remainingAnnualLeaves = 0;
        $remainingCasualLeaves = 0;
        $remainingSickLeaves = 0;
        $remainingSpecialLeaves = 0;
        $remainingCplLeaves = 0;

        $leaves_policy = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $leave_policy_id]])
            ->get();

        $takenAnnualLeaves = DB::table("leave_application_data")
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.employee_id', '=', $employee_id], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '!=', 3],['leave_application.leave_type', '=', '1'],
                ['leave_application.leave_policy_id', '=', $leave_policy_id]])->sum('no_of_days');

        $takenSickLeaves = DB::table("leave_application_data")
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.employee_id', '=', $employee_id], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '!=', 3],['leave_application.leave_type', '=', '2'],
                ['leave_application.leave_policy_id', '=', $leave_policy_id]])->sum('no_of_days');

        $takenCasualLeaves = DB::table("leave_application_data")
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.employee_id', '=', $employee_id], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '!=', 3], ['leave_application.leave_type', '=', '3'],
                ['leave_application.leave_policy_id', '=', $leave_policy_id]])->sum('no_of_days');

        $takenSpecialLeaves = DB::table("leave_application_data")
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.employee_id', '=', $employee_id], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '!=', 3], ['leave_application.leave_type', '=', '4'],
                ['leave_application.leave_policy_id', '=', $leave_policy_id]])->sum('no_of_days');

        $takenCplLeaves = DB::table("leave_application_data")
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.employee_id', '=', $employee_id], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '!=', 3], ['leave_application.leave_type', '=', '5'],
                ['leave_application.leave_policy_id', '=', $leave_policy_id]])->sum('no_of_days');

        $totalCplLeaves = DB::table("cpl")->where([['employee_id', '=', $employee_id], ['status', '=', '1']])->sum('cpl');

        $transferred_leaves = DB::table('transfered_leaves')->where([['employee_id', '=', $employee_id], ['leaves_policy_id', '=', $leave_policy_id], ['status', '=', '1']]);
        $transferred_leaves_data[1] = $transferred_leaves->value('annual_leaves');
        $transferred_leaves_data[2] = $transferred_leaves->value('sick_leaves');
        $transferred_leaves_data[3] = $transferred_leaves->value('casual_leaves');

        foreach ($leaves_policy as $val):

            if ($val->leave_type_id == 1):
                $totalAnnualLeaves = $val->no_of_leaves;
                $remainingAnnualLeaves = $val->no_of_leaves + $transferred_leaves_data[1] - $takenAnnualLeaves;
            elseif ($val->leave_type_id == 2):
                $totalSickLeaves = $val->no_of_leaves;
                $remainingSickLeaves = $val->no_of_leaves + $transferred_leaves_data[2] - $takenSickLeaves;
            elseif ($val->leave_type_id == 3):
                $totalCasualLeaves = $val->no_of_leaves;
                $remainingCasualLeaves = $val->no_of_leaves + $transferred_leaves_data[3] - $takenCasualLeaves;
            elseif ($val->leave_type_id == 4):
                $totalSpecialLeaves = $takenSpecialLeaves;
                $remainingSpecialLeaves = $totalSpecialLeaves - $takenSpecialLeaves;
            elseif ($val->leave_type_id == 5):
                $remainingCplLeaves = $totalCplLeaves - $takenCplLeaves;
            endif;

        endforeach;

        $leaves_policy = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $leave_policy_id]])
            ->orderBy('leaves_data.leave_type_id')->get();

        $leave_type = Cache::get('leave_type');

        $totalLeaves = $totalAnnualLeaves + $totalSickLeaves + $totalCasualLeaves + $totalSpecialLeaves + $totalCplLeaves +
            $transferred_leaves_data[1] + $transferred_leaves_data[2] + $transferred_leaves_data[3];
        $totalUsedLeaves = $takenAnnualLeaves + $takenSickLeaves + $takenCasualLeaves + $takenSpecialLeaves + $takenCplLeaves;
        $totalRemainingLeaves = $remainingAnnualLeaves + $remainingSickLeaves + $remainingCasualLeaves + $remainingSpecialLeaves + $remainingCplLeaves;

        return compact('totalAnnualLeaves','totalSickLeaves','totalCasualLeaves','totalSpecialLeaves','totalCplLeaves',
            'remainingAnnualLeaves','remainingCasualLeaves','remainingSickLeaves','remainingSpecialLeaves','remainingCplLeaves',
            'takenAnnualLeaves','takenSickLeaves','takenCasualLeaves','takenSpecialLeaves','takenCplLeaves',
            'totalLeaves','totalUsedLeaves','totalRemainingLeaves','transferred_leaves_data','transferred_leaves','leaves_policy','leave_type','flag');
    }
    
    public static function remainingLeaveAfterConfirmation($m,$employee_id)
    {
        
        $employees = DB::Table('employee')->where('status',1)->where('id',$employee_id)->first();
        



        if($employees->employment_status_id != 4 || $employees->joining_date == '0000-00-00' ||  $employees->joining_date == ''  || $employees->leaves_policy_id == 0 || $employees->leaves_policy_id == '' )
        {
            return ;
        }

        $annaul = 14 ;
        $casual = 10 ;
        $sick = 8 ;

        $remainingannaul = 0 ;
        $remainingcasual = 0 ;
        $remainingsick = 0 ;

        $usedannaul = 0 ;
        $usedcasual = 0 ;
        $usedsick = 0 ;


        
        
        $currentDate  = date('Y-m-d');

        $confirmMonth = date('m', strtotime($employees->joining_date)) ;


        $previousDate = date('Y-m-d', strtotime($employees->joining_date. ' -2 months')); // ($confirmMonth == 05 ) ?   date('Y-m-d', strtotime($employees->joining_date. ' -1 months')) : date('Y-m-d', strtotime($employees->joining_date. ' -2 months'));
     

        if($employees->joining_date <=  $currentDate)
        {


            switch ($confirmMonth) 
            {
                
                case "04":

                    
                break;
                
                case "05":
                
                    $remainingannaul = ($annaul / 12) * 11 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 11 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 11 ;
                    $usedsick = $sick - $remainingsick ;

                break;
                
                case "06":
                
                    $remainingannaul = ($annaul / 12) * 10 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 10 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 10 ;
                    $usedsick = $sick - $remainingsick ;

                break;
                  
                case "07":
                
                    $remainingannaul = ($annaul / 12) * 9 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 9 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 9 ;
                    $usedsick = $sick - $remainingsick ;

                break;
                
                case "08":
                
                    $remainingannaul = ($annaul / 12) * 8 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 8 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 8 ;
                    $usedsick = $sick - $remainingsick ;

                break;

                case "09":
                
                    $remainingannaul = ($annaul / 12) * 7 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 7 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 7 ;
                    $usedsick = $sick - $remainingsick ;

                break;
                
                case "10":
                
                    $remainingannaul = ($annaul / 12) * 6 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 6 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 6 ;
                    $usedsick = $sick - $remainingsick ;

                break;

                case "11":
                
                    $remainingannaul = ($annaul / 12) * 5 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 5 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 5 ;
                    $usedsick = $sick - $remainingsick ;

                break;
                
                case "12":
                
                    $remainingannaul = ($annaul / 12) * 4 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 4 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 4 ;
                    $usedsick = $sick - $remainingsick ;

                break;

                case "1":
                
                    $remainingannaul = ($annaul / 12) * 3 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 3 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 3 ;
                    $usedsick = $sick - $remainingsick ;

                break;
                
                case "2":
                
                    $remainingannaul = ($annaul / 12) * 2 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 2 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 2 ;
                    $usedsick = $sick - $remainingsick ;

                break;

                case "3":
                
                    $remainingannaul = ($annaul / 12) * 1 ;
                    $usedannaul = $annaul - $remainingannaul ;
                    $remainingcasual = ($casual / 12) * 1 ;
                    $usedcasual = $casual - $remainingcasual ;
                    $remainingsick = ($sick / 12) * 1 ;
                    $usedsick = $sick - $remainingsick ;

                break;
                
                default:
                  echo "";
              }




            ( $usedannaul > 0 && $usedcasual > 0 && $usedsick > 0 ) ? static::appliedUsedLeave($m,$usedannaul,$usedcasual,$usedsick,$employees->joining_date,$previousDate,$employee_id,$employees->leaves_policy_id) : null ;


        }

    }

    public static function appliedUsedLeave($m,$usedannaul,$usedcasual,$usedsick,$empConfirmationDate,$previousDate,$employee_id,$leave_policy_id)
    {

        CommonHelper::reconnectMasterDatabase();

        $leaveExist = DB::table('leave_application')->where([['employee_id',$employee_id]])->exists();
        $leaveType = DB::table('leave_type')->where([['status',1]])->get();

        if($leaveExist)
        {
            CommonHelper::companyDatabaseConnection($m);
            return ; 
        }

        $previousMonth = date('m', strtotime($previousDate)) ;
        $year = date('Y', strtotime($previousDate)) ;

        $previousDate = ($previousMonth == 02 ) ? $year.'-'.$previousMonth.'-23' : $year.'-'.$previousMonth.'-26';



        foreach($leaveType as $key => $leave)
        {
            $leave_Count = 0 ;
            $val = $leave->id; 

            if($val == 1)
            {
                $leave_Count = round($usedannaul);

                if ($leave_Count == 1 )
                {
                    $from_date = $previousDate ;
                    $to_date = $from_date; 

                }
                else
                {
                    $leave_Count = $leave_Count - 1; 

                    $from_date = $previousDate ;
                    $to_date = date('Y-m-d', strtotime($from_date. ' + '.$leave_Count.' days')); 
                }
            }
            elseif($val == 2)
            {
                $leave_Count = round($usedcasual);

                if ($leave_Count == 1 )
                {
                    $from_date = date('Y-m-d', strtotime($to_date. ' + 1 days')) ;
                    $to_date = $from_date; 

                }
                else
                {
                    $leave_Count = $leave_Count - 1; 

                    $from_date = date('Y-m-d', strtotime($to_date. ' + 1 days')) ;
                    $to_date = date('Y-m-d', strtotime($from_date. ' + '.$leave_Count.' days')); 
                }

            }
            elseif($val == 3)
            {
                $leave_Count = round($usedsick) ;

                if ($leave_Count == 1 )
                {
                    $from_date = date('Y-m-d', strtotime($to_date. ' + 1 days')) ;
                    $to_date = $from_date; 

                }
                else
                {
                    $leave_Count = $leave_Count - 1; 
                    $from_date = date('Y-m-d', strtotime($to_date. ' + 1 days')) ;
                    $to_date = date('Y-m-d', strtotime($from_date. ' + '.$leave_Count.' days')); 
                }

            }

            
            
            $data1['employee_id'] = $employee_id;
            $data1['leave_policy_id'] = $leave_policy_id;
            $data1['company_id'] = $m;
            $data1['leave_type'] = $val;
            $data1['leave_day_type'] = 1;
            $data1['reason'] = 'Other';
            $data1['leave_address'] = 'new joining leaves';
            $data1['approval_status'] = 2; // 1 pending 2 approve 3 reject
            $data1['approval_status_lm'] = 2; // 1 pending 2 approve 3 reject
            $data1['approved'] = 2;
            $data1['status'] = 1;
            $data1['view'] = 'no';
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $leave_application_id = DB::table('leave_application')->insertGetId($data1);


    
            $data['employee_id'] = $employee_id;
            $data['leave_application_id'] = $leave_application_id;
            $data['leave_policy_id'] = $leave_policy_id;
            $data['no_of_days'] = $leave_Count + 1;
            $data['from_date'] =$from_date;
            $data['to_date'] = $to_date;
            $data['status'] = 1;
            $data['username'] = Auth::user()->name;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");

            DB::table('leave_application_data')->insert($data);

            CommonHelper::companyDatabaseConnection($m);
        }
    }

    public static function replacePrefix($number) {
        // Use a regular expression to replace "92" or "0" at the start of the number
        $result = preg_replace('/^(92|0)/', '', $number);
        return $result;
    }

}
?>