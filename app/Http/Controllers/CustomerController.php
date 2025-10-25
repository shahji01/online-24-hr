<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Session;
use Redirect;
use Input;
use Hash;
use App\Models\Role;
use App\Models\MenuPrivileges;
use App\Models\Users;
use Yajra\DataTables\DataTables;
class CustomerController extends Controller
{
    public function __construct(Request $request)
    {
        $this->page = 'TaskManagement.customers.';
    }

    public function index(Request $request){

        if($request->ajax()){
            $company_id = $request->input('company_id');
            $filterCustomerType = $request->input('filterCustomerType');
            $filterStatus = $request->input('filterStatus');
            CommonHelper::companyDatabaseConnection($company_id);
            $customers = DB::table('customers as c')
                ->select('c.*')
                ->when($filterCustomerType != '', function ($q) use ($filterCustomerType){
                    return $q->where('c.customer_type','=',$filterCustomerType);
                })
                ->when($filterStatus != '', function ($q) use ($filterStatus){
                    return $q->where('c.status','=',$filterStatus);
                })
                ->orderBy('c.id', 'ASC')
                ->get();
            CommonHelper::reconnectMasterDatabase();
            return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('customer_type', function ($row) {
                if($row->customer_type == 2){
                    return 'International';
                }else {
                    return 'Local';
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
            CommonHelper::companyDatabaseConnection($company_id);
        //                 $data .= '<li><a href="' . route('customers.edit', $row->id) . '?m=' . $company_id . '">Edit</a></li>
        //   <li><a id="inactive-record" data-url="' . route('customers.destroy', $row->id) . '">Inactive</a></li>';
                    }else{
                        //$data .= '<li><a id="active-record" data-url="'.route('employees.active', $row->id).'">Active</a></li>';
                    }
                $data .= '</ul></div></td>';
                return $data;
            })
            ->rawColumns(['customer_type','status','action'])
            ->make(true);
        }
        return view($this->page.'index');
    }

    public function create()
    {
        $menu_privileges = Role::where([['status','=',1]])->orderBy('id')->get();
        return view($this->page.'create',compact('menu_privileges'));
    }

    public function store(Request $request)
    {
        $company_id = $request->input('company_id');
        // Create an associative array with data for insertion
        $data = array(
            'customer_name' => $request->input('customer_name'),
            'customer_type' => $request->input('customer_type'),
            'mobile_no' => $request->input('mobile_no'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
            'status' => 1, // Ensure this matches your database constraints
            'created_date' => date('Y-m-d'),
            'created_by' => Auth::user()->name
        );
        CommonHelper::companyDatabaseConnection($company_id);
        // Insert data into the 'company_projects' table
        $cust_id = DB::table('customers')->insertGetId($data);
        CommonHelper::reconnectMasterDatabase();

        $password = Input::get('password');
        $account_type = 'customer';
        $role_id = $request->input('role_id');

        $dataCredentials['customer_id'] = $cust_id;
        $dataCredentials['name'] = $request->input('customer_name');
        $dataCredentials['username'] = $request->input('email');
        $dataCredentials['email'] = $request->input('email');
        $dataCredentials['mobile_no'] = $request->input('mobile_no');
        $dataCredentials['password'] = Hash::make($password);
        $dataCredentials['acc_type'] = $account_type;
        $dataCredentials['updated_at'] = date('Y-m-d');
        $dataCredentials['created_at'] = date('Y-m-d');
        $dataCredentials['company_id'] = $company_id;
        $userLastId = Users::insertGetId($dataCredentials);
        CommonHelper::companyDatabaseConnection($company_id);
            CommonHelper::LogGenerator('users',$userLastId,'insert','row is inserted','tms',Auth::user()->id);
        CommonHelper::reconnectMasterDatabase();
        $roles = Role::where([['id','=',$role_id]]);

        if($roles->count() > 0):

            MenuPrivileges::where('customer_id', $cust_id)->delete();
            $MenuPrivileges                         = new MenuPrivileges();
            $MenuPrivileges->role_id                = $role_id;
            $MenuPrivileges->customer_id            = $cust_id;
            $MenuPrivileges->main_modules           = $roles->value('main_modules');
            $MenuPrivileges->submenu_id             = $roles->value('submenu_id');
            $MenuPrivileges->menu_titles            = $roles->value('menu_titles');
            $MenuPrivileges->crud_rights            = $roles->value('crud_rights');
            $MenuPrivileges->company_list           = $roles->value('company_list');
            $MenuPrivileges->status                 = 1;
            $MenuPrivileges->username               = Auth::user()->name;
            $MenuPrivileges->created_at             = date('Y-m-d');
            $MenuPrivileges->updated_at             = date('Y-m-d');
            $MenuPrivileges->save();
            CommonHelper::companyDatabaseConnection($company_id);
            CommonHelper::LogGenerator('Menu_Privileges',$MenuPrivileges->id,'insert','row is inserted','tms',Auth::user()->id);
        endif;
        
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('tms/customers?m='.$company_id);

    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        $menu_privileges = Role::where([['status','=',1]])->orderBy('id')->get();
        return view($this->page.'edit',compact('menu_privileges'));
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
