<?php
$m = Input::get('m');
?>

@section('css')
    <style>
        .card-body{
            min-height:170px;
        }
    </style>
@endsection
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
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                                <button class="btn btn-sm btn-primary" id="viewUploadAttendanceFileForm" >Upload Attendance File</button>
                                &nbsp;&nbsp;
                                <button class="btn btn-sm btn-primary" id="viewManualAttendanceForm" >Add Manual Attendance</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="addMoreSection"></div>
        <div class="row">&nbsp;</div>
    </div>

@endsection

@section('script')
    <script>
        $('#viewUploadAttendanceFileForm').click(function() {
            var m = '{{ $m }}';
            $('#addMoreSection').html('<div class="loader"></div>');
            $.ajax({
                url: baseUrl+"/hdc/viewUploadAttendanceFileForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#addMoreSection').html(response);
                },
                error: function(error) {
                    $('#addMoreSection').html('');
                    swalError();
                }
            });
        });

        $('#viewManualAttendanceForm').click(function() {
            var m = '{{ $m }}';
            $('#addMoreSection').html('<div class="loader"></div>');
            $.ajax({
                url: baseUrl+"/hdc/viewManualAttendanceForm",
                type: 'GET',
                data: {m : m},
                success: function (response){
                    $('#addMoreSection').html(response);
                },
                error: function(error) {
                    $('#addMoreSection').html('');
                    swalError();
                }
            });
        });
    </script>
@endsection