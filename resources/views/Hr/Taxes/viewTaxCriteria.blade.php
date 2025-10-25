<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
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

            <?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Tax Criteria</h4>
                            </div>


                        </div>
                        <hr>


                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Department:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="sub_department_id" id="sub_department_id">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $key => $y)
                                        <optgroup label="All Employees" value="all"> <option selected value="all">All Employees</option></optgroup>
                                        <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                            <?php
                                            $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');
                                            ?>
                                            @foreach($subdepartments as $key2 => $y2)
                                                <option value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <input type="button" class="btn btn-sm btn-primary" onclick="viewTaxCriteria()" value="Check" style="margin-top: 32px;" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Taxes List:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="tax_id" id="tax_id">
                                    <option value="">Select Tax</option>
                                    @foreach($taxes as $key2 => $value)
                                        <option value="{{ $value->id}}">{{ $value->tax_name}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <input type="button" class="btn btn-sm btn-primary" onclick="viewTax($('#tax_id').val())" value="View " style="margin-top: 32px;" />
                            </div>
                        </div>
                        <div class="text-center ajax-loader"></div>
                        <div class="viewCarPolicyArea"></div>
                    </div>

                </div>
            </div>
                <?php echo Form::close();?>
        </div>

@endsection
