<?php
$m = Input::get('m');
use App\Helpers\HrHelper;
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
                                <h4 class="card-title">Exit Clearance List</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="searchId" name="searchId" class="form-control" placeholder="Search..." />
                                    <input type="hidden" name="company_id" id="company_id" value="{{ $m }}">
                                </div>
                            </div>
                            <div class="col-sm-8 text-right">
                                @if(in_array('print',$operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export',$operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printList">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportList">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Department</th>
                                    <th class="text-center">Designation</th>
                                    <th class="text-center">Last Working Date</th>
                                    <th class="text-center">Approval Status</th>
                                    <th class="text-center">Action</th>
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
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                viewEmployeeExitClearanceListDetail();
            }, 1000);
        });

        function viewEmployeeExitClearanceListDetail() {
            var company_id = '{{ $m }}';
            var rights_url = 'hr/viewEmployeeExitClearanceList';
            $('#tableData').html('<div class="loader"></div>');
            $.ajax({
                url: baseUrl + '/hdc/viewEmployeeExitClearanceListDetail',
                type: "GET",
                data: {
                    company_id: company_id,
                    rights_url: rights_url
                },
                success: function (data) {
                    $('#tableData').html(data);
                },
                error: function () {
                    swalError();
                    $('#tableData').html('');
                }
            });
        }

        function deleteEmployeeExitClearance(companyId,recordId,employee_id,tableName){

            if(confirm("Do you want to delete this record ?") == true){
                $.ajax({
                    url: baseUrl+'/cdOne/deleteEmployeeExitClearance',
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,tableName:tableName, emp:employee_id},
                    success:function(data) {
                        location.reload();
                    },
                    error: function () {
                        swalError();
                    }
                });
            }
            else{
                return false;
            }
        }

        function approveAndRejectEmployeeExit(companyId, recordId, approval_status, tableName, employee_emr_no, employee_status){

            $.ajax({
                url : ''+baseUrl+'/cdOne/approveAndRejectEmployeeExit',
                type: "GET",
                data: {'emr_no':employee_emr_no,companyId: companyId, recordId: recordId, tableName: tableName, approval_status: approval_status, employee_emr_no: employee_emr_no, employee_status: employee_status},
                success: function (data) {
                    if(data == 'error') {
                        swalAlert('Error','Something went wrong');
                    }
                    else{
                        location.reload();
                    }
                },
                error: function () {
                    swalError();
                }
            });
        }

    </script>
@endsection