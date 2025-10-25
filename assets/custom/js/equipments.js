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
    viewEquipmentsListDetail();
});

var counter = 1;
function addMoreRow() {
    counter++;
    $('#addMoreSection').append('<div id="sectionAddMore_'+counter+'"><div class="lineHeight">&nbsp;</div>' +
        '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
        '<span><label>Equipment Name:</label><a href="#" onclick="removeAddMoreSection('+counter+')" class="btn btn-sm btn-danger" style="float: right;">X</a></span>' +
        '<span class="rflabelsteric"><strong>*</strong> </span>' +
        '<input type="text" name="equipment_name[]" id="equipment_name_'+counter+'" class="form-control requiredField" /></div></div></div>')
}

function removeAddMoreSection(id) {
    var elem = document.getElementById('sectionAddMore_'+id+'');
    elem.parentNode.removeChild(elem);
}

function viewEquipmentsListDetail() {
    $('#tableData').html('<div class="loader"></div>');
    $.ajax({
        url: baseUrl+'/hdc/viewEquipmentsListDetail',
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



function viewEmployeeEquipmentsForm()
{
    $("#equipmentSection").css({"display": "none"});
    $('.equipmentSectionLoader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    var emp_id = $('#emp_id').val();

    if(emp_id) {
        $("#equipmentSection").css({"display": "block"});
        $.ajax({
            url: baseUrl+'/hdc/viewEmployeeEquipmentsForm',
            type: "GET",
            data: { emp_id:emp_id,m:m},
            success:function(data) {
                $('.equipmentSection').html(data);
                $('.equipmentSectionLoader').html('');
            },
            error: function () {
                $('.equipmentSectionLoader').html('');
                $('.equipmentSection').html('');
            }
        });
    }
}


function insuranceCheck()
{
    if ($('.insurance').is( ":checked" )) {
        $('#insurance_number').prop("disabled", false);
        $('#insurance_path').prop("disabled", false);
    }
    else {
        $('#insurance_number').prop("disabled", true);
        $('#insurance_path').prop("disabled", true);
    }

}

function eobiCheck()
{
    if ($('.eobi').is( ":checked" )) {
        $('#eobi_number').prop("disabled", false);
        $('#eobi_path').prop("disabled", false);
    }
    else {
        $('#eobi_number').prop("disabled", true);
        $('#eobi_path').prop("disabled", true);
    }

}

function mobileCheck()
{
    if ($('.mobile').is( ":checked" )) {
        $('#model_number').prop("disabled", false);
        $('#mobile_number').prop("disabled", false);
        $('#sim_number').prop("disabled", false);
    }
    else {
        $('#model_number').prop("disabled", true);
        $('#mobile_number').prop("disabled", true);
        $('#sim_number').prop("disabled", true);
    }

}



function deleteEmployeeEquipments(companyId,recordId){
    var companyId;
    var recordId

    if(confirm("Do you want to delete this record ?") == true){
        $.ajax({
            url: baseUrl+'/cdOne/deleteEmployeeEquipments',
            type: "GET",
            data: {companyId:companyId,recordId:recordId},
            success:function(data) {
                location.reload();
            }
        });
    }
    else{
        return false;
    }

}

$(document).ready(function() {
    if ($('.insurance').is( ":checked" )) {
        $('#insurance_number').prop("disabled", false);
        $('#insurance_path').prop("disabled", false);
    }
    else {
        $('#insurance_number').prop("disabled", true);
        $('#insurance_path').prop("disabled", true);
    }

    if ($('.eobi').is( ":checked" )) {
        $('#eobi_number').prop("disabled", false);
        $('#eobi_path').prop("disabled", false);
    }
    else {
        $('#eobi_number').prop("disabled", true);
        $('#eobi_path').prop("disabled", true);
    }

    if ($('.mobile').is( ":checked" )) {
        $('#model_number').prop("disabled", false);
        $('#mobile_number').prop("disabled", false);
        $('#sim_number').prop("disabled", false);
    }
    else {
        $('#model_number').prop("disabled", true);
        $('#mobile_number').prop("disabled", true);
        $('#sim_number').prop("disabled", true);
    }
});


function insuranceCheck()
{
    if ($('.insurance').is( ":checked" )) {
        $('#insurance_number').prop("disabled", false);
        $('#insurance_path').prop("disabled", false);
    }
    else {
        $('#insurance_number').prop("disabled", true);
        $('#insurance_path').prop("disabled", true);
    }

}

function eobiCheck()
{
    if ($('.eobi').is( ":checked" )) {
        $('#eobi_number').prop("disabled", false);
        $('#eobi_path').prop("disabled", false);
    }
    else {
        $('#eobi_number').prop("disabled", true);
        $('#eobi_path').prop("disabled", true);
    }

}

function mobileCheck()
{
    if ($('.mobile').is( ":checked" )) {
        $('#model_number').prop("disabled", false);
        $('#mobile_number').prop("disabled", false);
        $('#sim_number').prop("disabled", false);
    }
    else {
        $('#model_number').prop("disabled", true);
        $('#mobile_number').prop("disabled", true);
        $('#sim_number').prop("disabled", true);
    }

}

$('#eobi_check').click(function() {
    $('#eobi_div_hide').hide();
    $('#eobi_div_show').show();
});

$('#insurance_check').click(function() {
    $('#insurance_div_hide').hide();
    $('#insurance_div_show').show();
});
