var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

function viewDashboardDetails(m){
    $.ajax({
        url: ''+baseUrl+'/hdc/viewDashboardDetails',
        type: "GET",
        data: {m:m},
        success:function(data) {
            $(".total_employees").html(data.employees);
            $(".total_employees_onboard").html(data.employees_onboard);
            $(".total_employees_exit").html(data.employees_exit);
            $(".total_departments").html(data.departments);

        },error: function () {
        }
    });
}

setTimeout(function() { viewDashboardDetails(m) }, 200);

$('#btn_update').on('click', function() {
    var $this = $(this);
    $('#btn_update').html('Loading');
    $('#btn_update').append('&nbsp;<span class="loading"></span>');
    $(".loading").addClass("spinner-border spinner-border-sm");

    setTimeout(function() {
        $('#btn_update').html('Update');
    }, 2000);
});
$('#btn_add').on('click', function() {
    var $this = $(this);
    $('#btn_add').hide();
    $('#btn_add').after('&nbsp;<button type="button" class="btn btn-success load">Loading <span class="loading"></span></button>');
    $(".loading").addClass("spinner-border spinner-border-sm");

    setTimeout(function() {
        $('#btn_add').show();
        $('.load').hide();
    }, 2000);
});
$('#btn_search').on('click', function() {
    var $this = $(this);
    $('#btn_search').hide();
    $('#btn_search').after('&nbsp;<button type="button" class="btn btn-primary load">Loading <span class="loading"></span></button>');
    $(".loading").addClass("spinner-border spinner-border-sm");

    setTimeout(function() {
        $('#btn_search').show();
        $('.load').hide();
    }, 2000);
});


$('#reset').on('click', function() {
    var $this = $(this);
    $('#reset').html('Loading');
    $('#reset').append('&nbsp;<span class="loading"></span>');
    $(".loading").addClass("spinner-border spinner-border-sm");

    setTimeout(function() {
        $('#reset').html('Clear');
    }, 2000);
});