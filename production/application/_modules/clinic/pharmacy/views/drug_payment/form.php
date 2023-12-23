<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 ?>

<?php echo form_open( current_url(), array("name" => "form_drug_payment", "id"=>"form_drug_payment") ); ?>
<div class="panel panel-info panel-collapsed">
	<div class="panel-heading panel-collapse">
		<h3 class="panel-title"><?php echo lang('drug_payment:patient_label') ?></h3>
		<ul class="panel-btn">
			<li><a href="javascript:;" class="btn btn-info panel-collapse" title="Tampilkan"><i class="fa fa-angle-up"></i></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<input type="hidden" name="f[DokterID]" value="<?php echo $pharmacy->DokterID ?>"/>
		<?php if(config_item('allow_change_transaction_date')): ?>
		<input type="hidden" name="f[Tanggal]" value="<?php echo $pharmacy->Tanggal ?>"/>
		<input type="hidden" name="f[Jam]" value="<?php echo $pharmacy->Jam ?>"/>
		<?php endif;?>
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="page-subtitle">
					<h3><?php echo lang('drug_payment:pharmacy_data_subtitle') ?></h3>
					<p><?php echo lang('drug_payment:pharmacy_data_subtitle_helper') ?></p>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:evidence_number_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" required readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:date_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="Jam" name="f[Jam]" value="<?php echo (config_item('allow_change_transaction_date')) ? substr(@$pharmacy->Jam, 0, 19 ) : date("Y-m-d H:m:s"); ?>"  class="form-control" readonly />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:pharmacy_number_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NoBuktiFarmasi" name="f[NoBuktiFarmasi]" value="<?php echo @$item->NoBuktiFarmasi ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:pharmacy_date_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="TanggalFarmasi" name="p[TanggalFarmasi]" value="<?php echo substr(@$pharmacy->Jam, 0, 19 )?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="page-subtitle">
					<h3><?php echo lang('drug_payment:patient_label') ?></h3>
					<p><?php echo lang('drug_payment:pharmacy_data_subtitle_helper') ?></p>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:patient_name_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="Keterangan" name="f[Keterangan]" value="<?php echo @$pharmacy->Keterangan ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:patient_type_label') ?></label>
					<div class="col-lg-8">
						<input type="text" value="<?= @$pharmacy->TipePasien ?>" class="form-control" readonly>
						<input type="hidden" id="JenisKerjasama" name="p[JenisKerjasama]" value="<?php echo @$pharmacy->JenisKerjasama ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:card_number_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NoKartu" name="p[NoKartu]" value="<?php echo @$pharmacy->NoKartu ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:company_code_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="KodeCustomerPenjamin" name="f[KodeCustomerPenjamin]" value="<?php echo @$pharmacy->KodePerusahaan ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
			</div>    
		</div>
	</div>
