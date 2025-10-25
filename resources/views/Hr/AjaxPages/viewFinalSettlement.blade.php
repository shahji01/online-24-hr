<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];
$currentDate = date('Y-m-d');

$designation_name = HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $designation_id, 'id');

$emp_salary = $salary;
$emp_joining_date = $employee->emp_joining_date;
$last_working_date = $exit_data->last_working_date;

$leaving_type = "";
if($exit_data->leaving_type == 1):
    $leaving_type = "Resignation";
elseif($exit_data->leaving_type == 2):
    $leaving_type = "Retirement";
elseif($exit_data->leaving_type == 3):
    $leaving_type = "Termination";
elseif($exit_data->leaving_type == 4):
    $leaving_type = "Dismissal";
elseif($exit_data->leaving_type == 4):
    $leaving_type = "Demise";

endif;

if ($count > 0):
    $salary_from = $final_settlement_data->salary_from;
    $salary_to = $final_settlement_data->salary_to;
    $gratuity = $gratuityAmount;
    $others = $final_settlement_data->others;
    $notice_pay = $final_settlement_data->notice_pay;
    $advance = $final_settlement_data->advance;
    $mobile_bill = $final_settlement_data->mobile_bill;
    $toolkit = $final_settlement_data->toolkit;
    $mfm_id_card = $final_settlement_data->mfm_id_card;
    $uniform = $final_settlement_data->uniform;
    $laptop = $final_settlement_data->laptop;
    $any_others = $final_settlement_data->any_others;

else:
    $salary_from = '';
    $salary_to = $last_working_date;
    $gratuity = $gratuityAmount;
    $others = '';
    $notice_pay = '';
    $advance = $loan_amount;
    $mobile_bill = '';
    $toolkit = '';
    $mfm_id_card = '';
    $uniform = '';
    $laptop = '';
    $any_others = '';
endif;

?>

<div class="row">&nbsp;</div>
<div class="row" style="background-color: gainsboro">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h4 style="text-decoration: underline;font-weight: bold;">Final Settlement Form</h4>
    </div>
</div>
<div class="row">&nbsp;</div>

<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Designation:</label>
            <input readonly name="designation" id="designation" type="text" value="{{ $designation_name }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Date Of Joining:</label>
            <input readonly name="emp_joining_date" id="emp_joining_date" type="date" value="{{ $emp_joining_date }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Last Working Date:</label>
            <input readonly name="last_working_date" id="last_working_date" type="date" value="{{ $last_working_date }}" class="form-control requiredField"/>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Reason Of Release:</label>
            <input readonly name="leaving_type" id="leaving_type" type="text" value="{{ $leaving_type }}" class="form-control requiredField"/>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
            <label>Last Salary Rate:</label>
            <input readonly name="emp_salary" id="emp_salary" type="text" value="{{ $emp_salary }}" class="form-control requiredField"/>
        </div>
    </div>
    
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4>Additions (+)</h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Salary From</label>
            <input type="date" name="salary_from" id="salary_from" value="{{ $salary_from }}" class="form-control requiredField">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Salary To</label>
            <input type="date" name="salary_to" id="salary_to" value="{{ $salary_to }}" class="form-control requiredField">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Gratuity</label>
            <input readonly type="number" name="gratuity" id="gratuity" value="{{ $gratuity }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Others</label>
            <input type="number" name="others" id="others" value="{{ $others }}" class="form-control">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4>Deductions (-)</h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Notice Pay</label>
            <input type="number" name="notice_pay" id="notice_pay" value="{{ $notice_pay }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Advance</label>
            <input type="number" name="advance" id="advance" value="{{ $advance }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Mobile Bill</label>
            <input type="number" name="mobile_bill" id="mobile_bill" value="{{ $mobile_bill }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Tool Kit</label>
            <input type="number" name="toolkit" id="toolkit" value="{{ $toolkit }}" class="form-control">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>MFM ID Card</label>
            <input type="number" name="mfm_id_card" id="mfm_id_card" value="{{ $mfm_id_card }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Uniform</label>
            <input type="number" name="uniform" id="uniform" value="{{ $uniform }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Laptop</label>
            <input type="number" name="laptop" id="laptop" value="{{ $laptop }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Any Others</label>
            <input type="number" name="any_others" id="any_others" value="{{ $any_others }}" class="form-control">
        </div>
    </div>

</div>

<br>
<div style="float: right;">
    <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Submit</button>
</div>

<script>

    $(document).ready(function () {

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='employeeSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of employee) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else if(validate == 1){
                    return false;
                }
            }

        });
    });

</script>

