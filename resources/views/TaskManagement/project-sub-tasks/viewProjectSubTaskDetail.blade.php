@php
    $accType = Auth::user()->acc_type;
    $remarksJson = $taskSubDetail->remarks;
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
    @if($taskSubDetail->start_date == null || $taskSubDetail->end_date == null)
        <div class="row" id="updateDetailSection">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks" id="remarks">-</textarea>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Sub Task Start Date</label>
                        <input type="date" name="sub_task_start_date" id="sub_task_start_date" class="form-control" value="{{date('Y-m-d')}}" />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>&nbsp;</label>
                        <input type="text" value="To" class="form-control" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Sub Task End Date</label>
                        <input type="date" name="sub_task_end_date" id="sub_task_end_date" class="form-control" value="{{date('Y-m-d')}}" />
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                        <label>&nbsp;</label>
                        <input type="button" class="btn btn-success btn-xs" onclick="updateDetailAgainstSubTask()" value="Update Detail" />
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            @if($taskSubDetail->sub_task_status != 2)
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks" id="remarks">-</textarea>
            </div>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                @if($taskSubDetail->sub_task_status != 2)
                    @if(empty($subTaskWorkHistoryLast))
                        <input type="button" class="btn btn-xs btn-success" onclick="subTaskStart()" value="Start Timer"/>
                    @elseif($taskSubDetail->sub_task_status == 6)
                        <input type="button" class="btn btn-xs btn-success" onclick="subTaskStart()" value="Start Timer"/>
                    @elseif($taskSubDetail->sub_task_status == 5)
                        <input type="hidden" name="subTaskWorkHistoryLastId" id="subTaskWorkHistoryLastId" value="{{$subTaskWorkHistoryLast->id}}" />
                        @if($taskSubDetail->user_id == Auth::user()->id)
                            <input type="button" class="btn btn-xs btn-danger" onclick="subTaskAdditionalActivity(3)" value="Complete Task"/>
                            <input type="button" class="btn btn-xs btn-danger" onclick="subTaskAdditionalActivity(4)" value="Reassign Task"/>
                        @endif
                    @elseif($subTaskWorkHistoryLast->end_date == '0000-00-00')
                        <input type="hidden" name="subTaskWorkHistoryLastId" id="subTaskWorkHistoryLastId" value="{{$subTaskWorkHistoryLast->id}}" />
                        <input type="button" class="btn btn-xs btn-danger" onclick="subTaskAdditionalActivity(1)" value="Stop Timer"/>
                        <input type="button" class="btn btn-xs btn-danger" onclick="subTaskAdditionalActivity(2)" value="Send Testing"/>
                    @else
                        <input type="button" class="btn btn-xs btn-success" onclick="subTaskStart()" value="Start Timer"/>
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
                                <td>{{$taskSubDetail->customer_name}}</td>
                            </tr>
                            <tr>
                                <th>Project Name</th>
                                <td>{{$taskSubDetail->project_name}}</td>
                            </tr>
                            <tr>
                                <th>Project Type</th>
                                <td>@if ($taskSubDetail->project_type == 1) Local @else International @endif</td>
                            </tr>
                            <tr>
                                <th>Assign Project Employee</th>
                                <td>{{$taskSubDetail->emp_name_one}}</td>
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
                                <th>Task Name</th>
                                <td>{{$taskSubDetail->task_name}}</td>
                            </tr>
                            <tr>
                                <th>Sub Task Name</th>
                                <td>{{$taskSubDetail->sub_task_name}}</td>
                            </tr>
                            <tr>
                                <th>Sub Task Status</th>
                                <td>@if ($taskSubDetail->sub_task_status == 1) Pending @elseif ($taskSubDetail->sub_task_status == 2) Completed @elseif ($taskSubDetail->sub_task_status == 3) Hold  @else Inprogress @endif</td>
                            </tr>
                            <tr>
                                <th>Assign Employee</th>
                                <td>{{$taskSubDetail->emp_name_two}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!empty($remarksArray) && count($remarksArray) > 0)
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Activity Remarks</label>
        @foreach($remarksArray as $key => $value)
            <div class="row mb-2">
                <div class="col-sm-4 font-weight-bold">{{ $key }}</div>
                <div class="col-sm-8">{{ $value }}</div>
            </div>
        @endforeach
    </div>
    @endif
</div>
<div class="row">
    <div class="col-lg-12" id="loadCommentAgainstSubTask">
        <!-- Comments will load here -->
    </div>

    <div class="col-lg-12">
        <h4>Add Comment</h4>
        <form id="addCommentForm" enctype="multipart/form-data">
            <div class="form-group">
                <textarea class="form-control" name="comment_body" id="comment_body" placeholder="Write a comment..."></textarea>
            </div>
            <div class="form-group">
                <label>Attach File/Image:</label>
                <input type="file" name="attachment[]" id="attachment" class="form-control" accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx" multiple>
            </div>
            <div class="form-group">
                <button type="button" onclick="addComment()" class="btn btn-primary">Add Comment</button>
            </div>
        </form>
    </div>
