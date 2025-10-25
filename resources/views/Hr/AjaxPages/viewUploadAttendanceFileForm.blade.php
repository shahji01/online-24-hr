<?php
use App\Helpers\CommonHelper;
$m = Input::get('m');
$user_roles = CommonHelper::userRoles($m);
?>

<div class="row">
    <div class="col-12">
        {!! Form::open(array('url' => 'had/uploadAttendanceFile','method'=>'POST','files'=>'true')) !!}
        <input type="hidden" name="m" value="{{ Input::get('m') }}">
        <input type="hidden" name="employeeSection[]" value="1">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="sf-label">Select File to Import:</label>
                                    <span class="rflabelsteric">* (.XLSX file Supported)</span>
                                    {!! Form::file('sample_file', array('class' => 'form-control requiredField','id'=>'sample_file','required'=>'required','onChange'=>'getoutput()')) !!}
                                    <span id="extension_err_messg" style="color:red;"></span>
                                </div>
                            </div>
                            <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Location</label>
                                <span class="rflabelsteric">*</span>
                                <select class="form-control requiredField" id="location_id" name="location_id">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $key => $val)
                                        @if(in_array($val->id, $user_roles['location_ids']))
                                            <option value="{{ $val->id }}">{{ $val->location_code.' -- '.$val->location_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="location_loader"></span>
                            </div> -->
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 38px">
                                {!! Form::submit('Upload',['class'=>'btn btn-sm btn-success','id'=>'BtnImport']) !!}
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right" style="margin-top: 37px;">
                                <a style="color: #516bdd;font-weight: bold;" href="<?=url('/')?>/assets/sample_images/Attendance-Month-of-july.xlsx">Download Sample / Format </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script>

    $(function(){
        $('#location_id').select2();
        $(".btn-primary").click(function(e){
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
                }else{
                    return false;
                }
            }

        });
    });

    function getoutput(){
        var file_extension = sample_file.value.split('.')[1];

        if( file_extension == 'xlsx' || file_extension == 'xls' )
        {

            $("#extension_err_messg").html('');
            $("#BtnImport").removeAttr('disabled');
        }
        else
        {

            $("#BtnImport").attr('disabled','disabled');
            $("#extension_err_messg").html('Please Select xlsx File !');
        }

    }
</script>