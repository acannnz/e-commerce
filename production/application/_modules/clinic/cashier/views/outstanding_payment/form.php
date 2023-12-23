<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo form_open( $submit_url, array("name" => "form_outstanding_payment", "id"=>"form_outstanding_payment") ); ?>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('outstanding_payment:pay_heading') ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("cashier/outstanding-payment/pay") ?>" title="<?php echo 'Pembayaran Baru' ?>" class="btn btn-info pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo 'Pembayaran Baru' ?></span></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<div class="page-subtitle">
					<div class="col-md-12">
						<h3><?php echo lang('outstanding_payment:invoice_data_subtitle') ?></h3>
						<p><?php echo lang('outstanding_payment:invoice_data_subtitle_helper') ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:evidence_number_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" required readonly>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:date_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="Jam" name="Jam" value="<?php echo date("Y-m-d H:m:s") ?>"  class="form-control" readonly />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:invoice_number_label') ?></label>
					<div class="col-lg-8">
						<?php if (@$is_edit): ?>
						<input type="text" id="NoInvoice" name="f[NoInvoice]" value="<?php echo @$item->NoInvoice ?>" placeholder="" class="form-control" readonly>
						<?php else: ?>
						<div class="input-group">
							<input type="text" id="NoInvoice" name="f[NoInvoice]" value="<?php echo @$item->NoInvoice ?>" placeholder="" class="form-control invoice" readonly>
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_invoice ?>" data-toggle="lookup-ajax-modal" class="btn btn-default btn-invoice" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_invoice" class="btn btn-default btn-clear btn-invoice" data-clear=".invoice" ><i class="fa fa-times"></i></a>
							</span>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:invoice_date_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="TanggalInvoice" name="p[TanggalInvoice]" value="<?php echo substr(@$item->TanggalInvoice, 0, 19 )?>" placeholder="" class="form-control invoice" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:registration_number_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" placeholder="" class="form-control invoice" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:nrm_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NRM" name="p[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control invoice" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:patient_name_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control invoice" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:patient_type_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="JenisKerjasama" name="p[JenisKerjasama]" value="<?php echo @$item->JenisKerjasama ?>" placeholder="" class="form-control invoice" readonly>
					</div>
				</div>
			</div>    
			
			<div class="col-md-8">
				<div class="page-subtitle">
					<div class="col-md-12">
						<h3><?php echo lang('outstanding_payment:payment_data_subtitle') ?></h3>
						<p><?php echo lang('outstanding_payment:payment_data_subtitle_helper') ?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:outstanding_value_label') ?></label>
							<div class="col-lg-8">
								<h4 class="text-right"><b id="NilaiAwal" class="invoice"><?php echo number_format(@$item->NilaiAwal, 2, '.', ',') ?></b></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang("outstanding_payment:accumulated_payment_label") ?></label>
							<div class="col-lg-8">
								<h4 class="text-right"><b id="NilaiAkumulaiPembayaran" class="invoice"><?php echo number_format(@$item->NilaiAkumulaiPembayaran, 2, '.', ',') ?></b></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:obligation_label') ?></label>
							<div class="col-lg-8">
								<h4 class="text-right"><b id="Obligation" class="invoice"><?php echo number_format(@$item->Obligation, 2, '.', ',') ?></b></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:payment_value_label') ?></label>
							<div class="col-lg-8">
								<input type="text" id="NilaiPembayaran" name="f[NilaiPembayaran]" value="<?php echo number_format(@$item->NilaiPembayaran, 2, '.', ',') ?>" placeholder="" class="form-control text-right" autofocus>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:remain_label') ?></label>
							<div class="col-lg-8">
								<h4 class="text-right text-danger"><b id="Remain" class="invoice"><?php echo number_format(@$item->Remain, 2, '.', ',') ?></b></h4>
							</div>
						</div>
					</div>
		
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="payment_type" id="Tunai" value="1" <?php echo ($item->Tunai) ? "checked" : NULL ?>>
										<label for="Tunai"><?php echo lang("outstanding_payment:cash_label")?></label>
									</div>
								</div>
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="payment_type" id="BRITunai" value="1" <?php echo ($item->BRITunai) ? "checked" : NULL ?>>
										<label for="BRITunai"><?php //echo lang("outstanding_payment:cash_label")?> BRI Tunai</label>
									</div>
								</div>
							</div>
							<div class="col-lg-7">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="payment_type" id="CC" value="1" <?php echo ($item->CC) ? "checked" : NULL ?>>
										<label for="CC"><?php echo lang("outstanding_payment:credit_card_label")?></label>
									</div>
									<div class="input-group">
										<input type="hidden" id="IDBank"  value="<?php echo @$item->IDBank ?>" placeholder="" class="form-control merchan">
										<input type="text" id="NamaBank"  value="<?php echo @$item->NamaBank ?>" placeholder="" class="form-control merchan">
										<span class="input-group-btn">
											<a href="<?php echo @$lookup_merchan ?>" data-toggle="lookup-ajax-modal" class="btn btn-default btn-merchan" ><i class="fa fa-search"></i></a>
											<a href="javascript:;" id="clear_merchan" class="btn btn-default btn-clear btn-merchan" data-clear=".merchan" ><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<input type="text" id="NoKartu" value="<?php echo @$item->NoKartu ?>" placeholder="" class="form-control merchan">
										<span class="input-group-btn">
											<a href="javascript:;"  class="btn btn-default">No</a>
										</span>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<input type="text" id="AddCharge_Persen" value="<?php echo @$item->AddCharge_Persen ?>" placeholder="" class="form-control merchan">
										<span class="input-group-btn">
											<a href="javascript:;"  class="btn btn-default"><i class="fa fa-percent"></i></a>
											<a href="javascript:;"  class="btn btn-default"><?php echo lang("outstanding_payment:add_charge_label") ?></a>
										</span>
									</div>
								</div>
							</div>    
						</div>    
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="row form-group">
			<div class="col-lg-4">
				<div class="form-group">
					<?php if (@$is_edit): ?>
					<div class="col-lg-4">
						<?php /*?><a href="<?php echo $print_billing_link ?>" id="print_billing" target="_blank" class="btn btn-info btn-block"><b><i class="fa fa-search"></i> <?php echo lang("buttons:preview") ?></b></a><?php */?>
					</div>
					<div class="col-lg-8">
						<a href="<?php echo $print_kwitansi_link ?>" id="print_receipt" target="_blank" class="btn btn-primary btn-block"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a>
					</div>                
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="form-group text-right"> 

					<button type="reset" class="btn btn-warning"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:reset") ?></b></button>
					<?php if (@$is_edit): ?>
					<a href="<?php echo $cancel_payment_link ?>" id="cancel" data-toggle="ajax-modal" class="btn btn-danger"><b><i class="fa fa-times"></i> <?php echo lang("buttons:cancel") ?></b></a>
					<?php endif; ?>
					<a href="<?php echo base_url("cashier/outstanding-payment/pay")?>" class="btn btn-info"><i class="fa fa-file"></i> <?php echo lang( 'buttons:create' ) ?></a>
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
				
				// --> Start Masking Pembayaran
				$("#NilaiPembayaran").on("keyup", function(e){		
					Obligation = parseFloat($("#Obligation").html().replace(/[^0-9\.-]+/g,"")).toFixed( 2 );
						
					NilaiPembayaran = parseFloat($(this).val().replace(/[^0-9\.-]+/g,"")) || 0;
					Remain = Obligation - NilaiPembayaran;
					console.log("Kewajiban :", Obligation, " Bayar : ", NilaiPembayaran, " Remain: ", Remain );
					$("#Remain").html(parseFloat(Remain).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
					
				});
				
				$("#NilaiPembayaran").on("focus", function(){
					val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
					if ( val == 0)
					{
						$(this).val("")
					}
                });

				$("#NilaiPembayaran").on("blur",function(){
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
				
				// --> Start Radio Button Kartu Kredit
				if ( $("#CC:checked").val() )
				{
					$(".merchan").prop("disabled", false);
					$(".btn-merchan").removeClass("disabled");
				} else {
					$(".merchan").prop("disabled", true);					
					$(".btn-merchan").addClass("disabled");
				}
				
				$('input[type=radio][name=payment_type]').on("change", function(){
					if ( $(this).prop("id") == "CC")
					{
						$(".merchan").prop("disabled", false);
						$(".btn-merchan").removeClass("disabled");
					} else {
						$(".merchan").prop("disabled", true);					
						$(".btn-merchan").addClass("disabled");
					}
				});
				// --> End Radio Button Kartu Kredit
				
				// --> Start Clear Button
				$(".btn-clear").on("click", function(e){
					var classClear = $(this).data("clear");
					
					$( classClear ).val("");
					$( classClear ).html("");
				});
				// --> End Clear Buttson
				
				$("form[name=\"form_outstanding_payment\"]").on("submit", function(e){
					e.preventDefault();
					
					// menhapus tanda baca currency
					$("#NilaiAwal, #NilaiAkumulaiPembayaran").each(function(index, element) {
						val = parseFloat($(this).html().replace(/[^0-9\.-]+/g,""));
						$(this).html(val);
                    });

					$("#NilaiPembayaran").each(function(index, element) {
						val = parseFloat(element.value.replace(/[^0-9\.-]+/g,""));
						$(this).val(val);
                    });
				
					if ( !confirm("Apakah Anda yakin akan menyimpan data ini ?"))
					{
						// mengembalikan tanda baca currency
						$("#NilaiAwal, #NilaiAkumulaiPembayaran").each(function(index, element) {
							val = parseFloat($(this).html().replace(/[^0-9\.-]+/g,""));
							var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
							$(this).html( mask );
						});

						$("#NilaiPembayaran").each(function(index, element) {
							val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
							var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
							$(this).val( mask );
						});

						return false;
					}
					var data_post = {}
						data_post['p'] = {
						"NRM" : $("#NRM").val(),
						"NamaPasien" : $("#NamaPasien").val(),
						"JenisKerjasama" : $("#JenisKerjasama").val(),
					};

						data_post['f'] = {
						"NoBukti" : $("#NoBukti").val(),
						"NoInvoice" : $("#NoInvoice").val(),
						"NoReg" : $("#NoReg").val(),
						"NilaiAwal" : $("#NilaiAwal").html(),
						"NilaiAkumulaiPembayaran" : $("#NilaiAkumulaiPembayaran").html(),
						"NilaiPembayaran" : $("#NilaiPembayaran").val(),
						"Tunai" : $("#Tunai:checked").val() || 0,
						"BRITunai" : $("#BRITunai:checked").val() || 0,
						"CC" : $("#CC:checked").val() || 0,
					};

					
					if ( $("#CC:checked").val() )
					{
						data_post['f']['IDBank'] = $("#IDBank").val();
						data_post['f']['NoKartu'] = $("#NoKartu").val();
						data_post['f']['AddCharge_Persen'] = $("#AddCharge_Persen").val();
						
						AddCharge = parseFloat($("#NilaiPembayaran").val()).toFixed(2) * parseFloat($("#AddCharge_Persen").val()).toFixed(2) / 100;
						data_post['f']['AddCharge'] = AddCharge;
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
								document.location.href = "<?php echo base_url("cashier/outstanding-payment/edit/?NoBukti="); ?>"+ NoBukti;
								}, 3000 );
						})	
				});
			
		 });
	})( jQuery );
//]]>
</script>