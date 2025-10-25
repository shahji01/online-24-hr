<?php
$counter = 0;
$salarys = array();
?>
<div class="row">
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12" id="pass_eye" style="margin-top: 19px">
        <input type="password" class="form-control" name="p_user" id="p_user" placeholder="password" style="margin-left: 20px;" >
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 checkSubmit ">
        <button type="button" class="icon btn btn-success btn-sm" id="check_pass" style="margin-top: 23px;margin-right: -3px;">
            <span class="fas fa-eye fa-lg"></span>
        </button>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <br>
        <ul class="timeline">
            @if($employee_promotion->count() > 0)
                @foreach($employee_promotion->get() as $value)
                    <?php
                    $salarys[] = round($value->salary);
                    ?>
                    <li>
                        <a target="_blank" href="#">{{ $designations[$value->designation_id]->designation_name }}</a>
                        <a href="#" class="float-right">{{ date("F-d-Y", strtotime($value->date)) }}</a>
                        <h5>Salary: <span class="salary_hidden demi" id="check_{{ $counter++ }}" >{{ number_format($value->salary) }}</span></h5>
                    </li>
                @endforeach
            @endif
            <li>
                <a target="_blank" href="#">{{ $emp_data->old_designation }}</a>
                <a href="#" class="float-right">{{ date("F-d-Y", strtotime($emp_data->date)) }}</a>
                <h5>Salary: <span class="salary_hidden demi" id="check_{{ $counter++ }}" >{{ number_format($emp_data->old_salary) }}</span></h5>
                <?php $salarys[] = round($emp_data->old_salary); ?>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="bg-danger text-center" id="error_password" style="width: 100%;color:white;" ></p>
    </div>
</div>
{{--<div class="tab-pane fade in" id="JobHistory"></div>--}}
<script>
    $("#check_pass").click(function(e){
        e.preventDefault();
        var p_user = $("#p_user").val();
        var emp_id = '{{ $emp_data->emp_id }}';
        let passedArrays = '{{ json_encode($salarys) }}';
        passedArrays = jQuery.parseJSON(passedArrays);
        $.ajax({
            type:'GET',
            url: '{{ url("/") }}/ddc/checkingPassword',
            data:{p_user:p_user,emp_id:emp_id},
            success:function(res){
                if(res == 'matched'){
                    $(".demi").removeClass('salary_hidden');
                    $.notify({
                        icon: "fas fa-check-circle",
                        message: "<b>Password matched!</b>."
                    }, {
                        type: 'success',
                        timer: 3000
                    });

                    for (let x in passedArrays) {
                        $("#check_"+x).html(passedArrays[x].toLocaleString());
                    }
                }
                else{
                    //$('#error_password').hide().html('<strong>Password Not Matched !!</strong>').fadeIn('slow').delay(3000).hide(1);
                    $.notify({
                        icon: "fa fa-times-circle",
                        message: "<b>Passwprd not matched!</b>."
                    }, {
                        type: 'danger',
                        timer: 3000
                    });
                    $(".demi").addClass('salary_hidden');
                    $(".salary_hidden").html('******');
                }
            }
        });
    });
</script>