<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addEmployeeExitClearanceDetail')) }}
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
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    @include('includes.singleFilters')
                                    <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12" style="margin-top: 40px">
                                        <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewEmployeeExitClearance()"><i id="load" class="fas fa-search fa"> Search</i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="addMoreSection"></div>
        <div class="row">&nbsp;</div>
        {{ Form::close() }}
    </div>

@endsection

@section('script')
    <script>
        function viewEmployeeExitClearance() {
            $('#addMoreSection').html('<div class="loader"></div>');
            var employee_id = $('#employee_id').val();
            var company_id = '{{ $m }}';
            $.ajax({
                url: baseUrl+"/hdc/viewEmployeeExitClearanceForm",
                type: 'GET',
                data: {employee_id: employee_id, company_id : company_id},
                success: function (data){
                    if(data == 'false') {
                        swalAlert('Alert','No record Found');
                    } else {
                        $('#addMoreSection').html(data);
                    }
                },
                error: function (){
                    $('#addMoreSection').html("");
                    swalError();
                }
            });
        }
    </script>
@endsection