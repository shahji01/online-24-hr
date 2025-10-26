<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];


use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

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
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintBonuslReport','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('bonusReport','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Departments:</label>
                                            <select class="form-control" name="department_id" id="department_id" onchange="getEmployee('sub_department')">
                                                <option value="">Select Option</option>
                                                @foreach($departments as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Sub Department:</label>
                                            <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee('')">
                                                <option value="">Select Option</option>
                                                @foreach($sub_departments as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->sub_department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Location:</label>
                                            <select class="form-control" name="location_id" id="location_id" onchange="getEmployee('')">
                                                <option value="">Select Option</option>
                                                @foreach($locations as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->location_code.' -- '.$y->location_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Category:</label>
                                            <select class="form-control" name="category_id" id="category_id" onchange="getEmployee('')">
                                                <option value="">Select Option</option>
                                                @foreach($category as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->employee_category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                        </div>
                        
                        <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Employee:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="employee_id" id="employee_id">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $key => $y)
                                            <option value="{{ $y->id }}">{{ $y->emp_id.' -- '.$y->emp_name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="emp_loader"></div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Bonus Month:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="bonus_month_year" id="bonus_month_year" value="" required class="form-control requiredField" required />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <!-- <input type="checkbox" id="show_all" name="show_all" value=""> -->
                                <!-- <label for="show_all" style="margin-right: 11px;"> Show All </label> -->
                                <br /> 
                                <button type="button" class="btn btn-sm btn-primary btn_search" style="margin-top: 18px;" id="viewBonusReport" onclick="viewBonusReport()"><i id="load" class="fas fa-search fa"> </i> Search</button>
                            </div>
                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>

        <div class="row" id="employeeBonusReport" style="display: none;">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintBonuslReport">
                        <div class="row employeeBonusReport">
                            <div class="col-sm-12">

                            </div>
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

