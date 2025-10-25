<?php

namespace App\Http\Controllers;
use App\Helpers\CommonHelper;
use App\Models\Menu;
use App\User;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\MainMenuTitle;
use App\Models\EmployeeDocuments;
use App\Models\MenuPrivileges;
use App\Models\ApprovalSystem;
use App\Models\Role;
use Input;
use Auth;
use DB;
use Config;
use Psy\Util\Json;
use Redirect;
use Hash;
use Session;
use Validator;
class UsersEditDetailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function editUserPasswordDetail(Request $request)
    {


           $validator =  $this->validate($request, array(  // Removed `[]` from the array.
                'password' =>[
                'required', 
                'min:6',
                'string',
                'confirmed',
                'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                function ($attribute, $value, $fail) use($request) {

                    if (!Hash::check($request->old_password, Auth::user()->password)) {
                         $fail('Old password does not match.');
                    }
                    
                }
            ],
             'old_password' => 'required',
             'password_confirmation' => 'required',
               
            ));
           if (!$validator) {
                    return redirect('users/editUserProfile?m='.Input::get('m').'#Innovative')
                                ->withErrors($validator)
                                ->withInput();
                }
                else{

                    $data['password'] = Hash::make(Input::get('password'));
                    $data['created_at'] = date('Y-m-d');
                    $data['updated_at'] = date('Y-m-d');
                    $data['password_status'] = 1;
                    DB::table('users')->where('id', Auth::user()->id)->update($data); 
                    Auth::logout();
                    Session::flash('msg', 'Your Password has been successfully changed , Login with new Password.');
                    return Redirect::to('/');
      
                  
                }
    }

    function editUserProfileDetail(Request $request){
        $emp_id=Input::get('emp_id');
        $father_name = Input::get('father_name_1');
        $date_of_birth = Input::get('dob');
        $gender = Input::get('gender_1');
        $cnic = Input::get('cnic_1');
        $cnic_expiry_date = Input::get('cnic_expiry_date_1');
        $contact_no = Input::get('contact_no_1');
        $emergency_contact_person = Input::get('emergency_contact_person');
        $contact_home = Input::get('contact_home_1');
        $marital_status = Input::get('marital_status_1');
        $place_of_birth = Input::get('place_of_birth_1');
        $relegion = Input::get('religion_1');
        $nationality = Input::get('nationality_1');
        $residential_address = Input::get('residential_address_1');
        $permanent_address = Input::get('permanent_address_1');


        if ($request->file('cnic_path_1')):
            $file_name1 = Input::get('name') . '_' . time() . '.' . $request->file('cnic_path_1')->getClientOriginalExtension();
            $path1 = 'app/' . $request->file('cnic_path_1')->storeAs('uploads/employee_cnic_copy', $file_name1);
            $data['cnic_path'] = $path1;
            $data['cnic_name'] = $file_name1;
            $data['cnic_type'] = $request->file('cnic_path_1')->getClientOriginalExtension();
        endif;

        $data['emp_name'] = Input::get('name');
        $data['emp_father_name'] = $father_name;
        $data['emp_cnic'] = $cnic;
        $data['emp_contact_no'] = $contact_no;
        $data['contact_home'] = $contact_home;
        $data['emergency_contact_person'] = $emergency_contact_person;
        
        if(Input::get('cnic_expiry_date_1') != ''){
            $data['emp_cnic_expiry_date'] = $cnic_expiry_date;
        }

        $data['emp_date_of_birth'] = $date_of_birth;
        $data['emp_gender'] = $gender;
        $data['emp_marital_status'] = $marital_status;
        if(Input::get('place_of_birth_1') != ''){
            $data['emp_place_of_birth'] = $place_of_birth;
        }
        if(Input::get('nationality_1')!= ''){
            $data['nationality'] = $nationality;
        }
        if(Input::get('religion_1')!= ''){
            $data['relegion'] = $relegion;
        }
        if ($request->file('fileToUpload_1')):
            $file_name = Input::get('name') . '_' . time() . '.' . $request->file('fileToUpload_1')->getClientOriginalExtension();
            $path = $request->file('fileToUpload_1')->storeAs('uploads/employee_images', $file_name);
            $data['img_path'] = 'app/' . $path;
        endif;
        if(Input::get('permanent_address_1')!=''){
            $data['permanent_address']=$residential_address;$permanent_address;
        }
        $data['residential_address']=$residential_address;
        //file upload start
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $employee_documents_count = EmployeeDocuments::where([['status', '=', 1], ['emp_id', '=', $emp_id]])->max('counter');

        $counter = $employee_documents_count;
        if ($request->file('media')) {
            foreach ($request->file('media') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_documents', $file_name);

                    $fileUploadData['emp_id'] = $emp_id;
                    $fileUploadData['documents_upload_check'] = 1;
                    $fileUploadData['file_name'] = $file_name;
                    $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('employee_documents')->insert($fileUploadData);
                }
            }
        }
