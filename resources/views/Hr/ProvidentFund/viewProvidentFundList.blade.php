<?php
$accType = Auth::user()->acc_type;
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Provident Fund List</h4>
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

                        <span id="PrintTaxesList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                                        <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="TaxesList">
                                                    <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Provident Fund Name</th>
                                                    <th class="text-center">PF Mode</th>
                                                    <th class="text-center">% / Amount</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center hidden-print">Action</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php $counter = 1;?>
                                                    @foreach($provident_fund as $value)
                                                        <tr>
                                                            <td class="text-center"><?php echo $counter++;?></td>
                                                            <td class="text-center"><?php echo $value->name?></td>
                                                            <td class="text-center"><?php echo $value->pf_mode?></td>
                                                            <td class="text-center"><?php echo $value->amount_percent?></td>
                                                            <td class="text-center"><?php echo HrHelper::getStatusLabel($value->status);?></td>
                                                            <td class="text-center hidden-print">

                                                            <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
																<i data-feather="chevron-down"
                                                                   class="svg-icon"></i></button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                <div class="dropdown">
                                                                <li role="presentation" class="actionsLink" onclick="showMasterTableEditModel('hr/editProvidentFundDetail','<?php echo $value->id; ?>','Provident Fund Detail Form','<?php echo $m?>')">
																		<a class="delete-modal btn">
																			Edit
																		</a>
																	</li>
																 @if($value->status == 2)
                                                                    <li role="presentation" class="actionsLink" onclick="repostMasterTableRecords('<?php echo $value->id ?>','provident_fund')">
																		<a class="delete-modal btn">
																			Refresh
																		</a>
																	</li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                         <li role="presentation" class="actionsLink" onclick="deleteRowMasterTable('<?php echo $value->id ?>','provident_fund')">
																		<a class="delete-modal btn">
																			Remove
																		</a>
																	</li>
                                                                @endif

                                                            </div>
															</ul>


                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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

