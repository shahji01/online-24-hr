var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function() {
    $('#year').select2();
    viewHolidayCalender();
});

function viewHolidayCalender()
{
    var month_year = $('#year').val();
    var rights_url = 'hr/createHolidaysForm';
    $('#calender').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    $.ajax({
        url: baseUrl+'/hdc/viewHolidayCalender',
        type: "GET",
        data: {month_year:month_year, m:m, rights_url:rights_url},
        success: function (data) {
            $('#calender').html(data);
        },
        error: function () {
            console.log("error");
        }
    });
}

function functionModal(param1)
{
    showDetailModelFourParamerter("hdc/viewHolidaysDetail",param1,"Holiday Detail",m,"hr/createHolidaysForm");
}

function addHolidaysDetail(day, month, year) {
    var holiday_date = year + '-' + month + '-' + day;
    var holiday_reason = prompt('Holiday Reason');
    if (!holiday_reason) {
        alert('Holiday Reason Required !');
        return false;
    }
    else if (holiday_reason != '') {
        $.ajax({
            url: baseUrl+'/had/addHolidaysDetail',
            type: "GET",
            data: {
                day: day,
                month: month,
                year: year,
                holiday_reason: holiday_reason,
                holiday_date: holiday_date,
                company_id: m
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                console.log("error");
            }
        });
    }
}