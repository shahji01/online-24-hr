<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    {{ Form::open(array('url' => 'had/editSubDepartmentDetail')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="formSection[]" id="formSection" value="1" />
                    <input type="hidden" name="id" id="id" value="{{ $sub_departments->id }}" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}" />
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Select Department:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="department_id" id="department_id">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $key => $y)
                                            <option @if($y->id == $sub_departments->department_id) selected @endif value="{{ $y->id}}">{{ $y->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Sub Department Name:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="sub_department_name" id="sub_department_name" value="{{$sub_departments->sub_department_name}}" class="form-control requiredField" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
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
        // $('#department_id_1').select2();
        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var subDepartment = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                subDepartment.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val in subDepartment) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }
        });
        $('#department_id').select2();

    });
</script>
