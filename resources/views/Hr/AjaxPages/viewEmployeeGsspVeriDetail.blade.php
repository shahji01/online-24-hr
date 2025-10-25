
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <div class="panel">
                <div class="panel-body" id="PrintTaxesList">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list" id="TaxesList">
                                    <thead>
                                    <th class="text-center col-sm-1">S.No</th>
                                    <th class="text-center">Document Type</th>
                                    <th class="text-center">Document Extension</th>
                                    <th class="text-center">Download</th>
                                    <th class="text-center">Action</th>
                                    </thead>
                                    <tbody id="document_html_area">
                                    <?php $counter=1; ?>
                                    @if($viewEmployeeGsspVeriDetail->count() > 0)
                                        @foreach($viewEmployeeGsspVeriDetail->get() as $value)
                                            <?php $url = url('/').Storage::url($value->document_path); ?>
                                            <tr class="remove_row_<?=$value->id?>">
                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                                <td class="text-center">
                                                    <input id="path_<?=$value->id?>" type="hidden" value="<?=$url?>"> {{$value->document_type}} </td>
                                                <td class="text-center">{{$value->document_extension}}</td>
                                                <td class="text-center">
                                                    <a target="_blank" href="<?=$url?>">Download</a>
                                                </td>
                                                <td class="text-center">
                                                    <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteEmployeeGsspDocument('<?= Input::get('m') ?>','<?=$value->id?>')" class="btn btn-xs btn-danger" type="button">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="remove_row_<?=$value->id?>">
                                                <td class="text-center" colspan="5">
                                                    <div class="iframe-loading" style="background: url('<?=url('/assets/img/icon-loader.gif')?>') center center no-repeat;background-size: 200px 200px">
                                                        @if($value->document_extension == 'doc' || $value->document_extension == 'docx')
                                                            <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                                        @elseif($value->document_extension == 'pdf')
                                                            <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                                        @elseif($value->document_extension == 'jpeg' || $value->document_extension == 'jpg' || $value->document_extension == 'png' || $value->document_extension == 'gif')
                                                            <img style="width: 100%;" src="<?=$url?>">
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
    function deleteEmployeeGsspDocument(company_id,recordId) {

    var path = $("#path_"+recordId).val();
    var data = {'path':path,'companyId':company_id,'recordId':recordId,'tableName':'employee_documents'};
    var url= '<?php echo url('/')?>/cdOne/deleteEmployeeGsspDocument';
    $.get(url,data, function(result){
    $(".remove_row_"+recordId).fadeOut();
    });
    }
</script>