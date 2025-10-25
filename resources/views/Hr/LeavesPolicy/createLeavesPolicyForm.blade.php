<?php
$m = Input::get('m');
$count=count($leaves_types)
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addLeavesPolicyDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
        <input type="hidden" id="count" value="{{ $count }}"/>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Leaves Policy Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Leaves Policy Name:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="leaves_policy_name" id="leaves_policy_name" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Policy Date from:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="policy_date_from" id="policy_date_from" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Policy Date till:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="policy_date_till" id="policy_date_till" class="form-control requiredField"  />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Full Day Deduction Rate:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select readonly class="form-control requiredField" name="full_day_deduction_rate" id="full_day_deduction_rate">
                                    <option selected value="1">1 (Day)</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Half Day Deduction Rate:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select readonly class="form-control requiredField" id="half_day_deduction_rate" name="half_day_deduction_rate">
                                    <option selected value="0.5">0.5 (Day)</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Short Leave Deduction Rate:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select readonly class="form-control requiredField" id="per_hour_deduction_rate" name="per_hour_deduction_rate">
                                    <option selected value="0.25">0.25 (Day)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Leaves Type:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="leaves_type_id[]" id="leaves_type_id_1" class="form-control requiredField test">
                                    <option value="">Select</option>
                                    @foreach($leaves_types as $value)
                                        <option value="{{ $value->id }}">{{ $value->leave_type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">No. of Leaves:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input onkeyup="LeavesCount()" type="number" name="no_of_leaves[]" id="no_of_leaves_1" class="form-control requiredField getLeaves" />
                            </div>
                        </div>
                        <div id="addMoreSection"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><b>Total</b></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <input readonly id="totalLeaves" name="totalLeaves" type="text" class="form-control requiredField"/>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary" value="Add More" onclick="addMoreRow()" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var counter = 1;
        function addMoreRow() {
            counter++;

            $('#addMoreSection').append('<div id="sectionAddMore_'+counter+'"><div class="lineHeight">&nbsp;</div>' +
                    '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label>Leaves Type:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong> </span>' +
                    '<select class="form-control requiredField" name="leaves_type_id[]" id="leaves_type_id_'+counter+'">' +
                    '<option value="">Select</option>' +
                    '@foreach($leaves_types as $value)<option value="{{ $value->id }}">{{ $value->leave_type_name }}</option>@endforeach</select></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><span><label>No. of Leaves:</label><a href="#" onclick="removeAddMoreSection('+counter+')" class="btn btn-sm btn-danger" style="float: right;">X</a></span>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input onkeyup="LeavesCount()" type="number" name="no_of_leaves[]" id="no_of_leaves_'+counter+'" class="form-control requiredField getLeaves" />' +
                    '</div></div></div>');
            $('#leaves_type_id_'+counter+'').select2();
        }

        function removeAddMoreSection(id) {
            var elem = document.getElementById('sectionAddMore_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection