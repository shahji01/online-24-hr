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

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title">Employees Gratuity Form</h4>
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
                                <label class="sf-label amount_label">Month - Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="month_year" id="month_year" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 40px">
                                <button type="button" class="btn btn-sm btn-primary btn_search"
                                        onclick="viewEmployeeGratuityFormDetail()"><i id="load" class="fas fa-search fa"> Search</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="addMoreSection"></div>
                <div class="row">&nbsp;</div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function viewEmployeeGratuityFormDetail() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var location_id = $('#location_id').val();
            var month_year = $('#month_year').val();
            var company_id = '{{ $m }}';

            jqueryValidationCustom();
            if (validate == 0) {
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl + '/hdc/viewEmployeeGratuityFormDetail',
                    type: "GET",
                    data: {
                        company_id: company_id,
                        employee_id: employee_id,
                        department_id: department_id,
                        sub_department_id: sub_department_id,
                        project_id: project_id,
                        category_id: category_id,
                        location_id:location_id,
                        month_year:month_year
                    },
                    success: function (data) {
                        $('#addMoreSection').html(data);
                    },
                    error: function () {
                        swalError();
                        $('#addMoreSection').html('');
                    }
                });
            }
        }

    </script>
@endsection
