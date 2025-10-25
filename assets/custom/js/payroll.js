var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function(){
    // $('#sub_department_id').select2();
    // $('#emp_id').select2();
    // $('#department_id').select2();


});


function showPayrollReport(){
    $("#employeeAttendenceReportSection").css({"display": "none"});
    var month_year = $('#month_year').val();
    var company_id = m;
    jqueryValidationCustom();
    if(validate == 0){
        $("#employeeAttendenceReportSection").css({"display": "block"});
        $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        $.ajax({
            url: baseUrl+'/hdc/companyWisePayrollReport',
            type: "GET",
            data: {company_id:company_id,month_year:month_year},
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

function viewEmployeePfForm(){

    $("#employeePayslipSection").css({"display": "none"});
    $('.employeePayslipSection').empty();
    var department_id = $('#department_id').val();
    var sub_department_id = $('#sub_department_id').val();
    var project_id = $('#project_id').val();
    var location_id = $('#location_id').val();
    var category_id = $('#category_id').val();
    var employee_id = $('#employee_id').val();
    var datas = '';

    var show_all = $('#show_all').val();
    jqueryValidationCustom();

    if(validate == 0){

        $("#employeePayslipSection").css({"display": "block"});
        $('#run_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');


        var datas = {employee_id:employee_id,m:m,location_id:location_id,sub_department_id:sub_department_id,department_id:department_id,project_id:project_id,category_id:category_id}


        $.ajax({
            url: baseUrl+'/hdc/viewEmployeePfForm',
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

function viewConcileReport(){
    $("#employeeAttendenceReportSection").css({"display": "none"});
    $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    var month_from = $('#month_year_from').val();
    var month_to = $('#month_year_to').val();
    var data = '';
    jqueryValidationCustom();
    if(validate == 0){
        $("#employeeAttendenceReportSection").css({"display": "block"});

            var data = {m:m,month_to:month_to,month_from:month_from}


        $.ajax({
            url: baseUrl+'/hdc/viewConcileReport',
            type: "GET",
            data:data,
            success:function(data) {
                $('#loader').html('');
                $('.employeeAttendenceReportSection').empty();
                $('.employeeAttendenceReportSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
            }
        });
    }
    else{
        return false;
    }
}

function viewComparisonReport() {
    $("#employeeAttendenceReportSection").css({"display": "none"});
    $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    var month_from = $('#month_year_from').val();
    var month_to = $('#month_year_to').val();
    var data = '';
    jqueryValidationCustom();
    if (validate == 0) {
        $("#employeeAttendenceReportSection").css({"display": "block"});

        var data = {m: m, month_to: month_to, month_from: month_from}


        $.ajax({
            url: baseUrl + '/hdc/viewComparisonReport',
            type: "GET",
            data: data,
            success: function (data) {
                $('#loader').html('');
                $('.employeeAttendenceReportSection').empty();
                $('.employeeAttendenceReportSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' + data + '</div>');
            }
        });
    }
    else {
        return false;
    }
}

function payrollCalculation(id,netSalary,loan_per_month,total_deduction2,e)
{

    let income_tax = ( 0 > (+$("#income_tax_"+id).val()) )? 0 : (+$("#income_tax_"+id).val()) ;
    let loan_amount = ( 0 > (+$("#loan_amount_"+id).val())) ? 0 : (+$("#loan_amount_"+id).val());
    let total_deduction =  income_tax + loan_amount + (+total_deduction2 );
    netSalary = Math.round((+netSalary) - total_deduction) ;

    if(total_deduction < 0)
    {
        total_deduction = 0 ;
    }
    if(netSalary < 0)
    {
        netSalary = 0 ;
    }

    $(".net_salary2_"+id).val(netSalary);
    $(".net_salary2_"+id).html(netSalary);
    $(".total_deduction_"+id).html(total_deduction);
    $(".total_deduction_"+id).val(total_deduction);
}