<?php

namespace App\Http\Controllers\Auth;
use App\Models\MenuPrivileges;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use Auth;
use Illuminate\Support\Facades\Hash;
use Mockery\CountValidator\Exception;
use Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\RefreshCacheHelper;
use DB;

class LoginController extends Controller
{
    //use AuthenticatesUsers, ThrottlesLogins;
    use AuthenticatesUsers;
    protected $maxAttempts = 4; // Number of login attempts
    protected $decayMinutes = 1; // Time in minutes to throttle for

    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        // return $this->limiter()->tooManyAttempts(
        //     $this->throttleKey($request),
        //     $this->maxAttempts(),
        //     $this->decayMinutes()
        // );
    }

    protected function incrementLoginAttempts(Request $request)
    {
        // $this->limiter()->hit(
        //     $this->throttleKey($request),
        //     $this->decayMinutes()
        // );
    }

    protected function clearLoginAttempts(Request $request)
    {
        //$this->limiter()->clear($this->throttleKey($request));
    }

    protected function sendLockoutResponse(Request $request)
    {
        // $seconds = $this->limiter()->availableIn(
        //     $this->throttleKey($request)
        // );

        // throw ValidationException::withMessages([
        //     $this->username() => [trans('auth.throttle', ['seconds' => $seconds])],
        // ])->status(429);
    }

    protected function authenticated($request, $user)
    {
        session ( [
            'accountYear' => $request->get ( 'account_year' )
        ] );
        $company_id = Auth::user()->company_id;
        $employee_id = Auth::user()->employee_id;
        $customer_id = Auth::user()->customer_id;
        RefreshCacheHelper::MenuCache();
        RefreshCacheHelper::EmployeeCache();
        RefreshCacheHelper::CustomerCache();
        RefreshCacheHelper::DepartmentCache();
        RefreshCacheHelper::SubDepartmentCache();
        RefreshCacheHelper::DesignationCache();
        RefreshCacheHelper::CategoryCache();
        RefreshCacheHelper::JobTypeCache();
        RefreshCacheHelper::LeaveTypeCache();
        RefreshCacheHelper::LoanTypeCache();
        RefreshCacheHelper::MaritalCache();
        RefreshCacheHelper::LeavesPolicyCache();
        RefreshCacheHelper::EobiCache();
        RefreshCacheHelper::GradesCache();
        RefreshCacheHelper::LocationsCache();
        RefreshCacheHelper::DegreeTypeCache();
        RefreshCacheHelper::EquipmentsCache();
        RefreshCacheHelper::ProjectsCache();
        RefreshCacheHelper::CitiesCache();
        RefreshCacheHelper::AllowanceTypeCache();
        if($user->acc_type === 'client') {
            return redirect()->intended('/dc/hrDashboard?m=12');
        }else if($user->acc_type === 'customer') {
            $emp_status = HrHelper::getCompanyTableValueByIdAndColumn($company_id,'customers','status',$customer_id,'id');
            if($emp_status == 1){
                if($user->password_status == '0'):
                    return redirect()->intended('/users/editUserProfile?m='.$company_id);
                endif;
                $user_rights = MenuPrivileges::where([['customer_id','=',$customer_id]]);
                
                $crud_permission = '';
                if($user_rights->count() > 0):
                    $main_modules = explode(",",$user_rights->value('main_modules'));
                    $submenu_ids  = explode(",",$user_rights->value('submenu_id'));
                    $crud_rights  = explode(",",$user_rights->value('crud_rights'));
                    $companyList= $user_rights->value('company_list');
                endif;
                if(empty($crud_rights)):
                    return "You have Insufficient Privileges, Please Contact Administrator.";
                endif;
                if(in_array('User_Dashboard',$crud_rights) !== false):
                    return redirect()->intended('/dc/userDashboard?m='.$company_id);
                elseif(in_array('Client_Dashboard',$crud_rights) !== false):
                    return redirect()->intended('/dc/clientDashboard?m='.$company_id);
                elseif(in_array('HR_Dashboard',$crud_rights) !== false):
                    return redirect()->intended('/dc/hrDashboard?m='.$company_id);
                endif;
            }
            else{
                return redirect()->intended('/logout');
            }
        }else if($user->acc_type === 'user' || $user->acc_type === 'admin') {
            
            CommonHelper::companyDatabaseConnection($company_id);
                $ip = CommonHelper::getIPAddress();
                $getEmployeeDetail = DB::table('employee')->where('id',$employee_id)->first();
                if($getEmployeeDetail->ip_district == 2){
                    if($getEmployeeDetail->ip_number != $ip){
                        return redirect()->intended('/logout');
                    }
                }
                CommonHelper::addAttendanceAfterLogin($employee_id,date('Y-m-d'),date("H:i:s"));
            CommonHelper::reconnectMasterDatabase();
            $emp_status = HrHelper::getCompanyTableValueByIdAndColumn($company_id,'employee','status',$employee_id,'id');
            if($emp_status == 1){
                if($user->password_status == '0'):
                    return redirect()->intended('/users/editUserProfile?m='.$company_id);
                endif;
                $user_rights = MenuPrivileges::where([['employee_id','=',$employee_id]]);

                $crud_permission = '';
                if($user_rights->count() > 0):
                    $main_modules = explode(",",$user_rights->value('main_modules'));
                    $submenu_ids  = explode(",",$user_rights->value('submenu_id'));
                    $crud_rights  = explode(",",$user_rights->value('crud_rights'));
                    $companyList= $user_rights->value('company_list');
                endif;
                
                if(empty($crud_rights)):
                    return "You have Insufficient Privileges, Please Contact Administrator.";
                endif;
                if(in_array('User_Dashboard',$crud_rights) !== false):
                    return redirect()->intended('/dc/userDashboard?m='.$company_id);
                elseif(in_array('HR_Dashboard',$crud_rights) !== false):
                    return redirect()->intended('/dc/hrDashboard?m='.$company_id);
                endif;
            }
            else{
                return redirect()->intended('/logout');
            }
        }
    }
}
