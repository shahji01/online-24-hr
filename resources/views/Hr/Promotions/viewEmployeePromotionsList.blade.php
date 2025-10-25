<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
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
                                <h4 class="card-title">Promotion List</h4>
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
                                    <label class="sf-label">Employment Status</label>
                                        <select class="form-control  employee_status" name="employment_status_id" id="employment_status_id" >
                                            <option value="">Select Employment Status</option>
                                            @foreach($job_type as $key3 => $value)
                                                <option value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                            @endforeach
                                        </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Leaves Status</label>
                                <select class="form-control" id="approval_status" name="approval_status">
                                    <option value="">Select Option</option>
                                    <option selected value="1">Pending</option>
                                    <option value="2">Approved</option>
                                    <option value="3">Rejected</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewEmployeePromotionsListDetail()"><i id="load" class="fas fa-search fa"> Search</i></button>
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
                                        <th class="text-center">S No</th>
                                        <th class="text-center">Emp ID</th>
                                        <th class="text-center">Emp Name</th>
                                        <th class="text-center">Designation</th>
                                        <th class="text-center">Increment</th>
                                        <th class="text-center">Salary</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Approval Status</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">View</th>
                                        @if (Auth::user()->acc_type == 'client')
                                            <th class="text-center">
                                                Approval Check <br>
                                                <input id="checkbox" type="checkbox" name="checkbox" value="">
                                            </th>
                                        @endif
                                        <th class="text-center hidden-print" id="hide-table-row">Action</th>
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
        $(document).ready(function() {
            $('#approval_status').select2();

            $("#checkbox").click(function() {
                if ($("#checkbox").prop("checked") == true) {
                    $(".check_input").prop("checked", true);
                } else {
                    $(".check_input").prop("checked", false);
                }
            });
        });

        setTimeout(function() {
            viewEmployeePromotionsListDetail();
        }, 1000);

        function viewEmployeePromotionsListDetail() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var location_id = $('#location_id').val();
            var approval_status = $('#approval_status').val();
            var company_id = '{{ $m }}';
            var job_type = $('#employment_status_id').val();
            var rights_url = 'hr/viewEmployeePromotionsList';

            jqueryValidationCustom();
            if (validate == 0) {
                $('#tableData').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl + '/hdc/viewEmployeePromotionsListDetail',
                    type: "GET",
                    data: {
                        company_id: company_id,
                        employee_id: employee_id,
                        department_id: department_id,
                        sub_department_id: sub_department_id,
                        project_id: project_id,
                        category_id: category_id,
                        location_id:location_id,
                        approval_status: approval_status,
                        job_type:job_type,
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
        }

    </script>
@endsection

