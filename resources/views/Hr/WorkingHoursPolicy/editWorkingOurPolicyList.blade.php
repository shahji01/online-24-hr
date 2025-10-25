<?php
$days_off_array=[];
$days_off=$editWorkingPolicyDetail->days_off;
if($days_off!=''){
    $days_off_array=explode("=>",$days_off);
}
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            {{ Form::open(array('url' => 'had/editWorkingHoursPolicyDetail')) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="w_id" value="{{$editWorkingPolicyDetail->id}}">
            <input type="hidden" name="m" value="{{Input::get('m')}}">
            <input type="hidden" name="formSection[]" id="formSection" value="1">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Working Hours Policy Name:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="working_hours_policy" class="form-control requiredField" id="working_hours_policy" value="{{$editWorkingPolicyDetail->working_hours_policy}}" >
                            </div>
                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="text-dark sf-label">Days Off</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select style="width:100%;" class="form-control requiredField" name="days_off_1[]" id="days_off_1" multiple>
                                    <option value="None">None</option>
                                    <option @if (in_array("Mon", $days_off_array)) selected @endif value="Mon">Monday</option>
                                    <option @if (in_array("Tue", $days_off_array)) selected @endif value="Tue">Tuesday</option>
                                    <option @if (in_array("Wed", $days_off_array)) selected @endif value="Wed">Wednesday</option>
                                    <option @if (in_array("Thu", $days_off_array)) selected @endif value="Thu">Thursday</option>
                                    <option @if (in_array("Fri", $days_off_array)) selected @endif value="Fri">Friday</option>
                                    <option @if (in_array("Sat", $days_off_array)) selected @endif value="Sat">Saturday</option>
                                    <option @if (in_array("Sun", $days_off_array)) selected @endif value="Sun">Sunday</option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Days of Overtime or CPL</label>
                                <select style="width:100%;" class="form-control " name="ot_cpl" id="ot_cpl">
                                    <option value="">None</option>
                                    <option @if ("holiday_and_offday" == $editWorkingPolicyDetail->ot_cpl ) selected @endif value="holiday_and_offday">off days and holiday</option>
                                    <option @if ("all_days" == $editWorkingPolicyDetail->ot_cpl) selected @endif value="all_days">All days</option>
                                </select>
                            </div>


                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Deduction Amount Late Day:</label>
                                <input type="number" value="{{ $editWorkingPolicyDetail->deduction_amount_late_day }}" name="late_deduction" class="form-control" id="late_deduction" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Clock In Time:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="time" name="start_working_hours_time" class="form-control requiredField" id="start_working_hours_time" value="{{ $editWorkingPolicyDetail->start_working_hours_time }}" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Clock Out Time:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="time" name="end_working_hours_time" class="form-control requiredField" id="end_working_hours_time" value="{{ $editWorkingPolicyDetail->end_working_hours_time }}" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Half Day Time (Minutes)</label>
                                <input type="number" name="half_day_time" class="form-control" id="half_day_time" value="{{ $editWorkingPolicyDetail->half_day_time }}" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Grace Time (Minutes)</label>
                                <input type="number" name="working_hours_grace_time" class="form-control" id="working_hours_grace_time" min="0" value="{{ $editWorkingPolicyDetail->working_hours_grace_time }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Early Going Grace Time (Minutes)</label>
                                <input type="number" name="early_going_grace_time" class="form-control" id="early_going_grace_time" value="{{ $editWorkingPolicyDetail->early_going_grace_time }}" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Overtime After Minutes</label>
                                <input type="number" name="overtime_after_minutes" class="form-control" id="overtime_after_minutes" min="0"  value="{{ $editWorkingPolicyDetail->overtime_after_minutes }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <input class="btn btn-sm btn-success" type="submit" value="Update">
                        <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<script>

    $(document).ready(function(){

        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            // for (val in workingHoursSection) {
            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        });

        $('#days_off_1').select2();
    });

</script>


