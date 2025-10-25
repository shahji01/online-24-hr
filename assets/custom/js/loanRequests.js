var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

function getEmpLoans()
{
    $('#emp_loader_2').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    $.get( baseUrl+'/slal/viewEmployeeLoansList',
        { m:m,emp_id: $("#emp_id").val()})
        .done(function( data ) {
            $('#emp_loader_2').html('');
            $('#loan_id').html(data);

        });
}