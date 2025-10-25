<?php
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addDesignationDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Designation Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Designation Name:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="designation_name[]" id="designation_name_1" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="text-dark sf-label">Select Grade</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control  requiredField" required name="grade_id[]" id="grade_id_1">
                                    <option value="">Select Grade</option>
                                    @foreach($grade_type as $val)
                                        <option value="{{ $val->id}}">{{ $val->employee_grade_type}}</option>
                                    @endforeach
                                </select>
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
        });

        var counter = 1;
        function addMoreRow() {
            counter++;
            $('#addMoreSection').append('<div id="sectionAddMore_'+counter+'"><div class="lineHeight">&nbsp;</div>' +
                    '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label>Designation Name:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong> </span>' +
                    '<input type="text" name="designation_name[]" id="designation_name_'+counter+'" class="form-control requiredField" /></div>'+
                   
                    
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> <span><label>Select Grade:</label><a href="#" onclick="removeAddMoreSection('+counter+')" class="btn btn-sm btn-danger" style="float: right;">X</a></span>'  +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<select name="grade_id[]" id="grade_id_'+counter+'" class="form-control requiredField" required>' +
                    '<option value="">Select Grade</option>@foreach($grade_type as $key => $val)' +
                    '<option value="{{ $val->id }}">{{ $val->employee_grade_type }}</option>@endforeach</select></div></div></div>')
        }

        function removeAddMoreSection(id) {
            var elem = document.getElementById('sectionAddMore_'+id+'');
            elem.parentNode.removeChild(elem);
        }

    </script>
@endsection