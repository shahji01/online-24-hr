<?php
$accType = Auth::user()->acc_type;

$m = $_GET['m'];


?>

<div class="well">
    <div class="row">
        {{ Form::open(array('url' => 'had/editTaxesDetail','id'=>'EOBIform')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="recordId" value="{{ Input::get('id') }}">
        <input type="hidden" name="company_id" value="<?php echo $tax->company_id ?>">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="TaxesSection[]" class="form-control" id="sectionEOBI" value="1" />
                        <input type="hidden" name="id" value="{{ $tax_id }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Tax Name:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" name="tax_name" id="tax_name" value="{{ $tax->tax_name }}" class="form-control requiredField" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Tax Month Year:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="month" name="tax_month_year" id="tax_month_year" value="{{ $tax->tax_month_year }}" class="form-control requiredField" />
                    </div>
                </div>
                <div class="row">&nbsp;</div>

                @if($tax_slabs->count() > 0)
                    @foreach($tax_slabs->get() as $val)
                        <?php
                        $count = 0;
                        $counter = $count++;
                        ?>
                        <div class="row" id="deduction_row_edit_{{ $counter }}">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Salary Range From:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="salary_range_from[]" id="salary_range_from" value="{{ $val->salary_range_from }}" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Salary Range To:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="salary_range_to[]" id="salary_range_to" value="{{ $val->salary_range_to }}" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Percentange <span class="rflabelsteric"><strong>*</strong></span></label>

                                <input type="text" name="tax_percent[]" id="tax_percent" value="{{ $val->tax_percent }}" class="form-control requiredField" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Tax Amount Per Year</label>
                                <input type="number" name="tax_amount[]" id="tax_amount" value="{{ $val->tax_amount }}" class="form-control" />
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <input type="button"  value="Remove" onclick="removeTax('{{ $counter }}')" class="btn btn-danger btn-sm">
                            </div>
                        </div>
                    @endforeach
                @endif
                <div class="lineHeight">&nbsp;</div>
                <div class="TaxesSection"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <input type="button" value="Add More Tax Slabs" id="add_taxes" class="btn btn-primary btn-sm addMoreTaxesSection">
                {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

