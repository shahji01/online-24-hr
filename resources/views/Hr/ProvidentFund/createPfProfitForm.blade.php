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
        <?php echo Form::open(array('url' => 'had/addPfProfitDetail'));?>
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
                                <span class="rflabelsteric"><strong>*</strong></span>
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

                            </div>



                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Profit Amount:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" class="form-control requiredField" value="" name="pf_profit_amount" id="pf_profit_amount">

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Year From:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" class="form-control requiredField" name="year_from" value="2021" placeholder="YYYY" pattern="\d*" maxlength="4" minlength="4">
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Year To:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" class="form-control requiredField" name="year_to" value="2022" placeholder="YYYY" pattern="\d*" maxlength="4" minlength="4">
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <br>
                                <!--<input style="width: 24px;height: 20px;margin-top: 8px;" type="checkbox" id="show_all" name="show_all" value="show_all">-->
                                <!--<label  for="show_all"> Show All </label>&nbsp;&nbsp;-->
                                <button type="submit" class="btn btn-sm btn-success" style="margin-top: 12px;">Submit</button>
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


        document.querySelector("input[type=number]")
            .oninput = e => console.log(new Date(e.target.valueAsNumber, 0, 1))



    </script>
@endsection

