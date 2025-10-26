<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addEmployeeBonusDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="{{ $m }}">
        <input type="hidden" name="formSection[]" id="formSection" value="1" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                               <h4 class="card-title">{{$breadCrums['heading']}}</h4>
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
                                <label class="sf-label">Bonus List:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" required name="bonus_id" id="bonus_id">
                                    <option value="">Select</option>
                                    @foreach($bonus_list as $value)
                                        <option value="{{ $value->id }}">{{ $value->bonus_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label class="sf-label">Bonus Month:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="bonus_month_year" id="bonus_month_year" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label class="sf-label">Bonus Pay:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="bonus_pay" id="bonus_pay">
                                    <option value="">Select Option</option>
                                    <option value="1">With Salary</option>
                                    <option value="2">Separately</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                <button style="margin-top: 40px" type="button" class="btn btn-sm btn-primary btn_search" onclick="viewEmployeesBonus()"><i id="load" class="fas fa-search fa"> </i> Search</button>
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

        $(document).ready(function() {

            $(".btn-success").click(function (e) {
                var employee = new Array();
                var val;
                $("input[name='formSection[]']").each(function () {
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val in employee) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                        //alert(response);
                    } else {
                        return false;
                    }
                }
            });

            $("#bonus_id").select2();
            $("#bonus_pay").select2();
        });

        function viewEmployeesBonus(){
            var department_id = $('#department_id').val();
            var sub_department_id = $('#sub_department_id').val();
            var project_id = $('#project_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var bonus_month_year = $('#bonus_month_year').val();
            var bonus_id = $('#bonus_id').val();
            var bonus_pay = $('#bonus_pay').val();
            var location_id = $('#location_id').val();
            var company_id = '{{ $m }}';

            jqueryValidationCustom();
            if (validate == 0) {
                $('#addMoreSection').html('<div class="loader"></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewEmployeesBonus',
                    type: "GET",
                    data: { department_id:department_id,sub_department_id:sub_department_id,
                        bonus_id:bonus_id,bonus_pay:bonus_pay,employee_id:employee_id,
                        bonus_month_year:bonus_month_year,location_id:location_id,m:company_id,
                        project_id: project_id,category_id:category_id},
                    success:function(data) {
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