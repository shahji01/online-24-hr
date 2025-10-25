
<style>

    hr{border-top: 1px solid cadetblue}

    .img-circle {width: 150px;
        height: 150px;
        border: 2px solid #ccc;
        padding: 4px;
        border-radius: 50%;
        margin-bottom: 32px;
        margin-top: -78px;
        z-index: 10000000;}


</style>

<div class="row"></div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="hr-border" style="border: 1px solid #e5e5e5b0; margin-top: 89px;"></div>
        <img id="img_file_1" class="img-circle" src="<?= Storage::url($employee['img_path'])?>">
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Designation:</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <input readonly name="designation_name" id="designation_name" type="text" value="{{ $designation['designation_name']  }}" class="form-control">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>CNIC:</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <input readonly name="emp_cnic" id="emp_cnic" type="text" value="{{ $employee['emp_cnic'] }}" class="form-control requiredField">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Joining Date:</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <input readonly name="emp_cnic" id="emp_cnic" type="text" value="{{ $employee['emp_joining_date'] }}" class="form-control requiredField">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>Posted At:</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <input name="posted_at" id="posted_at" type="date" value="" class="form-control requiredField">
        </div>
    </div>
</div>

<br>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label>ID Card Image</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <input type="file" name="card_image" id="card_image" type="date" value="" class="form-control">
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <b> Replacement Of Card: <input name="card_replacement" id="card_replacement" value="1" type="checkbox"> </b>
    </div>

    <span id="replacement-card" style="display: none">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <b><input name="replacement_type" class="replacement_type" type="radio" value="lost"> Lost </b>
            <b><input name="replacement_type" class="replacement_type" type="radio" value="stolen"> Stolen </b>
            <b><input name="replacement_type" class="replacement_type" type="radio" value="damaged"> Damaged </b>
            <b><input name="replacement_type" class="replacement_type" type="radio" value="other"> Other </b>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-inline">
                <div class="form-group">
                    <label>Payment:</label>
                    <input type="number" class="form-control" id="payment" name="payment">
                </div>


                <div class="form-group">
                    <label>Upload FIR Copy:</label>
                    <input type="file" class="form-control" name="fir_copy" id="fir_copy" value="" multiple>
                </div>
            </div>
        </div>
    </span>
</div>

<br>
<div style="float: right;">
    <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Submit</button>
</div>








<script>
    $(function () {
        $("#card_replacement").click(function () {
            if ($(this).is(":checked")) {
                $("#replacement-card").show();

            } else {
                $("#replacement-card").hide();
                $('#payment').val('');
                $('.replacement_type').prop('checked',false);
            }
        });
    });
</script>