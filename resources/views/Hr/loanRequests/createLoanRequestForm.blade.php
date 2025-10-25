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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Loan Request Form</h4>
                            </div>
                            @if(in_array('edit',$operation_rights))
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal" style="float: right;"> Import csv </button>
                                </div>
                            @endif
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'had/addLoanRequestDetail')) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="formSection[]" id="formSection" value="1" />
                        <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    @include('includes.singleFilters')
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Loan Type:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select name="loan_type_id" id="loan_type_id" class="form-control requiredField">
                                            <option value="">Select Option</option>
                                            @foreach($loan_types as $key => $y)
                                                <option value="{{ $y->id }}">{{ $y->loan_type_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Amount:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" name="loan_amount" id="loan_amount" class="form-control requiredField" />
                                        <span id="pf_amount"></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Out Standing Amount:</label>
                                        <input type="number" name="out_standing_amount" id="out_standing_amount" class="form-control" />
                                        <span id="pf_amount"></span>
                                    </div>
                                </div>
                                <div id="addMoreSection"></div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Needed on Month & Year:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="month" name="needed_on_date" id="needed_on_date" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Per Month Deduction:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" name="per_month_deduction" id="per_month_deduction" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Deduction Start Month - Year:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="month" name="deduction_start_date" id="deduction_start_date" class="form-control requiredField" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Remarks</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="loan_description" class="form-control requiredField" id="contents"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
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
                    <form action="{{ url('had/addLoanRequestDetailCsv') }}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="mb-3 col-sm-12 col-md-12">
                                <fieldset class="form-group" id="__BVID__194">
                                    <div>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="company_id" id="company_id"
                                               value="{{ $m }}" />
                                        <input type="file" name='file' label="Choose File" required>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Location</label>
                                            <span class="rflabelsteric">*</span>
                                            <select class="form-control requiredField" id="location_id" name="location_id" required>
                                                <option value="">Select Location</option>
                                                @foreach($locations as $key => $val)
                                                    @if(in_array($val->id, $user_roles['location_ids']))
                                                        <option value="{{ $val->id }}">{{ $val->location_code.' -- '.$val->location_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <span class="location_loader"></span>
                                        </div>
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
                                <td>Loan type</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>

                            </tr>
                            <tr>
                                <td>loan Amount</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>

                            </tr>
                            <tr>
                                <td>Outstanding Amount</td>
                                <th><span class="badge badge-outline-info">Field optional</span></th>

                            </tr>
                            <tr>
                                <td>Needed on Month & Year</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Per Month Deduction</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Deduction Start Month - Year</td>
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

        var csvFileData = [
            ['10038', 'Personal', '5000','','2022-11','1500','2022-12','Personal Salary' ],
            ['10038', 'Car', '20000','','2022-11','1500','2022-12','Car Loan' ]
        ];

        //create a user-defined function to download CSV file
        function download_csv_file() {

            //define the heading for each row of the data
            var csv = 'Employee id, Loan type,Loan Amount,Outstanding Amount,Needed on Month & Year,Per Month Deduction,Deduction Start Month - Year,Remarks\n';

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
            hiddenElement.download = 'loan file example.csv';
            hiddenElement.click();
        }

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

            $("#loan_type_id").select2();
        });

        $("#employee_id").on("change", function(e) {
            var company_id = '{{ $m }}';
            var employee_id = $('#employee_id').val();
            $.ajax({
                url: '{{ url('/') }}/hdc/checkLoanData',
                type: "GET",
                data: {company_id:company_id, employee_id:employee_id},
                success:function(data) {
                    $('#addMoreSection').html('<div class="lineHeight">&nbsp;</div><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >' +
                            '<table><tr><th>Joining Date</th><td class="text-center">'+data.joining_date+'</td>' +
                            '<th>Gratuity</th><td class="text-right">'+data.gratuity+'</td></tr><tr><th>Out Standing Loan</th><td class="text-right">'+data.loan_remaining_amount+'</td>' +
                            '<th>70% of Gratuity</th><td class="text-right">'+data.per_gratuity+'</td></tr></table></div></div><div class="lineHeight">&nbsp;</div>');
                }
            });
        });

    </script>

@endsection