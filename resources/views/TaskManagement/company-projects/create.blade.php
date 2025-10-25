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
                            <div class="col-sm-12">
                                <h4 class="card-title">Create Company Project Form</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'tms/company-projects/store')) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Customer Name</label>
                                        <select name="customer_id" id="customer_id" class="form-control requiredField">
                                            <option value="">Select Customer</option>
                                            @foreach($customerList as $clRow)
                                                <option value="{{$clRow->id}}">{{$clRow->customer_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Project Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="project_name" id="project_name" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Project Type</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select name="project_type" id="project_type" class="form-control">
                                            <option value="1">Local</option>
                                            <option value="2">International</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Project Owner Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="project_owner_name" id="project_owner_name" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Total Cost:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" name="total_cost" id="total_cost" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Total Working Days:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" name="total_working_days" id="total_working_days" class="form-control requiredField" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Description</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="description" class="form-control requiredField"></textarea>
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