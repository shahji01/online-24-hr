<?php
namespace App\Http\Controllers;
use App\Helpers\DashboardHelper;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\DegreeType;
use App\Models\EmployeeOfTheMonth;
use App\Helpers\CommonHelper;
use App\Models\EmployeePromotion;
use App\Models\EmployeeEducationalData;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Policies;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use \Cache;

use Illuminate\Pagination\LengthAwarePaginator;
class DashboardDataCallController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function basicInfo(){
		$employeeResponse = [];
		$name = Input::get('name');
		$p_email = Input::get('p_email');
		$cnic = Input::get('cnic');
		$phone = Input::get('phone');
		$dob = Input::get('dob');
		$emp_id = Input::get('emp_id');

		$convertDob = date("Y-m-d", strtotime($dob));

		$data['emp_name'] = $name;
		$data['professional_email'] = $p_email;
		$data['emp_cnic'] = $cnic;
		$data['emp_contact_no'] = $phone;
		$data['emp_date_of_birth'] = $convertDob;

		CommonHelper::companyDatabaseConnection(Input::get('m'));
		$check_cnic = Employee::where([['emp_cnic','=',$cnic],['emp_id','!=',$emp_id]]);
		if($check_cnic->count() == 0){
			Employee::where('emp_id','=',$emp_id)->update($data);
			$employeeData = Employee::where('emp_id','=',$emp_id);
			CommonHelper::reconnectMasterDatabase();
			return $employeeResponse = [$employeeData->value('emp_name'),$employeeData->value('professional_email'),$employeeData->value('emp_cnic'),$employeeData->value('emp_contact_no'),$employeeData->value('emp_date_of_birth')];

		}
		else{
            CommonHelper::reconnectMasterDatabase();
			echo '1';
			return;
		}
    }

	public function checkingPassword(){
		$emp_id = Input::get('emp_id');
		$p_user = Input::get('p_user');
		$bycrpt_password = bcrypt($p_user);

		$users = DB::table('users')->where('emp_id','=',$emp_id);

		if (Hash::check($p_user, $users->value('password'))) {
			//The passwords match...
			echo 'matched';
		}
		else{
			echo 'not matched';
		}
		return;
	}

