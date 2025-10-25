<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

if($advance_salary->deduction_month[0] != '0' && $advance_salary->deduction_month[0] != '1' ):
    $date = "0".$advance_salary->deduction_month;
else:
    $date = $advance_salary->deduction_month;
endif;
?>

<div class="well">
    <?php echo Form::open(array('url' => 'had/editAdvanceSalaryDetail','id'=>'employeeForm'));?>
    <div class="row">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?=$m?>">
        <input type="hidden" name="id" value="{{ $advance_salary->id }}">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Amount Needed</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="number" class="form-control requiredField" name="advance_salary_amount" id="advance_salary_amount" value="{{ $advance_salary->advance_salary_amount }}" />
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <label class="sf-label">Advance Salary to be Needed On</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" class="form-control requiredField" name="salary_needed_date" id="salary_needed_date" value="{{ $advance_salary->salary_needed_on }}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Deduction Month & Year</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="month" class="form-control requiredField" name="deduction_month_year" id="deduction_month_year" value="{{ $advance_salary->deduction_year."-".$date }}" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="sf-label">Reason (Detail)</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <textarea name="advance_salary_detail" class="form-control requiredField">{{ $advance_salary->detail }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <br>
        <div class="employeeSection"></div>

    </div>
        <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
        </div>
    </div>
    <?php echo Form::close();?>
</div>
