<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$m = Input::get('m');

$leave_type = [4 => 'Sick',1 => 'Annual',2 => 'Emergency',3 => 'Casual'];
$leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
$counter = 1;
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">User Leave Application List</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="searchId" name="searchId" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="{{ $m }}">
                                </div>
                            </div>
                            <div class="col-sm-8 text-right">
                                @if(in_array('print',$operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export',$operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" id="printList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportList">
                                        <thead>
                                        <tr>
                                            <th class="text-center">S No.</th>
                                            <th class="text-center">Leave Type</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Approval Status</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Created on</th>
                                            <th class="text-center hidden-print">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($leave_application_list))
                                            @foreach($leave_application_list as $value)
                                                <?php
                                                if($value->approval_status == 2 && $value->approval_status_lm == 2){
                                                    $approval_status_final = 2;
                                                }
                                                else if($value->approval_status == 3 && $value->approval_status_lm == 3){
                                                    $approval_status_final = 3;
                                                }
                                                else if($value->approval_status == 1 && $value->approval_status_lm == 1){
                                                    $approval_status_final = 1;
                                                }
                                                else{
                                                    $approval_status_final = 1;
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center">{{ $counter++ }}</td>
                                                    <td>{{ $leave_type[$value->leave_type] }}</td>
                                                    <td>
                                                        @if($value->name == '')
                                                            {{ $leave_day_type[$value->leave_day_type] }}
                                                        @else
                                                            {{ $value->no_of_days." Day Leave" }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{ HrHelper::getApprovalStatusLabel($approval_status_final) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ HrHelper::getStatusLabel($value->status) }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if($value->name=='')
                                                            {{ HrHelper::date_format($value->date) }}
                                                        @else
                                                            <?php
                                                            $explode_month_name=explode('-',$value->name);
                                                            $month_name = date("F", mktime(0, 0, 0, $explode_month_name[1], 10)); ?>
                                                            {{ $month_name }} Generated Leave
                                                        @endif
                                                    </td>
                                                    <td class="text-center hidden-print">
                                                        @if($value->name=='')
                                                            @if($value->approval_status_lm != 2 || Auth::user()->acc_type == 'client')
                                                                <button onclick="showDetailModelFourParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->id;?>','Edit Leave Application Detail','<?php echo $m; ?>')" class="btn btn-sm btn-info" type="button">
                                                                    <span class="fas fa-edit"></span>
                                                                </button>
                                                            @endif
                                                            <button onclick="getLeavesData('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$value->leave_type?>')" class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample<?=$value->id?>" aria-expanded="false" aria-controls="collapseExample">
                                                                <span class="fas fa-eye"></span>
                                                            </button>
                                                        @endif
                                                        @if($value->approval_status_lm != 2 || Auth::user()->acc_type == 'client')
                                                            <button onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-sm btn-danger " type="button"><span class="fas fa-trash"></span></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                            <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center text-danger">
                                                    <strong>No Record Found</strong>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection