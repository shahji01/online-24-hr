var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

$(document).ready(function() {
    
    $('#fixed_policies').click(function(){
        if($(this).is(":checked")){
            $('#from').attr('readonly',true);
            $('#to').attr('readonly',true);
        }else{
            $('#from').attr('readonly',false);
            $('#to').attr('readonly',false);
        }
    })
});
