var m=$('#m').val();
var baseUrl=$('#baseUrl').val();
var emp_id=$('#employeeid').val();

$("#basic_info_form").submit(function (e) {

    e.preventDefault();
    var name = $("#name").val();
    var p_email = $("#p_email").val();
    var cnic = $("#cnic").val();
    var phone = $("#phone").val();
    var dob = $("#dob").val();
    var url = baseUrl+'/ddc/basicInfo';
    jqueryValidationCustom()
    if (validate == '0') {
        $.ajax({
            type: 'GET',
            url: url,
            data: {name: name, p_email: p_email, cnic: cnic, phone: phone, dob: dob, emp_id: emp_id, m: m},
            success: function (res) {
                if (res == 1) {
                    $('#error_cnic').hide().html('<strong>Cnic Already Exists !!</strong>').fadeIn('slow').delay(6000).hide(1);
                } else {
                    $("#name").val(res[0]);
                    $("#p_email").val(res[1]);
                    $("#cnic").val(res[2]);
                    $("#phone").val(res[3]);
                    $("#dob").val(res[4]);

                    $("#username").html(res[0]);
                    $("#p_email_1").html(res[1]);
                    $("#cell_1").html(res[3]);
                    $("#dob_1").html(res[4]);
                    $("#name_1").html(res[0]);
                    $('#response').hide().html('<strong>Successfully Updated !!</strong>').fadeIn('slow').delay(3000).hide(1);
                }
            }
        });
    }
});



function showPrilvigesMenu(name,type)
{
    if(type == 'master')
    {
        if($('#'+name+'_master').is(':checked'))
        {
            $('#Prilviges_master_'+name).css("display","block");
        }
        else
        {
            $('#Prilviges_master_'+name).css("display","none");
        }
    }

    if($('#'+name).is(':checked'))
    {
        $('#Prilviges_'+name).css("display","block");
    }
    else
    {
        $('#Prilviges_'+name).css("display","none");
    }
}
$(function(){
    $('#approval_code_check').click(function(){
        if($(this).is(":checked") == true)
        {
            $("#sampleField").remove();
            $(".approval_area").append('<input class="form-control requiredField" required id="approval_code" name="approval_code" type="text" >');
        }
        else
        {
            $("#approval_code").remove();
            $(".approval_area").append('<input class="form-control" disabled="disabled" id="sampleField">');
        }
    });
});

$(document).ready(function () {
    $('#department_id_').select2();
    $('#sub_department_id_1').select2();
    $('#emp_id').select2();
});
function checkRegions()
{
    var counter = 0;
    $.each($("input[class='regions']:not(:checked)"), function(){
        counter++;
    });
    if(counter == 3)
    {

        $('.regionError').html('<span class="label label-danger">Please Select at least one Region.</span>');
        $('.btn').attr('disabled','disabled');
    }
    else{
        $('.regionError').html('');
        $('.btn').removeAttr('disabled')

    }
}


function getEmployee(){
    var m = $("#company_id").val();
    var department = $("#sub_department_id_1").val();
    var employee_project_id = $("#employee_project_id").val();

    if(department == '' && employee_project_id != ''){
        alert('Please Select Department');
    }
    if(department != '' && employee_project_id == ''){
        data = {department:department,employee_project_id:'0',m:m}
    }
    else if(department != '' && employee_project_id != ''){
        data = {department:department,employee_project_id:employee_project_id,m:m}
    }
    if(department != ''){
        $('#emp_loader_1').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            type:'GET',
            url:baseUrl+'/slal/getSubDepartment',
            data:data,
            success:function(res){
                $('#emp_loader_1').html('');
                $('select[name="emp_id"]').empty();
                $('select[name="emp_id"]').html(res);
                $("#emp_id").prepend("<option value='' selected='selected'>Select Employee</option>");
            }
        })
    }
    else{

        $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
        $('select[name="sub_department_id_1"]').empty();
        $('select[name="emr_no"]').empty();
    }
}
$( document ).ready(function() {
    $('#emp_category_id').select2();
    $('#region_id').select2();
    $('#emr_no').select2();
});


function showPrilvigesMenu(name,type)
{
    if(type == 'master')
    {
        if($('#'+name+'_master').is(':checked'))
        {
            $('.'+name+'_child').prop('checked', true);
            $('#Prilviges_master_'+name).css("display","block");
        }
        else
        {
            $('.'+name+'_child').prop('checked', false);
            $('#Prilviges_master_'+name).css("display","none");
        }
    }

    if($('#'+name).is(':checked'))
    {
        $('.'+name+'_child').prop('checked', true);
        $('#Prilviges_'+name).css("display","block");
    }
    else
    {
        $('.'+name+'_child').prop('checked', false);
        $('#Prilviges_'+name).css("display","none");
    }
}
$(function(){
    $('select[name="sub_department_id"]').on('change', function() {
        $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        var department_id = $(this).val();
        if(department_id == ''){$('#emp_loader').html('');}

        if(department_id) {
            $.ajax({
                url: baseUrl+'/slal/MachineEmployeeListDeptWise',
                type: "GET",
                data: { department_id:department_id,m:m},
                success:function(data) {
                    $('#emp_loader').html('');
                    $('select[name="emr_no"]').empty();
                    $('select[name="emr_no"]').html(data);
                    $('#emr_no').find('option').get(0).remove();
                }
            });
        }else{
            $('select[name="employee_id"]').empty();
        }
    });

    $('#approval_code_check').click(function(){
        if($(this).is(":checked") == true)
        {
            $("#sampleField").remove();
            $(".approval_area").append('<input class="form-control requiredField" required id="approval_code" name="approval_code" type="text" >');
        }
        else
        {
            $("#approval_code").remove();
            $(".approval_area").append('<input class="form-control" disabled="disabled" id="sampleField">');
        }
    });
});
$(document).ready(function () {
    $('#sub_department_id').select2();
    $('#emr_no').select2();
});
function checkRegions()
{
    var counter = 0;
    $.each($("input[class='regions']:not(:checked)"), function(){
        counter++;
    });
    if(counter == 3)
    {

        $('.regionError').html('<span class="label label-danger">Please Select at least one Region.</span>');
        $('.btn').attr('disabled','disabled');
    }
    else{
        $('.regionError').html('');
        $('.btn').removeAttr('disabled')

    }
}
$(function(){
    $('select[name="emp_category_id"]').on('change', function() {
        var emp_category_id = $(this).val();
        var region_id = $("#region_id").val();
        if(region_id == ''){alert('Please Select Region !');return false;}

        if(emp_category_id) {
            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: baseUrl+'/slal/getEmployeeCategoriesList',
                type: "GET",
                data: { emp_category_id:emp_category_id,region_id:region_id,m:m},
                success:function(data) {
                    $('#emp_loader').html('');
                    $('select[name="emr_no"]').empty();
                    $('select[name="emr_no"]').html(data);
                    $('select[name="emr_no"]').find('option').get(0).remove();

                }
            });
        }else{
            $('select[name="emr_no"]').empty();
        }
    });
});







