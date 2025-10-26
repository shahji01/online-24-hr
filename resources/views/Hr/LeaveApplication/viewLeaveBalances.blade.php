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
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <div class="row">
                            <div class="col-sm-6">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                @if(in_array('print',$operation_rights))
                                    {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                                @endif
                                @if(in_array('export',$operation_rights))
                                    {{ CommonHelper::displayExportButton('exportList','','1') }}
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @include('includes.allFilters')
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%;" class="form-control requiredField" name="year" id="year">
                                    <option value="">Select Year</option>
                                    <option value="2022-2023">2022 - 2023</option>
                                    <option value="2023-2024">2023 - 2024</option>
                                    <option value="2024-2025">2024 - 2025</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search"
                                        onclick="viewLeavesBalances()"><i id="load" class="fas fa-search fa"> Search</i>
                                </button>
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
        $('#year').select2();

        function viewLeavesBalances(){
            var year = $('#year').val();
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var location_id = $('#location_id').val();
            var company_id = '{{ $m }}';
            jqueryValidationCustom();
            if(validate == 0){
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewLeavesBalances',
                    type: "GET",
                    data: {
                        year: year,
                        company_id: company_id,
                        employee_id: employee_id,
                        department_id: department_id,
                        sub_department_id: sub_department_id,
                        project_id: project_id,
                        category_id: category_id,
                        location_id:location_id
                    },
                    success:function(data) {
                        $('#addMoreSection').html(data);
                    },
                    error: function() {
                        $('#addMoreSection').html('');
                        swalError();
                    }
                });
            }else{
                return false;
            }
        }

    </script>
@endsection
