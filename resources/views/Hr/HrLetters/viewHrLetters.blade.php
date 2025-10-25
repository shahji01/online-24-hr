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

        <?php echo Form::open(array('url' => 'had/addHrLetters','id'=>'HrLetters', 'method' => 'post'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="show_all_status" id="show_all_status" >
        <input type="hidden" name="company_id" value="<?=$m?>">
        <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Hr Letters</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintHrLetter','','1');?>
                                <?php echo CommonHelper::displayExportButton('HrLetter','','1')?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
                                    <option value="0">Select Department</option>
                                    @foreach($Department  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Sub Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                    <option value="0">Select Department</option>
                                    @foreach($SubDepartment  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->sub_department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="emp_id" id="emp_id" >
                                    <option value="0">-</option>
                                </select>
                                <div id="emp_loader_1"></div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Letter:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="letter_id" id="letter_id" required>
                                    <option value="">Select Letters</option>
                                    {{--<option value="1">Warning Letter</option>--}}
                                    {{--<option value="2">MFM South Increment Letter</option>--}}
                                    {{--<option value="3">MFM South Without Increment Letter</option>--}}
                                    {{--<option value="4">Contract Conclusion Letter</option>--}}
                                    {{--<option value="5">Termination Letter Format 1</option>--}}
                                    {{--<option value="6">Termination Letter Format 2</option>--}}
                                    {{--<option value="7">Transfer Letter</option>--}}
                                    <option value="8">Appointment Offer Letter</option>
                                </select>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 text-right">
                                <label for="show_all">Show All</label>
                                <input type="checkbox"class="checkboxs" id="show_all" name="show_all" value="1" style="width: 20px;height: 20px;">
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">
                                <button class="btn btn-primary btn-sm btn_search" id="search" onclick="searchLetters()" type="button"><i id="load" class="fas fa-search fa"></i> Search</button>
                            </div>
                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>
        <span id="letterSection" style="display: none;">




            <div class="col-12">
                <div class="card">
                    <div class="card-body" >
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="dataLoader"></div>
                                <div class="letterSection"></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


        </span>
        <?php echo Form::close();?>

        </div>





@endsection

