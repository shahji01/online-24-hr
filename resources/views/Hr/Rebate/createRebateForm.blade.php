<?php
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')

@section('css')
    <style>
        input[type="radio"], input[type="checkbox"]{ width:20px;
            height:20px;
        }
    </style>
@endsection

@section('content')

    <div class="page-wrapper">
        {{ Form::open(array('url' => 'had/addRebateDetail', 'enctype' => 'multipart/form-data')) }}
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
                        </div>
                        <hr>
                        <div class="row">
                            @include('includes.singleFilters')
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Month - Year</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="month_year" id="month_year" class="form-control requiredField">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Rebate Type</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="type" id="type" class="form-control requiredField" onchange="rebateType(this.value)">
                                    <option value="">Select Rebate Type</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" id="rebateSection"></div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>

@endsection
@section('script')
    <script>

        $(document).ready(function(){

            $(".btn-success").click(function(e){
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
                    }else if(validate == 1){
                        return false;
                    }
                }
            });

            $('#type').select2();
        });

        function rebateType(value)
        {
            if(value == 'fixed') {
                $('#rebateSection').html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Rebate Nature</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<select name="nature" id="nature_fixed" class="form-control requiredField">' +
                        '<option value="">Select Nature of Rebate</option><optgroup label="Education">' +
                        '<option value="Educational Tax us 60B">Educational Tax us 60B</option></optgroup><optgroup label="Consumer">' +
                        '<option value="Domestic Consumer Tax us 235A">Domestic Consumer Tax us 235A</option></optgroup>' +
                        '<optgroup label="Banking"><option value="Cash Withdrawal us 231A">Cash Withdrawal us 231A</option>' +
                        '<option value="Certain Bank Transaction us 231A">Certain Bank Transaction us 231A</option>' +
                        '<option value="Banking Transaction Other Than Cash us 236P">Banking Transaction Other Than Cash us 236P</option>' +
                        '</optgroup><optgroup label="Vehicle"><option value="Transfer of Vehicle us 231B">Transfer of Vehicle us 231B</option>' +
                        '<option value="Private Vehicle Token us 234">Private Vehicle Token us 234</option>' +
                        '<option value="Vehicle Registration us 231B(1)">Vehicle Registration us 231B(1)</option>' +
                        '<option value="Sale of Vehicle us 231B(3)">Sale of Vehicle us 231B(3)</option></optgroup><optgroup label="Telephone">' +
                        '<option value="Cell Phone us 236(1)(a)">Cell Phone us 236(1)(a)</option>' +
                        '<option value="Telephone us 236(1)(a)">Telephone us 236(1)(a)</option>' +
                        '<option value="Prepaid us 236(1)(b)">Prepaid us 236(1)(b)</option>' +
                        '<option value="Phone Unit us 236(1)(c)">Phone Unit us 236(1)(c)</option>' +
                        '<option value="Internet us 236(1)(d)">Internet us 236(1)(d)</option></optgroup><optgroup label="Property">' +
                        '<option value="Purchase and Sale of Property us 236C">Purchase and Sale of Property us 236C</option>' +
                        '<option value="Transfer of Property us 236K">Transfer of Property us 236K</option>' +
                        '<option value="Registration us 236W">Registration us 236W</option></optgroup></select></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Rebate Amount</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="number" step="any" name="rebate_amount" id="rebate_amount" class="form-control requiredField"></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">File Upload:</label>' +
                        '<input type="file" name="rebate_file_path[]" id="rebate_file_path" class="form-control" multiple></div>');

            } else if(value == 'Other') {
                $('#rebateSection').html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Rebate Nature</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<select name="nature" id="nature_other" class="form-control requiredField">' +
                        '<option value="">Select Nature of Rebate</option>' +
                        '<option value="Charitable / Zakaat Donation us 61">Charitable / Zakaat Donation us 61</option>' +
                        '<option value="Investment of Shares and Insurance us 62">Investment of Shares and Insurance us 62</option>' +
                        '<option value="Health Insurance us 62A">Health Insurance us 62A</option>' +
                        '<option value="Pension us 63">Pension us 63</option><option value="Bank Investment">Bank Investment</option></select>' +
                        '</div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Actual Investment</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="number" step="any" name="actual_investment" id="actual_investment" class="form-control requiredField" value=""></div>' +
                        '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">File Upload:</label>' +
                        '<input type="file" name="rebate_file_path[]" id="rebate_file_path" class="form-control" multiple>' +
                        '</div>');
            }

            $('#nature_other').select2();
            $('#nature_fixed').select2();
        }

    </script>
@endsection