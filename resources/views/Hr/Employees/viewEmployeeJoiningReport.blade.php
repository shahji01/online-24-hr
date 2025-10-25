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
        <?php echo Form::open(array('url' => 'had/createEmployeeJoiningLetter'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Employee Joining Report</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('JoiningLetterForm','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('JoiningLetter','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%;" class="form-control requiredField" name="department_id" id="department_id" onchange="getEmployee('sub_department')">
                                    <option value="0">Select Department</option>
                                    @foreach($Department  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Sub Department</label>

                                <span id="subDepartments">
                                <select style="width: 100%;" class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee('')">
                                    <option value="0">Select Department</option>
                                    @foreach($SubDepartment  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->sub_department_name}}
                                        </option>
                                    @endforeach
                                </select>
                                </span>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%;" class="form-control requiredField" name="emp_id" id="emp_id" >
                                    <option value="0">-</option>
                                </select>
                                <div id="emp_loader_1"></div>
                            </div>


                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Designation</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%;" class="form-control requiredField" name="designation_id" id="designation_id">
                                    <option value="0">Select Designations</option>
                                    @foreach($designations  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->designation_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Date</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="date" id="date" class="form-control requiredField">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                <br>
                                <button type="button" style="margin-top: 13px;" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="createEmployeeJoiningLetter()"><i id="load" class="fas fa-search fa"> Search</i></button>
                            </div>

                        </div>

                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>

        <span id="employeePayslipSection" style="display:none;">


        <div class="row">

            <div class="col-12">
                <div class="card" id="JoiningLetter">
                    <div class="card-body" id="JoiningLetterForm">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="JoiningLetterForm"></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        </span>
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


        $(document).ready(function(){


            $('#sub_department_id').select2();
            $('#emp_id').select2();
            $('#department_id').select2();
            $('#designation_id').select2();

        });



    </script>

@endsection

