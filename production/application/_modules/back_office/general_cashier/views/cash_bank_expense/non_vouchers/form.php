<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($item->posted);exit;

?>
<?php echo form_open( $submit_url, array("name" => "form_cash_bank_expense") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('cash_bank_expense:non_voucher_page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_expense:date_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="Tgl_Transaksi" name="Tgl_Transaksi" value="<?php echo @$item->Tgl_Transaksi ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> class="form-control datepicker" required>
					</div>
				</div>
				<?php if ( @$is_edit ): ?>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_expense:evidence_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="No_Bukti" name="No_Bukti" value="<?php echo @$item->No_Bukti ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_expense:description_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="Keterangan" placeholder="" class="form-control"  required><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6"> 
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_expense:type_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<select id="Type_Transaksi" name="Type_Transaksi" class="form-control">
							<option value="BKK" <?php echo ($item->Type_Transaksi == 'BKK') ? 'selected' : NULL ?> data-type="<?php echo $lookup_accounts ?>/GC-Cash">Kas Keluar</option>
							<option value="BBK" <?php echo ($item->Type_Transaksi == 'BBK') ? 'selected' : NULL ?> data-type="<?php echo $lookup_accounts ?>/GC-Bank">Bank Keluar</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_expense:account_label') ?> <span class="text-danger">*</span></label>
					<input type="hidden" id="Akun_ID" name="Akun_ID" value="<?php echo @$item->Akun_ID ?>" class="form-control" />
					<div class="col-md-3">
						<input type="text" id="Akun_No" name="Akun_No"  value="<?php echo @$item->Akun_No ?>" class="form-control" readonly />
					</div>
					<div class="col-md-6 input-group">
						<input type="text" id="Akun_Name" name="Akun_Name" value="<?php echo @$item->Akun_Name ?>" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo $lookup_accounts ?>" id="btn_lookup_accounts" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""  <?php echo (@$is_edit) ? "disabled" : NULL ?>><i class="fa fa-gear"></i></a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_expense:to_label') ?>Kepada/Dari</label>
					<div class="col-lg-9">
						<input type="text" id="Instansi" name="Instansi" value="<?php echo @$item->Instansi ?>" placeholder="" class="form-control" >
					</div>
				</div>
				
				<div class="form-group">
					<h3 id="pay_total" class="pull-right text-danger"><?php echo "Rp. ".number_format($item->Kredit, 2, ".", ","); ?></h3>
				</div>        
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-9">
						<?php if (@$item->Posted) : ?>
							<h3  class="text-danger"><?php echo lang("cash_bank_expense:posted_data")?></h3>
						<?php endif;
							if (@$item->Status_Batal) :?>
							<h3  class="text-danger"><?php echo lang("cash_bank_expense:cancel_data")?></h3>
						<?php endif; ?>
					</div>
				</div>        
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-primary"><b><?php echo lang("cash_bank_expense:account_detail_subtitle")?></b></h4>
			</div>
		</div>
		<div class="row">
			<?php echo  modules::run("general_cashier/cash_bank_expense/non_vouchers/detail", @$item, @$is_edit) ?>
		</div>
		
		<div class="row">
			<div class="col-lg-12 text-right">
				<button type="submit" id="btn-submit" class="btn btn-primary"  <?php echo ( @$item->Posted == 1 || @$item->Status_Batal == 1 )? "disabled" : NULL ?>><?php echo lang( 'buttons:submit' ) ?></button>
				<button type="reset" class="btn btn-warning"  <?php echo (@$is_edit) ? "disabled" : NULL ?>><?php echo lang( 'buttons:reset' ) ?></button>
				<a href="<?php echo @$cancel_url ?>"  class="btn btn-danger" data-toggle="ajax-modal" <?php echo (@$is_edit && @$item->Posted == 0 && @$item->Status_Batal == 0 ) ? NULL : "disabled" ?>><b><?php echo lang( 'buttons:cancel' ) ?></b></a>
				<a href="<?php echo @$create_url ?>"  class="btn btn-success"><b><?php echo lang( 'buttons:create' ) ?></b></a>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>


<script type="text/javascript">
//<![CDATA[
(function( $ ){
		
		$( document ).ready(function(e) {
				
				var _type = $('#Type_Transaksi option:selected').data('type');
				var _btn_lookup_account = $("#btn_lookup_accounts");					
				_btn_lookup_account.prop("href", _type);					

				$("#Type_Transaksi").on("change", function(){
					
					_type = $('option:selected', this).data('type');
					_btn_lookup_account.prop("href", _type);				
					
					$("#Akun_ID").val('');
					$("#Akun_No").val('');
					$("#Akun_Name").val('');
					
				});
								
			});
	})( jQuery );
//]]>
</script>

 
