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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="card-title mb-0">Create Company Project Task Form</h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                <a class="btn btn-xs btn-primary" href="{{ url('/tms/project-tasks') . '?m=' . $m }}">View List</a>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'tms/project-tasks/store')) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Project Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control" name="project_id" required id="project_id">
                                            <option value="">Select Project Name</option>
                                            @foreach($projectList as $plRow)
                                                <option value="{{$plRow->id}}">{{$plRow->project_name}} - {{$plRow->project_owner_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Employee Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control" name="employee_id" required id="employee_id">
                                            <option value="">Select Employee</option>
                                            @foreach($employeeList as $elRow)
                                                <option value="{{$elRow->id}}">{{$elRow->emp_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Task Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="task_name" id="task_name" required class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">No of Working Days:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" name="no_of_working_days" required id="no_of_working_days" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Task Type</label>
                                        <select name="task_type" id="task_type" required class="form-control requiredField">
                                            <option value="1">Normal</option>
                                            <option value="2">Urgent</option>
                                            <option value="3">Average</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Description</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="description" required class="form-control requiredField"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="addMoreSection"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection