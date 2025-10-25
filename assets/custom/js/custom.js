var m = $('#m').val();
var baseUrl = $('#baseUrl').val();

function getSubDepartments(department_id,type){
    $('#sub_department_loader').html('<div class="animated yt-loader"></div>');
    $.ajax({
        url: baseUrl+'/slal/getSubDepartments',
        type: "get",
        data: {m: m, department_id:department_id,type:type},
        success:function(res){
            $('#sub_department_loader').html('');
            $('#sub_department_id').html(res);
        }
    });
}

function getEmployee(sub_val, single)
{
    var department_id = $("#department_id").val();
    var sub_department_id = $("#sub_department_id").val();
    var category_id = $("#category_id").val();
    var project_id = $("#project_id").val();
    var location_id = $("#location_id").val();

    if (sub_val == 'sub_department') {
        getSubDepartments(department_id, '');
    }

    $('#emp_loader').html('<div class="animated yt-loader"></div>');
    $.ajax({
        type: 'GET',
        url: baseUrl + '/slal/getEmployee',
        data: { m: m, department_id: department_id, flagEmp: single, sub_department_id: sub_department_id, category_id: category_id, project_id: project_id, location_id: location_id },
        success: function (res) {
            $('#emp_loader').html('');
            $('#employee_id').html(res);
        }
    });
}

$(document).ready(function () {

    $('#department_id').select2();
    $('#sub_department_id').select2();
    $('#project_id').select2();
    $('#category_id').select2();
    $('#employee_id').select2();
    $('#location_id').select2();

    $("#department_id").trigger("onchange");

    //$('#department_id').find('option').get(0).remove();
    //let department_id = new vanillaSelectBox("#department_id",{
    //    search: true,
    //    placeHolder: "Select Department",
    //});
    //
    //
    //$('#btn-group-department_id ul').find('li').get(0).remove();
    ////$('#sub_department_id').find('option').get(0).remove();
    //let sub_department_id = new vanillaSelectBox("#sub_department_id",{
    //    search: true,
    //    placeHolder: "Select Sub Department",
    //});
    //$('#btn-group-sub_department_id ul').find('li').get(0).remove();
    ////$('#project_id').find('option').get(0).remove();
    //let project_id = new vanillaSelectBox("#project_id",{
    //    search: true,
    //    placeHolder: "Select Option",
    //});
    //$('#btn-group-project_id ul').find('li').get(0).remove();
    ////$('#category_id').find('option').get(0).remove();
    //let category_id = new vanillaSelectBox("#category_id",{
    //    search: true,
    //    placeHolder: "Select Option",
    //});
    //$('#btn-group-category_id ul').find('li').get(0).remove();
    ////$('#employee_id').find('option').get(0).remove();
    //let employee_id = new vanillaSelectBox("#employee_id",{
    //    search: true,
    //    placeHolder: "Select Employee",
    //});

    $('#reset').html('Clear Form');
    $(document).bind('ajaxStart', function () {
        $('.btn_search').html(' <i id="load"> </i>'+' Loading');
        $("#load").addClass("spinner-border spinner-border-sm");
    }).bind('ajaxStop', function () {
        $('.btn_search').html('<i id="load" class="fas fa-search fa"> </i> '+' Search');
        $('#load').removeClass("spinner-border spinner-border-sm");
    });

    $('#filter_users').focus(function(){
        $('#itemsDiv').fadeIn(250);
    }).focusout(function(){
        $('#itemsDiv').fadeOut(250);
    });

});

function deleteRowCompanyRecords(companyId,recordId,tableName){
    if(confirm("Do you want to delete this record ?") == true){
        $.ajax({
            url: baseUrl+'/cdOne/deleteRowCompanyRecords',
            type: "GET",
            data: {companyId:companyId,recordId:recordId,tableName:tableName},
            success:function(data) {
                location.reload();
            }
        });
    }
    else{
        return false;
    }
}

