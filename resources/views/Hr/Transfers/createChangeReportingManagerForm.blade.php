<?php
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addChangeReportingManagerDetail')) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Change Reporting Manager Form</h4>
                            </div>
                        </div>
                        <hr>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label pointer">Departments:</label>
                                        <select style="width: 100%;" class="form-control" name="department_id" id="department_id" onchange="getEmployee('sub_department')">
                                            <option value="">Select Option</option>
                                            @foreach($departments as $key => $y)
                                                <option value="{{ $y->id }}">{{ $y->department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label pointer">Sub Department:</label>
                                        <select style="width: 100%;" class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee('')">
                                            <option value="">Select Option</option>
                                            @foreach($sub_departments as $key => $y)
                                                <option value="{{ $y->id }}">{{ $y->sub_department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label pointer">Category:</label>
                                        <select style="width: 100%;" class="form-control" name="category_id" id="category_id" onchange="getEmployee('')">
                                            <option value="">Select Option</option>
                                            @foreach($category as $key => $y)
                                                <option value="{{ $y->id }}">{{ $y->employee_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label pointer">Location:</label>
                                        <select class="form-control" name="location_id" id="location_id" onchange="getEmployee('')">
                                            <option value="">Select Option</option>
                                            @foreach($locations as $key => $y)
                                                <option value="{{ $y->id }}">{{ $y->location_code.' -- '.$y->location_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Employee:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select style="width: 100%;" class="form-control requiredField" name="employee_id" id="employee_id">
                                            @foreach($employees as $key => $y)
                                                <option value="{{ $y->id }}">{{ $y->emp_id.' -- '.$y->emp_name}}</option>
                                            @endforeach
                                        </select>
                                        <div id="emp_loader"></div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 40px">
                                        <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewChangeReportingManagerFormDetail()"><i id="load" class="fas fa-search fa"> Search</i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="addMoreSection"></div>
        <div class="row">&nbsp;</div>
        {{ Form::close() }}
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var department = new Array();
                var val;
                $("input[name='formSection[]']").each(function(){
                    department.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val in department) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }
            });
            let location_id = new vanillaSelectBox("#location_id",{
                search: true,
                placeHolder: "Select Location"
            });
            $('#btn-group-location_id ul').find('li').get(0).remove();
        });

        function viewChangeReportingManagerFormDetail() {

            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var location_id = $('#location_id').val();
            var company_id = '{{ $m }}';
            var rights_url = 'hr/createChangeReportingManagerForm';
            jqueryValidationCustom();
            if (validate == 0) {
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: '{{ url('/') }}/hdc/viewChangeReportingManagerFormDetail',
                    type: "GET",
                    data: {
                        company_id: company_id,
                        employee_id: employee_id,
                        department_id: department_id,
                        sub_department_id: sub_department_id,
                        project_id: project_id,
                        category_id: category_id,
                        location_id: location_id,
                        rights_url: rights_url
                    },
                    success: function (data) {
                        //       $('.location_id').css({'display':'block'});
                        $('#addMoreSection').html(data);
                        //      $('.location_id').next('.vsb-main').remove();
                    },
                    error: function () {
                        $('#addMoreSection').html('');
                        swalError();
                    }
                });
            }
        }

    </script>
@endsection