<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><b><u>Increment Report Form</u></b></h3>
    </div>
</div>
<hr>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>From</label>
                <input type="date" class="form-control requiredField" id="from" name="from" required>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>To</label>
                <input type="date" class="form-control requiredField" id="to" name="to" required>
            </div>

        </div>
        <br>
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <span style="margin: 10px;">
                   <label >Show All</label>
                   <input type="checkbox" class="checkbox" id="show_all" name="show_all" value="1">
                </span>
                <button style="margin-bottom: 1px;" class="btn btn-sm btn-info" id="search" style="margin-top: 32px"><i class="fas fa-search fa"></i> Search</button>
            </div>

        </div>
    </div>
</div>

<input type="hidden" id="baseUrl" value="{{ url('/') }}">
<script>
    var baseUrl=$('#baseUrl').val();
    $(document).ready(function(){

        $('.checkbox').change(function () {
            if ($(this).is(':checked')) {
                $('#from').attr('disabled', true);
                $('#to').attr('disabled', true);
                $('#from').removeClass('requiredField').removeAttr('required');
                $('#to').removeClass('requiredField').removeAttr('required');
            } else {
                $('#from').attr('disabled', false);
                $('#to').attr('disabled', false);
                $('#from').addClass('requiredField').attr('required');
                $('#to').addClass('requiredField').attr('required');
            }
        });

        $(".btn-info").click(function(e){
            var degreeType = new Array();
            var val;
            $("input[name='HrReports[]']").each(function(){
                degreeType.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of degreeType) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });


        $('#search').click(function() {
            var from = $('#from').val();
            var to = $('#to').val();
            var show_all = $("input[name='show_all']:checked"). val();
            $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo Input::get('m'); ?>';
            jqueryValidationCustom();
            if(validate == 0)
            {
                $.ajax({
                    url: baseUrl+"/HrReports/viewIncrementReport",
                    type: 'GET',
                    data: {m : m, from:from, to:to, show_all:show_all},
                    success: function (response){
                        $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                        $('#report-area').html(response);

                    }
                });
            }
            else
            {
                $('#report-area').html('');
                return false;
            }
        });

    });



</script>