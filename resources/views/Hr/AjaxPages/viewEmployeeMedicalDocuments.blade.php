<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <div class="panel">
                <div class="panel-body" id="PrintTaxesList">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list" id="LeaveTypeList">
                                    <thead>
                                    <th class="text-center col-sm-1">S.No</th>
                                    <th class="text-center">File Name</th>
                                    <th class="text-center">File Type</th>
                                    <th class="text-center">Download</th>
                                    <th class="text-center hidden-print">Action</th>
                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;?>
                                    @if($employeeMedicalDocuments->count() > 0):
                                    @foreach($employeeMedicalDocuments->get() as $value)
                                        <?php $url = url('/').Storage::url($value->medical_file_path); ?>
                                        <tr class="remove_row_<?=$value->id?>">
                                            <input id="path_<?=$value->id?>" type="hidden" value="<?=$url?>">
                                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                            <td class="text-center"><a target="_blank" href="<?= Storage::url($value->medical_file_path)?>"><?=$value->medical_file_name?></a></td>
                                            <td class="text-center">.<?=$value->medical_file_type?></td>
                                            <td class="text-center"><a target="_blank" href="<?=$url?>">Download</a></td>
                                            <td class="text-center">
                                                <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteEmployeeDocument('<?= Input::get('m') ?>','<?=$value->id?>')" class="btn btn-xs btn-danger" type="button">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="remove_row_<?=$value->id?>">
                                            <td class="text-center" colspan="5">
                                                <div class="iframe-loading" style="background: url('<?=url('/assets/img/icon-loader.gif')?>') center center no-repeat;background-size: 200px 200px">

                                                    @if($value->medical_file_type == 'doc' || $value->medical_file_type == 'docx')
                                                        <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                                    @elseif($value->medical_file_type == 'pdf')
                                                        <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                                    @elseif($value->medical_file_type == 'jpeg' || $value->medical_file_type == 'jpg' || $value->medical_file_type == 'png' ||  $value->medical_file_type == 'gif')
                                                        <img style="width: 100%;" height="789" src="<?=$url?>">
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="5" style="color:red;font-weight: bold;">Record Not Found !</td>
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
    function deleteEmployeeDocument(company_id,recordId) {

        var path = $("#path_"+recordId).val();
        var data = {'path':path,'companyId':company_id,'recordId':recordId,'tableName':'employee_medical_documents'};
        var url= '<?php echo url('/')?>/cdOne/deleteEmployeeDocument';
        $.get(url,data, function(result){
            $(".remove_row_"+recordId).fadeOut();
        });
    }
</script>