<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($item->posted);exit;

?>
<?php echo form_open( $submit_url, array("name" => "form_cash_bank_mutation") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('cash_bank_mutation:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_mutation:date_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="Tgl_Transaksi" name="Tgl_Transaksi" value="<?php echo @$item->Tgl_Transaksi ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" placeholder="" <?php echo (@$is_edit) ? "readonly" : NULL ?> class="form-control datepicker" required>
					</div>
				</div>
				<?php if ( @$is_edit ): ?>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_mutation:evidence_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="No_Bukti" name="No_Bukti" value="<?php echo @$item->No_Bukti ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_mutation:description_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="Keterangan" placeholder="" class="form-control"  required><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-md-6"> 
				<div class="form-group">
					<h4 class="text-primary"><b><?php echo lang("cash_bank_mutation:account_origin_subtitle")?></b></h4>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_mutation:account_label') ?> <span class="text-danger">*</span></label>
					<input type="hidden" id="Akun_ID" name="Akun_ID" value="<?php echo @$item->Akun_ID ?>" class="form-control" />
					<div class="col-md-3">
						<input type="text" id="Akun_No" name="Akun_No"  value="<?php echo @$item->Akun_No ?>" class="form-control" readonly />
					</div>
					<div class="col-md-6 input-group">
						<input type="text" id="Akun_Name" name="Akun_Name" value="<?php echo @$item->Akun_Name ?>" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo $lookup_account_origin ?>" id="btn_lookup_account_origin" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title="" <?php echo (@$item->Status_Batal == 1) ? 'disabled' : NULL?>><i class="fa fa-gear"></i></a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_mutation:transfer_form_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NoBg" name="NoBg" value="<?php echo @$item->NoBg ?>" placeholder="" class="form-control" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_mutation:mutation_value_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="Kredit" name="Kredit" value="<?php echo number_format($item->Kredit, 2, ".", ",") ?>" placeholder="" class="form-control mask-number" >
					</div>
				</div>
				
				<div class="form-group">
					<h3 id="mutation_total" class="pull-right text-danger"><?php echo "Rp. ".number_format($item->Kredit, 2, ".", ","); ?></h3>
				</div>        
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-9">
						<?php if (@$item->Posted) : ?>
							<h3  class="text-danger"><?php echo lang("cash_bank_mutation:posted_data")?></h3>
						<?php endif;
							if (@$item->Status_Batal) :?>
							<h3  class="text-danger"><?php echo lang("cash_bank_mutation:cancel_data")?></h3>
						<?php endif; ?>
					</div>
				</div>        
			</div>
			<div class="col-md-6"> 
				<div class="form-group">
					<h4 class="text-primary"><b><?php echo lang("cash_bank_mutation:account_destination_subtitle")?></b></h4>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('cash_bank_mutation:account_label') ?> <span class="text-danger">*</span></label>
					<input type="hidden" id="Destination_Akun_ID" name="Destination_Akun_ID" value="<?php echo @$item->Destination_Akun_ID ?>" class="form-control" />
					<div class="col-md-3">
						<input type="text" id="Destination_Akun_No" name="Destination_Akun_No"  value="<?php echo @$item->Destination_Akun_No ?>" class="form-control" readonly />
					</div>
					<div class="col-md-6 input-group">
						<input type="text" id="Destination_Akun_Name" name="Destination_Akun_Name" value="<?php echo @$item->Destination_Akun_Name ?>" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo $lookup_account_destination ?>" id="btn_lookup_account_origin" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title="" <?php echo (@$item->Status_Batal == 1) ? 'disabled' : NULL?>><i class="fa fa-gear"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		
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
(function( $ ){

	form_actions = {
		calculate_balance : function(){
			
			var _this = $("#Kredit"),
				_mutation_total = $("#mutation_total");
			
			_this.on("focus",function(){
					var val = parseFloat( mask_number.currency_remove( $(this).val() ) );
					$(this).val( val );
					_mutation_total.html( 'Rp. '+ val );
				});
					
			_this.on("blur",function(){
					_mutation_total.html( 'Rp. '+ mask_number.currency_add($(this).val()) );
				});
	
		}
	}
//<![CDATA[
		$( document ).ready(function(e) {
				
				form_actions.calculate_balance();	
							
				var timer = 0;
				$("form[name=\"form_cash_bank_mutation\"]").on("submit", function(e){
					e.preventDefault();
					// untuk 
					if (timer) {
						clearTimeout(timer);
					}
					
					timer = setTimeout(postCashBankMutation, 400); 
						
				});
				
				function postCashBankMutation(){
					
					var data_post = {
							"form_cash_bank_mutation" : {},
							"detail" : {},
						};
					
					data_post.header = {
						"AkunBG_ID" : $("#Akun_ID").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Tgl_Transaksi"  : $("#Tgl_Transaksi").val(),
						"Tgl_Update" : $("#Tgl_Transaksi").val(),
						"Instansi" : $("#Instansi").val(),
						"NoBg" : $("#NoBg").val(),
						"Kredit" : mask_number.currency_remove( $("#Kredit").val() ),
					};
					
					data_post.detail = {
						"Akun_ID" : $("#Destination_Akun_ID").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Debet" : mask_number.currency_remove( $("#Kredit").val() ),
					};
					
					$.post($("form[name=\"form_cash_bank_mutation\"]").attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);

						if( response.status == "error"){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var No_Bukti = response.No_Bukti;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("general-cashier/cash-bank-mutation/edit"); ?>/?No_Bukti="+ No_Bukti ;
							
							}, 300 );
						
					});				
				}
								
			});
		
	})( jQuery );
//]]>
</script>

 
