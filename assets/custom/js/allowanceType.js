var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

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

    $('#taxable_1').select2();

    viewAllowanceTypeListDetail();

});

var counter = 1;
function addMoreRow() {
    counter++;
    $('#addMoreSection').append('<div id="sectionAddMore_'+counter+'"><div class="lineHeight">&nbsp;</div>' +
        '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
        '<label>Allowance Type:</label>' +
        '<span class="rflabelsteric"><strong>*</strong> </span>' +
        '<input type="text" name="allowance_type[]" id="allowance_type_'+counter+'" class="form-control requiredField" /></div>' +
        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><span><label>Is Taxable:</label><a href="#" onclick="removeAddMoreSection('+counter+')" class="btn btn-sm btn-danger" style="float: right;">X</a></span>' +
        '<span class="rflabelsteric"><strong>*</strong></span><select name="taxable[]" id="taxable_'+counter+'"  class="form-control requiredField">' +
        '<option selected value="1">Taxable</option><option value="2">Non Taxable</option>' +
        '</select></div></div></div>');
    $('#taxable_'+counter).select2();
}

function removeAddMoreSection(id) {
    var elem = document.getElementById('sectionAddMore_'+id+'');
    elem.parentNode.removeChild(elem);
}

function viewAllowanceTypeListDetail() {
    $('#tableData').html('<div class="loader"></div>');
    $.ajax({
        url: baseUrl+'/hdc/viewAllowanceTypeListDetail',
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
