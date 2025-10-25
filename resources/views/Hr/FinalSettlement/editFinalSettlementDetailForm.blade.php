<?php
use App\Helpers\CommonHelper;
?>

<div class="well">
    {{ Form::open(array('url' => 'had/editFinalSettlementDetail')) }}
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="company_id" value="{{ $company_id }}">
    <input type="hidden" name="id" value="{{ $final_settlement->id }}">
    <input type="hidden" name="employee_id" id="employee_id" value="{{ $final_settlement->employee_id }}">
    <input type="hidden" name="hold_salary_month" value="{{ $final_settlement->hold_salary_month }}">
    <input type="hidden" name="hold_salary_year" value="{{ $final_settlement->hold_salary_year }}">
    <input type="hidden" name="loan_id" value="{{ $final_settlement->loan_id }}">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <div class="row">&nbsp;</div>
                <div class="row" style="background-color: #f5f3ff;">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h4  style="text-decoration: underline;font-weight: bold;">Salary Detail</h4>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                </div>
                <div class="row">&nbsp;</div>

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Last Working Date</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input readonly type="date" name="last_working_date" id="last_working_date" class="form-control" value="{{ $final_settlement->last_working_date }}">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Salary To Be Paid</label>
                        <input type="date" name="salary_upto" id="salary_upto" class="form-control" value="{{ $final_settlement->salary_upto }}">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Salary Amount</label>
                        <input type="number" name="salary_amount" id="salary_amount" class="form-control" value="{{ $final_settlement->salary_amount }}">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Gratuity</label>
                        <input type="number" name="gratuity" id="gratuity" class="form-control" value="{{ $final_settlement->gratuity }}" />
                    </div>
                </div>
                <div class="row">
                    <div id="loader"></div>
                    <div class="salarySection" style="width: 100%"></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Hold Salary</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input readonly type="number" name="hold_salary" class="form-control" value="{{ $final_settlement->hold_salary }}">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Salary Cheque No.</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" name="salary_cheque_no" id="salary_cheque_no" class="form-control requiredField" value="{{ $final_settlement->salary_cheque_no }}" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Salary Cheque Date</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="date" name="salary_cheque_date" id="salary_cheque_date" class="form-control requiredField" value="{{ $final_settlement->salary_cheque_date }}" />
                    </div>
                </div>

                <div class="row">&nbsp;</div>
                <div class="row" style="background-color: #f5f3ff;">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h4  style="text-decoration: underline;font-weight: bold;">Other Amount</h4>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                </div>
                <div class="row">&nbsp;</div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="sf-label">Other Amount Type</label>
                        <input type="text" name="other_amount_narration" id="other_amount_narration" class="form-control" value="{{ $final_settlement->other_amount_narration }}" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="sf-label">Amount</label>
                        <input type="number" name="other_amount" id="other_amount" class="form-control" value="{{ $final_settlement->other_amount }}" />
                    </div>
                </div>

                <div class="row">&nbsp;</div>
                <div class="row" style="background-color: #f5f3ff;">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h4  style="text-decoration: underline;font-weight: bold;">Leave Encashment</h4>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                </div>
                <div class="row">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="sf-label">Leaves Encashment Days</label>
                        <input type="number" step="any" name="leave_encashment_days" id="leave_encashment_days" class="form-control" value="{{ $final_settlement->leave_encashment_days }}">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="sf-label">Leaves Encashment Amount</label>
                        <input type="number" name="leave_encashment_amount" id="leave_encashment_amount" class="form-control" value="{{ $final_settlement->leave_encashment_amount }}">
                    </div>
                </div>

                <div class="row">&nbsp;</div>
                <div class="row" style="background-color: #f5f3ff;">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h4  style="text-decoration: underline;font-weight: bold;">Deductions</h4>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                </div>
                <div class="row">&nbsp;</div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Leaves Deduction Days</label>
                        <input type="number" step="any" name="leave_deduction_days" id="leave_deduction_days" class="form-control" value="{{ $final_settlement->leave_deduction_days }}">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Leaves Deduction Amount</label>
                        <input readonly type="number" name="leave_deduction_amount" id="leave_deduction_amount" class="form-control" value="{{ $final_settlement->leave_deduction_amount }}">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Loan</label>
                        <input type="number" name="loan_deduction" id="pf_loan_deduction" class="form-control" value="{{ $final_settlement->loan_deduction }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">OPD Excess</label>
                        <input type="number" name="opd_excess" id="opd_excess" class="form-control" value="{{ $final_settlement->opd_excess }}" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Other Deduction Type</label>
                        <input type="text" name="other_deduction_type" id="other_deduction_type" class="form-control" value="{{ $final_settlement->other_deduction_type }}" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Other Deduction Amount</label>
                        <input type="number" name="other_deduction_amount" id="other_deduction_amount" class="form-control" value="{{ $final_settlement->other_deduction_amount }}" />
                    </div>
                </div>
                <div id="deduction_area"></div>
            </div>
        </div>
        <div class="row text-right">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="submit" name="submit" value="Update" class="btn btn-success" />
            </div>
        </div>
    </div>
</div>

<script>

    $("#salary_upto").change(function() {

        var employee_id = $('input[id="employee_id"]').val();
        var salary_upto = $('#salary_upto').val();
        var last_working_date = $('#last_working_date').val();
        var hold_net_amount = $('#hold_net_amount').val();
        $('#loader').html('<div class="loader"></div>');
        $.ajax({
            url: '{{ url('/') }}/hdc/calculateSalaryTobePaidAmount',
            type: "GET",
            data: {employee_id: employee_id, salary_upto:salary_upto, last_working_date:last_working_date, hold_net_amount:hold_net_amount},
            success: function (data) {
                $('.salarySection').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
                var val = $('#hidden_salary').val();
                $('#salary_amount').val(val);
                $('#loader').html('');
            }
        });
    });

    $("#leave_deduction_days").keyup(function(){

        var employee_id = $('input[id="employee_id"]').val();
        var leave_deduction_days = $('#leave_deduction_days').val();
        var last_working_date = $('#last_working_date').val();
        $.ajax({
            url: '{{ url('/') }}/hdc/calculateLeaveDeductionAmount',
            type: "GET",
            data: {employee_id: employee_id, leave_deduction_days:leave_deduction_days, last_working_date:last_working_date},
            success: function (data) {
                $('#leave_deduction_amount').val(data.leave_deduction_amount);
            }
        });
    });

</script>