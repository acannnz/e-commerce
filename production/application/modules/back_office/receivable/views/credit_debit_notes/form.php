<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open( $submit_url, array("name" => "form_debit_credit_note") ); ?>

<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:date_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <input type="text" id="Tgl_Invoice" name="f[Tgl_Invoice]" value="<?php echo @$item->Tgl_Invoice ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> class="form-control datepicker" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:evidence_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <input type="text" id="No_Invoice" name="f[No_Invoice]" value="<?php echo @$item->No_Invoice ?>" placeholder="" class="form-control" readonly="readonly"  required>
            </div>
        </div>
	</div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:customer_label') ?> <span class="text-danger">*</span></label>
            <input type="hidden" id="Customer_ID" name="f[Customer_ID]" value="<?php echo @$item->Customer_ID ?>" class="form-control" />
            <div class="col-md-3">
                <input type="text" id="Kode_Customer" name="Kode_Customer"  value="<?php echo @$item->Kode_Customer ?>" class="form-control" readonly="readonly" />
            </div>
            <div class="col-md-7 input-group">
                <input type="text" id="Nama_Customer" name="Nama_Customer" value="<?php echo @$item->Nama_Customer ?>" class="form-control" readonly="readonly" />
                <div class="input-group-btn">
                    <a href="<?php echo @$lookup_customers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""  <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
                </div>
            </div>
        </div>
		<?php if (!@$is_edit): ?> 
        <div class="form-group">
            <div class="col-lg-12">
                <a href="javascript:;" id="lookup_invoices" class="btn btn-success btn-xl col-md-12"><b><i class="fa fa-search"></i> <?php echo lang("credit_debit_notes:search_transactions_label")?></b></a>
            </div>
        </div>
        <?php else: ?>
        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <?php if (@$item->Posted) : ?>
                    <h3  class="text-danger"><?php echo lang("credit_debit_notes:posted_data")?></h3>
                <?php endif;
                    if (@$item->TutupBuku) :?>
                    <h3  class="text-danger"><?php echo lang("credit_debit_notes:close_book_data")?></h3>
                <?php endif;
                    if (@$item->Cancel_Invoice) :?>
                    <h3  class="text-danger"><?php echo lang("credit_debit_notes:cancel_data")?></h3>
                <?php endif; ?>
            </div>
        </div>
		<?php endif; ?> 
    </div>
</div>

<div class="row">
	<?php echo modules::run("receivable/credit_debit_note/invoices", @$item, @$is_edit) ?>
</div>
<hr/>
<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:account_label') ?> <span class="text-danger">*</span></label>
            <input type="hidden" id="Akun_ID" name="f[Akun_ID]" value="<?php echo @$item->Akun_ID ?>" class="form-control" />
            <div class="col-md-3">
                <input type="text" id="Akun_No" name="Akun_No"  value="<?php echo @$item->Akun_No ?>" class="form-control" readonly="readonly" />
            </div>
            <div class="col-md-7 input-group">
                <input type="text" id="Akun_Name" name="Akun_Name" value="<?php echo @$item->Akun_Name ?>" class="form-control" readonly="readonly" />
                <div class="input-group-btn">
                    <a href="<?php echo @$lookup_accounts ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title="" <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:description_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <textarea id="Keterangan" name="f[Keterangan]" class="form-control" <?php echo (@$is_edit) ? "readonly" : NULL ?> required="required"><?php echo @$item->Keterangan ?></textarea>
            </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-lg-12 text-right">
    	<button type="submit" id="btn-submit" class="btn btn-primary" <?php echo (@$is_edit ) ? "disabled" : NULL ?>><b><i class="fa fa-save"></i> <?php echo lang( 'buttons:submit' ) ?></b></button>
        <a href="<?php echo @$delete_url ?>"  class="btn btn-danger" data-toggle="ajax-modal" <?php echo (!@$is_edit || @$item->Cancel_Invoice || $item->TutupBuku || $item->Posted ) ? "disabled" : NULL ?>><b><i class="fa fa-trash"></i> <?php echo lang( 'buttons:delete' ) ?></b></a>
        <button type="reset" class="btn btn-warning"  <?php echo (@$is_edit) ? "disabled" : NULL ?>><b><i class="fa fa-refresh"></i> <?php echo lang( 'buttons:reset' ) ?></b></button>
        <a href="<?php echo @$create_url ?>"  class="btn btn-success"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a>
    </div>
</div>
<?php echo form_close() ?>


<script type="text/javascript">
//<![CDATA[
(function( $ ){		
		$( document ).ready(function(e) {
				
				$("#lookup_invoices").on("click", function(e){
					e.preventDefault();
					
					if( $("#Customer_ID").val() != '' && $("#Customer_ID").val() != 0 )
					{
						lookup_ajax_modal.show("<?php echo @$lookup_invoices ?>");
						return;
					}
					
					lookup_ajax_modal.show("<?php echo @$lookup_customers ?>");
					$.alert_error("<?php echo lang('credit_debit_notes:customer_not_selected') ?>");
					
				});
				
								
			});
	})( jQuery );
//]]>
</script>

 