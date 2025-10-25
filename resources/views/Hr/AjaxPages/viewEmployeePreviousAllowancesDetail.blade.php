<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>

<?php $counter = 0; ?>
@if($allowances->count() > 0)
    @foreach($allowances->get() as $val)
        <?php $counter++ ?>
        <div class="row count_rows" id="remove_area_<?=$counter?>">
            <div class="count_rows"></div>

            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <label class="sf-label">Allowance Type:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="allowance_type[]" id="allowance_type[]" value="{{ $val->allowance_type }}" class="form-control" />
            </div>

            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <label class="sf-label">Amount:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="number" name="allowance_amount[]" id="allowance_amount[]" value="{{ $val->allowance_amount }}" class="form-control" />
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><br>
                <button onclick="removeEmployeeSection('<?=$counter?>')" type="button" class="btn btn-xs btn-danger">Remove</button>
            </div>
        </div>
    @endforeach

@else
    <table class="table table-bordered">
        <th>
            <td class="text-center" style="color: red" >No Allowances Found ! <br>Please click <b>Add More Allowances Section</b> to add Allowance </td>
        </th>
    </table>

@endif

