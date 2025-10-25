<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$m = $_GET['m'];

?>

<style>

    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    {{ Form::open(array('url' => 'had/editEmployeePromotionDetail')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                    <input type="hidden" name="formSection[]" id="formSection" value="1" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                    <input type="hidden" name="id" id="id" value="{{ Input::get('id') }}" />
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">EMP ID:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input readonly name="emp_id" id="emp_id" type="text" value="@if(array_key_exists($employee_promotion->value('employee_id'), $employee)) {{ $employee[$employee_promotion->value('employee_id')]->emp_id }} @endif" class="form-control" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Employee Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input readonly name="emp_name" id="emp_name" type="text" value="@if(array_key_exists($employee_promotion->value('employee_id'), $employee)) {{ $employee[$employee_promotion->value('employee_id')]->emp_name }} @endif" class="form-control" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Designation:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" id="designation_id" name="designation_id" onchange="getGradeByDesignationSingle()">
                                <option value="">Select Designation</option>
                                @foreach($designations as $key => $val)
                                    <option @if($val->id == $employee_promotion->value('designation_id')) selected @endif value="{{ $val->id}}"  data-value="{{ $val->grade_id }}">{{ $val->designation_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Grade</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" name="grade_id" id="grade_id">
                                <option value="">Select Grade</option>
                                @foreach($grade_type as $key => $val)
                                    <option @if($val->id == $employee_promotion->value('grade_id')) selected @endif value="{{ $val->id }}">{{ $val->employee_grade_type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Promotion / Increment Date:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" name="promotion_date" id="promotion_date" value="{{ $employee_promotion->value('promotion_date') }}" class="form-control requiredField" />
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <label class="sf-label">Edit Salary:</label><br>
                            <input type="checkbox" id="edit_salary" onclick="salaryDiv()" name="edit_salary" value="1">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 div_salary" style="display: none;">
                            <label class="sf-label">Increment</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="increment" id="increment" value="{{ $employee_promotion->value('increment') }}" class="form-control requiredField" onkeyup="changeSalary()" required/>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 div_salary" style="display: none;">
                            <label>Salary <span class="rflabelsteric"><strong>*</strong></span></label>
                            <input readonly name="salary" id="salary" type="number" value="{{ $employee_promotion->value('salary') }}" class="form-control requiredField">
                            <input readonly name="old_salary" id="old_salary" type="hidden" value="{{ $employee_promotion->value('salary') - $employee_promotion->value('increment') }}" />
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val in employee) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }
        });

        $('#designation_id').select2();
        $('#grade_id').select2();

    });

    function getGradeByDesignationSingle()
    {
        var grade_id = 0;
        grade_id = $("#designation_id option:selected").attr("data-value");
        $('#grade_id').val(parseInt(grade_id)).change();
    }

    function changeSalary() {
        var previousSalary = parseFloat($('#old_salary').val());
        var increment = parseFloat($('#increment').val());
        $('#salary').val(previousSalary + increment);

        if ($('#increment').val() == '')
            $('#salary').val(previousSalary);
    }

    function salaryDiv(){
        if ($('#edit_salary').is(':checked')) {
            $(".div_salary").css({"display": "block"});

        } else {
            $(".div_salary").css({"display": "none"});
        }
    }

</script>