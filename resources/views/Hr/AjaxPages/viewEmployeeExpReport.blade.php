<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeePromotion;
use App\Models\EmployeeTransfer;
use App\Models\SubDepartment;
use App\Models\Department;
$location_id = '';

?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="HrReport">
                        @if($employee_detail->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Emp Name</th>
                            <th class="text-center">Dep/Sub Dep</th>
                            <th class="text-center">Joining Date</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Qualification</th>
                            <th class="text-center">Last Company</th>
                            <th class="text-center">Year of Exp</th>
                            <th class="text-center">Duration</th>
                            <th class="text-center">Status</th>

                            </thead>
                            <tbody>
                            <?php $counter = 1;?>
                            @foreach($employee_detail->get() as $key => $y)
                                <?php

                                $department_id = Department::where('id','=',$y->emp_department_id)->first();
                                $SubDepartment = SubDepartment::where('id','=',$y->emp_sub_department_id)->first();

                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                //                                $EmployeeTransfer = EmployeeTransfer::where([['emp_id', '=', $y->emp_id]])->orderBy('location_id','desc')->first();
//                                if(count($EmployeeTransfer) != '0'){
//                                    $location_id = $EmployeeTransfer->location_id;
//                                }
//                                else{
//                                    $location_id = $y->branch_id;
//                                }

                                $last_qualification  = \App\Models\EmployeeEducationalData::select('degree_type')->where([['emp_id','=',$y->emp_id],['status','=',1]])
                                    ->orderBy('id','desc')
                                    ->offset(0)->limit(1)->first();

                                $last_work_exp  = \App\Models\EmployeeWorkExperience::where([['emp_id','=',$y->emp_id],['status','=',1]])
                                    ->orderBy('id','desc')
                                    ->offset(0)->limit(1)
                                    ->first();




                                //$current_salary = $y->emp_salary;
//                                if(EmployeePromotion::where([['emp_id', '=', $y->emp_id]])->exists()):
//                                    $employee_promotion = EmployeePromotion::where([['emp_id', '=', $y->emp_id]])->orderBy('id', 'desc')->first();
//                                    $current_salary = $employee_promotion->salary;
//                                endif;
                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center ">{{ $y->emp_id}}</td>
                                    <td class="text-center">{{ $y->emp_name}}</td>
                                    <td class="text-center">

									{{ $department_id['department_name'] ?? "--"}} /
									<small>{{ $SubDepartment['sub_department_name' ?? "--"] }}</small>
									</td>
                                    <td class="text-center">{{ HrHelper::date_format($y->emp_joining_date)}}</td>
                                    <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$y->designation_id)}}</td>
                                    <td class="text-center">

                                      @if($last_qualification['degree_type'] != "")
                                           {{ HrHelper::getMasterTableValueById(Input::get('m'),'degree_type','degree_type_name',$last_qualification['degree_type']) }}
                                        @else
                                          <span>--</span>
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if($last_work_exp['employeer_name']!=""):
                                            echo $last_work_exp['employeer_name'];
                                        else:
                                            echo "-";
                                        endif;
                                     ?>
                                    </td>
                                    <td class="text-center">
                                      <?php

                                            if($last_work_exp['started'] != '' && $last_work_exp['ended'] != ''):

                                                $diff = abs(strtotime($last_work_exp['ended']) - strtotime($last_work_exp['started']));
                                                $years = floor($diff / (365*60*60*24));
                                                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                                                printf("%d years, %d months, %d days\n", $years, $months, $days);

                                            else:
                                                echo "-";
                                            endif;


//                                        ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        $diff = abs(strtotime(date('Y-m-d')) - strtotime($y->emp_joining_date));
                                        $years = floor($diff / (365*60*60*24));
                                        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                                        printf("%d years, %d months, %d days\n", $years, $months, $days);
                                        ?>
                                    </td>
                                    <td class="text-center">{{HrHelper::getStatusLabel($y->status)}}</td>
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