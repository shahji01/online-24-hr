<?php
$accType = Auth::user()->acc_type;

$m = $_GET['m'];

?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        {{ Form::open(array('url' => 'had/addTaxesDetail','id'=>'EOBIform')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="TaxesSection[]" class="form-control" id="sectionEOBI" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Taxes Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Tax Name</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="tax_name" id="tax_name" value="" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Monthly Salary Range From</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="salary_range_from[]" id="salary_range_from" value="" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Monthly Salary Range To</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="salary_range_to[]" id="salary_range_to" value="" class="form-control requiredField" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Percentange of Tax</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="tax_percent[]" id="tax_percent" value="" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Tax Amount Per Year</label>
                            <input type="number" name="tax_amount[]" id="tax_amount" value="" class="form-control" />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Tax Month & Year</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="month" name="tax_month_year" id="tax_month_year" value="" class="form-control requiredField" />
                        </div>
                    </div>

                    <div class="TaxesSection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <input type="button" class="btn btn-sm btn-primary addMoreTaxesSection" value="Add More Taxes" />
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

