<?php
$m = Input::get('m');
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            {{ Form::open(array('url' => 'had/editLoanRequestDetail')) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="formSection[]" id="formSection" value="1" />
            <input type="hidden" name="id" id="id" value="{{ $loan_request->id }}" class="form-control requiredField" />
            <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
            <div class="panel">
                <div class="panel-body">
                    <div class="get_clone">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Needed on Month & Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="needed_on_date" id="needed_on_date" value="{{ $loan_request->year.'-'.$loan_request->month }}" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Loan Type</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="loan_type_id" class="form-control requiredField" id="loan_type_id">
                                    <option value="">Select</option>
                                    @foreach($loan_types as $val)
                                        <option @if($loan_request->loan_type_id == $val->id) selected @endif value="{{ $val->id}}">{{ $val->loan_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Loan Amount</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="loan_amount" id="loan_amount" value="{{ $loan_request->loan_amount }}" class="form-control requiredField count_rows" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Out Standing Amount:</label>
                                <input type="number" name="out_standing_amount" id="out_standing_amount" class="form-control" value="{{ $loan_request->out_standing_amount }}" />
                                <span id="pf_amount"></span>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Per Month Deduction</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="per_month_deduction" id="per_month_deduction" value="{{ $loan_request->per_month_deduction }}" class="form-control requiredField count_rows" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label class="sf-label">Loan Description</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <textarea required name="loan_description" class="form-control" id="contents">{{ $loan_request->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
                        <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script>
    $('#loan_type_id').select2();
</script>