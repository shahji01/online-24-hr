@php
    $accType = Auth::user()->acc_type;
    $remarksJson = $taskDetail->remarks;
    $remarksArray = json_decode($remarksJson, true);
@endphp
<style>
    .floatLeft{
        width: 40%;
        float: left;
    }
    .floatRight{
        width: 40%;
        float: right;
    }
</style>
@if($accType != 'customer')
    @if($taskDetail->start_date == null || $taskDetail->end_date == null)
        <div class="row" id="updateDetailSection">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks" id="remarks">-</textarea>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Task Start Date</label>
                        <input type="date" name="task_start_date" id="task_start_date" class="form-control" value="{{date('Y-m-d')}}" />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>&nbsp;</label>
                        <input type="text" value="To" class="form-control" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Task End Date</label>
                        <input type="date" name="task_end_date" id="task_end_date" class="form-control" value="{{date('Y-m-d')}}" />
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                        <label>&nbsp;</label>
                        <input type="button" class="btn btn-success btn-xs" onclick="updateDetailAgainstTask()" value="Update Detail" />
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            @if($taskDetail->task_status != 2)
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks" id="remarks">-</textarea>
            </div>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                @if($taskDetail->task_status != 2)
                    @if(empty($taskWorkHistoryLast))
                        <input type="button" class="btn btn-xs btn-success" onclick="taskStart()" value="Start Timer"/>
                    @elseif($taskDetail->task_status == 6)
                        <input type="button" class="btn btn-xs btn-success" onclick="taskStart()" value="Start Timer"/>
                    @elseif($taskDetail->task_status == 5)
                        <input type="hidden" name="taskWorkHistoryLastId" id="taskWorkHistoryLastId" value="{{$taskWorkHistoryLast->id}}" />
                        @if($countPendingSubTasks == 0)
                            <input type="button" class="btn btn-xs btn-danger" onclick="subTaskAdditionalActivity(3)" value="Complete Task"/>
                        @endif
                        <input type="button" class="btn btn-xs btn-danger" onclick="subTaskAdditionalActivity(4)" value="Reassign Task"/>
                    @elseif($taskWorkHistoryLast->end_date == '0000-00-00')
                        <input type="hidden" name="taskWorkHistoryLastId" id="taskWorkHistoryLastId" value="{{$taskWorkHistoryLast->id}}" />
                        <input type="button" class="btn btn-xs btn-danger" onclick="subTaskAdditionalActivity(1)" value="Stop Timer"/>
                        <input type="button" class="btn btn-xs btn-danger" onclick="subTaskAdditionalActivity(2)" value="Send Testing"/>
                    @else
                        <input type="button" class="btn btn-xs btn-success" onclick="taskStart()" value="Start Timer"/>
                    @endif
                @endif
            </div>
        </div>
    @endif
@endif
<div class="lineHeight">&nbsp;</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="floatLeft">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th>Customer Name</th>
                                <td>{{$taskDetail->customer_name}}</td>
                            </tr>
                            <tr>
                                <th>Project Name</th>
                                <td>{{$taskDetail->project_name}}</td>
                            </tr>
                            <tr>
                                <th>Project Type</th>
                                <td>@if ($taskDetail->project_type == 1) Local @else International @endif</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="floatRight">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th>Assign Employee</th>
                                <td>{{$taskDetail->emp_name}}</td>
                            </tr>
                            <tr>
                                <th>Task Name</th>
                                <td>{{$taskDetail->task_name}}</td>
                            </tr>
                            <tr>
                                <th>Task Status</th>
                                <td>@if ($taskDetail->task_status == 1) Pending @elseif ($taskDetail->task_status == 2) Completed @elseif ($taskDetail->task_status == 3) Hold @elseif ($taskDetail->task_status == 4) Inprogress  @else Inprogress @endif</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(!empty($remarksArray) && count($remarksArray) > 0)
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Activity Remarks</label>
        
        @foreach($remarksArray as $key => $value)
            <div class="row mb-2">
                <div class="col-sm-4 font-weight-bold">{{ $key }}</div>
                <div class="col-sm-8">{{ $value }}</div>
            </div>
        @endforeach
        
    </div>
</div>
@endif
<div class="row">
    <div class="col-lg-12" id="loadCommentAgainstTask">
        <!-- Comments will load here -->
    </div>

    <div class="col-lg-12">
        <h4>Add Comment</h4>
        <form id="addCommentAgainstTask" enctype="multipart/form-data">
            <div class="form-group">
                <textarea class="form-control" name="comment_body" id="comment_body" placeholder="Write a comment..."></textarea>
            </div>
            <div class="form-group">
                <label>Attach File/Image:</label>
                <input type="file" name="attachment[]" id="attachment" class="form-control" accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx" multiple>
            </div>
            <div class="form-group">
                <input type="button" onclick="addCommentAgainstTask()" class="btn btn-primary" value="Add Comment" />
            </div>
        </form>
    </div>
