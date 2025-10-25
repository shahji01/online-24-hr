@php
    $m = Input::get('m');
    use App\Helpers\HrHelper;
    $accType = Auth::user()->acc_type;
@endphp
@extends('layouts.default')
@section('content')
    <style>
        .cardBody {
            border: 1px solid #ccc;
            margin-left: 0px;
            border-radius: 6px;
            box-shadow: 0 0 8px rgb(0 0 0 / 25%);
        }
        .cardHeading {
            font-size: 18px;
            text-align: center;
            font-weight: bold;
            color: #000;
            border-bottom: 1px #ccc solid;
            padding: 12px;
        }
        .cardContent {
            padding: 13px;
            text-align: center;
            font-size: 18px;
        }
        .leftCardBorder {
            border-left: 7px #0e276f solid;
            border-radius: 6px;
        }
        @media print {
            .cardBody {
                border: 1px solid #ccc;
                margin-left: 0px;
                border-radius: 6px;
                box-shadow: 0 0 8px rgb(0 0 0 / 25%);
            }
            .cardHeading {
                font-size: 14px;
                text-align: center;
                font-weight: bold;
                color: #000;
                border-bottom: 1px #ccc solid;
                padding: 12px;
            }
            .cardContent {
                padding: 13px;
                text-align: center;
                font-size: 14px;
            }
            .leftCardBorder {
                border-left: 7px #0e276f solid;
                border-radius: 6px;
            }
        }
    </style>
    <div class="page-wrapper">
        <div id="dashboardUser">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Task Management Summary</h4>
                        </div>
                    </div>
                    <hr>
                    <?php 
                        if($accType == 'client'){
                            HrHelper::getAuthorizedInputFieldsSix();
                        }else{
                            HrHelper::getAuthorizedInputFieldsThree();
                        }
                    ?>
                </div>
            </div>
        </div>
        <div id="tmSummaryDashboard" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="showTMSummaryDashboard"></div>
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
            filterTMSummaryDashBoard($("#emp_id2").val());
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
        function getProjectTaskSummary(){
            var dfProjectId = $('#dfProjectId').val();
            var dfTaskId = $('#dfTaskId').val();
            var m = $('#m').val();
            var baseUrl = $('#baseUrl').val();
            var emp_id = $('#emp_id2').val();
            $.ajax({
                url: ''+baseUrl+'/ddc/getProjectTaskSummary',
                method: 'GET',
                data: {
                    m: m,
                    dfProjectId: dfProjectId,
                    dfTaskId:dfTaskId,
                    emp_id:emp_id
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                },
                success: function(response) {
                    $('#getProjectTaskSummary').html(response);
                }
            });
        }

        function getProjectSubTaskSummary(){
            var dfProjectId = $('#dfProjectId').val();
            var dfTaskId = $('#dfTaskId').val();
            var m = $('#m').val();
            var baseUrl = $('#baseUrl').val();
            var emp_id = $('#emp_id2').val();
            $.ajax({
                url: ''+baseUrl+'/ddc/getProjectSubTaskSummary',
                method: 'GET',
                data: {
                    m: m,
                    dfProjectId: dfProjectId,
                    dfTaskId:dfTaskId,
                    emp_id:emp_id
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                },
                success: function(response) {
                    $('#getProjectSubTaskSummary').html(response);
                }
            });
        }
        function filterTMSummaryDashboardData(){
            var dfProjectId = $('#dfProjectId').val();
            var m = $('#m').val();
            var baseUrl = $('#baseUrl').val();
            var emp_id = $('#emp_id2').val();
            $.ajax({
                url: ''+baseUrl+'/ddc/filterTMSummaryDashboardData',
                method: 'GET',
                data: {
                    m: m,
                    dfProjectId: dfProjectId,
                    emp_id:emp_id
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                },
                success: function(response) {
                    $('#filterTMSummaryDashboardDataTwo').html(response);
                    getProjectTaskSummary();
                    getProjectSubTaskSummary();
                }
            });

        }
        function filterTMSummaryDashBoard(value){
            $("#tmSummaryDashboard").css({"display": "none"});
            var emp_id = value;
            var m = '{{ $m }}';
            $('#run_loaders').html('<div class="loader"></div>');
            $.ajax({
                type:'GET',
                url: '{{ url("/") }}/ddc/filterTMSummaryDashboard',
                data:{employee_id:emp_id,m:m,filter:'user'},
                success:function(res){
                    $("#tmSummaryDashboard").css({"display": "block"});
                    $("#showTMSummaryDashboard").html(res);
                    $('#run_loaders').html('');
                    filterTMSummaryDashboardData();
                },
                error: function() {
                    swalError();
                }
            });
        }
        
        
    </script>
@endsection