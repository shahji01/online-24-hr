<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
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
                                @if($rebate_documents->count() > 0)
                                    @foreach($rebate_documents->get() as $value)
                                        <?php $url = url('/').Storage::url($value->rebate_file_path); ?>
                                        <tr class="remove_row_{{ $value->id }}">
                                            <input id="path_{{ $value->id }}" type="hidden" value="{{ $url }}">
                                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                            <td class="text-center"><a target="_blank" href="{{ Storage::url($value->rebate_file_path) }}">{{ $value->rebate_file_name }}</a></td>
                                            <td class="text-center">{{ $value->rebate_file_type }}</td>
                                            <td class="text-center"><a target="_blank" href="{{ $url }}">Download</a></td>
                                            <td class="text-center">
                                                <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteRebateDocument('{{ $value->id }}','{{ $m }}','employee_rebate_documents')" class="btn btn-sm btn-danger" type="button">
                                                    <span class="fas fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="remove_row_{{ $value->id }}">
                                            <td class="text-center" colspan="5">
                                                <div class="iframe-loading" style="background: url('{{ url('/assets/img/icon-loader.gif') }}') center no-repeat;background-size: 200px 200px">
                                                    @if($value->rebate_file_type == 'doc' || $value->rebate_file_type == 'docx')
                                                        <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url={{ $url }}&embedded=true"></iframe>
                                                    @elseif($value->rebate_file_type == 'pdf')
                                                        <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url={{ $url }}" style="width: 100%" height="789">
                                                            @elseif($value->rebate_file_type == 'jpeg' || $value->rebate_file_type == 'jpg' || $value->rebate_file_type == 'png' ||  $value->rebate_file_type == 'gif')
                                                                <img style="width: 100%;" height="789" src="{{ $url }}">
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