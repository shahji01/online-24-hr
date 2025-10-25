<?php
$accType = Auth::user()->acc_type;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <div class="panel">
                <div class="panel-body" id="PrintTaxesList">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="TaxesList">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Slab Name</th>
                                    <th class="text-center">Salary Range From</th>
                                    <th class="text-center">Salary Range To</th>
                                    <th class="text-center">Percentage</th>
                                    <th class="text-center">Amount</th>
                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;?>
                                    @foreach($tax_slabs as $key => $value)
                                        <tr>
                                            <td class="text-center">{{ $counter++ }}</td>
                                            <td class="text-center">{{ $value->slab_name }}</td>
                                            <td class="text-center">{{ $value->salary_range_from }}</td>
                                            <td class="text-center">{{ $value->salary_range_to }}</td>
                                            <td class="text-center">{{ $value->tax_percent }}</td>
                                            <td class="text-center">{{ $value->tax_amount }}</td>

                                        </tr>
                                    @endforeach
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
