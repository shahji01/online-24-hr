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
        <?php echo Form::open(array('url' => 'had/addPfWithrawalDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">PF Withdrawal Form</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintPayrollForm','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('PayrollForm','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Departments:</label>
                                            <select class="form-control" name="department_id" id="department_id" onchange="getEmployee('sub_department','single')">
                                                <option value="">Select Option</option>
                                                @foreach($departments as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Sub Department:</label>
                                            <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee('','single')">
                                                <option value="">Select Option</option>
                                                @foreach($sub_departments as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->sub_department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Location:</label>
                                            <select class="form-control" name="location_id" id="location_id" onchange="getEmployee('','single')">
                                                <option value="">Select Option</option>
                                                @foreach($locations as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->location_code.' -- '.$y->location_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Category:</label>
                                            <select class="form-control" name="category_id" id="category_id" onchange="getEmployee('','single')">
                                                <option value="">Select Option</option>
                                                @foreach($category as $key => $y)
                                                    <option value="{{ $y->id }}">{{ $y->employee_category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>



                        </div>
                        <br>
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
                                <label class="sf-label">Withdrawal Amount:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" class="form-control requiredField" value="" name="pf_withdrawal_amount" id="pf_withdrawal_amount">

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Month Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" class="form-control requiredField" value="" name="month_year" id="month_year">

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <br>
                                <!--<input style="width: 24px;height: 20px;margin-top: 8px;" type="checkbox" id="show_all" name="show_all" value="show_all">-->
                                <!--<label  for="show_all"> Show All </label>&nbsp;&nbsp;-->
                                <button  type="submit" class="btn btn-sm btn-success" style="margin-top: 18px;">Submit</button>
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


    <script>
        $(document).ready(function() {
            $('#sub_department_id').select2();
            $('#department_id').select2();
            $('#emp_id').select2();
        });



    </script>
@endsection

