<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$letterTypeArray[1]= 'Warning Letter';
$letterTypeArray[2]= 'MFM South Increment Letter';
$letterTypeArray[3]= 'MFM South Without Increment Letter';
$letterTypeArray[4]= 'Contract Conclusion Letter';
$letterTypeArray[5]= 'Termination Letter Format 1';
$letterTypeArray[6]= 'Termination Letter Format 2';
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/AddLettersFile','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?=$m?>">
        <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Upload Letters File</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintregionWisePayrollReport','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('regionWisePayrollReport','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
                                    <option value="0">Select Department</option>
                                    @foreach($department_id  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Sub Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                    <option value="0">Select Department</option>
                                    @foreach($sub_department_id  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->sub_department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="emp_id" id="emp_id" required ></select>
                                <div id="emp_loader"></div>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Letter:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="letter_type" id="letter_type" required>
                                    <option value="">Select Letter</option>
                                    <option value="1">Warning Letter</option>
                                    <option value="2">MFM South Increment Letter</option>
                                    <option value="3">MFM South Without Increment Letter</option>
                                    <option value="4">Contract Conclusion Letter</option>
                                    <option value="5">Termination Letter Format 1</option>
                                    <option value="6">Termination Letter Format 2</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">File:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input class="form-control requiredField" type="file" name="letter_file" id="letter_file" required>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right" style="margin-top: 30px">
                                <button id="do" type="submit" class="btn btn-sm btn-success">Submit</button>
                            </div>
                        </div>


                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>





        <div class="col-12">
            <div class="card">
                <div class="card-body" id="PrintregionWisePayrollReport">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="TaxesList">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Emp Id</th>
                                    <th class="text-center">Emp Name</th>
                                    <th class="text-center">letter Type</th>
                                    <th class="text-center">File Type</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center hidden-print">Action</th>
                                    </thead>
                                    <tbody>
                                    <?php $counter =1 ; ?>
                                    @foreach($uploaded_letters_list as $value)
                                        <tr>
                                            <td class="text-center">{{$counter++}}</td>
                                            <td class="text-center">{{$value['emr_no']}}</td>
                                            <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $value['emr_no'], 'emp_id') }} </td>
                                            <td class="text-center">{{$letterTypeArray[$value['letter_type']]}}</td>
                                            <td class="text-center">{{$value['file_type']}}</td>
                                            <td class="text-center">{{HrHelper::date_format($value['date'])}}</td>
                                            <td class="text-center hidden-print">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown"><i data-feather="chevron-down"
                                                                                                                                                              class="svg-icon"></i>
                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                        @if(in_array('view', $operation_rights))
                                                            <li role="presentation" class="actionsLink">
                                                                <a class="delete-modal btn" onclick="showMasterTableEditModel('hdc/viewHrLetterFiles','<?=$value['id']?>','View Hr Letters Documents','<?=$m?>')">View</a>
                                                            </li>
                                                        @endif

                                                        @if(in_array('delete', $operation_rights))
                                                            @if($value['status']== 1)
                                                                <li role="presentation" class="actionsLink">
                                                                    <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value['id'] ?>','letter_files')">
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

                        </div>
                    </div>

                </div>

            </div>
        </div>









</div>






@endsection

