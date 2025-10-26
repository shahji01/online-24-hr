<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
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
                        <hr>
                        <div class="row">
                            @include('includes.allFilters')
                             <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Month - Year</label>
                                <input type="month" name="month_year" id="month_year" value="{{ date('Y-m') }}" class="form-control" />
                            </div>
                            <div class="ccol-lg-2 col-md-2 col-sm-2 col-xs-12 text-left" style="margin-top: 40px;">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewFilteredAdvanceSalaryList();" ><i id="load" class="fas fa-search fa"> </i> Search</button>
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
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">EMP ID</th>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Amount Needed</th>
                                        <th class="text-center">Salary Need On</th>
                                        <th class="text-center">Deduction Month/year</th>
                                        <th class="text-center">Approval Status</th>
                                        <th class="text-center">Status</th>
                                        @if (Auth::user()->acc_type == 'client')
                                            <th class="text-center">
                                                Approval Check <br>
                                                <input id="checkAll" type="checkbox" name="checkbox" value="">
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
            let checkAllCheckbox = document.getElementById('checkAll');
           
            
            checkAllCheckbox.addEventListener('click', function() {
                let individualCheckboxes = document.querySelectorAll('.check_input');
                individualCheckboxes.forEach(checkbox => {
                    checkbox.checked = checkAllCheckbox.checked;
                });
            });
            setTimeout(function() {
                viewFilteredAdvanceSalaryList();
            }, 1000);
        });

        function viewFilteredAdvanceSalaryList() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var category_id = $('#category_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var employee_id = $('#employee_id').val();
            var month_year = $('#month_year').val();
            var company_id = '{{ $m }}';
            var rights_url = 'hr/viewAdvanceSalaryList';
            $('#tableData').html('<div class="loader"></div>');
            $.ajax({
                url: '{{ url('/') }}/hdc/viewFilteredAdvanceSalaryList',
                type: "GET",
                data:{department_id:department_id, sub_department_id:sub_department_id,location_id:location_id, category_id:category_id, project_id:project_id,
                    employee_id:employee_id, month_year:month_year, company_id:company_id, rights_url:rights_url},
                success: function(res) {
                    $('#tableData').html(res);
                },
                error: function(error) {
                    $('#tableData').html('');
                }
            });
        }

    </script>
@endsection