//	public function oldfilterUserDashboard()
//	{
//		$leave_type = Cache::get('leave_type');
//		$m = Input::get('m');
//		$DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
//
//		$emp_data = Cache::get('employee')[Input::get('employee_id')];
//
//		CommonHelper::companyDatabaseConnection($m);
//		$dashboard_home_data = HrHelper::dashboardHomeData();
//		$EmployeePromotion = EmployeePromotion::where([['employee_id','=',$emp_data->id],['status','=',1]])->orderBy('id','desc');
//		$team_members = Employee::select('id','emp_id','emp_name')->where('reporting_manager_id',Auth::user()->employee_id);
//		// echo Auth::user()->acc_type;
//		// exit();
//		if(Auth::user()->acc_type == 'client')// && Auth::user()->employee_id != Input::get('employee_id') && $team_members->count() == 0)
//		{ return '<span style="color:red"><b>Something went wrong !</b></span>'; }
//
//
//		$employee_time_period = Employee::select('id','emp_id','probation_expire_date','emp_name','sub_department_id','date')->where('status','=',1)->get();
//
//		$emp = Employee::select('id','emp_id','leaves_policy_id','attendance_id')->where([['id', '=', Input::get('employee_id')]]);
//		$attendance_id = $emp->value('attendance_id');
//		$leaves_policy_id = $emp->value('leaves_policy_id');
//		$id = $emp->value('id');
//		CommonHelper::reconnectMasterDatabase();
//
//		if($leaves_policy_id != '0' && $leaves_policy_id != ''){
//
//			$leaves_policy = DB::table('leaves_policy')
//				//->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
//				->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//				->select('leaves_policy.*', 'leaves_data.*')
//				->where([['leaves_policy.id', '=', $leaves_policy_id]])
//				->get();
//
//
//			$leaves_policy_validatity = DB::table('leaves_policy')
//				//->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
//				->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
//				->select('leaves_policy.id', 'leaves_data.id')
//				->where([['leaves_policy.id', '=', $leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
//				->count();
//
//			//echo Auth::user()->emp_id; die();
//			$total_leaves = DB::table("leaves_data")
//				->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
//				->where([['leaves_policy_id', '=', $leaves_policy_id]])
//				->first();
//
//			$taken_leaves = DB::table("leave_application_data")
//				->select(DB::raw("SUM(no_of_days) as taken_leaves"))
//				->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
//				->where([['leave_application.employee_id', '=', $id], ['leave_application.status', '=', '1'],
//					['leave_application.approval_status', '=', '2']])
//				->first();
//
//			CommonHelper::companyDatabaseConnection(Input::get('m'));
//
//			CommonHelper::reconnectMasterDatabase();
//			$getCurrentLeavePolicyYear = date('Y',strtotime($leaves_policy[0]->policy_date_from));
//			$date = strtotime($getCurrentLeavePolicyYear.' -1 year');
//			$getPreviousLeavePolicyYear = date('Y', $date);
//			$getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear.'%');
//			$getPreviousUsedAnnualLeavesBalance = 0;
//			$getPreviousUsedCasualLeavesBalance = 0;
//			if($getPreviousLeavePolicy->count() > 0 ):
//				// print_r($getPreviousLeavePolicyId->first()->id);
//				$getPreviousLeavePolicyId=$getPreviousLeavePolicy->first();
//
//				$getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',1],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
//				$getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',3],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
//				$getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
//					->select(DB::raw("SUM(no_of_days) as no_of_days"))
//					->where([['employee_id','=',Input::get('employee_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','1']])
//					->first();
//				$getPreviousUsedCasualLeaves = DB::table("leave_application_data")
//					->select(DB::raw("SUM(no_of_days) as no_of_days"))
//					->where([['employee_id','=',Input::get('employee_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','3']])
//					->first();
//
//				$getPreviousUsedAnnualLeavesBalance =  $getPreviousAnnualLeaves-$getPreviousUsedAnnualLeaves->no_of_days;
//				$getPreviousUsedCasualLeavesBalance =$getPreviousCasualLeaves-$getPreviousUsedCasualLeaves->no_of_days;
//
//			endif;
//			$WithoutLeavePolicy = [];
//			$WithoutLeavePolicy[] = 'have';
//
//			return view('Dashboard.ajax_pages.filterUserDashboard',compact('dashboard_home_data','leave_type','getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','attendance_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data','EmployeePromotion', 'total_leaves', 'taken_leaves','WithoutLeavePolicy','employee_time_period','DegreeType'));
//
//		}
//		else{
//			$WithoutLeavePolicy = [];
//			$WithoutLeavePolicy[] = 'Select Leave Policy';
//			$leaves_policy_validatity = '0';
//			$leaves_policy  = '0';
//			return view('Dashboard.ajax_pages.filterUserDashboard',compact('dashboard_home_data','EmployeeOfTheMonth','leave_type','upcoming_birthdays_detail','empWorkAnvs', 'leaves_policy_validatity', 'leaves_policy', 'WithoutLeavePolicy','employee_time_period','DegreeType'));
//		}
//	}

	public function filterUserDashboard()
	{
//		$leave_type = Cache::get('leave_type');
		$emp_data = Cache::get('employee')[Input::get('employee_id')];
//		$all_employees = Cache::get('employee');
//		$designations = Cache::get('designation');
//		$departments = Cache::get('department');
//		$marital_status = Cache::get('marital_status');
//		$job_types = Cache::get('job_type');
//		$degree_types = Cache::get('degree_type');

		$attendance_id = $emp_data->attendance_id;
		$leaves_policy_id = $emp_data->leaves_policy_id;
		$id = $emp_data->id;
		$leaves = HrHelper::viewLeaveBalances($emp_data->id,$leaves_policy_id, 1);


		$m = Input::get('m');

		CommonHelper::companyDatabaseConnection($m);

		$team_members = Employee::select('id','emp_id','emp_name')->where('reporting_manager_id',Auth::user()->employee_id);

		if(Auth::user()->acc_type == 'user' && Auth::user()->employee_id != Input::get('employee_id') && $team_members->count() == 0)
		{ return '<span style="color:red"><b>Something went wrong !</b></span>'; }

		$employee_time_period = Employee::select('id','emp_id','probation_expire_date','emp_name','sub_department_id','date')->where('status','=',1)->get();
		$team_lead_exists = Employee::where([['reporting_manager_id','=',Auth::user()->employee_id],['status','=',1]]);

		CommonHelper::reconnectMasterDatabase();

		return view('Dashboard.ajax_pages.filterUserDashboard',compact('team_lead_exists','emp_data','leaves'));
	}


	function addEducationDetails(Request $request){

		$employee_id = Input::get('employee_id');

		CommonHelper::companyDatabaseConnection(Input::get('m'));
		if (!empty(Input::get('education_data'))):
			foreach (Input::get('education_data') as $educationalRow):
				$educationalData['employee_id'] = $employee_id;
				if(Input::get('institute_name_' . $educationalRow . '') == ''){
					break;

				}
				$educationalData['institute_name'] = Input::get('institute_name_' . $educationalRow . '');
				$educationalData['year_of_admission'] = Input::get('year_of_admission_' . $educationalRow . '');
				$educationalData['year_of_passing'] = Input::get('year_of_passing_' . $educationalRow . '');
				$educationalData['degree_type'] = Input::get('degree_type_' . $educationalRow . '');
				$educationalData['status'] = 1;
				$educationalData['username'] = Auth::user()->name;
				$educationalData['date'] = date("Y-m-d");
				$educationalData['time'] = date("H:i:s");

				if (request()->file('document_' . $educationalRow . '')) {

					if (!empty(request()->file('document_' . $educationalRow . ''))) {
						$counter=1;
						$file_name = 'emp_id' . $employee_id . '_unisons_' . $counter . '.' . request()->file('document_' . $educationalRow . '')->getClientOriginalExtension();
						$path = request()->file('document_' . $educationalRow . '')->storeAs('uploads/employee_education_documents', $file_name);

						$educationalData['file_name'] = $file_name;
						$educationalData['file_type'] = request()->file('document_' . $educationalRow . '')->getClientOriginalExtension();
						$educationalData['file_path'] = 'app/' . $path;
					}
				}

				DB::table('employee_educational_data')->insert($educationalData);

			endforeach;
		endif;
		CommonHelper::reconnectMasterDatabase();

	}

	public function filterTeamVise(){

		$emp_id = Input::get('emp_id');
		$m = Input::get('m');
		$DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
		CommonHelper::companyDatabaseConnection($m);

		$upcoming_birthdays_detail = DB::select( DB::raw("SELECT id,emp_name,emp_date_of_birth,emp_sub_department_id FROM employee where DATE_FORMAT(emp_date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +1 month), '%m-%d') and status = 1 ORDER BY MONTH(emp_date_of_birth), DAYOFMONTH(emp_date_of_birth)"));
		$EmployeeOfTheMonth = EmployeeOfTheMonth::where([['status','=', '1'],['month','=',date('m')],['year','=',date('Y')]])->orderBy('id');
		$empWorkAnvs = DB::select( DB::raw("SELECT id,emp_name,emp_joining_date FROM employee where status = 1 and DATE_FORMAT(emp_joining_date, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_joining_date, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +10 day), '%m-%d') ORDER BY MONTH(emp_joining_date), DAYOFMONTH(emp_joining_date)"));
		$emp = Employee::select('id','emp_id','leaves_policy_id')->where([['emp_id', '=', Input::get('emp_id')]])->first();
		$attendance_machine_id = $emp->emp_id;
		CommonHelper::reconnectMasterDatabase();
		if($emp->leaves_policy_id != '0'){

			$leaves_policy = DB::table('leaves_policy')
				//->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
				->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
				->select('leaves_policy.*', 'leaves_data.*')
				->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
				->get();

			$leaves_policy_validatity = DB::table('leaves_policy')
				//->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
				->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
				->select('leaves_policy.id', 'leaves_data.id')
				->where([['leaves_policy.id', '=', $emp->leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
				->count();

			//echo Auth::user()->emp_id; die();
			$total_leaves = DB::table("leaves_data")
				->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
				->where([['leaves_policy_id', '=', $leaves_policy[0]->leaves_policy_id]])
				->first();

			$taken_leaves = DB::table("leave_application_data")
				->select(DB::raw("SUM(no_of_days) as taken_leaves"))
				->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
				->where([['leave_application.emp_id', '=', $emp->emp_id], ['leave_application.status', '=', '1'],
					['leave_application.approval_status', '=', '2']])
				->first();

			CommonHelper::companyDatabaseConnection(Input::get('m'));
			$emp_data = Employee::where([['emp_id', '=', Auth::user()->emp_id]])->orderBy('id')->first();
			CommonHelper::reconnectMasterDatabase();
			$getCurrentLeavePolicyYear = date('Y',strtotime($leaves_policy[0]->policy_date_from));
			$date = strtotime($getCurrentLeavePolicyYear.' -1 year');
			$getPreviousLeavePolicyYear = date('Y', $date);
			$getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear.'%');
			$getPreviousUsedAnnualLeavesBalance = 0;
			$getPreviousUsedCasualLeavesBalance = 0;
			if($getPreviousLeavePolicy->count() > 0 ):
				// print_r($getPreviousLeavePolicyId->first()->id);
				$getPreviousLeavePolicyId=$getPreviousLeavePolicy->first();

				$getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',1],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
				$getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',3],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
				$getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
					->select(DB::raw("SUM(no_of_days) as no_of_days"))
					->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','1']])
					->first();
				$getPreviousUsedCasualLeaves = DB::table("leave_application_data")
					->select(DB::raw("SUM(no_of_days) as no_of_days"))
					->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','3']])
					->first();

				$getPreviousUsedAnnualLeavesBalance =  $getPreviousAnnualLeaves-$getPreviousUsedAnnualLeaves->no_of_days;
				$getPreviousUsedCasualLeavesBalance =$getPreviousCasualLeaves-$getPreviousUsedCasualLeaves->no_of_days;

			endif;
			$WithoutLeavePolicy = [];
			$WithoutLeavePolicy[] = 'have';
			return view('Dashboard.ajax_pages.filterTeamVise',compact('EmployeeOfTheMonth','upcoming_birthdays_detail','empWorkAnvs','getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves','WithoutLeavePolicy','DegreeType'));
		}
		else{
			$WithoutLeavePolicy = [];
			$WithoutLeavePolicy[] = 'Select Leave Policy';
			$leaves_policy_validatity = '0';
			$leaves_policy  = '0';
			return view('Dashboard.ajax_pages.filterTeamVise',compact('EmployeeOfTheMonth','upcoming_birthdays_detail','empWorkAnvs','WithoutLeavePolicy','DegreeType'));
		}
	}
	
	function viewDashboardComponent()
	{
		$id = Input::get('id');
		$m = Input::get('m');
		$leave_type = Cache::get('leave_type');
		$leave_day_type = [1 => 'Full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];

		$emp_data = Cache::get('employee')[Input::get('employee_id')];
		$leaves_policy_id = $emp_data->leaves_policy_id ;
		$employee_id = $emp_data->id ;
		$emp_id = Input::get('emp_id');

		if($id == 'info'):

			$marital_status = Cache::get('marital_status');
			$job_types = Cache::get('job_type');
			return view('Dashboard.dashboardComponent.basicInfo', compact('emp_data','marital_status', 'job_types','m'));

		elseif($id == 'leaves'):

			$leaves = HrHelper::viewLeaveBalances($emp_data->id,$leaves_policy_id, 1);
			$reasons = DB::table('reasons')->where('status',1)->get();

			$leaves_policy = DB::table('leaves_policy')
				//->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
				->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
				->select('leaves_policy.*', 'leaves_data.*')
				->where([['leaves_policy.id', '=', $leaves_policy_id]])
				->orderBy('leaves_data.leave_type_id')->get();

			$leaves_policy_validatity = DB::table('leaves_policy')
				//->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
				->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
				->select('leaves_policy.id', 'leaves_data.id')
				->where([['leaves_policy.id', '=', $leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
				->count();
			$WithoutLeavePolicy = [];
			$WithoutLeavePolicy[] = 'have';
			return view('Dashboard.dashboardComponent.leaves', compact('leaves_policy','leaves_policy_validatity','WithoutLeavePolicy','emp_data','leave_type',
				'leaves','reasons','m'));

		elseif($id == 'myLeaves'):

			$leave_application_request_list = DB::table('leave_application AS la')
				->join('leave_application_data AS lad', 'la.id', '=', 'lad.leave_application_id')
				->select('la.*','lad.no_of_days','lad.from_date','lad.to_date','lad.first_second_half','lad.first_second_half_date')
				->where('la.employee_id','=',$emp_data->id)
				->where('la.view','=','yes')
				->where('la.status','=','1')
				->get();


			return view('Dashboard.dashboardComponent.myLeaves', compact('leave_application_request_list','leave_day_type','emp_data','leave_type','m'));

		elseif($id == 'teamLeaves'):

			$all_employees = Cache::get('employee');
			$line_manager_employees = [];
			CommonHelper::companyDatabaseConnection($m);
			$line_manager_emp = Employee::select('id')->where([['reporting_manager_id','=',$employee_id],['status','=',1]])->get();
			foreach($line_manager_emp as $value){
				$line_manager_employees[] = $value->id;
			}
			CommonHelper::reconnectMasterDatabase();

			$leave_application_request_list = DB::table('leave_application AS la')
				->join('leave_application_data AS lad', 'la.id', '=', 'lad.leave_application_id')
				->select('la.*','lad.no_of_days','lad.from_date','lad.to_date','lad.first_second_half','lad.first_second_half_date')
				->whereIn('la.employee_id',$line_manager_employees)
				->where('la.view','=','yes')
				->where('la.status','=','1')
				->get();
			return view('Dashboard.dashboardComponent.teamLeaves', compact('leave_application_request_list','leave_day_type','emp_data','all_employees','leave_type','m'));

		elseif($id == 'education'):

			$degree_types = Cache::get('degree_type');
			CommonHelper::companyDatabaseConnection($m);
			$employee_education = EmployeeEducationalData::where([['employee_id','=',$emp_data->id],['status','=',1]])->get();
			CommonHelper::reconnectMasterDatabase();
			return view('Dashboard.dashboardComponent.education', compact('employee_education','degree_types','leave_type','employee_id','emp_id','m'));

		elseif($id == 'jobHistory'):

			$designations = Cache::get('designation');
			CommonHelper::companyDatabaseConnection($m);
			$employee_promotion = EmployeePromotion::where([['employee_id','=',$emp_data->id],['status','=',1]])->orderBy('id','desc');
			CommonHelper::reconnectMasterDatabase();
			return view('Dashboard.dashboardComponent.jobHistory', compact('employee_promotion','designations','emp_data','m'));

		elseif($id == 'policies'):

			CommonHelper::companyDatabaseConnection($m);
			$policies = Policies::where([['status', '=', 1],['category_id', '=', 1]])->get();
			$forms = Policies::where([['status', '=', 1],['category_id', '=', 2]])->get();
			CommonHelper::reconnectMasterDatabase();
			return view('Dashboard.dashboardComponent.policies', compact('policies','forms','m'));

		elseif($id == 'team'):

			$designations = Cache::get('designation');
			$departments = Cache::get('department');
			CommonHelper::companyDatabaseConnection($m);
			$team_lead_exists = Employee::where([['reporting_manager_id','=',$employee_id],['status','=',1]]);
			CommonHelper::reconnectMasterDatabase();
			return view('Dashboard.dashboardComponent.teams', compact('team_lead_exists','designations','departments','m'));
		endif;

	}

	public function employeeAssignProjectDetail(Request $request){
		$m = $request->input('m');
		$dfProjectId = $request->input('dfProjectId');
		$dfTaskId = $request->input('dfTaskId');
		$emp_id = $request->input('emp_id');
		CommonHelper::companyDatabaseConnection($m);
			$projectList = DB::table('company_projects as cp')
				->join('assign_employee_project as aep', 'cp.id', '=', 'aep.project_id')
				->join('customers as c','cp.customer_id','=','c.id')
				->where('cp.status', 1)
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->where('aep.employee_id',$emp_id)
				->select('cp.*','c.customer_name')
				->get();
		CommonHelper::reconnectMasterDatabase();
		return view('Dashboard.employeeAssignProjectDetail',compact('projectList','m'));
	}

	public function employeeAssignProjectTaskDetail(Request $request){
		$m = $request->input('m');
		$dfProjectId = $request->input('dfProjectId');
		$dfTaskId = $request->input('dfTaskId');
		$emp_id = $request->input('emp_id');
		CommonHelper::companyDatabaseConnection($m);
		$projectTasks = DB::table('project_tasks as pt')
			->select('pt.*','cp.project_name','cp.project_type','e.emp_name',
			DB::raw("
					CASE 
						WHEN pt.start_date IS NULL OR pt.end_date IS NULL THEN 0
						WHEN pt.start_date = pt.end_date THEN 1
						ELSE DATEDIFF(pt.end_date, pt.start_date) + 1
					END AS num_days
				"),
				'twho.id as twho_id',
				'twho.start_date as twho_start_date',
				'twho.start_time as twho_start_time',
				'twho.end_date as twho_end_date',
				'twho.end_time as twho_end_time',
				'twho.testing_date as twho_testing_date',
				'twho.testing_time as twho_testing_time',
				'twho.reassign_date as twho_reassign_date',
				'twho.reassign_time as twho_reassign_time',
				'twho.completion_date as twho_completion_date',
				'twho.completion_time as twho_completion_time',
				'twht.id as twht_id',
				'twht.start_date as twht_start_date',
				'twht.start_time as twht_start_time',
				'twht.end_date as twht_end_date',
				'twht.end_time as twht_end_time',
				'twht.testing_date as twht_testing_date',
				'twht.testing_time as twht_testing_time',
				'twht.reassign_date as twht_reassign_date',
				'twht.reassign_time as twht_reassign_time',
				'twht.completion_date as twht_completion_date',
				'twht.completion_time as twht_completion_time'
			)
			->join('company_projects AS cp', 'pt.project_id','=', 'cp.id')
			->join('employee AS e', 'pt.employee_id','=', 'e.id')
			->leftJoin(DB::raw('(SELECT task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY task_id ORDER BY id ASC) AS rn FROM task_work_history) AS twho'), function($join) {
				$join->on('pt.id', '=', 'twho.task_id')
					->where('twho.rn', '=', 1);
			})
			->leftJoin(DB::raw('(SELECT task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY task_id ORDER BY id DESC) AS rn FROM task_work_history) AS twht'), function($join) {
				$join->on('pt.id', '=', 'twht.task_id')
					->where('twht.rn', '=', 1);
			})
			->when($emp_id != '', function ($q) use ($emp_id){
				return $q->where('pt.employee_id','=',$emp_id);
			})
			->orderBy('pt.id', 'ASC')
			->get();
		CommonHelper::reconnectMasterDatabase();

		return view('Dashboard.employeeAssignProjectTaskDetail',compact('projectTasks','m'));
	}

	public function employeeAssignProjectSubTaskDetail(Request $request){
		$m = $request->input('m');
		$dfProjectId = $request->input('dfProjectId');
		$dfTaskId = $request->input('dfTaskId');
		$emp_id = $request->input('emp_id');
		CommonHelper::companyDatabaseConnection($m);
            $projectSubTasks = DB::table('project_sub_tasks as pst')
                ->select(
                    'pst.*',
                    'cp.project_type',
                    'cp.project_name',
                    'e.emp_name',
                    'pt.task_name',
                    DB::raw("
                        CASE 
                            WHEN pst.start_date IS NULL OR pst.end_date IS NULL THEN 0
                            WHEN pst.start_date = pst.end_date THEN 1
                            ELSE DATEDIFF(pst.end_date, pst.start_date) + 1
                        END AS num_days
                    "),
                    'stwho.id as stwho_id',
                    'stwho.start_date as stwho_start_date',
                    'stwho.start_time as stwho_start_time',
                    'stwho.end_date as stwho_end_date',
                    'stwho.end_time as stwho_end_time',
                    'stwho.testing_date as stwho_testing_date',
                    'stwho.testing_time as stwho_testing_time',
                    'stwho.reassign_date as stwho_reassign_date',
                    'stwho.reassign_time as stwho_reassign_time',
                    'stwho.completion_date as stwho_completion_date',
                    'stwho.completion_time as stwho_completion_time',
                    'stwht.id as stwht_id',
                    'stwht.start_date as stwht_start_date',
                    'stwht.start_time as stwht_start_time',
                    'stwht.end_date as stwht_end_date',
                    'stwht.end_time as stwht_end_time',
                    'stwht.testing_date as stwht_testing_date',
                    'stwht.testing_time as stwht_testing_time',
                    'stwht.reassign_date as stwht_reassign_date',
                    'stwht.reassign_time as stwht_reassign_time',
                    'stwht.completion_date as stwht_completion_date',
                    'stwht.completion_time as stwht_completion_time'
                )
                ->join('project_tasks as pt', 'pst.task_id', '=', 'pt.id')
                ->join('company_projects as cp', 'pt.project_id', '=', 'cp.id')
                ->join('employee as e', 'pt.employee_id', '=', 'e.id')
                ->leftJoin(DB::raw('(SELECT sub_task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY sub_task_id ORDER BY id ASC) AS rn FROM sub_task_work_history) AS stwho'), function($join) {
                    $join->on('pst.id', '=', 'stwho.sub_task_id')
                        ->where('stwho.rn', '=', 1);
                })
                ->leftJoin(DB::raw('(SELECT sub_task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY sub_task_id ORDER BY id DESC) AS rn FROM sub_task_work_history) AS stwht'), function($join) {
                    $join->on('pst.id', '=', 'stwht.sub_task_id')
                        ->where('stwht.rn', '=', 1);
                })
                ->when($emp_id != '', function ($q) use ($emp_id){
                    return $q->where('pst.employee_id','=',$emp_id);
                })
                ->orderBy('pst.id', 'ASC')
                ->get();
        CommonHelper::reconnectMasterDatabase();
		return view('Dashboard.employeeAssignProjectSubTaskDetail',compact('projectSubTasks','m'));
	}

	public function employeeProjectTaskDashboardDetail(Request $request){
		$m = $request->input('m');
		$dfProjectId = $request->input('dfProjectId');
		$dfTaskId = $request->input('dfTaskId');
		$emp_id = $request->input('emp_id');
		$type = $request->input('type');
		CommonHelper::companyDatabaseConnection($m);
		$query = DB::table('project_tasks as pt')
			->select('pt.*','cp.project_name','cp.project_type','e.emp_name',
			DB::raw("
					CASE 
						WHEN pt.start_date IS NULL OR pt.end_date IS NULL THEN 0
						WHEN pt.start_date = pt.end_date THEN 1
						ELSE DATEDIFF(pt.end_date, pt.start_date) + 1
					END AS num_days
				"),
				'twho.id as twho_id',
				'twho.start_date as twho_start_date',
				'twho.start_time as twho_start_time',
				'twho.end_date as twho_end_date',
				'twho.end_time as twho_end_time',
				'twho.testing_date as twho_testing_date',
				'twho.testing_time as twho_testing_time',
				'twho.reassign_date as twho_reassign_date',
				'twho.reassign_time as twho_reassign_time',
				'twho.completion_date as twho_completion_date',
				'twho.completion_time as twho_completion_time',
				'twht.id as twht_id',
				'twht.start_date as twht_start_date',
				'twht.start_time as twht_start_time',
				'twht.end_date as twht_end_date',
				'twht.end_time as twht_end_time',
				'twht.testing_date as twht_testing_date',
				'twht.testing_time as twht_testing_time',
				'twht.reassign_date as twht_reassign_date',
				'twht.reassign_time as twht_reassign_time',
				'twht.completion_date as twht_completion_date',
				'twht.completion_time as twht_completion_time'
			)
			->join('company_projects AS cp', 'pt.project_id','=', 'cp.id')
			->join('employee AS e', 'pt.employee_id','=', 'e.id')
			->leftJoin(DB::raw('(SELECT task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY task_id ORDER BY id ASC) AS rn FROM task_work_history) AS twho'), function($join) {
				$join->on('pt.id', '=', 'twho.task_id')
					->where('twho.rn', '=', 1);
			})
			->leftJoin(DB::raw('(SELECT task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY task_id ORDER BY id DESC) AS rn FROM task_work_history) AS twht'), function($join) {
				$join->on('pt.id', '=', 'twht.task_id')
					->where('twht.rn', '=', 1);
			})
			->when($emp_id != '', function ($q) use ($emp_id){
				return $q->where('pt.employee_id','=',$emp_id);
			});
			// Apply conditional logic for task type
			switch ($type) {
				case 1:
					$query->whereNotNull('pt.start_date');
					break;
				case 2:
					$query->where('pt.task_status', 2);
					break;
				case 3:
					$query->where('pt.task_status', 1);
					break;
				default:
					$query->where('pt.task_status', 4);
					break;
			}
		
			$projectTasks = $query->get();
		CommonHelper::reconnectMasterDatabase();
		return view('Dashboard.employeeProjectTaskDashboardDetail',compact('m','projectTasks'));
	}

	public function employeeProjectSubTaskDashboardDetail(Request $request){
		$m = $request->input('m');
		$dfProjectId = $request->input('dfProjectId');
		$dfTaskId = $request->input('dfTaskId');
		$emp_id = $request->input('emp_id');
		$type = $request->input('type');
		CommonHelper::companyDatabaseConnection($m);
			$query = DB::table('project_sub_tasks as pst')
                ->select(
                    'pst.*',
                    'cp.project_type',
                    'cp.project_name',
                    'e.emp_name',
                    'pt.task_name',
                    DB::raw("
                        CASE 
                            WHEN pst.start_date IS NULL OR pst.end_date IS NULL THEN 0
                            WHEN pst.start_date = pst.end_date THEN 1
                            ELSE DATEDIFF(pst.end_date, pst.start_date) + 1
                        END AS num_days
                    "),
                    'stwho.id as stwho_id',
                    'stwho.start_date as stwho_start_date',
                    'stwho.start_time as stwho_start_time',
                    'stwho.end_date as stwho_end_date',
                    'stwho.end_time as stwho_end_time',
                    'stwho.testing_date as stwho_testing_date',
                    'stwho.testing_time as stwho_testing_time',
                    'stwho.reassign_date as stwho_reassign_date',
                    'stwho.reassign_time as stwho_reassign_time',
                    'stwho.completion_date as stwho_completion_date',
                    'stwho.completion_time as stwho_completion_time',
                    'stwht.id as stwht_id',
                    'stwht.start_date as stwht_start_date',
                    'stwht.start_time as stwht_start_time',
                    'stwht.end_date as stwht_end_date',
                    'stwht.end_time as stwht_end_time',
                    'stwht.testing_date as stwht_testing_date',
                    'stwht.testing_time as stwht_testing_time',
                    'stwht.reassign_date as stwht_reassign_date',
                    'stwht.reassign_time as stwht_reassign_time',
                    'stwht.completion_date as stwht_completion_date',
                    'stwht.completion_time as stwht_completion_time'
                )
                ->join('project_tasks as pt', 'pst.task_id', '=', 'pt.id')
                ->join('company_projects as cp', 'pt.project_id', '=', 'cp.id')
                ->join('employee as e', 'pt.employee_id', '=', 'e.id')
                ->leftJoin(DB::raw('(SELECT sub_task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY sub_task_id ORDER BY id ASC) AS rn FROM sub_task_work_history) AS stwho'), function($join) {
                    $join->on('pst.id', '=', 'stwho.sub_task_id')
                        ->where('stwho.rn', '=', 1);
                })
                ->leftJoin(DB::raw('(SELECT sub_task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY sub_task_id ORDER BY id DESC) AS rn FROM sub_task_work_history) AS stwht'), function($join) {
                    $join->on('pst.id', '=', 'stwht.sub_task_id')
                        ->where('stwht.rn', '=', 1);
                })
                ->when($emp_id != '', function ($q) use ($emp_id){
                    return $q->where('pst.employee_id','=',$emp_id);
                });

				switch ($type) {
					case 1:
						$query->whereNotNull('pst.start_date');
						break;
					case 2:
						$query->where('pst.sub_task_status', 2);
						break;
					case 3:
						$query->where('pst.sub_task_status', 1);
						break;
					default:
						$query->where('pst.sub_task_status', 4);
						break;
				}
			$projectSubTasks = $query->get();
		CommonHelper::reconnectMasterDatabase();
		return view('Dashboard.employeeProjectSubTaskDashboardDetail',compact('m','projectSubTasks'));
	}

	public function filterClientSummaryDashboardData(Request $request){
		$m = $request->input('m');
		$dfProjectId = $request->input('dfProjectId');
		$customer_id = $request->input('customer_id');
		CommonHelper::companyDatabaseConnection($m);
			$projectList = DB::table('company_projects as cp')
				->leftJoin('assign_employee_project as aep', 'cp.id', '=', 'aep.project_id')
				->join('customers as c','cp.customer_id','=','c.id')
				->where('cp.status', 1)
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->when($customer_id != '', function ($q) use ($customer_id){
                    return $q->where('cp.customer_id','=',$customer_id);
                })
				->select('cp.*','c.customer_name')
				->get();
		CommonHelper::reconnectMasterDatabase();
		return view('Dashboard.filterClientDashboard',compact('projectList','m'));
	}

	public function filterTMSummaryDashboard(Request $request){
		$employee_id = $request->input('employee_id');
		$userId = Auth::user()->id;
		$m = $request->input('m');
		CommonHelper::companyDatabaseConnection($m);
			$projectList = DB::table('company_projects as cp')
				->join('assign_employee_project as aep', 'cp.id', '=', 'aep.project_id')
				->where('cp.status', 1)
				->where('aep.employee_id',$employee_id)
				->select('cp.*')
				->get();
		CommonHelper::reconnectMasterDatabase();
		return view('Dashboard.filterTMSummaryDashboard',compact('projectList'));
	}
	public function filterTMSummaryDashboardData(Request $request){
		$m = $request->input('m');
		$emp_id = $request->input('emp_id');
		$dfProjectId = $request->input('dfProjectId');
		CommonHelper::companyDatabaseConnection($m);
			$projectList = DB::table('company_projects as cp')
				->join('assign_employee_project as aep', 'cp.id', '=', 'aep.project_id')
				->join('customers as c','cp.customer_id','=','c.id')
				->where('cp.status', 1)
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->where('aep.employee_id',$emp_id)
				->select('cp.*','c.customer_name')
				->get();
		CommonHelper::reconnectMasterDatabase();
		return view('Dashboard.filterTMSummaryDashboardDataTwo',compact('projectList','m'));
	}

	public function filterTMSDashboard(Request $request){
		$employee_id = $request->input('employee_id');
		$userId = Auth::user()->id;
		$m = $request->input('m');
		CommonHelper::companyDatabaseConnection($m);
			$projectList = DB::table('company_projects as cp')
				->join('assign_employee_project as aep', 'cp.id', '=', 'aep.project_id')
				->where('cp.status', 1)
				->where('cp.project_status', 1)
				->where('aep.employee_id',$employee_id)
				->select('cp.*')
				->get();
		CommonHelper::reconnectMasterDatabase();
		return view('Dashboard.filterTMSDashboard',compact('projectList'));
	}

	public function filterTMSDashboardData(Request $request){
		$m = $request->input('m');
		$emp_id = $request->input('emp_id');
		$dfProjectId = $request->input('dfProjectId');
		$dfTaskId = $request->input('dfTaskId');
		$df_from_date = $request->input('df_from_date');
		$df_to_date = $request->input('df_to_date');
		CommonHelper::companyDatabaseConnection($m);
			$dTotalAssignProject = DB::table('assign_employee_project as aep')
				->join('company_projects as cp', 'aep.project_id', '=', 'cp.id')
				->where('aep.employee_id', $emp_id)
				->count('cp.id');
			$dTotalAssignProjectTask = DB::table('project_tasks')
				->where('employee_id', $emp_id)
				->count('id');
			$dTotalAssignProjectSubTask = DB::table('project_sub_tasks')
				->where('employee_id', $emp_id)
				->count('id');
			$dAttendedProjectTask = DB::table('project_tasks')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->whereNotNull('start_date')
				->where('employee_id', $emp_id)
				->count();
			$dCompletedProjectTask = DB::table('project_tasks')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->where('task_status',2)
				->where('employee_id', $emp_id)
				->count();
			$dPendingProjectTask = DB::table('project_tasks')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->where('task_status',1)
				->where('employee_id', $emp_id)
				->count();
			$dInprogressProjectTask = DB::table('project_tasks')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->where('task_status',4)
				->where('employee_id', $emp_id)
				->count();

			$dAttendedProjectSubTask = DB::table('project_sub_tasks')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->when($dfTaskId != '', function ($q) use ($dfTaskId){
                    return $q->where('task_id','=',$dfTaskId);
                })
				->whereNotNull('start_date')
				->where('employee_id', $emp_id)
				->count();
			$dCompletedProjectSubTask = DB::table('project_sub_tasks')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->when($dfTaskId != '', function ($q) use ($dfTaskId){
                    return $q->where('task_id','=',$dfTaskId);
                })
				->where('sub_task_status',2)
				->where('employee_id', $emp_id)
				->count();
			$dPendingProjectSubTask = DB::table('project_sub_tasks')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->when($dfTaskId != '', function ($q) use ($dfTaskId){
                    return $q->where('task_id','=',$dfTaskId);
                })
				->where('sub_task_status',1)
				->where('employee_id', $emp_id)
				->count();
			$dInprogressProjectSubTask = DB::table('project_sub_tasks')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('project_id','=',$dfProjectId);
                })
				->when($dfTaskId != '', function ($q) use ($dfTaskId){
                    return $q->where('task_id','=',$dfTaskId);
                })
				->where('sub_task_status',4)
				->where('employee_id', $emp_id)
				->count();
		CommonHelper::reconnectMasterDatabase();
		$data['dTotalAssignProject'] = $dTotalAssignProject;
		$data['dTotalAssignProjectTask'] = $dTotalAssignProjectTask;
		$data['dTotalAssignProjectSubTask'] = $dTotalAssignProjectSubTask;
		$data['dAttendedProjectTask'] = $dAttendedProjectTask;
		$data['dCompletedProjectTask'] = $dCompletedProjectTask;
		$data['dPendingProjectTask'] = $dPendingProjectTask;
		$data['dInprogressProjectTask'] = $dInprogressProjectTask;
		$data['dAttendedProjectSubTask'] = $dAttendedProjectSubTask;
		$data['dCompletedProjectSubTask'] = $dCompletedProjectSubTask;
		$data['dPendingProjectSubTask'] = $dPendingProjectSubTask;
		$data['dInprogressProjectSubTask'] = $dInprogressProjectSubTask;
		return $data;
		

	}

	public function getProjectTaskSummary(Request $request){
		$m = $request->input('m');
		$emp_id = $request->input('emp_id');
		$dfProjectId = $request->input('dfProjectId');
		$dfTaskId = $request->input('dfTaskId');
		CommonHelper::companyDatabaseConnection($m);
			$getProjectTaskSummary = DB::table('project_tasks as pt')
				->join('company_projects as cp', 'pt.project_id', '=', 'cp.id')
				->join('customers as c', 'cp.customer_id', '=', 'c.id')
				->select('pt.*', 
					DB::raw('DATEDIFF(pt.end_date, CURDATE()) AS days_remaining'), 
					'cp.project_name', 
					'c.customer_name', 
					'c.customer_type', 
					'cp.project_type')
				->where('pt.task_status', '!=', 2)
				->where('pt.employee_id', $emp_id)
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('pt.project_id','=',$dfProjectId);
                })
				->whereRaw('DATEDIFF(pt.end_date, CURDATE()) < 3')
				->get();
		CommonHelper::reconnectMasterDatabase();
		$data = '';
		$counter = 1;
		if(count($getProjectTaskSummary) != 0){
			foreach($getProjectTaskSummary as $gptsRow){
				$projectType = 'Local';
				if($gptsRow->project_type == 2){
					$projectType = 'International';
				}
				$days_remaining = 'bg-info';
				if($gptsRow->days_remaining == 2){
					$days_remaining = 'bg-primary';
				}else if($gptsRow->days_remaining == 1){
					$days_remaining = 'bg-warning';
				}else if($gptsRow->days_remaining <= 0){
					$days_remaining = 'bg-danger';
				}
				$data .= '<tr class="'.$days_remaining.'"><td class="text-center">'.$counter++.'</td><td>'.$gptsRow->customer_name.'</td><td>'.$gptsRow->project_name.'</td><td>'.$projectType.'</td><td>'.$gptsRow->task_name.'</td><td class="text-center">'.$gptsRow->start_date.'</td><td class="text-center">'.$gptsRow->end_date.'</td><td class="text-center">'.$gptsRow->days_remaining.'</td></tr>';
			}
		}else{
			$data .='<tr><th colspan="12" class="text-center">No Record Found....</th></tr>';
		}
		return $data;
	}

	public function getProjectSubTaskSummary(Request $request){
		$m = $request->input('m');
		$emp_id = $request->input('emp_id');
		$dfProjectId = $request->input('dfProjectId');
		$dfTaskId = $request->input('dfTaskId');
		CommonHelper::companyDatabaseConnection($m);
			$getProjectSubTaskSummary = DB::table('project_sub_tasks as pst')
				->join('company_projects as cp', 'pst.project_id', '=', 'cp.id')
				->join('project_tasks as pt', 'pst.task_id', '=', 'pt.id')
				->join('customers as c', 'cp.customer_id', '=', 'c.id')
				->select('pst.*', 
					DB::raw('DATEDIFF(pst.end_date, CURDATE()) AS days_remaining'), 
					'cp.project_name', 
					'c.customer_name', 
					'c.customer_type', 
					'cp.project_type',
					'pt.task_name')
				->where('pst.sub_task_status', '!=', 2)
				->where('pst.employee_id', $emp_id)
				->whereRaw('DATEDIFF(pst.end_date, CURDATE()) < 3')
				->when($dfProjectId != '', function ($q) use ($dfProjectId){
                    return $q->where('pst.project_id','=',$dfProjectId);
                })
				->when($dfTaskId != '', function ($q) use ($dfTaskId){
                    return $q->where('pst.task_id','=',$dfTaskId);
                })
				->get();
		CommonHelper::reconnectMasterDatabase();
		$data = '';
		$counter = 1;
		if(count($getProjectSubTaskSummary) != 0){
			foreach($getProjectSubTaskSummary as $gpstsRow){
				$projectType = 'Local';
				if($gpstsRow->project_type == 2){
					$projectType = 'International';
				}
				$days_remaining = 'bg-info';
				if($gpstsRow->days_remaining == 2){
					$days_remaining = 'bg-primary';
				}else if($gpstsRow->days_remaining == 1){
					$days_remaining = 'bg-warning';
				}else if($gpstsRow->days_remaining <= 0){
					$days_remaining = 'bg-danger';
				}
				$data .= '<tr class="'.$days_remaining.'"><td class="text-center">'.$counter++.'</td><td>'.$gpstsRow->customer_name.'</td><td>'.$gpstsRow->project_name.'</td><td>'.$projectType.'</td><td>'.$gpstsRow->task_name.'</td><td>'.$gpstsRow->sub_task_name.'</td><td class="text-center">'.$gpstsRow->start_date.'</td><td class="text-center">'.$gpstsRow->end_date.'</td><td class="text-center">'.$gpstsRow->days_remaining.'</td></tr>';
			}
		}else{
			$data .='<tr><th colspan="12" class="text-center">No Record Found....</th></tr>';
		}
		return $data;
	}
}
