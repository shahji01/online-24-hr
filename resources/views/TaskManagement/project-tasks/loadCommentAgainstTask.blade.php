@php
    use App\Helpers\CommonHelper;
    $m = $_GET['m'];
@endphp
<div class="row">
    @foreach($getCommentDetail as $gcdRow)
        @php
            CommonHelper::companyDatabaseConnection($m);
            $attachments = DB::table('task_comment_attachments')->where('task_comment_id',$gcdRow->comment_id)->get();
            CommonHelper::reconnectMasterDatabase();
        @endphp
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-success">
                <strong>{{$gcdRow->comment_emp_name ?? $gcdRow->comment_customer_name}}</strong>
                <p>{{$gcdRow->comment_body}}</p>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @foreach($attachments as $aRow)
                            <a href="{{ asset('storage/app/public/' . $aRow->file_path) }}" class="btn btn-xs btn-primary" target="_blank">
                                {{ $aRow->original_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>    
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <strong>{{CommonHelper::changeDateFormat($gcdRow->comment_created_date)}}</strong>
        </div>

        @if($gcdRow->comment_parent_id == 0 && $gcdRow->reply_id == '')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">&nbsp;</div>
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 alert alert-secondary">
                        <h4>Add Reply</h4>
                        <form id="addReplyAgainstCommentTask_{{$gcdRow->comment_id}}">
                            <div class="form-group">
                                <textarea class="form-control" name="reply_body_{{$gcdRow->comment_id}}" id="reply_body_{{$gcdRow->comment_id}}"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="button" onclick="addReplyAgainstCommentTask('{{$gcdRow->comment_id}}')" class="btn btn-primary" value="Add Reply" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">&nbsp;</div>
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                        <div class="row alert alert-secondary">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong>{{$gcdRow->reply_emp_name ?? $gcdRow->reply_customer_name}}</strong>
                                <p>{{$gcdRow->reply_body}}</p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <strong>{{CommonHelper::changeDateFormat($gcdRow->reply_created_date)}}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <hr />
    @endforeach
</div>