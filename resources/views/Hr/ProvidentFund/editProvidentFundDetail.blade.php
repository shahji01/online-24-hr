<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <?php echo Form::open(array('url' => 'had/editProvidentFundDetail','id'=>'providentFund'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
            <input type="hidden" name="recordId" value="<?php echo Input::get('id')?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Provident Fund Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="pf_name[]" id="pf_name[]" value="<?=$provident_fund_detail->name?>" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Percentange % of Salary
                                <input type="radio" @if($provident_fund_detail->pf_mode == 'percentage') checked @endif name="pf_mode[]" value="percentage">Amount
                                <input type="radio" @if($provident_fund_detail->pf_mode == 'amount') checked @endif name="pf_mode[]" value="amount">
                            </label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="amount_percent[]" id="amount_percent[]" value="<?=$provident_fund_detail->amount_percent?>" class="form-control requiredField" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="TaxesSection"></div>
            <br>
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



