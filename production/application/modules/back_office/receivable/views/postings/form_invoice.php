<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($item->posted);exit;

?>
<?php echo form_open( current_url(), array("name" => "form_debit_credit_note") ); ?>

<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:date_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <input type="text" id="invoice_date" name="f[invoice_date]" value="<?php echo @$item->invoice_date ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> class="form-control datepicker" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:evidence_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <input type="text" id="invoice_number" name="f[invoice_number]" value="<?php echo @$item->invoice_number ?>" placeholder="" class="form-control" readonly="readonly"  required>
            </div>
        </div>
	</div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:customer_label') ?> <span class="text-danger">*</span></label>
            <input type="hidden" id="customer_id" name="f[customer_id]" value="<?php echo @$item->customer_id ?>" class="form-control" />
            <div class="col-md-3">
                <input type="text" id="customer_code" name="customer_code"  value="<?php echo @$customer->code ?>" class="form-control" readonly="readonly" />
            </div>
            <div class="col-md-7 input-group">
                <input type="text" id="customer_name" name="customer_name" value="<?php echo @$customer->customer_name ?>" class="form-control" readonly="readonly" />
                <div class="input-group-btn">
                    <a href="<?php echo @$lookup_customers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""  <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <a href="<?php echo base_url("receivable/credit_debit_notes/lookup_invoices")?>" id="lookup_invoices" class="btn btn-success btn-xl col-md-12" data-toggle="lookup-ajax-modal" disabled><b><i class="fa fa-search"></i> <?php echo lang("buttons:search")?></b></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-6">
    	<?php if ($item->posted) : ?>
			<h3  class="text-danger"><?php echo lang("credit_debit_notes:posted_data")?></h3>
    	<?php endif;
		 	if ($item->close_book) :?>
			<h3  class="text-danger"><?php echo lang("credit_debit_notes:close_book_data")?></h3>
    	<?php endif; ?>
	</div>
	<div class="col-md-6">
		<h2 id="credit_debit_note_value" class="pull-right"><?php echo "Rp. ".number_format($item->value, 2, ".", ","); ?></h2>
	</div>
</div>

<!-- Untuk menyimpan data invoice id yg akan disesuikan -->
<input type="hidden" id="invoice_id" name="invoice_id" value="<?php echo @$invoice->id ?>">
<!-- Untuk menyimpan nomor nota debit credit-->
<input type="hidden" id="evidence_number" name="evidence_number" value="<?php echo @$item->invoice_number ?>">

<div class="row">
	<?php echo  modules::run("receivable/debit_credit_note/details", @$item, @$is_edit) ?>
</div>
<hr/>
<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:account_label') ?> <span class="text-danger">*</span></label>
            <input type="hidden" id="account_id" name="f[account_id]" value="<?php echo @$item->account_id ?>" class="form-control" />
            <div class="col-md-3">
                <input type="text" id="account_number" name="account_number"  value="<?php echo @$account->account_number ?>" class="form-control" readonly="readonly" />
            </div>
            <div class="col-md-7 input-group">
                <input type="text" id="account_name" name="account_name" value="<?php echo @$account->account_name ?>" class="form-control" readonly="readonly" />
                <div class="input-group-btn">
                    <a href="<?php echo @$lookup_accounts ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title="" <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:description_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <textarea id="description" name="f[description]" class="form-control" <?php echo (@$is_edit) ? "readonly" : NULL ?>><?php echo @$item->description ?></textarea>
            </div>
        </div>
	</div>
</div>

<?php echo form_close() ?>
