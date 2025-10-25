<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="card-title">Allowance List</h4>
                            </div>
                            <div class="col-sm-6 text-right">
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
                                <label class="sf-label">Allowance Type:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%;" name="allowance_type_id_search"
                                        id="allowance_type_id_search" class="form-control">
                                    <option value="">Select Allowance Type</option>
                                    @foreach ($allowance_types as $key => $y)
                                        <option value="{{ $y->id }}">{{ $y->allowance_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search"
                                        onclick="viewAllowanceListDetail()"><i id="load" class="fas fa-search fa"> Search</i></button>
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
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Emp Name</th>
                                    <th class="text-center">Department</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Project</th>
                                    <th class="text-center">Allowance Type</th>
                                    <th class="text-center">Month - Year</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center hidden-print" id="hide-table-row">Actions</th>
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
        $('#allowance_type_id_search').select2();
        $(document).ready(function() {
            setTimeout(function() {
                viewAllowanceListDetail();
            }, 1000);
        });

        function viewAllowanceListDetail() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var location_id = $('#location_id').val();
            var allowance_type_id_search = $('#allowance_type_id_search').val();
            var company_id = m;
            var rights_url = 'hr/viewAllowanceList';

            jqueryValidationCustom();
            if (validate == 0) {
                $('#tableData').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl + '/hdc/viewAllowanceListDetail',
                    type: "GET",
                    data: {
                        company_id: company_id,
                        employee_id: employee_id,
                        department_id: department_id,
                        sub_department_id: sub_department_id,
                        project_id: project_id,
                        category_id: category_id,
                        location_id:location_id,
                        deduction_type:allowance_type_id_search,
                        rights_url: rights_url
                    },
                    success: function (data) {
                        $('#tableData').html(data);

                    },
                    error: function () {
                        swalError();$('#tableData').html('');

                    }
                });
            }
        }

    </script>
@endsection