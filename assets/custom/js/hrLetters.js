var m=$('#m').val();
var baseUrl=$('#baseUrl').val();


$(document).ready(function() {

    $('#emp_id').select2();
    $('#sub_department_id').select2();
    $('#department_id').select2();
    $('#designation_id').select2();


    // Wait for the DOM to be ready
    // $(".btn-success").click(function(e){
    //     var employee = new Array();
    //     var val;
    //     $("input[name='employeeSection[]']").each(function(){
    //         employee.push($(this).val());
    //     });
    //     var _token = $("input[name='_token']").val();
    //     for (val of employee) {
    //         jqueryValidationCustom();
    //         if(validate == 0){
    //             //alert(response);
    //         }else if(validate == 1){
    //             return false;
    //         }
    //     }
    //
    // });


    $(".btn-success").click(function(e){
        $("#letter_content1").val($("#content1").find('.note-editable').html());
        $("#letter_content2").val($("#content2").find('.note-editable').html());

    });
    $('#emp_category_id').select2();
    $('#region_id').select2();
    $('#designation_id').select2();
    $('#emp_id').select2();
    $('#letter_id').select2();
    $('#employee_project_id').select2();
});

//
// function employeeProject() {
//     var emp_category_id = $("#emp_category_id").val();
//     var region_id = $("#region_id").val();
//     var employee_project_id = $("#employee_project_id").val();
//     if(employee_project_id == ''){
//         empCategory()
//     }
//     if (region_id == '') {
//         alert('Please Select Region !');
//         return false;
//     } else if (emp_category_id == '') {
//         alert('Please Select Cateogery !');
//         return false;
//     } else {
//         var m = '<?= Input::get('m'); ?>';
//         if (employee_project_id) {
//             $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
//
//             $.ajax({
//                 url: '<?php echo url('/')?>/slal/getEmployeeProjectList',
//                 type: "GET",
//                 data: {
//                     emp_category_id: emp_category_id,
//                     region_id: region_id,
//                     employee_project_id: employee_project_id,
//                     m: m
//                 },
//                 success: function (data) {
//                     $('#emp_loader').html('');
//                     $('select[name="emp_id"]').empty();
//                     $('select[name="emp_id"]').html(data);
//                 }
//             });
//         } else {
//             $('select[name="emp_id"]').empty();
//         }
//     }
// }

// function empCategory() {
//     var emp_category_id = $("#emp_category_id").val();
//     var region_id = $("#region_id").val();
//     if (region_id == '') {
//         alert('Please Select Region !');
//         return false;
//     } else {
//         var m = '<?= Input::get('m'); ?>';
//         if (emp_category_id) {
//             $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
//
//             $.ajax({
//                 url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
//                 type: "GET",
//                 data: {emp_category_id: emp_category_id, region_id: region_id, m: m},
//                 success: function (data) {
//                     $('#emp_loader').html('');
//                     $('select[name="emp_id"]').empty();
//                     $('select[name="emp_id"]').html(data);
//                 }
//             });
//         } else {
//             $('select[name="emp_id"]').empty();
//         }
//     }
// }
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

