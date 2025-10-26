<?php
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="printList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">EOBI List</h4>
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
                            <a href="{{ url('/hr/createEOBIForm') . '?m=' . $m }}" class="btn btn-sm btn-success">
           Add EOBI
    </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" id="printList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="exportList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">EOBI Name</th>
                                        <th class="text-center">EOBI Amount</th>
                                        <th class="text-center">Year & Month </th>
                                        <th class="text-center">Created By</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center hidden-print">Action</th>

                                        </thead>
                                        <tbody id="tableData"></tbody>
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