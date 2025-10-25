var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function(){

    $(".btn-success").click(function(e){
        var employee = new Array();
        var val;
        $("input[name='formSection[]']").each(function(){
            employee.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val in employee) {
            jqueryValidationCustom();

            if(validate == 0){
                $('#pTimePeriod').prop("disabled", false);
                //alert(response);
            }else{
                return false;
            }
        }
    });

    $('#department_id').select2();
    $('#sub_department_id').select2();
    $('#project_id').select2();
    $('#location_id').select2();
    $('#designation_id').select2();
    $('#category_id').select2();
    $('#marital_status_id').select2();
    $('#employment_status_id').select2();
    $('#grade_id').select2();
    $('#eobi_id').select2();
    $('#gender_id').select2();
    $('#reporting_manager_id').select2();
    $('#leaves_policy_id').select2();
    $('#account_type').select2();
    $('#role_id').select2();

});



function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}


function password_generator( len ) {
    var length = (len)?(len):(10);
    var string = "abcdefghijklmnopqrstuvwxyz"; //to upper
    var numeric = '0123456789';
    var punctuation = '!@#$%^&*()_+~`|}{[]\:;?><,./-=';
    var password = "";
    var character = "";
    var crunch = true;
    while( password.length<length ) {
        entity1 = Math.ceil(string.length * Math.random()*Math.random());
        entity2 = Math.ceil(numeric.length * Math.random()*Math.random());
        entity3 = Math.ceil(punctuation.length * Math.random()*Math.random());
        hold = string.charAt( entity1 );
        hold = (password.length%2==0)?(hold.toUpperCase()):(hold);
        character += hold;
        character += numeric.charAt( entity2 );
        character += punctuation.charAt( entity3 );
        password = character;
    }
    password=password.split('').sort(function(){return 0.5-Math.random()}).join('');
    document.getElementById("password").value = password.substr(0,len);
}

function removeQualificationSection(id){
    $("#remove_area_"+id).remove();
}

function removeWorkExperienceSection(id){
    $("#remove_area1_"+id).remove();
}

function removeReferenceSection(id) {
    $("#remove_area2_"+id).remove();
}

function removeFamilyDataSection(id) {
    $("#remove_area3_"+id).remove();
}

function removeActivityDataSection(id) {
    $(".remove_area4_"+id).remove();
}

function removeEmergencyContactSection(id) {
    $(".remove_area5_"+id).remove();
}

function removeKinDetailsSection(id) {
    $(".remove_area6_"+id).remove();
}

function removeLanguageProficiencySection(id) {
    $("#remove_area7_"+id).remove();
}

function removeHealthDetailsSection(id) {
    $(".remove_area8_"+id).remove();
}
function removeRelativesDetailsSection(id) {
    $(".remove_area10_"+id).remove();
}
function removeEmployeeGsspDocumentDataSection(id) {
    $(".remove_area_"+id).remove();
}


$('#leaves_policy_id_1').click(function (e)
{
    var leaves_policy_id = $('#leaves_policy_1').val();
    if(leaves_policy_id != ''){

        showDetailModelTwoParamerter('hdc/viewLeavePolicyDetail',leaves_policy_id,'View Leaves Policy Detail ',m);
    }
    else
    {
        alert('Please Select Policy !');
    }
});

$('#view_tax_1').click(function (e)
{
    var tax_id = $('#tax_id_1').val();
    if(tax_id != '0'){

        showDetailModelTwoParamerterJson('hdc/viewTax',tax_id,'View Tax  Detail ',m);
    }
    else
    {
        alert('Please Select Tax !');
    }
});

$('#can_login').click(function (e)
{
    if($("#can_login").prop('checked') == true)
    {
        $('#credential_area').fadeIn();
    }
    else
    {
        $('#credential_area').fadeOut();
    }

});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img_file_1').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#file_1").change(function(){
    readURL(this);
});

$("#transport_yes_1").change(function(){
    if($("#transport_yes_1").prop('checked') == true)
    {
        $("#transport_particular").fadeIn();
        $("#transport_no_1").prop('checked', false);
    }
    else
    {
        $("#transport_particular").fadeOut();
        $("#transport_yes_1").prop('checked', false);
    }
});

$("#transport_no_1").change(function(){
    if($("#transport_no_1").prop('checked') == true)
    {
        $("#transport_particular").fadeOut();
        $("#transport_yes_1").prop('checked', false);
    }

});

