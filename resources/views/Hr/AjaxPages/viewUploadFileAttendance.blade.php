<style>
    .table > thead:first-child > tr:first-child > th {
        background-color: #133875;
        color: white;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="lineHeight">&nbsp;</div>
            <?php echo Form::open(array('url' => 'had/addEmployeeAttendanceFileDetail','id'=>'addEmployeeAttendanceFileDetail',"enctype"=>"multipart/form-data"));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="m" value="<?php echo Input::get('m')?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="designationSection[]" class="form-control" id="designationSection" value="1" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Date From</label>
                            <input type="date" class="form-control requiredField" name="date_from" id="date_from" required value="">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Date To</label>
                            <input type="date" class="form-control requiredField" name="date_to" id="date_to" required value="">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>File</label>
                           <input required type="file" name="employeeAttendanceFile" id="employeeAttendanceFile" class="form-control requiredField" value="">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 32px">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="text-center">
                            <h2><a  href="<?=url('/')?>/assets/sample_images/attendance_sample.xlsx">Download Sample / Format </a></h2>
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list" id="TaxesList">
                                        <thead>
                                        <th class="text-center">S. No.</th>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Designation</th>
                                        <th class="text-center">Location/Site</th>
                                        <th class="text-center">EMR</th>
                                        <th class="text-center">Present Days</th>
                                        <th class="text-center">Absent Days</th>
                                        <th class="text-center">Leaves                              (Sick, Casual, Annual)</th>
                                        <th class="text-center">Total Over Time</th>
                                        <th class="text-center">Bank Account Number</th>
                                        <th class="text-center">Remarks</th>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-center">Noman Hashmat  </td>
                                            <td class="text-center">Accountant </td>
                                            <td class="text-center">Saddar </td>
                                            <td class="text-center">555</td>
                                            <td class="text-center">22</td>
                                            <td class="text-center">9</td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">9</td>
                                            <td class="text-center">4240119942173</td>
                                            <td class="text-center">--</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <?php echo Form::close();?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".btn-success").click(function(e){
            var designation = new Array();
            var val;
            $("input[name='designationSection[]']").each(function(){
                designation.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of designation) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });
    });
</script>