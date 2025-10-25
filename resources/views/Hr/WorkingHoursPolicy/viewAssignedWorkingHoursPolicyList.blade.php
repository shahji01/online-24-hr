<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/UpdateAssignWorkingHoursPolicyDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="{{ $m }}">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Assigned Working Hours Policy List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export', $operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">From Date:</label>
                                <input type='date' name="from" id="from" class="form-control" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">To Date:</label>
                                <input type='date' name="to" id="to" class="form-control" />
                            </div>
                            <div class="col-sm-3">
                                <div class="row">&nbsp;</div>
                                <input style="width: 15px;height: 15px;margin-top: 8px;" type="checkbox" id="fixed_policies" name="fixed_policies" value="fixed_policies">
                                <label style="margin-top:23px;" for="fixed_policies"> Fixed Policies </label> &nbsp;&nbsp;
                                <button type="button" style="margin-top:5px;" class="btn btn-sm btn-primary btn_search" onclick="filterAssignWorkingHoursPolicyList()"><i id="load" class="fas fa-search fa"> Search</i></button>
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
        function filterAssignWorkingHoursPolicyList(){
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var location_id = $('#location_id').val();
            var employee_id = $('#employee_id').val();
            var working_hours_policy_id = $('#working_hours_policy_id').val();
            var from_date = $('#from').val();
            var to_date = $('#to').val();
            var rights_url = 'hr/viewAssignedWorkingHoursPolicyList';
            var fixed = 0;
            if($('#fixed_policies').is(":checked")){
                fixed = 1;
            }else{
                fixed = 0;
            }

            var company_id = '{{ $m }}';
            jqueryValidationCustom();
            if(validate == 0){
                $('#formSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/filterAssignWorkingHoursPolicyList',
                    type: "GET",
                    data: {company_id:company_id,employee_id:employee_id,department_id:department_id,sub_department_id:sub_department_id,fixed:fixed,location_id:location_id,
                        project_id:project_id,category_id:category_id,working_hours_policy_id:working_hours_policy_id,from_date:from_date,to_date:to_date, rights_url:rights_url
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