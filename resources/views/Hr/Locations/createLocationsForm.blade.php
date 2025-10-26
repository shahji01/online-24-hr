<?php
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addLocationsDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Location Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Location Name</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" class="form-control requiredField" name="location_name[]" id="location_name_1" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Location Code</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" class="form-control requiredField" name="location_code[]" id="location_code_1" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">City</label>
                                <select name="city_id[]" id="city_id_1" class="form-control">
                                    <option value="">Select City</option>
                                    @foreach($cities as $key => $val)
                                        <option value="{{ $val->id }}">{{ $val->city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Fuel Amount</label>
                                <input type="number" step="any" class="form-control" name="fuel_amount[]" id="fuel_amount_1" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Latitude</label>
                                <input type="number" step="any" class="form-control" name="latitude[]" id="latitude_1" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Longitude</label>
                                <input type="number" step="any" class="form-control" name="longitude[]" id="longitude_1" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Address</label>
                                <textarea class="form-control" name="address[]" id="address_1"></textarea>
                            </div>
                        </div>
                        <div id="addMoreSection"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary" value="Add More" onclick="addMoreRow()" />
                              <a href="{{ url('/hr/viewLocationsList') . '?m=' . $m }}" class="btn btn-sm btn-info">
           View Location List
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

@section('script')
    <script>

        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var designation = new Array();
                var val;
                $("input[name='formSection[]']").each(function(){
                    designation.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val in designation) {

                    jqueryValidationCustom();
                    if(validate == 0){

                    }else{
                        return false;
                    }
                }
            });
            $("#city_id_1").select2();

        });

        var counter = 1;
        function addMoreRow() {
            counter++;
            let newSection = `
                <div id="sectionAddMore_${counter}">
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row" style="float: right">
                        <a href="#" onclick="removeAddMoreSection(${counter})" class="btn btn-sm btn-danger">X</a>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Location Name</label>
                            <span class="rflabelsteric"><strong>*</strong></span> 
                            <input type="text" class="form-control requiredField" name="location_name[]" id="location_name_${counter}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Location Code</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" class="form-control requiredField" name="location_code[]" id="location_code_${counter}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">City</label>
                            <select name="city_id[]" id="city_id_${counter}" class="form-control">
                                <option value="">Select City</option>
                                @foreach($cities as $key => $val)
                                    <option value="{{ $val->id }}">{{ $val->city }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Fuel Amount</label>
                            <input type="number" class="form-control" step="any" name="fuel_amount[]" id="fuel_amount_${counter}" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Longitude</label>
                            <input type="number" class="form-control" step="any" name="longitude[]" id="longitude_${counter}" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Latitude</label>
                            <input type="number" class="form-control" step="any" name="latitude[]" id="latitude_${counter}" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Address</label>
                            <textarea class="form-control" name="address[]" id="address_${counter}"></textarea>
                        </div>
                    </div>
                </div>`;

            $('#addMoreSection').append(newSection);
            $(`#city_id_${counter}`).select2();
        }


        function removeAddMoreSection(id) {
            var elem = document.getElementById('sectionAddMore_'+id+'');
            elem.parentNode.removeChild(elem);
        }

    </script>
@endsection