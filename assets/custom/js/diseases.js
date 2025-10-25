var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function() {

    var table = $('#DiseaseList').DataTable({
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


$(document).ready(function() {
    // Wait for the DOM to be ready
    $(".btn-success").click(function(e){
        var diseaseType = new Array();
        var val;
        $("input[name='EmployeeDegreeTypeSection[]']").each(function(){
            diseaseType.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val in diseaseType) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });

    var category = 1;
    $('.addMoreDiseaseTypeSection').click(function (e){
        e.preventDefault();
        category++;
        $('.DiseaseTypeSection').append('<div style="margin-top: 5px;" id="sectionDiseaseType_'+category+'">' +
            '<a href="#" onclick="removeDiseaseTypeSection('+category+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>' +
            '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
            '<div class="row">' +
            '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
            ' <label>Disease Type:</label>' +
            '<span class="rflabelsteric"><strong>*</strong></span>' +
            '<input type="text" name="disease_type[] " id="disease_type[] " value="" class="form-control requiredField" required/>' +
            '</div></div></div></div></div>');

    });
});

function removeDiseaseTypeSection(id){
    var elem = document.getElementById('sectionDiseaseType_'+id+'');
    elem.parentNode.removeChild(elem);
}

$(".btn-success").click(function(e){
    var diseaseType = new Array();
    var val;
    $("input[name='diseaseTypeSection[]']").each(function(){
        diseaseType.push($(this).val());
    });
    var _token = $("input[name='_token']").val();
    for (val in diseaseType) {

        jqueryValidationCustom();
        if(validate == 0){
            //alert(response);
        }else{
            return false;
        }
    }

});