</div>
<script>
    //(() => {
        const getCommonData = () => {
            return {
                taskId: '{{$taskDetail->id}}',
                m: $('#m').val(),
                baseUrl: $('#baseUrl').val()
            };
        };

        function addCommentAgainstTask() {
            const { taskId, m, baseUrl } = getCommonData();
            const url = `${baseUrl}/tms/project-tasks/project_task_comment`;
            const formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('m', m);
            formData.append('taskId', taskId);
            formData.append('commentBody', $('#comment_body').val());

            // Append multiple attachments
            let files = $('#attachment')[0].files;
            for (let i = 0; i < files.length; i++) {
                formData.append('attachment[]', files[i]);
            }

            $.ajax({
                url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response === 'Done') {
                        $('#addCommentAgainstTask')[0].reset();
                        loadCommentAgainstTask();
                    }
                },
                error: function () {
                    alert('Error submitting comment');
                }
            });
        }

        function addReplyAgainstCommentTask(id) {
            const { taskId, m, baseUrl } = getCommonData();
            const url = `${baseUrl}/tms/project-tasks/project_task_reply_against_comment`;
            const replyBody = $(`#reply_body_${id}`).val();

            $.ajax({
                url,
                method: 'GET',
                data: {
                    m,
                    taskId,
                    replyBody,
                    id
                },
                success: function (response) {
                    if (response === 'Done') {
                        $(`#addReplyAgainstCommentTask_${id}`)[0].reset();
                        loadCommentAgainstTask();
                    }
                },
                error: function () {
                    alert('Error adding reply');
                }
            });
        }

        function loadCommentAgainstTask() {
            const { taskId, m, baseUrl } = getCommonData();
            const url = `${baseUrl}/tms/project-tasks/loadCommentAgainstTask`;

            $.ajax({
                url,
                method: 'GET',
                data: { m, taskId },
                success: function (response) {
                    $('#loadCommentAgainstTask').html(response);
                },
                error: function () {
                    alert('Error loading comments');
                }
            });
        }

        // Auto-refresh comments every 60 seconds
        setInterval(loadCommentAgainstTask, 60000);

        // Initial comment load
        loadCommentAgainstTask();
    //})();
    function taskStart(){
        var id = '{{$taskDetail->id}}';
        var m = $('#m').val();
        var baseUrl = $('#baseUrl').val();
        var remarks = $('#remarks').val();
        $.ajax({
            url: ''+baseUrl+'/tms/project-tasks/taskStart',
            method: 'GET',
            data: {
                m: m,
                id: id,
                remarks:remarks
            },
            error: function() {
                alert('error');
            },
            success: function(response) {
                if(response == 'Done'){
                    $('#showDetailModelOneParamerter').modal('toggle');
                    dataCall();
                }
            }
        });
    }
    function subTaskAdditionalActivity(param){
        var id = '{{$taskDetail->id}}';
        var taskWorkHistoryLastId = $('#taskWorkHistoryLastId').val();
        var remarks = $('#remarks').val();
        var m = $('#m').val();
        var baseUrl = $('#baseUrl').val();
        if(param == 1){
            var url = ''+baseUrl+'/tms/project-tasks/taskStop';
        }else if(param == 2){
            var url = ''+baseUrl+'/tms/project-tasks/taskSendTesting';
        }else if(param == 3){
            var url = ''+baseUrl+'/tms/project-tasks/taskCompleted';
        }else if(param == 4){
            var url = ''+baseUrl+'/tms/project-tasks/taskReassign';
        }
        $.ajax({
            url: url,
            method: 'GET',
            data: {
                m: m,
                id:id,
                remarks:remarks,
                taskWorkHistoryLastId:taskWorkHistoryLastId
            },
            error: function() {
                alert('error');
            },
            success: function(response) {
                if(response == 'Done'){
                    $('#showDetailModelOneParamerter').modal('toggle');
                    dataCall();
                }
            }
        });
    }
    function updateDetailAgainstTask(){
        var taskStartDate = new Date($('#task_start_date').val());
        var taskEndDate = new Date($('#task_end_date').val());
        var remarks = $('#remarks').val();
        var id = '{{$taskDetail->id}}';
        var m = $('#m').val();
        var baseUrl = $('#baseUrl').val();

        // Validate date range
        if (taskEndDate < taskStartDate) {
            alert('End date cannot be less than start date.');
            return; // Exit the function if dates are invalid
        }

        $.ajax({
            url: ''+baseUrl+'/tms/project-tasks/updateDetailAgainstTask',
            method: 'GET',
            data: {
                m: m,
                remarks:remarks,
                taskStartDate: $('#task_start_date').val(),
                taskEndDate: $('#task_end_date').val(),
                id: id
            },
            error: function() {
                alert('An error occurred while processing your request.');
            },
            success: function(response) {
                if(response == 'Done'){
                    $('#showDetailModelOneParamerter').modal('toggle');
                    dataCall();
                }
            }
        });
    }
</script>