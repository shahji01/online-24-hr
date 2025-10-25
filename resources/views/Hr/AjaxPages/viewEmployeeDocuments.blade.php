<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                        <thead>
                        <th class="text-center">S.No</th>
                        <th class="text-center">File Name</th>
                        <th class="text-center">File Type</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Download</th>
                        <th class="text-center hidden-print">Action</th>
                        </thead>
                        <tbody>
                        <?php $counter = 1;?>
                        @if($employeeDocuments->count() > 0)
                            @foreach($employeeDocuments->get() as $value)
                                <?php $url = url('/').Storage::url($value->file_path); ?>
                                <tr class="remove_row_{{ $value->id }}">
                                    <input id="path_{{ $value->id }}" type="hidden" value="{{ $url }}">
                                    <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                    <td class="text-center"><a target="_blank" href="{{ Storage::url($value->file_path) }}">{{ $value->file_name }}></a></td>
                                    <td class="text-center">.{{ $value->file_type }}</td>
                                    <td class="text-center">{{ $value->type }}</td>
                                    <td class="text-center">
                                        <a style="cursor:pointer;margin-top:10px;font-size:18px;font-wight:bold;" target="_blank" href="{{ $url }}"><i class="badge badge-info">Download</i></a>
                                    </td>
                                    <td class="text-center">
                                        <button onclick="deleteEmployeeDocument('{{ Input::get('m') }}','{{ $value->id }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="6" style="color:red;font-weight: bold;">Record Not Found !</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteEmployeeDocument(company_id,recordId) {

        var path = $("#path_"+recordId).val();
        var data = {'path':path,'companyId':company_id,'recordId':recordId,'tableName':'employee_documents'};
        var url= '<?php echo url('/')?>/cdOne/deleteEmployeeDocument';
        $.get(url,data, function(result){
            $(".remove_row_"+recordId).fadeOut();
        });
    }
</script>