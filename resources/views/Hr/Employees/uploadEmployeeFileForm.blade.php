<?php
use App\Helpers\CommonHelper;
$m = Input::get('m');
$user_roles = CommonHelper::userRoles($m);
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                              {{-- <div class="col-sm-4">
                                <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div> --}}
                            <div class="col-sm-12" >
                                <h4 class="card-title" style="display: flex; justify-content: space-between;">{{$breadCrums['heading']}}  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
                                        <a class="btn btn-sm btn-primary" href="{{ url('/') }}/assets/sample_images/employee_samples.xlsx">Download Sample / Format </a>
                                    </div></h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'had/uploadEmployeeFileDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data")) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="{{ $m }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="text-dark">File: </label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input required type="file" name="employeeFile" id="employeeFile" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Location</label>
                                        <span class="rflabelsteric">*</span>
                                        <select class="form-control requiredField" id="location_id" name="location_id">
                                            <option value="">Select Location</option>
                                            @foreach($locations as $key => $val)
                                                @if(in_array($val->id, $user_roles['location_ids']))
                                                    <option value="{{ $val->id }}">{{ $val->location_code.' -- '.$val->location_name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="location_loader"></span>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 40px;">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success','id'=>'btn_add']) }}
                                    </div>
                                   
                                </div>
                                <br>
                                @if(Session::has('errorMsg'))
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">&nbsp;
                                            <div class="alert-danger" style="font-size: 18px; color: white"><span class="fas fa-wa"></span><em> {!! session('errorMsg') !!}</em></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection