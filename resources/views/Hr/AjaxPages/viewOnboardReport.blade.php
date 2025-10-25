<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="HrReport">
                        @if($employee_details->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Father Name</th>
                            <th class="text-center">Designation</th> 
                            <th class="text-center">CNIC</th>
                            <th class="text-center">Joining Date</th>
                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($employee_details->get() as $key => $y)
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center">{{ $y->emp_id}}</td>
                                    <td class="text-center">{{ $y->emp_name}}</td>
                                    <td class="text-center">{{ $y->emp_father_name}}</td>
									 <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$y->designation_id)}}</td>
                                    <td class="text-center">{{ $y->emp_cnic }}</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->emp_joining_date) }}</td>

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