<?php
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                {{ Form::open(array('url' => 'had/addWorkingHoursPolicyDetail')) }}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="m" value="{{$m}}">
                                <input type="hidden" name="formSection[]" id="formSection" value="1">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Working Hours Policy Name:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="working_hours_policy" class="form-control requiredField" id="working_hours_policy" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Days Off</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width:100%;" class="form-control requiredField" name="days_off_1[]" id="days_off_1" multiple>
                                                    <option value="None">None</option>
                                                    <option value="Mon">Monday</option>
                                                    <option value="Tue">Tuesday</option>
                                                    <option value="Wed">Wednesday</option>
                                                    <option value="Thu">Thursday</option>
                                                    <option value="Fri">Friday</option>
                                                    <option value="Sat" selected>Saturday</option>
                                                    <option value="Sun" selected>Sunday</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Days of Overtime or CPL</label>
                                                <select style="width:100%;" class="form-control " name="ot_cpl" id="ot_cpl">
                                                    <option value="">None</option>
                                                    <option value="holiday_and_offday">off days and holiday</option>
                                                    <option value="all_days">All days</option>

                                                </select>
                                            </div>


                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Deduction Amount Late Day:</label>
                                                <input type="number" name="late_deduction" value="0" class="form-control" id="late_deduction" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Clock In Time:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="time" name="start_working_hours_time" class="form-control requiredField" id="start_working_hours_time" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Clock Out Time:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="time" name="end_working_hours_time" class="form-control requiredField" id="end_working_hours_time" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Half Day Time (Minutes)</label>
                                                <input type="number" name="half_day_time" class="form-control" id="half_day_time" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Grace Time (Minutes)</label>
                                                <input type="number" name="working_hours_grace_time" class="form-control" id="working_hours_grace_time" min="0" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Early Going Grace Time (Minutes)</label>
                                                <input type="number" name="early_going_grace_time" class="form-control" id="early_going_grace_time" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Overtime After Minutes</label>
                                                <input type="number" name="overtime_after_minutes" class="form-control" id="overtime_after_minutes" min="0" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input class="btn btn-sm btn-success" type="submit" value="Submit">
                                        <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            // for (val in workingHoursSection) {
            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
            // }
        });

        $('#days_off_1').select2();
    </script>

@endsection