function approveAndRejectAll(approval_status, table_name) {

    var check = $("input[name='check_input[]']:checked").map(function(){return $(this).val();}).get();
    var company_id = m;
    $.ajax({
        url: baseUrl+'/hadbac/approveAndRejectAll',
        type: "GET",
        data: {
            approval_status: approval_status,
            table_name: table_name,
            check: check,
            company_id: company_id
        },
        success: function(res) {
            $.notify({
                icon: "fa fa-check-circle",
                message: "<b>Successfully Updated</b>."
            }, {
                type: 'info',
                timer: 3000
            });
            if(table_name == 'employee_promotion') {
                location.reload();
            } else if(table_name == 'advance_salary') {
                viewFilteredAdvanceSalaryList();
            } else if(table_name == 'loan_request') {
                viewFilteredLoanRequestList();
            } else {

            }

        },
        error: function(error) {
            swalError();
        }
    });
}

function check(){
    const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
    const firstDate = $('#from_date').val();
    const secondDate = $('#to_date').val();
    //new Date(2008, 1, 12);

    const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay)+1);
    //$('#no_of_days').val(parseInt(diffDays));

}

var validate;
function jqueryValidationCustom(){
    var requiredField = document.getElementsByClassName('requiredField');
    for (i = 0; i < requiredField.length; i++){
        var rf = requiredField[i].id;
        var checkType = requiredField[i].type;
        if($('#'+rf).val() == ''){
            if(checkType == 'select-one') {
                $('#'+rf).siblings(".select2-container").css('border', '2px solid red');
            }
            else {
                $('#'+rf).css('border', '2px solid red');
            }
            $('#'+rf).focus();
            validate = 1;
            return false;
        }else{
            if(checkType == 'select-one') {
                $('#'+rf).siblings(".select2-container").css('border-color', '#ccc');
            }
            else {
                $('#'+rf).css('border-color', '#ccc');
            }
            validate = 0;
        }
    }
    return validate;
}


function hidemenu(){
    $('.main-wrapper1').removeAttr('id');
    $('.left-sidebar').css({"display": "none"});
    $('#hidemenu').css({"display": "none"});
    $('#showmenu').css({"display": "block"});
}
function showmenu(){
    $('.main-wrapper1').attr('id','main-wrapper');
    $('.left-sidebar').css({"display": "block"});
    $('#showmenu').css({"display": "none"});
    $('#hidemenu').css({"display": "block"});
}

function repostMasterTableRecords(recordId,tableName){
    $.ajax({
        url: baseUrl+'/cdOne/repostMasterTableRecords',
        type: "GET",
        data: {recordId:recordId,tableName:tableName},
        success:function(data) {
            location.reload();
        }
    });
}

function deleteRowCompanyHRRecords(companyId,recordId,tableName){
    if(confirm("Do you want to delete this record ?") == true) {
        $.ajax({
            url: baseUrl+'/cdOne/deleteRowCompanyHRRecords',
            type: "GET",
            data: {companyId: companyId, recordId: recordId, tableName: tableName},
            success: function (data) {
                location.reload();
            }
        });
    }
    else{
        return false;
    }
}
function deleteLoanTopUp(companyId,recordId,tableName){
    if(confirm("Do you want to delete this record ?") == true) {
        $.ajax({
            url: baseUrl+'/cdOne/deleteLoanTopUp',
            type: "GET",
            data: {companyId: companyId, recordId: recordId, tableName: tableName},
            success: function (data) {
                location.reload();
            }
        });
    }
    else{
        return false;
    }
}

function repostCompanyTableRecord(companyId,recordId,tableName) {
    $.ajax({
        url: baseUrl+'/cdOne/repostOneTableRecords',
        type: "GET",
        data: {companyId:companyId,recordId:recordId,tableName:tableName},
        success:function(data) {
            location.reload();
        }
    });
}