$('#letter_id').on('change', function() {
    var newLine = "\r\n";
    var emp_id = $('#emp_id').val();
    var letter_id = $('#letter_id').val();
    var hr=0;
    var basic=0;
    var utility=0;
    var salary=0;
    var designation_name="";
    $.ajax({
            type:'GET',
            url:baseUrl+'/hdc/getEmployeeDetail',
            data:{emp_id:emp_id,m:m},
            success:function(res){
                hr=res.hr;
                basic=res.basic_salary;
                utility=res.utility;
                salary=res.emp_salary;
                designation_name=res.designation_name;

                if(letter_id == 2)
                {
                    $('#details').html('<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Confirmation Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getIncrementLettersDetails()" type="date" required class="form-control requiredField" value="" name="confirmation_from" id="confirmation_from"/></div>');

                    $("#content1").find(".note-editable").html('Consequent to the review of your performance during <span class="performance_from"></span> ' +
                        ' to <span class="performance_to"></span>, we have the pleasure in informing you that, your services are being confirmed ' +
                        ' as <span class="designation"></span> w.e.f <span class="confirmation_from"></span> with' +
                        ' the salary increment of PKR: <span class="salary"></span> based on your satisfactory performance. Your revised package will be: ');
                }

                else if(letter_id == 3)
                {
                    $('#details').html(
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Performance From:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getWithoutIncrementLettersDate()" type="date" class="form-control requiredField" required value="" name="performance_from" id="performance_from"/></div>' +
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Performance To:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getWithoutIncrementLettersDate()" type="date" class="form-control requiredField" required value="" name="performance_to" id="performance_to"/></div>' +
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Confirmation Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getWithoutIncrementLettersDate()" type="date" class="form-control requiredField" required value="" name="confirmation_from" id="confirmation_from"/></div>');

                    $("#content1").find(".note-editable").html('Consequent to the review of your performance during <span class="performance_from"></span> ' +
                        'to <span class="performance_to"></span> ' +
                        'your probation period, we have the pleasure in informing you that, your services are being confirmed as Lock Smith  w.e.f <span class="confirmation_from"></span>.' +
                        '<br><br>All other terms and conditions as detailed in your appointment letter shall remain unchanged.' +
                        '<br><br>We look forward to your valuable contributions and wish you all the very best for a rewarding career with the organization.');
                }

                else if(letter_id == 4)
                {
                    $('#details').html(
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Conclude Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="date" class="form-control requiredField" onchange="getConclusionLettersDate()" value="" name="conclude_date" id="conclude_date"/></div>' +
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Final Settlement Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="date" class="form-control requiredField" onchange="getConclusionLettersDate()" value="" name="settlement_date" id="settlement_date"/></div>');
                    $("#content1").find(".note-editable").html('This has reference to the letter of appointment dated <span id="date_of_joining"></span>. In accordance with clause B-(2) of' +
                        ' the appointment letter, we hereby give you advance notice to conclude the employment contract on <span class="conclude_date"></span>,' +
                        ' Your employment with MIMA Facility Management as Tea Boy will cease on close of business on <span class="conclude_date"></span>.<br><br> ' +
                        'As stated in the clause of B-(9); you are bound not to disclose any information relating to the Company or its customers, ' +
                        'and will not divulge any of Company’s or client’s affairs or trade secrets that you may have obtained while in the service of the Company. ' +
                        'You are also required to return any of the company’s material, documents, ID card, Insurance Card or any under your possession.' +
                        '<br><br>Your final settlement will be released by <span class="settlement_date"></span> after clearance from your immediate supervisor');

                }
                else if(letter_id == 1)
                {


                    $('#details').html('');
                    $("#content1").find(".note-editable").html('Please be advised that we are receiving continuous complaints against your behavior at the' +
                        'work place from your seniors. Verbal warnings have already been given to you by Branch Operation Manager' +
                        'of our client but ' +'unfortunately you are not bringing any improvement in your behavior. <br><br>' +
                        'Therefore, you are hereby advised to immediately bring positive improvement in your behavior ' +
                        'towards your seniors, peers and customers or else Management will have no alternative other than to' +
                        'initiate stern action against you which may lead up to the termination of your services');

                }
                else if(letter_id == 5)
                {

                    $('#details').html('<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Settlement Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="insertEmployeeDates()" type="date" class="form-control requiredField" value="" required name="settlement_date" id="settlement_date"/></div>');

                    $("#content1").find(".note-editable").html('This has reference to the letter of appointment dated <span id="date_of_joining"></span> In accordance with clause B-(2)' +
                        ' of the appointment letter, we hereby terminate your employment with immediate effect i.e. <span id="settlement_date_area"></span>' +
                        ' You have committed a serious misconduct which has been proved through inquiry and afterwards admitted by you in writing, hence your services are being terminated with immediate effect.<br><br>' +
                        'As stated in the clause of B-(9); you are bound not to disclose any information relating to the Company or its customers, and will not divulge any of Company’s or client’s affairs or trade secrets that you may have obtained while in the service of the Company. ' +
                        'You are also required to return any of the company’s material, documents, ID card, Insurance Card' +
                        ' or any under your possession.  ');
                }
                else if(letter_id == 6)
                {
                    $("#content1").find(".note-editable").html('This has reference to the letter of appointment dated <span id="date_of_joining"></span> In accordance with clause B-(2) of ' +
                        'the appointment letter, we hereby terminate your employment with immediate effect due to continuous absence from work without proper notice and approvals<br><br>' +
                        'As stated in the clause of B-(9); you are bound not to disclose any information relating to the ' +
                        'Company or its customers, and will not divulge any of Company’s or client’s affairs or trade secrets ' +
                        'that you may have obtained while in the service of the Company.' +
                        ' You are also required to return any of the company’s material, documents, ID card, ' +
                        'Insurance Card or any belongings under your possession to get final clearance' +
                        '');
                    $('#details').html('<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Settlement Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="insertEmployeeDates()" type="date" class="form-control requiredField" value="" required name="settlement_date" id="settlement_date"/></div>');


                }

                else if(letter_id == 7)
                {
                    $('#details').html('<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Transfer Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getTransferLettersDetails()" type="date" required class="form-control requiredField" value="" name="transfer_date" id="transfer_date"/></div>');

                    $("#content1").find(".note-editable").html('This is to inform you that as per the Management decision, you have been transferred from <span class="transfer_from"></span>' +
                        ' to <span class="transfer_to"></span> as <span class="designation"></span> w.e.f <span class="transfer_date"></span> . ');
                }
                else if(letter_id == 8)
                {
                    $("#content1").find(".note-editable").html('<div class="container-fluid" id="PrintHrLettersForm"><h4 class="text-center" style="text-decoration: underline;"><b>Confidential</b></h4> ' +
                        '<p>Ms.XXX <br>Karachi</p> ' +
                        '<h3 class="text-center" style="text-decoration: underline;"><b>Appointment Offer Letter</b></h3> ' +
                        '<p>Dear Ms,</p> <p>We are pleased to offer you the position of <b style="text-decoration: underline">'+designation_name+'</b> in VIS Credit Rating Company Ltd.</p> ' +
                        '<p>Head-Office, Karachi on the following terms and conditions:</p> ' +
                        '<p><b>1.</b> You will be paid an aggregate monthly remuneration of Rs: <b style="text-decoration: underline">'+salary+'</b> break up of which is as follows:</p> ' +
                        '<div class="row"><div class="col-sm-3">Basic Salary</div> <div class="col-sm-3"><b>'+addCommas(basic)+'/-</b></div>  </div> ' +
                        '<div class="row"><div class="col-sm-3">House rent</div><div class="col-sm-3"><b>'+addCommas(hr)+'/-</b></div></div> ' +
                        '<div class="row"><div class="col-sm-3">Utilities</div><div class="col-sm-3"><b>'+addCommas(utility)+'/-</b></div></div>  ' +
                        '<div class="row" style="border-top:1px solid black;border-bottom:1px solid black"><div class="col-sm-3"><b>Total Consolidated Salary</b></div><div class="col-sm-3"><b>'+addCommas(salary)+'/-</b></div></div>  ' +
                        '<p><b>2.</b> You will be placed on probation for a period of three months from the date of joining the company and on completion on your probationary period your performance in the company will be reviewed; decision of confirmation will be made accordingly.</p> '+
                        '<p><b>3.</b> Upon confirmation, you will be entitled to all benefits mentioned in Service Rules of the Company.</p> ' +
                        '<p><b>4.</b> You will be required to abide by all other rules and regulations of the Company.</p> ' +
                        '<p><b>5.</b> You are advised to read the service rules and code of ethics of the company before joining the Company.</p> '+
                        '<br><p> Please signify your acceptance of the above offer by signing duplicate copy this letter.</p> '+
                        '<br><br><div class="row"><div class="col-sm-4"><b style="border-top:1px solid black;">Faryal Ahmed Faheem &nbsp;&nbsp;&nbsp;</b><br><b>Deputy CEO</b></div><div class="col-sm-4"></div>' +
                        '<div class="col-sm-4 text-right"><b style="border-top:1px solid black;">&nbsp;&nbsp; Acceptance &nbsp;&nbsp;&nbsp;&nbsp;</b></div></div> </div>')

                }


            }
        });


});

function insertEmployeeDates() {
    var settlement_date = $("#settlement_date").val();
    var emp_id = $("#emp_id").val();
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const d = new Date();
    var date = (monthNames[d.getMonth()]) +' '+ d.getDate() +', '+ d.getFullYear();
    if(emp_id == null){alert("Select Employee First !"); return false;}


    $.get(baseUrl+"/hdc/getEmployeeDateOfJoining",{'settlement_date':settlement_date,'emp_id':emp_id,'m':m},
        function(data){
            $("#settlement_date_area").html(date);
            $("#date_of_joining").html(data[1]);

        });
}

function getWithoutIncrementLettersDate()
{
    var performance_from = $("#performance_from").val();
    var performance_to = $("#performance_to").val();
    var confirmation_from = $("#confirmation_from").val();
    var emp_id = $("#emp_id").val();
    if(emp_id == null){alert("Select Employee First !"); return false;}


    $.get(baseUrl+"/hdc/getWithoutIncrementLettersDate",{'confirmation_from':confirmation_from,'performance_from':performance_from,'performance_to':performance_to,'emp_id':emp_id,'m':m}, function(data){
        $(".performance_from").html(data[0]);
        $(".performance_to").html(data[1]);
        $(".confirmation_from").html(data[2]);

    });
}

function getIncrementLettersDetails()
{
    var confirmation_from = $("#confirmation_from").val();
    var emp_id = $("#emp_id").val();
    if(emp_id == null){alert("Select Employee First !"); return false;}


    $.get(baseUrl+"/hdc/getIncrementLettersDetails",{'confirmation_from':confirmation_from,'emp_id':emp_id,'m':m}, function(data){

        if(data == 1)
        {
            $("#error_messages").html('<div class="row">&nbsp;</div><div class="text-center" style="color: red"><h3>Increment Record not found !</h3></div>');

            $("#do").attr("disabled","disabled");
        }
        else
        {
            $("#error_messages").html("");
            $("#do").removeAttr("disabled");
            $(".performance_from").html(data[0]);
            $(".performance_to").html(data[1]);
            $(".confirmation_from").html(data[2]);
            $(".designation").html(data[3]);
            $(".salary").html(data[4]);
        }


    });
}

function getConclusionLettersDate()
{
    var conclude_date = $("#conclude_date").val();
    var settlement_date = $("#settlement_date").val();
    var emp_id = $("#emp_id").val();
    if(emp_id == null){alert("Select Employee First !"); return false;}


    $.get(baseUrl+"/hdc/getConclusionLettersDate",{'conclude_date':conclude_date,'settlement_date':settlement_date,'emp_id':emp_id,'m':m}, function(data){
        $("#date_of_joining").html(data[0]);
        $(".conclude_date").html(data[1]);
        $(".settlement_date").html(data[2]);

    });
}

function getTransferLettersDetails()
{
    var transfer_date = $("#transfer_date").val();
    var emp_id = $("#emp_id").val();
    if(emp_id == null){alert("Select Employee First !"); return false;}


    $.get(baseUrl+"/hdc/getTransferLettersDetails",{'transfer_date':transfer_date,'emp_id':emp_id,'m':m}, function(data){

        if(data == 1)
        {
            $("#error_messages").html('<div class="row">&nbsp;</div><div class="text-center" style="color: red"><h3>Transfer Record not found !</h3></div>');

            $("#do").attr("disabled","disabled");
        }
        else
        {
            $("#error_messages").html("");
            $("#do").removeAttr("disabled");
            $(".transfer_from").html(data[0]);
            $(".transfer_to").html(data[1]);
            $(".transfer_date").html(data[2]);
            $(".designation").html(data[3]);
        }


    });
}


$(function() {
    $('.summernote1').summernote({
        height: 200
    });

    $('.summernote2').summernote({
        height: 200
    });
});




















$(document).ready(function(){
    $('#sub_department_id').select2();
    $('#emp_id').select2();
    $('#department_id').select2();
});

// function getEmployee(){
//
//     var department = $("#department_id").val();
//     var sub_department = $("#sub_department_id").val();
//
//     if(department == '0'){
//         $("#department_id_").val('0');
//         $("#sub_department").val('0');
//         $('select[name="emp_id"]').empty();
//         $("#emp_id").prepend("<option value='0'>-</option>");
//         return false;
//     }
//     if(department != '0' && sub_department == ''){
//         data = {department:department,sub_department:'0',m:m}
//     }
//     else if(department != '' && sub_department != ''){
//         data = {department:department,sub_department:sub_department,m:m}
//     }
//     if(department != ''){
//         $('#emp_loader_1').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
//         $.ajax({
//             type:'GET',
//             url:baseUrl+'/slal/getSubDepartment',
//             data:data,
//             success:function(res){
//                 $('#emp_loader_1').html('');
//                 $('select[name="emp_id"]').empty();
//                 $('select[name="emp_id"]').html(res);
//
//             }
//         })
//     }
//     else{
//         $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
//         $('select[name="sub_department_id_1"]').empty();
//         $('select[name="emp_id"]').empty();
//     }
// }
//
//


function empCategory() {
    var emp_category_id = $("#emp_category_id").val();
    var region_id = $("#region_id").val();
    if (region_id == '') {
        alert('Please Select Region !');
        return false;
    } else {

        if (emp_category_id) {
            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: baseUrl+'/slal/getEmployeeCategoriesList',
                type: "GET",
                data: {emp_category_id: emp_category_id, region_id: region_id, m: m},
                success: function (data) {
                    $('#emp_loader').html('');
                    $('select[name="emp_id"]').empty();
                    $('select[name="emp_id"]').html(data);
                }
            });
        } else {
            $('select[name="emp_id"]').empty();
        }
    }
}

