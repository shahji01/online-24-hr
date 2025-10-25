<?php
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\EmployeePromotion;
$counter = 1
?>

@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title">{{$breadCrums['heading']}}</h4>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" id="company_id" value="{{ $m }}">
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Status:</label>
                                <select class="form-control" name="status" id="status" style="width:100%;">
                                    <option value="">Select Option</option>
                                    <option value="1" selected>Active</option>
                                    <option value="4">InActive</option>
                                    <option value="3">Exit</option>
                                    <option value="2">Deleted</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewEmployeeFilteredList()"><i id="load" class="fas fa-search fa"> Search</i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body wrapper">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printList">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                                    <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Emp ID</th>
                                        <th class="text-center">Attendance id</th>
                                        <th class="text-center">Emp Name</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Designation</th>
                                        <th class="text-center">Birth Date</th>
                                        <th class="text-center">Joining Date</th>
                                        <th class="text-center">CNIC</th>
                                        <th class="text-center">Contact</th>
                                        <th class="text-center">Salary</th>
                                        <th id="hide-table-row" class="text-center hidden-print">Action</th>
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
        $('#status').select2();
        setTimeout(function() {
            viewEmployeeFilteredList();
        }, 1500);

        function viewEmployeeFilteredList() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var category_id = $('#category_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var employee_id = $('#employee_id').val();
            var company_id = $('#company_id').val();
            var status = $('#status').val();
            var rights_url = 'hr/viewEmployeeList';

            $('#tableData').html('<div class="loader"></div>');
            $.ajax({
                url: baseUrl+"/hdc/viewEmployeeFilteredList",
                type: 'GET',
                data: {
                    company_id: company_id,
                    department_id: department_id,
                    sub_department_id: sub_department_id,
                    category_id: category_id,
                    project_id: project_id,
                    location_id: location_id,
                    employee_id: employee_id,
                    rights_url: rights_url,
                    status: status
                },
                success: function (response) {
                    $('#tableData').html(response);
                },
                error: function() {
                    $('#tableData').html('');
                    swalError();
                }
            });
        }

    </script>
@endsection