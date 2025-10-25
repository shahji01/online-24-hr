<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
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
                                <h4 class="card-title">Payroll Form</h4>
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
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="m" value="{{ $m }}">
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Employment Status</label>
                                <select style="width: 100%" class="form-control  employee_status" name="employment_status_id" id="employment_status_id" >
                                    <option value="">Select Employment Status</option>
                                    @foreach($job_type as $key3 => $value)
                                        <option value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Payroll Month</label>
                                <input type="month" name="payslip_month" id="payslip_month" class="form-control requiredField" value="{{Session::get('fromDate')}}">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <button style="margin-top: 40px;" type="button" class="btn btn-sm btn-primary btn_search" onclick="viewEmployeePayrollForm()"><i id="load" class="fas fa-search fa"> Search</i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="formSection"></div>
    </div>
@endsection

@section('script')
    <script>

        $('#employment_status_id').select2();

        function viewEmployeePayrollForm(){

            var payslip_month = $('#payslip_month').val();
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var job_type = $('#employment_status_id').val();
            jqueryValidationCustom();
            if(validate == 0){
                $('#formSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewEmployeePayrollForm',
                    type: "GET",
                    data: {employee_id:employee_id,location_id:location_id,project_id:project_id,payslip_month:payslip_month,category_id:category_id,
                        m:m,sub_department_id:sub_department_id,department_id:department_id,job_type:job_type},
                    success:function(data) {
                        $('#formSection').html(data);
                    },
                    error: function() {
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