<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];


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

        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Re-Concile Report</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintregionWisePayrollReport','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('regionWisePayrollReport','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Month-Year</label>
                                <input type="month" name="month_year_from" id="month_year_from" max="" class="form-control requiredField">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Month-Year</label>
                                <input type="month" name="month_year_to" id="month_year_to" max="" class="form-control requiredField">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <br>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{--<button type="button" class="btn btn-sm btn-primary btn_search" id="viewConcileReport" onclick="viewConcileReport()"><i id="load" class="fas fa-search fa"> </i> Search</button>
                                --}}
                                <button type="button" class="btn btn-sm btn-primary btn_search" id="viewConcileReport" onclick="viewConcileReport()"><i id="load" class="fas fa-search fa"> </i> Search</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <span id="employeeAttendenceReportSection" style="display: none;">


        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintregionWisePayrollReport">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="loader"></div>
                                <div class="employeeAttendenceReportSection" id="PrintEmployeeAttendanceList"></div>


                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        </span>
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

