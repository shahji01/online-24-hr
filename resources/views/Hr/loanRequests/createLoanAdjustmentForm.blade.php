<?php
$counter = 0;
$m = Input::get('m');
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    {{ Form::open(array('url' => 'had/addLoanAdjustmentDetail')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="loanSection[]" class="form-control" id="loanSection" value="1" />
                    <input type="hidden" name="loan_id" id="loan_id" value="{{ $loan_id }}" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-bordered sf-table-list">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Loan Adjustment Amount  <span class="rflabelsteric"><strong>*</strong></span></th>
                                            <th class="text-center">Adjusted On <span class="rflabelsteric"><strong>*</strong></span></th>
                                            <th class="text-center"> <input type="button" class="btn btn-sm btn-primary" onclick="addMore()" value="Add More" /></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addMoreSection">
                                        @if($loan_adjustment->count() > 0)
                                            @foreach($loan_adjustment->get() as $key => $val)
                                                <tr id="sectionAddMore_{{$key}}">
                                                    <td class="text-center">
                                                        <input type="hidden" name="id[]" id="id_{{ $key }}" value="{{ $val->id }}">
                                                        <input type="number" step="any" name="amount[]" id="amount_{{$key}}" value="{{ $val->amount }}" class="form-control requiredField" />
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="month" name="month_year[]" id="month_year_{{$key}}" value="{{ date($val->year.'-'.sprintf('%02d',$val->month)) }}" class="form-control requiredField" />
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" value="Delete" onclick="deleteLoanTopUp('{{ $m }}','{{ $val->id }}','loan_adjustment')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                                <?php $counter = $key ?>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
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
</div>

<script>
    $(document).ready(function() {

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var loanType = new Array();
            var val;
            $("input[name='loanTypeSection[]']").each(function(){
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

    var counter = '{{ $counter }}';
    function addMore(){
        counter++;
        var data = '<tr id="sectionAddMore_'+counter+'"><td><input type="number" step="any" name="amount[]" id="amount_'+counter+'" value="" class="form-control requiredField" /></td>' +
                '<td><input type="month" name="month_year[]" id="month_year_'+counter+'" value="" class="form-control requiredField" /></td><td class="text-center"><a onclick="removeAddMoreSection('+counter+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a></td></tr>';
        $('#addMoreSection').append(data);
    }

    function removeAddMoreSection(param){
        var elem = document.getElementById('sectionAddMore_'+param+'');
        elem.parentNode.removeChild(elem);
    }


</script>