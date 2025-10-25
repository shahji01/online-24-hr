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
                            <div class="col-sm-6">
                                <h4 class="card-title mb-0">View Project Sub Task List</h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                <a class="btn btn-xs btn-primary" href="{{ url('/tms/project-sub-tasks/create') . '?m=' . $m }}">Create New Project Sub Task</a>
                            </div>
                        </div>
                        <hr>
                        <form id="list_data" method="get" action="{{ route('project-sub-task.index') }}">
                            <input type="hidden" name="m" id="m" value="{{$m}}" />
                            <input type="hidden" name="customer_id" id="customer_id" value="{{Auth::user()->customer_id}}" />
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Employee Name</label>
                                    <select name="filterEmployeeId[]" id="filterEmployeeId" class="form-control select2" multiple style="width: 100%;">
                                        @foreach($employeeList as $elRow)
                                            <option value="{{$elRow->id}}">{{$elRow->emp_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Project Name</label>
                                    <select name="filterProjectId[]" id="filterProjectId" class="form-control select2" multiple style="width: 100%;">
                                        @foreach($projectList as $plRow)
                                            <option value="{{$plRow->id}}">{{$plRow->project_name}} - {{$plRow->project_owner_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-sm-2 col-xs-12">
                                    <label>Project Task</label>
                                    <select name="filterTaskId" id="filterTaskId" class="form-control select2" multiple style="width: 100%;">
                                        @foreach($projectTaskList as $ptlRow)
                                            <option value="{{$ptlRow->id}}">{{$ptlRow->task_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Task Status</label>
                                    <select name="filterSubTaskStatus[]" id="filterSubTaskStatus" class="form-control select2" multiple style="width: 100%;">
                                        <option value="1">Pending</option>
                                        <option value="2">Completed</option>
                                        <option value="3">Hold</option>
                                        <option value="4">Inprogress</option>
                                        <option value="5">Testing</option>
                                        <option value="6">Reassign</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Status</label>
                                    <select name="filterStatus" id="filterStatus" class="form-control select2" multiple style="width: 100%;">
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
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover data-table" id="ExportProjectTaskList">
                                        <thead>
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Project Name</th>
                                                <th class="text-center">Project Type</th>
                                                <th class="text-center">Employee Name</th>
                                                <th class="text-center">Task Name</th>
                                                <th class="text-center">Sub Task Name</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">Task Status</th>
                                                <th class="text-center">Estimated Days</th>
                                                <th class="text-center">Start Date</th>
                                                <th class="text-center">End Date</th>
                                                <th class="text-center">Total Days</th>
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
        $('.select2').select2();
        function dataCall(){
            var columnTable = [
                { data: 'id', title: 'ID' },
                { data: 'project_name', title: 'Project Name' },
                { data: 'project_type', title: 'Project Type'},
                { data: 'emp_name', title: 'Employee Name' },
                { data: 'task_name', title: 'Task Name' },
                { data: 'sub_task_name', title: 'Sub Task Name' },
                { data: 'description', title: 'Description' },
                { data: 'sub_task_status', title: 'Sub Task Status' },
                { data: 'num_days', title: 'Estimated Days', class: 'text-center'},
                { data: 'start_date', title: 'Start Date', class: 'text-center'},
                { data: 'end_date', title: 'End Date', class: 'text-center'},
                { data: 'total_days', title: 'Total Days', class: 'text-center'},
                { data: 'status', title: 'Status'},
                {data: 'action',title: 'Action', class:'text-center hidden-print'}
            ];
            get_ajax_data_two('ExportProjectTaskList',columnTable);
        }
        dataCall();
    </script>
@endsection