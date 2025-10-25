<?php
namespace App\Http\Controllers;
use App\Models\SubDepartment;
use App\Models\EmployeeProjects;
use App\Models\Employee;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\EmployeeOfTheMonth;
use App\Http\Requests;
use App\Helpers\CommonHelper;
use App\Models\DegreeType;
use App\Models\Department;
use App\Helpers\DashboardHelper;
use Illuminate\Http\Request;

use Input;
use Auth;
use DB;
use Config;

use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function hrDashboard()
    {
		$donutChartDataEmployee = DashboardHelper::donutChartDataEmployee();
		$verticalBarChartDataEmployeeSalary = DashboardHelper::verticalBarChartDataEmployeeSalary();
		$lineChartDataEmployeeSalary = DashboardHelper::lineChartDataEmployeeSalary();

        $data = array("employee" => $donutChartDataEmployee,"total_salary" => $verticalBarChartDataEmployeeSalary ,"total_monthly_salary" => $lineChartDataEmployeeSalary);
       
        return view('Dashboard.hrDashboard',compact('data'));
    }

    public function userDashboard()
    {
        return view('Dashboard.userDashboard');
	}

    public function financeDashboard()
    {
        return view('Dashboard.financeDashboard');
    }

    public function tmsDashboard()
    {
        return view('Dashboard.tmsDashboard');
    }

    public function tmSummaryDashboard(){
        return view('Dashboard.tmSummaryDashboard');
    }

    public function clientDashboard(Request $request){
        $companyId = $request->get('m');
        $accType = Auth::user()->acc_type;
        $customerId = Auth::user()->customer_id;
        CommonHelper::companyDatabaseConnection($companyId);
			$projectList = DB::table('company_projects as cp')
				->where('cp.status', 1)
				->select('cp.*');
                if($accType != 'client'){
                    $projectList = $projectList->where('cp.customer_id',$customerId);
                }
				$projectList = $projectList->get();
		CommonHelper::reconnectMasterDatabase();
        return view('Dashboard.clientDashboard',compact('projectList'));
    }
}