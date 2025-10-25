var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function() {
    $('.summernote').summernote({
        height: 200
    });

    $('#leaves_type_id_1').select2();
    $('#emp_id').select2();
    $('#department_id').select2();
    $('#sub_department_id').select2();
    $('#region_id').select2();
    $('#emp_category_id').select2();
    $('#employee_project_id').select2();

    var table = $('#exportList').DataTable({
        "dom": "t",
        "bPaginate" : false,
        "bLengthChange" : true,
        "bSort" : false,
        "bInfo" : false,
        "bAutoWidth" : false

    });

    $('#searchId').keyup( function() {
        table.search(this.value).draw();
    });

});

function LeavesCount()
{
    var sum = 0;
    $(".getLeaves").each(function(){
        sum += +$(this).val();
    });
    $("#totalLeaves").val(sum);
}


function deleteLeavesDataPolicyRows(functionName,companyId,recordId)
{
    if(confirm('Are you sure You want to delete?')){
        var main_url = baseUrl+functionName;

        $.ajax({
            url: main_url,
            type: "GET",
            data: {companyId:companyId,recordId:recordId},
            success:function(data) {
                location.reload();
            }
        });
    }

}

function checkManualLeaves(value,leave_type,error_status,m,main_id){
    var emr_no = $("#emp_id").val();
    var casual_leaves = $("#casual-leaves").html();
    var sick_leaves = $("#sick-leaves").html();
    var annual_leaves = $("#annuals-leaves").html();
    var error = 'Your'+' '+error_status+' '+'is greater than your leave policy';
    if(emr_no != null) {
        $.ajax({
            type: 'GET',
            url: baseUrl+'/hdc/checkManualLeaves',
            data: {value: value, leave_type: leave_type, error_status: error_status, m: m,emr_no:emr_no},
            success: function (res) {
                if(res != 'done'){
                    $("#"+error_status).html(res);
                    $("#"+main_id).val('');
                    $( "#create" ).prop( "disabled", true);
                }
                else{
                    $('#'+error_status).html('');
                    $( "#create" ).prop( "disabled", false );
                }
            }
        });
    }
    else{
        $("#casual_leaves").val('');
        $("#sick_leaves").val('');
        $("#annual_leaves").val('');
        alert('Please Select Employee');
    }
}


function viewLeavesBalances(){
    $("#employeeAttendenceReportSection").css({"display": "none"});
    var company_id = $('#company_id').val();
    var leaves_policy_id = $("#leaves_policy_id").val();
    jqueryValidationCustom();
    if(validate == 0){
        $("#employeeAttendenceReportSection").css({"display": "block"});
        $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url: baseUrl+'/hdc/employeeGetLeavesBalances',
            type: "GET",
            data: {company_id:company_id,leaves_policy_id:leaves_policy_id,m:m},
            success:function(data) {
                $('#loader').html('');
                $('.employeeAttendenceReportSection').empty();
                $('.employeeAttendenceReportSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
            }
        });
    }else{
        return false;
    }
}