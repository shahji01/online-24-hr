<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//	$m = $_GET['m'];
//}else{
//	$m = Auth::user()->company_id;
//}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/addQualificationDetail?m='.$m.'','id'=>'qualificationForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="qualificationSection[]" class="requiredField form-control" id="qualificationSection" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Qualification Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Institute Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="institute_name_1" id="institute_name_1" class="form-control requiredField">
                                @foreach($institutes as $key => $i)
                                    <option value="{{ $i->id}}">{{ $i->institute_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Qualification Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="qualification_name_1" id="qualification_name_1" placeholder="Qualification Name" value="" required class="form-control requiredField" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Country</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="country_1" id="country_1" required class="form-control requiredField">
                                <option value="">Select Country</option>
                                @foreach($countries as $key => $y)
                                    <option value="{{ $y->id}}">{{ $y->nicename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>State</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <span id="state_area_1">
											<select name="state_1" class="form-control" id="state_1">
												<option value="">Select State</option>
											</select>
										</span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>City</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <span id="city_area_1">
											<select name="city_1" id="city_1" required class="form-control requiredField">
												<option value="">Select City</option>
											</select>
										</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Sub Department Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="sub_department_name_1" id="sub_department_name_1" value="" required class="form-control requiredField" />
                        </div>
                    </div>
                    <div class="qualificationSection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreQualificationSection" value="Add More Qualification's Section" />
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

