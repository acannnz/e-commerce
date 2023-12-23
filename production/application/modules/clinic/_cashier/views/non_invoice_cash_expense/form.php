<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('non_invoice_cash_expense:pay_heading')?></h3>
	</div>
	<div class="panel-body">
		<?php echo form_open( current_url(), array("name" => "form_non_invoice_cash_expense", "id"=>"form_non_invoice_cash_expense") ); ?>
		<div class="row form-group">
			<div class="col-md-6">
				<div class="page-subtitle">
					<h3><?php echo lang('non_invoice_cash_expense:general_data_subtitle') ?></h3>
					<p><?php echo lang('non_invoice_cash_expense:general_data_subtitle_helper') ?></p>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:evidence_number_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" required readonly>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:date_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="Jam" name="Jam" value="<?php echo date("Y-m-d H:m:s") ?>"  class="form-control" readonly />
					</div>
				</div>				
				<?php /*?><div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:section_label') ?></label>
					<div class="col-lg-9">
						<select id="SectionID" name="f[SectionID]" class="form-control" disabled>
							<?php foreach($option_section as $key => $val):?>
							<option value="<?php echo $key ?>" <?php echo $key == $item->SectionID ? 'selected' : ''?>><?php echo $val ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div><?php */?>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:doctor_label') ?></label>
					<div class="col-lg-9">
						<select id="DokterID" name="f[DokterID]" class="form-control">
							<?php foreach($option_doctor as $key => $val):?>
							<option value="<?php echo $key ?>" <?php echo $key == $item->DokterID ? 'selected' : ''?>><?php echo $val ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<?php /*?><div class="form-group">
					<label class="col-lg-3 control-label">Untuk <?php echo lang('non_invoice_cash_expense:section_label') ?></label>
					<div class="col-lg-9">
						<select id="UntukSectionID" name="f[UntukSectionID]" class="form-control">
							<?php foreach($option_section as $key => $val):?>
							<option value="<?php echo $key ?>" <?php echo $key == $item->UntukSectionID ? 'selected' : ''?>><?php echo $val ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div><?php */?>
			</div>    
			
			<div class="col-md-6">
				<div class="page-subtitle">
					<h3><?php echo lang('non_invoice_cash_expense:transaction_data_subtitle') ?></h3>
					<p><?php echo lang('non_invoice_cash_expense:transaction_data_subtitle_helper') ?></p>
				</div>
				<?php /*?><div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:type_label') ?></label>
					<div class="col-lg-9">
						<select id="TipeTransaksi" name="f[TipeTransaksi]" class="form-control">
							<option value="KAS" data-type="" <?php echo $item->TipeTransaksi == "KAS" ? "selected" : NULL ?>>KAS</option>
							<option value="KARTU KREDIT" data-type=".merchan" <?php echo $item->TipeTransaksi == "KARTU KREDIT" ? "selected" : NULL ?>>KARTU KREDIT</option>
							<option value="BANK" data-type=".account-merchan" <?php echo $item->TipeTransaksi == "BANK" ? "selected" : NULL ?>>BANK</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:merchan_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="MerchanID" name="f[MerchanID]" value="<?php echo @$item->Akun_ID_Tujuan ?>" placeholder="" class="merchan">
							<input type="text" id="MerchanName" name="f[MerchanName]" value="<?php echo sprintf( "%s - %s", @$item->MerchanID, @$item->MerchanName ) ?>" placeholder="" class="form-control merchan" readonly="readonly">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_merchan ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_account" class="btn btn-default btn-clear" data-clear=".merchan" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:merchan_account_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="AkunMerchanID" name="f[AkunMerchanID]" value="<?php echo @$item->AkunMerchanID ?>" placeholder="" class="account-merchan">
							<input type="text" id="AkunMerchanName" name="f[AkunMerchanName]" value="<?php echo sprintf( "%s - %s", @$item->AkunMerchanNo, @$item->AkunMerchanName ) ?>" placeholder="" class="form-control account-merchan" readonly="readonly">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_account_merchan ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_account" class="btn btn-default btn-clear" data-clear=".account-merchan" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:receipt_from_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="DIterimaDari" name="f[DIterimaDari]" value="<?php echo @$item->DIterimaDari ?>" placeholder="" class="form-control" autofocus="autofocus">
					</div>
				</div><?php */?>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:description_label') ?></label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="f[Keterangan]" class="form-control"><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:value_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="Nilai" name="f[Nilai]" value="<?php echo @$item->Nilai?>" placeholder="" class="form-control text-right mask-number" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:account_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="AkunID" name="f[AkunID]" value="<?php echo @$item->AkunID ?>" placeholder="" class="account">
							<input type="text" id="Akun_Name" name="f[Akun_Name]" value="<?php echo sprintf( "%s - %s", @$item->Akun_No, @$item->Akun_Name ) ?>" placeholder="" class="form-control account" readonly>
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_account ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_account" class="btn btn-default btn-clear" data-clear=".account" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row form-group">
			<div class="col-lg-6">
				<div class="form-group">
					<?php if (@$is_edit): ?>
					<div class="col-lg-3">
						<?php /*?><a href="<?php echo $print_billing_link ?>" id="print_billing" target="_blank" class="btn btn-info btn-block"><b><i class="fa fa-search"></i> <?php echo lang("buttons:preview") ?></b></a><?php */?>
					</div>
					<div class="col-lg-7">
						<a href="<?php echo $print_kwitansi_link ?>" id="print_link" target="_blank" class="btn btn-primary btn-block"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a>
					</div>                
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group text-right"> 
					<button type="reset" class="btn btn-warning"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:reset") ?></b></button>
					<?php if (@$is_edit): ?>
					<a href="<?php echo $cancel_link ?>" id="cancel" data-toggle="ajax-modal" class="btn btn-danger"><b><i class="fa fa-times"></i> <?php echo lang("buttons:cancel") ?></b></a>
					<?php endif; ?>
					<a href="<?php echo base_url("cashier/non-invoice-cash-expense/create")?>" class="btn btn-info"><i class="fa fa-file"></i> <?php echo lang( 'buttons:create' ) ?></a>
					<button type="submit" class="btn btn-primary"><b><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo lang("buttons:submit") ?></b></button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		$( document ).ready(function(e) {	
				
				// --> Start Clear Button
				$(".btn-clear").on("click", function(e){
					var classClear = $(this).data("clear");
					
					$( classClear ).val("");
					$( classClear ).html("");
				});
				// --> End Clear Buttson
				
				$("form[name=\"form_non_invoice_cash_expense\"]").on("submit", function(e){
					e.preventDefault();
									
					var data_post = {};
					
						data_post['f'] = {
						//"TipeTransaksi" : $("#TipeTransaksi").val(),
						//"DIterimaDari" : $("#DIterimaDari").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Nilai" : mask_number.currency_remove($("#Nilai").val()),
						"AkunID" : $("#AkunID").val(),
						"SectionID" : $("#SectionID").val(),
						"UntukSectionID" : $('#UntukSectionID').val(),
						"DokterID" : $("#DokterID").val(),
					};
					
					var TipeTransaksi = $("#TipeTransaksi").val();
					console.log(TipeTransaksi);
					if ( TipeTransaksi == "KARTU KREDIT" )
					{
						data_post['f']['MerchanID'] = $("#MerchanID").val();
						data_post['f']['AkunMerchanID'] = $("#AkunMerchanID").val();
					}
					if ( TipeTransaksi == "BANK" )
					{
						data_post['f']['AkunMerchanID'] = $("#AkunMerchanID").val();
					}
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
							
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}

							if( !response.NoBukti ){
								$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
								return false
							}
							$.alert_success( response.message );
							setTimeout(function(){
								document.location.href = "<?php echo base_url("cashier/non-invoice-cash-expense"); ?>";
								}, 300 );
						})	
				});
			
		 });
	})( jQuery );
//]]>
</script>