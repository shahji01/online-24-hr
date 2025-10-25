<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

?>
@extends('layouts.default')
@section('content')
    <div class="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="card-title">View Company Project List</h4>
                            </div>
                        </div>
                        <hr>
                        <form id="list_data" method="get" action="{{ route('company-projects.index') }}">
                            <input type="hidden" name="company_id" id="company_id" value="{{$m}}" />
                            <input type="hidden" name="customer_id" id="customer_id" value="{{Auth::user()->customer_id}}" />
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Project Type</label>
                                    <select name="filterProjectType" id="filterProjectType" class="form-control select2">
                                        <option value="">All Project Type</option>
                                        <option value="1">Local</option>
                                        <option value="2">International</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Project Status</label>
                                    <select name="filterProjectStatus" id="filterProjectStatus" class="form-control select2">
                                        <option value="">All Project Status</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Completed</option>
                                        <option value="3">Hold</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Status</label>
                                    <select name="filterStatus" id="filterStatus" class="form-control select2">
                                        <option value="">All Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">InActive</option>
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="padding: 30px;">
                                    <input type="button" id="filter-button" value="Filter" onclick="dataCall()" class="btn btn-xs btn-success" />
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive wrapper">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover data-table" id="ExportCompanyProjectList">
                                        <thead>
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Customer Name</th>
                                                <th class="text-center">Project Name</th>
                                                <th class="text-center">Project Type</th>
                                                <th class="text-center">Project Owner Name</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">Total Cost</th>
                                                <th class="text-center">Total Working Days</th>
                                                <th class="text-center">Project Status</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center hidden-print">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data">
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
    <script>
        function dataCall(){
            var columnTable = [
                { data: 'id', title: 'ID' },
                { data: 'customer_name', title: 'Customer Name' },
                { data: 'project_name', title: 'Project Name' },
                { data: 'project_type', title: 'Project Type'},
                { data: 'project_owner_name', title: 'Project Owner Name' },
                { data: 'description', title: 'Description' },
                { data: 'total_cost', title: 'Total Cost',class:'text-right' },
                { data: 'total_working_days', title: 'Total Working Days',class:'text-center' },
                { data: 'project_status', title: 'Project Status' },
                { data: 'status', title: 'Status'},
                {data: 'action',title: 'Action', class:'hidden-print'}
            ];
            get_ajax_data_two('ExportCompanyProjectList',columnTable);
        }
        dataCall();
    </script>
@endsection