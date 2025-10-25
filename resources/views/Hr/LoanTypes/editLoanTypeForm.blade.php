<?php
$currentDate = date('Y-m-d');
$id = $_GET['id'];
$m 	= $_GET['m'];
//$d 	= DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;
$loanTypeDetail = DB::selectOne('select * from `loan_type` where `id` = '.$id.'');
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <?php echo Form::open(array('url' => 'had/editLoanTypeDetail?m='.$m.'','id'=>'loanTypeForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="loanTypeSection[]" class="form-control" id="loanTypeSection" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Loan Type Name:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="loan_type_name_1" id="loan_type_name_1" value="<?php echo $loanTypeDetail->loan_type_name?>" class="form-control requiredField" />
                                    <input type="hidden" name="loan_type_id_1" id="loan_type_id_1" value="<?php echo $loanTypeDetail->id?>" class="form-control requiredField" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="loanTypeSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>
</div>

