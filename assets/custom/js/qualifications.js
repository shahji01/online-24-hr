var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

var validate=0;
$(document).ready(function() {
    // var qualification = 1;
    // $('.addMoreQualificationSection').click(function (e){
    //     e.preventDefault();
    //     qualification++;
    //
    //     $.ajax({
    //         url: baseUrl+'/hmfal/makeFormQualificationDetail',
    //         type: "GET",
    //         data: { id:qualification},
    //         success:function(data) {
    //             $('.qualificationSection').append('<div style="margin-top: 5px;" id="sectionQualification_'+qualification+'"><a href="#" onclick="removeQualificationSection('+qualification+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
    //         }
    //     });
    // });
    //
    // // Wait for the DOM to be ready
    $(".btn-success").click(function(e){
        var qualification = new Array();
        var val;
        $("input[name='qualificationSection[]']").each(function(){
            qualification.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val in qualification) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });

    $("#country_1").on('change', function() {

        var countryID = $('#country_1').val();

        if(countryID) {
            $.ajax({
                url: baseUrl+'/slal/stateLoadDependentCountryId',
                type: "GET",
                data: { id:countryID},
                success:function(data) {
                    $('#state_1').html(data);

                }
            });
        }
    });


});



function changeCity(id){
    var res = id.split("_");
    var stateID = $('#'+id+'').val();
    if(stateID) {
        $.ajax({
            url: baseUrl+'/slal/cityLoadDependentStateId',
            type: "GET",
            data: { id:stateID},
            success:function(data) {
                $('#city_1').empty();
                $('#city_1').html(data);
                //$('#city_'+res[1]+'').html(data);
            }
        });
    }else{
        $('#city_'+res[1]+'').empty();
    }
}

// function removeQualificationSection(id){
//     var elem = document.getElementById('sectionQualification_'+id+'');
//     elem.parentNode.removeChild(elem);
// }


$(document).ready(function() {

    var table = $('#QualificationList').DataTable({
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



$(document).ready(function() {
    var qualification = 1;
    $('.addMoreQualificationSection').click(function (e){
        e.preventDefault();
        qualification++;

        $.ajax({
            url: baseUrl+'/hmfal/makeFormQualificationDetail',
            type: "GET",
            data: { id:qualification},
            success:function(data) {
                $('.qualificationSection').append('<div id="sectionQualification_'+qualification+'"><a href="#" onclick="removeQualificationSection('+qualification+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
            }
        });
    });

    // Wait for the DOM to be ready
    // $(".btn-success").click(function(e){
    //     var qualification = new Array();
    //     var val;
    //     $("input[name='qualificationSection[]']").each(function(){
    //         qualification.push($(this).val());
    //     });
    //     var _token = $("input[name='_token']").val();
    //     for (val in qualification) {
    //
    //         jqueryValidationCustom();
    //         if(validate == 0){
    //             //alert(response);
    //         }else{
    //             return false;
    //         }
    //     }
    //
    // });



    $("#state_1").on('change', function () {

        var stateID = $('#state_1').val();
        if (stateID) {
            $.ajax({
                url: baseUrl+'/slal/cityLoadDependentStateId',
                type: "GET",
                data: {id: stateID},
                success: function (data) {
                    $('#city_1').html(data);
                }
            });
        }

    });

});




function removeQualificationSection(id){
    var elem = document.getElementById('sectionQualification_'+id+'');
    elem.parentNode.removeChild(elem);
}