function deleteRowMasterTable(id,tableName){
    if(confirm("Do you want to delete this record ?") == true){
        $.ajax({
            url: baseUrl+'/deleteMasterTableReceord',
            type: "GET",
            data: {id:id,tableName:tableName},
            success:function(data) {
                location.reload();
            }
        });
    }
    else{
        return false;
    }
}

function locationsList() {
    var project_id = $("#project_id").val();
    $('#location_loader').html('<div class="animated yt-loader"></div>');
    $.ajax({
        url: baseUrl+'/slal/locationsList',
        type: "GET",
        data: { project_id: project_id, m: m },
        success: function (res) {
            $('#location_loader').html('');
            $("#location_id").html(res);
        }
    });
}

function locationsListUsingId(id) {
    var project_id = $('#project_id_'+id+'').val();
    $('#location_loader_'+id+'').html('<div class="animated yt-loader"></div>');
    $.ajax({
        url: baseUrl+'/slal/locationsListUsingId',
        type: "GET",
        data: { project_id: project_id, m: m },
        success: function (res) {
            $('#location_loader_'+id+'').html('');
            $('select[id="location_id_'+id+'"]').html(res);
        }
    });
}

function locationsListWithAllOption() {
    var project_id = $("#project_id").val();
    $('#location_loader').html('<div class="animated yt-loader"></div>');
    $.ajax({
        url: baseUrl+'/slal/locationsListWithAllOption',
        type: "GET",
        data: { project_id: project_id, m: m },
        success: function (res) {
            $('#location_loader').html('');
            $('#location_id').html(res);
        }
    });
}

getGradeByDesignation();
function getGradeByDesignation() {
    var grade_id = $("#designation_id option:selected").attr("data-value")
    $('#grade_loader').html('<div class="animated yt-loader"></div>');
    $.ajax({
        url: baseUrl+'/slal/getGradeByDesignation',
        type: "GET",
        data: { grade_id: grade_id, m: m },
        success: function (res) {
            $('#grade_loader').html('');
            $('#grade_id').html(res);
        }
    });
}

function getEmployeeMultiple(sub_val){
    var department_id = $("#department_id").val();
    var sub_department_id = $("#sub_department_id").val();
    var project_id = $("#project_id").val();
    var category_id = $("#category_id").val();

    if(sub_val=='sub_department'){
        getSubDepartments(department_id,'multiple');
    }

    if(department_id != '') {
        $('#emp_loader').html('<div class="loader"></div>');
        $.ajax({
            type: 'GET',
            url: baseUrl + '/slal/getEmployeeMultiple',
            data: {
                m: m,
                department_id: department_id,
                sub_department_id: sub_department_id,
                category_id: category_id,
                project_id: project_id
            },
            success: function (res) {
                $('#emp_loader').html('');
                $('#employee_id').html(res);
            }
        })
    }
}

$('#btn_update').on('click', function() {
    var $this = $(this);
    $('#btn_update').html('Loading');
    $('#btn_update').append('&nbsp;<span class="loading"></span>');
    $(".loading").addClass("spinner-border spinner-border-sm");

    setTimeout(function() {
        $('#btn_update').html('Update');
    }, 1000);
});

$('#btn_add').on('click', function() {
    var $this = $(this);
    $('#btn_add').hide();
    $('#btn_add').after('&nbsp;<button type="button" class="btn btn-sm btn-success load">Loading <span class="loading"></span></button>');
    $(".loading").addClass("spinner-border spinner-border-sm");

    setTimeout(function() {
        $('#btn_add').show();
        $('.load').hide();
    }, 1000);
});

$('#btn_search').on('click', function() {
    var $this = $(this);
    $('#btn_search').hide();
    $('#btn_search').after('&nbsp;<button type="button" class="btn btn-primary load">Loading <span class="loading"></span></button>');
    $(".loading").addClass("spinner-border spinner-border-sm");

    setTimeout(function() {
        $('#btn_search').show();
        $('.load').hide();
    }, 1000);
});

