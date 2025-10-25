

<?php
$accType = Auth::user()->acc_type;

/*if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}*/
//$parentCode = $_GET['parentCode'];
$m = Input::get('m');

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')
@section('content')


    <div class="page-wrapper">

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" >
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Basic Info</h4>
                            </div>
                        </div>
                        <br>
                        <div class="alert-success">
                        <p class="bg-success text-center" id="response" style="color:white;"></p>
                        </div>

                        <div class="container">


                            {{--<p class="p-3 mb-2 bg-danger text-white text-center"  id="error_cnic"></p>--}}
                        {{ Form::open(array('url' => 'uad/editUserProfileDetail','id'=>'basic_info_form','onSubmit'=>'return editMyProfile(this);' ,"enctype"=>"multipart/form-data", "files" => true)) }}
                            @foreach($employee as $emp)
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <div class="hr-border"></div>
                                            <img id="img_file_1" class="avatar img-circle img-thumbnail" style="width:15%;" src="<?= url($emp->img_path != '' ? 'storage/'.$emp->img_path  : 'storage/app/uploads/employee_images/user-dummy.png')?> ">
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <label class="text-dark">
                                                <input type="file" id="file_1" name="fileToUpload_1" accept="image/*" capture style="display:none"/>
                                                <img class="avatar img-circle img-thumbnail" style="width:15%;cursor:pointer" src="<?= url('assets/images/cam.png')?>" id="upfile1"/>
                                                Change Image
                                            </label>
                                        </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <input type="hidden" value="{{ $m }}" name="company_id">
                                            <input type="hidden" value="{{ $emp->emp_id }}" name="emp_id">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label  class="text-dark sf-label" for="name"><h4>Name</h4></label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" name="name" id="name" placeholder="Name" value="<?php echo $emp->emp_name?>" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="text-dark sf-label">Father / Husband Name</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" placeholder="Father Name" name="father_name_1" id="father_name_1" value="<?=$emp->emp_father_name?>" />
                                                </div>
                                                <div class="col-sm-4 ">
                                                    <label  class="text-dark sf-label" for="p_email"><h4>Professional Email</h4></label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input readonly type="email" class="form-control requiredField" name="p_email" id="p_email" placeholder="Personal Email" value="<?php echo $emp->professional_email ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="row">

                                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="text-dark sf-label">Gender</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select style="width: 100%;" class="form-control requiredField" name="gender_1" id="gender_1">
                                                        <option value="">Select Gender</option>
                                                        <option @if($emp->emp_gender == 1) selected @endif value="1">Male</option>
                                                        <option @if($emp->emp_gender == 2) selected @endif value="2">Female</option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="text-dark sf-label">Contact Number</label>
                                                    <span class="input-group-prepend">
                                                    <span class="input-group-text">+92</span>
                                                    <span class="input-group-area" style="width:100%;">
                                                    <?php substr($emp->emp_contact_no, 0, 1);
                                                        $contactNo=ltrim($emp->emp_contact_no, '0');
                                                    ?>
                                                    <input type="text" id="contact_no_1" name="contact_no_1"  value="<?=str_replace("-","",$contactNo)?>" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorMobileNumberOne','1','e')" class="form-control"  placeholder="3xxxxxxxxx" maxlength="10" onkeypress="return isNumber(event)" />
                                                    </span>

                                                    </span>
                                                    <span style="color:red;font-size:13px;font-weight: bold;" class="errorMobileNumberOne" ></span>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="text-dark sf-label">Emergency Contact Number</label>
                                                    <span class="input-group-prepend">
                                                    <span class="input-group-text">+92</span>
                                                    <span class="input-group-area" style="width:100%;">
                                                        <?php substr($emp->contact_home, 0, 1);
                                                        $contactHome=ltrim($emp->contact_home, '0');
                                                        ?>
                                                        <input type="text" id="contact_home_1" value="<?=str_replace("-","",$contactHome)?>" name="contact_home_1" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorlandlineNumberOne','1','e')" class="form-control"  placeholder="3xxxxxxxxx" maxlength="10" onkeypress="return isNumber(event)" />
                                                    </span>
                                                    </span>

                                                    <span style="color:red;font-size:13px;font-weight: bold;" class="errorlandlineNumberOne" ></span>
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">
                                                
                                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="text-dark sf-label">Emergency Person</label>
                                                    <input type="text" class="form-control" placeholder="Person Name" name="emergency_contact_person" id="emergency_contact_person" value="<?=$emp->emergency_contact_person ?>" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="email"><h4>Dob</h4></label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="date" class="form-control" name="dob" id="dob" placeholder="DOB" value="<?php echo $emp->emp_date_of_birth?>">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="text-dark sf-label">Place of Birth</label>
                                                    <input type="text" class="form-control" placeholder="Pace of Birth" name="place_of_birth_1" id="place_of_birth_1" value="<?=$emp->emp_place_of_birth ?>" />
                                                </div>
                                                @if($employee_documents->value('documents_upload_check') == 1)
                                                    <!--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">-->
                                                    <!--    <label class="text-dark pointer" id="cnic_check" style="color: green;">Change / Edit Document:</label><br>-->
                                                    <!--    <a style="cursor: pointer;color: white;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','<?php echo $emp->id;?>','View Employee Documents','<?php echo $m; ?>')" class=" btn btn-info btn-md">View</a>-->
                                                    <!--</div>-->
                                                    @else
                                                    <!--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">-->
                                                    <!--    <label for="media">Upload Document File:</label>-->
                                                    <!--    <input type="file" class="form-control" name="media[]" id="media" multiple>-->
                                                    <!--</div>-->
                                                @endif
                                                

                                            </div>
                                            <hr>
                                        <div class="form-group">
                                            <div class="row">

                                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="text-dark sf-label">CNIC</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField cnicExistMessage" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" maxlength="15" placeholder="CNIC Number" name="cnic_1" id="cnic_1" value="<?=$emp->emp_cnic ?>" />
                                                    <span style="color:red;font-size:13px;font-weight: bold;" id="cnicExistMessage"></span>
                                                </div>
                                                @if($employee_cnic_copy->count() > 0)
                                                    <div id="cnic_div_hide" class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="text-dark pointer" id="cnic_check" style="color: green;">Change / Edit CNIC copy:</label><br>
                                                        <a onclick="showMasterTableEditModel('hdc/viewEmployeeCnicCopy','<?php echo $emp->id.'|'.$emp->emp_id;?>','View Employee CNIC Copy','<?php echo $m; ?>')" class=" btn btn-info btn-md" style="cursor:pointer;color:white;">View</a>

                                                    </div>
                                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" style="display: none" id="cnic_div_show">
                                                        <label class="text-dark">Upload CNIC Copy:</label>
                                                        <input type="file" class="form-control" name="cnic_path_1" id="cnic_path_1" multiple>
                                                    </div>
                                                @else
                                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="text-dark">Upload CNIC Copy:</label>
                                                        <input type="file" class="form-control" name="cnic_path_1" id="cnic_path_1" multiple>
                                                    </div>
                                                @endif
                                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="text-dark sf-label">CNIC Expiry Date</label>

                                                    <input type="date" class="form-control" name="cnic_expiry_date_1" id="cnic_expiry_date_1" value="{{ $emp->emp_cnic_expiry_date }}" />
                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            
                                            <div class="row">


                                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 custom-div">
                                                    <label class="text-dark sf-label">Select Marital Status</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select style="width: 100%;" class="form-control" name="marital_status_1" id="marital_status_1">
                                                        <option value="">Select Marital</option>
                                                        @foreach($marital_status as $value2)
                                                            <option @if($emp->emp_marital_status == $value2->id) selected @endif value="{{ $value2->id}}">{{ $value2->marital_status_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="text-dark sf-label">Nationality</label>

                                                    <input type="text" class="form-control" placeholder="Nationality" name="nationality_1" id="nationality_1" value="<?=$emp->nationality ?>" />
                                                </div>
                                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="text-dark sf-label">Religion</label>
                                                    <input type="text" class="form-control" placeholder="Religion Name" name="religion_1" id="religion_1" value="<?=$emp->relegion?>" />
                                                </div>

                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="text-dark sf-label">Current Address</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <textarea class="form-control requiredField" id="residential_address_1" name="residential_address_1"><?=$emp->residential_address?></textarea>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="text-dark sf-label">Permanent Address</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <textarea class="form-control requiredField" id="permanent_address_1" name="permanent_address_1"><?=$emp->permanent_address?></textarea>
                                                </div>
                                            </div>

                                        </div>




                                        <hr>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-12  text-right">
                                                    <button class="btn btn-md btn-success" id="btn_disable" type="submit">
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        @if($errors->all())
                                            <div class="container alert-danger">
                                                <h3>Validation Message</h3>
                                            </div>
                                            <ul style="color:red;font-size: 13px;">
                                                @foreach($errors->all() as $error)
                                                    <li>{{$error}}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                            </div>

                                <div class="employeeSection"></div>
                        @endforeach

                        <?php echo Form::close();?>
                     </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebart -->
        <!-- ============================================================== -->
    </div>

    <script>
        function readImgURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#emp_image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img_file_1').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#file_1").change(function(){
            readURL(this);
        });
        $("#cnic_1").change(function() {
            var emp_cnic = $("#cnic_1").val();


            $.ajax({
                url: baseUrl+'/hdc/checkCnicNoExist',
                type: "get",

                data: {emp_cnic:emp_cnic, m: m},
                success:function(data) {
                    if(data == 'success')
                    {
                        $('#emp_warning').html('');
                        $("#btn_disable").removeAttr("disabled");
                        $("#cnicExistMessage").html('');
                    }
                    else
                    {
                        $('#btn_disable').attr('disabled', 'disabled');
                        $('#emp_warning').html('Please Remove Errors !');
                        $("#cnicExistMessage").html(data);
                    }
                }
            });
        });

        function editMyProfile(form){
            var inp=$('#img_file_1').attr('src');
            $.ajax({
                type: 'POST',
                url: form.action,
                data:new FormData(form),
                contentType:false,
                processData:false,
                success:function(res){
                    $.notify({
                        icon: "fa fa-check-circle",
                        message: "<b> Successfully updated </b>.",
                    }, {
                        type: 'success',
                        timer: 3000
                    });
                     $('#emp_image').attr('src', inp);


                }

            });

            return false;
        };

    </script>

@endsection


















