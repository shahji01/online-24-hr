<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <div class="panel">
                <div class="panel-body" id="PrintTaxesList">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                            <div class="row">
                                <?php $url = url('/').Storage::url($hrLettersFile[0]['letter_path']); ?>
                                <div class="iframe-loading" style="background: url('<?=url('/assets/img/icon-loader.gif')?>') center center no-repeat;background-size: 200px 200px">
                                    @if($hrLettersFile[0]['file_type']== 'doc' || $hrLettersFile[0]['file_type'] == 'docx')
                                        <iframe height="789" style="width: 100%" src="https://docs.google.com/gview?url=<?=$url?>&embedded=true"></iframe>
                                    @elseif($hrLettersFile[0]['file_type'] == 'pdf')
                                        <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?=$url?>" style="width: 100%" height="789">
                                     @elseif($hrLettersFile[0]['file_type'] == 'jpeg' || $hrLettersFile[0]['file_type'] == 'jpg' || $hrLettersFile[0]['file_type'] == 'png' ||  $hrLettersFile[0]['file_type'] == 'gif')
                                         <img src="<?=$url?>">
                                    @endif
                                </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

