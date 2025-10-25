<?php
use App\Helpers\HrHelper;
$m = Input::get('m');
$counter = 1;
?>
<div class="row">&nbsp;</div>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p class="icons">
                <a class="icon btn btn-sm btn-success" name="add_edu" id="add_edu" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                    <span style="color:white;" class="avion fas fa-plus"> Add Education </span>
                </a>
            </p>
            <div class="collapse multi-collapse" id="multiCollapseExample1">
                <form id="checking_edu" method="POST" enctype="multipart/form-data" action="javascript:void(0)">
                    <input type="hidden" name="employee_id" id="employee_id" value="{{ $employee_id }}"/>
                    <input type="hidden" name="m" id="m" value="{{ $m }}"/>
                    <input type="hidden" name="education_data[]" id="education_data[]" value="1">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <table class="table table-sm mb-0 table-bordered table-striped">
                        <thead>
                        <th class="text-center">Name Of Institution</th>
                        <th class="text-center">From</th>
                        <th class="text-center">To</th>
                        <th class="text-center">Degree / Diploma</th>
                        <th class="text-center">Document</th>
                        <th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-success"><i class="fas fa-plus"></i></button></th>
                        </thead>
                        <tbody id="insert_clone">
                        <tr class="get_rows">
                            <td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1"></td>
                            <td class="text-center"><input style="width: 100%;" name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1"></td>
                            <td class="text-center"><input style="width: 100%;" name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1"></td>
                            <td class=""><input type="hidden" name="qualificationSection[]">
                                <select style="width: 100%;" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1" ><option value="">Select</option>
                                    @foreach($degree_types as $val)
                                        <option value="{{ $val->id }}" >{{ $val->degree_type_name }}</option>
                                    @endforeach
                                </select>
                                <span id="other_option_1"></span>
                            </td>
                            <td class="text-center"><input name="document_1" type="file" class="form-control requiredField" id="document_1" value=""></td>
                            <td class="text-center"><span class="badge badge-pill badge-secondary"></span></td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="row">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <input type="submit" name="addSubmit" id="addSubmit" class="btn btn-sm btn-primary" value="Submit" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">&nbsp;</div>
            <table class="table table-sm mb-0 table-bordered table-striped table-fixed">
                <thead>
                <tr>
                    <th class="text-center">S.no</th>
                    <th class="text-center">Institute Name</th>
                    <th class="text-center">Degree Type</th>
                    <th class="text-center">Year Of Admission</th>
                    <th class="text-center">Year Of Passing</th>
                    <th class="text-center">File</th>
                </tr>
                </thead>
                <tbody>
                @if(count($employee_education) != 0)
                    @foreach($employee_education as $value)
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td class="text-center">{{ $value->institute_name }}</td>
                            <td class="text-center">@if(array_key_exists($value->degree_type, $degree_types)) {{ $degree_types[$value->degree_type]->degree_type_name }} @endif</td>
                            <td class="text-center">{{ HrHelper::date_format($value->year_of_admission) }}</td>
                            <td class="text-center">{{ HrHelper::date_format($value->year_of_passing) }}</td>
                            <td class="text-center">
                                @if($value->file_path != '')
                                    <a class="btn btn-sm btn-primary" href="{{ url('/').'/storage/'.$value->file_path }}" download >Download</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-danger text-center"><strong><h4>No Record Found</h4></strong></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
{{--<div class="tab-pane fade in" id="Education"></div>--}}

