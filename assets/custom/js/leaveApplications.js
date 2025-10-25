var m=$('#m').val();
var baseUrl=$('#baseUrl').val();
var a = '' ;

function viewEmployeeLeavesDetail(id, no_of_leaves, leave_type_id) {
    $('#leave_days_area').html('');
    $('#leavesData').html('<div class="row" style="background-color: gainsboro"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
        '<h4>Full Day Leave :<input class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="full_day_leave" onclick="leaves_day_type(this.value)" /></h4>' +
        '</div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><h4>Half Day Leave :<input type="radio" name="leave_day_type" id="leave_day_type" value="half_day_leave" onclick="leaves_day_type(this.value)" />' +
        '</h4></div></div>');
}

function leaves_day_type(type) {
    var leave_type = $("input[id='leave_type']:checked").val();

    if (type == 'full_day_leave') {

        $('#leave_days_area').html('<div class="row">' +
            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
            '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date" onchange="calculateNumberOfDates(this.value,1)"> </div>' +
            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
            '<label> Leave Till </label><input type="date" class="form-control requiredField"  name="to_date" id="to_date" onchange="calculateNumberOfDates(this.value,2)"></div>' +
            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
            '<label> No. of Days</label><input type="number" readonly class="form-control requiredField" id="no_of_days" name="no_of_days">' +
            '<span id="warning_message" style="color:red"></span></div></div>');

    } else if (type == 'half_day_leave') {

        $('#leave_days_area').html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
            '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
            '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
            '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
            '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date" onchange="calculateNumberOfDates(this.value,1)"> </div></div>'
        );
    }
}

function check_reason(val) {
    if(val == 7) {
        $('#reason_area').append('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+
            '<label>Enter Your Reason</label><textarea id="Otherreason" class="form-control requiredField">-</textarea>'+
            '</div></div>');
    } else {
        $('#reason_area').html('');
    }
}

function approveAndRejectLeaveApplication(recordId,approval_status,leave_day_type)
{
    var check = (approval_status == 2) ? "Approve":"Reject";
    var companyId = m;
    swal({
        title: check,
        text: "Are you sure you want to "+check+" this application",
        buttons: true,
    }).then((isConfirm) => {
        if (isConfirm) {
            $.ajax({
                url: baseUrl + '/cdOne/approveAndRejectLeaveApplication',
                type: "GET",
                data: {companyId: companyId, recordId: recordId, approval_status: approval_status},
                success: function (data) {
                    $('#showDetailModelTwoParamerter').modal('hide');
                    viewFilteredLeaveRequestList();
                    swalUpdate();
                },
                error: function(error) {
                    swalError();
                }
            });
        }
    });
}

function approveAndRejectLeaveApplication2(recordId,approval_status_lm,leave_day_type)
{
    var check = (approval_status_lm == 2) ? "Approve":"Reject";
    var companyId = m;
    swal({
        title: check,
        text: "Are you sure you want to "+check+" this application",
        buttons: true,
    }).then((isConfirm) => {
        if (isConfirm) {
            $.ajax({
                url: baseUrl + '/hdc/approveAndRejectLeaveApplication2',
                type: "GET",
                data: {companyId: companyId, recordId: recordId, approval_status_lm: approval_status_lm},
                success: function (data) {
                    $('#showDetailModelTwoParamerter').modal('hide');
                    viewFilteredLeaveRequestList();
                    swalUpdate();
                },
                error: function(error) {
                    swalError();
                }
            });
        }
    });
}

//function viewLeavesBalances(){
//    $("#employeeAttendenceReportSection").css({"display": "none"});
//    var company_id = $('#company_id').val();
//    var leaves_policy_id = $("#leaves_policy_id").val();
//    jqueryValidationCustom();
//    if(validate == 0){
//        $("#employeeAttendenceReportSection").css({"display": "block"});
//        $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
//        $.ajax({
//            url: baseUrl+'/hdc/viewLeavesBalances',
//            type: "GET",
//            data: {company_id:company_id,leaves_policy_id:leaves_policy_id},
//            success:function(data) {
//                $('#loader').html('');
//                $('.employeeAttendenceReportSection').empty();
//                $('.employeeAttendenceReportSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
//            }
//        });
//    }else{
//        return false;
//    }
//}