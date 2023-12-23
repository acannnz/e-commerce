<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo form_open( current_url(), array("name" => "form_drug_payment", "id"=>"form_drug_payment") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('drug_payment:view_heading') ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
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
						<input type="text" id="Jam" name="f[Jam]" value="<?php echo date("Y-m-d H:m:s") ?>"  class="form-control" readonly />
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
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:patient_name_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="Keterangan" name="f[Keterangan]" value="<?php echo @$pharmacy->Keterangan ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('drug_payment:patient_type_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="JenisKerjasama" name="p[JenisKerjasama]" value="<?php echo @$pharmacy->JenisKerjasama ?>" placeholder="" class="form-control" readonly>
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
							<?php $no = 1; if(!empty($pharmacy_detail)): foreach( $pharmacy_detail as $row ) :?>
							<tr>
								<td align="center" width="50px"><?php echo $no++ ?></td>
								<td><?php echo $row->Barang_ID == 0 ? $row->NamaResepObat : $row->Nama_Barang ?></td>
								<td><?php echo $row->Satuan ?></td>
								<td align="center"><?php echo $row->JmlObat ?></td>
							</tr>
							<?php endforeach; endif; ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<table class="table">
						<tr>
							<td><?php echo lang("drug_payment:sub_total_label")?></td>
							<td id="sub_total_pay" class="text-right"><?php echo number_format($item->NilaiTransaksi, 2, '.', ',') ?></td>
						</tr>
						<tr>
							<td><?php echo lang("drug_payment:add_charge_label")?></td>
							<td id="add_charge_pay" class="text-right"><?php echo number_format($item->AddChargeValue, 2, '.', ',') ?></td>
						</tr>
						<tr>
							<td><?php echo lang("drug_payment:grand_total_label")?></td>
							<td id="grand_total_pay" class="text-right"><?php echo number_format($item->GrandTotal, 2, '.', ',') ?></td>
						</tr>
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
						<input type="text" id="NilaiTransaksi" name="f[NilaiTransaksi]" value="<?php echo number_format(@$pharmacy->Total, 2, '.', ',') ?>" placeholder="" class="form-control text-right" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:cash_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiPembayaran" name="f[NilaiPembayaran]" value="<?php echo number_format(@$item->NilaiPembayaran, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay"  readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:billed_company_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiPembayaranIKS" name="f[NilaiPembayaranIKS]" value="<?php echo number_format(@$item->NilaiPembayaranIKS, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay"  readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:debt_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="Kredit" name="f[Kredit]" value="<?php echo number_format(@$item->Kredit, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay"  readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:billed_bpjs_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiPembayaranBPJS" name="f[NilaiPembayaranBPJS]" value="<?php echo number_format(@$item->NilaiPembayaranBPJS, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay"  readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:hospital_expenses_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiPembayaranBebanRS" name="f[NilaiPembayaranBebanRS]" value="<?php echo number_format(@$item->NilaiPembayaranBebanRS, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay"  readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang("drug_payment:credit_card_label") ?></label>
					<div class="col-lg-7">
						<input type="text" id="NilaiPembayaranCC" name="f[NilaiPembayaranCC]" value="<?php echo number_format(@$item->NilaiPembayaranCC, 2, '.', ',') ?>" placeholder="" class="form-control text-right pay"  readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"><?php echo lang('drug_payment:merchan_label') ?></label>
					<div class="col-lg-7">
						<div class="input-group">
							<input type="hidden" id="IDBank"  value="<?php echo @$item->IDBank ?>" placeholder="" class="form-control merchan">
							<input type="text" id="NamaBank"  value="<?php echo @$merchan->NamaBank ?>" placeholder="" class="form-control merchan" readonly>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-5 col-lg-7">
						<div class="input-group">
							<input type="text" id="AddCharge" value="<?php echo @$item->AddCharge ?>" placeholder="" class="form-control merchan" readonly>
							<span class="input-group-btn">
								<a href="javascript:;"  class="btn btn-default"><i class="fa fa-percent"></i></a>
								<a href="javascript:;"  class="btn btn-default"><?php echo lang("drug_payment:add_charge_label") ?></a>
							</span>
						</div>
					</div>
				</div>
				<?php if(@$item->Audit == 1): ?>
				<div class="page-subtitle">
					<h3 class="text-danger">Data Sudah diVerifikasi</h3>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<hr/>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<div class="col-lg-offset-4 col-lg-8">
						<a href="javascript:;" id="dp_billing" class="btn btn-primary btn-block"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a>
					</div>                
				</div>
			</div>
			<div class="col-lg-4">
				
			</div>
			<div class="col-lg-4">
				<div class="form-group text-right"> 
					<a href="<?php echo $cancel_payment_link ?>" id="cancel" data-toggle="ajax-modal" class="btn btn-danger"><b><i class="fa fa-eye"></i> <?php echo lang("buttons:cancel") ?></b></a>
					<button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:back' ) ?></button>
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
				
				var total = 0;
				$(".pay").each(function(index, element) {
					sub_total = parseFloat(element.value.replace(/[^0-9\.-]+/g,""));
					total = parseFloat(total) + sub_total;
				});
				
				$("#total_pay").html(parseFloat(total).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));		
				
				// DP = Direct Print
				$("#dp_billing").on("click", function(e){
					
					data_post = {
						"NoBuktiFarmasi" : '<?php echo $item->NoBuktiFarmasi?>'
					}
					
					$.post( "<?php echo $dp_billing_link ?>", data_post, function(response, status, xhr){
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success(response.message);
					});
					
				});
				
		 });


	})( jQuery );
//]]>
</script>