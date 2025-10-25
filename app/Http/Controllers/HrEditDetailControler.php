<?php

namespace App\Http\Controllers;
use App\Helpers\AttendanceHelper;
use App\Models\Employee;
use App\Models\EmployeeGsspDocuments;
use App\Models\EmployeeTransfer;
use App\Models\Allowance;
use App\Models\Eobi;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\LeavesData;
use App\Models\LeavesPolicy;
use App\Models\EmployeeDeposit;
use App\Models\EmployeeProjects;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeFuelData;
use App\Models\EmployeeLeavingReason;
use App\Models\Role;
use App\Models\MenuPrivileges;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\Designation;
use App\Models\JobType;
use App\Models\LeaveType;
use App\Models\LoanType;
use App\Models\Cities;
use App\Models\Locations;
use App\Models\EmployeeCategory;
use App\Models\MaritalStatus;
use App\Models\Grades;
use App\Models\DegreeType;
use App\Models\Equipments;
use App\Models\Log;
use App\Models\EmployeeFamilyData;
use App\Models\EmployeeEducationalData;
use App\Models\EmployeeLanguageProficiency;
use App\Models\EmployeeHealthData;
use App\Models\EmployeeActivityData;
use App\Models\EmployeeWorkExperience;
use App\Models\EmployeeReferenceData;
use App\Models\EmployeeKinsData;
use App\Models\EmployeeRelativesData;
use App\Models\EmployeeOtherDetails;
use App\Models\EmployeeMultipleLocations;
use App\Models\EmployeeBankData;
use App\Models\EmployeeEquipments;
use App\Models\AllowanceType;
use App\Models\Users;
use App\Models\Deduction;


use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use Hash;
use Helpers;
use \Cache;
use App\Helpers\RefreshCacheHelper;
use App\Helpers\HrHelper;


