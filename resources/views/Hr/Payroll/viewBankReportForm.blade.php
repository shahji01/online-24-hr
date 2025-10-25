<?php
use App\Helpers\CommonHelper;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="m" value="{{ $m }}">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Bank Report</h4>
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
                            @include('includes.allFilters')
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>Month - Year</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="month_year" id="month_year" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>Cheque Date.</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="cheque_date" id="cheque_date" value="{{ date('Y-m-d') }}" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Select Bank</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width: 100%" class="form-control requiredField" name="bank_name" id="bank_name">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewBankReportDetail()" style="margin-top: 36px;" ><i id="load" class="fas fa fa-search"> </i> Search</button>
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
        $('#bank_name').select2();
        function viewBankReportDetail(){
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var month_year = $('#month_year').val();
            var company_id = '{{ $m }}';
            var cheque_no =  $('#cheque_no').val();
            var cheque_date = $("#cheque_date").val();
            var bank = $("#bank_name").val();
            jqueryValidationCustom();
            if(validate == 0){
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewBankReportDetail',
                    type: "GET",
                    data: {
                        employee_id:employee_id,
                        project_id:project_id,
                        category_id:category_id,
                        department_id:department_id,
                        sub_department_id:sub_department_id,
                        company_id:company_id,
                        month_year:month_year,
                        cheque_no:cheque_no,
                        cheque_date:cheque_date,
                        bank:bank
                    },
                    success:function(data) {
                        $('#addMoreSection').html(data);
                    },
                    error: function () {
                        $('#v').html('');
                        swalError();
                    }
                });
            }else{
                return false;
            }
        }

    </script>
@endsection