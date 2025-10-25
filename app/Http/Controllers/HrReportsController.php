<?php

namespace App\Http\Controllers;

use App\Helpers\HrHelper;
use App\Http\Requests;
use App\Models\Gratuity;
use App\Models\Department;
use App\Models\JobType;
use Auth;
use DB;
use Config;
use Input;

use App\Helpers\CommonHelper;
use App\Models\User;

use App\Models\Employee;
use App\Models\EmployeeMedical;
use App\Models\HrWarningLetter;
use App\Models\EmployeeExit;
use App\Models\EmployeePromotion;
use App\Models\EmployeeTransfer;
use App\Models\Regions;
use App\Models\EmployeeCategory;
use App\Models\Trainings;
use App\Models\EmployeeProjects;
use App\Models\SubDepartment;







class HrReportsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewTurnoverReportForm()
    {
        return view('Hr.AjaxPages.viewTurnoverReportForm');
    }

    public function viewOnboardReportForm()
    {	CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '=', '1']])->orderBy('id')->get();
		CommonHelper::reconnectMasterDatabase();
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
		$employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewOnboardReportForm',compact('employees','department_id','SubDepartment','employeeProjects'));
    }

    public function viewIncrementReportForm()
    {
        return view('Hr.AjaxPages.viewIncrementReportForm');
    }

    public function viewWarningReportForm()
    {
        return view('Hr.AjaxPages.viewWarningReportForm');
    }

    public function viewEmployeeReportForm()
    {	
		CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '=', '1']])->orderBy('id')->get();
		CommonHelper::reconnectMasterDatabase();
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
		$employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewEmployeeReportForm', compact('employees','department_id','SubDepartment','employeeProjects'));
    }

    public function viewTransferReportForm()
    {
		$SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
       
        return view('Hr.AjaxPages.viewTransferReportForm',compact('SubDepartment','Employee_projects'));
    }

    public function viewTrainingReportForm()
    {
        $regionsRights =  CommonHelper::regionRights(Input::get('m'));
        $regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regionsRights)->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $Employeeprojects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewTrainingReportForm',compact('employee_category', 'regions','Employeeprojects'));
    }

    public function viewMedicalReportForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status','=', 1]])->select('id', 'emp_id', 'emp_name')->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
		$SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $Employeeprojects = Employeeprojects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewMedicalReportForm', compact('employees','Employeeprojects','SubDepartment'));
    }

    public function viewTurnoverReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_exit = EmployeeExit::where([['status','=', 1 ],['approval_status', '=', 2]])->orderBy('emp_id','asc');
        else:
            $employee_exit = EmployeeExit::where([['status','=', 1 ],['approval_status', '=', 2]])->whereBetween('last_working_date', [$from, $to])->orderBy('last_working_date','asc');;
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTurnoverReport', compact('employee_exit'));
    }

    public function viewOnboardReport()
    {	

        $emp_id = Input::get('emp_id');
        $department_id_ = Input::get('department_id');
        $sub_department_id_ = Input::get('sub_department_id');

        $show_all = Input::get('show_all');
		$gender = Input::get('gender');
		
		$all_emp_id = HrHelper::getAllEmpId($department_id_,$sub_department_id_,Input::get('m'));
		
        CommonHelper::companyDatabaseConnection(Input::get('m'));

            if($show_all == '1'){
				$employee_details = Employee::where([['status','=',1]]);
			}
            elseif($emp_id == 'all'){
                $employee_details = Employee::whereIn('emp_id',$all_emp_id)->where([['status','=',1]]);
			}
            else{
                 $employee_details = Employee::where('emp_id',$emp_id)->where([['status','=',1]]);
            }
			
		
            $employee_details->orderBy('emp_id');
        CommonHelper::reconnectMasterDatabase();
		// print_r($employee_details->get());
		// die;
        return view('Hr.AjaxPages.viewOnboardReport', compact('employee_details'));
    }

    public function viewEmployeeReport()
    {
		$emp_id = Input::get('emp_id');
        $department_id_ = Input::get('department_id');
        $sub_department_id_ = Input::get('sub_department_id');


		//$employee_project_id = Input::get('employee_project_id');
        $show_all = Input::get('show_all');
		$gender = Input::get('gender');
		
		$all_emp_id = HrHelper::getAllEmpId($department_id_,$sub_department_id_,Input::get('m'));
		
        CommonHelper::companyDatabaseConnection(Input::get('m'));

            if($show_all == '1'){
				$employee_detail = Employee::where([['status','=',1]]);
			}
            elseif($emp_id == 'all'){
                $employee_detail = Employee::whereIn('emp_id',$all_emp_id)->where([['status','=',1]]);
			}
            else{
                 $employee_detail = Employee::where('emp_id',$emp_id)->where([['status','=',1]]);
            }
			if($gender != '0'){
				$employee_detail->where('emp_gender',$gender);
			}

            $employee_detail->orderBy('emp_id');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeReport', compact('employee_detail'));
    }

    public function viewWarningReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $warning_letter = HrWarningLetter::where([['status', '=', 1]])->orderBy('emr_no', 'asc');
        else:
            $warning_letter = HrWarningLetter::whereBetween('date', [$from, $to])->orderBy('date', 'asc');
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewWarningReport', compact('warning_letter'));
    }

    public function viewTransferReport()
    {
        $sub_department_id_1 = Input::get('sub_department_id_1');
        $employee_project_id = Input::get('employee_project_id');
        $show_all = Input::get('show_all');
        $from_date =Input::get('from_date');
        $to_date =Input::get('to_date');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_transfer = EmployeeTransfer::where([['status', '=', 1],['approval_status', '=', 2]])->orderBy('emr_no', 'asc')->get();
            $employee_project_id = '0';
        else:
            $employee_transfer = EmployeeTransfer::where([['emp_category_id','=',$employee_category_id],['emp_region_id','=',$region_id],['approval_status', '=', 2]])
                ->whereBetween('date',[$from_date,$to_date])
                ->orderBy('emr_no', 'asc')
                ->get();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTransferReport', compact('employee_transfer','employee_project_id'));
    }


    public function viewIncrementReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_promotion = EmployeePromotion::where([['status', '=', 1]])->orderBy('emp_id', 'asc');
        else:
            $employee_promotion = EmployeePromotion::whereBetween('promotion_date', [$from, $to])->orderBy('promotion_date', 'asc');
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewIncrementReport', compact('employee_promotion'));
    }

    public function viewMedicalReport()
    {
        $emr_no = Input::get('emp_id');
        $show_all = Input::get('show_all');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $employee_project_id = Input::get('employee_project_id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $employee_medical = EmployeeMedical::where('status','=',1)->orderBy('emp_id', 'asc')
                                                ->orderBy('disease_date', 'asc');
        else:
            $employee_medical = EmployeeMedical::where('status','=',1)
                                                ->whereBetween('disease_date',[$from_date,$to_date])
                                                ->orderBy('disease_date', 'asc');
        endif;
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewMedicalReport', compact('employee_medical','employee_project_id'));
    }

    public function viewTrainingReport()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $show_all = Input::get('show_all');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($show_all == 1):
            $trainingsData =Trainings::where('status','=',1)->orderBy('training_date', 'asc');
        else:
            $trainingsData = Trainings::where([['employee_category_id','=',Input::get('employee_category_id')],['region_id','=',Input::get('region_id')]])
                                                ->whereBetween('training_date',[$from_date,$to_date])
                                                ->orderBy('training_date', 'asc');
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTrainingReport', compact('trainingsData'));

    }

    public function viewEmployeeExpReportForm()
    {
		$sub_department = SubDepartment::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
		$Employeeprojects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewEmployeeExpReportForm', compact('department_id','sub_department','Employeeprojects'));

    }

    public function viewGratuityReportForm()
    {
        $employee_regions  = Regions::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $employee_category = EmployeeCategory::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        $Employeeprojects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewGratuityReportForm', compact('employee_category', 'employee_regions','Employeeprojects'));
    }


    public function viewGratuityReport()
    {
        $employee_project_id = Input::get('employee_project_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $emr_no = Input::get('emr_no');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
            if(Input::get('show_all') == '1'):
                $gratuityDetails =Gratuity::select('*');
            else:
                $gratuityDetails =Gratuity::select('*')->where([['employee_category_id','=',Input::get('employee_category_id')],['region_id','=',Input::get('region_id')]])
                                                        ->whereBetween('date',[$from_date,$to_date]);
            endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewGratuityReport', compact('gratuityDetails','employee_project_id'));

    }

    public function viewEmployeeExpReport()
    {
        $show_all = Input::get('show_all');
        $employee_project_id = Input::get('employee_project_id');
        $emp_id = Input::get('emp_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
     
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if($show_all == 1):
            $employee_detail = Employee::select('id','emp_department_id','emp_sub_department_id','status','emp_joining_date','emp_id','emp_name', 'designation_id','employee_project_id')
                ->orderBy('emp_id');
        elseif($emp_id == 'All' && $employee_project_id == '0'):
            $employee_detail = Employee::select('id','status','emp_joining_date','emp_id','emp_name', 'designation_id','employee_project_id')
                ->where('emp_sub_department_id', '=', Input::get('sub_department_id_1'))
                ->whereBetween('emp_joining_date',[$from_date,$to_date])
                ->orderBy('emp_id');
        elseif($emp_id == 'All' && $employee_project_id != '0'):
            $employee_detail = Employee::select('id','status','emp_joining_date','emp_id','emp_name', 'designation_id','employee_project_id')
                ->where([['emp_sub_department_id', '=', Input::get('sub_department_id_1')],['employee_project_id','=',$employee_project_id]])
                ->whereBetween('emp_joining_date',[$from_date,$to_date])
                ->orderBy('emp_id');
        elseif($emp_id != 'All' && $employee_project_id != '0'):
            $employee_detail = Employee::where([['emp_sub_department_id', '=', Input::get('sub_department_id_1')],['employee_project_id','=',$employee_project_id]])
                ->whereBetween('emp_joining_date',[$from_date,$to_date])
                ->where('emp_id','=',$emp_id)
                ->select('id','status','emp_joining_date','emp_id','emp_name','designation_id','employee_project_id')->orderBy('emp_id');
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeExpReport', compact('department_id','SubDepartment','employee_detail'));
    }
}