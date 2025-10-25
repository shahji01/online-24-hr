<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="OvertimeDetailList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emp ID</th>
                    <th class="text-center">Emp Name</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1; ?>
                    @if(!empty($warningLetters))
                        @foreach($warningLetters as $value)
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{ $value['emp_id'] }}</td>
                                <td class="text-center">{{ $value['username'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-danger">
                            <td colspan="5" class="text-center" style="color:red;"><b>Record Not Found !</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>