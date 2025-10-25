<?php
$accType = Auth::user()->acc_type;
use App\Helpers\HrHelper;
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
        <div class="row" style="margin-bottom: 10px;">

        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/addManuallyLeaves'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="<?= Input::get('m') ?>">
        <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Create Manual Leaves</h4>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
                                    <option value="0">Select Department</option>
                                    @foreach($department_id  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Sub Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                    <option value="0">Select Department</option>
                                    @foreach($sub_department_id  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->sub_department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="emp_id" id="emp_id" required></select>
                                <div id="emp_loader_1"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Casual:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="casual_leaves" id="casual_leaves" value="" class="form-control requiredField" required onchange="checkManualLeaves(this.value,'3','casual-leaves','<?php echo $m ?>','casual_leaves')" />
                                <span class="rflabelsteric" id="casual-leaves"></span>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Sick:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="sick_leaves" id="sick_leaves" value="" class="form-control requiredField" required onchange="checkManualLeaves(this.value,'2','sick-leaves','<?php echo $m ?>','sick_leaves')" />
                                <span class="rflabelsteric" id="sick-leaves"></span>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Annual:</label>
                                <span class="rflabelsteric"></span>
                                <input type="number" name="annual_leaves" id="annual_leaves" value="" class="form-control requiredField" required onchange="checkManualLeaves(this.value,'1','annuals-leaves','<?php echo $m ?>','annual_leaves')" />
                                <span class="rflabelsteric" id="annuals-leaves"></span>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <input type="submit" class="btn btn-sm btn-primary" id="create" value="Submit" style="margin-top: 32px;" />
                            </div>
                        </div>
                    </div>
                        <div class="text-center ajax-loader"></div>
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

