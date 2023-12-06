 <?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_customer', 
		'name' => 'form_customer', 
		'rule' => 'form', 
		'class' => 'form-horizontal'
	]); ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">                
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <a href="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        <i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:vendor_update') : lang('heading:vendor_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:code').' *', 'Kode_Supplier', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Kode_Supplier]', set_value('f[Kode_Supplier]', @$item->Kode_Supplier, TRUE), [
										'id' => 'Kode_Supplier', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<?php 
							if(config_item('bpjs_bridging') == 'TRUE')
								echo modules::run('bpjs/doctor/form_mapping', @$item->Kode_Supplier_BPJS);
						?>
                        <div class="form-group">
                            <?php echo form_label(lang('label:name').' *', 'Nama_Supplier', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Nama_Supplier]', set_value('f[Nama_Supplier]', @$item->Nama_Supplier, TRUE), [
										'id' => 'Nama_Supplier', 
										'placeholder' => '',
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:category').' *', 'KodeKategoriVendor', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[KodeKategoriVendor]', $dropdown_category, set_value('f[KodeKategoriVendor]', @$item->KodeKategoriVendor, TRUE), [
									'id' => 'KodeKategoriVendor', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:specialist').' *', 'SpesialisID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[SpesialisID]', $dropdown_specialist, set_value('f[v]', @$item->SpesialisID, TRUE), [
									'id' => 'SpesialisID', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:subspecialist').' *', 'SubSpesialisID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[SubSpesialisID]', $dropdown_subspecialist, set_value('f[SubSpesialisID]', @$item->SubSpesialisID, TRUE), [
									'id' => 'SubSpesialisID', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:default_honor'), 'HonorDefault', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input('f[HonorDefault]', set_value('f[HonorDefault]', @$item->HonorDefault, TRUE), [
											'id' => 'HonorDefault', 
											'placeholder' => '',
											'class' => 'form-control text-right'
										]); ?>
									<span class="input-group-addon">%</span>
								</div>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:tax'), 'Pajak', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input('f[Pajak]', set_value('f[Pajak]', @$item->Pajak, TRUE), [
											'id' => 'Pajak', 
											'placeholder' => '',
											'class' => 'form-control text-right'
										]); ?>
									<span class="input-group-addon">%</span>
								</div>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:tht'), 'THT', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input('f[THT]', set_value('f[THT]', @$item->THT, TRUE), [
											'id' => 'THT', 
											'placeholder' => '',
											'class' => 'form-control text-right'
										]); ?>
									<span class="input-group-addon">%</span>
								</div>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:begin_balance_honor'), 'SaldoAwalHonor', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[SaldoAwalHonor]', set_value('f[SaldoAwalHonor]', @$item->SaldoAwalHonor, TRUE), [
										'id' => 'SaldoAwalHonor', 
										'placeholder' => '',
										'class' => 'form-control text-right mask-number'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:begin_balance_honor_date'), 'TglSaldoAwalHonor', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[TglSaldoAwalHonor]', set_value('f[TglSaldoAwalHonor]', @$item->TglSaldoAwalHonor, TRUE), [
										'id' => 'TglSaldoAwalHonor', 
										'placeholder' => '',
										'class' => 'form-control datepicker text-right'
									]); ?>
							</div>
                        </div>
					</div>
					
					<div class="col-md-6 col-xs-12">
						<div class="form-group">
                            <?php echo form_label(lang('label:npwp'), 'No_NPWP', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_NPWP]', set_value('f[No_NPWP]', @$item->No_NPWP, TRUE), [
										'id' => 'No_NPWP', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:debt_payment_with'), 'CaraPembayaranHutang', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[CaraPembayaranHutang]', set_value('f[CaraPembayaranHutang]', @$item->CaraPembayaranHutang, TRUE), [
										'id' => 'CaraPembayaranHutang', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:honor_group'), 'KelompokHonor', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[KelompokHonor]', set_value('f[KelompokHonor]', @$item->KelompokHonor, TRUE), [
										'id' => 'KelompokHonor', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:address'), 'Alamat_1', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_textarea([
										'name' => 'f[Alamat_1]', 
										'value' => set_value('f[Alamat_1]', @$item->Alamat_1, TRUE),
										'id' => 'Alamat_1', 
										'placeholder' => '',
										'rows' => 4,
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:phone'), 'No_Telepon_1', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_Telepon_1]', set_value('f[No_Telepon_1]', @$item->No_Telepon_1, TRUE), [
										'id' => 'No_Telepon_1', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:birthdate'), 'TglLahir', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[TglLahir]', set_value('f[TglLahir]', @$item->TglLahir, TRUE), [
										'id' => 'TglLahir', 
										'placeholder' => '',
										'class' => 'form-control datepicker'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label('Opsi', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
							<div class="col-sm-9 col-xs-12">
								<div class="row">
									<div class="col-sm-6 col-xs-12">
										<?php echo form_hidden('f[Active]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'Active',
												'name' => 'f[Active]',
												'value' => 1,
												'checked' => set_value('f[Active]', (boolean) @$item->Active, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('label:active').'</b>', 'Active'); ?>
									</div>
									<div class="col-sm-6 col-xs-12">
										<?php echo form_hidden('f[DokterTetap]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'DokterTetap',
												'name' => 'f[DokterTetap]',
												'value' => 1,
												'checked' => set_value('f[DokterTetap]', (boolean) @$item->DokterTetap, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('label:permanent').'</b>', 'DokterTetap'); ?>
									</div>
									<div class="col-sm-6 col-xs-12">
										<?php echo form_hidden('f[KonsultanSHHC]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'KonsultanSHHC',
												'name' => 'f[KonsultanSHHC]',
												'value' => 1,
												'checked' => set_value('f[KonsultanSHHC]', (boolean) @$item->KonsultanSHHC, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('label:shhc_consultant').'</b>', 'KonsultanSHHC'); ?>
									</div>
									<div class="col-sm-6 col-xs-12">
										<?php echo form_hidden('f[IncludePajak]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'IncludePajak',
												'name' => 'f[IncludePajak]',
												'value' => 1,
												'checked' => set_value('f[IncludePajak]', (boolean) @$item->IncludePajak, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('label:honor_include_tax').'</b>', 'IncludePajak'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button class="btn btn-success" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">Buat Baru</button> 
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Tutup</button> 
							<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_customer");
		
		function populate_subspecialist(v){
			var parent = this.find('select[name="f[SpesialisID]"]').val();
			
			_input = this.find('select[name="f[SubSpesialisID]"]');				
			_input.html('<option value="0"><?php echo lang('ajax:loading'); ?></option>');
			_input.attr('disabled', 'disabled');
			_input.load('<?php echo site_url("{$nameroutes}/get_subspecialist_list") ?>',{'parent': parent}, function(response, status){
					_input.removeAttr('disabled');
					_input.val(v || '');
				});
		}
						
		$( document ).ready(function(e) {				
				_form.find('select[name="f[SpesialisID]"]').on('change', function(e){
						e.preventDefault();
						populate_subspecialist.call(_form);
					});
						
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var data_post = _form.serializeArray();
							
					$.post( _form.prop("action"), data_post, function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url($nameroutes); ?>";
							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
