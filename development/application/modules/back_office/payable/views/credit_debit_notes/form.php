<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open( $submit_url, array("name" => "form_debit_credit_note") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('credit_debit_notes:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:date_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<input type="text" id="Tgl_Voucher" name="f[Tgl_Voucher]" value="<?php echo @$item->Tgl_Voucher ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> class="form-control datepicker" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:evidence_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<input type="text" id="No_Voucher" name="f[No_Voucher]" value="<?php echo @$item->No_Voucher ?>" placeholder="" class="form-control" readonly  required>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:supplier_label') ?> <span class="text-danger">*</span></label>
					<input type="hidden" id="Supplier_ID" name="f[Supplier_ID]" value="<?php echo @$item->Supplier_ID ?>" class="form-control" />
					<div class="col-md-3">
						<input type="text" id="Kode_Supplier" name="Kode_Supplier"  value="<?php echo @$item->Kode_Supplier ?>" class="form-control" readonly />
					</div>
					<div class="col-md-7 input-group">
						<input type="text" id="Nama_Supplier" name="Nama_Supplier" value="<?php echo @$item->Nama_Supplier ?>" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo @$lookup_suppliers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""  <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
						</div>
					</div>
				</div>
				<?php if (!@$is_edit): ?> 
				<div class="form-group">
					<div class="col-lg-12">
						<a href="javascript:;" id="lookup_vouchers" class="btn btn-success btn-xl col-md-12"><b><i class="fa fa-search"></i> <?php echo lang("credit_debit_notes:search_transactions_label")?></b></a>
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
							if (@$item->Cancel_Voucher) :?>
							<h3  class="text-danger"><?php echo lang("credit_debit_notes:cancel_data")?></h3>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?> 
			</div>
		</div>
		
		<div class="row">
			<?php echo modules::run("payable/credit_debit_note/vouchers", @$item, @$is_edit) ?>
		</div>
		<hr/>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:account_label') ?> <span class="text-danger">*</span></label>
					<input type="hidden" id="Akun_ID" name="f[Akun_ID]" value="<?php echo @$item->Akun_ID ?>" class="form-control" />
					<div class="col-md-3">
						<input type="text" id="Akun_No" name="Akun_No"  value="<?php echo @$item->Akun_No ?>" class="form-control" readonly />
					</div>
					<div class="col-md-7 input-group">
						<input type="text" id="Akun_Name" name="Akun_Name" value="<?php echo @$item->Akun_Name ?>" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo @$lookup_accounts ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title="" <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('credit_debit_notes:description_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<textarea id="Keterangan" name="f[Keterangan]" class="form-control" <?php echo (@$is_edit) ? "readonly" : NULL ?> required><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12 text-right">
				<button type="submit" id="btn-submit" class="btn btn-primary" <?php echo (@$is_edit ) ? "disabled" : NULL ?>><b><i class="fa fa-save"></i> <?php echo lang( 'buttons:submit' ) ?></b></button>
				<a href="<?php echo @$delete_url ?>"  class="btn btn-danger" data-toggle="ajax-modal" <?php echo (!@$is_edit || @$item->Cancel_Voucher || $item->TutupBuku || $item->Posted ) ? "disabled" : NULL ?>><b><i class="fa fa-trash"></i> <?php echo lang( 'buttons:delete' ) ?></b></a>
				<button type="reset" class="btn btn-warning"  <?php echo (@$is_edit) ? "disabled" : NULL ?>><b><i class="fa fa-refresh"></i> <?php echo lang( 'buttons:reset' ) ?></b></button>
				<a href="<?php echo @$create_url ?>"  class="btn btn-success"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>


<script type="text/javascript">
//<![CDATA[
(function( $ ){		
		$( document ).ready(function(e) {
				
				$("#lookup_vouchers").on("click", function(e){
					e.preventDefault();
					
					if( $("#Supplier_ID").val() != '' && $("#Supplier_ID").val() != 0 )
					{
						lookup_ajax_modal.show("<?php echo @$lookup_vouchers ?>");
						return;
					}
					
					lookup_ajax_modal.show("<?php echo @$lookup_suppliers ?>");
					$.alert_error("<?php echo lang('credit_debit_notes:supplier_not_selected') ?>");
					
				});
				
								
			});
	})( jQuery );
//]]>
</script>

 