//        file upload end




        DB::table('employee')->where('emp_id', $emp_id)->update($data);
        CommonHelper::reconnectMasterDatabase();

//        return Redirect::to('/');
        return Json::encode('success');
    }
    
    function editUserRoleDetail( Request $request)
    {
        $main_modules = '';
        $menu = '';
        $sub_menu = '';
        $crud_rights = '';
        $locations = '';

        if(!empty(Input::get('locations'))):
            foreach (Input::get('locations') as $locationValues):
                $locations .= $locationValues.",";
            endforeach;
        endif;

        foreach (Input::get('main_modules') as $moduleId):
            $main_modules .= $moduleId . ",";

            foreach (Input::get('menu_title_' . $moduleId) as $title):
                $menu .= $title . ",";
                if (Input::get('sub_menu_' . $title)):
                    foreach (Input::get('sub_menu_' . $title) as $submenu):
                        $sub_menu .= $submenu . ",";
                    endforeach;
                endif;

                if (Input::get('crud_rights_' . $title)):
                    foreach (Input::get('crud_rights_' . $title) as $crudValue):
                        $crud_rights .= $crudValue . "_" . $title . ",";
                    endforeach;
                endif;
            endforeach;
        endforeach;

        $role_id = Input::get('id');
        MenuPrivileges::where([['role_id','=', $role_id]])->update([
            'main_modules' => substr($main_modules,0,-1),
            'menu_titles' => substr($menu,0,-1),
            'submenu_id' => substr($sub_menu,0,-1),
            'crud_rights' => substr($crud_rights,0,-1),
            'location_permission' => substr($locations,0,-1),
            'company_list' => Input::get('companyList'),
            'status' => 1,
            'username' => Auth::user()->name,
            'updated_at' => date('Y-m-d')
        ]);

        Role::where([['id','=', $role_id]])->update([
            'role_name' => Input::get('role_name'),
            'main_modules' => substr($main_modules,0,-1),
            'menu_titles' => substr($menu,0,-1),
            'submenu_id' => substr($sub_menu,0,-1),
            'crud_rights' => substr($crud_rights,0,-1),
            'location_permission' => substr($locations,0,-1),
            'company_list' => Input::get('companyList'),
            'status' => 1,
            'username' => Auth::user()->name,
            'updated_at' => date('Y-m-d')
        ]);

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('users/viewRoleList?m=' . Input::get('company_id'));
    }
    
    public function editApprovalCodeDetail()
    {
        $data1['approval_code'] = Hash::make(Input::get('approval_code'));
        $data1['approval_check']= 1;
        $data1['status']     	= 1;
        $data1['username']     	= Auth::user()->name;
        $data1['date']     		= date("Y-m-d");
        $data1['time']     		= date("H:i:s");
       // DB::table('approval_system')->where([['emr_no','=',Input::get('emr_no')]])->update($data1);
        ApprovalSystem::updateOrCreate(['emp_id' => Input::get('emp_id')],$data1);
        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('users/editUserProfile');
    }

    public function editSubMenuDetail(){

        $subMenu= Menu::Find(Input::get('subid'));
        $main_navigation_name = Input::get('main_navigation_name');
        $explodeMainNavigation = explode('_',$main_navigation_name);
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
        $subMenu->m_code=$code;
        $subMenu->m_parent_code = $explodeMainNavigation[0];


        $subMenu->m_main_title=$mainNavigationTitleId;
        $subMenu->name=Input::get('sub_navigation_title_name');
        $subMenu-> js=Input::get('js');
        $subMenu-> m_controller_name=Input::get('sub_navigation_url');
        $subMenu->update();
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');

        return Redirect::to('users/createSubMenuForm?m='.Input::get('companyId').'#Innovative');
    }


}
