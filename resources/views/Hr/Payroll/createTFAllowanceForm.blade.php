<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
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
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="m" value="{{ $m }}">
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Month - Year</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="month_year" id="month_year" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <button style="margin-top: 40px;" type="button" class="btn btn-sm btn-primary btn_search" onclick="viewTFAllowanceForm()"><i id="load" class="fas fa-search fa"> Search</i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="formSection"></div>
    </div>
@endsection

@section('script')
    <script>

        function viewTFAllowanceForm(){

            var month_year = $('#month_year').val();
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var location_id = $('#location_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            jqueryValidationCustom();
            if(validate == 0) {
                $('#formSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewTFAllowanceForm',
                    type: "GET",
                    data: {employee_id:employee_id,location_id:location_id,project_id:project_id,category_id:category_id,
                        m:m,sub_department_id:sub_department_id,department_id:department_id,month_year:month_year},
                    success:function(data) {
                        $('#formSection').html(data);
                    },
                    error: function() {
                        $('#formSection').html('');
                        swalError();
                    }
                });
            } else {
                return false;
            }
        }

    </script>
@endsection