$('#family_data_check').click(function(){

    if($(this).is(":checked") == true)
    {
        $("#family_data_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
            '<input type="hidden" name="family_data[]" id="get_rows3" value="1"><div class="" id="family_append_area_1">' +
            '<table class="table table-bordered table-hover get_rows3" id="get_clone3">' +
            '<thead><th class="text-center">S.No</th><th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Relation<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Add Emergency Contact<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center"><button type="button" id="addMoreFamilyData" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></thead>' +
            '<tbody id="family_append_area"><tr><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
            '<td class="text-center"><input class="form-control requiredField" name="family_name_1" id="family_name_1"></td>' +
            '<td><input class="form-control requiredField" name="family_relation_1"  id="family_relation_1"></td>' +
            '<td><input class="form-control requiredField" name="family_emergency_1" id="family_emergency_1"></td><td></td></tr></tbody>' +
            '</table></div></div>');

        $("#addMoreFamilyData").click(function(e){
            var form_rows_count = $(".get_rows3").length;
            form_rows_count++;
            $("#family_append_area").append('<tr class="get_rows3" id="remove_area3_'+form_rows_count+'">' +
                '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'<span></td>' +
                '<td class="text-center"><input class="form-control requiredField" name="family_name_'+form_rows_count+'" id="family_name_'+form_rows_count+'"></td>' +
                '<td><input class="form-control requiredField" name="family_relation_'+form_rows_count+'"  id="family_relation_'+form_rows_count+'"></td>' +
                '<td><input class="form-control requiredField" name="family_emergency_'+form_rows_count+'" id="family_emergency_'+form_rows_count+'"></td>' +
                '<td class="text-center"><button type="button" onclick="removeFamilyDataSection('+form_rows_count+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td></tr>');
        });
    }
    else
    {
        $("#family_data_area").html('');
    }

});

$('#language_check').click(function(){

    if($(this).is(":checked") == true)
    {
        $("#language_area").html(' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
            '<input type="hidden" name="language_data[]" value="1"><div class="">' +
            '<table class="table table-bordered table-hover" ><thead><th class="text-center">S.No</th>' +
            '<th class="text-center">Language<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Read<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Write<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Speak<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center"><button type="button" id="addMoreLanguage" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>' +
            '</thead><tbody id="insert_clone7"><tr class="get_rows7"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
            '<td id="get_clone7" class="text-center"><input class="form-control requiredField" name="language_name_1"  id="language_name_1" required>' +
            '</td><td class="text-center"><b>Good : <input checked type="radio" name="reading_skills_1" value="Good"></b><b>Fair : <input type="radio" name="reading_skills_1" value="Fair">' +
            '</b><b>Poor : <input type="radio" name="reading_skills_1" value="Poor"></b></td><td class="text-center"><b>Good : <input checked type="radio" name="writing_skills_1" value="Good"></b>' +
            '<b>Fair : <input type="radio" name="writing_skills_1" value="Fair"></b><b>Poor : <input type="radio" name="writing_skills_1" value="Poor"></b>' +
            '</td><td class="text-center"><b>Good : <input checked type="radio" name="speaking_skills_1" value="Good"></b><b>Fair : <input type="radio" name="speaking_skills_1" value="Fair"></b>' +
            '<b>Poor : <input type="radio" name="speaking_skills_1" value="Poor"></b></td><td class="text-center"></td></tr></tbody></table> </div></div>');

        $("#addMoreLanguage").click(function(e){
            var form_rows_count = $(".get_rows7").length;
            form_rows_count++;
            $("#insert_clone7").append("<tr class='get_rows7' id='remove_area7_"+form_rows_count+"' ><td class='text-center'>" +
                '<input type="hidden" name="language_data[]" value="'+form_rows_count+'">' +
                "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                "<td class='text-center'><input class='form-control requiredField' name='language_name_"+form_rows_count+"' value='' id='language_name_"+form_rows_count+"' required></td>" +
                "<td class='text-center'><b>Good : <input checked type='radio' name='reading_skills_"+form_rows_count+"' value='Good'></b>" +
                "<b>Fair : <input  type='radio' name='reading_skills_"+form_rows_count+"' value='Fair'></b>" +
                "<b>Poor : <input type='radio' name='reading_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                "<td class='text-center'><b>Good : <input checked type='radio' name='writing_skills_"+form_rows_count+"' value='Good'></b>" +
                "<b>Fair : <input  type='radio' name='writing_skills_"+form_rows_count+"' value='Fair'></b>" +
                "<b>Poor : <input type='radio' name='writing_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                "<td class='text-center'><b>Good : <input checked type='radio' name='speaking_skills_"+form_rows_count+"' value='Good'></b>" +
                "<b>Fair : <input  type='radio' name='speaking_skills_"+form_rows_count+"' value='Fair'></b>" +
                "<b>Poor : <input type='radio' name='speaking_skills_"+form_rows_count+"' value='Poor'></b></td>" +
                "<td class='text-center'><button onclick='removeLanguageProficiencySection("+form_rows_count+")' type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                "</td>" +
                "</tr>");
        });
    }
    else
    {
        $("#language_area").html('');
    }

});

$('#health_type_check').click(function(){

    if($(this).is(":checked") == true)
    {
        $("#health_type_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
            '<input type="hidden" name="health_data[]" value="1"><div class="">' +
            '<table class="table table-bordered table-hover" ><thead><th class="text-center">S.No</th>' +
            '<th class="text-center">Health Type<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Yes / No<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center"><button type="button" id="addMoreHealth" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>' +
            '</thead>' +
            '<tbody id="insert_clone8"><tr class="get_rows8"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
            '<td><select style="width:100%;" class="form-control" name="health_type_1" id="health_type_1" required>' +
            '<option value="">Select Option</option>' +
            '<option value="Speech">Speech</option>' +
            '<option value="Hearing">Hearing</option>' +
            '<option value="Sight">Sight</option>' +
            '<option value="AIDS">AIDS</option>' +
            '<option value="Hands">Hands</option>' +
            '<option value="Feet">Feet</option>' +
            '<option value="Skin">Skin</option>' +
            '<option value="Cancer">Cancer</option>' +
            '<option value="Epilespy">Epilespy</option>' +
            '<option value="Asthma">Asthma</option>' +
            '<option value="Tuberculosis">Tuberculosis</option>' +
            '<option value="Hepatitis">Hepatitis</option>' +
            '</select></td><td><select style="width: 100%" class="form-control" id="health_check_1" name="health_check_1" required>' +
            '<option value="">Select Option</option>' +
            '<option value="Yes">Yes</option>' +
            '<option value="No">No</option>' +
            '</select></td><td class="text-center">-</td></tr></tbody></table></div>' +
            '<div class="row"><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
            '<label class="sf-label">Any Physical Handicap</label>' +
            '<span class="rflabelsteric"><strong>*</strong></span> ' +
            '<input type="text" class="form-control requiredField" name="physical_handicap" id="physical_handicap" value="-" />' +
            '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label class="sf-label">Height</label>' +
            '<span class="rflabelsteric"><strong>*</strong></span>' +
            '<input type="text" class="form-control requiredField" placeholder="" name="height" id="height"/>' +
            '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label class="sf-label">Weight</label>' +
            "<span class='rflabelsteric'><strong>*</strong></span>" +
            "<input type='number' class='form-control requiredField' placeholder='80kg' name='weight' id='weight'  />" +
            "</div> <div class='col-lg-3 col-md-3 col-sm-3 col-xs-12'><label class='sf-label'>Blood Group</label>" +
            "<span class='rflabelsteric'><strong>*</strong></span>" +
            "<input type='text' class='form-control requiredField' placeholder='A+' name='blood_group' id='blood_group'  />" +
            "</div></div></div>");

        $("#addMoreHealth").click(function(e){
            var clone_health_type = $("#health_type_1").html();
            var clone_health_check = $("#health_check_1").html();
            var form_rows_count = $(".get_rows8").length;
            form_rows_count++;
            $("#insert_clone8").append('<tr class="remove_area8_'+form_rows_count+' get_rows8" id="">' +
                '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                '<td><select style="width:100%;" class="form-control" name="health_type_'+form_rows_count+'" id="health_type_'+form_rows_count+'" required>'+clone_health_type+'</select></td>' +
                '<td><select style="width: 100%;" class="form-control" name="health_check_'+form_rows_count+'" id="health_check_'+form_rows_count+'" required>'+clone_health_check+'</select></td>' +
                '<td class="text-center"><input type="hidden" name="health_data[]" value="'+form_rows_count+'">' +
                '<button type="button" onclick="removeHealthDetailsSection('+form_rows_count+')" class="btn btn-sm btn-danger remove_area8_'+form_rows_count+'"><i class="fas fa-trash"></i></button></td>' +
                '</tr>');
            $("#health_type_"+form_rows_count+"").select2();
            $("#health_check_"+form_rows_count+"").select2();

        });
        $("#health_type_1").select2();
        $("#health_check_1").select2();
    }
    else
    {
        $("#health_type_area").html('');
    }

});

$('#activity_check').click(function(){

    if($(this).is(":checked") == true)
    {
        $("#activity_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" name="activity_data[]" value="1">' +
            '<table class="table table-bordered table-hover" ><thead><th class="text-center">S.No</th>' +
            '<th class="text-center">Name Of Institution<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Position Held<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center"><button type="button" id="addMoreActivities" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>' +
            '</thead>' +
            '<tbody id="insert_clone4"><tr class="get_rows4"><td class="text-center">' +
            '<span class="badge badge-pill badge-secondary">1</span></td>' +
            '<td><input class="form-control requiredField" name="institution_name_1" id="institution_name_1"></td>' +
            '<td><input type="text" class="form-control requiredField" name="position_held_1" id="position_held_1" /></td><td></td></tbody></table></div>');

        $("#addMoreActivities").click(function(e){

            var form_rows_count = $(".get_rows4").length;
            form_rows_count++;
            $("#insert_clone4").append('<tr class="remove_area4_'+form_rows_count+' get_rows4">' +
                '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                '<td><input class="form-control requiredField" name="institution_name_'+form_rows_count+'" id="institution_name_'+form_rows_count+'"></td>' +
                '<td><input type="text" class="form-control requiredField" name="position_held_'+form_rows_count+'" id="position_held_'+form_rows_count+'" /></td>' +
                '<td class="text-center"><button type="button" onclick="removeActivityDataSection('+form_rows_count+')" class="btn btn-sm btn-danger remove_area4_'+form_rows_count+'"><i class="fas fa-trash"></i></button></td></tr>');
        });
    }
    else
    {
        $("#activity_area").html('');
    }

});

$('#work_experience_check').click(function(){

    if($(this).is(":checked") == true)
    {
        $("#work_experience_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="">' +
            '<table class="table table-bordered table-hover"><thead><th class="text-center">S.No</th>' +
            '<th class="text-center">Organization Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">From</th><th class="text-center">Till</th>' +
            '<th class="text-center">File</th>' +
            ' <th class="text-center"><button type="button" id="addMoreWorkExperience" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>' +
            '</thead><tbody id="insert_clone1"><tr class="get_rows1"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
            '<td id="get_clone1" class="text-center"><input type="hidden" name="work_experience_data[]" value="1">' +
            '<input type="text" name="employeer_name_1" id="employeer_name_1" class="form-control requiredField"></td>' +
            '<td class="text-center"><input name="started_1" type="date" class="form-control" id="started_1">' +
            '</td><td class="text-center"><input name="ended_1" id="ended_1"type="date" class="form-control" ></td>' +
            '<td class="text-center"><input type="file" class="form-control" name="work_exp_path_1" id="work_exp_path_1" multiple></td>' +
            '<td class="text-center">-</td></tr></tbody></table></div></div>' +
            '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12"><br><label class="sf-label">Have you ever been dismissed / suspended from service ?</label>' +
            '<input type="radio" name="suspend_check_1" id="suspend_check_1" value="no" checked/>&nbsp; No' +
            '<input type="radio" name="suspend_check_1" id="suspend_check_1" value="yes" />&nbsp; Yes &nbsp;' +
            '</div><div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" id="suspend_detail_1"></div>');


        $("input[name='suspend_check_1']").click(function() {
            if($(this).val() == 'yes')
            {
                $("#suspend_detail_1").html('<label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" class="form-control requiredField" placeholder="Suspend Reason" name="suspend_reason_1" id="suspend_reason_1" value="" />');
            }
            else
            {
                $("#suspend_detail_1").html('');
            }
        });

        $("#addMoreWorkExperience").click(function(e){
            var form_rows_count = $(".get_rows1").length;
            form_rows_count++;
            $("#insert_clone1").append("<tr class='get_rows1' id='remove_area1_"+form_rows_count+"'><td class='text-center'>" +
                "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td><td>" +
                '<input type="hidden" name="work_experience_data[]" value="'+form_rows_count+'">' +
                "<input type='text' name='employeer_name_"+form_rows_count+"' class='form-control requiredField' required></td>" +
                "<td class='text-center'><input name='started_"+form_rows_count+"' id='started_"+form_rows_count+"'  type='date' class='form-control' value=''></td>" +
                "<td class='text-center'><input name='ended_"+form_rows_count+"' id='ended_"+form_rows_count+"' type='date' class='form-control' value=''></td>" +
                "<td class='text-center'><input type='file' class='form-control' name='work_exp_path_"+form_rows_count+"' id='work_exp_path_"+form_rows_count+"' multiple></td>" +
                "<td class='text-center'><button onclick='removeWorkExperienceSection("+form_rows_count+")' type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                "</td>" +
                "</tr>");

        });
    }
    else
    {
        $("#work_experience_area").html('');
    }
});

$('#reference_check').click(function(){

    if($(this).is(":checked") == true)
    {
        $("#reference_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows2">' +
            '<table class="table table-bordered table-hover" id="get_clone2"><thead>' +
            '<th class="text-center">S.No</th>' +
            '<th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Designation<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Organization<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Contact Number<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Relationship<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center"><button type="button" id="addMoreReference" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th></thead>' +
            '<tbody id="insert_clone2"><tr><td class="text-center"><input type="hidden" name="reference_data[]" value="1"><span class="badge badge-pill badge-secondary">1</span></td>' +
            '<td><input class="form-control requiredField" name="reference_name_1" id="reference_name_1"></td>' +
            '<td><input class="form-control requiredField" name="reference_designation_1" id="reference_designation_1"></td>' +
            '<td><input class="form-control requiredField" name="reference_organization_1" id="reference_organization_1"></td>' +
            '<td><input class="form-control" type="text" name="reference_contact_1" id="reference_contact_1"></td>' +
            '<td><input class="form-control requiredField" name="reference_relationship_1" id="reference_relationship_1"></td>'+
            '<td></td></tr></tbody></table></div>');


        $("#addMoreReference").click(function(e){
            var form_rows_count = $(".get_rows2").length;
            form_rows_count++;
            $("#insert_clone2").append('<tr class="get_rows2" id="remove_area2_'+form_rows_count+'">' +
                '<td class="text-center"><input type="hidden" name="reference_data[]" value="'+form_rows_count+'">' +
                '<span class="badge badge-pill badge-secondary">'+form_rows_count+'<span></td>' +
                '<td><input class="form-control requiredField" name="reference_name_'+form_rows_count+'" id="reference_name_'+form_rows_count+'"></td>' +
                '<td><input class="form-control requiredField" name="reference_designation_'+form_rows_count+'" id="reference_designation_'+form_rows_count+'"></td>' +
                '<td><input class="form-control requiredField" name="reference_organization_'+form_rows_count+'" id="reference_organization_'+form_rows_count+'"></td>' +
                '<td><input class="form-control" type="text" name="reference_contact_'+form_rows_count+'"  id="reference_contact_'+form_rows_count+'"></td>' +
                '<td><input class="form-control requiredField" name="reference_relationship_'+form_rows_count+'" id="reference_relationship_'+form_rows_count+'"></td>' +
                '<td class="text-center"><button type="button" onclick="removeReferenceSection('+form_rows_count+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td></tr>')
        });

    }
    else
    {
        $("#reference_area").html('');
    }

});

$('#kins_check').click(function(){

    if($(this).is(":checked") == true)
    {
        $("#kins_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows9"><div class=""> ' +
            '<table class="table table-bordered table-hover" ><thead><th class="text-center">S.No</th>' +
            '<th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Relation<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center"> <button type="button" id="addMoreKinDetails" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>' +
            '</thead><tbody id="insert_clone9"><tr>' +
            '<td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
            '<td class="text-center"><input type="hidden" name="kins_data[]" value="1">' +
            '<input class="form-control requiredField" name="next_kin_name_1" id="next_kin_name_1" required></td>' +
            '<td class="text-center"><input class="form-control requiredField" name="next_kin_relation_1" id="next_kin_relation_1" required></td>'+'<td class="text-center">-</td></tr></tbody></table><div></div></div></div>');


        $("#addMoreKinDetails").click(function(e){

            var form_rows_count = $(".get_rows9").length;
            form_rows_count++;
            $("#insert_clone9").append('<tr class="remove_area6_'+form_rows_count+' get_rows9" id="">' +
                '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                "<td class='text-center'><input type='hidden' name='kins_data[]' value="+form_rows_count+">" +
                "<input class='form-control requiredField' name='next_kin_name_"+form_rows_count+"' id='next_kin_name_"+form_rows_count+"' required></td>" +
                "<td class='text-center'><input class='form-control requiredField' name='next_kin_relation_"+form_rows_count+"' id='next_kin_relation_"+form_rows_count+"' required></td>" +
                "<td class='text-center'><button type='button' onclick='removeKinDetailsSection("+form_rows_count+")' class='btn btn-sm btn-danger remove_area9_"+form_rows_count+"'><i class='fas fa-trash'></i></button></td>" +
                '</tr>');

        });

    }
    else
    {
        $("#kins_area").html('');
    }

});

$("input[name='relative_check']").click(function() {

    if($(this).val() == 'Yes')
    {
        $("#relative_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 get_rows10">' +
            '<input type="hidden" name="relatives_data[]" value="1"><div class=""><table class="table table-bordered table-hover">' +
            '<thead><th class="text-center">S.No</th><th class="text-center">Name<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center">Position<span class="rflabelsteric"><strong>*</strong></span></th>' +
            '<th class="text-center"> <button type="button" id="addMoreRelativesDetails" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>' +
            '</thead><tbody id="insert_clone10"><tr><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
            '<td class="text-center"><input class="form-control requiredField" name="relative_name_1" id="relative_name_1" required></td>' +
            '<td class="text-center"><input class="form-control requiredField" name="relative_position_1"  id="relative_position_1" required></td>' +
            '<td class="text-center">-</td></tr></tbody></table><div></div></div></div>');

        $("#addMoreRelativesDetails").click(function(e){

            var form_rows_count = $(".get_rows10").length;
            form_rows_count++;
            $("#insert_clone10").append('<tr class="remove_area10_'+form_rows_count+' get_rows10" id="">' +
                '<td class="text-center"><span class="badge badge-pill badge-secondary">'+form_rows_count+'</td>' +
                '<td class="text-center"><input type="hidden" name="relatives_data[]" value='+form_rows_count+'>' +
                '<input class="form-control requiredField" name="relative_name_'+form_rows_count+'" value="" id="relative_name_'+form_rows_count+'" required></td>' +
                '<td class="text-center"><input class="form-control requiredField" name="relative_position_'+form_rows_count+'" value="" id="next_kin_relation_'+form_rows_count+'" required></td>' +
                '<td class="text-center"><button type="button" onclick="removeRelativesDetailsSection('+form_rows_count+')" class="btn btn-sm btn-danger remove_area10_'+form_rows_count+'"><i class="fas fa-trash"></i></button></td>' +
                '</tr>');

        });

    }
    else
    {
        $("#relative_area").html('');
    }
});

$("input[name='transport_check_1']").click(function() {

    if($(this).val() == 'Yes')
    {
        $("#transport_particular_area_1").html(' <label class="sf-label">Particulars</label>' +
            '<span class="rflabelsteric"><strong>*</strong></span>' +
            '<input type="text" class="form-control requiredField" placeholder="Particulars" name="transport_particulars_1" id="transport_particulars_1" value="" />' +
            '');
    }
    else
    {
        $("#transport_particular_area_1").html('');
    }
});

$('#documents_upload_check').click(function(){

    if($(this).is(":checked") == true)
    {
        $("#file_upload_area").html('<div class="row"><div class="col-sm-4"><label for="media">Upload CV:</label>' +
            '<input type="file" class="form-control" name="media[]" id="media" multiple></div>' +
           '<div class="col-sm-4"><label for="media">Upload Education Degrees:</label>' +
        '<input type="file" class="form-control" name="edu_degrees[]" id="edu_degrees" multiple></div>' +
        '<div class="col-sm-4"><label for="media">Upload 2 Passport size photos:</label>' +
        '<input type="file" class="form-control" name="passport_photos[]" id="passport_photos" multiple></div></div></hr></br>' +
            '<div class="row"><div class="col-sm-4"><label for="media">Upload CNIC:</label>' +
            '<input type="file" class="form-control" name="cnic_path_1" id="cnic_path_1"></div>' +
            '<div class="col-sm-4"><label for="media">Clearance Letter (If Any):</label>' +
        '<input type="file" class="form-control" name="clearance_letter[]" id="clearance_letter" multiple></div>' +
            '<div class="col-sm-4"><label for="media">Fit & Proper Criteria Form:</label>' +
            '<input type="file" class="form-control" name="fit_and_criteria_form[]" id="fit_and_criteria_form" multiple></div>' +
            '</div></hr></br>'+
            '<div class="row"><div class="col-sm-6"><label for="media">Affirmation of having read the Company Service Rules</label>' +
            '<input type="file" class="form-control" name="company_service_rules[]" id="company_service_rules" multiple></div>' +
            '<div class="col-sm-6"><label for="media">Affirmation of having read the VIS Code of Conduct 2019</label>' +
            '<input type="file" class="form-control" name="code_of_conduct[]" id="code_of_conduct" multiple></div>' +
            '</div></hr></br>'+
            '<div class="row"><div class="col-sm-4"><label for="media">Upload Non-Disclosure Agreement:</label>' +
            '<input type="file" class="form-control" name="non_disclosure_agreement[]" id="non_disclosure_agreement" multiple></div>' +
            '<div class="col-sm-4"><label for="media">Upload Information Secrecy/Confidentially Form:</label>' +
            '<input type="file" class="form-control" name="confidentially_form[]" id="confidentially_form" multiple></div>' +
            '<div class="col-sm-4"><label for="media">Upload Investment Disclosure:</label>' +
            '<input type="file" class="form-control" name="investment_disclosure[]" id="investment_disclosure" multiple></div></div>'
        );
    }
    else
    {
        $("#file_upload_area").html('');
    }
});

$("#emp_id").change(function() {
    var emp_id = $("#emp_id").val();

    $.ajax({
        url: baseUrl+'/hdc/checkEmrNoExist',
        type: "get",
        data: { _token: $('meta[name=csrf-token]').attr('content'), emp_id:emp_id ,m : m},
        success:function(data) {
            if(data == 'success')
            {
                $('#emp_warning').html('');
                $(".btn_disable").removeAttr("disabled");
                $("#emrExistMessage").html('');
            }
            else
            {
                $('.btn_disable').attr('disabled', 'disabled');
                $('#emp_warning').html('Please Remove Errors !');
                $("#emrExistMessage").html(data);
            }
        }
    });
});

$("#cnic").change(function() {
    var emp_cnic = $("#cnic").val();
    

    $.ajax({
        url: baseUrl+'/hdc/checkCnicNoExist',
        type: "get",
        
        data: {emp_cnic:emp_cnic, m: m},
        success:function(data) {
            if(data == 'success')
            {
                $('#emp_warning').html('');
                $("#btn_disable").removeAttr("disabled");
                $("#cnicExistMessage").html('');
            }
            else
            {
                $('#btn_disable').attr('disabled', 'disabled');
                $('#emp_warning').html('Please Remove Errors !');
                $("#cnicExistMessage").html(data);
            }
        }
    });
});

$('#life_time_cnic').click(function(){
    if($(this).is(":checked") == true)
    {
        $("#cnic_expiry_date").attr('disabled', 'disabled');
        $("#cnic_expiry_date").removeClass('requiredField');
    }

    else
    {
        $("#cnic_expiry_date").removeAttr('disabled');
        $("#cnic_expiry_date").addClass('requiredField');
    }

});

function employeestatus(value){
    if(value == 8 || value == 13 || value==21){

        $('#pTimePeriod').prop("disabled", false);
        $("#pTimePeriod").addClass('requiredField');
    }
    else{

        $("#pTimePeriod").val('');
        $('#pTimePeriod').prop("disabled", true);
        $("#pTimePeriod").removeClass('requiredField');
        if(value == 7){
            $('#show_permanent_form').trigger("click");
        }

    }
}

function getDesignations (val){

    $.ajax({
        url: baseUrl+'/hdc/getDesignations',
        type: "get",

        data: {m: m,value:val},
        success:function(data) {
            $('#designations').html('');
            var sel = $('<select style="width:100%;" class="form-control requiredField designation" id="designation_1" name="designation_1">').appendTo('#designations');
            sel.append($("<option>").attr('value','').text('Select'));
            $(sel).select2();
            $(data).each(function() {
                sel.append($("<option>").attr('value',this.id).text(this.designation_name));
            });
        }
    });

}

function password_generator( len ) {
    var length = (len)?(len):(10);
    var string = "abcdefghijklmnopqrstuvwxyz"; //to upper
    var numeric = '0123456789';
    var punctuation = '!@#$%^&*()_+~`|}{[]\:;?><,./-=';
    var password = "";
    var character = "";
    var crunch = true;
    while( password.length<length ) {
        entity1 = Math.ceil(string.length * Math.random()*Math.random());
        entity2 = Math.ceil(numeric.length * Math.random()*Math.random());
        entity3 = Math.ceil(punctuation.length * Math.random()*Math.random());
        hold = string.charAt( entity1 );
        hold = (password.length%2==0)?(hold.toUpperCase()):(hold);
        character += hold;
        character += numeric.charAt( entity2 );
        character += punctuation.charAt( entity3 );
        password = character;
    }
    password=password.split('').sort(function(){return 0.5-Math.random()}).join('');
    document.getElementById("password").value = password.substr(0,len);
}

$('.cnicExistMessage').on('keydown',function(evt){

    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 8 || charCode == 9
         || charCode == 27 || charCode == 13
         || (charCode == 65 && evt.ctrlKey === true) )
        return;
    if ((charCode < 48 || (charCode > 57 && charCode < 96 || charCode > 105 ))) {
        return false;
    }
    else{
        var cnicLength = $(this).val().length;
        if( cnicLength <=15){
            $('#cnicExistMessage').text('Not less than 13').css("color", "red");
            $('#btn_disable').attr('disabled', 'disabled');
        }
        if (cnicLength == 15) {
            $('#cnicExistMessage').text('');
            $("#btn_disable").removeAttr("disabled");
        }
        if(cnicLength == 5 || cnicLength == 13){
            $(this).val($(this).val()+'-');
        }
    }

});

function stopKeyPressedSpace(){
    var key = event.keyCode || event.charCode || event.which ;
    return key;
}

function checkMobileNumber(paramOne,paramTwo,paramThree,e){

    var mobileNumbers  = paramOne;
    var phoneno = /^[2-9]\d{9}$/;
    var charCode = (e.which) ? e.which : event.keyCode;
    if(paramThree == '1'){
        if(phoneno.test(mobileNumbers)) {
            $('.'+paramTwo+'').html('');
            $("#btn_disable").removeAttr("disabled");
            return true;
        }else {
            if(charCode == 48 && mobileNumbers.length  ==1 ){
                $('#btn_disable').attr('disabled', 'disabled');
                $('.'+paramTwo+'').html('zero is not allowed').css("color", "red");
            }else{
                if (mobileNumbers.length  <10 ) {
                    $('#btn_disable').attr('disabled', 'disabled');
                    $('.'+paramTwo+'').html('Only 10 digits Mobile Number Allowed').css("color", "red");
                    return false;
                }else if(mobileNumbers.length  >10) {
                    $('#btn_disable').attr('disabled', 'disabled');
                    $('.'+paramTwo+'').html('Only 10 digits Mobile Number Allowed ').css("color", "red");
                    return false;
                }else{
                    $('.'+paramTwo+'').html('');
                    $("#btn_disable").removeAttr("disabled");
                    return true;
                }
            }
        }
    }else if(paramThree == '2'){
        if(phoneno.test(mobileNumbers)) {
            $('.'+paramTwo+'').html('');
            $("#btn_disable").removeAttr("disabled");
            return true;
        }else {
            if(charCode == 48 && mobileNumbers.length  ==1){

                $('.'+paramTwo+'').html('zero is not allowed').css("color", "red");
                $('#btn_disable').attr('disabled', 'disabled');
            }else{
                if (mobileNumbers.length  <10 ) {
                    $('.'+paramTwo+'').html('Only 10 digits Mobile Number  Not Less Than 10').css("color", "red");
                    $('#btn_disable').attr('disabled', 'disabled');
                    return false;
                }else if(mobileNumbers.length  >10) {
                    $('.'+paramTwo+'').html('Only 10 digits Mobile Number Not Greater Than 10 ').css("color", "red");
                    $('#btn_disable').attr('disabled', 'disabled');
                    return false;
                }else{
                    $('.'+paramTwo+'').html('');
                    $("#btn_disable").removeAttr("disabled");
                    return true;
                }
            }
        }
    }
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

//$('#leaves_policy_id_1').click(function (e)
//{
//    var leaves_policy_id = $('#leaves_policy_1').val();
//    if(leaves_policy_id != ''){
//
//        showDetailModelFourParamerter('hdc/viewLeavePolicyDetail',leaves_policy_id,'View Leaves Policy Detail ',m);
//    }
//    else
//    {
//        alert('Please Select Policy !');
//    }
//});


//function readURL(input) {
//    if (input.files && input.files[0]) {
//        var reader = new FileReader();
//
//        reader.onload = function (e) {
//            $('#img_file_1').attr('src', e.target.result);
//        }
//
//        reader.readAsDataURL(input.files[0]);
//    }
//}
//
//$("#file_1").change(function(){
//    readURL(this);
//});

$("input[name='crime_check']").click(function() {

    if($(this).val() == 'Yes')
    {
        $("#crime_check_input").html('<label class="sf-label">Details</label>' +
            '<span class="rflabelsteric"><strong>*</strong></span>' +
            '<textarea class="form-control requiredField" name="crime_detail" id="crime_detail"></textarea>' +
            '');
    }
    else
    {
        $("#crime_check_input").html('');
    }
});


$("input[name='additional_info_check']").click(function() {

    if($(this).val() == 'Yes')
    {
        $("#additional_info_input").html('<label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>' +
            ' <textarea class="form-control requiredField" name="additional_info_detail" id="additional_info_detail"></textarea>');
    }
    else
    {
        $("#additional_info_input").html('');
    }
});

function deleteEmployee(companyId,recordId,tableName,emp_id){

    if(confirm("Do you want to delete this record ?") == true){
        $.ajax({
            url: baseUrl+'/cdOne/deleteEmployee',
            type: "GET",
            data: {'request_type':'delete',companyId:companyId,recordId:recordId,tableName:tableName,'emp_id':emp_id},
            success:function(data) {
                location.reload();
            }
        });
    }
    else{
        return false;
    }
}

function restoreEmployee(companyId,recordId,tableName){

    $.ajax({
        url: baseUrl+'/cdOne/restoreEmployee',
        type: "get",
        data: {companyId:companyId,recordId:recordId,tableName:tableName},
        success:function(data) {
            location.reload();
        }
    });
}

function createEmployeeJoiningLetter(){
    var sub_department_id=$('#sub_department_id').val();
    var emp_id=$('#emp_id').val();
    var department_id=$('#department_id').val();
    var designation_id=$('#designation_id').val();
    var date=$('#date').val();

    jqueryValidationCustom();
    if(validate == 0) {
        $("#employeePayslipSection").css({"display": "none"});
        $.ajax({
            url: baseUrl + '/hdc/createEmployeeJoiningLetter',
            type: "GET",
            data:{m:m,date:date,designation_id:designation_id,department_id:department_id,emp_id:emp_id,sub_department_id:sub_department_id},
            success: function (res) {
                $("#employeePayslipSection").css({"display": "block"});
                $('.JoiningLetterForm').html(res);

            }
        });
    }
    else{
        return false;
    }
}

function workExpFile(id) {
    $('.workExpFile_'+id).hide();
    $('#work_exp_path_'+id).show();
}

function documentFile(id) {
    $('#'+id).hide();
    $('#media_show').show();
}



// $('#cnic_1').keydown(function(){
//
//
//     //allow  backspace, tab, ctrl+A, escape, carriage return
//     if (event.keyCode == 8 || event.keyCode == 9
//         || event.keyCode == 27 || event.keyCode == 13
//         || (event.keyCode == 65 && event.ctrlKey === true) )
//         return;
//     if((event.keyCode < 48 || (event.keyCode > 57 && event.keyCode < 96 || event.keyCode > 105 )))
//         event.preventDefault();
//
//     var length = $(this).val().length;
//
//     if(length == 5 || length == 13)
//         $(this).val($(this).val()+'-');
//
//     if((length > 13))
//         return false;
// });

//$("input[name='transport_check_1']").click(function() {
//
//    if($(this).val() == 'Yes')
//    {
//        $("#transport_particular_area_1").html(' <label class="sf-label">Particulars</label>' +
//            '<span class="rflabelsteric"><strong>*</strong></span>' +
//            '<input type="text" class="form-control requiredField" placeholder="Particulars" name="transport_particulars_1" id="transport_particulars_1" value="" />' +
//            '');
//    }
//    else
//    {
//        $("#transport_particular_area_1").html('');
//    }
//});

//function getSubDepartment(value){
//    if(value != ''){
//        $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
//        $.ajax({
//            type:'GET',
//            url:baseUrl+'/slal/getSubDepartment',
//            data:{id:value},
//            success:function(res){
//                $('#emp_loader').html('');
//                $('select[name="sub_department_id_1"]').empty();
//                $('select[name="sub_department_id_1"]').html(res);
//                $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
//            }
//        })
//    }
//    else{
//
//        $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
//        $('select[name="sub_department_id_1"]').empty();
//    }
//}

// function getEmployee(){
//
//     var department = $("#department_id").val();
//     var sub_department = $("#sub_department_id").val();
//
//     if(department == '0'){
//         $("#department_id_").val('0');
//         $("#sub_department_id").val('0');
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
//         $('select[name="sub_department_id"]').empty();
//         $('select[name="emr_no"]').empty();
//     }
// }

// $('#documents_upload_check').click(function(){
//
//     if($(this).is(":checked") == true)
//     {
//         $("#file_upload_area").html('<label for="media">Upload File:</label>' +
//             '<input type="file" class="form-control" name="media[]" id="media" multiple>');
//     }
//     else
//     {
//         $("#file_upload_area").html('');
//     }
// })


// $("#emp_id").change(function() {
//     var emp_id = $("#emp_id").val();
//
//     $.ajax({
//         url: baseUrl+'/hdc/checkEmrNoExist',
//         type: "POST",
//         data: { _token: $('meta[name=csrf-token]').attr('content'), emp_id:emp_id ,m : m},
//         success:function(data) {
//             if(data == 'success')
//             {
//                 $('#emp_warning').html('');
//                 $(".btn_disable").removeAttr("disabled");
//                 $("#emrExistMessage").html('');
//             }
//             else
//             {
//                 $('.btn_disable').attr('disabled', 'disabled');
//                 $('#emp_warning').html('Please Remove Errors !');
//                 $("#emrExistMessage").html(data);
//             }
//         }
//     });
// });

// $("#cnic_1").change(function() {
//     var emp_cnic = $("#cnic_1").val();

//     $.ajax({
//         url: baseUrl+'/hdc/checkCnicNoExist',
//         type: "POST",
//         data: { _token: $('meta[name=csrf-token]').attr('content'), emp_cnic:emp_cnic, m: m},
//         success:function(data) {
//             if(data == 'success')
//             {
//                 $('#emp_warning').html('');
//                 $(".btn_disable").removeAttr("disabled");
//                 $("#cnicExistMessage").html('');
//             }
//             else
//             {
//                 $('.btn_disable').attr('disabled', 'disabled');
//                 $('#emp_warning').html('Please Remove Errors !');
//                 $("#cnicExistMessage").html(data);
//             }
//         }
//     });
// });

// $('#life_time_cnic_1').click(function(){
//     if($(this).is(":checked") == true)
//     {
//         $("#cnic_expiry_date_1").attr('disabled', 'disabled');
//         $("#cnic_expiry_date_1").removeClass('requiredField');
//     }

//     else
//     {
//         $("#cnic_expiry_date_1").removeAttr('disabled');
//         $("#cnic_expiry_date_1").addClass('requiredField');
//     }

// });

//function getSubDepartment(value){
//    if(value != ''){
//        $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
//        $.ajax({
//            type:'GET',
//            url:baseUrl+'/slal/getSubDepartment',
//            data:{id:value},
//            success:function(res){
//                $('#emp_loader').html('');
//                $('select[name="sub_department_id_1"]').empty();
//                $('select[name="sub_department_id_1"]').html(res);
//                $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
//            }
//        })
//    }
//    else{
//
//        $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
//        $('select[name="sub_department_id_1"]').empty();
//    }
//}

//$("#transport_yes_1").change(function(){
//    if($("#transport_yes_1").prop('checked') == true)
//    {
//        $("#transport_particular").fadeIn();
//        $("#transport_no_1").prop('checked', false);
//    }
//    else
//    {
//        $("#transport_particular").fadeOut();
//        $("#transport_yes_1").prop('checked', false);
//    }
//});
//
//$("#transport_no_1").change(function(){
//    if($("#transport_no_1").prop('checked') == true)
//    {
//        $("#transport_particular").fadeOut();
//        $("#transport_yes_1").prop('checked', false);
//    }
//
//});