<script>
    $("#add_edu1").click(function(){
        // $('#myModalss').modal('toggle');

        $("#education_area").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" name="check_emp_id" id="check_emp_id" value="<?php echo $emp_id ?>"/><input type="hidden" name="m" id="m" value="<?php echo $m ?>"/><div class="table-responsive">' +
                '<input type="hidden" name="education_data[]" id="education_data[]" value="1"><table class="table table-sm mb-0 table-bordered table-striped"><thead><th class="text-center col-sm-1">S.No</th>' +
                '<th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>' +
                '<th class="text-center">Degree / Diploma</th>' +
                '<th class="text-center">Document</th>' +
                '<th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-success"><i class="fas fa-plus"></i></button></th>' +
                '</thead><tbody id="insert_clone"><tr class="get_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                '<td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1"></td>' +
                '<td class="text-center"><input name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1"></td>' +
                '<td class="text-center"><input name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1"></td>' +
                '<td class="text-center"><input type="hidden" name="qualificationSection[]">' +
                '<select style="width:100% !important;" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1"><option value="">Select</option>'+
                '@foreach($degree_types as $val)<option value="{{ $val->id }}">{{ $val->degree_type_name }}</option>@endforeach</select><span id="other_option_1"></span></td>'+
                '<td class="text-center"><input name="document_1" type="file" class="form-control" id="document_1" value=""></td>'+
                '<td class="text-center">-</td></tr></tbody></table></div></div>');


        $("#addMoreQualification").click(function(e){
            var clone = $(".get_clone_1").html();

            var form_rows_count = $(".get_rows").length;
            form_rows_count++;
            $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' ><td class='text-center'>" +
                    "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                    "<td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>" +
                    "<td class='text-center'><input style='width: 100%;' name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' id='year_of_admission_"+form_rows_count+"'></td>" +
                    "<td class='text-center'><input style='width: 100%;' name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' id='year_of_passing_"+form_rows_count+"'></td>" +
                    "<td><input type='hidden' name='education_data[]' id='education_datas[]' value="+form_rows_count+">" +
                    "<select style='width: 100%;' id='degree_type_"+form_rows_count+"' class='form-control degree_type_ requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                    "<span style='width: 100%;' id='other_option_"+form_rows_count+"'></span></td>" +
                    "<td class='text-center'><input name='document_"+form_rows_count+"' type='file' class='form-control' id='document_"+form_rows_count+"'></td>"+
                    "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='icon btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                    "</td>" +
                    "</tr>");
            $('#degree_type_'+form_rows_count+'').select2();

        });
    });

    $("#addMoreQualification").click(function(e){
        var clone = $(".get_clone_1").html();

        var form_rows_count = $(".get_rows").length;
        form_rows_count++;
        $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' > <td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>"
                +"<td class='text-center'><input style='width: 100%;' name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                "<td class='text-center'><input style='width: 100%;' name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                "<td><input type='hidden' name='education_data[]' id='education_datas[]' value="+form_rows_count+">" +
                "<select style='width: 100%;' id='degree_type_"+form_rows_count+"' class='form-control degree_type_ requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                "<span id='other_option_"+form_rows_count+"'></span></td>"+
                "<td class='text-center'><input name='document_"+form_rows_count+"' type='file' class='form-control requiredField' id='document_"+form_rows_count+"'></td>"+
                "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='icon btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                "</td> </tr>");
        $('#degree_type_'+form_rows_count+'').select2();

    });

    function removeQualificationSection(id){
        $('#remove_area_'+id).remove();
    }

    $('#checking_edu').submit(function(e) {
        e.preventDefault();

        if($('#institute_name_1').val() == ''){
            alert('field required');
        }
        else if($('#year_of_passing_1').val() == ''){
            alert('field required');
        }
        else if($('#year_of_admission_1').val() == ''){
            alert('field required');
        }
        else if($('#degree_type_1').val() == ''){
            alert('field required');
        }

        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url:'{{ url("/") }}/ddc/addEducationDetails',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(res){
                location.reload();
            },
            error: function() {
                swalError();
            }
        });
    });

    $('#add_edu').click(function(){
        var a=$('#add_edu span').html();
        if(a == " Add Education"){
            $("#add_edu .avion").removeClass("fa-plux").fadeIn(1000);
            $("#add_edu").removeClass("btn-success").fadeIn(1000);
            $("#add_edu").addClass("btn-warning").fadeIn(1000);
            $("#add_edu .avion").addClass("fa-times").fadeIn(1000);
            $("#add_edu .avion").html(' Close').fadeIn(1000);
            $("#institute_name_1").addClass('requiredField');
            $("#year_of_admission_1").addClass('requiredField');
            $("#year_of_passing_1").addClass('requiredField');
            $("#degree_type_1").addClass('requiredField');
        }
        else{
            $("#add_edu .avion").removeClass("fa-times").fadeIn(1000);
            $("#add_edu").removeClass("btn-warning").fadeIn(1000);
            $("#add_edu").addClass("btn-success").fadeIn(1000);
            $("#add_edu .avion").addClass("fa-plus").fadeIn(1000);
            $("#add_edu .avion").html(' Add Education').fadeIn(1000);
            $("#institute_name_1").removeClass('requiredField');
            $("#year_of_admission_1").removeClass('requiredField');
            $("#year_of_passing_1").removeClass('requiredField');
            $("#degree_type_1").removeClass('requiredField');
        }
    });

    $("#addSubmit").click(function(e){

        e.preventDefault();
        jqueryValidationCustom();

        const form = document.querySelector('#checking_edu');
        const formData = new FormData(form);

        if(validate){
            alert(4);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'{{ url("/") }}/ddc/addEducationDetails',
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(res){
                    location.reload();
                },
                error: function() {
                    swalError();
                }
            });
        }
    });
</script>