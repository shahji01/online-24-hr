<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];




use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\EmployeePromotion;

 CommonHelper::companyDatabaseConnection(Input::get('m'));
 $get_user_data = EmployeePromotion::where([['emp_id','=',$employee->emp_id],['status','=',1]])->get();
        if(count($get_user_data) != '0'){
            
            $employee_promotion = DB::table('employee_promotion')
                ->where([['emp_id','=',$employee->emp_id],['status','=',1]])
                ->orderBy('id','desc')
                ->first();
            $salary = $employee_promotion->salary;
            $date = $employee_promotion->promotion_date;

        }
        else{
            $employee = Employee::where([['emp_id','=',$employee->emp_id],['status','=',1]])->first();
            $salary = $employee->emp_salary;
            $date = $employee->date;
        }
 CommonHelper::reconnectMasterDatabase();

?>
<?php echo Form::open(array('url' => 'had/addEmployeePromotionDetail','id'=>'employeePromotionForm',"enctype"=>"multipart/form-data"));?>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="company_id" id="company_id" value="<?=$m?>">
<input type="hidden" name="department_id" value="{{ $employee->emp_department_id }}">
<input type="hidden" name="sub_department_id" value="{{ $employee->emp_sub_department_id }}">
<input type="hidden" name="emp_id" value="{{ $employee->emp_id }}">
<input type="hidden" name="designation_1" value="{{ $employee->designation_id }}">
<input type="hidden" name="grade_type" value="{{ $employee->emp_grade_id }}">
<input type="hidden" name="type" value="permanent">




<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label class="sf-label">Increment :</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="text" name="increment" id="increment" onkeyup="changeSalary()" value="" class="form-control" required/>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label class="sf-label">Salary :</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="text" name="salary" id="salary" value="{{ $salary }}" class="form-control requiredField" required readonly/>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label class="sf-label">Date :</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="date" name="promotion_date" id="promotion_date" value="{{ $date }}" class="form-control requiredField" required/>
    </div>
    <input type="hidden" value="1" name="permanent_form" />   

    {{--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">--}}
    {{--<label class="sf-label">Add Allowances :</label><br>--}}
    {{--<input type="checkbox" name="addAllowancesCheck" id="addAllowancesCheck" value="1"/>--}}
    {{--</div>--}}
</div>
<br>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
    </div>
</div>

<script>
    var previousSalary=$('#salary').val();
    function changeSalary(){
        $('#salary').val(previousSalary);
        var salary = parseFloat($('#salary').val());
        var increment = parseFloat($('#increment').val());
        $('#salary').val(salary + increment);

        if ($('#increment').val() == '')
            $('#salary').val(previousSalary);

    }
</script>