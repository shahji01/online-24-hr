<?php
use App\Helpers\CommonHelper;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('css')
    <style>
        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }
    </style>
@endsection
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addEmailPayslipDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="{{ $m }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Email Payslip</h4>
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
                                <label>Month - Year</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="month_year" id="month_year" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewEmployeePayslips()" style="margin-top: 40px;" ><i id="load" class="fas fa fa-search"> </i> Search</button>
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

            $(".btn-primary").click(function(e){
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
                    }
                    else{
                        return false;
                    }
                }
            });
        });

        function viewEmployeePayslips() {
            $("#employeeAttendenceReportSection").css({"display": "none"});
            var month_year = $('#month_year').val();
            var employee_id = $('#employee_id').val();
            var company_id = '{{ $m }}';
            jqueryValidationCustom();
            if (validate == 0) {
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: '{{ url('/') }}/hdc/viewEmployeePayslips',
                    type: "GET",
                    data: {month_year: month_year, employee_id:employee_id, company_id: company_id},
                    success: function (data) {
                        $('#addMoreSection').html(data);
                    },
                    error: function () {
                        $('#addMoreSection').html('');
                        swalError();
                    }
                });
            } else {
                return false;
            }
        }

    </script>

@endsection