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

    viewMaritalStatusListDetail();

});

var counter = 1;
function addMoreRow() {
    counter++;
    $('#addMoreSection').append('<div id="sectionAddMore_'+counter+'"><div class="lineHeight">&nbsp;</div>' +
        '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
        '<span><label>Marital Status Name:</label><a href="#" onclick="removeAddMoreSection('+counter+')" class="btn btn-sm btn-danger" style="float: right;">X</a></span>' +
        '<span class="rflabelsteric"><strong>*</strong> </span>' +
        '<input type="text" name="marital_status_name[]" id="marital_status_name_'+counter+'" class="form-control requiredField" /></div></div></div>')
}

function removeAddMoreSection(id) {
    var elem = document.getElementById('sectionAddMore_'+id+'');
    elem.parentNode.removeChild(elem);
}

function viewMaritalStatusListDetail() {
    $('#tableData').html('<div class="loader"></div>');
    $.ajax({
        url: baseUrl+'/hdc/viewMaritalStatusListDetail',
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