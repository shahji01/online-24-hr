<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//	$m = $_GET['m'];
//}else{
//	$m = Auth::user()->company_id;
//}

$m = $_GET['m'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Department;

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
                                <h4 class="card-title">View Employee Trainings List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print',$operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintSubDepartmentList','','1');?>
                                @endif
                                @if(in_array('export',$operation_rights))
                                    <?php echo CommonHelper::displayExportButton('SubDepartmentList','','1')?>
                                @endif
                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <span id="PrintSubDepartmentList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="AdvancedSalayList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Emp Id</th>
                                        <th class="text-center">Emp Name</th>
                                        <th class="text-center">Training Topic</th>
                                        <th class="text-center">Training Hours</th>
                                        <th class="text-center">Training Date</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @if(count($trainingsData) > 0)
                                        @foreach($trainingsData as $key => $y)
                                            <tr>
                                                <td class="text-center"><?php echo $counter++;?></td>
                                                <td class="text-center">
                                                    <?php echo $y->emp_id;?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $y->emp_name;?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $y->topic_name;?>
                                                </td>
                                                <td class="text-center"> 
                                                    <?php echo $y->training_hours;?>
                                                </td>
                                                <td class="text-center"> 
                                                    <?php echo HrHelper::date_format($y->training_date);?>
                                                </td>
                                                <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
                                                <td class="text-center hidden-print">
                                                    <!--@if(in_array('edit',$operation_rights))-->
                                                    <!--    <button class="edit-modal btn btn-sm btn-info" onclick="showMasterTableEditModel('hr/editTrainingDetailForm','<?php echo $y->id ?>','Training Edit Detail Form','<?php echo $m?>')">-->
                                                    <!--        <span class="fas fa-edit"></span>-->
                                                    <!--    </button>-->
                                                    <!--@endif-->
                                                    @if(in_array('repost',$operation_rights))
                                                        @if($y->status == 2)
                                                            <button class="delete-modal btn btn-sm btn-primary" onclick="repostMasterTableRecords('<?php echo $y->id ?>','trainings')">
                                                                <span class="fas fa-refresh"></span>
                                                            </button>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete',$operation_rights))
                                                        @if($y->status == 1)
                                                            <button class="delete-modal btn btn-sm btn-danger" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $y->id ?>','employee_training')">
                                                                <span class="fas fa-trash"></span>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td class="text-danger text-center" colspan="8">No record found</td>
                                            </tr>  
                                        @endif
                                        </tbody>
                                    </table>
                                    </span>
                                </div>
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

