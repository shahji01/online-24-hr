<?php
$accType = Auth::user()->acc_type;
$m = $_GET['m'];
$current_date = date('Y-m-d');
use App\Helpers\CommonHelper;

?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintPayrollForm','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('regionWisePayrollReport','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Type Password to view your Salary Sheet</label>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <input type="password" id="passwordSecret" class="form-control">
                                        <label style="font-size: 14px;color:red;" id="err"></label>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <button type="button" class="btn btn-sm btn-info" id="passwordSecretbtn" style="margin-top: 5px;">View</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <span id="employeePayslipSection" style="display: none;">


        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintPayrollForm">
                        <div class="row">
                            <div class="col-sm-12">

                                <div id="mainDiv"></div>
                            </div>
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

    <script type="text/javascript">

    </script>




@endsection

