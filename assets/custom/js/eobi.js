var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function() {

    $(".btn-success").click(function(e){
        var EOBISection = new Array();
        var val;
        $("input[name='formSection[]']").each(function(){
            EOBISection.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val in EOBISection) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }
    });

    viewEOBIListDetail();
});

function viewEOBIListDetail() {
    $('#tableData').html('<div class="loader"></div>');
    $.ajax({
        url: baseUrl+'/hdc/viewEOBIListDetail',
        type: "GET",
        data:{m:m},
        success: function(res) {
            $('#tableData').html(res);
        },
        error: function(error) {
            $('#tableData').html('');
        }
    });
}
