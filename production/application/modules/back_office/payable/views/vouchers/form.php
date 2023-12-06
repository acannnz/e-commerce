<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($item->posted);exit;

?>
<?php echo form_open( $submit_url, array("name" => "form_payable") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('vouchers:create_heading'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('vouchers:date_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="Tgl_Voucher" name="Tgl_Voucher" value="<?php echo @$item->Tgl_Voucher ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" placeholder="" class="form-control datepicker" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('vouchers:voucher_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="No_Voucher" name="No_Voucher" value="<?php echo @$item->No_Voucher ?>" placeholder="" class="form-control" readonly  required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('vouchers:supplier_label') ?> <span class="text-danger">*</span></label>
					<input type="hidden" id="Supplier_ID" name="Supplier_ID" value="<?php echo @$item->Supplier_ID ?>" class="form-control" />
					<div class="col-md-3">
						<input type="text" id="Kode_Supplier" name="Kode_Supplier"  value="<?php echo @$item->Kode_Supplier ?>" class="form-control" readonly />
					</div>
					<div class="col-md-6 input-group">
						<input type="text" id="Nama_Supplier" name="Nama_Supplier" value="<?php echo @$item->Nama_Supplier ?>" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo $lookup_suppliers ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""  <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('vouchers:due_date_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="Tgl_Tempo" name="Tgl_Tempo" value="<?php echo @$item->Tgl_Tempo ?>" placeholder="" class="form-control datepicker"  data-date-min-date="<?php echo  (@$is_edit) ? $item->Tgl_Voucher : $beginning_balance_date  ?>" required>
					</div>
				</div>
			</div>
			<div class="col-md-6"> 
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('vouchers:description_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="Keterangan" placeholder="" class="form-control"  required><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<h3 id="voucher_value" class="pull-right text-danger"><?php echo "Rp. ".number_format($item->Nilai, 2, ".", ","); ?></h3>
				</div>        
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-9">
						<?php if (@$item->Posted) : ?>
							<h3  class="text-danger"><?php echo lang("vouchers:posted_data")?></h3>
						<?php endif;
							if (@$item->TutupBuku) :?>
							<h3  class="text-danger"><?php echo lang("vouchers:close_book_data")?></h3>
						<?php endif;
							if (@$item->Cancel_Voucher) :?>
							<h3  class="text-danger"><?php echo lang("vouchers:cancel_data")?></h3>
						<?php endif; ?>
					</div>
				</div>        
			</div>
		</div>
		<hr>
		<input type="hidden" id="Nilai" value="<?php echo $item->Nilai; ?>" />
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-primary"><b><?php echo lang("vouchers:voucher_detail_subtitle")?></b></h4>
			</div>
		</div>
		<div class="row">
			<?php echo  modules::run("payable/vouchers/detail", @$item, @$is_edit) ?>
		</div>
		
		<?php if (@$is_edit): ?>
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-primary"><b><?php echo lang("vouchers:mutation_history_subtitle")?></b></h4>
			</div>
		</div>
		<div class="row">
			<?php echo  modules::run("payable/vouchers/detail_mutation", @$item, @$is_edit) ?>
		</div>
		<?php endif; ?>
		
		<div class="row">
			<div class="col-lg-12 text-right">
				<button type="submit" id="btn-submit" class="btn btn-primary"  <?php echo (@$is_edit && (@$item->TutupBuku == 1 || @$item->Posted == 1 || @$item->Cancel_Voucher == 1 )) ? "disabled" : NULL ?>><?php echo lang( 'buttons:submit' ) ?></button>
				<button type="reset" class="btn btn-warning"  <?php echo (@$is_edit) ? "disabled" : NULL ?>><?php echo lang( 'buttons:reset' ) ?></button>
				<a href="<?php echo @$cancel_url ?>"  class="btn btn-danger" data-toggle="ajax-modal" <?php echo (@$is_edit && @$item->TutupBuku == 0 && @$item->Posted == 0 && @$item->Cancel_Voucher == 0 ) ? NULL : "disabled" ?>><b><?php echo lang( 'buttons:cancel' ) ?></b></a>
				<a href="<?php echo @$create_url ?>"  class="btn btn-success"><b><?php echo lang( 'buttons:create' ) ?></b></a>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
