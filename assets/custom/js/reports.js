var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$('#viewTurnoverReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
   
    $.ajax({
        url: baseUrl+"/HrReports/viewTurnoverReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewOnboardReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    
    $.ajax({
        url: baseUrl+"/HrReports/viewOnboardReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewEmployeeReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
   
    $.ajax({
        url: baseUrl+"/HrReports/viewEmployeeReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewTransferReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
   
    $.ajax({
        url: baseUrl+"/HrReports/viewTransferReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewWarningReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
  
    $.ajax({
        url: baseUrl+"/HrReports/viewWarningReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewIncrementReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    
    $.ajax({
        url: baseUrl+"/HrReports/viewIncrementReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewMedicalReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    
    $.ajax({
        url: baseUrl+"/HrReports/viewMedicalReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewTrainingReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    
    $.ajax({
        url: baseUrl+"/HrReports/viewTrainingReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewGratuityReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    
    $.ajax({
        url: baseUrl+"/HrReports/viewGratuityReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

$('#viewEmployeeExpReportForm').click(function() {
    $("#reportsArea").css({"display": "none"});
    $('#report-area').html('');
    $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    
    $.ajax({
        url: baseUrl+"/HrReports/viewEmployeeExpReportForm",
        type: 'GET',
        data: {m : m},
        success: function (response){
            $("#reportsArea").css({"display": "block"});
            $('#report-form-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
            $('#report-form-area').html(response);

        }
    });
});

