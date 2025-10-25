var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

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

    // $('#deduction_type_search').select2();

    $('#once').click(function (e){
        if($("#once").is(':checked')){
            $(".once_area").html('<label>Month - Year</label><span class="rflabelsteric"><strong>*</strong></span><input type="month" class="form-control requiredField" name="month_year" id="month_year">')
        } else{
            $(".once_area").html('')
        }

    });

});





