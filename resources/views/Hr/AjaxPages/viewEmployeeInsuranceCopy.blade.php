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
                                    @if($employee->count() > 0):
                                    @foreach($employee->get() as $value)
                                        <?php $url = url('/').Storage::url($value->insurance_path); ?>
                                        <tr class="remove_row_<?=$value->id?>">
                                            <input id="path_<?=$value->id?>" type="hidden" value="<?=$url?>">
                                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                            <td class="text-center">Insurance Copy</td>
                                            <td class="text-center">{{ $value->insurance_type }}</td>
                                            <td class="text-center"><a target="_blank" href="<?=$url?>">Download</a></td>
                                            <td class="text-center">
                                                <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteEmployeeInsuranceCopy('<?= Input::get('m') ?>','<?=$value->id?>')" class="btn btn-xs btn-danger" type="button">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="remove_row_<?=$value->id?>">
                                            <td class="text-center" colspan="5">
                                                <div class="iframe-loading" style="background: url('<?=url('/assets/img/icon-loader.gif')?>') center center no-repeat;background-size: 200px 200px">

                                                    @if($value->insurance_type == 'doc' || $value->insurance_type == 'docx')
                                                        <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                                    @elseif($value->insurance_type == 'pdf')
                                                        <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                                    @elseif($value->insurance_type == 'jpeg' || $value->insurance_type == 'jpg' || $value->insurance_type == 'png' ||  $value->insurance_type == 'gif')
                                                        <img style="width: 100%;" src="<?=$url?>">
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
    function deleteEmployeeInsuranceCopy(company_id,id) {

        var data = {'companyId':company_id,'id':id,'tableName':'employee'};
        var url= '<?php echo url('/')?>/cdOne/deleteEmployeeInsuranceCopy';
        $.get(url,data, function(result){
            $(".remove_row_"+id).fadeOut();
        });
    }
</script>