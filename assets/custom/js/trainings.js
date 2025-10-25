var m=$('#m').val();
var baseUrl=$('#baseUrl').val();



function getEmployee(sub_val){


    var department = $("#department_id").val();


    if(sub_val=='sub_department'){
        getSubDepartments(department);
    }
    var sub_department = $("#sub_department_id").val();
    if(department == '0'){
        $("#department_id_").val('0');
        $("#sub_department").val('0');
        $('select[name="emp_id"]').empty();
        $("#emp_id").prepend("<option value='0'>-</option>");
        return false;
    }
    if(department != '0' && sub_department == ''){
        data = {department:department,sub_department:'0',m:m}
    }
    else if(department != '' && sub_department != ''){
        data = {department:department,sub_department:sub_department,m:m}
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
                

            }
        })
    }
    else{
        $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
        $('select[name="sub_department_id_1"]').empty();
        $('select[name="emr_no"]').empty();
    }
}

$(document).ready(function() {
    
    $('#emp_id').select2();
    $('#sub_department_id').select2();
    $("#department_id").select2();
    $("#training_id").select2();
    
    
    
    var table = $('#AdvancedSalayList').DataTable({
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



function viewEmployeeTrainingForm(){
    
    $("#employeePayslipSection").css({"display": "none"});
    $('.employeePayslipSection').empty();
    var emp_id = $('#emp_id').val();
    var sub_department = $("#sub_department_id").val();
    var department_id = $("#department_id").val();
    var training_id = $("#training_id").val();
    var datas = '';
    
    var show_all = $('#show_all').val();
    jqueryValidationCustom();
   
    if(validate == 0){

        $("#employeePayslipSection").css({"display": "block"});
        $('#run_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        if ($("#show_all").is(":checked")) {

            var datas = {show_all:show_all,emp_id:emp_id,m:m,sub_department:sub_department,department_id:department_id,training_id:training_id}
        }
        else{
            var datas = {emp_id:emp_id,m:m,sub_department:sub_department,department_id:department_id,training_id:training_id}
        }
        
        $.ajax({
            url: baseUrl+'/hdc/viewEmployeeTrainingForm',
            type: "GET",
            data: datas,
            success:function(data) {

                $('.employeePayslipSection').empty();
                $('.employeePayslipSection').append('<div class="">'+data+'</div>');
                $('#run_loader').html('');
                // $('#TaxesList ').tableHover({colClass: 'hover'});
            }
        });
    }else{
        return false;
    }
}



