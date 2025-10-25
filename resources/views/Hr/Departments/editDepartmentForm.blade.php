<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            {{ Form::open(array('url' => 'had/editDepartmentDetail')) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="formSection[]" id="formSection" value="1" />
            <input type="hidden" name="id" id="id" value="{{ $departments->id }}" class="form-control requiredField" />
            <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Department Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="department_name" id="department_name" value="{{ $departments->department_name }}" class="form-control requiredField" />
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
    $(document).ready(function() {

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var department = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                department.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val in department) {

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