class HrEditDetailControler extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editDepartmentDetail()
    {
        $data['department_name'] = Input::get('department_name');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        Department::where('id', Input::get('id'))->update($data);

        Cache::forget('department');
        Cache::rememberForever('department', function () {
            $values = Department::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewDepartmentList?m='.Input::get('company_id'));
    }

    public function editSubDepartmentDetail()
    {
        $data['department_id'] = Input::get('department_id');
        $data['sub_department_name'] = Input::get('sub_department_name');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        SubDepartment::where('id', Input::get('id'))->update($data);

        Cache::forget('sub_department');
        Cache::rememberForever('sub_department', function () {
            $values = SubDepartment::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewSubDepartmentList?m='.Input::get('company_id'));
    }

    public function editDesignationDetail()
    {
        $data['designation_name'] = Input::get('designation_name');
        $data['grade_id'] = Input::get('grade_id');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        Designation::where('id', Input::get('id'))->update($data);

        Cache::forget('designation');
        Cache::rememberForever('designation', function () {
            $values = Designation::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewDesignationList?m='.Input::get('company_id'));
    }

    public function editHealthInsuranceDetail()
    {
        $healthInsuranceSection = Input::get('healthInsuranceSection');
        foreach ($healthInsuranceSection as $row) {
            $health_insurance_name = Input::get('health_insurance_name_' . $row . '');
            $health_insurance_id = Input::get('health_insurance_id_' . $row . '');
            $data1['health_insurance_name'] = strip_tags($health_insurance_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('health_insurance')->where('id', $health_insurance_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewHealthInsuranceList?m=' . $_GET['m'] . '#Online24');
    }

    public function editLifeInsuranceDetail()
    {
        $lifeInsuranceSection = Input::get('lifeInsuranceSection');
        foreach ($lifeInsuranceSection as $row) {
            $life_insurance_name = Input::get('life_insurance_name_' . $row . '');
            $life_insurance_id = Input::get('life_insurance_id_' . $row . '');
            $data1['life_insurance_name'] = strip_tags($life_insurance_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('life_insurance')->where('id', $life_insurance_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewLifeInsuranceList?m=' . $_GET['m'] . '#Online24');
    }

    public function editJobTypeDetail()
    {
        $data['job_type_name'] = Input::get('job_type_name');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        JobType::where('id', Input::get('id'))->update($data);

        Cache::forget('job_type');
        Cache::rememberForever('job_type', function () {
            $values = JobType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewJobTypeList?m='.Input::get('company_id'));
    }

    public function editProjectsDetail()
    {
        $data['project_name']    = Input::get('project_name');
        $data['project_code']    = Input::get('project_code');
        $data['username']        = Auth::user()->name;
        $data['date']            = date("Y-m-d");
        $data['time']            = date("H:i:s");
        EmployeeProjects::where('id', Input::get('id'))->update($data);

        Cache::forget('employee_projects');
        Cache::rememberForever('employee_projects', function () {
            $values = EmployeeProjects::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewProjectsList?m='.Input::get('company_id'));
    }

    public function editDesignationWiseLocationDetail()
    {
        $data['location_id'] = Input::get('location_name');
        $data['designation_id'] = Input::get('designation_name');
        $data['fuel_amount'] = Input::get('fuel_amount');
        $data['username'] = Auth::user()->name;
        $data['status'] = 1;
        $data['date'] = date("Y-m-d");
        
        DB::table('designation_wise_locations')->where('id', Input::get('id'))->update($data);

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewDesignationWiseLocation?m='.Input::get('company_id'));
    }



    public function editLocationsDetail()
    {
        $data['location_name'] = Input::get('location_name');
        $data['location_code'] = Input::get('location_code');
        $data['city_id'] = Input::get('city_id');
        $data['fuel_amount'] = Input::get('fuel_amount');
        $data['latitude'] = Input::get('latitude');
        $data['longitude'] = Input::get('longitude');
        $data['address'] = Input::get('address');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        Locations::where('id', Input::get('id'))->update($data);

        Cache::forget('locations');
        Cache::rememberForever('locations', function () {
            $values = Locations::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewLocationsList?m='.Input::get('company_id'));
    }

    public function editCitiesDetail()
    {
        $data['city'] = Input::get('city');
        $data['username'] = Auth::user()->name;
        $data['status'] = 1;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        Cities::where([['id', '=', Input::get('id')]])->update($data);

        Cache::forget('cities');
        Cache::rememberForever('cities', function () {
            $values = Cities::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewCityList?m='.Input::get('company_id'));
    }

    public function editCategoryDetail()
    {
        $data['employee_category_name'] = Input::get('employee_category_name');
        $data['company_id'] = Input::get('company_id');
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        EmployeeCategory::where('id', Input::get('id'))->update($data);

        Cache::forget('employee_category');
        Cache::rememberForever('employee_category', function () {
            $values = EmployeeCategory::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewCategoryList?m='.Input::get('company_id'));
    }

    public function editShiftTypeDetail()
    {
        $shiftTypeSection = Input::get('shiftTypeSection');
        foreach ($shiftTypeSection as $row) {
            $shift_type_name = Input::get('shift_type_name_' . $row . '');
            $shift_type_id = Input::get('shift_type_id_' . $row . '');
            $data1['shift_type_name'] = strip_tags($shift_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('shift_type')->where('id', $shift_type_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewShiftTypeList?m=' . $_GET['m'] . '#Online24');
    }

    public function editAdvanceTypeDetail()
    {
        $advanceTypeSection = Input::get('advanceTypeSection');
        foreach ($advanceTypeSection as $row) {
            $advance_type_name = Input::get('advance_type_name_' . $row . '');
            $advance_type_id = Input::get('advance_type_id_' . $row . '');
            $data1['advance_type_name'] = strip_tags($advance_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('advance_type')->where('id', $advance_type_id)->update($data1);
        }
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewAdvanceTypeList?m=' . $_GET['m'] . '#Online24');
    }

    public function editAllowanceTypeDetail()
    {
        $data['allowance_type'] = Input::get('allowance_type');
        $data['name'] = strtolower(preg_replace('/\s+/', '', "$".Input::get('allowance_type')));
        $data['taxable'] = Input::get('taxable');
        $data['company_id'] = Input::get('company_id');
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        AllowanceType::where('id', Input::get('id'))->update($data);

        Cache::forget('allowance_types');
        Cache::rememberForever('allowance_types', function () {
            $values = AllowanceType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewAllowanceTypeList?m='.Input::get('company_id'));
    }

    public function editLoanTypeDetail()
    {
        $data['loan_type_name'] = Input::get('loan_type_name');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        LoanType::where('id', Input::get('id'))->update($data);

        Cache::forget('loan_type');
        Cache::rememberForever('loan_type', function () {
            $values = LoanType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewLoanTypeList?m='.Input::get('company_id'));
    }

    public function editLeaveTypeDetail()
    {
        $data['leave_type_name'] = Input::get('leave_type_name');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        LeaveType::where('id', Input::get('id'))->update($data);

        Cache::forget('leave_type');
        Cache::rememberForever('leave_type', function () {
            $values = LeaveType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewLeaveTypeList?m='.Input::get('company_id'));
    }

    public function editMaritalStatusDetail()
    {
        $data['marital_status_name'] = Input::get('marital_status_name');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        MaritalStatus::where('id', Input::get('id'))->update($data);

        Cache::forget('marital_status');
        Cache::rememberForever('marital_status', function () {
            $values = MaritalStatus::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewMaritalStatusList?m='.Input::get('company_id'));
    }

    public function editEOBIDetail()
    {
        $data['EOBI_name'] = Input::get('EOBI_name');
        $data['EOBI_amount'] = Input::get('EOBI_amount');
        $data['month_year'] = Input::get('month_year');
        $data['username'] = Auth::user()->name;;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        Eobi::where([['id', '=', Input::get('id')]])->update($data);

        Cache::forget('eobi');
        Cache::rememberForever('eobi', function () {
            $values = Eobi::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewEOBIList?m='.Input::get('company_id'));
    }

    public function editEmployeeGradesDetail()
    {
        $data['employee_grade_type'] = Input::get('employee_grade_type');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        Grades::where('id', Input::get('id'))->update($data);

        Cache::forget('grades');
        Cache::rememberForever('grades', function () {
            $values = Grades::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewEmployeeGradesList?m='.Input::get('company_id'));
    }

    public function editEmployeeDegreeTypeDetail()
    {
        $data['degree_type_name'] = Input::get('degree_type_name');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        DegreeType::where('id', Input::get('id'))->update($data);

        Cache::forget('degree_type');
        Cache::rememberForever('degree_type', function () {
            $values = DegreeType::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewEmployeeDegreeTypeList?m='.Input::get('company_id'));
    }

    public function editEquipmentsDetail()
    {
        $data['equipment_name'] = Input::get('equipment_name');
        $data['username'] = Auth::user()->name;
        $data['company_id'] = Input::get('company_id');
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        Equipments::where('id', Input::get('id'))->update($data);

        Cache::forget('employee_equipments');
        Cache::rememberForever('employee_equipments', function () {
            $values = Equipments::where([['status','=', 1]])->orderBy('id')->get();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewEquipmentsList?m='.Input::get('company_id'));
    }

    public function editHiringRequestDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $jobTitle = Input::get('job_title');
        $jobTypeId = Input::get('job_type_id');
        $subDepartmentId = Input::get('sub_department_id');
        $designationId = Input::get('designation_id');
        $qualificationId = Input::get('qualification_id');
        $shiftTypeId = Input::get('shift_type_id');
        $gender = Input::get('gender');
        $salaryStart = Input::get('salary_start');
        $salaryEnd = Input::get('salary_end');
        $age = Input::get('age');
        $jobDescription = Input::get('job_description');
        $location = Input::get('location');
        $experience = Input::get('experience');
        $career_level = Input::get('career_level');
        $apply_before_date = Input::get('apply_before_date');


        $data1['RequestHiringTitle'] = strip_tags($jobTitle);
        $data1['sub_department_id'] = strip_tags($subDepartmentId);
        $data1['job_type_id'] = strip_tags($jobTypeId);
        $data1['designation_id'] = strip_tags($designationId);
        $data1['qualification_id'] = strip_tags($qualificationId);
        $data1['shift_type_id'] = strip_tags($shiftTypeId);
        $data1['RequestHiringGender'] = strip_tags($gender);
        $data1['RequestHiringSalaryStart'] = strip_tags($salaryStart);
        $data1['RequestHiringSalaryEnd'] = strip_tags($salaryEnd);
        $data1['RequestHiringAge'] = strip_tags($age);
        $data1['RequestHiringDescription'] = $jobDescription;
        $data1['location'] = strip_tags($location);
        $data1['experience'] = strip_tags($experience);
        $data1['career_level'] = strip_tags($career_level);
        $data1['apply_before_date'] = strip_tags($apply_before_date);
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('requesthiring')->where('id', Input::get('RequestHiringId'))->update($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewHiringRequestList?m=' . Input::get('company_id') . '#Online24');
    }


    public function editUserAccountDetail(Request $request)
    {
        $id = $request->id;
        $emp_id = $request->emp_id;
        $employee_id = $request->employee_id;
        $password = $request->password;
        $role_id = $request->role_id;
        $company_id = Input::get('company_id');

        if($password) {
            DB::table('users')->where('employee_id', $employee_id)->where('status', '1')->update(['password' => Hash::make($password)]);
        }

        $roles = Role::where([['id','=',$role_id]]);
        if($roles->count() > 0):

            MenuPrivileges::where('employee_id', $employee_id)->delete();
            $MenuPrivileges                         = new MenuPrivileges();
            $MenuPrivileges->emp_id                 = $emp_id;
            $MenuPrivileges->employee_id            = $employee_id;
            $MenuPrivileges->role_id                = $roles->value('id');
            $MenuPrivileges->main_modules           = $roles->value('main_modules');
            $MenuPrivileges->submenu_id             = $roles->value('submenu_id');
            $MenuPrivileges->menu_titles            = $roles->value('menu_titles');
            $MenuPrivileges->crud_rights            = $roles->value('crud_rights');
            $MenuPrivileges->location_permission    = $roles->value('location_permission');
            $MenuPrivileges->company_list           = $roles->value('company_list');
            $MenuPrivileges->status                 = 1;
            $MenuPrivileges->username               = Auth::user()->name;
            $MenuPrivileges->updated_at             = date("Y-m-d");
            $MenuPrivileges->save();

            CommonHelper::companyDatabaseConnection($company_id);

            CommonHelper::LogGenerator('MenuPrivileges',$MenuPrivileges->id,'insert','row is inserted','hr',Auth::user()->id);

            CommonHelper::reconnectMasterDatabase();

        endif;

        Session::flash('dataEdit', 'Duccessfully Updated');
        return Redirect::to('hr/viewUseAccountsList?m='.$company_id);
    }

    public function editEmployeeDetail(Request $request)
    {
        $company_id = Input::get('company_id');
        $username = Auth::user()->name;
        $status = 1;
        $date = date("Y-m-d");
        $time = date("H:i:s");

        
        CommonHelper::companyDatabaseConnection($company_id);
        //AttendanceHelper::addEmptyAttendanceRecords();

        $id = Input::get('id');
        $location_id = Input::get('location_id');
        $emp_id = Input::get('emp_id');
        $attendance_id = Input::get('attendance_id');
        $emp_name = Input::get('emp_name');
        $emp_father_name = Input::get('emp_father_name');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $designation_id = Input::get('designation_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $grade_id = Input::get('grade_id');
        $marital_status_id = Input::get('marital_status_id');
        $employment_status_id = Input::get('employment_status_id');
        $cpl_ot = Input::get('cpl_ot');
        $gender_id = Input::get('gender_id');
        $nationality = Input::get('nationality');
        $religion = Input::get('religion');
        $date_of_birth = Input::get('date_of_birth');
        $joining_date = Input::get('joining_date');
        $confirmation_date = Input::get('confirmation_date');
        $resign_date = Input::get('resign_date');
        $last_working_date = Input::get('last_working_date');
        $cnic = Input::get('cnic');
        $cnic_expiry_date = Input::get('cnic_expiry_date');
        $life_time_cnic = Input::get('life_time_cnic');
        $reporting_manager_id = Input::get('reporting_manager_id');
        $contact_no = HrHelper::replacePrefix(Input::get('contact_no'));
        $emergency_number = HrHelper::replacePrefix(Input::get('emergency_number'));
        $emergency_contact_person = Input::get('emergency_contact_person');
        $office_no = Input::get('office_no');
        $personal_email = Input::get('personal_email');
        $official_email = Input::get('official_email');
        $salary = Input::get('salary');
        $eobi_id = Input::get('eobi_id');
        $eobi_number = Input::get('eobi_number');
        $leaves_policy_id = Input::get('leaves_policy_id');
        $current_address = Input::get('current_address');
        $permanent_address = Input::get('permanent_address');
        $role_id = Input::get('role_id');

        $data['emp_id'] = $emp_id;
        $data['attendance_id'] = $attendance_id;
        $data['emp_name'] = $emp_name;
        $data['emp_father_name'] = $emp_father_name;
        $data['department_id'] = $department_id;
        $data['sub_department_id'] = $sub_department_id;
        $data['designation_id'] = $designation_id;
        $data['category_id'] = $category_id;
        $data['project_id'] = $project_id;
        $data['grade_id'] = $grade_id;
        $data['marital_status_id'] = $marital_status_id;
        $data['employment_status_id'] = $employment_status_id;
        $data['cpl_or_ot'] = $cpl_ot;
        $data['gender_id'] = $gender_id;
        $data['nationality'] = $nationality;
        $data['religion'] = $religion;
        $data['date_of_birth'] = $date_of_birth;
        $data['joining_date'] = $joining_date;
        $data['confirmation_date'] = $confirmation_date;
        $data['resign_date'] = $resign_date;
        $data['last_working_date'] = $last_working_date;
        $data['cnic'] = $cnic;
        $data['cnic_expiry_date'] = $cnic_expiry_date;
        $data['life_time_cnic'] = $life_time_cnic;
        $data['reporting_manager_id'] = $reporting_manager_id;
        $data['contact_no'] = $contact_no;
        $data['emergency_number'] = $emergency_number;
        $data['emergency_contact_person'] = $emergency_contact_person;
        $data['office_no'] = $office_no;
        $data['personal_email'] = $personal_email;
        $data['official_email'] = $official_email;
        $data['salary'] = $salary;
        $data['eobi_id'] = $eobi_id;
        $data['eobi_number'] = $eobi_number;
        $data['leaves_policy_id'] = $leaves_policy_id;
        $data['current_address'] = $current_address;
        $data['permanent_address'] = $permanent_address;
        $data['role_id'] = $role_id;
        $data['username'] = $username;
        $data['status'] = $status;
        $data['date'] = $date;
        $data['time'] = $time;

        //employee image
        if ($request->file('fileToUpload_1')):
            $file_name = $emp_name . '_' . time() . '.' . $request->file('fileToUpload_1')->getClientOriginalExtension();
            $path = 'app/' . $request->file('fileToUpload_1')->storeAs('uploads/employee_images', $file_name);
            $data['img_path'] = $path;
    
        endif;

        //eobi image
        if ($request->file('eobi_path')):
            $file_name1 = $emp_name . '_' . time() . '.' . $request->file('eobi_path')->getClientOriginalExtension();
            $path1 = 'app/' . $request->file('eobi_path')->storeAs('uploads/employee_eobi_copy', $file_name1);
            $data['eobi_path'] = $path1;
            $data['eobi_type'] = $request->file('eobi_path')->getClientOriginalExtension();
       
        endif;

        Employee::where('id', $id)->update($data);

        CommonHelper::LogGenerator('Employee',$id,'Update','row is updated','hr',Auth::user()->id);

        $oldstatus = Input::get('oldstatus');
        
        if($oldstatus != $employment_status_id && $oldstatus != 4 && $employment_status_id == 4)
        {
            HrHelper::remainingLeaveAfterConfirmation($company_id,$id);
        }
        

        //multiple locations
            EmployeeMultipleLocations::where([['employee_id', '=', $id]])->update(['status' => 2 ]);
        foreach ($location_id as $location_key => $location_value) {
            # code...
            $location['employee_id'] = $id;
            $location['location_id'] = $location_value;
            $location['company_id'] = $company_id;
            $location['project_id'] = $project_id;
            $location['username'] = $username;
            $location['status'] = $status;
            $location['date'] = $date;
            $location['time'] = $time;
            $EmployeeMultipleLocationslastid = EmployeeMultipleLocations::insertGetId($location);
            CommonHelper::LogGenerator('EmployeeMultipleLocations',$EmployeeMultipleLocationslastid,'insert','row is inserted','hr',Auth::user()->id);
        }

        if (Input::get('bank_account_check') == 'on'):
            EmployeeBankData::where([['employee_id','=', $id]])->delete();
            $bankData['employee_id'] = $id;
            $bankData['account_no'] = Input::get('account_no');
            $bankData['bank_name'] = Input::get('bank_name');
            $bankData['account_title'] = Input::get('account_title');
            $bankData['username'] = $username;
            $bankData['status'] = $status;
            $bankData['date'] = $date;
            $bankData['time'] = $time;
            $EmployeeBankDatalastid = EmployeeBankData::insertGetId($bankData);

            CommonHelper::LogGenerator('EmployeeBankData',$EmployeeBankDatalastid,'insert','row is inserted','hr',Auth::user()->id);

        endif;

        //family data
        EmployeeFamilyData::where('employee_id', '=', $id)->delete();
        if (!empty(Input::get('family_data'))):


            foreach (Input::get('family_data') as $familyRow):
                $familyData['employee_id'] = $id;
                $familyData['family_name'] = Input::get('family_name_' . $familyRow . '');
                $familyData['family_relation'] = Input::get('family_relation_' . $familyRow . '');
                $familyData['family_emergency_contact'] = Input::get('family_emergency_' . $familyRow . '');
                $familyData['username'] = $username;
                $familyData['status'] = $status;
                $familyData['date'] = $date;
                $familyData['time'] = $time;
                $EmployeeFamilyDatalastid = EmployeeFamilyData::insertGetId($familyData);

                CommonHelper::LogGenerator('EmployeeFamilyData',$EmployeeFamilyDatalastid,'insert','row is inserted','hr',Auth::user()->id);
            endforeach;
        endif;

        //Educational data
        EmployeeEducationalData::where('employee_id', '=', $id)->delete();
        if (!empty(Input::get('education_data'))):


            foreach (Input::get('education_data') as $educationalRow):
                $educationalData['employee_id'] = $id;
                $educationalData['institute_name'] = Input::get('institute_name_' . $educationalRow . '');
                $educationalData['year_of_admission'] = Input::get('year_of_admission_' . $educationalRow . '');
                $educationalData['year_of_passing'] = Input::get('year_of_passing_' . $educationalRow . '');
                $educationalData['degree_type'] = Input::get('degree_type_' . $educationalRow . '');
                $educationalData['username'] = $username;
                $educationalData['status'] = $status;
                $educationalData['date'] = $date;
                $educationalData['time'] = $time;
                $EmployeeEducationalDatalastid = EmployeeEducationalData::insertGetId($educationalData);
                CommonHelper::LogGenerator('EmployeeEducationalData',$EmployeeEducationalDatalastid,'insert','row is inserted','hr',Auth::user()->id);
            endforeach;
        endif;

        //Language data
        EmployeeLanguageProficiency::where('employee_id', '=', $id)->delete();
        if (!empty(Input::get('language_data'))):
            foreach (Input::get('language_data') as $languageRow):
                $languageData['employee_id'] = $id;
                $languageData['language_name'] = Input::get('language_name_' . $languageRow . '');
                $languageData['reading_skills'] = Input::get('reading_skills_' . $languageRow . '');
                $languageData['writing_skills'] = Input::get('writing_skills_' . $languageRow . '');
                $languageData['speaking_skills'] = Input::get('speaking_skills_' . $languageRow . '');
                $languageData['username'] = $username;
                $languageData['status'] = $status;
                $languageData['date'] = $date;
                $languageData['time'] = $time;
                $EmployeeLanguageProficiencylastid = EmployeeLanguageProficiency::insertGetId($languageData);
                CommonHelper::LogGenerator('EmployeeLanguageProficiency',$EmployeeLanguageProficiencylastid,'insert','row is inserted','hr',Auth::user()->id);

            endforeach;
        endif;

        //Health data
        EmployeeHealthData::where('employee_id', '=', $id)->delete();
        if (!empty(Input::get('health_data'))):
            foreach (Input::get('health_data') as $healthRow):
                $healthData['employee_id'] = $id;
                $healthData['health_type'] = Input::get('health_type_' . $healthRow . '');
                $healthData['health_check'] = Input::get('health_check_' . $healthRow . '');
                $healthData['physical_handicap'] = Input::get('physical_handicap');
                $healthData['height'] = Input::get('height');
                $healthData['weight'] = Input::get('weight');
                $healthData['blood_group'] = Input::get('blood_group');
                $healthData['username'] = $username;
                $healthData['status'] = $status;
                $healthData['date'] = $date;
                $healthData['time'] = $time;
                $EmployeeHealthDatalastid = EmployeeHealthData::insertGetId($healthData);
                CommonHelper::LogGenerator('EmployeeHealthData',$EmployeeHealthDatalastid,'insert','row is inserted','hr',Auth::user()->id);

            endforeach;
        endif;

        //Activity data
        EmployeeActivityData::where('employee_id', '=', $id)->delete();
        if (!empty(Input::get('activity_data'))):
            foreach (Input::get('activity_data') as $activityRow):
                $activityData['employee_id'] = $id;
                $activityData['institution_name'] = Input::get('institution_name_' . $activityRow . '');
                $activityData['position_held'] = Input::get('position_held_' . $activityRow . '');
                $activityData['username'] = $username;
                $activityData['status'] = $status;
                $activityData['date'] = $date;
                $activityData['time'] = $time;
                $EmployeeActivityDatalastid = EmployeeActivityData::insertGetId($activityData);
                CommonHelper::LogGenerator('EmployeeActivityData',$EmployeeActivityDatalastid,'insert','row is inserted','hr',Auth::user()->id);

            endforeach;
        endif;

        //work experience data

        EmployeeWorkExperience::where('employee_id', '=', $id)->delete();


        if (!empty(Input::get('work_experience_data'))):
            $counter1 = 1;
            foreach (Input::get('work_experience_data') as $workExperienceRow):

                if ($request->hasFile('work_exp_path_1')):
                    $extension = $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $file_name3 = $emp_id . '_' . $counter1 . '_' . time() . '.' . $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $path3 = 'app/' . $request->file('work_exp_path_' . $workExperienceRow . '')->storeAs('uploads/employee_experience_documents', $file_name3);

                    $workExperienceData['work_exp_path'] = $path3;
                    $workExperienceData['work_exp_name'] = $file_name3;
                    $workExperienceData['work_exp_type'] = $extension;
                
                endif;

                $counter1++;
                $workExperienceData['employee_id'] = $id;
                $workExperienceData['employeer_name'] = Input::get('employeer_name_' . $workExperienceRow . '');
                $workExperienceData['started'] = Input::get('started_' . $workExperienceRow . '');
                $workExperienceData['ended'] = Input::get('ended_' . $workExperienceRow . '');
                $workExperienceData['suspend_check'] = Input::get('suspend_check_1');
                $workExperienceData['suspend_reason'] = Input::get('suspend_reason_1');
                $workExperienceData['username'] = $username;
                $workExperienceData['status'] = $status;
                $workExperienceData['date'] = $date;
                $workExperienceData['time'] = $time;
                $EmployeeWorkExperiencelastid = EmployeeWorkExperience::insertGetId($workExperienceData);
                CommonHelper::LogGenerator('EmployeeWorkExperience',$EmployeeWorkExperiencelastid,'insert','row is inserted','hr',Auth::user()->id);

            endforeach;
        endif;

        //Reference data
        EmployeeReferenceData::where('employee_id', '=', $id)->delete();

        if (!empty(Input::get('reference_data'))):
            foreach (Input::get('reference_data') as $referenceRow):
                $referenceData['employee_id'] = $id;
                $referenceData['reference_name'] = Input::get('reference_name_' . $referenceRow . '');
                $referenceData['reference_designation'] = Input::get('reference_designation_' . $referenceRow . '');
                $referenceData['reference_organization'] = Input::get('reference_organization_' . $referenceRow . '');
                $referenceData['reference_contact'] = Input::get('reference_contact_' . $referenceRow . '');
                $referenceData['reference_relationship'] = Input::get('reference_relationship_' . $referenceRow . '');
                $referenceData['username'] = $username;
                $referenceData['status'] = $status;
                $referenceData['date'] = $date;
                $referenceData['time'] = $time;
                $EmployeeReferenceDatalastid = EmployeeReferenceData::insertGetId($referenceData);
                CommonHelper::LogGenerator('EmployeeReferenceData',$EmployeeReferenceDatalastid,'insert','row is inserted','hr',Auth::user()->id);

            endforeach;
        endif;

        //kins data
        EmployeeKinsData::where('employee_id', '=', $id)->delete();

        if (!empty(Input::get('kins_data'))):
            foreach (Input::get('kins_data') as $kinsRow):
                $kinsData['employee_id'] = $id;
                $kinsData['next_kin_name'] = Input::get('next_kin_name_' . $kinsRow . '');
                $kinsData['next_kin_relation'] = Input::get('next_kin_relation_' . $kinsRow . '');
                $kinsData['username'] = $username;
                $kinsData['status'] = $status;
                $kinsData['date'] = $date;
                $kinsData['time'] = $time;
                $EmployeeKinsDatalastid = EmployeeKinsData::insertGetId($kinsData);

                CommonHelper::LogGenerator('EmployeeKinsData',$EmployeeKinsDatalastid,'insert','row is inserted','hr',Auth::user()->id);

            endforeach;
        endif;

        //relatives data
        EmployeeRelativesData::where('employee_id', '=', $id)->delete();

        if (!empty(Input::get('relatives_data'))):
            foreach (Input::get('relatives_data') as $relativesRow):
                $relativesData['employee_id'] = $id;
                $relativesData['relative_name'] = Input::get('relative_name_' . $relativesRow . '');
                $relativesData['relative_position'] = Input::get('relative_position_' . $relativesRow . '');
                $relativesData['username'] = $username;
                $relativesData['status'] = $status;
                $relativesData['date'] = $date;
                $relativesData['time'] = $time;
                $EmployeeRelativesDatalastid = EmployeeRelativesData::insertGetId($relativesData);

                CommonHelper::LogGenerator('EmployeeRelativesData',$EmployeeRelativesDatalastid,'insert','row is inserted','hr',Auth::user()->id);

            endforeach;
        endif;

        //other details
        EmployeeOtherDetails::where('employee_id', '=', $id)->delete();

        $otherDetails['employee_id'] = $id;
        $otherDetails['crime_check'] = Input::get('crime_check');
        $otherDetails['crime_detail'] = Input::get('crime_detail');
        $otherDetails['additional_info_check'] = Input::get('additional_info_check');
        $otherDetails['additional_info_detail'] = Input::get('additional_info_detail');
        $otherDetails['username'] = $username;
        $otherDetails['status'] = $status;
        $otherDetails['date'] = $date;
        $otherDetails['time'] = $time;
        $EmployeeOtherDetailslastid = EmployeeOtherDetails::insertGetId($otherDetails);

        CommonHelper::LogGenerator('EmployeeOtherDetails',$EmployeeOtherDetailslastid,'insert','row is inserted','hr',Auth::user()->id);

        //Documents uploading
        $employee_documents_count = EmployeeDocuments::where([['status', '=', 1], ['employee_id', '=', $id]])->max('counter');
        $counter = $employee_documents_count;
        if($request->documents_upload_check){
            if ($request->file('media')) {
                foreach ($request->file('media') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'cv';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('edu_degrees')) {
                foreach ($request->file('edu_degrees') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'education';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('passport_photos')) {
                foreach ($request->file('passport_photos') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'passport';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('clearance_letter')) {
                foreach ($request->file('clearance_letter') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'clearance_letter';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('fit_and_criteria_form')) {
                foreach ($request->file('fit_and_criteria_form') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'fit_criteria_form';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('company_service_rules')) {
                foreach ($request->file('company_service_rules') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'company_service_rules';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('code_of_conduct')) {
                foreach ($request->file('code_of_conduct') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'code_of_conduct';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('non_disclosure_agreement')) {
                foreach ($request->file('non_disclosure_agreement') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'non_disclosure_agreement';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('confidentially_form')) {
                foreach ($request->file('confidentially_form') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'confidentially_form';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
            if ($request->file('cnic_path_1')) {
                foreach ($request->file('cnic_path_1') as $media) {
                    
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id .'_'. time() .'_'. $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);
                        
                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = $path;
                        $fileUploadData['type'] = 'cnic_copy';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);
                    }
                }
            }

            if ($request->file('investment_disclosure')) {
                foreach ($request->file('investment_disclosure') as $media) {
                    if (!empty($media)) {
                        $counter++;
                        $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                        $path = $media->storeAs('uploads/employee_documents', $file_name);

                        $fileUploadData['employee_id'] = $id;
                        $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                        $fileUploadData['file_name'] = $file_name;
                        $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                        $fileUploadData['file_path'] = 'app/' . $path;
                        $fileUploadData['type'] = 'investment_disclosure';
                        $fileUploadData['counter'] = $counter;
                        $fileUploadData['username'] = $username;
                        $fileUploadData['status'] = $status;
                        $fileUploadData['date'] = $date;
                        $fileUploadData['time'] = $time;
                        $EmployeeDocumentslastid = EmployeeDocuments::insertGetId($fileUploadData);
                        CommonHelper::LogGenerator('EmployeeDocuments',$EmployeeDocumentslastid,'insert','row is inserted','hr',Auth::user()->id);

                    }
                }
            }
        }

        if($request->employee_items_check && !empty(Input::get('equipment_id'))):
            EmployeeEquipments::where('employee_id', $id)->delete();

            foreach (Input::get('equipment_id') as $key => $val):
                if (strip_tags($val) == 11):
                    $data1['model_number'] = Input::get('model_number');
                    $data1['mobile_number'] = Input::get('mobile_number');
                    $data1['sim_number'] = Input::get('sim_number');
                endif;

                $data1['equipment_id'] = strip_tags($val);
                $data1['employee_id'] = $id;
                $data1['approval_status'] = 1;
                $data1['username'] = $username;
                $data1['status'] = $status;
                $data1['date'] = $date;
                $data1['time'] = $time;
                $EmployeeEquipmentslastid = EmployeeEquipments::insertGetId($data1);
                CommonHelper::LogGenerator('EmployeeEquipments',$EmployeeEquipmentslastid,'insert','row is inserted','hr',Auth::user()->id);

            endforeach;
        endif;

        CommonHelper::reconnectMasterDatabase();

        if (Input::get('can_login') && $contact_no != ''):
            $employee_password = Input::get('password');
            $employee_account_type = Input::get('account_type');
            $dataCredentials['employee_id'] = $id;
            $dataCredentials['emp_id'] = $emp_id;
            $dataCredentials['name'] = $emp_name;
            $dataCredentials['username'] = $official_email;
            $dataCredentials['email'] = $official_email;
            $dataCredentials['mobile_no'] = $contact_no;
            if($employee_password):
                $dataCredentials['password'] = Hash::make($employee_password);
            endif;
            $dataCredentials['acc_type'] = $employee_account_type;
            $dataCredentials['updated_at'] = $date;
            $dataCredentials['created_at'] = $date;
            $dataCredentials['company_id'] = $company_id;
            Users::updateOrCreate(['employee_id' => $id],$dataCredentials);

            $roles = Role::where([['id','=',$role_id]]);
            if($roles->count() > 0):

                MenuPrivileges::where('employee_id', $id)->delete();
                $MenuPrivileges                         = new MenuPrivileges();
                $MenuPrivileges->emp_id                 = $emp_id;
                $MenuPrivileges->employee_id            = $id;
                $MenuPrivileges->role_id                = $role_id;
                $MenuPrivileges->main_modules           = $roles->value('main_modules');
                $MenuPrivileges->submenu_id             = $roles->value('submenu_id');
                $MenuPrivileges->menu_titles            = $roles->value('menu_titles');
                $MenuPrivileges->crud_rights            = $roles->value('crud_rights');
                $MenuPrivileges->location_permission    = $roles->value('location_permission');
                $MenuPrivileges->company_list           = $roles->value('company_list');
                $MenuPrivileges->status                 = $status;
                $MenuPrivileges->username               = $username;
                $MenuPrivileges->updated_at             = $date;
                $MenuPrivileges->save();

                CommonHelper::companyDatabaseConnection($company_id);

                CommonHelper::LogGenerator('MenuPrivileges',$MenuPrivileges->id,'insert','row is inserted','hr',Auth::user()->id);

                CommonHelper::reconnectMasterDatabase();

            endif;
        endif;
        RefreshCacheHelper::EmployeeCache();

        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewEmployeeList?m='.$company_id);
    }

    public function editEmployeeLeavingDetail()
    {
        $last_working_date = Input::get('last_working_date');
        $id = Input::get('recordId');
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        EmployeeLeavingReason::where([['employee_id','=',$id]])->delete();
        $data['employee_id']        = $id;
        $data['leaving_reason']     = Input::get('leaving_reason');
        $data['last_working_date']  = $last_working_date;
        $data['username']           = Auth::user()->name;
        $data['status']             = 1;
        $data['date']               = date("Y-m-d");
        $data['time']               = date("H:i:s");
        DB::table('employee')->where([['id','=',$id]])
            ->update(array('status'=>'4', 'can_login'=>'no', 'last_working_date' => $last_working_date));

        DB::table('employee_leaving_reason')->insert($data);
        CommonHelper::reconnectMasterDatabase();
        DB::table('users')->where([['employee_id','=',$id]])->update(array('status'=>'2'));

        Cache::rememberForever('employee', function () {
            $values = Employee::all();
            $data_array = [];
            foreach($values as $key => $val):
                $data_array[$val->id] = $val;
            endforeach;
            return $data_array;
        });
        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewEmployeeList?m='.Input::get('company_id'));
    }

    public function editAllowanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $data['allowance_type_id'] = Input::get('allowance_type_id');
        $data['allowance_amount'] = Input::get('allowance_amount');
        $data['remarks'] = Input::get('remarks');
        $data['once'] = Input::get('once');
        if(Input::get('month_year') != ''):
            $month_year = explode('-', Input::get('month_year'));
            $data['month'] = $month_year[1];
            $data['year'] = $month_year[0];
        else:
            $data['month'] = "";
            $data['year'] = "";
        endif;
        $data['username'] = Auth::user()->name;
        $data['status'] = 1;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        Allowance::where([['id', '=', Input::get('id')]])->update($data);

        CommonHelper::LogGenerator('Allowance',Input::get('id'),'Update','row is updated' ,'hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewAllowanceList?m='.Input::get('company_id'));

    }

    public function editDeductionDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $month_year = explode('-', Input::get('month_year'));
        $data1['deduction_type'] = Input::get('deduction_type_id');
        $data1['deduction_amount'] = Input::get('deduction_amount');
        $data1['remarks'] = Input::get('remarks');
        $data1['once'] = Input::get('once');
        if(Input::get('month_year') != ''){
            $data1['month'] = $month_year[1];
            $data1['year'] = $month_year[0];
        }
        else{
            $data1['month'] = "";
            $data1['year'] = "";
        };
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        deduction::where([['id', '=', Input::get('id')]])->update($data1);
        CommonHelper::LogGenerator('deduction',Input::get('id'),'Update','row is updated','hr',Auth::user()->id);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit', 'Successfully Updated');
        return Redirect::to('hr/viewDeductionList?m=' . Input::get('company_id'));
    }

    public function editAdvanceSalaryDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $implode_date = explode("-", Input::get('deduction_month_year'));

        $data1['advance_salary_amount'] = Input::get('advance_salary_amount');
        $data1['salary_needed_on'] = Input::get('salary_needed_date');
        $data1['deduction_year'] = $implode_date[0];
        $data1['deduction_month'] = $implode_date[1];
        $data1['detail'] = Input::get('advance_salary_detail');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");


        DB::table('advance_salary')->where([['id', '=', Input::get('id')]])->update($data1);

        CommonHelper::LogGenerator('advance_salary',Input::get('id'),'Update','row is updated','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAdvanceSalaryList?m=' . Input::get('company_id') . '#vis');


    }

    public function editLeavesPolicyDetail()
    {
        $id = Input::get('id');
        $data1['leaves_policy_name'] = strip_tags(Input::get('leaves_policy_name'));
        $data1['policy_date_from'] = strip_tags(Input::get('PolicyDateFrom'));
        $data1['policy_date_till'] = strip_tags(Input::get('PolicyDateTill'));
        $data1['terms_conditions'] = Input::get('terms_conditions');
        $data1['fullday_deduction_rate'] = Input::get('full_day_deduction_rate');
        $data1['halfday_deduction_rate'] = Input::get('half_day_deduction_rate');
        $data1['per_hour_deduction_rate'] = Input::get('per_hour_deduction_rate');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['time'] = date("H:i:s");
        $data1['date'] = date("Y-m-d");

        LeavesPolicy::where([['id', '=', $id]])->update($data1);
        LeavesData::where([['leaves_policy_id', '=', $id]])->delete();
        if (Input::get('leaves_type_id')) {
            foreach (Input::get('leaves_type_id') as $key => $val):

                $data2['leaves_policy_id'] = $id;
                $data2['leave_type_id'] = $val;
                $data2['no_of_leaves'] = Input::get('no_of_leaves')[$key];
                $data2['username'] = Auth::user()->name;;
                $data2['status'] = 1;
                $data2['time'] = date("H:i:s");
                $data2['date'] = date("Y-m-d");
                DB::table('leaves_data')->insert($data2);
            endforeach;
        }

        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewLeavesPolicyList?m=' . Input::get('company_id'));
    }

    public function editVehicleTypeDetail()
    {
        $data1['vehicle_type_name'] = strip_tags(Input::get('vehicle_type_name'));
        $data1['vehicle_type_cc'] = strip_tags(Input::get('vehicle_type_cc'));
        $data1['username'] = Auth::user()->name;
        $data1['company_id'] = Input::get('m');
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");


        DB::table('vehicle_type')->where([['id', '=', Input::get('record_id')], ['company_id', '=', Input::get('company_id')]])->update($data1);
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewVehicleTypeList?m=' . Input::get('company_id') . '#vis');

    }

    public function editCarPolicyDetail()
    {

        $data1['designation_id'] = Input::get('designation_id');
        $data1['vehicle_type_id'] = Input::get('vehicle_type_id');
        $data1['policy_name'] = Input::get('policy_name');
        $data1['start_salary_range'] = Input::get('start_salary_range');
        $data1['end_salary_range'] = Input::get('end_salary_range');
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        DB::table('car_policy')->where([['id', '=', Input::get('record_id')]])->update($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewCarPolicyList?m=' . Input::get('company_id') . '#vis');

    }

    public function editQualificationDetail()
    {
        $qualificationSection = Input::get('qualificationSection');
        foreach ($qualificationSection as $row) {
            $qualification_name = Input::get('qualification_name_' . $row . '');
            $country = Input::get('country_' . $row . '');
            $state = Input::get('state_' . $row . '');
            $city = Input::get('city_' . $row . '');
            $institute = Input::get('institute_id_' . $row . '');
            $data2['qualification_name'] = strip_tags($qualification_name);
            $data2['institute_id'] = strip_tags($institute);
            $data2['country_id'] = strip_tags($country);
            $data2['state_id'] = strip_tags($state);
            $data2['city_id'] = strip_tags($city);
            $data2['username'] = Auth::user()->name;
            $data2['status'] = 1;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['company_id'] = $_GET['m'];

            DB::table('qualification')->where([['id', '=', Input::get('qualification_id_1')]])->update($data2);


        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewQualificationList?m=' . $_GET['m'] . '#vis');

    }


    public function editLoanRequestDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $month_data = (explode("-", Input::get('needed_on_date')));
        $data1['year'] = $month_data[0];
        $data1['month'] = $month_data[1];
        $data1['month_year'] = $month_data[0].'-'.$month_data[1].'-01';
        $data1['loan_type_id'] = Input::get('loan_type_id');
        $data1['loan_amount'] = Input::get('loan_amount');
        $data1['out_standing_amount'] = Input::get('out_standing_amount');
        $data1['per_month_deduction'] = Input::get('per_month_deduction');
        $data1['description'] = Input::get('loan_description');
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('loan_request')->where([['id', '=', Input::get('id')]])->update($data1);

        CommonHelper::LogGenerator('loan_request',Input::get('id'),'Update','row is updated' ,'hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLoanRequestList?m=' . Input::get('company_id') . '#vis');

    }



//    public function editTaxesDetail()
//    {
//        $data1['tax_name'] = Input::get('tax_name');
//        $data1['salary_range_from'] = Input::get('salary_range_from');
//        $data1['salary_range_to'] = Input::get('salary_range_to');
//        $data1['tax_mode'] = Input::get('tax_mode');
//        $data1['tax_percent'] = Input::get('tax_percent');
//        $data1['tax_month_year'] = Input::get('tax_month_year');
//        $data1['company_id'] = Input::get('company_id');
//        $data1['username'] = Auth::user()->name;;
//        $data1['date'] = date("Y-m-d");
//        $data1['time'] = date("H:i:s");
//
//        DB::table('tax')->where([['company_id', '=', Input::get('company_id')], ['id', '=', Input::get('recordId')]])->update($data1);
//        Session::flash('dataInsert', 'successfully saved.');
//        return Redirect::to('hr/viewTaxesList?m=' . Input::get('company_id') . '#vis');
//
//
//    }

    public function editTaxesDetail()
    {
        $data['tax_name'] = Input::get('tax_name');
        $data['tax_month_year'] = Input::get('tax_month_year');
        $data['status'] = 1;
        $data['username'] = Auth::user()->name;;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        DB::table('tax')->where('id', Input::get('id'))->update($data);

        DB::table('tax_slabs')->where('tax_id', '=', Input::get('id'))->delete();
        foreach (Input::get('salary_range_from') as $key => $val):

            $data1['slab_name'] = Input::get('tax_name');
            $data1['tax_id'] = Input::get('id');
            $data1['salary_range_from'] = $val;
            $data1['salary_range_to'] = Input::get('salary_range_to')[$key];
            $data1['tax_mode'] = Input::get('tax_mode')[$key];
            $data1['tax_percent'] = Input::get('tax_percent')[$key];
            $data1['tax_amount'] = Input::get('tax_amount')[$key];
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('tax_slabs')->insert($data1);

        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewTaxesList?m='.Input::get('company_id').'#vis');
    }
    public function editIncomeTaxDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $id=$request->id;
        $amount=$request->amount;

        $data1['amount']=$amount;
        $data1['status']=1;

        DB::table('income_tax')->where('id',$id)->update($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewIncomeTaxList?m=' . Input::get('company_id') . '#vis');
    }

    public function editBonusDetail()
    {

        $data1['bonus_name'] = Input::get('Bonus_name');
        $data1['percent_of_salary'] = Input::get('percent_of_salary');
        $data1['type'] = Input::get('type') == 1 ? 'Percent' : 'Amount';
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        DB::table('bonus')->where([['id', '=', Input::get('recordId')]])->update($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewBonusList?m=' . Input::get('company_id') . '#vis');
    }

    public function editHolidayDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $month_year = explode('-', Input::get('holiday_date'));

        $data1['holiday_name'] = Input::get('holiday_name');
        $data1['holiday_date'] = Input::get('holiday_date');
        $data1['year'] = $month_year[0];
        $data1['month'] = $month_year[1];
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("d-m-Y");
        $data1['time'] = date("H:i:s");

        DB::table('holidays')->where([['id', '=', Input::get('record_id')]])->update($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewHolidaysList?m=' . Input::get('company_id') . '#vis');
    }

    public function editEmployeeDepositDetail(Request $request)
    {
        $depositId = Input::get('depositId');
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));


        $month_and_year = explode('-', Input::get('to_be_deduct_on_date'));
        $employeeDepositUpdate['sub_department_id'] = Input::get('sub_department_id');
        $employeeDepositUpdate['acc_no'] = Input::get('employee_id');
        $employeeDepositUpdate['deposit_name'] = Input::get('deposit_name');
        $employeeDepositUpdate['deposit_amount'] = Input::get('deposit_amount');
        $employeeDepositUpdate['deduction_month'] = $month_and_year[1];
        $employeeDepositUpdate['deduction_year'] = $month_and_year[0];
        $employeeDepositUpdate['username'] = Auth::user()->name;
        $employeeDepositUpdate['status'] = 1;
        $employeeDepositUpdate['date'] = date("Y-m-d");
        $employeeDepositUpdate['time'] = date("H:i:s");
        DB::table('employee_deposit')->where([['id', '=', $depositId]])->update($employeeDepositUpdate);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataUpdate', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeDepositList?m=' . Input::get('company_id') . '#vis');
    }

    public function editEmployeeRegionsDetail()
    {

        $emp_region_id = Input::get('recordId');
        $data1['employee_region'] = Input::get('employee_region');
        $data1['username'] = Auth::user()->name;
        $data1['company_id'] = $_GET['m'];
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('regions')->where('id', $emp_region_id)->update($data1);

        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeRegionsList?m=' . $_GET['m'] . '#Online24');
    }

    public function editEmployeeExitClearanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $id = Input::get('id');

        $data1['leaving_type'] = Input::get('leaving_type');
        $data1['supervisor_name'] = Input::get('supervisor_name');
        $data1['signed_by_supervisor'] = Input::get('signed_by_supervisor');
        $data1['last_working_date'] = Input::get('last_working_date');
        $data1['branch'] = Input::get('branch');

        $data1['room_key'] = Input::get('room_key');
        $data1['room_key_remarks'] = Input::get('room_key_remarks');
        $data1['mobile_sim'] = Input::get('mobile_sim');
        $data1['mobile_sim_remarks'] = Input::get('mobile_sim_remarks');
        $data1['fuel_card'] = Input::get('fuel_card');
        $data1['fuel_card_remarks'] = Input::get('fuel_card_remarks');
        $data1['mfm_employee_card'] = Input::get('mfm_employee_card');
        $data1['mfm_employee_card_remarks'] = Input::get('mfm_employee_card_remarks');
        $data1['client_access_card'] = Input::get('client_access_card');
        $data1['client_access_card_remarks'] = Input::get('client_access_card_remarks');
        $data1['medical_insurance_card'] = Input::get('medical_insurance_card');
        $data1['medical_insurance_card_remarks'] = Input::get('medical_insurance_card_remarks');
        $data1['eobi_card'] = Input::get('eobi_card');
        $data1['eobi_card_remarks'] = Input::get('eobi_card_remarks');
        $data1['biometric_scan'] = Input::get('biometric_scan');
        $data1['biometric_scan_remarks'] = Input::get('biometric_scan_remarks');
        $data1['payroll_deduction'] = Input::get('payroll_deduction');
        $data1['payroll_deduction_remarks'] = Input::get('payroll_deduction_remarks');
        $data1['info_sent_to_client'] = Input::get('info_sent_to_client');
        $data1['info_sent_to_client_remarks'] = Input::get('info_sent_to_client_remarks');
        $data1['client_exit_checklist'] = Input::get('client_exit_checklist');
        $data1['client_exit_checklist_remarks'] = Input::get('client_exit_checklist_remarks');
        $data1['exit_interview'] = Input::get('exit_interview');
        $data1['exit_interview_remarks'] = Input::get('exit_interview_remarks');
        $data1['laptop'] = Input::get('laptop');
        $data1['laptop_remarks'] = Input::get('laptop_remarks');
        $data1['desktop_computer'] = Input::get('desktop_computer');
        $data1['desktop_computer_remarks'] = Input::get('desktop_computer_remarks');
        $data1['email_account_deactivated'] = Input::get('email_account_deactivated');
        $data1['email_account_deactivated_remarks'] = Input::get('email_account_deactivated_remarks');
        $data1['toolkit_ppe'] = Input::get('toolkit_ppe');
        $data1['toolkit_ppe_remarks'] = Input::get('toolkit_ppe_remarks');
        $data1['uniform'] = Input::get('uniform');
        $data1['uniform_remarks'] = Input::get('uniform_remarks');
        $data1['advance_loan'] = Input::get('advance_loan');
        $data1['advance_loan_remarks'] = Input::get('advance_loan_remarks');
        $data1['extra_leaves'] = Input::get('extra_leaves');
        $data1['extra_leaves_remarks'] = Input::get('extra_leaves_remarks');
        $data1['final_settlement'] = Input::get('final_settlement');
        $data1['final_settlement_remarks'] = Input::get('final_settlement_remarks');
        $data1['note'] = Input::get('note');
        $data1['laptop_other_devices']=Input::get('laptop_other_devices');
        $data1['cr_manuals']=Input::get('cr_manuals');
        $data1['other']=Input::get('other');
        $data1['cr_reference_books']=Input::get('cr_reference_books');
        $data1['laptop_other_devices_remarks']=Input::get('laptop_other_devices_remarks');
        $data1['cr_manuals_remarks']=Input::get('cr_manuals_remarks');
        $data1['cr_reference_books_remarks']=Input::get('cr_reference_books_remarks');
        $data1['other_remarks']=Input::get('other_remarks');
        $data1['exit_from_attendance_system']=Input::get('exit_from_attendance_system');
        $data1['inactivate_vis_login']=Input::get('inactivate_vis_login');
        $data1['network_login']=Input::get('network_login');
        $data1['inactivate_vis_ratio_login']=Input::get('inactivate_vis_ratio_login');
        $data1['change_email_password']=Input::get('change_email_password');
        $data1['omit_name']=Input::get('omit_name');
        $data1['email_diverted_to_supervisor']=Input::get('email_diverted_to_supervisor');
        $data1['remove_name_from_web']=Input::get('remove_name_from_web');
        $data1['inactivate_vista_plus_login']=Input::get('inactivate_vista_plus_login');
        $data1['remove_from_vis_whatsapp']=Input::get('remove_from_vis_whatsapp');
        $data1['inactivate_own_cloud_login']=Input::get('inactivate_own_cloud_login');
        $data1['leaving_statement']=Input::get('leaving_statement');

        $data1['username'] = Auth::user()->name;
        $data1['approval_status'] = 1;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_exit')->where('id', $id)->update($data1);

        CommonHelper::LogGenerator('employee_exit',$id,'update','row is updated','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataEdit', 'successfully edit.');
        return Redirect::to('hr/viewEmployeeExitClearanceList?m=' . Input::get('company_id') . '#vis');
    }


    public function editAttendanceDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $att_status= $request->att_status;
        $emp_id = $request->emp_id;
        $data['attendance_date'] = $request->att_date;
        if($att_status == 'present'){
            $data['clock_in'] = '08:00:00';
            $data['clock_out'] = '06:00:00';
        }else{
            $data['clock_in'] = '';
            $data['clock_out'] = '';
        }

        $data['status'] = 1;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        DB::table('attendance')->where('emp_id', $emp_id)->where('attendance_date',$request->att_date)->update($data);
        CommonHelper::reconnectMasterDatabase();
        return "success";

    }

    public function updateAttendanceError(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        
        
        if($request->working_hours_policy_id)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));

                $emp_id = $request->emp_id;
                $att_id = $request->att_id;
                $attendance_date = $request->attendance_date;
                $clock_in = $request->clock_in;
                $clock_out = $request->clock_out;
                $working_hours_policy_id = $request->working_hours_policy_id;
                $m = $request->m;
                
                
                
                $data['clock_in'] = (!empty($clock_in) || $clock_in)? $clock_in : null ;
                $data['clock_out'] = (!empty($clock_out) || $clock_out)? $clock_out : null ;
                $data['working_hours_policy_id'] = $working_hours_policy_id;
                $data['status'] = 1;
                $data['username'] = 'attendance_error_update';
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('attendance')->where([
                    ['id', $att_id],
                    ['attendance_date', $attendance_date],
                    ['employee_id', $emp_id]
                    ])->update($data);
            CommonHelper::reconnectMasterDatabase();
            return "success";
        }
        else
        {
            return "false";

        }

    }

    public function editFinalSettlementDetail()
    {
        $employee_id = Input::get('employee_id');
        $data['last_working_date'] = Input::get('last_working_date');
        $data['salary_upto'] = Input::get('salary_upto');
        $data['salary_amount'] = Input::get('salary_amount');
        $data['salary_cheque_no'] = Input::get('salary_cheque_no');
        $data['salary_cheque_date'] = Input::get('salary_cheque_date');
        $data['other_amount_narration'] = Input::get('other_amount_narration');
        $data['other_amount'] = Input::get('other_amount');
        $data['leave_encashment_days'] = Input::get('leave_encashment_days');
        $data['leave_encashment_amount'] = Input::get('leave_encashment_amount');
        $data['hold_salary'] = Input::get('hold_salary');
        $data['hold_salary_month'] = Input::get('hold_salary_month');
        $data['hold_salary_year'] = Input::get('hold_salary_year');
        $data['leave_deduction_days'] = Input::get('leave_deduction_days');
        $data['leave_deduction_amount'] = Input::get('leave_deduction_amount');
        $data['loan_deduction'] = Input::get('loan_deduction');
        $data['gratuity'] = Input::get('gratuity');
        $data['loan_id'] = Input::get('loan_id');
        $data['opd_excess'] = Input::get('opd_excess');
        $data['other_deduction_type'] = Input::get('other_deduction_type');
        $data['other_deduction_amount'] = Input::get('other_deduction_amount');
        $data['status'] = 1;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        $emp_status['status'] = 2;
        $company_id = Input::get('company_id');
        CommonHelper::companyDatabaseConnection($company_id);
        Employee::where([['id', '=', $employee_id]])->update($emp_status);

        CommonHelper::LogGenerator('Employee',$employee_id,'update','row is updated','hr',Auth::user()->id);

        FinalSettlement::where([['id', '=', Input::get('id')]])->update($data);

        CommonHelper::LogGenerator('final_settlement',Input::get('id'),'update','row is updated','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','Successfully Updated');
        return Redirect::to('hr/viewFinalSettlementList?m='.$company_id );
    }

    public function editEmployeeIdCardRequestDetail(Request $request)
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        if($request->hasFile('fir_copy')):
            $extension = $request->file('fir_copy')->getClientOriginalExtension();
            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('fir_copy')->getClientOriginalExtension();
            $path = $request->file('fir_copy')->storeAs('uploads/employee_id_card_fir_copy', $file_name);
            $data1['fir_copy_path'] =    'app/'.$path;
            $data1['fir_copy_extension'] =  $extension;
        endif;

        if($request->hasFile('card_image')):

            $extension = $request->file('card_image')->getClientOriginalExtension();
            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('card_image')->getClientOriginalExtension();
            $path = $request->file('card_image')->storeAs('uploads/employee_id_card_images', $file_name);
            $data1['card_image_path'] =    'app/'.$path;
            $data1['card_image_extension'] =  $extension;
        endif;

        if(Input::get('card_replacement') == 0)
        {
            $data1['fir_copy_path'] =    null;
            $data1['fir_copy_extension'] = null;
        }

        $id = Input::get('id');

        $data1['username']         = Auth::user()->name;
        $data1['posted_at']        = Input::get('posted_at');
        $data1['card_replacement'] = Input::get('card_replacement');
        $data1['replacement_type'] = Input::get('replacement_type');
        $data1['payment']          = Input::get('payment');
        $data1['approval_status']  = 1;
        $data1['status'] 		   = 1;
        $data1['date']     		   = date("Y-m-d");
        $data1['time']     		   = date("H:i:s");

        DB::table('employee_card_request')->where('id', $id)->update($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('hr/viewEmployeeIdCardRequestList?m='.Input::get('company_id').'#vis');
    }

    public function editEmployeePromotionDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $id = Input::get('id');
        $edit_salary = Input::get('edit_salary');
        if($edit_salary == 1):
            $data['increment'] = Input::get('increment');
            $data1['salary'] = Input::get('salary');
        endif;

        $data['designation_id'] = Input::get('designation_id');
        $data['grade_id'] = Input::get('grade_id');
        $data['status'] = 1;
        $data['approval_status'] = 1;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        DB::table('employee_promotion')->where('id', $id)->update($data);

        CommonHelper::LogGenerator('employee_promotion',$id,'update','row is updated','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','Successfully Updated');
        return Redirect::to('hr/viewEmployeePromotionsList?m='.Input::get('company_id'));
    }

    public function editEmployeeTransferDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $location_check = Input::get('location_check');
        $edit_salary = Input::get('edit_salary');
        $transfer_project_check = Input::get('transfer_project_check');

        if ($location_check != '')
            $location_check = 1;
        else
            $location_check = 0;

        $location_id = Input::get('id');
        $employeeLocationDetail = EmployeeTransfer::select('emr_no', 'promotion_id','transfer_project_id')->where([['id', '=', $location_id]])->first()->toArray();

        $promotion_id = $employeeLocationDetail['promotion_id'];
        $transfer_project_id = $employeeLocationDetail['transfer_project_id'];

        if ($location_check == 1) {
            if ($promotion_id != 0) {
                if ($edit_salary == 1):
                    $data['increment'] = Input::get('increment');
                    $data['salary'] = Input::get('salary');
                endif;
                $data['designation_id'] = Input::get('designation_id');
                $data['grade_id'] = Input::get('grade_id');
                $data['status'] = 1;
                $data['approval_status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('employee_promotion')->where('id', $promotion_id)->update($data);

                $data1['location_id'] = Input::get('location_id');
                $data1['status'] = 1;
                $data1['approval_status'] = 1;
                $data1['username'] = Auth::user()->name;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('employee_location')->where('id', $location_id)->update($data1);
            }

            if ($promotion_id == 0) {
                $data['emr_no'] = Input::get('emr_no');
                $data['designation_id'] = Input::get('designation_id');
                $data['grade_id'] = Input::get('grade_id');
                $data['increment'] = Input::get('increment');
                $data['salary'] = Input::get('salary');
                $data['promotion_date'] = Input::get('promotion_date');
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['approval_status'] = 1;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('employee_promotion')->insert($data);
                $promotion_id = DB::getPdo()->lastInsertId();

                $data1['location_id'] = Input::get('location_id');
                $data1['promotion_id'] = $promotion_id;
                $data1['status'] = 1;
                $data1['username'] = Auth::user()->name;
                $data1['approval_status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('employee_location')->where('id', $location_id)->update($data1);
            }
        }
        elseif ($location_check == 0) {
            if ($promotion_id != 0) {
                DB::table('employee_promotion')->where('id', $promotion_id)->delete();

                $data1['location_id'] = Input::get('location_id');
                $data1['promotion_id'] = 0;
                $data1['status'] = 1;
                $data1['username'] = Auth::user()->name;
                $data1['approval_status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('employee_location')->where('id', $location_id)->update($data1);
            }

            if ($promotion_id == 0) {
                $data1['location_id'] = Input::get('location_id');
                $data1['status'] = 1;
                $data1['approval_status'] = 1;
                $data1['username'] = Auth::user()->name;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('employee_location')->where('id', $location_id)->update($data1);
            }
        }

        if($transfer_project_check == 1){
            if($transfer_project_id == '0'){
                $employee_data = Employee::where('emr_no','=',Input::get('emr_no'));
                $region_id = $employee_data->value('region_id');
                $employee_category_id = $employee_data->value('employee_category_id');
                $emr_no = Input::get('emr_no');
                $data2['emr_no'] = $emr_no;
                $data2['employee_project_id'] = Input::get('transfer_project_id');
                $data2['username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                $data2['emp_region_id'] = $region_id;
                $data2['emp_categoery_id'] = $employee_category_id;
                $transfer_id = DB::table('transfer_employee_project')->insertGetId($data2);
                $data5['active'] = 2;
                Employee::where('emr_no','=',$emr_no)->update($data5);
                $previous = DB::table('transfer_employee_project')->where([['emr_no','=',$emr_no],['id', '<', $transfer_id]])->max('id');
                if(count($previous) != '0'){
                    $data4['active'] = 2;
                    DB::table('transfer_employee_project')->where('id','=',$previous)->update($data4);
                }
                $data10['transfer_project_id'] = $transfer_id;
                DB::table('employee_location')->where('id', $location_id)->update($data10);
            }
            else{
                $transfer_project = Input::get('transfer_project_id');
                $emr_no = Input::get('emr_no');
                $m = Input::get('company_id');
                CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                $data2['emr_no'] = Input::get('emr_no');
                $data2['employee_project_id'] = $transfer_project;
                $data2['username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                DB::table('transfer_employee_project')->where([['id','=',$transfer_project_id],['active','=',1]])->update($data2);
            }
        }
        else{
            if($transfer_project_id != '0') {
                $transfer_project = Input::get('transfer_project_id');
                $emr_no = Input::get('emr_no');
                $m = Input::get('company_id');
                CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                $data2['emr_no'] = Input::get('emr_no');
                $data2['employee_project_id'] = $transfer_project;
                $data2['username'] = Auth::user()->name;
                $data2['date'] = date("Y-m-d");
                $data2['time'] = date("H:i:s");
                $data2['active'] = 2;
                DB::table('transfer_employee_project')->where([['id','=',$transfer_project_id],['active','=',1]])->delete();

                $data10['transfer_project_id'] = 0;
                DB::table('employee_location')->where('id', $location_id)->update($data10);

                $data12['active'] = 1;
                Employee::where('emr_no','=',Input::get('emr_no'))->update($data12);

            }
        }


        $check_letter_uploading = $_FILES['letter_uploading']['name'][0];
        if ($check_letter_uploading != '') {
            $letter_uploadings = $request->file('letter_uploading');
            $extention = [];
            foreach ($letter_uploadings as $key => $value) {
                $file_name = time().'_'.Input::get('emr_no').'_'.$key.'_'.$value->getClientOriginalExtension();
                $paths = 'app/' . $value->storeAs('uploads/transfer_letter', $file_name);
                $path = $_FILES['letter_uploading']['name'][$key];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $extention['file_type'] = $ext;
                $extention['emp_location_id'] = $location_id;
                $extention['letter_uploading'] = $paths;
                $extention['date'] = date("Y-m-d");
                $extention['time'] = date("H:i:s");
                DB::table('transfer_letter')->where('emp_location_id', '=', $location_id)->insert($extention);

            }
        }

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeTransferList?m=' . Input::get('company_id') . '#vis');

    }

    public function editEmployeeFuelDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $data['fuel_date'] = Input::get('fuel_date');
        $data['from'] = Input::get('from');
        $data['to'] = Input::get('to');
        $data['km'] = Input::get('km');
        $data['status'] = 1;
        $data['approval_status'] = 1;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        DB::table('employee_fuel_data')->where('id', Input::get('id'))->update($data);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeFuel?m='.Input::get('company_id').'#vis');
    }

    public function updateLabourSalary()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $data['emp_salary'] = Input::get('emp_salary');

        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        DB::table('employee')->where([['labour_law', '=', 1], ['status', '=', 1]])->update($data);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeList?m='.Input::get('company_id').'#vis');
    }

    public function editEmployeeAttendanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if(Input::get('attendance_type') == 1):


            $data1['attendance_from']   = Input::get('date_from');
            $data1['attendance_to']     = Input::get('date_to');
            $data1['present_days']      = Input::get('present_days');
            $data1['absent_days']       = Input::get('absent_days');
            $data1['overtime']          = Input::get('overtime');
            $data1['username']          = Auth::user()->name;
            $data1['date']              = date("Y-m-d");
            $data1['time']              = date("H:i:s");

            DB::table('attendance')->where('id', Input::get('recordId'))->update($data1);

        elseif(Input::get('attendance_type') == 2):


            $data1['attendance_date'] = Input::get('attendance_date');
            $data1['attendance_status'] = Input::get('attendance_status');
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('attendance')->where('id', Input::get('recordId'))->update($data1);

        endif;


        CommonHelper::reconnectMasterDatabase();


        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully edit.');
        return Redirect::to('hr/viewEmployeeAttendanceList?&&m='.Input::get('m').'#vis');


    }

    public function editEmployeeEquipmentDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        DB::table('employee_equipments')->where('emp_id', Input::get('emr_no'))->delete();
        foreach (Input::get('equipment_id') as $key => $val):

            if(strip_tags($val) == 11):
                $data['model_number'] = Input::get('model_number');
                $data['mobile_number'] = Input::get('mobile_number');
                $data['sim_number'] = Input::get('sim_number');
            endif;

            $data['equipment_id'] = strip_tags($val);
            $data['emp_id'] = Input::get('emr_no');
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['approval_status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");

            $last_insert_id = DB::table('employee_equipments')->insertGetId($data);

            if(strip_tags($val) == 9):

                if ($request->file('insurance_path')):
                    $file_name1 = Input::get('emr_no') . '_' . time() . '.' . $request->file('insurance_path')->getClientOriginalExtension();
                    $path1 = 'app/' . $request->file('insurance_path')->storeAs('uploads/employee_insurance_copy', $file_name1);
                    $data1['insurance_path'] = $path1;
                    $data1['insurance_type'] = $request->file('insurance_path')->getClientOriginalExtension();
                endif;

                $data1['insurance_number'] = Input::get('insurance_number');

                DB::table('employee')->where('emp_id', Input::get('emr_no'))->update($data1);
            endif;

            if(strip_tags($val) == 10):

                if ($request->file('eobi_path')):
                    $file_name1 = Input::get('emr_no') . '_' . time() . '.' . $request->file('eobi_path')->getClientOriginalExtension();
                    $path1 = 'app/' . $request->file('eobi_path')->storeAs('uploads/employee_eobi_copy', $file_name1);
                    $data2['eobi_path'] = $path1;
                    $data2['eobi_type'] = $request->file('eobi_path')->getClientOriginalExtension();
                endif;

                $data2['eobi_number'] = Input::get('eobi_number');

                DB::table('employee')->where('emp_id', Input::get('emr_no'))->update($data2);
            endif;

        endforeach;


        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeEquipmentsList?m='.Input::get('company_id').'#vis');
    }

    public function editEmployeeMedicalDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $emr_no = Input::get('emr_no');

        $counter = 0;
        if ($request->file('medical_file_path')) {
            foreach ($request->file('medical_file_path') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'EmrNo_' . $emr_no . '_employee_medical_file_' . time() .'_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_medical_documents', $file_name);

                    $fileUploadData['emr_no'] = $emr_no;
                    $fileUploadData['medical_file_name'] = $file_name;
                    $fileUploadData['medical_file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['medical_file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('employee_medical_documents')->insert($fileUploadData);
                }
            }
        }

        $data1['disease_type_id'] = Input::get('disease_type_id');
        $data1['disease_date'] = Input::get('disease_date');
        $data1['amount'] = Input::get('amount');
        $data1['cheque_number'] = Input::get('cheque_number');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_medical')->where('id', Input::get('id'))->update($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewEmployeeMedicalList?m='.Input::get('company_id').'#vis');
    }

    // public function editTrainingDetail(Request $request)
    // {

    //     CommonHelper::companyDatabaseConnection(Input::get('m'));


    //     if(Input::get('participant_type') == '1'):
    //         $participants = implode(Input::get('participants_name'),',');
    //     else:
    //         $participants = Input::get('participants_name');
    //     endif;

    //     $data1['region_id']          = Input::get('region_id');
    //     $data1['participant_type']   = Input::get('participant_type');
    //     $data1['employee_category_id'] = Input::get('emp_category_id');
    //     $data1['participants'] = $participants;
    //     $data1['location_id'] = Input::get('location_id');
    //     $data1['training_date'] = Input::get('training_date');
    //     $data1['topic_name'] = Input::get('topic_name');
    //     $data1['trainer_name'] = Input::get('trainer_name');
    //     $data1['certificate_number'] = Input::get('certificate_number');
    //     $data1['username'] = Auth::user()->name;
    //     $data1['status'] = 1;
    //     $data1['date'] = date("Y-m-d");
    //     $data1['time'] = date("H:i:s");

    //     DB::table('trainings')->where('id', Input::get('id'))->update($data1);

    //     $certificate_uploading = $request->file('certificate_uploading');
    //     $extention = [];
    //     foreach ($certificate_uploading as $key => $value){
    //         $file_name = Input::get('certificate_number'). time() . '.' . $value->getClientOriginalExtension();
    //         $paths = 'app/' . $value->storeAs('uploads/training_certificate', $file_name);
    //         $path = $_FILES['certificate_uploading']['name'][$key];
    //         $ext = pathinfo($path, PATHINFO_EXTENSION);
    //         $extention['file_type'] = $ext;
    //         $extention['certificate_uploading'] = $paths;
    //         $extention['training_id'] = Input::get('id');
    //         $extention['date'] = date("Y-m-d");
    //         $extention['time'] = date("H:i:s");

    //         DB::table('training_certificate')->where('training_id', Input::get('id'))->update($extention);
    //     }

    //     CommonHelper::reconnectMasterDatabase();
    //     Session::flash('dataInsert','successfully saved.');
    //     return Redirect::to('hr/viewTrainingList?m='.Input::get('m').'');


    // }

    public function editTrainingDetail(Request $request){
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $data1['training_date'] = Input::get('training_date');
        $data1['topic_name'] = Input::get('training_topic');
        $data1['training_hours'] = Input::get('training_hour');
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('trainings')->where('id', Input::get('id'))->update($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewTrainingList?m='.Input::get('m').'');
    }


    public function ediTransferProject(Request $request){
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $id = Input::get('transfer_id');

        $data1['emr_no'] = 		  Input::get('emr_no_id');
        $data1['emp_region_id'] = 		  Input::get('region_id');
        $data1['emp_categoery_id'] = 	  Input::get('emp_category_id');
        $data1['employee_project_id'] = 		  Input::get('transfer_project_id');
        $data1['status']=               1;
        $data1['approval_status']=      1;
        $data1['username']        = Auth::user()->name;
        $data1['date']=                 date("Y-m-d");
        $data1['time']=                 date("H:i:s");

        DB::table('transfer_employee_project')->where('id', $id)->update($data1);

        $check_letter_uploading = $_FILES['letter_uploading']['name'][0];
        if ($check_letter_uploading != '') {
            $letter_uploading = $request->file('letter_uploading');
            $extention = [];
            foreach ($letter_uploading as $key => $value) {
                $file_name = time().'_'.Input::get('emr_no').'_'.$key.'_'.$value->getClientOriginalExtension();
                $paths = 'app/' . $value->storeAs('uploads/promotions_letter', $file_name);
                $path = $_FILES['letter_uploading']['name'][$key];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $extention['file_type'] = $ext;
                $extention['emp_project_id'] = $id;
                $extention['letter_uploading'] = $paths;
                $extention['date'] = date("Y-m-d");
                $extention['time'] = date("H:i:s");
                DB::table('project_transfer_letter')->where('emp_project_id', '=', $id)->insert($extention);
            }
        }
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','successfully saved.');
        return Redirect::to('hr/viewProjectTransferList?m='.Input::get('company_id').'#vis');
    }


    public function editPfProfitDetail(Request $request)
    {
        $id=$request->input('id');
        $emp_id=$request->input('emp_id');
        $pf_profit_amount=$request->input('pf_profit_amount');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $data['emp_id']=$emp_id;
        $data['year_from'] = $request->input('year_from');
        $data['year_to'] = $request->input('year_to');
        $data['profit']=$pf_profit_amount;
        $data['status']=1;

        DB::table('pf_profit')->where('id',$id)->update($data);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert','Successfully Updated.');
        return Redirect::to('hr/viewPfProfitList?m='.Input::get('m').'#vis');


    }

    public function editPfWithrawalDetail(Request $request)
    {
        $id=$request->input('id');
        $employee_id=$request->input('employee_id');
        $pf_withdrawal_amount=$request->input('pf_withdrawal_amount');
        $pf_amount=0;

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $provident_fund=DB::select(DB::raw("SELECT sum(amount) as amount FROM pf where status=1 and employee_id='".$employee_id."'"));
        $pf_withdrawal=DB::select(DB::raw("SELECT sum(amount) as amount FROM pf_withdrawal where status=1 and employee_id='".$employee_id."'
        and id != '".$id."'"));
        $pf_amount=$provident_fund[0]->amount-$pf_withdrawal[0]->amount;



        if($pf_amount != '' || $pf_amount != 0){
            if($pf_amount >= $pf_withdrawal_amount){
                $month_data = (explode("-", $request->input('month_year')));

                $data['year'] = $month_data[0];
                $data['month'] = $month_data[1];
                $data['amount']=$request->input('pf_withdrawal_amount');
                $data['month_year']=$request->input('month_year');

                DB::table('pf_withdrawal')->where('id',$id)->update($data);

                CommonHelper::LogGenerator('pf_withdrawal',$id,'update','row is updated','hr',Auth::user()->id);

                CommonHelper::reconnectMasterDatabase();

                Session::flash('dataInsert','Successfully Updated.');
                return Redirect::to('hr/viewPfWithdrawalList?m='.Input::get('m').'#vis');
            }
            else{
                Session::flash('dataDelete', 'Provident Fund is less than Withdrawal amount !');
                return Redirect::to('hr/viewPfWithdrawalList?m=' . Input::get('m') . '#vis');

            }
        }
        else{
            Session::flash('dataDelete', 'Provident Fund is 0 ');
            return Redirect::to('hr/viewPfWithdrawalList?m=' . Input::get('m') . '#vis');
        }


    }

    public function editProvidentFundDetail()
    {

        foreach (Input::get('pf_name') as $key => $val):

            $data1['name'] = $val;
            $data1['pf_mode'] = Input::get('pf_mode')[$key];
            $data1['amount_percent'] = Input::get('amount_percent')[$key];
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('provident_fund')->where([['id','=',Input::get('recordId')]])->update($data1);

        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewProvidentFundList?m='. Input::get('company_id').'Innovative');

    }

    public function UpdateAssignWorkingHoursPolicyDetail(Request $request)
    {
        $employee_id = $request->employee_id;
        $working_hours_policy_array = [];
        $working_hours_policy = DB::table('working_hours_policy')->select('id','days_off')->where('status',1)->get();
        foreach($working_hours_policy as $val){
            $working_hours_policy_array[$val->id] = ['day_off'=>$val->days_off];
        }
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        foreach($employee_id as $key => $value){

            if($request->get('check_input')[$key]==1){
                $id = $request->get('id')[$key];
                $data['employee_id'] = $value;
                if($request->get('fixed_input')[$key] == 1){
                    $data['fixed'] = $request->get('fixed_input')[$key];
                    $data['from_date'] = '';
                    $data['to_date'] = '';
                }else{
                    $data['fixed'] = $request->get('fixed_input')[$key];
                    $data['from_date'] = $request->get('from_date')[$key];
                    $data['to_date'] = $request->get('to_date')[$key];
                }
                $data['day_off'] = $working_hours_policy_array[$request->get('working_hours_policy')[$key]]['day_off'];
                $data['working_hours_policy_id'] = $request->get('working_hours_policy')[$key];
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('assign_working_hour_policies')->where('employee_id',$value)->where('status',1)
                    ->where('id',$id)->update($data);
                CommonHelper::LogGenerator('assign_working_hour_policies',$id,'update','row is updated','hr',Auth::user()->id);
            }
        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('hr/viewAssignedWorkingHoursPolicyList?m='. Input::get('m'));
    }

    public function editWorkingHoursPolicyDetail()
    {
        $days_off_1 = Input::get('days_off_1');
        $days = '';
        foreach ($days_off_1 as $value) {
            $days .= $value . '=>';
        }


        $data['days_off'] = $days;
        $data['ot_cpl'] = strip_tags(Input::get('ot_cpl'));

        $data['working_hours_policy'] = strip_tags(Input::get('working_hours_policy'));
        $data['start_working_hours_time'] = strip_tags(Input::get('start_working_hours_time'));
        $data['end_working_hours_time'] = strip_tags(Input::get('end_working_hours_time'));
        $data['working_hours_grace_time'] = strip_tags(Input::get('working_hours_grace_time'));
        $data['half_day_time'] = strip_tags(Input::get('half_day_time'));
        $data['deduction_amount_late_day'] =strip_tags(Input::get('late_deduction'));
        $data['username'] = Auth::user()->name;
        $data['status'] = 1;
        $data['company_id'] = Input::get('m');
        $data['time'] = date("H:i:s");
        $data['date'] = date("Y-m-d");
        DB::table('working_hours_policy')->where('id',Input::get('w_id'))->update($data);

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        CommonHelper::LogGenerator('working_hours_policy',Input::get('w_id'),'Update','row is updated','hr',Auth::user()->id);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewWorkingHoursPolicyList?m=' . Input::get('m') . '#vis');
    }

}
