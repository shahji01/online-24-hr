<?php

$tableArray = [
    'advance_salary' => 'Advance Salary',
    'allowance' => 'Allowance',
    'attendance' => 'Attendance',
    'bonus_issue' => 'Bonus Issue',
    'deduction' => 'Deduction',
    'drivers_allowance' => 'Drivers Allowance',
    'employee' => 'Employee',
    'employee_card_request' => 'Employee Card Request',
    'employee_exit' => 'Employee Exit Clearance',
    'employee_equipments' => 'Equipments',
    'employee_location' => 'Employee Transfers',
    'employee_promotion' => 'Employee Promotions',
    'final_settlement' => 'Final Settlement',
    'fuel' => 'Fuel Allowance',
    'payroll_data' => 'Attendance Progress',
    'holidays' => 'Holidays',
    'hr_contract_conclusion_letter' => 'Contract Conclusion Letter',
    'hr_mfm_south_increment_letter' => 'MFM South Increment Letter',
    'hr_mfm_south_without_increment_letter' => 'MFM South Without Increment Letter',
    'hr_termination_format1_letter' => 'Termination Format 1 Letter',
    'hr_termination_format2_letter' => 'Termination Format 2 Letter',
    'hr_transfer_letter' => 'Transfer Letter',
    'hr_warning_letter' => 'Warning Letter',
    'leave_application' => 'Leave Application',
    'loan_request' => 'Loan Request',
    'gratuity' => 'Gratuity',
    'overtime' => 'Overtime',
    'trainings' => 'Trainings',
    'employee_hr_audit' => 'HR Audit',
    'users' => 'Users',
    'payslip' => 'Payroll',
    'app_users' => 'App Users',
    'attendance_approval' => 'FM Attendance Approval',
    'hr_attendance_approval' => 'HR Attendance Approval',
    'letter_files' => 'Letter Files',
    'role' => 'Roles',
    'rebate' => 'Rebate',
    'LoanRequest'=>'Loan Request',
    'pf' => 'Provident Fund',
    'pf_withdrawal' => 'Provident Fund Withdrawal',
        'Employee_Bank_Data' => 'Employee Bank Data',
        'Employee_Equipments' => 'Employee Equipments',
        'Employee_Other_Details' => 'Employee Other Details',
        'Menu_Privileges' => 'Menu Privileges',
        'IncomeTax' => 'Income Tax',
        'Payslip' => 'Payslip'
    
];

$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

<style>
    .card-body {
     min-height: unset !important; 
}
</style>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="lineHeight">&nbsp;</div>
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Logs</span>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label>Sub Module</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="sub_module" id="sub_module">
                                                <option value="">Select</option>
                                                @foreach ($tableNames as $value)
                                                    <option value="{{ $value['table_name'] }}">
                                                        {{ $tableArray[$value['table_name']] }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label>From Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField" name="from_date"
                                                id="from_date">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label>Till Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField" name="till_date"
                                                id="till_date">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label>Users</label>
                                            <select class="form-control " name="username" id="username">
                                                <option value="">Select</option>
                                                @foreach ($username as $value)
                                                    <option value="{{ $value->id }}">{{ $value->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-top: 32px">
                                            <button id="searchLog" class="btn btn-primary btn-sm"
                                                type="button">Search</button>
                                        </div>
                                    </div>
                                </div>
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
                    <div class="panel">
                        <div class="panel-body">
    <div class="logSection" id="logSection"></div>

                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>-->
    <script>
        $(document).ready(function() {
            // $('#username').select2();
            // $('#sub_module').select2();
        });

        $("#searchLog").click(function() {
            var m = "{{ $m }}";
            var sub_module = $("#sub_module").val();
            var from_date = $("#from_date").val();
            var till_date = $("#till_date").val();
            var username = $("#username").val();
            jqueryValidationCustom();
            if (validate == 0) {
                $('#logSection').html('<div class="loading"></div>');
                $.ajax({
                    url: '{{ url('/') }}/hr/viewLogDetail',
                    type: "GET",
                    data: {
                        sub_module: sub_module,
                        m: m,
                        from_date: from_date,
                        till_date: till_date,
                        username: username
                    },
                    success: function(data) {
                        $("#logSection").html(data);
                    },
                    error: function() {
                        swalAlert('Error', 'Something went wrong');
                        $("#logSection").html('');
                    }
                });
            }
        });
    </script>
@endsection
