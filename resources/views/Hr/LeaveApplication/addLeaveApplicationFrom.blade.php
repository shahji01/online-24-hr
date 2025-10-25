<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\SubDepartment;
$user_roles = CommonHelper::userRoles($m);
?>

@extends('layouts.default')
@section('content')
    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="card-title">Leave Application Form</h4>
                            </div>
                        </div>
                        <hr>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>From Date</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="from_date" id="from_date" class="form-control requiredField" value="{{Session::get('fromDate')}}">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>To Date</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="to_date" id="to_date" class="form-control requiredField" value="{{Session::get('toDate')}}">
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewLeaveApplicationClientForm()"><i id="load" class="fas fa-search fa">Search</i></button>
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
                                        <th class="text-center">Department</th>
                                        <th class="text-center">From</th>
                                        <th class="text-center">Till</th>
                                        <th class="text-center">Day Type</th>
                                        <th class="text-center">Total days</th>
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
        <div id="addMoreSection"></div>
        <div class="row">&nbsp;</div>
    </div>
@endsection

@section('script')
    <script>

        function viewLeaveApplicationClientForm() {
            var department_id = $('#department_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var month_year = $('#month_year').val();
            var job_type = $('#employment_status_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            jqueryValidationCustom();
            if(validate == 0){

                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: '{{ url('/') }}/hdc/addLeaveApplicationFrom',
                    type: "GET",
                    data: {
                        month_year:month_year,
                        job_type:job_type,
                        m:m,
                        employee_id:employee_id,
                        category_id:category_id,
                        location_id:location_id,
                        department_id:department_id,
                        from_date:from_date,
                        to_date:to_date,
                        project_id:project_id
                    },
                    success: function(data) {
                        if (data == 0) {
                            swalAlert('Error', 'Please select leave policy !');
                            $('#tableData').html('');
                            $('#addMoreSection').html('');
                            setTimeout(() => {
                                viewEmployeeLeavesDetail();
                        }, 2000);

                    } else {
                        $('#tableData').html(data);
                $('#addMoreSection').html('');

            }
        },
        error: function() {

            $('#addMoreSection').html('');
            $('#tableData').html('');
            swalError();
        }
        });
        }
        }



    </script>
@endsection
