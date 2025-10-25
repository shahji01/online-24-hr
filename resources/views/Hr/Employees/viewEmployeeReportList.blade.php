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
    <style>
        #printList
        {
            overflow: unset;
            height: unset;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable with Buttons

        });
    </script>
    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title">Employee Report</h4>
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
                        <input type="hidden" id="company_id" value="{{ $m }}">
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Employment Status</label>
                                <select class="form-control  employee_status" name="employment_status_id" id="employment_status_id" style="width: 100%">
                                    <option value="">Select Option</option>
                                    @foreach($job_type as $key3 => $value)
                                        <option value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label class="sf-label">Joining Date</label>
                                <select class="form-control joining_date" name="joining_date" id="joining_date" style="width: 100%">
                                    <option value="">Select Option</option>
                                    <option value="last_year_5">Last Year 5</option>
                                    <option value="last_year_4">Last Year 4</option>
                                    <option value="last_year_3">Last Year 3</option>
                                    <option value="last_year_2">Last Year 2</option>
                                    <option value="last_year">Last Year</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="last_week">Last Week</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label class="sf-label pointer">Status:</label>
                                <select class="form-control" name="status" id="status" style="width:100%;">
                                    <option value="">Select Option</option>
                                    <option value="1" selected>Active</option>
                                    <option value="4">InActive</option>
                                    <option value="3">Exit</option>
                                    <option value="2">Deleted</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewEmployeeReportFilteredList()"><i id="load" class="fas fa-search fa"> Search</i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper" id="printList">

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
        $('#joining_date').select2();
        $('#status').select2();
        setTimeout(function() {
            viewEmployeeReportFilteredList();
        }, 1500);

        function viewEmployeeReportFilteredList() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var category_id = $('#category_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var employee_id = $('#employee_id').val();
            var company_id = $('#company_id').val();
            var jobType = $('#employment_status_id').val();
            var joining_date = $('#joining_date').val();
            var status = $('#status').val();
            var rights_url = 'hr/viewEmployeeReportList';
            $('#printList').html('<div class="loader"></div>');
            $.ajax({
                url: baseUrl+"/hdc/viewEmployeeReportFilteredList",
                type: 'GET',
                data: {
                    company_id: company_id,
                    department_id: department_id,
                    sub_department_id: sub_department_id,
                    category_id: category_id,
                    project_id: project_id,
                    location_id: location_id,
                    employee_id: employee_id,
                    jobType:jobType,
                    joining_date:joining_date,
                    rights_url: rights_url,
                    status: status
                },
                success: function (response) {
                    $('#printList').html(response);
                    $('#exportList').DataTable({
                        "destroy": true,
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'copyHtml5',
                                exportOptions: {
                                    columns: ':not(.exclude-export)' // Exclude columns with class 'exclude-export'
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                exportOptions: {
                                    columns: ':not(.exclude-export)'
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                exportOptions: {
                                    columns: ':not(.exclude-export)'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                exportOptions: {
                                    columns: ':not(.exclude-export)'
                                }
                            },
                            {
                                extend: 'print',
                                text: 'Print all',
                                exportOptions: {
                                    columns: ':not(.exclude-export)'
                                }
                            }
                        ],
                        columnDefs: [
                            {
                                targets: [-1], // Targets columns you want to exclude from export
                                visible: true,
                                className: 'exclude-export' // Add a class to these columns to exclude them
                            }
                        ]
                    });

                },
                error: function() {
                    $('#printList').html('');
                    swalError();
                }
            });
        }

    </script>
@endsection