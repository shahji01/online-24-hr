<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <?php echo Form::open(array('url' => 'had/editHolidayDetail','id'=>'Holidays'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
            <input type="hidden" name="record_id" value="<?php echo Input::get('id')?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Holiday Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input required type="text" name="holiday_name" id="holiday_name" value="{{ $holidaysDetail['holiday_name'] }}" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Holiday Date:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input required type="date" name="holiday_date" id="holiday_date" value="{{ $holidaysDetail['holiday_date'] }}" class="form-control requiredField" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                </div>
            </div>
            <?php echo Form::close();?>
        </div>
    </div>
</div>
