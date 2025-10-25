<?php
use \App\Models\EmployeeTransfer;
use \App\Models\Employee;
use \App\Models\HrWarningLetter;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="HrReport">
                        @if($warning_letter->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMR NO</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Father Name</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Verbal Warning</th>
                            <th class="text-center">First Warning Dated</th>
                            <th class="text-center">Second Warning Dated</th>
                            <th class="text-center">Final Action</th>
                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($warning_letter->distinct()->get() as $key => $y)
                                <?php
                                $second_date = null;
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $employee_detail = Employee::where([['emr_no', '=', $y->emr_no]])->select('id','emp_name','emp_father_name','designation_id')->first();

                                if(HrWarningLetter::where([['emr_no', '=', $y->emr_no]])->orderBy('id', 'asc')->skip(1)->take(1)->exists()):
                                    $second_date = HrWarningLetter::where([['emr_no', '=', $y->emr_no]])->orderBy('id', 'asc')->skip(1)->take(1)->first();

                                endif;

                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center">{{ $y->emr_no}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_name}}</td>
                                    <td class="text-center">{{ $employee_detail->emp_father_name}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee_detail->designation_id)}}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">{{ HrHelper::date_format($y->date) }}</td>
                                    <td class="text-center"> @if($second_date == null) -- @else {{ HrHelper::date_format($second_date['date']) }} @endif </td>
                                    <td class="text-center"></td>
                                </tr>
                            @endforeach
                            @else
                                <tr><td class="text-center" style="color:red;font-weight: bold;" colspan="14">Record Not Found !</td></tr>
                            @endif
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>