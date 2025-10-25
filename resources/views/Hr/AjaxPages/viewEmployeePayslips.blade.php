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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printList">
                                <table class="table table-bordered table-striped table-hover" id="exportList">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">View</th>
                                    <th class="text-center">Download</th>
                                    <th class="text-center">
                                        Check All<br>  <input id="check_all" checked type="checkbox">
                                    </th>
                                    </thead>
                                    <tbody>
                                    <?php $email = 1;  $counter = 1; $emailCount = 0; ?>
                                    @if($payslipData->count() > 0)
                                        @foreach($payslipData->get() as $value)
                                            @if(array_key_exists($value->employee_id, $employees))
                                                <?php
                                                $email = $employees[$value->employee_id]->official_email;
                                                $emp_name = $employees[$value->employee_id]->emp_name;
                                                ?>
                                            @endif
                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ $value->emp_id }}</td>
                                                <td>{{ $emp_name }}</td>
                                                <td> @if($email != '' && $email != '-') {{ $email }} @endif</td>
                                                <td class="text-center">
                                                    <a target="_blank" href="{{ url('/') }}/hr/viewPayslipPdf/{{ $company_id }}/{{ $value->month }}/{{ $value->year }}/{{ $value->employee_id }}">
                                                        View
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a target="_blank" href="{{ url('/') }}/hr/downloadPayslipPdf/{{ $company_id }}/{{ $value->month }}/{{ $value->year }}/{{ $value->employee_id }}">
                                                        <img src="{{ url('/') }}/assets/images/pdf.png" height="20" width="20">
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    @if($email != '' && $email != '-')
                                                        <input type="checkbox" checked class="ads_Checkbox" name="check_list[]" value="{{ $email.'<>'.$value->employee_id }}">
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
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <button type="submit" class="btn btn-sm btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(function(){
        $("#check_all").click(function(){

            if($("#check_all").prop("checked") == true) {
                $(".ads_Checkbox").prop("checked",true);
            }
            else {
                $(".ads_Checkbox").prop("checked",false);
            }
        });
    });
</script>