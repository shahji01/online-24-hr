<?php
use App\Helpers\CommonHelper;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="m" value="{{ $m }}">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">{{$breadCrums['heading']}}</h4>
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
                                <label class="sf-label">Employment Status</label>
                                <select style="width: 100%" class="form-control  employee_status" name="employment_status_id" id="employment_status_id" >
                                    <option value="">Select Employment Status</option>
                                    @foreach($job_type as $key3 => $value)
                                        <option value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Month - Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="payslip_month" id="payslip_month" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <button style="margin-top: 40px;" type="button" class="btn btn-sm btn-primary btn_search" id="viewPayrollReport" onclick="viewPayrollReport()"><i id="load" class="fas fa-search fa"> </i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="addMoreSection"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#employment_status_id').select2();
        function viewPayrollReport(){
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
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewPayrollReport',
                    type: "GET",
                    data: {m:m,employee_id:employee_id,project_id:project_id,location_id:location_id,category_id:category_id,payslip_month:payslip_month,
                        department_id:department_id,sub_department_id:sub_department_id,job_type:job_type},
                    success:function(data) {
                        $('#addMoreSection').html(data);
                    },
                    error: function () {
                        $('#addMoreSection').html('');
                        swalError();
                    }
                });
            }
            else{
                return false;
            }
        }

    </script>
@endsection
