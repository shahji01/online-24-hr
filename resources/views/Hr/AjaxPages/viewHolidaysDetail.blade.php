<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
$counter = 1;
?>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Holiday</th>
                            <th class="text-center">Holiday Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td>{{ $holidays->holiday_name }}</td>
                            <td class="text-center">{{ HrHelper::date_format($holidays->holiday_date) }}</td>
                            <td class="text-center hidden-print">
                                @if(in_array('delete', $operation_rights2))
                                    @if($holidays->status == 1)
                                        <button onclick="deleteRowCompanyRecords('{{ $m }}','{{ $holidays->id }}','holidays')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>