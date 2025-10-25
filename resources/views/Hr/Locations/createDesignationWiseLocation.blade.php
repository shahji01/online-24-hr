<?php
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addDesignationWiseLocationDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Designation Wise Location Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Location Name</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="location_name[]" id="location_name_1" >
                                    <option>Select Location</option>
                                    @foreach(DB::table('locations')->where('status',1)->get() as $val)
                                        <option value="{{ $val->id }}"> {{ $val->location_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Designation Name</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="designation_name[]" id="designation_name_1" >
                                    <option>Select Designation</option>
                                    @foreach(DB::table('designation')->where('status',1)->get() as $val)
                                        <option value="{{ $val->id }}"> {{ $val->designation_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Fuel Amount</label>
                                <input type="number" step="any" class="form-control" name="fuel_amount[]" id="fuel_amount_1" />
                            </div>
                           
                        </div>
                        
                        <div id="addMoreSection"></div>
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
            $(`#location_name_1`).select2();
            $(`#designation_name_1`).select2();

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
                            <select class="form-control requiredField" name="location_name[]" id="location_name_${counter}" >
                                <option>Select Location</option>
                                @foreach(DB::table('locations')->where('status',1)->get() as $val)
                                    <option value="{{ $val->id }}"> {{ $val->location_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Designation Name</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" name="designation_name[]" id="designation_name_${counter}" >
                                <option>Select Designation</option>
                                @foreach(DB::table('designation')->where('status',1)->get() as $val)
                                    <option value="{{ $val->id }}"> {{ $val->designation_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Fuel Amount</label>
                            <input type="number" class="form-control" step="any" name="fuel_amount[]" id="fuel_amount_${counter}" />
                        </div>
                        
                    </div>
                </div>`;

            $('#addMoreSection').append(newSection);
            $(`#location_name_${counter}`).select2();
            $(`#designation_name_${counter}`).select2();
        }


        function removeAddMoreSection(id) {
            var elem = document.getElementById('sectionAddMore_'+id+'');
            elem.parentNode.removeChild(elem);
        }

    </script>
@endsection