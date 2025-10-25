<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    {{ Form::open(array('url' => 'had/addStopPaymentDetail')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="formSection[]" id="formSection" value="1" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" />
                    <input type="hidden" name="loan_id" id="loan_id" value="{{ $loan_id }}" />
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Month - Year</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input class="form-control requiredField" type="month" id="month_year" name="month_year" />
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Reason</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <textarea class="form-control requiredField" id="reason" name="reason"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var loanType = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                loanType.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of loanType) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }
        });
    });

</script>