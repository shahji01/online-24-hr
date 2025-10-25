<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
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
                                <h4 class="card-title">View Policies/Forms List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintPolicyList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('PolicyList','','1')?>
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


                        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="TaxesList">
                                <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Download</th>
                                <th class="text-center">Status</th>
                                <th class="text-center hidden-print">Action</th>
                                </thead>
                                <tbody>
                                <?php $counter = 1;?>
                                @if($policies->count() > 0)
                                    @foreach($policies->get() as $key => $value)
                                        <tr>
                                            <td class="text-center">{{ $counter++ }}</td>
                                            <td class="text-center">
                                                @if($value->category_id == 1)
                                                    Policy
                                                @elseif($value->category_id == 2)
                                                    Forms
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $value->title }}</td>
                                            <td class="text-center"><a target="_blank" href="{{ url('/').'/storage/'.$value->file_path}}">{{ $value->file_name }}</a></td>
                                            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                            <td class="text-center hidden-print">
                                                <button class="delete-modal btn btn-sm btn-danger" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value->id ?>','policies')">

                                                    <span class="fas fa-trash fa"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td class="text-center" colspan="6" style="color: red">No Record Found !</td></tr>
                                @endif
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

