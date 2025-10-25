<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="table-responsive">
    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="OvertimeDetailList">
        <thead>
        <th class="text-center">S No.</th>
        <th class="text-center">Emd Id</th>
        <th class="text-center">Emp Name</th>
        <th class="text-center">DOB</th>
        </thead>
        <tbody>
        <?php $counter = 1;?>
        @if(! empty($upcoming_birthdays_detail))
            @foreach($upcoming_birthdays_detail as $value)
                <tr>
                    <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                    <td class="text-center">{{$value->emp_id}}</td>
                    <td class="text-center">
                        <a target="_blank" class="delete-modal btn" href="<?=url('')?>/hr/editEmployeeDetailForm/{{$value->id}}/{{Input::get('m')}}?m={{Input::get('m')}}">
                            {{$value->emp_name}}
                        </a>
                    </td>
                    <td class="text-center">{{HrHelper::date_format($value->date_of_birth)}}</td>

                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center" style="color:red;">Record Not Found !</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