</div>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('drug_payment:pay_heading') ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">			
			<div class="col-md-4">
				<div class="page-subtitle">
					<h3><?php echo lang('drug_payment:drug_data_subtitle') ?></h3>
					<p><?php echo lang('drug_payment:drug_data_subtitle_helper') ?></p>
				</div>
				<div class="table-responsive">
					<table class="table table-sm">
						<thead>
							<tr>
								<th></th>
								<th><?php echo lang("global:description")?></th>
								<th><?php echo lang("drug_payment:unit_label")?></th>
								<th><?php echo lang("drug_payment:qty_label")?></th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1; if(!empty($pharmacy_detail)): foreach( $pharmacy_detail as $row ):
								if($row->JmlObat - $row->JmlRetur == 0){continue;}
							?>
							<tr>
								<td align="center" width="50px"><?php echo $no++ ?></td>
								<td><?php echo $row->Barang_ID == 0 ? $row->NamaResepObat : $row->Nama_Barang ?></td>
								<td><?php echo ($row->NamaResepObat == $row->Nama_Barang) ? $row->Satuan : $row->NamaResepObat ?></td>
								<td align="center"><?php echo $row->JmlObat ?></td>
							</tr>
							<?php endforeach; endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="page-subtitle">
					<h3><?php echo lang('drug_payment:payment_data_subtitle') ?></h3>
					<p><?php echo lang('drug_payment:payment_data_subtitle_helper') ?></p>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang('drug_payment:transaction_value_label') ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiTransaksi" name="f[NilaiTransaksi]" value="<?php echo @$pharmacy->Total ?>" placeholder="" class="form-control text-right mask-number" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:cash_label") ?></label>
					<div class="col-lg-7">
						<input type="hidden" id="NilaiPembayaran" name="f[NilaiPembayaran]" value="<?php echo @$item->NilaiPembayaran ?>" placeholder="" class="form-control text-right mask-number">
						<input type="text" id="JumlahBayar" name="f[JumlahBayar]" value="<?php echo @$item->JumlahBayar ?>" placeholder="" class="form-control text-right  mask-number" <?= (@$is_edit) ? null : 'autofocus' ?> autocomplete="off">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:change_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiKembalian" name="f[NilaiKembalian]" value="<?php echo @$item->NilaiKembalian ?>" placeholder="" class="form-control text-right mask-number" readonly>
					</div>
				</div>
				<div class="col-md-12">
					<table class="table">
						<tr>
							<td><?php echo lang("drug_payment:sub_total_label")?></td>
							<td id="sub_total_pay" class="text-right"><?php echo number_format(@$item->NilaiTransaksi, 2, '.', ',') ?></td>
						</tr>
						<tr>
							<td><?php echo lang("drug_payment:add_charge_label")?></td>
							<td id="add_charge_pay" class="text-right"><?php echo number_format(@$item->AddChargeValue, 2, '.', ',') ?></td>
						</tr>
						<tr>
							<td><?php echo lang("drug_payment:grand_total_label")?></td>
							<td id="grand_total_pay" class="text-right"><?php echo number_format(@$item->GrandTotal, 2, '.', ',') ?></td>
						</tr>
					</table>
				</div>
			</div>
			<div class="col-md-4">
				<div class="page-subtitle">
					<h3><?php echo lang('drug_payment:payment_method_subtitle') ?></h3>
					<p><?php echo lang('drug_payment:payment_method_subtitle_helper') ?></p>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:billed_company_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiPembayaranIKS" data-jenisid='2' name="f[NilaiPembayaranIKS]" value="<?php echo number_format(@$item->NilaiPembayaranIKS, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay mask-number" autocomplete="off">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:debt_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="Kredit" name="f[Kredit]" value="<?php echo number_format(@$item->Kredit, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay mask-number" autocomplete="off">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:billed_bpjs_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiPembayaranBPJS" data-jenisid='9' name="f[NilaiPembayaranBPJS]" value="<?php echo number_format(@$item->NilaiPembayaranBPJS, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay mask-number" autocomplete="off">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:hospital_expenses_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiPembayaranBebanRS" name="f[NilaiPembayaranBebanRS]" value="<?php echo number_format(@$item->NilaiPembayaranBebanRS, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay mask-number" autocomplete="off">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:credit_card_label") ?></label>
					<div class="col-lg-7">	
						<div class="input-group">
							<input type="text" id="NilaiPembayaranCC" name="f[NilaiPembayaranCC]" value="<?php echo number_format(@$item->NilaiPembayaranCC, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay mask-number" autocomplete="off">
							<div class="input-group-addon"></div>
							<input type="number" name="f[AddCharge]" id="AddCharge" value="<?php echo @$item->AddCharge ?>" min="0" step=".5" class="form-control merchan text-right">
							<span class="input-group-btn">
								<a href="javascript:;"  class="btn btn-default"><i class="fa fa-percent"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang('drug_payment:merchan_label') ?></label>
					<div class="col-lg-7">
						<div class="input-group">
							<input type="hidden" name="f[IDBank]" id="IDBank"  value="<?php echo @$item->IDBank ?>" placeholder="" class="form-control merchan">
							<input type="text" id="NamaBank"  value="<?php echo @$merchan->NamaBank ?>" placeholder="" class="form-control merchan">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_merchan ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_merchan" class="btn btn-default btn-clear" data-clear="merchan" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				
				<?php if(@$item->Audit == 1): ?>
				<div class="form-group">
					<hr/>
					<h3 class="text-danger">Data Sudah diVerifikasi</h3>
					<p>Data Tidak Dapat Di Edit dan DiBatalkan</p>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<?php if(@$is_edit):?>
				<a href="<?php echo $cancel_payment_link ?>" id="cancel" data-toggle="ajax-modal" class="btn btn-danger btn-block"><b><i class="fa fa-eye"></i> <?php echo lang("buttons:cancel") ?></b></a>
				<?php endif;?>
			</div>
			<div class="col-lg-4">
				<?php if(@$is_edit):?>
				<a href="javascript:;" id="dp_billing" class="btn btn-primary btn-block"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a>
				<?php endif;?>
			</div>
			<div class="col-lg-4">
				<button type="submit" class="btn btn-primary btn-block"><b><i class="fa fa-file"></i> <?php echo lang( 'buttons:submit' ) ?></b></button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var form_actions = {
				init: function(){
					$(".pay, #JumlahBayar, #AddCharge").on("keyup", function(e){
						
						JenisKerjasamaID = $(this).data("jenisid");
						if ( JenisKerjasamaID == 9 && JenisKerjasamaID != <?php echo (int) @$pharmacy->KerjasamaID ?> )
						{
							e.preventDefault();
							alert("Tidak Dapat Menggunakan Tipe Pembayaran, Karena berbeda tipe pasien");
							$(this).val(0.00);
							return false;
						}
		
						if ( JenisKerjasamaID == 2 && JenisKerjasamaID != <?php echo (int) @$pharmacy->KerjasamaID ?> )
						{
							e.preventDefault();
							alert("Tidak Dapat Menggunakan Tipe Pembayaran, Karena berbeda tipe pasien");
							$(this).val(0.00);
							return false;
						}
						
						form_actions.calculate_payment();						
					});
					
				},
				calculate_payment : function(){
					var NilaiTransaksi = $("#NilaiTransaksi");
					var NilaiPembayaran = $("#NilaiPembayaran");
					var JumlahBayar = $("#JumlahBayar");
					var NilaiKembalian = $("#NilaiKembalian");
					var SubTotalPembayaran = $("#sub_total_pay");
					var AddChargePay = $("#add_charge_pay");
					var GrandTotalPembayaran = $("#grand_total_pay");
					var Total = SubTotal = addCharge = 0 , Sisa_ = 0, TaxCC_ = 0;
					
					$(".pay").each(function(index, element) {
						sub_total = mask_number.currency_remove( element.value );
						SubTotal = SubTotal + sub_total;
					});
					console.log('SubTotal', SubTotal);
					var JumlahBayar_ = mask_number.currency_remove(JumlahBayar.val());
					var NilaiTransaksi_ = mask_number.currency_remove(NilaiTransaksi.val());
					var SubTotalPembayaran_ = SubTotal + JumlahBayar_;
					SubTotalPembayaran_ = SubTotalPembayaran_ > NilaiTransaksi_ ? NilaiTransaksi_ : SubTotalPembayaran_;					
					SubTotalPembayaran.html( mask_number.currency_add(SubTotalPembayaran_) );	
					console.log('SubTotalPembayaran_', SubTotalPembayaran_);
					AddChargePay_ = mask_number.currency_remove( $("#NilaiPembayaranCC").val() ) * parseFloat( $("#AddCharge").val() ) / 100;
					Total = SubTotalPembayaran_ + AddChargePay_;	
					
					AddChargePay.html( mask_number.currency_add(AddChargePay_) );
					GrandTotalPembayaran.html( mask_number.currency_add(Total) );
					
					NilaiKembalian.val(0.00);
					var NilaiKembalian_ = JumlahBayar_ - (NilaiTransaksi_ - SubTotal) || 0;
					if( JumlahBayar_ > 0 && NilaiKembalian_ > 0 && JumlahBayar_ > NilaiKembalian_){
						NilaiKembalian.val(mask_number.currency_add(NilaiKembalian_));
					}
					console.log('Kembalian', NilaiKembalian_);
					NilaiKembalian_  = mask_number.currency_remove(NilaiKembalian.val());
					NilaiPembayaran.val(mask_number.currency_add(JumlahBayar_ - NilaiKembalian_));
					
					/*var JumlahBayar_ = mask_number.currency_remove(JumlahBayar.val());
					var Pembayaran_ = Total + JumlahBayar_;
					Pembayaran_ = Pembayaran_ > GrandTotal_ ? GrandTotal_ : Pembayaran_;
					Pembayaran.val(mask_number.currency_add( Pembayaran_ ));
					
					var Sisa_ = GrandTotal_ - Pembayaran_;
					Sisa.val( mask_number.currency_add(Sisa_));
					
					NilaiKembalian.val(0.00);
					var NilaiKembalian_ = JumlahBayar_ - (GrandTotal_ - Total) || 0;
					if( JumlahBayar_ > 0 && NilaiKembalian_ > 0 && JumlahBayar_ > NilaiKembalian_){
						NilaiKembalian.val(mask_number.currency_add(NilaiKembalian_));
					}
					
					NilaiKembalian_  = mask_number.currency_remove(NilaiKembalian.val());
					Tunai.val(mask_number.currency_add(JumlahBayar_ - NilaiKembalian_));*/
				},
			};
	
		$( document ).ready(function(e) {	
				
				form_actions.init();
				<?php if(@$is_modal):?>
				mask_number.init();
				<?php endif;?>
				
				<?php if(@$is_edit):?>
				form_actions.calculate_payment();
				<?php endif;?>
				
				$("form[name=\"form_drug_payment\"]").on("submit", function(e){

					$("#NilaiTransaksi, #NilaiPembayaran, #JumlahBayar, #NilaiKembalian, .pay").each(function(index, element) {
						$(this).val( mask_number.currency_remove( element.value ) );
                    });
					
					if( $('#NilaiPembayaranCC').val() > 0 && ($('#IDBank').val() == '' || $('#IDBank').val() == null))
					{				
						$.alert_error('Pembayaran tidak dapat diproses, karena Merchan Bank belum dipilih!');	
						$("#NilaiTransaksi, #NilaiPembayaran, #JumlahBayar, #NilaiKembalian, .pay").each(function(index, element) {
							$(this).val( mask_number.currency_add( element.value ) );
						});
						return false;
					}
				
					if ( !confirm("Apakah Anda yakin akan menyimpan data ini ?"))
					{
						$("#NilaiTransaksi, #NilaiPembayaran, #JumlahBayar, #NilaiKembalian, .pay").each(function(index, element) {
							$(this).val( mask_number.currency_add( element.value ) );
						});
						return false;
					}

				});
			
				// DP = Direct Print
				$("#dp_billing").on("click", function(e){					
					data_post = {
						"NoBukti" : '<?php echo $item->NoBuktiFarmasi?>'
					}					
					$.post('<?= @$print_billing_link ?>', data_post, function(response, status, xhr) {
						if ("error" == response.status) {
							$.alert_error(response.status);
							return false
						}

						// $.alert_success('Berhasil mencetak.');
						printJS({
							printable: response.data_print,
							type: 'pdf',
							base64: true
						});
					});				
				});	 
				<?php /* $("#dp_billing").on("click", function(e){					
					data_post = {
						"NoBuktiFarmasi" : '<?php echo $item->NoBuktiFarmasi?>'
					}					
					$.post( "<?php echo @$dp_billing_link ?>", data_post, function(response, status, xhr){
						var _response = $.parseJSON(response);
						console.log(_response.status)
						if( "error" == _response.status ){
							$.alert_error(_response.message);
							return false
						}						
						$.alert_success(_response.message);
					});					
				});	
				*/ ?>
		 });


	})( jQuery );
//]]>
</script>