<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
//print_r($holidays); die();
?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>


@extends('layouts.default')
@section('content')


    <div class="page-wrapper">

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Holidays List</h4>
                            </div>
                            <div class="col-sm-4 text-right">

                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeeList','','1')?>
                                @endif
                                <div class="row" style="margin-top: 10px;">

                                    <div class="col-sm-7"></div>
                                    <div class="col-sm-5 text-right">

                                    </div>
                                </div>

                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-6"></div>

                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <span id="PrintEmployeeList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                            <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="EmployeeList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Holiday Name</th>
                                        <th class="text-center">Holiday Date</th>
                                        <th class="text-center">Holiday Month-Year</th>
                                        <th class="text-center">Created By</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;

                                        ?>
                                        @foreach($holidays as $key => $value)
                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td>{{ $value->holiday_name }}</td>
                                                <td class="text-center">{{ HrHelper::date_format($value->holiday_date) }}</td>
                                                <td class="text-center">{{ $value->month."-".$value->year }}</td>
                                                <td class="text-center">{{ $value->username }}</td>
                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>

                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
																<i data-feather="chevron-down"
                                                                   class="svg-icon"></i></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            @if(in_array('edit', $operation_rights))
                                                                <li role="presentation">
                                                                    <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hr/editHolidaysDetailForm','<?=$value->id?>','Edit Holiday Detail','<?=$m?>')">
                                                                        Edit
                                                                    </a>
                                                                    

                                                                </li>
                                                            @endif
                                                            @if(in_array('repost', $operation_rights))
                                                                @if($value->status == 2)
                                                                    <li role="presentation">
                                                                        <a class="delete-modal btn" onclick="repostOneTableRecords('<?=$m?>','<?php echo $value->id ?>','holidays')">
                                                                            Repost
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($value->status == 1)
                                                                    <li role="presentation">
                                                                        <a class="delete-modal btn" onclick="deleteRowCompanyRecords('<?=$m?>','<?php echo $value->id ?>','holidays')">
                                                                            Delete
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                </span>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebart -->
        <!-- ============================================================== -->
    </div>

    <script>

        function viewHolidaysMonthWise()
        {
            $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var  monthYear = $('#monthYear').val();
            var m = '<?php echo $m?>';
            $.ajax({
                url: '<?php echo url('/')?>/hdc/viewHolidaysMonthWise',
                type: "GET",
                data: { monthYear:monthYear,m:m},
                success:function(data) {
                    $('#loader').html('');
                    $('#MonthlyData').empty();
                    $('#MonthlyData').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" ><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                }
            })

        }

        {{--$(function(){--}}
            {{--$('select[name="department_id"]').on('change', function() {--}}

                {{--$('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');--}}

                {{--var department_id = $(this).val();--}}
                {{--var m = '<?= Input::get('m'); ?>';--}}
                {{--if(department_id) {--}}
                    {{--$.ajax({--}}
                        {{--url: '<?php echo url('/')?>/slal/MachineEmployeeListDeptWise',--}}
                        {{--type: "GET",--}}
                        {{--data: { department_id:department_id,m:m},--}}
                        {{--success:function(data) {--}}

                            {{--$('#emp_loader').html('');--}}
                            {{--$('select[name="employee_id"]').empty();--}}
                            {{--$('select[name="employee_id"]').html(data);--}}
                            {{--$('#employee_id').find('option').get(0).remove();--}}


                        {{--}--}}
                    {{--});--}}
                {{--}else{--}}
                    {{--$('select[name="employee_id"]').empty();--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}

    </script>

    <script src="{{ url('assets/js/dataTables/jquery.dataTables.js') }}"> </script>
    <script>

        $(document).ready(function() {

            var table = $('#EmployeeList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false

            });

            $('#emp_id_search').keyup( function() {
                table.search(this.value).draw();
            });

        });

    </script>

@endsection