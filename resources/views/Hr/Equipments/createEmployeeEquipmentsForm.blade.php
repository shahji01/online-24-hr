<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addEmployeeEquipmentDetail',"enctype"=>"multipart/form-data")) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="company_id" value="{{ $m }}" />
        <input type="hidden" name="employeeSection[]" value="1" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Create Employee Equipment Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control " name="department_id" id="department_id" onchange="getEmployee()" >
                                    <option value="">Select Deparment</option>
                                    @foreach($departments as $key => $y)
                                        <option value="{{ $y->id }}"> {{ $y->department_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Sub Deparment</label>
                                <span class="rflabelsteric"><strong></strong></span>
                                <select class="form-control requiredField" name="sub_department_id_1" id="sub_department_id" onchange="getEmployee()">
                                    <option value="">Select Sub Department</option>
                                    @foreach($sub_department as $key => $y)
                                        <option value="{{ $y->id }}>">{{ $y->sub_department_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="emp_id" id="emp_id" required>
                                    <option value="">-</option>
                                </select>
                                <div id="emp_loader_1"></div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 30px;">
                                <a class=" btn btn-primary btn-sm btn_search" onclick="viewEmployeeEquipmentsForm()" style="color: white;cursor: pointer;"><i id="load" class="fas fa-search fa"></i> Search</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <span id="equipmentSection" style="display:none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintregionWisePayrollReport">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="equipmentSectionLoader"></div>
                                <div class="equipmentSection"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </span>
    </div>
@endsection