$('#reset').on('click', function() {
    var $this = $(this);
    $('#reset').html('Loading');
    $('#reset').append('&nbsp;<span class="loading"></span>');
    $(".loading").addClass("spinner-border spinner-border-sm");

    setTimeout(function() {
        $('#reset').html('Clear Form');
    }, 1000);
});



var results = [];
var urls=[];
function search_menu_key_press(){


    function autocomplete(inp, arr) {



        var rawFile = new XMLHttpRequest();
        var name = '';
        var url = '';
        var out = '';

        rawFile.open('GET', baseUrl+'/storage/app/menu.json', false);
        rawFile.onreadystatechange = function ()
        {
            if(rawFile.readyState === 4)
            {
                if(rawFile.status === 200 || rawFile.status == 0)
                {
                    var allText = rawFile.responseText;
                    myObj = JSON.parse(allText);
                    var searchVal = $('#search_menu').val();

                    for (var i=0 ; i < myObj.length; i++)
                    {
                        results[i]= [myObj[i].name,myObj[i].url];



                        //urls[i]=myObj[i].url;
                    }

                }
            }
        };
        rawFile.send(null);




        /*the autocomplete function takes two arguments,
         the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        // inp.addEventListener("input", function(e) {
        //     var a, b, i, val = this.value;
        //     /*close any already open lists of autocompleted values*/
        //     closeAllLists();
        //     if (!val) { return false;}
        //     currentFocus = -1;
        //     /*create a DIV element that will contain the items (values):*/
        //     a = document.createElement("DIV");
        //     a.setAttribute("id", this.id + "autocomplete-list");
        //     a.setAttribute("class", "autocomplete-items");
        //     /*append the DIV element as a child of the autocomplete container:*/
        //     this.parentNode.appendChild(a);
        //     /*for each item in the array...*/
        //     for (i = 0; i < arr.length; i++) {
        //         /*check if the item starts with the same letters as the text field value:*/
        //         if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        //             /*create a DIV element for each matching element:*/
        //             b = document.createElement("DIV");
        //             /*make the matching letters bold:*/
        //             b.innerHTML = arr[i].substr(0, val.length);
        //             b.innerHTML += arr[i].substr(val.length);
        //             /*insert a input field that will hold the current array item's value:*/
        //             b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
        //             /*execute a function when someone clicks on the item value (DIV element):*/
        //             b.addEventListener("click", function(e) {
        //                 /*insert the value for the autocomplete text field:*/
        //
        //                 inp.value = this.getElementsByTagName("input")[0].value;
        //                 /*close the list of autocompleted values,
        //                  (or any other open lists of autocompleted values:*/
        //                 closeAllLists();
        //             });
        //             a.appendChild(b);
        //         }
        //     }
        // });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                 increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                 decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
             except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    /*An array containing all the country names in the world:*/

    /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/


    autocomplete(document.getElementById("filter_users"), results);
// console.log(results)

}

(function ($) {
    $.fn.sanitizeInput = function () {
        return this.each(function () {
            $(this).on('input', function () {
                const sanitizedValue = $(this).val().replace(/[*'"<>!;%&`]/g, '');

                if ($(this).val() !== sanitizedValue) {
                    $(this).val(sanitizedValue);
                }
            });
        });
    };
})(jQuery);

$('.form-control').sanitizeInput();

$("#show_all").change(function(){
    if($('#show_all').is(':checked')){
        $('#department_id').prop("disabled", true);
        $('#sub_department_id').prop("disabled", true);
        $('#emp_id').prop("disabled", true);
    }
    else{
        $('#department_id_').prop("disabled", false);
        $('#sub_department_id').prop("disabled", false);
        $('#emp_id').prop("disabled", false);
        $('#department_id').prop("disabled", false);

    }

});