<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($options_type);exit;

?>
<?php echo form_open( current_url(), array("name" => "form_payable") ); ?>

<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?php echo lang('facturs:date_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" id="factur_date" name="factur_date" value="<?php echo @$item->factur_date ?>" data-date-min-date="<?php echo $house->system_date ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> class="form-control datepicker" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label"><?php echo lang('facturs:factur_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" id="factur_number" name="factur_number" value="<?php echo @$item->factur_number ?>" placeholder="" class="form-control" readonly="readonly"  required>
            </div>
        </div>
	</div>
    <div class="col-md-8">
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('facturs:supplier_label') ?> <span class="text-danger">*</span></label>
            <input type="hidden" id="supplier_id" name="supplier_id" value="<?php echo @$supplier->id ?>" class="form-control" />
            <div class="col-md-3">
                <input type="text" id="supplier_code" name="supplier_code"  value="<?php echo @$supplier->code ?>" class="form-control" readonly="readonly" />
            </div>
            <div class="col-md-7 input-group">
                <input type="text" id="supplier_name" name="supplier_name" value="<?php echo @$supplier->supplier_name ?>" class="form-control" readonly="readonly" />
                <div class="input-group-btn">
                    <a href="<?php echo @$lookup_suppliers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""  <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('types:type_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-5">
                <select id="type_id" name="type_id" class="form-control"  <?php echo (@$is_edit) ? "disabled" : NULL ?> required>
                	<?php  if ( !empty( $options_type ) ) : foreach($options_type as $k => $v) : ?>
                    <option value="<?php echo $k ?>" <?php echo ($k == @$item->type_id ) ? "selected" : NULL ?> ><?php echo $v ?></option>
                    <?php endforeach; endif;?>
                </select>
            </div>
            <label class="col-lg-2 control-label"><?php echo lang('facturs:due_date_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-3">
                <input type="text" id="due_date" name="due_date" value="<?php echo @$item->due_date ?>" placeholder="" class="form-control datepicker"  <?php echo (@$is_edit) ? "readonly" : NULL ?> required>
            </div>
        </div>
    </div>
</div>

<h3 id="factur_value" class="pull-right text-danger"><?php echo "Rp. ".number_format($item->value, 2, ".", ","); ?></h3>

<div class="page-subtitle">
    <h2 class="text-info"><i class="fa fa-sitemap text-info"></i> <?php echo lang('facturs:accounts_details_sub') ?></h2>
</div>
<div class="row">
	<?php echo  modules::run("payable/postings/factur_details", @$item, @$is_edit) ?>
</div>
<hr />
<?php echo form_close() ?>