$(document).ready(function () {
    $('#designation_id').select2();
    $('#emp_category_id').select2();
    $('#region_id').select2();
    $('#emp_id').select2();
    $('#letter_id').select2();
    $('#employee_project_id').select2();

    $('.checkboxs').change(function () {
        if ($(this).is(':checked')) {
            $('#emp_category_id').attr('disabled', true);
            $('#emp_id').attr('disabled', true);
            $('#employee_project_id').attr('disabled', true);
            $('#region_id').attr('disabled', true);
            $('#emp_category_id').removeClass('requiredField').removeAttr('required');
            $('#region_id').removeClass('requiredField').removeAttr('required');
            $(this).val('1');
        } else {
            $('#emp_id').attr('disabled', false);
            $('#emp_category_id').attr('disabled', false);
            $('#employee_project_id').attr('disabled', false);
            $('#region_id').attr('disabled', false);
            $('#emp_category_id').addClass('requiredField').attr('required');
            $('#region_id').addClass('requiredField').attr('required');
            $(this).val('0');
        }
    });

});


$('#letter_id').on('change', function() {
    var emp_id = $('#emp_id').val();
    var letter_id = $('#letter_id').val();


    if(letter_id == 2)
    {

    }

    else if(letter_id == 3)
    {

    }

    else if(letter_id == 4)
    {

    }
    else if(letter_id == 1 || letter_id == 5 || letter_id == 6)
    {

    }

});

