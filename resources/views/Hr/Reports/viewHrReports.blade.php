<?php
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

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
                                <h4 class="card-title">View HR Reports</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="m" id="m" value="<?php echo Input::get('m'); ?>">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintHrReport','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('HrReport','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                        <a style="width:100%;" class="btn btn-sm btn-outline-info waves-effect waves-light" href="#" id="viewEmployeeReportForm"><span class="btn-label"><i class="fas fa-table"> View Employee Report</i> </span></a>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                        <a style="width:100%;" class="btn btn-sm btn-outline-info waves-effect waves-light" id="viewOnboardReportForm" href="#"><span class="btn-label"><i class="fas fa-info-circle"> View Onboard Report</i></span> </a>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                        <a style="width:100%;" class="btn btn-sm btn-outline-info waves-effect waves-light" href="#" id="viewEmployeeExpReportForm"><span class="btn-label"><i class="fas fa-table"> View Exp & Edu Report</i></span> </a>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                        <a style="width:100%;" class="btn btn-sm btn-outline-info waves-effect waves-light" id="viewIncrementReportForm"href="#"><span class="btn-label"><i class="fas fa-plus"> View Increment Report</i> </span></a>
                                    </div>
                                </div>

                         <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>
        
        <span id="reportsArea" style="display:none;"> 



            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintregionWisePayrollReport">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="report-form-area"></div>
                                 <br>
                                 <div class="row">
                            <input type="hidden" name="HrReports[]">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id=""></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="report-area"></div>
                            </div>
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

