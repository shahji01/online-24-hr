<div class="well">
    <div class="panel" style="height: auto;">
        {{ Form::open(array('url' => 'had/editEmployeeLeavingDetail','id'=>'editEmployeeLeavingDetail', 'method' => 'post')) }}
        <div class="panel-body">

            <div class="label label-danger"></div>
            <div class="row">
                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="company_id" value="<?=Input::get('m')?>">
                <input type="hidden" name="recordId" value="<?=Input::get('id')?>">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="sf-label">Last Working Date</label>
                    <input type="date" id="last_working_date" name="last_working_date" class="form-control requiredField" value="<?=$empLeavingReason->value('last_working_date')?>">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="sf-label">Reason</label>
                    <select class="form-control requiredField" name="leaving_reason" id="leaving_reason">
                        <option value="">Select Reason</option>
                        <option @if($empLeavingReason->value('leaving_reason') == 'Resignation') selected @endif value="Resignation">Resignation</option>
                        <option @if($empLeavingReason->value('leaving_reason') == 'Retirement') selected @endif value="Retirement">Retirement</option>
                        <option @if($empLeavingReason->value('leaving_reason') == 'Termination') selected @endif value="Termination">Termination</option>
                        <option @if($empLeavingReason->value('leaving_reason') == 'Dismissal') selected @endif value="Dismissal">Dismissal</option>
                        <option @if($empLeavingReason->value('leaving_reason') == 'Demise') selected @endif value="Demise">Demise</option>
                        <option @if($empLeavingReason->value('leaving_reason') == 'BranchClosed') selected @endif value="BranchClosed">Branch Closed</option>
                        <option @if($empLeavingReason->value('leaving_reason') == 'ContractEnd') selected @endif value="ContractEnd">Contract End</option>
                    </select>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right"><button type="submit" class="btn btn-rounded btn-success">Update</button></div>
                </div>

            </div>

        </div>
        <?php echo Form::close();?>
    </div>
</div>

<script>

    $(document).ready(function() {

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='employeeSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of employee) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else if(validate == 1){
                    return false;
                }
            }

        })
    })
 </script>