<?php
namespace App\Helpers;
use App\Models\Regions;
use App\Helpers\CommonHelper;
use DB;
use Config;
use Auth;
use Illuminate\Support\Facades\App;
use Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\MenuPrivileges;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use App\Models\Log;
use \Cache;
use App\Models\Designation;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\EmployeeCategory;
use App\Models\MaritalStatus;
use App\Models\JobType;
use App\Models\LeaveType;
use App\Models\LoanType;
use App\Models\LeavesData;
use App\Models\LeavesPolicy;
use App\Models\Eobi;
use App\Models\Grades;
use App\Models\Locations;
use App\Models\Cities;
use App\Models\DegreeType;
use App\Models\EmployeeProjects;
use App\Models\AllowanceType;
use App\Models\Equipments;


class RefreshCacheHelper{


    public static function MenuCache()
    {
        Cache::forget('MainMenuTitles');
        $MainMenuTitles = Cache::rememberForever('MainMenuTitles',function(){
            return DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['menu_type','=',2],['status','=',1]])->groupBy('main_menu_id')->get();
        });

        foreach ($MainMenuTitles as $row) {
            $mainMenuId = $row->main_menu_id;

            Cache::forget('MainMenuTitlesSub_'.$mainMenuId);
            $MainMenuTitlesSub = Cache::rememberForever('MainMenuTitlesSub_'.$mainMenuId, function () use ($mainMenuId) {
                return DB::table('main_menu_title')->select(['main_menu_id', 'title', 'title_id', 'id'])->where([['main_menu_id', '=', $mainMenuId], ['status', '=', 1], ['menu_type', '=', 2]])->get();
            });

            foreach ($MainMenuTitlesSub as $row1) {
                Cache::forget('data_' . $row1->id);
                Cache::rememberForever('data_' . $row1->id, function () use ($row1) {
                    return DB::table('menu')->select(['m_type', 'name', 'm_controller_name', 'm_main_title', 'id', 'm_parent_code'])->where([['m_parent_code', '=', $row1->id], ['page_type', '=', 1], ['status', '=', 1]])->get();
                });
            }
        }

        Cache::forget('MainMenuTitlesMain');
        $MainMenuTitlesMain = Cache::rememberForever('MainMenuTitlesMain',function(){
            return DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['menu_type','=',1],['status','=',1]])->groupBy('main_menu_id')->get();
        });

        foreach ($MainMenuTitlesMain as $row) {
            $mainMenuId = $row->main_menu_id;

            Cache::forget('MainMenuTitlesSubMain_'.$mainMenuId);
            $MainMenuTitlesSubMain = Cache::rememberForever('MainMenuTitlesSubMain_'.$mainMenuId, function () use ($mainMenuId) {
                return DB::table('main_menu_title')->select(['main_menu_id', 'title', 'title_id', 'id'])->where([['main_menu_id', '=', $mainMenuId], ['status', '=', 1], ['menu_type', '=', 1]])->get();
            });

            foreach ($MainMenuTitlesSubMain as $row1) {
                Cache::forget('dataMain_' . $row1->id);
                Cache::rememberForever('dataMain_' . $row1->id, function () use ($row1) {
                    return DB::table('menu')->select(['m_type', 'name', 'm_controller_name', 'm_main_title', 'id', 'm_parent_code'])->where([['m_parent_code', '=', $row1->id], ['page_type', '=', 1], ['status', '=', 1]])->get();
                });
            }
        }
    }

    public static function EmployeeCache()
    {
       
        Cache::forget('employee');
        Cache::rememberForever('employee', function () {
         $data_array = [];
         
            $empQuery = "
            
                
            SELECT e.* ,
            eml.location_id ,
            IFNULL((SELECT emp_name FROM ".CONST_COMPANY_DB.".employee WHERE id = e.reporting_manager_id),'--') reporting_manager,
            d1.department_name,
            old_d.designation_name old_designation,
            IFNULL(old_g.employee_grade_type,'Grade Not Found') old_grade,
            IFNULL(ec.employee_category_name,'Category Not Found') old_category,
            IFNULL(ep.project_name,'Project Not Found') old_project,
            e.salary old_salary,
            IFNULL(new_d.designation_name,old_d.designation_name) new_designation,
            IFNULL(IFNULL(new_g.employee_grade_type,old_g.employee_grade_type),'Grade Not Found') new_grade,
            IFNULL((SELECT employee_promotion.salary FROM ".CONST_COMPANY_DB.".employee_promotion WHERE
                            employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.salary != '' AND employee_promotion.approval_status = 2
                            ORDER BY employee_promotion.id DESC LIMIT 1),e.salary) AS new_salary
            FROM ".CONST_COMPANY_DB.".employee e 
            INNER JOIN ".CONST_MASTER_DB.".department d1
                            ON d1.id = e.department_id
            INNER JOIN ".CONST_MASTER_DB.".designation old_d
                            ON old_d.id = e.designation_id 
            left JOIN ".CONST_MASTER_DB.".grades old_g
                            ON old_g.id = e.grade_id


            LEFT JOIN ".CONST_MASTER_DB.".designation new_d
            ON new_d.id = (SELECT employee_promotion.designation_id FROM ".CONST_COMPANY_DB.".employee_promotion WHERE
                                    employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.designation_id != '' AND employee_promotion.approval_status = 2
                                    ORDER BY employee_promotion.id DESC LIMIT 1)
            LEFT JOIN ".CONST_MASTER_DB.".grades new_g
                            ON new_g.id = (SELECT employee_promotion.grade_id FROM ".CONST_COMPANY_DB.".employee_promotion WHERE
                                    employee_promotion.employee_id = e.id AND employee_promotion.status = 1 AND employee_promotion.grade_id != '' AND employee_promotion.approval_status = 2
                                    ORDER BY employee_promotion.id DESC LIMIT 1)
            INNER JOIN ".CONST_COMPANY_DB.".employee_multiple_locations eml
                            ON eml.employee_id = e.id

            LEFT JOIN ".CONST_MASTER_DB.".employee_category ec
            ON   e.category_id = ec.id

            LEFT JOIN ".CONST_MASTER_DB.".employee_projects ep
            ON   eml.project_id = ep.id

            WHERE eml.status = 1 ORDER BY e.id ASC

             ";
            $empQueryData = DB::select(DB::raw($empQuery));

            foreach ($empQueryData as $key => $value) {
                $data_array[$value->id] = $value ;
            }
            return $data_array;
        });

        

    }

    public static function CustomerCache()
    {
       
        Cache::forget('customer');
        Cache::rememberForever('customer', function () {
         $data_array = [];
         
            $cusQuery = "
            
                
            SELECT c.* FROM ".CONST_COMPANY_DB.".customers as c";
            $cusQueryData = DB::select(DB::raw($cusQuery));

            foreach ($cusQueryData as $key => $value) {
                $data_array[$value->id] = $value ;
            }
            return $data_array;
        });

        

    }

    public static function DepartmentCache()
    {
        Cache::forget('department');
        Cache::rememberForever('department', function () {
            $values = Department::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function SubDepartmentCache()
    {
        Cache::forget('sub_department');
        Cache::rememberForever('sub_department', function () {
            $values = SubDepartment::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function DesignationCache()
    {
        Cache::forget('designation');
        Cache::rememberForever('designation', function () {
            $values = Designation::where([['status', '=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach ($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function CategoryCache()
    {
        Cache::forget('employee_category');
        Cache::rememberForever('employee_category', function () {
            $values = EmployeeCategory::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }


    public static function JobTypeCache()
    {
        Cache::forget('job_type');
        Cache::rememberForever('job_type', function () {
            $values = JobType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function LeaveTypeCache()
    {
        Cache::forget('leave_type');
        Cache::rememberForever('leave_type', function () {
            $values = LeaveType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function LoanTypeCache()
    {
        Cache::forget('loan_type');
        Cache::rememberForever('loan_type', function () {
            $values = LoanType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function MaritalCache()
    {
        Cache::forget('marital_status');
        Cache::rememberForever('marital_status', function () {
            $values = MaritalStatus::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function LeavesPolicyCache()
    {
        Cache::forget('leaves_policy');
        Cache::rememberForever('leaves_policy', function () {
            $values = LeavesPolicy::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Cache::forget('leaves_data');
        Cache::rememberForever('leaves_data', function () {
            $values = LeavesData::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function EobiCache()
    {
        Cache::forget('eobi');
        Cache::rememberForever('eobi', function () {
            $values = Eobi::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function GradesCache()
    {
        Cache::forget('grades');
        Cache::rememberForever('grades', function () {
            $values = Grades::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function LocationsCache()
    {
        Cache::forget('locations');
        Cache::rememberForever('locations', function () {
            $values = Locations::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function DegreeTypeCache()
    {
        Cache::forget('degree_type');
        Cache::rememberForever('degree_type', function () {
            $values = DegreeType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function EquipmentsCache()
    {
        Cache::forget('employee_equipments');
        Cache::rememberForever('employee_equipments', function () {
            $values = Equipments::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function ProjectsCache()
    {
        Cache::forget('employee_projects');
        Cache::rememberForever('employee_projects', function () {
            $values = EmployeeProjects::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function CitiesCache()
    {
        Cache::forget('cities');
        Cache::rememberForever('cities', function () {
            $values = Cities::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }

    public static function AllowanceTypeCache()
    {
        Cache::forget('allowance_types');
        Cache::rememberForever('allowance_types', function () {
            $values = AllowanceType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

    }


    public static function RefreshAll($company)
    {
        self::MenuCache();
        CommonHelper::companyDatabaseConnection($company);
        self::EmployeeCache();
        self::CustomerCache();
        CommonHelper::reconnectMasterDatabase();
        self::DepartmentCache();
        self::SubDepartmentCache();
        self::DesignationCache();
        self::CategoryCache();
        self::JobTypeCache();
        self::LeaveTypeCache();
        self::LoanTypeCache();
        self::MaritalCache();
        self::LeavesPolicyCache();
        self::EobiCache();
        self::GradesCache();
        self::LocationsCache();
        self::DegreeTypeCache();
        self::EquipmentsCache();
        self::ProjectsCache();
        self::CitiesCache();
        self::AllowanceTypeCache();
    }
  

}
?>    