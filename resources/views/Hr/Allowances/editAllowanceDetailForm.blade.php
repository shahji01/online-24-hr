<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            {{ Form::open(array('url' => 'had/editAllowanceDetail')) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="formSection[]" id="formSection" value="1" />
            <input type="hidden" name="id" id="id" value="{{ $allowance->id }}" class="form-control requiredField" />
            <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Allowance Type:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="allowance_type_id" id="allowance_type_id" class="form-control requiredField">
                                <option value="">Select Allowance Type</option>
                                @foreach($allowance_types  as $key => $y)
                                    <option @if($allowance->allowance_type_id == $y->id) selected @endif value="{{ $y->id }}">{{ $y->allowance_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label amount_label">Allowance Amount:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="number" name="allowance_amount" id="allowance_amount" value="{{$allowance->allowance_amount}}" class="form-control requiredField" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Remarks:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <textarea name="remarks" id="remarks" class="form-control requiredField">{{ $allowance->remarks }}</textarea>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                            <label>Once ?</label><br>
                            <input @if($allowance->once == 1) checked @endif type="checkbox" class="requiredField" name="once" id="once" value="1">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 once_area">
                            @if($allowance->once == 1)
                                <label class="sf-label">Month - Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" class="form-control requiredField" value={{$allowance->month.'-'.$allowance->month}} name="month_year">
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

    $('#allowance_type_id').select2();
    $('#once').click(function (e){

        if($("#once").is(':checked')) {
            $(".once_area").html('<label>Month - Year</label><span class="rflabelsteric"><strong>*</strong></span><input type="month" class="form-control requiredField" name="month_year" id="month_year">')
        } else {
            $(".once_area").html('')
        }

    });

</script>