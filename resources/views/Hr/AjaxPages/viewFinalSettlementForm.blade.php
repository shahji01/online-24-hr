<?php
$accType = Auth::user()->acc_type;
use App\Helpers\CommonHelper;
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="company_id" value="{{ $m }}">
                        <input type="hidden" name="employee_id" value="{{ $employee_id }}" />
                        <input type="hidden" name="hold_salary" value="{{ implode(',',$hold_salary) }}" />
                        <input type="hidden" name="hold_salary_month" value="{{ implode(',',$hold_salary_month) }}" />
                        <input type="hidden" name="hold_salary_year" value="{{ implode(',',$hold_salary_year) }}" />
                        <input type="hidden" name="loan_id" value="{{ $loan_id }}">

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
                                <input readonly type="date" name="last_working_date" id="last_working_date" class="form-control" value="{{ $last_working_date }}" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Salary To Be Paid</label>
                                <input type="date" name="salary_upto" id="salary_upto" class="form-control" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Salary Amount</label>
                                <input type="number" name="salary_amount" id="salary_amount" class="form-control" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Gratuity</label>
                                <input type="number" name="gratuity" id="gratuity" class="form-control" value="{{ $gratuity }}" />
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
                                <input readonly type="number" class="form-control" value="{{ $sum_hold_salary }}">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Salary Cheque No.</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="salary_cheque_no" id="salary_cheque_no" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Salary Cheque Date</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="salary_cheque_date" id="salary_cheque_date" class="form-control requiredField" />
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
                                <input type="text" name="other_amount_narration" id="other_amount_narration" class="form-control" />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Amount</label>
                                <input type="number" name="other_amount" id="other_amount" class="form-control" />
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
                                <input type="number" step="any" name="leave_encashment_days" id="leave_encashment_days" class="form-control" />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Leaves Encashment Amount</label>
                                <input type="number" name="leave_encashment_amount" id="leave_encashment_amount" class="form-control" />
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
                                <input type="number" step="any" name="leave_deduction_days" id="leave_deduction_days" class="form-control" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Leaves Deduction Amount</label>
                                <input readonly type="number" name="leave_deduction_amount" id="leave_deduction_amount" class="form-control" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Loan</label>
                                <input type="number" name="loan_deduction" id="loan_deduction" class="form-control" value="{{ $loan_amount_remaining }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">OPD Excess</label>
                                <input type="number" name="opd_excess" id="opd_excess" class="form-control" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Other Deduction Type</label>
                                <input type="text" name="other_deduction_type" id="other_deduction_type" class="form-control" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Other Deduction Amount</label>
                                <input type="number" name="other_deduction_amount" id="other_deduction_amount" class="form-control" />
                            </div>
                        </div>
                        <div id="deduction_area"></div>
                    </div>
                </div>
                <div class="row text-right">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 1%">
                        <input type="submit" name="submit" class="btn btn-success" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val in employee) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }
        });
    });


    //    var counter = 1;
    //    $('#add_deduction').on('click',function(){
    //        var count = counter++;
    //        $('#deduction_area').append(
    //                '<div class="row" id="deduction_row_'+count+'">' +
    //                '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">' +
    //                '<label class="sf-label">Deduction Type</label>' +
    //                '<input type="text" name="deduction_type[]" id="deduction_type" class="form-control" value=""></div>' +
    //                '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">'+
    //                '<label class="sf-label">Deduction Amount</label>'+
    //                '<input type="number" name="deduction_amount[]" id="deduction_amount" class="form-control" value=""></div>' +
    //                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 30px">' +
    //                '<input type="button" value="Remove" onclick="removeDeduction('+count+')" class="btn btn-danger btn-xs"></div></div>')
    //    });
    //
    //    function removeDeduction(id){
    //        var elem = document.getElementById('deduction_row_'+id+'');
    //        elem.remove(elem);
    //    }

    $("#leave_deduction_days").keyup(function(){

        var employee_id = $('#employee_id').val();
        alert(employee_id)
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

    $("#salary_upto").change(function() {

        var employee_id = $('#employee_id').val();
        var company_id = $('[name="company_id"]').val();
        var salary_upto = $('#salary_upto').val();
        var last_working_date = $('#last_working_date').val();
        var hold_net_amount = $('#hold_net_amount').val();
        $('.salarySection').html('<div class="loader"></div>');
        $.ajax({
            url: '{{ url('/') }}/hdc/calculateSalaryTobePaidAmount',
            type: "GET",
            data: {employee_id: employee_id, salary_upto:salary_upto,company_id:company_id,
                last_working_date:last_working_date, hold_net_amount:hold_net_amount},
            success: function (data) {
//                $('#salary_amount').val(data);
                $('.salarySection').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
                var val = $('#hidden_salary').val();
                $('#salary_amount').val(val);
                $('#loader').html('');
            }
        });
    });

</script>