$('.checkboxs').change(function() {
    if(this.checked) {
        $("#show_all_status").val('1');
    }
});

function searchLetters() {
    $("#letterSection").css({"display": "none"});
    var emp_id = $('#emp_id').val();

    var letter_id = $('#letter_id').val();
    var employee_project_id = $('#employee_project_id').val();
    var rights_url = 'hr/viewHrLetters';
    var emp_category_id = $("#emp_category_id").val();
    var region_id = $("#region_id").val();
    var show_all = $("#show_all_status").val();
    var department_id=$('#department_id').val();
    var sub_department_id=$('#sub_department_id').val();
    var performance_from = $('#performance_from').val();
    var performance_to = $('#performance_to').val();
    var confirmation_from = $('#confirmation_from').val();
    var conclude_date = $('#conclude_date').val();
    var settlement_date = $('#settlement_date').val();

    jqueryValidationCustom();

    if(validate == 0) {
        $("#letterSection").css({"display": "block"});
        data = {emp_id: emp_id, m: m, department_id:department_id,sub_department_id:sub_department_id,letter_id: letter_id, performance_from:performance_from, performance_to:performance_to, confirmation_from:confirmation_from,conclude_date:conclude_date, settlement_date:settlement_date,rights_url:rights_url,employee_project_id:employee_project_id,emp_category_id:emp_category_id,region_id:region_id,show_all:show_all}



        $('#dataLoader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        $.ajax({
            url: baseUrl+'/hdc/viewHrLetters',
            type: "GET",
            data:data,
            success: function (data) {
                $('#dataLoader').html('');
                $("#show_all_status").val('');
                $(".letterSection").html('<div class="row">&nbsp;</div>' + data);
            }
        });

    }
    else
    {
        return false;
    }
}




