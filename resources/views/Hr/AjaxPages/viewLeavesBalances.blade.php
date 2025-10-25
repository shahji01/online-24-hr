<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\TransferedLeaves;
$current_date = date('Y-m-d');
$count = 1;
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
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                             
                                    <table class="table table-bordered table-hover table-striped" id="exportList">
                                        
                                        @if($location_id == '2')

                                            <thead>
                                                <tr>
                                                    <th class="text-center">S No.</th>
                                                    <th class="text-center">Emp ID</th>
                                                    <th class="text-center">Emp Name</th>
                                                    <th class="text-center">year</th>
                                                    <th class="text-center">Jul</th>
                                                    <th class="text-center">Aug</th>
                                                    <th class="text-center">Sep</th>
                                                    <th class="text-center">Oct</th>
                                                    <th class="text-center">Nov</th>
                                                    <th class="text-center">Dec</th>
                                                    <th class="text-center">Jan</th>
                                                    <th class="text-center">Feb</th>
                                                    <th class="text-center">Mar</th>
                                                    <th class="text-center">Apr</th>
                                                    <th class="text-center">May</th>
                                                    <th class="text-center">Jun</th>
                                                    <th class="text-center">Used Casual</th>
                                                    <th class="text-center">Used Sick</th>
                                                    <th class="text-center">Used Annual</th>                                                
                                                    <th class="text-center">Remaining Casual</th>
                                                    <th class="text-center">Remaining Sick</th>
                                                    <th class="text-center">Remaining Annual</th>
                                                    <th class="text-center">Total Balance</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                           
                                                @foreach($employees as $value)

                                                    @if(array_key_exists($value->id,$emr_array) )
                                                    
                                                    
                                                        <tr>
                                                            <td class="text-center">{{ $count++ }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->emp_id }}</td>
                                                            <td>{{ $emr_array[$value->id]->emp_name }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->year }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->jul }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->aug }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->sep }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->oct }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->nov }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->dec }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->jan }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->feb }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->mar }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->apr }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->may }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->jun }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->used_casual }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->used_sick }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->used_annual }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->remaining_casual }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->remaining_sick }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->remaining_annual }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->remaining_annual + $emr_array[$value->id]->remaining_casual + $emr_array[$value->id]->remaining_sick }}</td>
                                                        </tr>
                                                    
                                                    @else
                                                    
                                                        <tr>
                                                            <td class="text-center">{{ $count++ }}</td>
                                                            <td class="text-center">{{ $value->emp_id }}</td>
                                                            <td>{{ $value->emp_name }}</td>
                                                            <td class="text-center">{{ $year }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 10 }}</td>
                                                            <td class="text-center">{{ 8 }}</td>
                                                            <td class="text-center">{{ 14 }}</td>
                                                            <td class="text-center">{{ 32 }}</td>
                                                        </tr>
                                                    @endif

                                                @endforeach
                                            
                                             </tbody>

                                        @else

                                            <thead>
                                                <tr>
                                                    <th class="text-center">S No.</th>
                                                    <th class="text-center">Emp ID</th>
                                                    <th class="text-center">Emp Name</th>
                                                    <th class="text-center">year</th>
                                                    <th class="text-center">Apr</th>
                                                    <th class="text-center">May</th>
                                                    <th class="text-center">Jun</th>
                                                    <th class="text-center">Jul</th>
                                                    <th class="text-center">Aug</th>
                                                    <th class="text-center">Sep</th>
                                                    <th class="text-center">Oct</th>
                                                    <th class="text-center">Nov</th>
                                                    <th class="text-center">Dec</th>
                                                    <th class="text-center">Jan</th>
                                                    <th class="text-center">Feb</th>
                                                    <th class="text-center">Mar</th>
                                                    <th class="text-center">Used Casual</th>
                                                    <th class="text-center">Used Sick</th>
                                                    <th class="text-center">Used Annual</th>                                                
                                                    <th class="text-center">Remaining Casual</th>
                                                    <th class="text-center">Remaining Sick</th>
                                                    <th class="text-center">Remaining Annual</th>
                                                    <th class="text-center">Total Balance</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                           
                                                @foreach($employees as $value)

                                                    @if(array_key_exists($value->id,$emr_array) )
                                                    
                                                    
                                                        <tr>
                                                            <td class="text-center">{{ $count++ }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->emp_id }}</td>
                                                            <td>{{ $emr_array[$value->id]->emp_name }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->year }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->apr }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->may }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->jun }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->jul }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->aug }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->sep }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->oct }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->nov }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->dec }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->jan }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->feb }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->mar }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->used_casual }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->used_sick }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->used_annual }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->remaining_casual }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->remaining_sick }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->remaining_annual }}</td>
                                                            <td class="text-center">{{ $emr_array[$value->id]->remaining_annual + $emr_array[$value->id]->remaining_casual + $emr_array[$value->id]->remaining_sick }}</td>
                                                        </tr>
                                                    
                                                    @else
                                                    
                                                        <tr>
                                                            <td class="text-center">{{ $count++ }}</td>
                                                            <td class="text-center">{{ $value->emp_id }}</td>
                                                            <td>{{ $value->emp_name }}</td>
                                                            <td class="text-center">{{ $year }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 0 }}</td>
                                                            <td class="text-center">{{ 10 }}</td>
                                                            <td class="text-center">{{ 8 }}</td>
                                                            <td class="text-center">{{ 14 }}</td>
                                                            <td class="text-center">{{ 32 }}</td>
                                                        </tr>
                                                    @endif

                                                @endforeach
                                            
                                             </tbody>

                                        @endif    
                                    </table>
                                      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>