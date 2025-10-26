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
                                <h4 class="card-title">View Bonus List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintBonusList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('BonusList','','1')?>
                                @endif
     <a href="{{ url('/hr/createBonusForm') . '?m=' . $m }}" class="btn btn-sm btn-success">
           Add Bonus
    </a>
                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id_search" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <span id="PrintBonusList">
                               <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                               <div class="table-responsive">
                                            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="BonusList">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Bonus Name</th>
                                                <th class="text-center">Amount / % Of Salary</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center hidden-print">Action</th>

                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                @foreach($bonus as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $counter++ }}</td>
                                                        <td class="text-center">{{ $value->bonus_name }}</td>
                                                        <td class="text-center">{{ $value->percent_of_salary }}</td>
                                                        <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                        <td class="text-center hidden-print">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle btn-sm btn-rounded" type="button" id="menu1" data-toggle="dropdown"><i data-feather="chevron-down"
                                                                                                                                                                          class="svg-icon"></i></button>
                                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                    @if(in_array('edit', $operation_rights))
                                                                        <li role="presentation" class="actionsLink">
                                                                            <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editBonusDetailForm','<?php echo $value->id;?>','Edit Bonus Detail','<?php echo $m; ?>')">
                                                                                Edit
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if(in_array('delete', $operation_rights))
                                                                        @if($value->status == 1)
                                                                            <li role="presentation" class="actionsLink">
                                                                                <a class="delete-modal btn" onclick="deleteRowCompanyRecords('<?php echo $m ?>','<?php echo $value->id ?>','bonus')">
                                                                                    Delete
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                    @if(in_array('repost', $operation_rights))
                                                                        @if($value->status == 2)
                                                                            <li role="presentation" class="actionsLink">
                                                                                <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $value->id ?>','bonus')">
                                                                                    Repost
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
                         </span>
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

