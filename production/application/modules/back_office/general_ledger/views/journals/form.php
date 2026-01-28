<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//	print_r($item);exit;
?>
<?php echo form_open( current_url(), array("name" => "form_general_ledger") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('journals:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('journals:date_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" id="Transaksi_Date" name="f[Transaksi_Date]" value="<?php echo @$item->Transaksi_Date ?>" data-date-min-date="<?php echo config_item('Tanggal Mulai System') ?>" placeholder="" class="form-control datepicker" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('journals:journal_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" id="No_Bukti" name="No_Bukti" value="<?php echo @$item->No_Bukti ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?php echo lang('journals:notes_label') ?></label>
					<div class="col-lg-8">
						<textarea id="Keterangan" name="f[Keterangan]" placeholder="" wrap="virtual" class="form-control"><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-4 control-label"><?php echo lang('journals:currency_label') ?></label>
					<div class="col-md-8">
						<select id="Currency_ID" name="f[Currency_ID]" class="form-control" required>
							<?php if (!empty($option_currency)) : foreach($option_currency as $k => $v) : ?>
							<option value="<?php echo @$k ?>" <?php echo (@$k == @$item->Currency_ID) ? "selected" : null ?> > <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>    
				<div class="form-group">
					<label class="col-md-4 control-label"><?php echo lang('journals:division_label') ?></label>
					<div class="col-md-8">
						<select id="DivisiID" name="f[DivisiID]" class="form-control">
							<option value=""><?php echo lang('global:select-none')?></option>
							<?php if (!empty($option_division)) : foreach($option_division as $k => $v) : ?>
							<option value="<?php echo @$k ?>" <?php echo (@$k == @$item->DivisiID) ? "selected" : null ?> > <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>    
				<div class="form-group">
					<label class="col-md-4 control-label"><?php echo lang('journals:project_label') ?></label>
					<div class="col-md-8">
						<select id="Kode_Proyek" name="f[Kode_Proyek]" class="form-control">
							<option value=""><?php echo lang('global:select-none')?></option>
							<?php if (!empty($option_project)) : foreach($option_project as $k => $v) : ?>
							<option value="<?php echo @$k ?>" <?php echo (@$k == @$item->Kode_Proyek) ? "selected" : null ?> > <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>    
			</div>
		</div>

		<div class="page-subtitle">
			<h2 class="text-info"><i class="fa fa-sitemap text-info"></i> <?php echo lang('journals:accounts_details_sub') ?></h2>
		</div>
		<div class="row">
			<?php echo  modules::run("general_ledger/journals/details", @$item ) ?>
		</div>
		
		<div class="page-subtitle">
			<h2 class="text-info"><i class="fa fa-money text-info"></i> <?php echo lang('journals:total_summary_sub') ?></h2>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="col-md-4">
					<label class="col-lg-12 control-label"><?php echo lang('journals:debit_label') ?></label>
					<input type="text" id="Debit" name="f[Debit]" value="<?php echo (float) @$item->Debit ?>" placeholder="" class="form-control">
				</div>
				<div class="col-md-4">
					<label class="col-lg-12 control-label"><?php echo lang('journals:credit_label') ?></label>
					<input type="text" id="Kredit" name="f[Kredit]" value="<?php echo (float) @$item->Kredit ?>" placeholder="" class="form-control">
				</div>
				<div class="col-md-4">
					<label class="col-lg-12 control-label"><?php echo lang('journals:balance_label') ?></label>
					<input type="text" id="balance" name="f[balance]" value="<?php echo (float) @$item->balance ?>" placeholder="" class="form-control">
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-6">
				<a href="<?php echo base_url("general_ledger/journals/create")?>"  class="btn btn-primary"><b><?php echo lang( 'journals:save_recurring_label' ) ?></b></a>
				<a href="<?php echo base_url("general_ledger/journals/create")?>"  class="btn btn-primary"><b><?php echo lang( 'journals:use_recurring_label' ) ?></b></a>
			</div>
			<div class="col-lg-6 text-right">
				<?php if ( $item->Posted == 0 ) : ?>
				<button type="submit" id="btn-submit" class="btn btn-primary" disabled="disabled"><?php echo lang( 'buttons:submit' ) ?></button>
				<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
				<?php endif; ?>
				<a href="<?php echo base_url("general_ledger/journals/create")?>"  class="btn btn-success"><b><?php echo lang( 'buttons:create' ) ?></b></a>
				<?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
(function( $ ){
	
	$(document).ready(function(e) {
        
		$("form[name=\"form_general_ledger\"]").on("submit", function(e){
			e.preventDefault();	

			var data_post = {};
				data_post['f'] = {
						'Transaksi_Date' : $("#Transaksi_Date").val(),
						'Keterangan' : $("#Keterangan").val(),
						'Currency_ID' : $("#Currency_ID").val(),
						'DivisiID' : $("#DivisiID").val(),
						'Kode_Proyek' : $("#Kode_Proyek").val(),
						'Debit' : $("#Debit").val(),
						'Kredit' : $("#Kredit").val(),
					};					
				<?php if(@$is_edit): ?>
				data_post['f']['No_Bukti'] = $('#No_Bukti').val();
				<?php endif;?>
				data_post['details'] = {};
				
			var table_data = $( "#dt_journal_details" ).DataTable().rows().data();
			
			table_data.each(function (value, index) {
				var detail = {
					"Akun_ID": value.Akun_ID,
					"Debit"		: value.Debit,
					"Kredit"	: value.Kredit,
					"Keterangan"	: value.Keterangan,
					"SectionID"	: value.SectionID
				}
				
				data_post['details'][index] = detail;
			});
			
			console.log(data_post);
		
			$.post($(this).attr("action"), data_post, function( response, status, xhr ){
				
				if( "error" == response.status ){
					$.alert_error(response.message);
					return false
				}
				
				$.alert_success(response.message);
				
				setTimeout(function(){
											
					document.location.href = "<?php echo base_url("general-ledger/journals"); ?>";
					
					}, 300 );
				
			})	
		});
		
    });

})(jQuery)
</script>