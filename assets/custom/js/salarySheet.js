var m=$('#m').val();
var baseUrl=$('#baseUrl').val();


$("#passwordSecretbtn").on('click',function(e) {
    $("#employeePayslipSection").css({"display": "none"});

        // $("#loader").html("<img style='width:120px;' src='<?=url('/assets/images/220.gif')?>'>");
        var url=baseUrl+'/hdc/viewMySalarySheetDetail';
        $.get( url,
            { passwordSecret: $("#passwordSecret").val(),m:m})
            .done(function( data ) {
                if(data == 'err')
                {
                    $("#employeePayslipSection").css({"display": "none"});
                    $("#err").html('<span class="label label-danger">Incorrect Password !</span>');
                    $("#loader").html('');
                }
                else
                {
                    $("#employeePayslipSection").css({"display": "block"});
                    $("#err").html('');
                    $("#loader").html('');
                    $("#mainDiv").html('');
                    $("#mainDiv").html(data);
                }

            });

});


$('#passwordSecret').on('keypress',function(e) {
    if(e.which == 13) {
        $(document).ready(function(){
            $("#passwordSecretbtn").trigger("click");
        });
    }
});