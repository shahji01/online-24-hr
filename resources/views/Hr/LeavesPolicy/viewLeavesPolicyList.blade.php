<?php
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;
?>

@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Leaves Policy List</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="searchId" name="searchId" class="form-control" placeholder="Search..." />
                                    <input type="hidden" name="company_id" id="company_id" value="{{ $m }}">
                                </div>
                            </div>
                            <div class="col-sm-8 text-right">
                                @if(in_array('print',$operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export',$operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                                         <a href="{{ url('/hr/createLeavesPolicyForm') . '?m=' . $m }}" class="btn btn-sm btn-success">
           Add Leaves Policy
    </a>
         <a href="{{ url('/hr/createManualLeaves') . '?m=' . $m }}" class="btn btn-sm btn-success">
           Add Manual Leaves
    </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" id="printList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Leave Policy Name</th>
                                        <th class="text-center">Policy Month - Year From</th>
                                        <th class="text-center">Policy Month - Year Till</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        @foreach($leavesPolicy as $key => $value)
                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td>{{ $value->leaves_policy_name }}</td>
                                                <td class="text-center">{{ date('d-F', strtotime($value->policy_date_from)) }}</td>
                                                <td class="text-center">{{ date('d-F', strtotime($value->policy_date_till)) }}</td>
                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                                                            <i data-feather="chevron-down"
                                                               class="svg-icon"></i></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hdc/viewLeavePolicyDetail','{{ $value->id }}','View Leaves Policy ','{{ $m }}')">
                                                                <a class="delete-modal btn">
                                                                    View
                                                                </a>
                                                            </li>
                                                            <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hr/editLeavesPolicyDetailForm','{{ $value->id }}','Edit Leaves Policy Form','{{ $m }}')">
                                                                <a  class="delete-modal btn">
                                                                    Edit
                                                                </a>
                                                            </li>
                                                            @if($value->status == 2)
                                                                <li role="presentation" class="actionsLink" onclick="repostCompanyTableRecord('{{ $m }}','{{ $value->id }}','leaves_policy')">
                                                                    <a class="delete-modal btn">
                                                                        Refresh
                                                                    </a>
                                                                </li>
                                                            @else
                                                                <li role="presentation" class="actionsLink" onclick="deleteLeavesDataPolicyRows('/hdc/deleteLeavesDataPolicyRows','{{ $m }}','{{ $value->id }}')">
                                                                    <a class="delete-modal btn">
                                                                        Delete
                                                                    </a>
                                                                </li>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection