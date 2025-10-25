<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <div class="panel">
                <div class="panel-body" id="PrintTaxesList">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveTypeList">
                                    <thead>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">File Name</th>
                                    <th class="text-center">File Type</th>
                                    <th class="text-center">Download</th>
                                    <th class="text-center hidden-print">Action</th>
                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;?>
                                    @if($employee_experience->count() > 0):
                                    @foreach($employee_experience->get() as $value)
                                        <?php $url = url('/').Storage::url($value->work_exp_path); ?>
                                        <tr class="remove_row_<?=$value->id?>">
                                            <input id="path_<?=$value->id?>" type="hidden" value="<?=$url?>">
                                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                            <td class="text-center"><a target="_blank" href="<?= Storage::url($value->work_exp_path)?>"><?=$value->work_exp_name?></a></td>
                                            <td class="text-center">.<?=$value->work_exp_type?></td>
                                            <td class="text-center"><a target="_blank" href="<?=$url?>">Download</a></td>
                                            <td class="text-center">
                                                <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteEmployeeExperienceDocuments('<?= Input::get('m') ?>','<?=$value->id?>')" class="btn btn-sm btn-danger" type="button">
                                                    <span class="fas fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="remove_row_<?=$value->id?>">
                                            <td class="text-center" colspan="5">
                                                <div class="iframe-loading">

                                                    @if($value->work_exp_type == 'doc' || $value->work_exp_type == 'docx')
                                                        <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                                    @elseif($value->work_exp_type == 'pdf')
                                                        <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                                    @elseif($value->work_exp_type == 'jpeg' || $value->work_exp_type == 'jpg' || $value->work_exp_type == 'png' ||  $value->work_exp_type == 'gif')
                                                        <img style="width: 100%;" height="789" src="<?=$url?>">
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="5" style="color:red;font-weight: bold;">No Record Found !</td>
                                        </tr>
                                    @endif

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
<script>
    function deleteEmployeeExperienceDocuments(company_id,id) {

        var data = {'companyId':company_id,'id':id,'tableName':'employee_work_experience'};
        var url= '<?php echo url('/')?>/cdOne/deleteEmployeeExperienceDocuments';
        $.get(url,data, function(result){
            $(".remove_row_"+id).fadeOut();
        });
    }
</script>