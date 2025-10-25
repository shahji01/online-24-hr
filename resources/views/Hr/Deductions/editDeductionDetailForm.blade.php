<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            {{ Form::open(array('url' => 'had/editDeductionDetail')) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="formSection[]" id="formSection" value="1" />
            <input type="hidden" name="id" id="id" value="{{ $deduction->id }}" class="form-control requiredField" />
            <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Deduction Type:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="deduction_type_id" id="deduction_type_id" class="form-control requiredField">
                                <option disabled value="">Select Deduction Type</option>
                                <option @if($deduction->deduction_type == "LWP") selected @endif value="LWP">LWP</option>
                                <option @if($deduction->deduction_type == "Penalty") selected @endif value="Penalty">Penalty</option>
                                <option @if($deduction->deduction_type == "Other") selected @endif value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="label-deduction">Deduction Amount:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="number" name="deduction_amount" id="deduction_amount" value="{{ $deduction->deduction_amount }}" class="form-control requiredField" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Remarks:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <textarea name="remarks" id="remarks" class="form-control requiredField">{{ $deduction->Remarks }}</textarea>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                            <label>Once ?</label><br>
                            <input @if($deduction->once == 1) checked @endif type="checkbox" class="requiredField" name="once" id="once" value="1">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 once_area">
                            @if($deduction->once == 1)
                                <label class="sf-label">Month - Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" class="form-control requiredField" name="month_year" value="{{ $deduction->year.'-'.$deduction->month }}">
                            @endif
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

<script>
    $('#deduction_type_id').select2();
    $('#once').click(function (e){
        if($("#once").is(':checked')) {
            $(".once_area").html('<label>Month - Year</label><span class="rflabelsteric"><strong>*</strong></span><input selected type="month" class="requiredField form-control" name="month_year" value="{{ $deduction->year.'-'.$deduction->month }}" >')
        }
        else {
            $(".once_area").html('')
        }

    });
    $(document).on('change', '#deduction_type', function() {

        if($(this).val()=="LWP"){
            $(this).closest('.label-deduction').html("Deduction Days:");
        }
        else{
            $(this).closest('.label-deduction').html("Deduction Amount:");
        }
    });

</script>