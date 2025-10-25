<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    {{ Form::open(array('url' => 'had/editDesignationDetail')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="formSection[]" id="formSection" value="1" />
                    <input type="hidden" name="id" id="id" value="{{ $designations->id }}" class="form-control requiredField" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" class="form-control requiredField" />
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Designation Name:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="designation_name" id="designation_name" value="{{ $designations->designation_name }}" class="form-control requiredField" />
                                </div>
                                
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="text-dark sf-label">Select Grade</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="grade_id" id="grade_id">
                                            <option value="">Select Grade</option>
                                            @foreach($grade_type as $key => $val)
                                                <option @if($designations->grade_id == $val->id) selected @endif value="{{ $val->id}}">{{ $val->employee_grade_type}}</option>
                                            @endforeach
                                        </select>
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
    </div>
</div>

<script>
    $(document).ready(function() {

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var designation = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                designation.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val in designation) {

                jqueryValidationCustom();
                if(validate == 0){

                }else{
                    return false;
                }
            }
        });
    });

</script>