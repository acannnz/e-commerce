<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo form_open( current_url(), array("name" => "form_non_invoice_receipt", "id"=>"form_non_invoice_receipt") ); ?>
	
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('non_invoice_receipt:pay_heading') ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("cashier/non-invoice-receipt/create") ?>" title="<?php echo lang('buttons:create') ?>" class="btn btn-info pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:create') ?></span></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">	
			<div class="col-md-6">
				 <div class="page-subtitle">
					<div class="col-md-12">
						<h3><?php echo lang('non_invoice_receipt:general_data_subtitle') ?></h3>
						<p><?php echo lang('non_invoice_receipt:general_data_subtitle_helper') ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:evidence_number_label') ?></label>
					<div class="col-lg-7">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" required readonly>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:date_label') ?></label>
					<div class="col-lg-7">
						<input type="text" id="Jam" name="Jam" value="<?php echo date("Y-m-d H:m:s") ?>"  class="form-control" readonly />
					</div>
				</div>
				
		
				<!-- <div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:section_label') ?></label>
					<div class="col-lg-7">
						<input type="text" id="SectionName" name="p[SectionName]" value="<?php echo @$section->SectionName?>" placeholder="" class="form-control " readonly>
					</div>
				</div> -->
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:user_label') ?></label>
					<div class="col-lg-7">
						<input type="text" id="Nama_Singkat" name="p[Nama_Singkat]" value="<?php echo @$user->Nama_Singkat ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
			</div>    
		
			<div class="col-md-6">
				<div class="page-subtitle">
					<div class="col-md-12">
						<h3><?php echo lang('non_invoice_receipt:transaction_data_subtitle') ?></h3>
						<p><?php echo lang('non_invoice_receipt:transaction_data_subtitle_helper') ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:type_label') ?></label>
					<div class="col-lg-9">
						<select id="TipeTransaksi" name="f[TipeTransaksi]" class="form-control">
							<option value="KAS" data-type="kas" <?php echo $item->TipeTransaksi == "KAS" ? "selected" : NULL ?>>KAS</option>
							<option value="KARTU KREDIT" data-type=".merchan" data-input="input .merchan" data-button="a .merchan" <?php echo $item->TipeTransaksi == "KARTU KREDIT" ? "selected" : NULL ?>>KARTU KREDIT</option>
							<option value="BANK" data-type=".account-merchan" data-input="input .account-merchan" data-button="a .account-merchan" <?php echo $item->TipeTransaksi == "BANK" ? "selected" : NULL ?>>BANK</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:merchan_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="MerchanID" name="f[MerchanID]" value="<?php echo @$item->Akun_ID_Tujuan ?>" placeholder="" class="merchan">
							<input type="text" id="MerchanName" name="f[MerchanName]" value="<?php echo sprintf( "%s - %s", @$item->MerchanID, @$item->MerchanName ) ?>" placeholder="" class="form-control merchan" readonly>
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_merchan ?>" data-toggle="lookup-ajax-modal" class="btn btn-default merchan" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_account" class="btn btn-default btn-clear merchan" data-clear=".merchan" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:merchan_account_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="AkunMerchanID" name="f[AkunMerchanID]" value="<?php echo @$item->AkunMerchanID ?>" placeholder="" class="account-merchan">
							<input type="text" id="AkunMerchanName" name="f[AkunMerchanName]" value="<?php echo sprintf( "%s - %s", @$item->AkunMerchanNo, @$item->AkunMerchanName ) ?>" placeholder="" class="form-control account-merchan" readonly>
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_account_cash_bank ?>" data-toggle="lookup-ajax-modal" class="btn btn-default account-merchan" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_account" class="btn btn-default btn-clear account-merchan" data-clear=".account-merchan" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:receipt_from_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="DIterimaDari" name="f[DIterimaDari]" value="<?php echo @$item->DIterimaDari ?>" placeholder="" class="form-control" autofocus>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:description_label') ?></label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="f[Keterangan]" class="form-control"><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:value_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="Nilai" name="f[Nilai]" value="<?php echo number_format(@$item->Nilai, 2, ".", ",") ?>" placeholder="" class="form-control text-right" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('non_invoice_receipt:account_label') ?></label>
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
					<a href="<?php echo base_url("cashier/non-invoice-receipt/create")?>" class="btn btn-info"><i class="fa fa-file"></i> <?php echo lang( 'buttons:create' ) ?></a>
					<button type="submit" class="btn btn-primary"><b><i class="fa fa-save"></i> <?php echo lang("buttons:submit") ?></b></button>
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
				
				// --> Start Masking Pembayaran
				$("#Nilai").on("focus", function(){
					val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
					if ( val == 0)
					{
						$(this).val("")
					}
                });

				$("#Nilai").on("blur",function(){
					val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
					if ( val > 0)
					{
						val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
						var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
						$(this).val( mask );
					} else {
						$(this).val( "0.00" );
					}
                });
				
				// --> End Masking Pembayaran
				
				
				// init Tipe Transaksi
				type  = $("#TipeTransaksi").find(':selected').data("type");
				tipe_change( type)
				
				// --> Start Tipe Transaksi OnChange
				$("#TipeTransaksi").on("change", function(){

					type  = $(this).find(':selected').data("type");
					if ( type != "kas")
					{
						$(type).removeClass("disabled");
					}
					
					tipe_change( type );
					
				});
				
				function tipe_change( type )
				{
					$("#TipeTransaksi option").each(function(index, element) {
						e_type = $(this).data('type');
						if ( e_type == type || e_type == 'kas')
						{
							return;
						}
						
						$( e_type ).val("");
						$( e_type ).addClass("disabled");
						
					});				
				}
				
				// --> End Tipe Transaksi OnChange
				
				// --> Start Clear Button
				$(".btn-clear").on("click", function(e){
					var classClear = $(this).data("clear");
					
					$( classClear ).val("");
					//$( classClear ).html("");
				});
				// --> End Clear Buttson
				
				$("form[name=\"form_non_invoice_receipt\"]").on("submit", function(e){
					e.preventDefault();

					$("#Nilai").each(function(index, element) {
						val = parseFloat(element.value.replace(/[^0-9\.-]+/g,""));
						$(this).val(val);
                    });
				
					if ( !confirm("Apakah Anda yakin akan menyimpan data ini ?"))
					{
						// mengembalikan tanda baca currency

						$("#Nilai").each(function(index, element) {
							val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
							var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
							$(this).val( mask );
						});

						return false;
					}
					
					var data_post = {};
					
						data_post['f'] = {
						"TipeTransaksi" : $("#TipeTransaksi").val(),
						"DIterimaDari" : $("#DIterimaDari").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Nilai" : $("#Nilai").val(),
						"AkunID" : $("#AkunID").val(),
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
							
							var NoBukti = response.NoBukti;
							
							setTimeout(function(){
								document.location.href = "<?php echo base_url("cashier/non-invoice-receipt/edit"); ?>/"+ NoBukti;
								}, 3000 );
						})	
				});
			
		 });
	})( jQuery );
//]]>
</script>