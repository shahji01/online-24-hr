<?php
$m = Input::get('m');
?>

@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addDepartmentDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Department Form</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Department Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="department_name[]" id="department_name_1" value="" class="form-control requiredField" />
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
        {{ Form::close() }}
    </div>
@endsection

@section('script')

    <script>
        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var department = new Array();
                var val;
                $("input[name='formSection[]']").each(function(){
                    department.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val in department) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
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
                    '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                    '<span><label>Department Name:</label><a href="#" onclick="removeAddMoreSection('+counter+')" class="btn btn-sm btn-danger" style="float: right;">X</a></span>' +
                    '<span class="rflabelsteric"><strong>*</strong> </span>' +
                    '<input type="text" name="department_name[]" id="department_name_'+counter+'" class="form-control requiredField" /></div></div></div>')
        }

        function removeAddMoreSection(id) {
            var elem = document.getElementById('sectionAddMore_'+id+'');
            elem.parentNode.removeChild(elem);
        }

    </script>

@endsection