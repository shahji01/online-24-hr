<?php
$data='';
$data.='
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list">
                    <thead>
                    <th class="text-center col-sm-1">Tax Name</th>
                    <td class="text-center col-sm-1">'.$tax->tax_name.'</td>
                    </thead>
                     <thead>
                     <th class="text-center col-sm-1">Tax ('.$tax->tax_mode.')</th>
                     <td class="text-center col-sm-1">'.$tax->tax_percent.'</td>
                    </thead>
                    <thead>
                     <th class="text-center col-sm-1">Created on</th>
                     <td class="text-center col-sm-1">'.$tax->time.'</td>
                    </thead>
                </table>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list">
                    <thead>
                    <th class="text-center col-sm-1">Tax Apply Salary Range</th>
                    <td class="text-center col-sm-1">'.$tax->salary_range_from.'--'.$tax->salary_range_to.'</td>
                    </thead>
                     <thead>
                     <th class="text-center col-sm-1">Tax Month & Year</th>
                     <td class="text-center col-sm-1">'.$tax->tax_month_year.'</td>
                    </thead>
                    <thead>
                     <th class="text-center col-sm-1">Created at</th>
                     <td class="text-center col-sm-1">'.$tax->date.'</td>
                     </thead>
                </table>
            </div>
          </div>
      </div>
</div>';


echo json_encode(array('data'=>$data));



?>
