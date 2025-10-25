var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function() {
    $('#sub_department_id').select2();
    $('#department_id').select2();
    $('#emp_id').select2();


    var EOBI = 1;
    $('.addMoreTaxesSection').click(function (e){
        e.preventDefault();
        EOBI++;
        $('.TaxesSection').append('<div class="row myloader_'+EOBI+'"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>')
        $.ajax({
            url: baseUrl+'/hmfal/makeFormTaxesDetail',
            type: "GET",
            data: { id:EOBI},
            success:function(data) {
                $('.TaxesSection').append('<div id="sectionTaxes_'+EOBI+'"><a style="cursor:pointer;" onclick="removeTaxesSection('+EOBI+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                $('.myloader_'+EOBI).remove();
            }
        });
    });

    // Wait for the DOM to be ready
    $(".btn-success").click(function(e){
        // var lifeInsurance = new Array();
        // var val;
        // $("input[name='lifeInsuranceSection[]']").each(function(){
        //     lifeInsurance.push($(this).val());
        // });
        var _token = $("input[name='_token']").val();
        // for (val in lifeInsurance) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        // }

    });

});

function removeTaxesSection(id){
    var elem = document.getElementById('sectionTaxes_'+id+'');
    elem.parentNode.removeChild(elem);
}

$(document).ready(function() {

    var table = $('#TaxesList').DataTable({
        "dom": "t",
        "bPaginate" : false,
        "bLengthChange" : true,
        "bSort" : false,
        "bInfo" : false,
        "bAutoWidth" : false

    });

    $('#emp_id_search').keyup( function() {
        table.search(this.value).draw();
    });

});

function deleteRowMasterTable(id,tableName){
    var id;
    var tableName;

    if(confirm("Do you want to delete this record ?") == true){

        $.ajax({
            url: baseUrl+'/deleteMasterTableReceord',
            type: "GET",
            data: {id:id,tableName:tableName},
            success:function(data) {
                location.reload();
            }
        });
    }
    else{
        return false;
    }

}

function viewProvidentFundReport(){
    $("#employeePayslipSection").css({"display": "none"});
    var company_id = $('#company_id').val();
    jqueryValidationCustom();
    if(validate == 0){
        $("#employeePayslipSection").css({"display": "block"});
        $('#run_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url: baseUrl+'/hdc/viewProvidentFundReport',
            type: "GET",
            data: { company_id:company_id,m:m},
            success:function(data) {
                $('.employeePayslipSection').empty();
                $('.employeePayslipSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
                $('#run_loader').html('');
            }
        });
    }else{
        return false;
    }
}
