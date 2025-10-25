<?php
$accType = Auth::user()->acc_type;
$m = $_GET['m'];
$current_date = date('Y-m-d');
use App\Helpers\CommonHelper;

?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">
        <div class="row" style="margin-bottom: 10px;">

        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/addPfDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Add Provident Fund For Employee</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintPayrollForm','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('PayrollForm','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Departments:</label>
                                            <select class="form-control" name="department_id" id="department_id" onchange="getEmployee('sub_department')">
                                                <option value="">Select Option</option>
                                                @foreach($departments as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Sub Department:</label>
                                            <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee('')">
                                                <option value="">Select Option</option>
                                                @foreach($sub_departments as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->sub_department_name}}</option>
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
                                            <select class="form-control" name="category_id" id="category_id" onchange="getEmployee('')">
                                                <option value="">Select Option</option>
                                                @foreach($category as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->employee_category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                         

                        </div>
                        
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Employee:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="employee_id" id="employee_id">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $key => $y)
                                            <option value="{{ $y->id }}">{{ $y->emp_id.' -- '.$y->emp_name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="emp_loader"></div>
                                </div>
                            <div class="col-sm-3">
                                <div class="row">&nbsp;</div>
                                <!--<input style="width: 24px;height: 20px;margin-top: 8px;" type="checkbox" id="show_all" name="show_all" value="show_all">-->
                                <!--<label  for="show_all"> Show All </label>&nbsp;&nbsp;-->
                                <button style="margin-top: 18px;" type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="viewEmployeePfForm()"><i id="load" class="fas fa-search fa"> Search</i></button>
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

    <?php echo Form::close();?>
 
    </div>



@endsection

