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
        <?php echo Form::open(array('url' => 'had/addBonusDetail','id'=>'EOBIform'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Create Bonus Form</h4>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Bonus Name:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="Bonus_name[]" id="Bonus_name" value="" class="form-control requiredField" required />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Amount / % of Salary:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <input type="radio" name="type" value="1">
                                        Percent
                                        <input type="radio" name="type" checked value="2">
                                        Amount
                                    </div>
                                </div>
                                
                                  

                                <input type="number" name="percent_of_salary[]" id="percent_of_salary" value="" class="form-control requiredField" required />
                            </div>
                        </div>

                        <div class="BonusSection"></div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{--<input type="button" class="btn btn-sm btn-primary addMoreBonusSection" value="Add More Bonus Section" />--}}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            </div>
                        </div>
                    </div>

                </div>


            </div>

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

