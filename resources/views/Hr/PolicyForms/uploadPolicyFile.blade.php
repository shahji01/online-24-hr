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
                                <h4 class="card-title">Upload Policies / File</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'had/uploadPolicyFileDetail',"enctype"=>"multipart/form-data")) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Category</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="category_id" id="category_id" class="form-control requiredField">
                                    <option value="">Select Category</option>
                                    <option value="1">Policy</option>
                                    <option value="2">Forms</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Policies / Forms Title</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="title" id="title" value="" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>File</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="file" name="policy_file[]" id="policy_file" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 40px">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $(".btn-success").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='formSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val in employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }
            });
            $("#category_id").select2();

        });
    </script>
@endsection