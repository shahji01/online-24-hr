<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$m = Input::get('m');
$leave_day_type = [1 => 'full Day Leave', 2 => 'Half Day Leave', 3 => 'Short Leave'];
$counter = 1;
$leave_type_name = '';
?>
@section('css')
    <style>
        input[type="radio"],
        input[type="checkbox"] {
            width: 30px;
            height: 20px;
        }
    </style>
@endsection
@extends('layouts.default')
@section('content')
    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                @if (in_array('print', $operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList', '', '1') }}
                                @endif
                                @if (in_array('export', $operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList', '', '1') }}
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Leaves Status</label>
                                <select class="form-control" id="approval_status" name="approval_status">
                                    <option value="">Select Option</option>
                                    <option selected value="1">Pending</option>
                                    <option value="2">Approved</option>
                                    <option value="3">Rejected</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>From Date</label>
                                <input type="Date" name="from_date" id="from_date" value="{{ date('Y-m-01') }}" class="form-control" />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>To Date</label>
                                <input type="Date" name="to_date" id="to_date" value="{{ date('Y-m-t') }}" class="form-control" />
                            </div>
                            <div class="ccol-lg-2 col-md-2 col-sm-2 col-xs-12 text-left" style="margin-top: 40px;">
                                <button type="button" class="btn btn-sm btn-primary btn_search"
                                    onclick="viewFilteredLeaveRequestList();"><i id="load" class="fas fa-search fa"></i> Search
                                </button>
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
                                    <tr>
                                        <th class="text-center">S No.</th>
                                        <th class="text-center">Emp ID</th>
                                        <th class="text-center">Emp Name</th>
                                        <th class="text-center">Leave Type</th>
                                        <th class="text-center">From</th>
                                        <th class="text-center">Till</th>
                                        <th class="text-center">Day Type</th>
                                        <th class="text-center">Approval Status (HR)</th>
                                        <th class="text-center">Approval Status (GM)</th>
                                        <th class="text-center">Status</th>
                                        @if (Auth::user()->acc_type == 'client' || Auth::user()->acc_type == 'admin')
                                            <th class="text-center">
                                                Approval Check
                                                <input id="checkbox" type="checkbox" name="checkbox" value="">
                                            </th>
                                        @endif
                                        <th class="text-center hidden-print">Action</th>
                                    </tr>
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
        $(function() {
            $('#approval_status').select2();
            $("#checkbox").click(function() {
                if ($("#checkbox").prop("checked") == true) {
                    $(".check_list").prop("checked", true);
                    $(".check_input").val('1');
                } else {
                    $(".check_list").prop("checked", false);
                    $(".check_input").val('0');
                }
            });
        });

        function checkListChange(emp_id) {
            if ($("#check_list_" + emp_id).prop("checked") == true) {
                $("#check_input_" + emp_id).val('1');
            } else {
                $("#check_input_" + emp_id).val('0');
            }
        }

        setTimeout(function() {
            viewFilteredLeaveRequestList();
        }, 1000);

        function viewFilteredLeaveRequestList() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var category_id = $('#category_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var employee_id = $('#employee_id').val();
            var approval_status = $('#approval_status').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var rights_url = 'hr/viewLeaveApplicationRequestList';
            var company_id = '{{ $m }}';
            $('#tableData').html('<div class="loader"></div>');
            $.ajax({
                url: '{{ url('/') }}/hdc/viewFilteredLeaveRequestList',
                type: "GET",
                data: {
                    department_id: department_id,
                    sub_department_id: sub_department_id,
                    category_id: category_id,
                    project_id: project_id,
                    employee_id: employee_id,
                    approval_status: approval_status,
                    location_id: location_id,
                    from_date: from_date,
                    to_date: to_date,
                    company_id: company_id,
                    rights_url: rights_url
                },
                success: function(res) {
                    $('#tableData').html(res);
                },
                error: function(error) {
                    $('#tableData').html('');
                    swalError();
                }
            });
        }

        function leaveApprovOrReject(approvalType) {
            var check = $("input[name='check_input[]']").map(function(){return $(this).val();}).get();

            if(check.includes('1')) {
                var leave_id = $("input[name='leave_id[]']").map(function(){return $(this).val();}).get();
                var employee_id = $("input[name='employee_id[]']").map(function(){return $(this).val();}).get();
                var company_id = '{{ $m }}';

                var data = {
                    approvalType:approvalType,
                    check: check,
                    leave_id: leave_id,
                    employee_id: employee_id,
                    company_id: company_id
                };

                $.ajax({
                    url: '{{ url('/') }}/hadbac/leaveApprovOrReject',
                    type: "GET",
                    data: data,
                    success: function(res) {
                        viewFilteredLeaveRequestList();
                    },
                    error: function(error) {
                        swalError();
                        viewFilteredLeaveRequestList();
                    }
                });
            }
        }

    </script>
@endsection
