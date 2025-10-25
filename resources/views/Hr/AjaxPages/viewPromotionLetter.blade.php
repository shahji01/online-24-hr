<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = '1';
$m = $_GET['m'];
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="company_id" value="<?=Input::get('m')?>">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row table-responsive">
                                    <table class="table table-sm mb-0 table-bordered sf-table-list table-hover">
                                        <tbody>
                                        <tr>
                                            <th  colspan="2"> Emp ID</th>
                                            <td  colspan="2"><?php echo $employee->emp_id ?></td>
                                        </tr>
                                        <tr>
                                            <th  colspan="2"> Employee Name </th>
                                            <td  colspan="2"><?php echo $employee->emp_name ?></td>
                                        </tr>
                                        <tr>
                                            <th  colspan="2"> Department</th>
                                            <td  colspan="2">{{HrHelper::getMasterTableValueById($m,'department','department_name',$employee->department_id )}}</td>
                                        </tr>
                                        <tr>
                                            <th  colspan="2">Sub Department</th>
                                            <td  colspan="2">{{HrHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$employee->sub_department_id?$employee->sub_department_id:0  )}}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Grade</th>
                                            <td  colspan="2">{{HrHelper::getMasterTableValueById($m,'grades','employee_grade_type',$EmployeePromotion->grade_id  )}}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Designation</th>
                                            <td  colspan="2">{{HrHelper::getMasterTableValueById($m,'designation','designation_name',$EmployeePromotion->designation_id  )}}</td>
                                        </tr>
                                        <tr>
                                            <th  colspan="2">Previous Salary</th>
                                            <td  colspan="2"><?php echo number_format($employee->emp_salary,0) ?></td>
                                        </tr>
                                        <tr>
                                            <th  colspan="2">Increment</th>
                                            <td  colspan="2"><?php echo number_format($EmployeePromotion->increment,0) ?></td>
                                        </tr>
                                        <tr>
                                            <th  colspan="2">Incremented Salary</th>
                                            <td  colspan="2"><?php echo number_format($EmployeePromotion->salary,0)?></td>
                                        </tr>
                                        <tr>
                                            <th  colspan="2">Date</th>
                                            <td  colspan="2"><?php echo $EmployeePromotion->promotion_date ?></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="&nbsp;"></div>
                            <?php if(count($promotion_letter) != '0'){ ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="LeaveTypeList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center hidden-print">Action</th>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($promotion_letter as $value) { ?>
                                            <?php $url1 = url('/').Storage::url($value->file_path); ?>
                                            <tr class="remove_row_<?=$value->id?>">
                                                <input id="path_<?=$value->id?>" type="hidden" value="<?=$url1?>">
                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                                <td class="text-center">
                                                    <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteEmployeeDocument('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-danger" type="button">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="remove_row_<?=$value->id?>">
                                                <td class="text-center" colspan="5">
                                                    <?php $url = url('/').Storage::url($value->letter_uploading);?>
                                                    @if($value->file_type == 'doc' || $value->file_type == 'docx')
                                                        <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                                    @elseif($value->file_type == 'pdf')
                                                        <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                                    @elseif($value->file_type == 'jpeg' || $value->file_type == 'jpg' || $value->file_type == 'png' || $value->file_type == 'PNG' || $value->document_extension == 'gif')
                                                        <img style="width: 100%;" src="{{$url}}">
                                                    @endif
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteEmployeeDocument(company_id,recordId) {
        var data = {'companyId':company_id,'recordId':recordId};
        var url= '<?php echo url('/')?>/cdOne/deletePromotionLetter';
        $.get(url,data, function(result){
            $(".remove_row_"+recordId).fadeOut();
        });
    }
</script>
