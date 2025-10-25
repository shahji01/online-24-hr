<?php
$currentDate = date('Y-m-d');
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <?php echo Form::open(array('url' => 'had/addProvidentFundDisburse','id'=>'providentFund'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="<?=$acc_no_and_name[3]?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Emp Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input readonly type="text" name="[]" id="[]" value="<?='Acc no:&nbsp;'.$acc_no_and_name[0]."&nbsp;&nbsp;".$acc_no_and_name[1]?>" class="form-control" />
                            <input type="hidden" name="emp_id" value="<?=$acc_no_and_name[0]?>">
                            <input type="hidden" name="provident_fund_id" value="<?=$acc_no_and_name[2]?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Total Provident Fund Amount:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input readonly type="text" name="pf_amount" id="pf_amount" value="<?=$total_pf_amount->pf_amount?>" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Disburse Amount</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="number" name="disburse_amount" id="disburse_amount" value="" class="form-control requiredField" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <label class="sf-label">Month-Year:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="month" name="month_year" id="month_year" value="<?php echo $currentDate?>" class="form-control" required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="TaxesSection"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <!--<input type="button" class="btn btn-sm btn-primary addMoreTaxesSection" value="Add More Taxes Section" />-->
                    <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                    {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                </div>
            </div>
            <?php echo Form::close();?>
        </div>
    </div>
</div>

