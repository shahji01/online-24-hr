<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('css')
    <style>
        input[type="radio"], input[type="checkbox"]{ width:20px;
            height:20px;
        }

    </style>
@endsection
@section('content')

    <div class="page-wrapper">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" id="m" value="{{ $m }}">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h4 class="card-title">Rebate List</h4>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                {{ CommonHelper::displayExportButton('exportList','','1') }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-top: 40px">
                                <button class="btn btn-sm btn-primary" onclick="viewEmployeeRebateDetail()" type="button">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" id="printList">
                                    <table class="table table-bordered table-sm mb-0 table-hover table-striped" id="exportList">
                                        <thead>
                                        <th class="text-center">S No.</th>
                                        <th class="text-center">Emp ID</th>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Month - Year</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Nature</th>
                                        <th class="text-center">Actual Investment</th>
                                        <th class="text-center">Rebate Amount</th>
                                        <th class="text-center hidden-print">Documents</th>
                                        <th class="text-center hidden-print">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody id="tableData"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                viewEmployeeRebateDetail();
            }, 1000);
        });

        function viewEmployeeRebateDetail() {
            var employee_id  = $('#employee_id').val();
            var company_id = '{{ $m }}';
            jqueryValidationCustom();
            if(validate == 0){
                $('#tableData').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewEmployeeRebateDetail',
                    type: "GET",
                    data: {employee_id: employee_id,company_id: company_id},
                    success:function(data) {
                        $('#tableData').html(data);
                    },
                    error: function() {
                        swalError();
                        $('#tableData').html('');
                    }
                });
            }else{
                return false;
            }
        }

        function deleteEmployeeRebate(recordId,m,tableName){

            if(confirm("Do you want to delete this record ?") == true){
                $.ajax({
                    url: baseUrl+'/cdOne/deleteEmployeeRebate',
                    type: "GET",
                    data: {recordId:recordId,m:m,tableName:tableName},
                    success:function(data) {
                        viewEmployeeRebateDetail();
                        $.notify({
                            icon: "fa fa-times-circle",
                            message: "<b>Successfully Deleted</b>"
                        }, {
                            type: 'danger',
                            timer: 3000
                        });
                    },
                    error: function() {
                        swalError();
                    }
                });
            }else{
                return false;
            }
        }

        function deleteRebateDocument(recordId,m,tableName) {
            if(confirm("Do you want to delete this record ?") == true){
                var data = {recordId:recordId,m:m, tableName:tableName};
                var url= '{{ url('/')}}/cdOne/deleteRebateDocument';
                $.get(url,data, function(result){
                    $(".remove_row_"+recordId).fadeOut();
                });
            }else{
                return false;
            }
        }
    </script>
@endsection