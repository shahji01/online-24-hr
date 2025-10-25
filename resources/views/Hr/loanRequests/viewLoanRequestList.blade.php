<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('css')
    <style>
        input[type="radio"],
        input[type="checkbox"] {
            width: 30px;
            height: 20px;
        }
    </style>
@endsection
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title">Loan Request List</h4>
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
                        <hr>
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Status</label>
                                <select name="status_search" id="status_search" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="0" selected>Not Paid</option>
                                    <option value="1">Paid</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewFilteredLoanRequestList()"><i id="load" class="fas fa-search fa"> Search</i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper" id="printList">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">EMP ID</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Needed On</th>
                                    <th class="text-center">Deduction Start Date</th>
                                    <th class="text-center">Monthly Deduction</th>
                                    <th class="text-center">Loan Amount</th>
                                    <th class="text-center">Top Up Amount</th>
                                    <th class="text-center">Total Loan</th>
                                    <!--<th class="text-center">Expected Completion Date</th>-->
                                    <th class="text-center">Remaining Amount</th>
                                    <th class="text-center">Stop Payment</th>
                                    <th class="text-center">Remarks</th>
                                    <th class="text-center">Approval status</th>
                                    <th class="text-center">Status</th>
                                    @if (Auth::user()->acc_type == 'client' || Auth::user()->acc_type == 'admin')
                                        <th class="text-center">
                                            Approval Check <br>
                                            <input id="checkbox" type="checkbox" name="checkbox" value="">
                                        </th>
                                    @endif
                                    <th class="text-center hidden-print" id="hide-table-row">Action</th>
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
                viewFilteredLoanRequestList();
            }, 1000);

            $('#employee_id').select2();
            $('#status_search').select2();

            $("#checkbox").click(function() {
                if ($("#checkbox").prop("checked") == true) {
                    $(".check_input").prop("checked", true);
                } else {
                    $(".check_input").prop("checked", false);
                }
            });
        });

        function viewFilteredLoanRequestList() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var category_id = $('#category_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var employee_id = $('#employee_id').val();
            var company_id = '{{ $m }}';
            var status = $('#status_search').val();
            var rights_url = 'hr/viewLoanRequestList';
            var m = '{{ $m }}';
            $('#tableData').html('<div class="loading"></div>');
            $.ajax({
                url: '{{ url('/') }}/hdc/viewFilteredLoanRequestList',
                type: "GET",
                data:{department_id:department_id, sub_department_id:sub_department_id,location_id:location_id, category_id:category_id, project_id:project_id,
                    employee_id:employee_id, status:status, company_id:company_id, rights_url:rights_url},
                success: function (data) {
                    $('#tableData').html(data);
                },
                error: function (error) {
                    $('#tableData').html('');
                    swalAlert('Error','Something went wrong');
                }
            });
        }

    </script>
@endsection