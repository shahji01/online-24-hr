<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

?>
<table class="table table-bordered sf-table-list" id="EOBIList">
    <thead>
    <th class="text-center col-sm-1">S.No</th>
    <th class="text-center">Holiday Name</th>
    <th class="text-center">Holiday Date</th>
    <th class="text-center">Holiday Month-Year</th>
    <th class="text-center">Created By</th>
    <th class="text-center">Status</th>
    <th class="text-center hidden-print">Action</th>
    </thead>
    <tbody>
    <?php $counter = 1;?>
    @foreach($holidays as $key => $value)
        <tr>
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo $value->holiday_name;?></td>
            <td class="text-center"><?php echo $value->holiday_date;?></td>
            <td class="text-center"><?php echo $value->month."-".$value->year;?></td>
            <td class="text-center"><?php echo $value->username ?></td>
            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
            <td class="text-center hidden-print">
                <button class="edit-modal btn btn-xs btn-info" onclick="showDetailModelTwoParamerter('hr/editHolidaysDetailForm','<?=$value->id?>','Edit Holiday Detail','<?=$m?>')">
                    <span class="glyphicon glyphicon-edit"></span>
                </button>
                @if($value->status == 1)
                    <button class="delete-modal btn btn-xs btn-danger" onclick="deleteRowCompanyRecords('<?=$m?>','<?php echo $value->id ?>','holidays')">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                @elseif($value->status == 2)
                    <button class="delete-modal btn btn-xs btn-primary" onclick="repostOneTableRecords('<?=$m?>','<?php echo $value->id ?>','holidays')">
                        <span class="glyphicon glyphicon-refresh"></span>
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>