<?php
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addEquipmentDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Employees Equipments Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Equipment Name:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="equipment_name[]" id="equipment_name" class="form-control requiredField" required />
                            </div>
                        </div>
                        <div id="addMoreSection"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary" value="Add More" onclick="addMoreRow()" />
                              <a href="{{ url('/hr/viewEquipmentsList') . '?m=' . $m }}" class="btn btn-sm btn-info">
           View Equipments List
    </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection