<?php

use App\Helpers\CommonHelper;
use Carbon\Carbon;

$totalBonus = 0;
$m=\Illuminate\Support\Facades\Input::get('m');

?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="bonusReport">
                        @if($bonus->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Emp Name</th>
                            <th class="text-center">Bonus Pay</th>
                            <th class="text-center">Bonus Amount</th>
                            <th class="text-center">Tax Amount</th>
                            <th class="text-center">Total Net</th>

                            <th class="text-center">Action</th>
                            </thead>
                            <tbody>

                            <?php $counter = 1;?>
                            @foreach($bonus->get() as $key => $y)
                                <?php
                                $date = Carbon::createFromFormat('m/Y',$y->bonus_month.'/'.$y->bonus_year)->subMonth();
                                $newDate = $date->format('m/Y');
                                $previous_date = explode('/',$newDate);
                                ?>
                                @if($y->bonus_pay == 1)
                                @php
                                    $totalBonus+=$y->bonus_amount;
                                @endphp
                                @else
                                    @php
                                    $totalBonus+=$y->total_net;
                                    @endphp
                                @endif
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{$y->emp_id}}</td>
                                    <td class="text-center">{{$y->emp_name}}</td>

                                    <td class="text-right">@if($y->bonus_pay == 1) With Salary @else Separately @endif</td>
                                    <td class="text-right">{{number_format($y->bonus_amount)}}</td>
                                    <td class="text-right">{{number_format($y->bonus_tax) }}</td>
                                    <td class="text-right">
                                        @if($y->bonus_pay == 1)
                                            {{number_format($y->bonus_amount) }}
                                        @else
                                            {{number_format($y->total_net) }}
                                        @endif
                                    </td>
                                    <td class="text-center" style="cursor: pointer;"><span onclick="deleteEmployeeBonus('<?php echo $y->id ?>','<?php echo $m ?>')" class="badge badge-danger">Remove</span></td>
                                </tr>
                            @endforeach
                            <tr>

                                <td class="text-right" colspan="6"><strong>Total</strong></td>
                                <td class="text-right"><strong>{{number_format($totalBonus)}}</strong></td>
                            </tr>
                            @else
                                <tr><td class="text-center" style="color:red;font-weight: bold;" colspan="8">Record Not Found !</td></tr>
                            @endif
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function deleteEmployeeBonus(id,m){
        if(confirm("Do you want to delete this record ?") == true){
            $.ajax({
                url: '<?php echo url('/')?>/cdOne/deleteEmployeesBonus',
                type: "GET",
                data: {'request_type':'delete',m:m,id:id},
                success:function(data) {
                    location.reload();
                }
            });
        }
        else{
            return false;
        }
    }
</script>