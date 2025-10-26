<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\SubDepartment;
$user_roles = CommonHelper::userRoles($m);

?>

@extends('layouts.default')
@section('css')

    <style>
        input[type="radio"],
        input[type="checkbox"] {
            width: 30px;
            height: 20px;
        }

        .modal-content {
            background-clip: padding-box;
            background-color: #fff;
            border: 1px solid rgba(10, 2, 30, .2);
            border-radius: 0.3rem;
            display: flex;
            flex-direction: column;
            outline: 0;
            pointer-events: auto;
            position: relative;
            width: 100%;
        }

        .btn-group-sm>.btn,

        .modal.fade .btn-info {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
        }

        .modal.fade .btn-primary {
            background-color: #8b5cf6;
            border-color: #8b5cf6;
            color: #fff;
        }

        .table {
            background-color: transparent;
            margin-bottom: 1rem;
            width: 100%;
        }

        .badge-outline-success {
            background: unset;
            border: 1px solid #10b981;
            color: #10b981;
        }

        .badge {
            font-weight: 600;
        }

        .badge {
            border-radius: 0.25rem;
            display: inline-block;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            padding: 0.25em 0.4em;
            text-align: center;
            vertical-align: baseline;
            white-space: nowrap;
        }
    </style>
@endsection
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addEmployeeDeductionDetail')) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                 <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#exampleModal" style="float: right;"> Import csv </button>
                            </div>
                        </div>
                        <hr>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    @include('includes.allFilters')
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Select Deduction Type:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select style="width: 100%;" name="deduction_type_search" id="deduction_type_search" class="form-control requiredField">
                                            <option value="">Select Deduction Type</option>
                                            <option value="LWP">LWP</option>
                                            <option value="Penalty">Penalty</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="label-deduction">Deduction Amount:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" name="deduction_amount_search" id="deduction_amount_search" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <label>Once ?</label><br>
                                        <input type="checkbox" class="requiredField" name="once" id="once" value="1">
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 once_area"></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <label class="sf-label">Remarks:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="remarks" id="remarks" class="form-control requiredField"></textarea>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 40px">
                                        <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewDeductionFormDetail()"><i id="load" class="fas fa-search fa"> Search</i></button>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="importProducts___BV_modal_body_" class="modal-body">
                    <form action="{{ url('had/addEmployeeDeductionDetailCsv') }}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="mb-3 col-sm-12 col-md-12">
                                <fieldset class="form-group" id="__BVID__194">
                                    <div>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="company_id" id="company_id"
                                               value="{{ $m }}" />
                                        <input type="file" name='file' label="Choose File" required>
                                        
                                        <div id="File-feedback" class="d-block invalid-feedback">Field must be in
                                            csvformat</div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6"><button type="submit"
                                                                   class="btn btn-primary btn-sm btn-block">Submit</button></div>
                            <div class="col-sm-6 col-md-6"><button onclick="download_csv_file()" target="_self"
                                                                   class="btn btn-info btn-sm btn-block">Download example</button></div>
                        </div>
                    </form>

                    <div class="col-sm-12 col-md-12">
                        <table class="table table-bordered table-sm mt-4">
                            <tbody>
                            <tr>
                                <td>Employee Id</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>

                            </tr>
                            <tr>
                                <td>Deduction type</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>

                            </tr>
                            <tr>
                                <td>Deduction Amount</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>

                            </tr>
                            <tr>
                                <td>Remarks</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Date (if you want to give specific allowance once)</td>
                                <th><span class="badge badge-outline-info">Field optional</span></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>

        function viewDeductionFormDetail() {
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var deduction_type = $('#deduction_type_search').val();
            var deduction_amount = $('#deduction_amount_search').val();
            var location_id = $('#location_id').val();
            var company_id = m;

            jqueryValidationCustom();
            if (validate == 0) {
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl + '/hdc/viewDeductionFormDetail',
                    type: "GET",
                    data: {
                        company_id: company_id,
                        employee_id: employee_id,
                        department_id: department_id,
                        sub_department_id: sub_department_id,
                        project_id: project_id,
                        category_id: category_id,
                        deduction_type: deduction_type,
                        location_id: location_id,
                        deduction_amount: deduction_amount
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

        var csvFileData = [
            ['10038', 'LWP', '120','LWP deduction' , '2022-11-16'],
            ['10038', 'Penalty', '200','Penalty deduction' , '']
        ];

        //create a user-defined function to download CSV file
        function download_csv_file() {

            //define the heading for each row of the data
            var csv = 'Employee id, Deduction type,Deduction Amount,Remarks,Date (if you want to deducation specific day)\n';

            //merge the data with CSV
            csvFileData.forEach(function(row) {
                csv += row.join(',');
                csv += "\n";
            });

            //display the created CSV data on the web browser
            //document.write(csv);

            var hiddenElement = document.createElement('a');
            hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
            hiddenElement.target = '_blank';

            //provide the name for the CSV file to be downloaded
            hiddenElement.download = 'Deduction file example.csv';
            hiddenElement.click();
        }


        $('#deduction_type_search').select2();
        $('#deduction_type_search').on('change', function() {
            if(this.value == "LWP") {
                $('.label-deduction').html("Deduction Days:");
            } else {
                $('.label-deduction').html("Deduction Amount:");
            }
        });

    </script>
@endsection