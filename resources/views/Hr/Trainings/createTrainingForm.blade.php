<?php
$m = Input::get('m');
$currentDate = date('Y-m-d');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addTrainingDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Training Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Training Topic:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="training_topic" id="training_topic" value="" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Training Hours:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="training_hour" id="training_hour" value="" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Training Date:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="training_date" id="training_date" value="" class="form-control requiredField" />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection