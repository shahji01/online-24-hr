<?php
$currentDate = date('Y-m-d');
$id = $_GET['id'];
$m 	= $_GET['m'];
$d 	= DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;
$subDepartmentDetail = DB::selectOne('select * from `sub_department` where `id` = '.$id.'');
?>
<div class="">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <?php echo Form::open(array('url' => 'had/editTrainingDetail?m='.$m.'&&d='.$d.'','id'=>'subDepartmentForm'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $trainingsData->id }}">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Training Topic:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="training_topic" id="training_topic" value="{{ $trainingsData->topic_name }}" class="form-control requiredField" />  
                        </div>
                        
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Training Hours:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="training_hour" id="training_hour" value="{{ $trainingsData->training_hours }}" class="form-control requiredField" />
                            </div>
                        
                         
                        
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Training Date:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="date" name="training_date" id="training_date" value="{{ $trainingsData->training_date }}" class="form-control requiredField" />
                            </div>
                        
                   </div>
                   
                 </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="subDepartmentSection"></div>
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                           
                        </div>
                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
