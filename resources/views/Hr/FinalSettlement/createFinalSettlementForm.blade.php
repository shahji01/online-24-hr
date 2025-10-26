<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addFinalSettlementDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export', $operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Employee</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="employee_id" id="employee_id">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $y)
                                        @if(($y->last_working_date != null && $y->last_working_date != '' && $y->last_working_date != '0000-00-00')||($y->status == 3))
                                            <option value="{{ $y->id }}">{{ $y->emp_id . ' -- '.$y->emp_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-top: 40px">
                                <button class="btn btn-sm btn-primary" id="viewFinalSettlementForm" type="button">Search</button>
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

        $("#viewFinalSettlementForm").click(function(){
            var employee_id  = $('#employee_id').val();
            var m = '{{ $m }}';
            jqueryValidationCustom();
            if(validate == 0){
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: '{{ url('/') }}/hdc/viewFinalSettlementForm',
                    type: "GET",
                    data: {employee_id:employee_id,m:m},
                    success: function (data) {
                        $('#addMoreSection').html(data);
                    },
                    error: function () {
                        swalError();
                        $('#addMoreSection').html('');
                    }
                });
            }else{
                return false;
            }
        });
    </script>
@endsection