<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use \Cache;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\Employee;
use App\Models\TransferEmployeeProject;
use App\Models\LoanRequest;

class SelectListLoadAjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSubDepartments()
    {
        $type = Input::get('type');
        $department_id = Input::get('department_id');
        $sub_department = Cache::get('sub_department');

        $type = Input::get('type');
        $department_id = Input::get('department_id');
        $sub_department = Cache::get('sub_department');

        if (!empty($sub_department) && !empty($department_id)):
            echo "<option value=''>Select Sub Department</option>";
            foreach ($sub_department as $row) {
                if($type!='multiple'){
                    if($row->department_id == $department_id) { ?>
                        <option value="<?php echo $row->id ?>"><?php echo $row->sub_department_name ?></option>
                        <?php
                    }
                }else{
                    if(in_array($row->department_id,$department_id)) { ?>
                        <option value="<?php echo $row->id ?>"><?php echo $row->sub_department_name ?></option>
                        <?php
                    }
                }

            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;

    }

    public function locationsList()
    {
        $project_id = Input::get('project_id');
        $locations = Cache::get('locations');
        if (!empty($locations)):
            echo '<option value="">Select Locations</option>';
            foreach ($locations as $row) {
                if($row->project_id == $project_id) {
                    echo "<option value='$row->id'>$row->location_code -- $row->location_name</option>";
                }
            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;
    }

    public function locationsListUsingId()
    {
        $project_id = Input::get('project_id');
        $locations = Cache::get('locations');
        if (!empty($locations)):
            echo '<option disabled value="">Select Locations</option><option value="0">All</option>';
            foreach ($locations as $row) {
                if($row->project_id == $project_id) { ?>
                    <option value="<?php echo $row->id ?>"><?php echo $row->location_code . ' -- ' . $row->location_name ?></option>
                    <?php
                }
            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;
    }

    public function getEmployeeMultiple()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $m = Input::get('m');

        CommonHelper::companyDatabaseConnection($m);

        $query_string=DB::table('employee')->select('id', 'emp_id', 'emp_name');
        if(!empty($department_id)){
            $query_string->whereIn('department_id',$department_id);
        }
        if(!empty($sub_department_id)){
            $query_string->whereIn('sub_department_id',$sub_department_id);
        }
        if(!empty($category_id)) {
            $query_string->whereIn('category_id',$category_id);
        }
        if(!empty($project_id)){
            $query_string->whereIn('project_id',$project_id);
        }
        $query_string->where('status',1)->orderBy('id','desc');



        CommonHelper::reconnectMasterDatabase();

        if($query_string->count() > 0){
            ?>
            <option value="all"> All</option>
            <?php
            foreach($query_string->get() as $value){
                ?>
                <option value="<?php echo $value->id ?>"> <?php echo $value->emp_id . ' -- ' . $value->emp_name; ?></option>
                <?php
            }
        }
        else{
            ?><option>No data found</option><?php
        }

    }

    public function locationsListWithAllOption()
    {
        $project_id = Input::get('project_id');
        $locations = Cache::get('locations');
        if (!empty($locations)):
            echo '<option value="">Select Locations</option><option value="0">All</option>';
            foreach ($locations as $row) {
                if($row->project_id == $project_id) {
                    echo "<option value='$row->id'>$row->location_code -- $row->location_name</option>";
                }
            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;
    }

    public function getGradeByDesignation()
    {
        $grade_id = Input::get('grade_id');
        $grade = Cache::get('grades');
        $flag = false ;
        if (!empty($grade)):
            foreach ($grade as $key =>  $row) {

                if($row->id == $grade_id) {
                    echo "<option value='$row->id'>$row->employee_grade_type</option>";
                    $flag = true;
                    break;
                }
            }

            if(!$flag)
            {
                echo "<option value=''>Please put grade against designation </option>";
            }
        endif;
    }


    public function getEmployee()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $location_id = Input::get('location_id');
        $flagEmp = Input::get('flagEmp');
        $m = Input::get('m');
        $acc_type = Auth::user()->acc_type;

        $user_roles = CommonHelper::userRoles($m);

        $employee_id = Auth::user()->employee_id;

        CommonHelper::companyDatabaseConnection($m);

        if(!empty($department_id)) $query_string_second_part[] = " AND e.department_id = '$department_id'";
        if(!empty($sub_department_id)) $query_string_second_part[] = " AND e.sub_department_id = '$sub_department_id'";
        if(!empty($category_id)) $query_string_second_part[] = " AND e.category_id = '$category_id'";
        if(!empty($project_id))  $query_string_second_part[] = " AND eml.project_id = '$project_id'";

        if($acc_type == 'client' && !empty($location_id))
            $query_string_second_part[] = " AND eml.location_id = '$location_id' ";

        if($acc_type == 'admin' && empty($location_id))
            $query_string_second_part[] = " AND eml.location_id IN (".implode(',',$user_roles['location_ids']).") ";

        if($acc_type == 'admin' && !empty($location_id))
            $query_string_second_part[] = " AND eml.location_id = '$location_id' ";

        if($acc_type == 'user')
            $query_string_second_part[] = " AND e.id = '$employee_id'";

        $query_string_second_part[] = " AND e.status = '1'";
        $query_string_second_part[] = " AND eml.status = '1'";
        $query_string_first_Part= "SELECT 
        
        
        e.id,e.emp_id, eml.location_id,e.emp_name FROM employee AS e
          INNER JOIN employee_multiple_locations AS eml ON e.id = eml.employee_id WHERE ";
        $query_string_third_part = 'GROUP BY e.id ORDER BY e.id';
        $query_string_second_part = implode(" ", $query_string_second_part);
        $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
        
        
        $employee = DB::select(DB::raw($query_string));

        CommonHelper::reconnectMasterDatabase();

        if(count($employee) > 0){
            if($flagEmp != 'single' && $acc_type != 'user'){
                ?>
                <option value="all">All</option>
                <?php
            }
            else{
                ?>
                <option value="">Select employee</option>

                <?php
            }
            foreach($employee as $value){
                ?>
                <option value="<?php echo $value->id ?>"><?php echo $value->emp_id . ' -- ' . $value->emp_name; ?></option>
                <?php
            }
        }
        else{
            ?><option value="emptyvalue">No data found</option><?php
        }

    }

    public function getEmployeeWithoutAll()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $category_id = Input::get('category_id');
        $project_id = Input::get('project_id');
        $m = Input::get('m');

        CommonHelper::companyDatabaseConnection($m);

        if(!empty($project_id)):
            if(!empty($department_id)) $query_string_second_part[] = " AND e.department_id = '$department_id'";
            if(!empty($sub_department_id)) $query_string_second_part[] = " AND e.sub_department_id = '$sub_department_id'";
            if(!empty($category_id)) $query_string_second_part[] = " AND e.category_id = '$category_id'";
            $query_string_second_part[] = " AND eml.project_id = '$project_id'";
            $query_string_second_part[] = " AND e.status = 1";
            $query_string_second_part[] = " AND eml.status = 1";
            $query_string_first_Part = "SELECT e.id,e.emp_id,e.emp_name FROM employee_multiple_locations AS eml JOIN
              employee AS e ON e.id = eml.employee_id WHERE";
            $query_string_third_part = ' ORDER BY e.id';
            $query_string_second_part = implode(" ", $query_string_second_part);
            $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
            $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
            $employees = DB::select(DB::raw($query_string));
        else:
            if(!empty($department_id)) $query_string_second_part[] = " AND department_id = '$department_id'";
            if(!empty($sub_department_id)) $query_string_second_part[] = " AND sub_department_id = '$sub_department_id'";
            if(!empty($category_id)) $query_string_second_part[] = " AND category_id = '$category_id'";
            $query_string_first_Part = "SELECT id, emp_id, emp_name FROM employee WHERE";
            $query_string_third_part = ' ORDER BY id';
            $query_string_second_part = implode(" ", $query_string_second_part);
            $query_string_second_part =  preg_replace("/AND/", " ", $query_string_second_part, 1);
            $query_string = $query_string_first_Part.$query_string_second_part.$query_string_third_part;
            $employees = DB::select(DB::raw($query_string));
        endif;
        CommonHelper::reconnectMasterDatabase();

        if(!empty($employees)):
            echo "<option value=''>Select Employee</option>";
            foreach($employees as $value):
                echo "<option value='$value->id'>$value->emp_id -- $value->emp_name</option>";
            endforeach;
        else:
            echo "<option value=''>No data found</option>";
        endif;
    }

    public function viewEmployeeLoansList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loansList = LoanRequest::where('emp_id',Input::get('emp_id'));

        echo "<option value=''>Select</option>";
        if($loansList->count() > 0){
            foreach($loansList->get() as $value){?>
                <option value="<?php echo $value->id ?>"><?php echo 'Amount: ' . $value->loan_amount ?></option>
                <?php
            }
        }
        else{
            ?><option value="emptyvalue">No Record fOUND</option><?php
        }
        CommonHelper::reconnectMasterDatabase();
    }
}
