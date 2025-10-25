<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Session;
use Redirect;
use Input;
use Yajra\DataTables\DataTables;
class CompanyProjectController extends Controller
{
    public function __construct(Request $request)
    {
        $this->page = 'TaskManagement.company-projects.';
    }

    public function index(Request $request){
        if($request->ajax()){
            
            $company_id = $request->input('company_id');
            $filterProjectType = $request->input('filterProjectType');
            $filterProjectStatus = $request->input('filterProjectStatus');
            $filterStatus = $request->input('filterStatus');
            $customerId = $request->input('customer_id');
            CommonHelper::companyDatabaseConnection($company_id);
            $companyProjects = DB::table('company_projects as cp')
                ->select('cp.*','c.customer_name')
                ->join('customers AS c', 'cp.customer_id','=', 'c.id')
                ->when($filterProjectType != '', function ($q) use ($filterProjectType){
                    return $q->where('cp.project_type','=',$filterProjectType);
                })
                ->when($filterProjectStatus != '', function ($q) use ($filterProjectStatus){
                    return $q->where('cp.project_status','=',$filterProjectStatus);
                })
                ->when($filterStatus != '', function ($q) use ($filterStatus){
                    return $q->where('cp.status','=',$filterStatus);
                })
                ->when($customerId != '', function ($q) use ($customerId){
                    return $q->where('cp.customer_id','=',$customerId);
                })
                ->orderBy('cp.id', 'ASC')
                ->get();
            CommonHelper::reconnectMasterDatabase();
            return DataTables::of($companyProjects)
            ->addIndexColumn()
            ->addColumn('project_type', function ($row) {
                if($row->project_type == 2){
                    return 'International';
                }else {
                    return 'Local';
                }
            })
            ->addColumn('project_status', function ($row) {
                if($row->project_status == 2){
                    return 'Completed';
                }else if($row->project_status == 3){
                    return 'Hold';
                }else {
                    return 'Pending';
                }
            })
            ->addColumn('status', function ($row) {
                if($row->status == 2){
                    return 'Inactive';
                }else {
                    return 'Active';
                }
            })
            ->addColumn('action',function ($row){
                $data = '<td class="text-center hidden-print" id="hide-table-row">
                <div class="dropdown">
                <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                   &nbsp;<i data-feather="chevron-down" class="fa fa-angle-down">&nbsp;</i>
               </button><ul class="dropdown-menu" role="menu" aria-labelledby="menu1">';
                    if($row->status == 1){
                        $data .= '<li><a onclick="showDetailModelOneParamerter(\'tms/company-projects/assignProjectToEmployeeForm\', ' . $row->id . ', \'Assign Project To Employees\')"><span class="glyphicon glyphicon-eye-open"></span> Assign Project</a></li>';
                        // $data .= '<li><a href="'.route('employees.edit', $row->id).'">Edit</a></li>
                        // <li><a id="inactive-record" data-url="'.route('employees.destroy', $row->id).'">Inactive</a></li>';
                    }else{
                        //$data .= '<li><a id="active-record" data-url="'.route('employees.active', $row->id).'">Active</a></li>';
                    }
                $data .= '</ul></div></td>';
                return $data;
            })
            ->rawColumns(['project_status','project_type','status','action'])
            ->make(true);
        }
        return view($this->page.'index');
    }

    public function create(Request $request)
    {
        $m = $request->get('m');
        CommonHelper::companyDatabaseConnection($m);
            $customerList = DB::table('customers')->where('status',1)->get();
        CommonHelper::reconnectMasterDatabase();
        return view($this->page.'create',compact('customerList'));
    }

    public function store(Request $request)
    {
        
        // Create an associative array with data for insertion
        $data = array(
            'company_id' => $request->input('company_id'), // Correct the key name here
            'customer_id' => $request->input('customer_id'),
            'project_name' => $request->input('project_name'),
            'project_type' => $request->input('project_type'),
            'project_owner_name' => $request->input('project_owner_name'),
            'description' => $request->input('description'),
            'total_cost' => $request->input('total_cost'),
            'total_working_days' => $request->input('total_working_days'),
            'status' => 1, // Ensure this matches your database constraints
            'project_status' => 1, // Ensure this matches your database constraints
            'created_date' => date('Y-m-d'),
            'created_by' => Auth::user()->name
        );
        CommonHelper::companyDatabaseConnection($request->input('company_id'));
        // Insert data into the 'company_projects' table
        DB::table('company_projects')->insert($data);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('tms/company-projects?m='.$request->input('company_id'));

    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        return view($this->page.'edit');
    }

    public function update(Request $request)
     {
         
 
     }

    public function status(Request $request)
    {
        
    }

    public function assignProjectToEmployeeForm(Request $request){
        $id = $request->input('id');
        $m = $request->input('m');
        CommonHelper::companyDatabaseConnection($m);
            $getProjectDetail = DB::table('company_projects')->where('id',$id)->first();
            $getPriviousAssignEmployees = DB::table('assign_employee_project as aep')
                ->join('employee as e','aep.employee_id','=','e.id')
                ->select('e.id','e.emp_name')
                ->where('aep.project_id',$id)
                ->get();
            $employees = DB::table('employee')->where('status',1)->get();
        CommonHelper::reconnectMasterDatabase();
        return view($this->page.'assignProjectToEmployeeForm',compact('getProjectDetail','getPriviousAssignEmployees','employees'));
    }

    public function updateAssignEmployeesDetail(Request $request){
        $m = $request->input('m');
        $assignEmployees = $request->input('assignEmployees');
        $projectId = $request->input('projectId');
        foreach($assignEmployees as $aeRow){
            $data[] = [
                'company_id' => $m,
                'project_id' => $projectId,
                'employee_id' => $aeRow,
                'status' => 1,
                'created_by' => Auth::user()->name,
                'created_date' => date('Y-m-d')
            ];
        }
        if (!empty($data)) {
            CommonHelper::companyDatabaseConnection($m);
                DB::table('assign_employee_project')->where('project_id',$projectId)->delete();
                DB::table('assign_employee_project')->insert($data);
            CommonHelper::reconnectMasterDatabase();
        }
    }
}
