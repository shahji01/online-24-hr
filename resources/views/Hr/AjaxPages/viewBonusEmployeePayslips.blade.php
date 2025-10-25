<?php

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\EmailedPayslip;
use App\Models\ApiEmployeeShifts;
use App\Models\Employee;


?>

<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="panel">
    <div class="panel-body" id="PrintLeaveApplicationRequestList">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover">
                        <thead>
                        <th class="text-center">S.no</th>
                        <th class="text-center">Emp ID</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Emp Email</th>
                        <th class="text-center">View</th>
                        <th class="text-center">Download</th>
                        <th class="text-center">
                            Check All<br>  <input id="check_all" checked type="checkbox">
                        </th>
                        </thead>
                        <tbody>
                        <?php $email =1;  $counter = 1; $emailCount = 0; ?>
                        @if($payslipBonusData->count() > 0)
                            @foreach($payslipBonusData->get() as $value)
                                <?php
                                     CommonHelper::companyDatabaseConnection(Input::get('m'));
                                     $email = Employee::select('official_email','emp_name')->where('id',$value->employee_id);
                                     CommonHelper::reconnectMasterDatabase();
                                ?>
                                <tr>
                                    <td class="text-center">{{$counter++}}</td>
                                    <td class="text-center">{{$value->emp_id}}</td>
                                    <td class="text-center">
                                         <?= $email->value('emp_name') ?>
                                    </td>

                                    <td class="text-center">
                                        @if($email->value('official_email') != '')
                                            {{$email->value('official_email') }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a target="_blank" href="{{ url('/') }}/hr/viewBonusPayslipPdf/{{ Input::get('m') }}/{{ $value->bonus_month }}/{{ $value->bonus_year }}/{{ $value->employee_id }}">
                                            View
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a target="_blank" href="{{ url('/') }}/hr/downloadBonusPayslipPdf/{{ Input::get('m') }}/{{ $value->bonus_month }}/{{ $value->bonus_year }}/{{ $value->employee_id }}">
                                            <img src="{{ url('/') }}/assets/images/pdf.png" height="20" width="20">
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if($email->value('official_email') != '')
                                            <input type="checkbox" checked class="ads_Checkbox " name="check_list[]" value="{{ $email->value('official_email').'<>'.$value->employee_id }}">
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" style="color: red" colspan="8">Data not found !</td>
                            </tr>
                        @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
            <br>
        <div class="text-right">
            <button type="submit" class="btn btn-sm btn-success">Submit</button>
        </div>
    </div>
</div>
<script>

    $(function(){
        $("#check_all").click(function(){

            if($("#check_all").prop("checked") == true)
            {
                $(".ads_Checkbox").prop("checked",true);
            }
            else
            {
                $(".ads_Checkbox").prop("checked",false);
            }


        });
    });
</script>