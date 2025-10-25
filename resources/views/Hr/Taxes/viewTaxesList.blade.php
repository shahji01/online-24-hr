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
                                <h4 class="card-title">View Tax List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintTaxesList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('TaxesList','','1')?>
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
                        <div class="table-responsive" id="PrintTaxesList">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="TaxesList">
                                <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Tax Name</th>
                                <th class="text-center">Tax Year</th>
                                <th class="text-center">Status</th>
                                <th class="text-center hidden-print">Action</th>
                                </thead>
                                <tbody>
                                <?php $counter = 1;?>
                                @foreach($tax as $key => $value)
                                    <?php $tax_year = explode('-',$value->tax_month_year) ?>
                                    <tr>
                                        <td class="text-center">{{ $counter++ }}</td>
                                        <td class="text-center">{{ $value->tax_name }}</td>
                                        <td class="text-center">{{ $tax_year[0] }}</td>
                                        <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                        <td class="text-center hidden-print">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle btn-sm btn-rounded" type="button" id="menu1" data-toggle="dropdown"><i data-feather="chevron-down"
                                                                                                                                                          class="svg-icon"></i><span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                    <li role="presentation">
                                                        <a class="edit-modal btn" onclick="showMasterTableEditModel('hdc/viewTaxesDetail','{{ $value->id }}','View Tax Detail','')">
                                                            View
                                                        </a>
                                                    </li>

                                                    @if(in_array('edit', $operation_rights))
                                                        <li role="presentation">
                                                            <a class="edit-modal btn" onclick="showMasterTableEditModel('hr/editTaxesDetailForm','{{ $value->id }}','Taxes Edit Detail Form','')">
                                                                Edit
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(in_array('repost', $operation_rights))
                                                        @if($value->status == 2)
                                                            <li role="presentation">
                                                                <a class="delete-modal btn" onclick="repostMasterTableRecords('{{ $value->id }}','tax')">
                                                                    Repost
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete', $operation_rights))
                                                        @if($value->status == 1)
                                                            <li role="presentation">
                                                                <a class="delete-modal btn" onclick="deleteRowMasterTable('{{ $value->id }}','tax')">
                                                                    Delete
                                                                </a>
                                                            </li>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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

