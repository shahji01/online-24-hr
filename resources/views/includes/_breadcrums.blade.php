<?php 
    use App\Helpers\CommonHelper;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <h4 class="card-title">{{$breadCrums['heading']}}</h4>
                    </div>
                    <div class="col-sm-8 text-right">
                        @if(in_array('print',$operation_rights))
                            {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}
                        @endif
                        @if(in_array('export',$operation_rights))
                            {{ CommonHelper::displayExportButton('exportList','','1') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>