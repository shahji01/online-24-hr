var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function() {

    // Wait for the DOM to be ready
    $(".btn-success").click(function(e){
        var leaveType = new Array();
        var val;
        $("input[name='formSection[]']").each(function(){
            leaveType.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val in leaveType) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }
    });

    viewLeaveTypeListDetail();
});

var counter = 1;
function addMoreRow() {
    counter++;
    $('#addMoreSection').append('<div id="sectionAddMore_'+counter+'"><div class="lineHeight">&nbsp;</div>' +
        '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
        '<span><label>Leave Type Name:</label><a href="#" onclick="removeAddMoreSection('+counter+')" class="btn btn-sm btn-danger" style="float: right;">X</a></span>' +
        '<span class="rflabelsteric"><strong>*</strong> </span>' +
        '<input type="text" name="leave_type_name[]" id="leave_type_name_'+counter+'" class="form-control requiredField" /></div></div></div>')
}

function removeAddMoreSection(id) {
    var elem = document.getElementById('sectionAddMore_'+id+'');
    elem.parentNode.removeChild(elem);
}

function viewLeaveTypeListDetail() {
    $('#tableData').html('<div class="loader"></div>');
    $.ajax({
        url: baseUrl+'/hdc/viewLeaveTypeListDetail',
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