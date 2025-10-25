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
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-12 text-right">

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Employee Equipments List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeEquipmentList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeeEquipmentList','','1')?>
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
                                <div class="table-responsive" id="PrintEmployeeEquipmentList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="EmployIdCardRequestList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Emp No.</th>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Department</th>

                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>

                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($employeeEquipment as  $row)
                                            <?php $sub_department_id = HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee','emp_sub_department_id', $row->emp_id, 'emp_id');
                                            ?>
                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ $row->emp_id }}</td>
                                                <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee','emp_name', $row->emp_id, 'emp_id')  }}</td>
                                                <td>{{ HrHelper::getMasterTableValueById($m, 'sub_department','sub_department_name', $sub_department_id ) }}</td>

                                                <td class="text-center">{{ HrHelper::getStatusLabel($row->status) }}</td>
                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown"><i data-feather="chevron-down"
                                                                                                                                                                  class="svg-icon"></i></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">

                                                            @if(in_array('view', $operation_rights))
                                                                <li role="presentation" class="actionsLink">
                                                                    <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hdc/viewEmployeeEquipmentsDetail','{{ $row->id }}','View Employee Equipments Detail','{{ $m }}')">
                                                                        View
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('edit', $operation_rights))
                                                                <li role="presentation" class="actionsLink">
                                                                    <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hr/editEmployeeEquipmentsDetailForm','{{ $row->id }}','Edit Employee Equipments Detail','{{ $m }}')">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('repost', $operation_rights))
                                                                @if($row->status == 2)
                                                                    <li role="presentation" class="actionsLink">
                                                                        <a class="delete-modal btn" onclick="repostCompanyTableRecord('{{ $m }}','{{ $row->id }}','employee_equipments')">
                                                                            Repost
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($row->status == 1)
                                                                    <li role="presentation" class="actionsLink">
                                                                        <a class="delete-modal btn" onclick="deleteEmployeeEquipments('{{ $m }}','{{ $row->id. '|' .$row->emp_id  }}')">
                                                                            Delete
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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

