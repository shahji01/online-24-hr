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
                            <h4 class="card-title">Task Management Dashboard</h4>
                        </div>
                    </div>
                    <hr>
                    <?php 
                        if($accType == 'client'){
                            HrHelper::getAuthorizedInputFieldsFive();
                        }else{
                            HrHelper::getAuthorizedInputFieldsTwo();
                        }
                    ?>
                </div>
            </div>
        </div>
        <div id="tmsDashboard" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="showTMSDashboard"></div>
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
            filterTMSDashBoard($("#emp_id2").val());
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
        function filterTMSDashboardData(){
            var dfProjectId = $('#dfProjectId').val();
            var dfTaskId = $('#dfTaskId').val();
            var df_from_date = new Date($('#df_from_date').val());
            var df_to_date = new Date($('#df_to_date').val());
            var m = $('#m').val();
            var baseUrl = $('#baseUrl').val();
            var emp_id = $('#emp_id2').val();
            // Validate date range
            if (df_to_date < df_from_date) {
                alert('End date cannot be less than start date.');
                return; // Exit the function if dates are invalid
            }

            $.ajax({
                url: ''+baseUrl+'/ddc/filterTMSDashboardData',
                method: 'GET',
                data: {
                    m: m,
                    dfProjectId: dfProjectId,
                    dfTaskId:dfTaskId,
                    df_from_date: $('#df_from_date').val(),
                    df_to_date: $('#df_to_date').val(),
                    emp_id:emp_id
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                },
                success: function(response) {
                    $('#dTotalAssignProject').text(response.dTotalAssignProject);
                    $('#dTotalAssignProjectTask').text(response.dTotalAssignProjectTask);
                    $('#dTotalAssignProjectSubTask').text(response.dTotalAssignProjectSubTask);
                    $('#dAttendedProjectTask').text(response.dAttendedProjectTask);
                    $('#dCompletedProjectTask').text(response.dCompletedProjectTask);
                    $('#dPendingProjectTask').text(response.dPendingProjectTask);
                    $('#dInprogressProjectTask').text(response.dInprogressProjectTask);
                    $('#dAttendedProjectSubTask').text(response.dAttendedProjectSubTask);
                    $('#dCompletedProjectSubTask').text(response.dCompletedProjectSubTask);
                    $('#dPendingProjectSubTask').text(response.dPendingProjectSubTask);
                    $('#dInprogressProjectSubTask').text(response.dInprogressProjectSubTask);
                    getProjectTaskSummary();
                    getProjectSubTaskSummary();
                }
            });

        }
        function filterTMSDashBoard(value){
            $("#tmsDashboard").css({"display": "none"});
            var emp_id = value;
            var m = '{{ $m }}';
            $('#run_loaders').html('<div class="loader"></div>');
            $.ajax({
                type:'GET',
                url: '{{ url("/") }}/ddc/filterTMSDashboard',
                data:{employee_id:emp_id,m:m,filter:'user'},
                success:function(res){
                    $("#tmsDashboard").css({"display": "block"});
                    $("#showTMSDashboard").html(res);
                    $('#run_loaders').html('');
                    filterTMSDashboardData();
                },
                error: function() {
                    swalError();
                }
            });
        }
        function loadTaskAgainstProjectId(){
            var m = $('#m').val();
            var baseUrl = $('#baseUrl').val();
            var projectId = $('#dfProjectId').val();
            $('#dfTaskId').html('');
            if(projectId == ''){
                $('#dfTaskId').html('');
            }else{
                $.ajax({
                    url: ''+baseUrl+'/tms/project-sub-task/loadTaskAgainstProjectId',
                    method: 'GET',
                    data: {
                        m: m,
                        projectId: projectId,
                        type:2
                    },
                    error: function() {
                        alert('error');
                    },
                    success: function(response) {
                        $('#dfTaskId').append(response);
                    }
                });
            }
        }

        function loadTMSDashboardSummaryDetailOne(type,modelTitle){
            var dfProjectId = $('#dfProjectId').val();
            var dfTaskId = $('#dfTaskId').val();
            var m = $('#m').val();
            var baseUrl = $('#baseUrl').val();
            var emp_id = $('#emp_id2').val();
            if(type == 1){
                var urlTwo = 'employeeAssignProjectDetail';
            }else if(type == 2){
                var urlTwo = 'employeeAssignProjectTaskDetail';
            }else {
                var urlTwo = 'employeeAssignProjectSubTaskDetail';
            }
            $.ajax({
                url: ''+baseUrl+'/ddc/'+urlTwo+'',
                type: "GET",
                data: {dfProjectId:dfProjectId,dfTaskId:dfTaskId,emp_id:emp_id,m:m},
                success:function(data) {
                    jQuery('#showDetailModelOneParamerter').modal('show', {backdrop: 'false'});
                    //jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
                    jQuery('#showDetailModelOneParamerter .modalTitle').html(modelTitle);
                    jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    setTimeout(function(){
                        jQuery('#showDetailModelOneParamerter .modal-body').html(data);
                    },1000);
                }
            });
            
        }

        function loadTMSDashboardSummaryProjectWiseDetail(type,modelTitle){
            var dfProjectId = $('#dfProjectId').val();
            var dfTaskId = $('#dfTaskId').val();
            var m = $('#m').val();
            var baseUrl = $('#baseUrl').val();
            var emp_id = $('#emp_id2').val();
            $.ajax({
                url: ''+baseUrl+'/ddc/employeeProjectTaskDashboardDetail',
                type: "GET",
                data: {dfProjectId:dfProjectId,dfTaskId:dfTaskId,emp_id:emp_id,m:m,type:type},
                success:function(data) {
                    jQuery('#showDetailModelOneParamerter').modal('show', {backdrop: 'false'});
                    //jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
                    jQuery('#showDetailModelOneParamerter .modalTitle').html(modelTitle);
                    jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    setTimeout(function(){
                        jQuery('#showDetailModelOneParamerter .modal-body').html(data);
                    },1000);
                }
            });
        }

        function loadTMSDashboardSummaryProjectTaskWiseDetail(type,modelTitle){
            var dfProjectId = $('#dfProjectId').val();
            var dfTaskId = $('#dfTaskId').val();
            var m = $('#m').val();
            var baseUrl = $('#baseUrl').val();
            var emp_id = $('#emp_id2').val();
            $.ajax({
                url: ''+baseUrl+'/ddc/employeeProjectSubTaskDashboardDetail',
                type: "GET",
                data: {dfProjectId:dfProjectId,dfTaskId:dfTaskId,emp_id:emp_id,m:m,type:type},
                success:function(data) {
                    jQuery('#showDetailModelOneParamerter').modal('show', {backdrop: 'false'});
                    //jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
                    jQuery('#showDetailModelOneParamerter .modalTitle').html(modelTitle);
                    jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    setTimeout(function(){
                        jQuery('#showDetailModelOneParamerter .modal-body').html(data);
                    },1000);
                }
            });
        }
        
    </script>
@endsection