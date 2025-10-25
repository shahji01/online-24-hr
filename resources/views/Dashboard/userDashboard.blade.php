@extends('layouts.default')
@section('content')
    <style>
        .card-body{
            min-height:150px;
        }
    </style>
    <?php
        $m = Input::get('m');
        use App\Helpers\HrHelper;
        $accType = Auth::user()->acc_type;
    ?>

    <div class="page-wrapper">
        <div id="dashboardUser">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">User Dashboard</h4>
                        </div>
                    </div>
                    <hr>
                    <?php 
                        if($accType == 'client'){
                            HrHelper::getAuthorizedInputFieldsFour();
                        }else{
                            HrHelper::getAuthorizedInputFields();
                        }
                    ?>

                    <div class="row" id="run_loaders">
                    </div>
                </div>
            </div>
        </div>

        <div id="userDashboard" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="showUserDashboard"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>

        $(document).ready(function(){

            $('#emp_id2').select2();

            $("#loaderbody").css({"display": "block"});
            $("#footer").css({"display": "none"});
            // $('#emp_id2').select2();
            filterUserDashBoard($("#emp_id2").val());
            if(sessionStorage.getItem('successMsg')){
                $.notify({
                    icon: "fa fa-check-circle",
                    message: "<b>'"+sessionStorage.getItem('successMsg')+"'</b>."
                }, {
                    type: 'success',
                    timer: 3000
                });
            }
            sessionStorage.clear();
        });

        function filterUserDashBoard(value){
            $("#userDashboard").css({"display": "none"});
            var emp_id = value;
            var m = '{{ $m }}';
            $('#run_loaders').html('<div class="loader"></div>');
            $.ajax({
                type:'GET',
                url: '{{ url("/") }}/ddc/filterUserDashboard',
                data:{employee_id:emp_id,m:m,filter:'user'},
                success:function(res){
                    $("#userDashboard").css({"display": "block"});
                    $("#showUserDashboard").html(res);
                    $('#run_loaders').html('');
                },
                error: function() {
                    swalError();
                }
            });
        }
    </script>
@endsection