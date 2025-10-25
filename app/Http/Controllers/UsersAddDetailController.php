<?php

namespace App\Http\Controllers;
use App\Helpers\CommonHelper;
use App\Models\ApprovalSystem;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\MainMenuTitle;
use App\Models\MenuPrivileges;
use App\Models\Employee;
use App\Models\Role;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Redirect;

class UsersAddDetailController extends Controller
{
    
    public function __construct()
    {
    	$this->middleware('auth');
	}

   	public function addMainMenuTitleDetail()
    {
		$main_menu_id = Input::get('main_menu_name');
		$title = Input::get('title_name');
		$title_id = preg_replace('/\s+/', '', $title);
		
		$data1['main_menu_id'] = $main_menu_id;
		$data1['title'] = $title;
		$data1['title_id'] = $title_id;
        $data1['menu_type'] = Input::get('menu_type');
        $data1['date'] = date("Y-m-d");
        DB::table('main_menu_title')->insert($data1);
	}
	
	public function addSubMenuDetail(){
		
		$main_navigation_name = Input::get('main_navigation_name');
		$explodeMainNavigation = explode('_',$main_navigation_name);
		$subNavigationTitleName = Input::get('sub_navigation_title_name');
		$subNavigationUrl = Input::get('sub_navigation_url');
        $js = Input::get('js');
        $page_type = Input::get('page_type');
		$mainNavigationName = $explodeMainNavigation[0];
		$mainNavigationTitleId = $explodeMainNavigation[1];
		
		$max_id = DB::selectOne('SELECT max(`id`) as id  FROM `menu` WHERE `m_parent_code` = '.$mainNavigationName.'')->id;
		
		if($max_id == ''){
        	$code = $mainNavigationName.'-1';
		}else{
			$max_code2 = DB::selectOne('SELECT `m_code` FROM `menu` WHERE `m_parent_code` = '.$explodeMainNavigation[0].'')->m_code;
			$max_code2;
			$max_code2;
			$max = explode('-',$max_code2);
        	$code = $mainNavigationName.'-'.(end($max)+1);
		}
		$data1['m_code'] =	$code;
		$data1['m_parent_code'] = $explodeMainNavigation[0];
		$data1['m_type'] = '';
        $data1['m_main_title']= $explodeMainNavigation[1];
		$data1['name'] = $subNavigationTitleName;
        $data1['js'] = $js;
		$data1['m_controller_name'] = $subNavigationUrl;
        $data1['page_type'] = $page_type;
        $data1['date']     		  = date("Y-m-d");
        DB::table('menu')->insert($data1);
        
        // Cache::forget('MainMenuTitlesSub');

        // $MainMenuTitlesSub = Cache::rememberForever('MainMenuTitlesSub',function() use ($mainMenuId){
        //     return DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$mainMenuId],['status','=',1],['menu_type','=',2]])->get();
        // });
	}

    function addRoleDetail(){

        $main_modules ='';
        $menu ='';
        $sub_menu ='';
        $crud_rights = '';
        $locations = '';

        if(!empty(Input::get('locations'))):
            foreach (Input::get('locations') as $locationValues):
                $locations .= $locationValues.",";
            endforeach;
        endif;

        foreach (Input::get('main_modules') as $moduleId):

            $main_modules.=$moduleId.",";
            foreach (Input::get('menu_title_'.$moduleId) as $title):
                $menu.=$title.",";
                if(Input::get('sub_menu_'.$title)):
                    foreach (Input::get('sub_menu_'.$title) as $submenu):
                        $sub_menu.= $submenu.",";
                    endforeach;
                endif;

                if(Input::get('crud_rights_'.$title)):
                    foreach (Input::get('crud_rights_'.$title) as $crudValue):
                        $crud_rights.= $crudValue."_".$title.",";
                    endforeach;
                endif;
            endforeach;
        endforeach;

        Role::where('role_name', Input::get('role_name'))->delete();

        //CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        // $employee_project = Employee::where([['emp_id','=',Input::get('emp_id')],['status','=',1]]);
        // CommonHelper::reconnectMasterDatabase();

        $Roles = new Role();

        $Roles->role_name         = Input::get('role_name');
        $Roles->main_modules      = substr($main_modules,0,-1);
        $Roles->menu_titles       = substr($menu,0,-1);
        $Roles->submenu_id        = substr($sub_menu,0,-1);
        $Roles->crud_rights       = substr($crud_rights,0,-1);
        $Roles->location_permission = substr($locations,0,-1);
        $Roles->company_list      = Input::get('companyList');
        $Roles->status            = 1;
        $Roles->username          = Auth::user()->name;
        $Roles->created_at        = date('Y-m-d');
        $Roles->updated_at        = date('Y-m-d');
        $Roles->save();

        return Redirect::to('users/viewRoleList?m='.Input::get('company_id'));
    }
}
