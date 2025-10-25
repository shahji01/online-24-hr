<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/AssignWorkingHoursPolicyDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="{{ $m }}">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Assign Working Hours Policy Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Working Hours Policy:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%;" class="form-control requiredField" name="working_hours_policy_id" id="working_hours_policy_id">
                                    <option value="">Select Policy</option>
                                    @foreach($workingHoursPolicy as $key => $y)
                                        <option value="{{ $y->id }}">{{ $y->working_hours_policy}}</option>
                                    @endforeach
                                </select>
                                <div id="emp_loader"></div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label class="sf-label">From Date:</label>
                                <input type='date' name="from" id="from" class="form-control" />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label class="sf-label">To Date:</label>
                                <input type='date' name="to" id="to" class="form-control" />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="filterAssignWorkingHoursPolicyForm()"><i id="load" class="fas fa-search fa"> Search</i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="formSection"></div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        $('#working_hours_policy_id').select2();
        
        function filterAssignWorkingHoursPolicyForm(){
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var working_hours_policy_id = $('#working_hours_policy_id').val();
            var from_date = $('#from').val();
            var to_date = $('#to').val();
            var location_id = $('#location_id').val();
            var company_id = '{{ $m }}';

            jqueryValidationCustom();
            if(validate == 0){
                $('#formSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/filterAssignWorkingHoursPolicyForm',
                    type: "GET",
                    data: {company_id:company_id,employee_id:employee_id,location_id:location_id,department_id:department_id,sub_department_id:sub_department_id,
                        project_id:project_id,category_id:category_id,working_hours_policy_id:working_hours_policy_id,from_date:from_date,to_date:to_date
                    },
                    success: function(data) {
                        $('#formSection').html(data);
                    },
                    error: function(error) {
                        $('#formSection').html('');
                        swalError();
                    }
                });
            }else{
                return false;
            }
        }
    </script>

@endsection