$(document).ready(function() {

    $('#emp_id').select2();
    $('#department_id').select2();
    $('#sub_department_id').select2();

    // Wait for the DOM to be ready
    // $(".btn-success").click(function (e) {
    //     var employee = new Array();
    //     var val;
    //     $("input[name='employeeSection[]']").each(function () {
    //         employee.push($(this).val());
    //     });
    //     var _token = $("input[name='_token']").val();
    //     for (val of employee) {
    //         jqueryValidationCustom();
    //         if (validate == 0) {
    //             //alert(response);
    //         } else if (validate == 1) {
    //             return false;
    //         }
    //     }
    //
    // });
    $('#emp_category_id').select2();
    $('#region_id').select2();
    $('#emp_id').select2();
    $('#letter_type').select2();
    $('#employee_project_id').select2();

});


function employeeProject() {
    var emp_category_id = $("#emp_category_id").val();
    var region_id = $("#region_id").val();
    var employee_project_id = $("#employee_project_id").val();
    if(employee_project_id == '0'){
        empCategory()
    }
    if (region_id == '') {
        alert('Please Select Region !');
        return false;
    } else if (emp_category_id == '') {
        alert('Please Select Cateogery !');
        return false;
    } else {

        if (employee_project_id) {
            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: baseUrl+'/slal/getEmployeeProjectList',
                type: "GET",
                data: {
                    emp_category_id: emp_category_id,
                    region_id: region_id,
                    employee_project_id: employee_project_id,
                    m: m
                },
                success: function (data) {
                    $('#emp_loader').html('');
                    $('select[name="emr_no"]').empty();
                    $('select[name="emr_no"]').html(data);
                }
            });
        } else {
            $('select[name="emr_no"]').empty();
        }
    }
}

function empCategory() {
    var emp_category_id = $("#emp_category_id").val();
    var region_id = $("#region_id").val();
    if (region_id == '') {
        alert('Please Select Region !');
        return false;
    } else {

        if (emp_category_id) {
            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: baseUrl+'/slal/getEmployeeCategoriesList',
                type: "GET",
                data: {emp_category_id: emp_category_id, region_id: region_id, m: m},
                success: function (data) {
                    $('#emp_loader').html('');
                    $('select[name="emr_no"]').empty();
                    $('select[name="emr_no"]').html(data);
                }
            });
        } else {
            $('select[name="emr_no"]').empty();
        }
    }
}