</div>
<script>
    //(() => {
        const getCommonData = () => ({
            taskId: '{{$taskSubDetail->task_id}}',
            subTaskId: '{{$taskSubDetail->id}}',
            m: $('#m').val(),
            baseUrl: $('#baseUrl').val()
        });

        function addComment() {
            const { taskId, subTaskId, m, baseUrl } = getCommonData();
            const commentBody = $('#comment_body').val().trim();

            if (!commentBody) {
                alert("Comment cannot be empty.");
                return;
            }

            const form = $('#addCommentForm')[0];
            const formData = new FormData(form);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('taskId', taskId);
            formData.append('subTaskId', subTaskId);
            formData.append('m', m);

            $.ajax({
                url: `${baseUrl}/tms/project-sub-task/project_sub_task_comment`,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                error: () => alert('An error occurred while adding the comment.'),
                success: response => {
                    if (response === 'Done') {
                        form.reset();
                        loadComments();
                    } else {
                        alert('Failed to add comment.');
                    }
                }
            });
        }

        function addReply(commentId) {
            const { taskId, subTaskId, m, baseUrl } = getCommonData();
            const replyBody = $(`#reply_body_${commentId}`).val().trim();

            if (!replyBody) {
                alert("Reply cannot be empty.");
                return;
            }

            $.ajax({
                url: `${baseUrl}/tms/project-sub-task/project_sub_task_reply_against_comment`,
                method: 'GET',
                data: {
                    m,
                    taskId,
                    subTaskId,
                    replyBody,
                    id: commentId
                },
                error: () => alert('An error occurred while replying.'),
                success: response => {
                    if (response === 'Done') {
                        $(`#addReplyAgainstCommentSubTask_${commentId}`)[0].reset();
                        loadComments();
                    } else {
                        alert('Failed to post reply.');
                    }
                }
            });
        }

        function loadComments() {
            const { taskId, subTaskId, m, baseUrl } = getCommonData();

            $.ajax({
                url: `${baseUrl}/tms/project-sub-task/loadCommentAgainstSubTask`,
                method: 'GET',
                data: { m, taskId, subTaskId },
                error: () => alert('Failed to load comments.'),
                success: response => {
                    $('#loadCommentAgainstSubTask').html(response);
                }
            });
        }

        // Initial load + polling
        $(document).ready(() => {
            setInterval(loadComments, 60000); // Every 60 seconds
        });
        loadComments();
    //})();

    function subTaskStart(){
        var taskId = '{{$taskSubDetail->task_id}}';
        var subTaskId = '{{$taskSubDetail->id}}';
        var remarks = $('#remarks').val();
        var m = $('#m').val();
        var baseUrl = $('#baseUrl').val();
        $.ajax({
            url: ''+baseUrl+'/tms/project-sub-task/subTaskStart',
            method: 'GET',
            data: {
                m: m,
                taskId: taskId,
                remarks:remarks,
                subTaskId:subTaskId
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
        var taskId = '{{$taskSubDetail->task_id}}';
        var subTaskId = '{{$taskSubDetail->id}}';
        var subTaskWorkHistoryLastId = $('#subTaskWorkHistoryLastId').val();
        var remarks = $('#remarks').val();
        var m = $('#m').val();
        var baseUrl = $('#baseUrl').val();
        if(param == 1){
            var url = ''+baseUrl+'/tms/project-sub-task/subTaskStop';
        }else if(param == 2){
            var url = ''+baseUrl+'/tms/project-sub-task/subTaskSendTesting';
        }else if(param == 3){
            var url = ''+baseUrl+'/tms/project-sub-task/subTaskCompleted';
        }else if(param == 4){
            var url = ''+baseUrl+'/tms/project-sub-task/subTaskReassign';
        }
        $.ajax({
            url: url,
            method: 'GET',
            data: {
                m: m,
                taskId: taskId,
                subTaskId:subTaskId,
                remarks:remarks,
                subTaskWorkHistoryLastId:subTaskWorkHistoryLastId
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
    function updateDetailAgainstSubTask(){
        var subTaskStartDate = new Date($('#sub_task_start_date').val());
        var subTaskEndDate = new Date($('#sub_task_end_date').val());
        var id = '{{$taskSubDetail->id}}';
        var remarks = $('#remarks').val();
        var m = $('#m').val();
        var baseUrl = $('#baseUrl').val();

        // Validate date range
        if (subTaskEndDate < subTaskStartDate) {
            alert('End date cannot be less than start date.');
            return; // Exit the function if dates are invalid
        }

        $.ajax({
            url: ''+baseUrl+'/tms/project-sub-task/updateDetailAgainstSubTask',
            method: 'GET',
            data: {
                m: m,
                subTaskStartDate: $('#sub_task_start_date').val(),
                subTaskEndDate: $('#sub_task_end_date').val(),
                remarks:remarks,
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