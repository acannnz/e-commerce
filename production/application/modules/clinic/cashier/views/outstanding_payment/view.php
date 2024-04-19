<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo form_open( current_url(), array("name" => "form_outstanding_payment", "id"=>"form_outstanding_payment") ); ?>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('non_invoice_receipt:view_heading') ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("cashier/non-invoice-receipt/create") ?>" title="<?php echo lang('buttons:create') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:create') ?></span></a></li>
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
						<input type="text" id="NoInvoice" name="f[NoInvoice]" value="<?php echo @$item->NoInvoice ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:invoice_date_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="TanggalInvoice" name="p[TanggalInvoice]" value="<?php echo substr(@$item->TanggalInvoice, 0, 19 )?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:registration_number_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:nrm_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NRM" name="p[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:patient_name_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:patient_type_label') ?></label>
					<div class="col-lg-8">
						<input type="text" id="JenisKerjasama" name="p[JenisKerjasama]" value="<?php echo @$item->JenisKerjasama ?>" placeholder="" class="form-control" readonly>
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
								<h4 class="text-right"><b id="NilaiAwal" ><?php echo number_format(@$item->NilaiAwal, 2, '.', ',') ?></b></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang("outstanding_payment:accumulated_payment_label") ?></label>
							<div class="col-lg-8">
								<h4 class="text-right"><b id="NilaiAkumulaiPembayaran"><?php echo number_format(@$item->NilaiAkumulaiPembayaran, 2, '.', ',') ?></b></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:obligation_label') ?></label>
							<div class="col-lg-8">
								<h4 class="text-right"><b id="Obligation"><?php echo number_format(@$item->Obligation, 2, '.', ',') ?></b></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:payment_value_label') ?></label>
							<div class="col-lg-8">
								<input type="text" id="NilaiPembayaran" name="f[NilaiPembayaran]" value="<?php echo number_format(@$item->NilaiPembayaran, 2, '.', ',') ?>" placeholder="" class="form-control text-right" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label"><?php echo lang('outstanding_payment:remain_label') ?></label>
							<div class="col-lg-8">
								<h4 class="text-right text-danger"><b id="Remain"><?php echo number_format(@$item->Remain, 2, '.', ',') ?></b></h4>
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
									<input type="text" id="NamaBank"  value="<?php echo @$item->NamaBank ?>" placeholder="" class="form-control merchan" readonly>
								</div>
								<div class="form-group">
									<div class="input-group">
										<input type="text" id="NoKartu" value="<?php echo @$item->NoKartu ?>" placeholder="" class="form-control merchan" readonly>
										<span class="input-group-btn">
											<a href="javascript:;"  class="btn btn-default">No</a>
										</span>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<input type="text" id="AddCharge_Persen" value="<?php echo @$item->AddCharge_Persen ?>" placeholder="" class="form-control merchan" readonly>
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
					<div class="col-lg-offset-4 col-lg-8">
						<a href="<?php echo $print_kwitansi_link ?>" target="_blank" class="btn btn-primary btn-block"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a>
						<?php /*?><a href="javascript:;" id="dp_billing" class="btn btn-primary btn-block"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a><?php */?>
					</div>                
				</div>
			</div>
			<div class="col-lg-4">
			</div>
			<div class="col-lg-4">
				<div class="form-group text-right"> 
					<button type="reset" class="btn btn-warning"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:reset") ?></b></button>
					<a href="<?php echo $cancel_payment_link ?>" id="cancel" data-toggle="ajax-modal" class="btn btn-danger"><b><i class="fa fa-times"></i> <?php echo lang("buttons:cancel") ?></b></a>
					<a href="<?php echo base_url("cashier/outstanding-payment/pay")?>" class="btn btn-default"><?php echo lang( 'buttons:create' ) ?></a>
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
				
				$("form[name=\"form_outstanding_payment\"]").on("submit", function(e){
					e.preventDefault();
					
					
					
					return false;
				});
			
		 });
	})( jQuery );
//]]>
</script>