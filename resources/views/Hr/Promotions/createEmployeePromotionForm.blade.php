<?php
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                        </div>
                        {{ Form::open(array('url' => 'had/addEmployeePromotionDetail')) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    @include('includes.singleFilters')
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Designation:</label>
                                        <select style="width:100%;" class="form-control" id="designation_id" name="designation_id" onchange="getGradeByDesignation()">
                                            <option value="">Select Designation</option>
                                            @foreach($designations as $key => $val)
                                                <option value="{{ $val->id}}"  data-value="{{ $val->grade_id }}">{{ $val->designation_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Grade:</label>
                                        <select style="width: 100%" class="form-control" name="grade_id" id="grade_id">
                                            <option value="">Select Option</option>
                                            @foreach($grade_type as $key => $val)
                                                <option value="{{ $val->id }}">{{ $val->employee_grade_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Increment:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="increment" id="increment" onkeyup="changeSalary()" class="form-control requiredField" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Salary:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="salary" id="salary" value="" class="form-control requiredField" readonly />
                                        <input type="hidden"  id="old_salary" value="" class="form-control requiredField"  />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Promotion / Increment Date:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" name="promotion_date" id="promotion_date" class="form-control requiredField" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div id="addMoreSection"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>

        $(document).ready(function() {
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

        var previousSalary;
        $('#employee_id').on('change', function() {
            $('#addMoreSection').html('<div class="loader"></div>');
            var employee_id = $(this).val();
            var company_id = '{{ $m }}';
            if(employee_id) {
                $.ajax({
                    url: baseUrl+'/hdc/viewEmployeePreviousPromotionsDetail',
                    type: "GET",
                    data: { employee_id:employee_id,company_id:company_id},
                    success:function(data) {
                        $("#addMoreSection").html(data);
                        previousSalary = parseInt($('#previousSalary').val());
                        $('#salary').val(previousSalary);
                        $('#old_salary').val(previousSalary);
                        $('#designation_id').val(parseInt($('#previous_designation_id').val())).change();
                    }
                });
            } else {
                $("#addMoreSection").html('');
            }
        });
        
        function changeSalary() {
            
            const previousSalary = parseFloat($('#old_salary').val());
            var increment = parseFloat($('#increment').val());
            $('#salary').val(previousSalary + increment);
    
            if ($('#increment').val() == '')
                $('#salary').val(previousSalary);
        }
    </script>
@endsection