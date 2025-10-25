<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Create Provident Fund Form</h4>
                            </div>


                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo Form::open(array('url' => 'had/addProvidentFundDetail','id'=>'providentFund'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">

                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>Provident Fund Name:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="pf_name[]" id="pf_name[]" value="" required class="form-control requiredField" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>Percentange % of Salary
                                                    <input type="radio" name="pf_mode[]" value="percentage">Amount
                                                    <input type="radio" name="pf_mode[]" value="amount">
                                                </label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="amount_percent[]" id="amount_percent[]" required value="" class="form-control requiredField" />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="TaxesSection"></div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php echo Form::close();?>
                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

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
    </div>









@endsection

