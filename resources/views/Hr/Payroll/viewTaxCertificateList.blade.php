<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\SubDepartment;
?>

@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                {{ Form::open(array('url' => 'had/addTaxCertificateDetail')) }}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="m" value="{{ $m }}">
                <input type="hidden" name="formSection[]" value="1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title">Tax Certificate</h4>
                            </div>
                            <div class="col-sm-8 text-right">
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
                                        onclick="viewEmployeeTaxCertificateDetail()"><i id="load" class="fas fa-search fa"> Search</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="addMoreSection"></div>
                <div class="row">&nbsp;</div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function () {

            $(".btn-primary").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='formSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }
            });
            $('#year').select2();
        });

        function viewEmployeeTaxCertificateDetail() {

            var year = $('#year').val();
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var location_id = $('#location_id').val();
            var company_id = '{{ $m }}';

            jqueryValidationCustom();
            if (validate == 0) {
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: '{{ url('/') }}/hdc/viewEmployeeTaxCertificateDetail',
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
                    success: function (data) {
                        $('#addMoreSection').html(data);
                    },
                    error: function () {
                        swalError();
                        $('#addMoreSection').html('');
                    }
                });
            } else {
                return false;
            }
        }

    </script>
@endsection
