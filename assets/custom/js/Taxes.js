var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function() {

    // Wait for the DOM to be ready
    $(".btn-success").click(function(e){
        var lifeInsurance = new Array();
        var val;
        $("input[name='TaxesSection[]']").each(function(){
            lifeInsurance.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val in lifeInsurance) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });

    var Tax = 1;
    $('.addMoreTaxesSection').click(function (e){
        e.preventDefault();
        Tax++;
        $('.TaxesSection').append('<div style="margin-top:5px;" id="remove_area_'+Tax+'">' +
            '<a href="#" onclick="removeTaxesSection('+Tax+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>' +
            '<div class="panel"><div class="panel-body">' +
            '<div class="row">' +
            '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label>Salary Range From</label> ' +
            '<span class="rflabelsteric"><strong>*</strong></span> ' +
            '<input type="text" name="salary_range_from[]" id="salary_range_from" value="" class="form-control requiredField" /></div> ' +
            '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
            '<label>Salary Range To</label><span class="rflabelsteric"><strong>*</strong></span> ' +
            '<input type="text" name="salary_range_to[]" id="salary_range_to" value="" class="form-control requiredField" /> </div> ' +
            '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
            '<label>Percentange of Tax</label>' +
            '<span class="rflabelsteric"><strong>*</strong></span>' +
            '<input type="text" name="tax_percent[]" id="tax_percent" value="" class="form-control requiredField" /> </div> ' +
            '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label>Tax Amount Per Year</label> ' +
            '<input type="number" name="tax_amount[]" id="tax_amount" value="" class="form-control" /></div></div></div></div>');

    });

});

function removeTaxesSection(id){
    $("#remove_area_"+id).remove();
}
$(document).ready(function() {

    var table = $('#TaxesList').DataTable({
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

$(document).ready(function () {
    $('#sub_department_id').select2();
    $('#tax_id').select2();

});


function viewTaxCriteria()
{
    $('.viewCarPolicyArea').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

    var sub_department_id = $('#sub_department_id').val();
    var url= baseUrl+'/hdc/viewTaxCriteria';
    $.getJSON(url, { sub_department_id:sub_department_id,m:m} ,function(result){
        $.each(result, function(i, field){

            $('.viewCarPolicyArea').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+field+'</div>');

        });
    })
}
function viewTax(tax_id)
{

    var tax_id = tax_id;
    showDetailModelTwoParamerterJson('hdc/viewTax',tax_id,'View Tax ',m)
}


$(function(){
    $('select[name="sub_department_id"]').on('change', function() {
        var sub_department_id = $(this).val();

        if(sub_department_id) {
            $.ajax({
                url: baseUrl+'/slal/employeeLoadDependentDepartmentID',
                type: "GET",
                data: { sub_department_id:sub_department_id,m:m},
                success:function(data) {
                    $('select[name="employee_id"]').empty();
                    $('select[name="employee_id"]').html(data);
                }
            });
        }else{
            $('select[name="employee_id"]').empty();
        }
    });
});

var Tax = 1;
$('.addMoreTaxesSection').click(function (e){
    e.preventDefault();
    Tax++;
    $('.TaxesSection').append('<div style="margin-top: 5px;" id="remove_area_'+Tax+'">' +
        '<div class="row">' +
        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label>Salary Range From</label> ' +
        '<span class="rflabelsteric"><strong>*</strong></span> ' +
        '<input type="text" name="salary_range_from[]" id="salary_range_from" value="" class="form-control requiredField" /></div> ' +
        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
        '<label>Salary Range To</label><span class="rflabelsteric"><strong>*</strong></span> ' +
        '<input type="text" name="salary_range_to[]" id="salary_range_to" value="" class="form-control requiredField" /> </div> ' +
        '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
        '<label>Percentange</label>' +
        '<span class="rflabelsteric"><strong>*</strong></span>' +
        '<input type="text" name="tax_percent[]" id="tax_percent" value="" class="form-control requiredField" /> </div> ' +
        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label>Tax Amount Per Year</label> ' +
        '<input type="number" name="tax_amount[]" id="tax_amount" value="" class="form-control" /></div>' +
        '<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-top: 30px">' +
        '<a href="#" onclick="removeTaxesSection('+Tax+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a></div></div>');

});


function removeTaxesSection(id){
    $("#remove_area_"+id).remove();
}

function removeTax(id){
    var elem = document.getElementById('deduction_row_edit_'+id+'');
    elem.remove(elem);
}
