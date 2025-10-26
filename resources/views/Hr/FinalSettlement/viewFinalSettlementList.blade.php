<?php
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
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
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Employee</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%;" name="employee_id" id="employee_id" class="form-control" onchange="viewFinalSettlementListDetail()">
                                    <option value="">Select Employee</option>
                                    @foreach($employee_search as $key => $val)
                                        <option value="{{ $val->id }}">{{ $val->emp_id.' -- '.$val->emp_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printList">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportList">
                                    <thead>
                                    <th class="text-center col-sm-1">S.No</th>
                                    <th class="text-center">Emp Code</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Last Working Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center col-sm-1 hidden-print">Action</th>
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
            $('#employee_id').select2();
            viewFinalSettlementListDetail();
        });

        function viewFinalSettlementListDetail() {
            var employee_id = $('#employee_id').val();
            var rights_url = 'hr/viewFinalSettlementList';
            var company_id = '{{ $m }}';
            $('#tableData').html('<div class="loading"></div>');
            $.ajax({
                url: '{{ url('/') }}/hdc/viewFinalSettlementListDetail',
                type: "GET",
                data: {company_id:company_id,employee_id:employee_id,rights_url:rights_url},
                success: function (data) {
                    $('#tableData').html(data);
                },
                error: function (error) {
                    $('#tableData').html('');
                    swalError();
                }
            });
        }
        function deleteEmployeeFinalSettlement(companyId,recordId,emr_no,tableName){
            if(confirm("Do you want to delete this record ?") == true){
                $.ajax({
                    url: baseUrl+'/cdOne/deleteEmployeeExitClearance',
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId,tableName:tableName, emp:emr_no},
                    success:function(data) {
                        viewFinalSettlementListDetail();
                    },
                    error: function (error) {
                        swalError();
                    }
                });
            }
        }
    </script>
@endsection