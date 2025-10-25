<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
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
                                <h4 class="card-title">Create Hr Letters Form</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintHrLettersForm','','1');?>
                                <?php echo CommonHelper::displayExportButton('HrLettersForm','','1')?>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addHrLetters','id'=>'HrLetters', 'method' => 'post'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=$m?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body" >
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label pointer">Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control" name="department_id" id="department_id" onchange="getEmployee('sub_department')">
                                                    <option value="0">Select Department</option>
                                                    @foreach($department_id  as $key => $y)
                                                        <option value="<?php echo $y->id ?>">
                                                            {{ $y->department_name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label pointer">Sub Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <span id="subDepartments">
                                                <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee('')">
                                                    <option value="0">Select Department</option>
                                                    @foreach($sub_department_id  as $key => $y)
                                                        <option value="<?php echo $y->id ?>">
                                                            {{ $y->sub_department_name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                </span>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Employee:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="emp_id" id="emp_id" required></select>
                                                <div id="emp_loader_1"></div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Letter:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="letter_id" id="letter_id" required>
                                                    <option value="">Select Letter</option>
                                                    {{--<option value="1">Warning Letter</option>--}}
                                                    {{--<option value="2">MFM South Increment Letter</option>--}}
                                                    {{--<option value="3">MFM South Without Increment Letter</option>--}}
                                                    {{--<option value="4">Contract Conclusion Letter</option>--}}
                                                    {{--<option value="5">Termination Letter Format 1</option>--}}
                                                    {{--<option value="6">Termination Letter Format 2</option>--}}
                                                    {{--<option value="7">Transfer Letter</option>--}}
                                                    <option value="8">Appointment Letter</option>
                                                </select>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row" id="details">
                                                </div>
                                            </div>

                                        </div>
                                        <br>
                                        <div class="row" >



                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="content1">
                                                <label>Letter Head Content</label>
                                                <textarea rows="10" class="form-control summernote1"> </textarea>
                                                <input type="hidden" id="letter_content1" name="letter_content1">
                                            </div>

                                            {{--<br>--}}
                                            {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="content2">--}}
                                                {{--<label>Other Page Content</label>--}}
                                                {{--<textarea rows="10" class="form-control summernote2"></textarea>--}}
                                                {{--<input type="hidden" id="letter_content2" name="letter_content2">--}}
                                            {{--</div>--}}
                                            <br>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Note</label>
                                                <textarea class="form-control" name="note"></textarea>
                                            </div>
                                           <div class="col-sm-4">
                                               <button id="do" type="submit" class="btn btn-sm btn-success">Submit</button>
                                           </div>


                                        </div>
                                        <br>


                                    </div>
                                </div>
                            </div>

                            <?php echo Form::close();?>
                        </div>

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

