<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

?>
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
        <?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="<?= Input::get('m') ?>">
        <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Provident Fund Report</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintProvidentFundReport','','1');?>
                                <?php echo CommonHelper::displayExportButton('ProvidentFundReport','','1')?>
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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewProvidentFundReport()" style="margin-top: 38px;" > <i id="load" class="fas fa-search fa"> </i> Search</button>
                            </div>
                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>
        <span id="employeePayslipSection" style="display:none;">




            <div class="col-12">
                <div class="card" id="ProvidentFundReport">
                    <div class="card-body" id="PrintProvidentFundReport">
                        <div class="row">
                            <div class="col-sm-12">
                               <div id="run_loader"></div>
                                <div class="employeePayslipSection"></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


        </span>

        </div>


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

