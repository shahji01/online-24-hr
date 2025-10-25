<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');
?>

<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <br>
            <div class="table-responsive">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="OvertimeDetailList">
                    <thead>
                        <th class="text-center">S No.</th>
                        <th class="text-center">Emd Id</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Date</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if($settlementTermination1->count() > 0)
                        @foreach($settlementTermination1->get() as $key => $value)
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{$value->emp_id}}</td>
                                {{--<td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value->emp_id, 'emp_id')}}</td>--}}
                                <td class="text-center">{{ HrHelper::date_format($value->settlement_date)}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4" style="color:red;"><b>Record Not Found !</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="text-center">
                <h3><u>Termination Letter 2</u></h3>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="OvertimeDetailList">
                    <thead>
                        <th class="text-center">S No.</th>
                        <th class="text-center">Emp Id</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Date</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if($settlementTermination2->count() > 0)
                        @foreach($settlementTermination2->get() as $key => $value)
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{$value->emp_id}}</td>
                                {{--<td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value->emp_id, 'emp_id')}}</td>--}}
                                <td class="text-center">{{ HrHelper::date_format($value->settlement_date)}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4" style="color:red;"><b>Record Not Found !</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="text-center">
                <h3><u>Contract Conclusion</u></h3>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="OvertimeDetailList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emp Id</th>
                    <th class="text-center">Emp Name</th>
                    <th class="text-center">Date</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if($settlementContract->count() > 0)
                        @foreach($settlementContract->get() as $key => $value)
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{$value->emp_id}}</td>
                                {{--<td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value->emp_id, 'emp_id')}}</td>--}}
                                <td class="text-center">{{ HrHelper::date_format($value->settlement_date)}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4" style="color:red;"><b>Record Not Found !</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
