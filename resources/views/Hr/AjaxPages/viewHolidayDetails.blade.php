<?php
use App\Helpers\HrHelper;

 ?>
<div class="well">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="text-center"><h3>Holidays Dates</h3></div>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 table-bordered table-striped">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">day</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(!empty($totalHolidays)):
                                    $counter2 = 1;?>
                                    @foreach($totalHolidays as $value1)

                                        <tr>
                                            <td class="text-center">{{ $counter2++ }}</td>
                                            <td class="text-center">{{ $emp_id }}</td>
                                            <td class="text-center" style="background-color: #FFC0CB">{{ HrHelper::date_format($value1['holiday_date']) }}</td>
                                            <td class="text-center" style="background-color: #FFC0CB">{{ date("D", strtotime($value1['holiday_date'])) }}</td>
                                        </tr>
                                    @endforeach
                                    <?php else: ?>

                                    <tr><td colspan="6" style="color:red" class="text-center">Record Not Found !</td></tr>

                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                   </div>
                  </div>
                 </div>
                </div>
              </div>      