<?php
$counter = 1;
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wrapper" id="printList">
                <table class="table table-sm mb-0 table-bordered table-striped table-hover tableFixHead" id="exportList">
                    <thead>
                    <th class="text-center">S.no</th>
                    <th class="text-center">Emp Code</th>
                    <th class="text-center">Emp Name</th>
                    <th class="text-center">Emp Email</th>
                    <th class="text-center">View</th>
                    <th class="text-center">Download</th>
                    <th class="text-center">
                        Check All<br>  <input id="check_all" checked type="checkbox">
                    </th>
                    </thead>
                    <tbody>
                    @if(!empty($employees))
                        @foreach($employees as $value)
                            <tr>
                                <td class="text-center">{{$counter++}}</td>
                                <td class="text-center">{{ $value->emp_id }}</td>
                                <td>{{ $value->emp_name }}</td>
                                <td>{{ $value->personal_email }}</td>
                                <td class="text-center">
                                    <a target="_blank" href="{{ url('/') }}/hr/viewTaxCertificate/{{ $value->id }}/{{ $year }}/{{ $company_id }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a target="_blank" href="{{ url('/') }}/hr/downloadTaxCertificatePdf/{{ $value->id }}/{{ $year }}/{{ $company_id }}">
                                        <img src="{{ url('/') }}/assets/images/pdf.png" height="20" width="20">
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if($value->personal_email != '')
                                        <input type="checkbox" @if(array_key_exists($value->id, $income_tax_data)) checked @endif class="ads_Checkbox " name="check_list[]" value="{{ $value->personal_email.'<>'.$value->id.'<>'.$year }}">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" style="color: red" colspan="8">Data not found !</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                <button class="btn btn-sm btn-success" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(function(){
        $("#checkbox").click(function(){
            if($("#checkbox").prop("checked") == true) {
                $(".check_list").prop("checked",true);
                $(".check_input").val('1');
            }
            else {
                $(".check_list").prop("checked",false);
                $(".check_input").val('0');
            }
        });
    });

    function checkListChange(emp_id){
        if($("#check_list_"+emp_id).prop("checked") == true)
        {
            $("#check_input_"+emp_id).val('1');
        }else{
            $("#check_input_"+emp_id).val('0');
        }
    }

</script>