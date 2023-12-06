<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( $submit_url, array("name" => "form_payable") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('facturs:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('facturs:date_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="Tgl_Faktur" name="Tgl_Faktur" value="<?php echo @$item->Tgl_Faktur ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> class="form-control datepicker" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('facturs:factur_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="No_Faktur" name="No_Faktur" value="<?php echo @$item->No_Faktur ?>" placeholder="" class="form-control" readonly  required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('facturs:supplier_label') ?> <span class="text-danger">*</span></label>
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
					<label class="col-lg-3 control-label"><?php echo lang('types:type_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<select id="JenisHutang_ID" name="JenisHutang_ID" class="form-control"  required>
							<?php  if ( !empty( $options_type ) ) : foreach($options_type as $row) : ?>
							<option value="<?php echo $row->TypeHutang_ID ?>" data-accountid="<?php echo $row->Akun_ID ?>" data-accountno="<?php echo $row->Akun_No ?>" data-accountname="<?php echo $row->Akun_Name ?>" <?php echo ($row->TypeHutang_ID == @$item->JenisHutang_ID ) ? "selected" : NULL ?> ><?php echo $row->Nama_Type ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('facturs:due_date_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="Tgl_JatuhTempo" name="Tgl_JatuhTempo" value="<?php echo @$item->Tgl_JatuhTempo ?>" placeholder="" class="form-control datepicker"  <?php echo (@$is_edit) ? "readonly" : NULL ?> required>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<h3 id="factur_value" class="pull-right text-danger"><?php echo "Rp. ".number_format($item->Nilai_Faktur, 2, ".", ","); ?></h3>
				</div>        
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-9">
						<?php if (@$item->Posted) : ?>
							<h3  class="text-danger"><?php echo lang("facturs:posted_data")?></h3>
						<?php endif;
							if (@$item->TutupBuku) :?>
							<h3  class="text-danger"><?php echo lang("facturs:close_book_data")?></h3>
						<?php endif;
							if (@$item->Cancel_Faktur) :?>
							<h3  class="text-danger"><?php echo lang("facturs:cancel_data")?></h3>
						<?php endif; ?>
					</div>
				</div>        
			</div>
		</div>
		
		<h2 class="text-info"><i class="fa fa-sitemap text-info"></i> <?php echo lang('facturs:accounts_details_sub') ?></h2>
		<div class="row">
			<?php echo  modules::run("payable/factur/details", @$item, @$is_edit) ?>
		</div>
		
		<input type="hidden" id="Nilai_Faktur" value="<?php echo $item->Nilai_Faktur; ?>" />
		
		<div class="row">
			<div class="col-lg-12 text-right">
				<?php if (@$is_edit):?>
				<a href="<?php echo @$print_url ?>"  class="btn btn-danger" target="_blank"><b><i class="fa fa-print"></i> <?php echo lang( 'buttons:print' ) ?></b></a>
				<?php endif;?>
				<button type="submit" id="btn-submit" class="btn btn-primary"  <?php echo (@$is_edit && (@$item->TutupBuku == 1 || @$item->Posted == 1 || @$item->Cancel_Faktur == 1 )) ? "disabled" : NULL ?>><?php echo lang( 'buttons:submit' ) ?></button>
				<button type="reset" class="btn btn-warning"  <?php echo (@$is_edit) ? "disabled" : NULL ?>><?php echo lang( 'buttons:reset' ) ?></button>
				<a href="<?php echo @$cancel_url ?>"  class="btn btn-danger" data-toggle="ajax-modal" <?php echo (@$is_edit && @$item->TutupBuku == 0 && @$item->Posted == 0 || @$item->Cancel_Faktur == 1 ) ? NULL : "disabled" ?>><b><?php echo lang( 'buttons:cancel' ) ?></b></a>
				<a href="<?php echo @$create_url ?>"  class="btn btn-success"><b><?php echo lang( 'buttons:create' ) ?></b></a>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
