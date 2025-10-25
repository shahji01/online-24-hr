<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = '1';
?>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table style="table-layout: fixed;" class="table table-bordered sf-table-list table-hover">
                                            <thead>
                                            <th class="text-center">Policies / Forms</th>
                                            <th class="text-center">File Name</th>
                                            </thead>

                                            <tbody>
                                            <td class="text-center">
                                                @if($policies->value('policy_id') == 1)
                                                    Zero Tolerance policy
                                                @elseif($policies->value('policy_id') == 2)
                                                    Attendance & Leave Policy
                                                @elseif($policies->value('policy_id') == 3)
                                                    Separation Policy
                                                @elseif($policies->value('policy_id') == 4)
                                                    Loan policy
                                                @elseif($policies->value('policy_id') == 5)
                                                    PF Policy
                                                @elseif($policies->value('policy_id') == 6)
                                                    Advance Salary Policy
                                                @elseif($policies->value('policy_id') == 7)
                                                    Exit Interview Form
                                                @elseif($policies->value('policy_id') == 8)
                                                    End of Service Clearance Form
                                                @elseif($policies->value('policy_id') == 9)
                                                    Probation Form
                                                @elseif($policies->value('policy_id') == 10)
                                                    Reimbursement Form
                                                @elseif($policies->value('policy_id') == 11)
                                                    Loan Form
                                                @elseif($policies->value('policy_id') == 12)
                                                    PF Form
                                                @elseif($policies->value('policy_id') == 13)
                                                    Advance Salary Form
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $policies->value('file_name') }}</td>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="LeaveTypeList">
                                            <tbody>
                                            @foreach ($policies->get() as $value)
                                                <?php
                                                $url1 = url('/').Storage::url($value->file_path); ?>
                                                <tr class="remove_row_{{$value->id}}">
                                                    <td class="text-center" colspan="5">
                                                        <?php $url = url('/').Storage::url($value->file_path);?>
                                                        @if($value->file_type == 'doc' || $value->file_type == 'docx')
                                                            <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url={{$url}}&embedded=true"></iframe>
                                                        @elseif($value->file_type == 'pdf')
                                                            <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url={{$url}}" style="width: 100%" height="789">
                                                                @elseif($value->file_type == 'jpeg' || $value->file_type == 'jpg' || $value->file_type == 'png' || $value->file_type == 'PNG' || $value->document_extension == 'gif')
                                                                    <img style="width: 100%;" src="{{$url}}">
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
