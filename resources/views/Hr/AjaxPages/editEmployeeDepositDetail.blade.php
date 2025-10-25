<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    echo $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

    <?php $currentDate = date('Y-m-d');?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    echo $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

    <?php $currentDate = date('Y-m-d');?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Employee Deposit Form</span>
                                </div>
                            </div>
                            <div class="lineHeight"></div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'had/editEmployeeDepositDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="company_id" value="<?= Input::get('m') ?>">
                                    <input type="hidden" name="depositId" value="<?= Input::get('id') ?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="get_clone">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="sf-label">Department:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <select class="form-control requiredField" name="sub_department_id" id="sub_department_id">
                                                            <option value="">Select Department</option>
                                                            @foreach($departments as $key => $y)
                                                                <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                                    <?php
                                                                    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');
                                                                    ?>
                                                                    @foreach($subdepartments as $key2 => $y2)
                                                                        <option <?php if($EmployeeDepositData->sub_department_id == $y2->id){echo "selected";}?>  value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="sf-label">Employee:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <select class="form-control requiredField" name="employee_id" id="employee_id" required>
                                                            @foreach($employee as $key => $e)
                                                                <option <?php if($EmployeeDepositData->acc_no == $e->acc_no){echo "selected";}?> value="<?php echo $e->acc_no?>"><?php echo $e->emp_name;?></option>
                                                            @endforeach
                                                        </select>
                                                        <div id="run_loader">

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="sf-label">To be Deduct on Month & Year:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="month" name="to_be_deduct_on_date" id="to_be_deduct_on_date" value="<?php echo $EmployeeDepositData->deduction_year.'-'.$EmployeeDepositData->deduction_month?>" class="form-control requiredField count_rows" required />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Deposit Name</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="text" name="deposit_name" id="deposit_name" value="<?php echo $EmployeeDepositData->deposit_name?>" class="form-control requiredField count_rows" required />
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Deposit Amount</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="number" name="deposit_amount" id="deposit_amount" value="<?php echo $EmployeeDepositData->deposit_amount?>" class="form-control requiredField count_rows" required />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="insert_clone"></div>
                                        </div>
                                    </div>
 
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>

                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>

    <script>
        $(document).ready(function() {
            $('.addMoreLoanRequestSection').click(function (){
                var count_rows = $('.count_rows').length;
                count_rows++;
                var m = '<?= Input::get('m'); ?>';
                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormLoanRequestDetail',
                    type: "GET",
                    data: { count_rows:count_rows,m:m},
                    success:function(data) {
                        $('.insert_clone').append('<div id="sectionLoanRequest_'+count_rows+'"><button type="button"  onclick="removeLoanRequestSection('+count_rows+')" class="btn btn-xs btn-danger">Remove</button><div class="lineHeight">&nbsp;</div>'+data+'</div>');
                    }
                });
            });
        })

        $(function(){
            $('select[name="sub_department_id"]').on('change', function() {
                $('#run_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                var sub_department_id = $(this).val();
                var m = '<?= Input::get('m'); ?>';
                if(sub_department_id) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/employeeLoadDependentDepartmentID',
                        type: "GET",
                        data: { sub_department_id:sub_department_id,m:m},
                        success:function(data) {
                            $('#run_loader').html('');
                            $('#employee_id').empty();
                            $('#employee_id').html(data);
                            $('#employee_id').find('option').get(0).remove();


                        }
                    });
                }else{
                    $('select[name="employee_id"]').empty();
                }
            });
        });

        function removeLoanRequestSection(id){
            var elem = document.getElementById('sectionLoanRequest_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>

                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Employee Deposit Form</span>
                                </div>
                            </div>
                            <div class="lineHeight"></div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'had/editEmployeeDepositDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="company_id" value="<?= Input::get('m') ?>">
                                    <input type="hidden" name="depositId" value="<?= Input::get('id') ?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="get_clone">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="sf-label">Department:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <select class="form-control requiredField" name="sub_department_id" id="sub_department_id">
                                                            <option value="">Select Department</option>
                                                            @foreach($departments as $key => $y)
                                                                <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                                    <?php
                                                                    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');
                                                                    ?>
                                                                    @foreach($subdepartments as $key2 => $y2)
                                                                        <option <?php if($EmployeeDepositData->sub_department_id == $y2->id){echo "selected";}?>  value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="sf-label">Employee:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <select class="form-control requiredField" name="employee_id" id="employee_id" required>
                                                            @foreach($employee as $key => $e)
                                                                <option <?php if($EmployeeDepositData->acc_no == $e->acc_no){echo "selected";}?> value="<?php echo $e->acc_no?>"><?php echo $e->emp_name;?></option>
                                                            @endforeach
                                                        </select>
                                                        <div id="run_loader">

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="sf-label">To be Deduct on Month & Year:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="month" name="to_be_deduct_on_date" id="to_be_deduct_on_date" value="<?php echo $EmployeeDepositData->deduction_year.'-'.$EmployeeDepositData->deduction_month?>" class="form-control requiredField count_rows" required />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Deposit Name</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="text" name="deposit_name" id="deposit_name" value="<?php echo $EmployeeDepositData->deposit_name?>" class="form-control requiredField count_rows" required />
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Deposit Amount</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="number" name="deposit_amount" id="deposit_amount" value="<?php echo $EmployeeDepositData->deposit_amount?>" class="form-control requiredField count_rows" required />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="insert_clone"></div>
                                        </div>
                                    </div>
 
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>

                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>

    <script>
        $(document).ready(function() {
            $('.addMoreLoanRequestSection').click(function (){
                var count_rows = $('.count_rows').length;
                count_rows++;
                var m = '<?= Input::get('m'); ?>';
                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormLoanRequestDetail',
                    type: "GET",
                    data: { count_rows:count_rows,m:m},
                    success:function(data) {
                        $('.insert_clone').append('<div id="sectionLoanRequest_'+count_rows+'"><button type="button"  onclick="removeLoanRequestSection('+count_rows+')" class="btn btn-xs btn-danger">Remove</button><div class="lineHeight">&nbsp;</div>'+data+'</div>');
                    }
                });
            });
        })

        $(function(){
            $('select[name="sub_department_id"]').on('change', function() {
                $('#run_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                var sub_department_id = $(this).val();
                var m = '<?= Input::get('m'); ?>';
                if(sub_department_id) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/employeeLoadDependentDepartmentID',
                        type: "GET",
                        data: { sub_department_id:sub_department_id,m:m},
                        success:function(data) {
                            $('#run_loader').html('');
                            $('#employee_id').empty();
                            $('#employee_id').html(data);
                            $('#employee_id').find('option').get(0).remove();


                        }
                    });
                }else{
                    $('select[name="employee_id"]').empty();
                }
            });
        });

        function removeLoanRequestSection(id){
            var elem = document.getElementById('sectionLoanRequest_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
