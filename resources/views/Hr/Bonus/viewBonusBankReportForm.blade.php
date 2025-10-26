<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}


use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->


        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>

                        </div>
                        <div class="col-sm-4 text-right" >
                            @if(in_array('print', $operation_rights))
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                            @endif
                            @if(in_array('export', $operation_rights))
                                <?php echo CommonHelper::displayExportButton('regionWisePayrollReport','','1')?>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Departments:</label>
                                            <select style="width: 100%;" class="form-control" multiple name="department_id[]" id="department_id" onchange="getEmployeeMultiple('sub_department')">
                                                <option value="">Select Option</option>
                                                @foreach($departments as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Sub Department:</label>
                                            <select style="width: 100%;" class="form-control" multiple name="sub_department_id" id="sub_department_id" onchange="getEmployeeMultiple('')">
                                                <option value="">Select Option</option>
                                                @foreach($sub_departments as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->sub_department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Project:</label>
                                            <select style="width: 100%;" class="form-control" multiple name="project_id[]" id="project_id" onchange="getEmployeeMultiple('')">
                                                <option value="">Select Option</option>
                                                @foreach($projects as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->project_code.' -- '.$y->project_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Category:</label>
                                            <select style="width: 100%;" class="form-control" multiple name="category_id[]" id="category_id" onchange="getEmployeeMultiple('')">
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
                            <select style="width: 100%;" class="form-control requiredField" name="employee_id" id="employee_id">
                                <option value="">Select Employee</option>
                                @foreach($employees as $key => $y)
                                    <option value="{{ $y->id }}">{{ $y->emp_id.' -- '.$y->emp_name}}</option>
                                @endforeach
                            </select>
                            <div id="emp_loader"></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Month-Year</label>
                            <input type="month" name="month_year" id="month_year" max="" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Cheque Date.</label>
                            <input type="date" name="cheque_date" id="cheque_date" max="" value="<?=date('Y-m-d')?>" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Select Bank</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" placeholder="Bank Name" name="bank_name" id="bank_name">
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button style="margin-top:8px;" type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="showBonusReportBank()" ><i id="load" class="fas fa fa-search"> </i> Search</button>
                        </div>

                    </div>

                </div>



            </div>
        </div>


        <br>


        <span style="display: none;" id="employeeAttendenceReportSection">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" id="PrintEmployeeAttendanceList">
                        <div class="col-sm-12" >
                             <br>
                            <div id="loader"></div>
                            <div class="employeeAttendenceReportSection" ></div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </span>




    </div>




    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    <!-- ============================================================== -->
    <!-- End Right sidebart -->
    <!-- ============================================================== -->




@endsection

