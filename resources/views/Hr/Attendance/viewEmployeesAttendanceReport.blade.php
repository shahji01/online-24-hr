<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addPayrollDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="{{ $m }}">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Employees Attendance Report</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintPayrollForm','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    {{ CommonHelper::displayExportButton('PayrollForm','','1') }}
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Departments:</label>
                                <select style="width: 100%;" class="form-control" name="department_id"
                                    id="department_id" onchange="getEmployee('sub_department')">
                                    <option value="">Select Option</option>
                                    @foreach ($departments as $key => $y)
                                        <option value="{{ $y->id }}">{{ $y->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Sub Department:</label>
                                <select style="width: 100%;" class="form-control" name="sub_department_id"
                                    id="sub_department_id" onchange="getEmployee('')">
                                    <option value="">Select Option</option>
                                    @foreach ($sub_departments as $key => $y)
                                        <option value="{{ $y->id }}">{{ $y->sub_department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Location:</label>
                                <select class="form-control" name="location_id" id="location_id" onchange="getEmployee('')">
                                    <option value="">Select Option</option>
                                    @foreach($locations as $key => $y)
                                        <option value="{{ $y->id }}">{{ $y->location_code.' -- '.$y->location_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Category:</label>
                                <select style="width: 100%;" class="form-control" name="category_id"
                                    id="category_id" onchange="getEmployee('')">
                                    <option value="">Select Option</option>
                                    @foreach ($category as $key => $y)
                                        <option value="{{ $y->id }}">{{ $y->employee_category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%;" class="form-control requiredField" name="employee_id"
                                    id="employee_id">
                                    <option value="all">All</option>
                                    @foreach ($employees as $key => $y)
                                        <option value="{{ $y->id }}">
                                            {{ $y->emp_id . ' -- ' . $y->emp_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="emp_loader"></div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>From Date</label>
                                <input type="date" name="from" id="from" class="form-control requiredField" >
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>To Date</label>
                                <input type="date" name="to" id="to" class="form-control requiredField" >
                            </div>
                        
                            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12 filterbtnDiv">
                                <br/>
                                <button style="margin-top:20px;" type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="viewEmployeesAttendanceReport()"><i id="load" class="fas fa-search fa"> Search</i></button>
                            </div>
                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>
                </div>
            </div>
        </div>

        <span id="employeePayslipSection" style="display:none;">
            <div class="row">
                <div class="col-12">
                    <div class="card" id="PayrollForm">
                        <div class="card-body" id="PrintPayrollForm">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="run_loader"></div>
                                    <div class="employeePayslipSection"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </span>
        {{ Form::close() }}
    </div>
@endsection