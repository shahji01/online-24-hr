var m = $('#m').val();
var baseUrl = $('#baseUrl').val();

$(document).ready(function () {

    var table = $('#att_request_list').DataTable({
        "dom": "t",
        "bPaginate": false,
        "bLengthChange": true,
        "bSort": false,
        "bInfo": false,
        "bAutoWidth": false
    });

    $('#emp_id_search').keyup(function () {
        table.search(this.value).draw();
    });

});


function viewEmployeesAttendanceReport() {
    $("#employeePayslipSection").css({ "display": "none" });
    $('.employeePayslipSection').empty();

    var department_id = $('#department_id').val();
    var sub_department_id = $('#sub_department_id').val();
    var project_id = $('#project_id').val();
    var category_id = $('#category_id').val();
    var location_id = $('#location_id').val();
    var employee_id = $('#employee_id').val();
    var from = $('#from').val();
    var to = $('#to').val();


    var datas = '';
    var show_all = $('#show_all').val();
    jqueryValidationCustom();
    if (validate == 0) {
        $("#employeePayslipSection").css({ "display": "block" });
        $('#run_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        if ($("#show_all").is(":checked")) {

            var datas = { show_all: show_all, project_id: project_id, location_id:location_id,category_id: category_id, employee_id: employee_id, from: from, to: to, m: m, sub_department_id: sub_department_id, department_id: department_id }
        }
        else {
            var datas = { project_id: project_id, category_id: category_id, location_id:location_id , employee_id: employee_id, from: from, to: to, m: m, sub_department_id: sub_department_id, department_id: department_id }
        }
        $.ajax({
            url: baseUrl + '/hdc/fetchEmployeesAttendanceReport',
            type: "GET",
            data: datas,
            success: function (data) {

                $('.employeePayslipSection').empty();
                $('.employeePayslipSection').append('<div class="">' + data + '</div>');
                $('#run_loader').html('');
                // $('#TaxesList ').tableHover({colClass: 'hover'});
            }
        });
    } else {
        return false;
    }
}
function viewAttendanceProgress() {

    var department_id = $('#department_id').val();
    var sub_department_id = $('#sub_department_id').val();
    var project_id = $('#project_id').val();
    var category_id = $('#category_id').val();
    var employee_id = $('#employee_id').val();

    var month_year = $('#month_year').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var show_all = $('#show_all').val();
    var data = '';

    var explode_from = from_date.split("-");
    var explode_to = to_date.split("-");

 

    if ($("#show_all").is(":checked")) {
        var data = { show_all: show_all, month_year: month_year, category_id: category_id, project_id: project_id, m: m, employee_id: employee_id, department_id: department_id, from_date: from_date, to_date: to_date, sub_department_id: sub_department_id }
    }
    else {
        var data = { month_year: month_year, m: m, employee_id: employee_id, category_id: category_id, project_id: project_id, department_id: department_id, from_date: from_date, to_date: to_date, sub_department_id: sub_department_id }
    }
    jqueryValidationCustom();
    if(validate == 0){

    $("#employeeAttendenceReportSection").css({ "display": "block" });
    $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    $.ajax({
        url: baseUrl + '/hdc/viewAttendanceProgress',
        type: "GET",
        data: data,
        success: function (data) {
            $('#loader').html('');
            $('.employeeAttendenceReportSection').empty();
            $('.employeeAttendenceReportSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' + data + '</div>');
        }
    });

}
}

$("#show_all").change(function () {
    if ($('#show_all').is(':checked')) {
        $('#department_id').prop("disabled", true);
        $('#sub_department_id').prop("disabled", true);
        $('#emp_id').prop("disabled", true);
        $('#department_id').removeClass("requiredField");
        $('#sub_department_id').removeClass("requiredField");
        $('#emp_id').removeClass("requiredField");
    }
    else {
        $('#department_id').prop("disabled", false);
        $('#sub_department_id').prop("disabled", false);
        $('#emp_id').prop("disabled", false);
        $('#department_id').addClass("requiredField");
        $('#sub_department_id').addClass("requiredField");
        $('#emp_id').addClass("requiredField");
    }

});

function approveProgress() {
    //$('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    //var check = $('.ads_Checkbox').val();

    var favorite = [];
    $.each($("input:checkbox[name='check_list']:checked"), function () {
        favorite.push($(this).val());
    });
    //alert(favorite);


    jqueryValidationCustom();
    if (validate == 0) {
        $.ajax({
            url: baseUrl + '/hedbac/approveEmployeePayrollDetail',
            type: "GET",
            data: { m: m, val: favorite },
            success: function (data) {
                attendanceProgressFilteredList();
                //location.reload();

            }
        });
    } else {
        return false;
    }
}

function rejectProgress() {
    //$('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    //var check = $('.ads_Checkbox').val();

    var favorite = [];
    $.each($("input:checkbox[name='check_list']:checked"), function () {
        favorite.push($(this).val());
    });
    //alert(favorite);


    jqueryValidationCustom();
    if (validate == 0) {
        $.ajax({
            url: baseUrl + '/hedbac/rejectEmployeePayrollDetail',
            type: "GET",
            data: { m: m, val: favorite },
            success: function (data) {
                attendanceProgressFilteredList();
                //location.reload();

            }
        });
    } else {
        return false;
    }
}

$(function () {
    $('select[name="department_id"]').on('change', function () {

        $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        var department_id = $(this).val();

        if (department_id) {
            $.ajax({
                url: baseUrl + '/slal/MachineEmployeeListDeptWise',
                type: "GET",
                data: { department_id: department_id, m: m },
                success: function (data) {

                    $('#emp_loader').html('');
                    $('select[name="employee_id"]').empty();
                    $('select[name="employee_id"]').html(data);


                }
            });
        } else {
            $('select[name="employee_id"]').empty();
        }
    });
});
$(function () {
    $("#check_all").click(function () {

        if ($("#check_all").prop("checked") == true) {
            $(".ads_Checkbox").prop("checked", true);
        }
        else {
            $(".ads_Checkbox").prop("checked", false);
        }


    });
});

function attendanceProgressFilteredList() {

    var month_year = $('#month_year').val();
    var m = $('#company_id').val();
    var accType = $('#accType').val();
    var acc_emp_id = $('#acc_emp_id').val();
    jqueryValidationCustom();
    if (validate == 0) {
        $('#employee-list').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url: baseUrl + "/hdc/attendanceProgressFilteredList",
            type: 'GET',
            data: { m: m, month_year: month_year, accType: accType, acc_emp_id: acc_emp_id },
            success: function (response) {

                $('#employee-list').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                var result = response;
                $('#employee-list').append(result);
            }
        });
    } else {
        return false;
    }
}