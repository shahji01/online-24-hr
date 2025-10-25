<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
//$parentCode = $_GET['parentCode'];

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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="card-title">Employee of the Month</h4>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintLoanReport','','1');?>
                                    <?php echo CommonHelper::displayExportButton('LoanReport','','1')?>
                                </div>
                            </div>

                            <hr>
                            <div class="panel">
                                <div class="panel-body">
                                    <?php echo Form::open(array('url' => 'had/addEmployeeOfTheMonthDetail','id'=>'employeeForm'));?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="m" value="<?php echo Input::get('m')?>">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Department</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width:100%;" class="form-control requiredField" name="department_id" id="department_id" onchange="getEmployee()">
                                                <option value="">Select Department</option>
                                                @foreach($department_id  as $key => $y)
                                                    <option value="<?php echo $y->id ?>">
                                                        {{ $y->department_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Sub Department</label>
                                            <span class="rflabelsteric"><strong></strong></span>
                                            <select style="width:100%;" class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()" >
                                                <option value="">Select Department</option>
                                                @foreach($sub_department_id as $key => $y)
                                                    <option value="<?php echo $y->id ?>">
                                                        {{ $y->sub_department_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Employee:</label>
                                            <span  class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width:100%;" class="form-control requiredField" name="emp_id" id="emp_id" required>
                                                <option value="">-</option>
                                            </select>
                                            <div id="emp_loader_1"></div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label>Month-Year</label>
                                            <input type="month" name="month_year" id="month_year" class="form-control requiredField" value="">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
                                            <label class="sf-label">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control requiredField"></textarea>
                                            <br>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success','id'=>'btn_add']) }}
                                        </div>
                                    </div>
                                    <?php echo Form::close();?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintLoanReport">

                        <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                        <div class="table-responsive" id="LoanReport">
                            <table class="table table-bordered table-striped" id="exportLateArrivals">
                                <thead>
                                <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Emp ID.</th>
                                <th scope="col">Emp Name</th>
                                <th scope="col">Month - Year</th>
                                <th scope="col">Remarks</th>
                                <th class="hidden-print" scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $count=1; ?>
                                @foreach($employeeOfTheMonth as $value)
                                    <tr">
                                        <td class="text-center"><span class="badge badge-pill badge-secondary">{{$count++}}</span></td>
                                        <td class="text-center">{{$value->emp_id}}</td>
                                        <td class="text-center">{{\App\Helpers\HrHelper::getCompanyTableValueByIdAndColumn(Input::get("m"),'employee','emp_name',$value->emp_id,'emp_id')}}</td>
                                        <td class="text-center"><?php
                                            $dateObj   = DateTime::createFromFormat('!m', $value->month);
                                            $monthName = $dateObj->format('F');
                                            echo $monthName."-".$value->year ?></td>
                                        <td class="">{{$value->remarks}}</td>
                                        <td class="text-center  hidden-print">
                                            @if($value->status == 2)
                                                <button type="button" class="delete-modal btn btn-primary btn-sm" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $value->id ?>','employee_of_the_month')">
                                                    <i class="fas fa fa-refresh"></i>
                                                </button>
                                            @else
                                                <button type="button" class="delete-modal btn btn-danger btn-sm" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value->id ?>','employee_of_the_month')">
                                                    <i class="fas fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>


                        <div class="text-center ajax-loader"></div>
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

