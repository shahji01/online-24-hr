<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\MainMenuTitle;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\MenuPrivileges;
use App\Models\Employee;
use App\Models\Menu;
use App\Helpers\CommonHelper;
use App\Models\ApprovalSystem;
use App\Models\Regions;
use App\Models\EmployeeCategory;
use App\Models\EmployeeProjects;
use App\Models\Role;
use App\Models\MaritalStatus;
use App\Models\EmployeeDocuments;
use Input;
use Auth;
use DB;
use Config;
use \Cache;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('dUser.home');
    }
   
   	public function toDayActivity()
    {
		return view('Users.toDayActivity');
   	}
	
	public function createUsersForm()
    {
		return view('Users.createUsersForm');
	}
	
	public function createMainMenuTitleForm()
    {
		return view('Users.MainMenuTitles.createMainMenuTitleForm');
	}
	
	public function createSubMenuForm()
    {
		$MainMenuTitles = new MainMenuTitle;
		$MainMenuTitles = $MainMenuTitles::where('status', '=', '1')->get();
		return view('Users.SubMenu.createSubMenuForm',compact('MainMenuTitles'));
	}
    public function editSubMenuDetailForm(){

        $MainMenuTitles = new MainMenuTitle;
        $MainMenuTitles = $MainMenuTitles::where('status', '=', '1')->get();
        $subMenu=menu::where([['id','=', Input::get('id')]])->first();
        return view('Users.SubMenu.editSubMenuDetailForm',compact('subMenu','MainMenuTitles'));
    }

    public function createRoleForm()
    {
        $locations = Cache::get('locations');
        return view('Users.Roles.createRoleForm', compact('locations'));
    }

    public function viewRoleList()
    {
        $MenuPrivileges = Role::where([['status','=',1]])->orderBy('id')->get();
        return view('Users.Roles.viewRoleList',compact('MenuPrivileges'));
    }

    public function viewEmployeePrivileges($id)
    {
        $company_id = Input::get('m');
        $locations = Cache::get('locations');
        $roles = Role::where([['id','=',$id]])->get()->toArray();
        return view('Users.Roles.viewEmployeePrivileges',compact('roles','company_id','locations'));
    }

    public function editUserProfile()
    {
           $breadCrums = array('heading' => 'Settings / Change Password');
        return view('Users.editUserProfile',compact('breadCrums'));
    }
    
    public function editMyProfile()
    {
        // echo "<pre>";
        // print_r(Auth::user());
        // exit();
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $employee = Employee::select('*')->where('id', '=', Auth::user()->employee_id)->get();
        $employee_cnic_copy = EmployeeDocuments::where([['employee_id','=',Auth::user()->employee_id],['status','=',1]]);
        $employee_documents = EmployeeDocuments::where([['employee_id', '=', Auth::user()->employee_id], ['status','=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();


        return view('Users.editMyProfile',compact('employee','marital_status','employee_cnic_copy','employee_documents'));
    }

}
