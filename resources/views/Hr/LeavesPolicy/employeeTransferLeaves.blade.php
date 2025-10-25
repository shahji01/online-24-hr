<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
//$parentCode = $_GET['parentCode'];

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
        <?php echo Form::open(array('url' => 'had/addPayrollDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Employee Transfer Leaves</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                                <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Companies:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="company_id" id="company_id">
                                    <option value="All">All Companies</option>
                                    @foreach($companies as $companyData)
                                        <option value="{{ $companyData->id}}">{{ $companyData->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="viewLeavesBalances()" style="margin-top: 38px;" ><i id="load" class="fas fa-search fa"></i> Seacrh </button>
                            </div>

                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>

        <span id="employeeAttendenceReportSection" style="display:none;">




            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeAttendanceList">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="loader"></div>
                               <div class="employeeAttendenceReportSection" id="PrintEmployeeAttendanceList"></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


        </span>
    <?php echo Form::close();?>
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
    </div>



@endsection

