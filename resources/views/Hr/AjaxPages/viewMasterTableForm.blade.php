<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="lineHeight">&nbsp;</div>
        <input type="hidden" name="columnName" id="columnName" value="<?php echo $_GET['columnName']?>">
        <input type="hidden" name="tableName" id="tableName" value="<?php echo $_GET['tableName']?>">
        <input type="hidden" name="dropDownId" id="dropDownId" value="<?php echo $_GET['dropDownId']?>">

        <div class="panel">
            <div class="panel-body">
                <div class="row" id="departments" style="display: none">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Select Department:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <select class="form-control" name="department_id_1" id="department_id_1">
                            <option value="">Select Department</option>
                            @foreach($departments as $key => $y)
                                <option value="{{ $y->id}}">{{ $y->department_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="lineHeight"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Name :</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" name="name" id="name" value="" class="form-control requiredField" required />
                    </div>
                </div>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <button id="submit" class="btn btn-success" onclick="addMasterTableDetail()">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){
       var dropDown =  $('#dropDownId').val();
       if(dropDown == 'sub_department_id')
       {
           $('#departments').show();
       }
    });

    function addMasterTableDetail() {


        var columnName = $('#columnName').val();
        var tableName = $('#tableName').val();
        var name = $('#name').val();
        var dropDownId = $('#dropDownId').val();
        var department_id = $('#department_id_1').val();
        var m = '<?php echo $m ?>';
        if(name != '' ){
            $('#showMasterTable').modal('toggle');
            $.ajax({
                url: '<?php echo url('/')?>/hadbac/addMasterTableDetail',
                type: "GET",
                data: {m: m, columnName: columnName, tableName: tableName, name: name, department_id:department_id},
                success: function (data) {
                    var newOption = new Option(data.name, data.id, true, true);
                    $('.'+dropDownId).append(newOption).trigger('change');
                }
            });
        }
        else
        {
            $('#name').focus();
            $('#name').css('border-color','red');
        }
    }

</script>
