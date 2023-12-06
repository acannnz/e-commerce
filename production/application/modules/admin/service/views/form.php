<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<style>
		.select2-container .select2-choice {
			display: contents!important;
			line-height: 20px!important;
		}
		.select2-custom{
			display: block!important;
		}
		@media (min-width: 992px)
		{
			.modal-lg {
				width: 1150px!important;
			}
		}
			
</style>
<?php echo form_open( $form_action, [
		'id' => 'form_service', 
		'name' => 'form_service', 
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
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:service_update') : lang('heading:service_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:code').' *', 'JasaID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[JasaID]', set_value('f[JasaID]', @$item->JasaID, TRUE), [
										'id' => 'JasaID', 
										'placeholder' => '', 
										'class' => 'form-control',
										'readonly' => TRUE
									]); ?>
							</div>
                        </div>
						<?php 
							if(config_item('bpjs_bridging') == 'TRUE')
								echo modules::run('bpjs/service_bpjs/form_mapping', @$item->JasaIDBPJS);
						?>
                        <div class="form-group">
                            <?php echo form_label(lang('label:name').' *', 'JasaName', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[JasaName]', set_value('f[JasaName]', @$item->JasaName, TRUE), [
										'id' => 'JasaName', 
										'placeholder' => '',
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:international_name').' *', 'JasaNameEnglish', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[JasaNameEnglish]', set_value('f[JasaNameEnglish]', @$item->JasaNameEnglish, TRUE), [
										'id' => 'JasaNameEnglish', 
										'placeholder' => '',
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:service_group').' *', 'GroupJasaID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php @$dropdown_group[''] = lang('global:select-none') ?>
								<?php echo form_dropdown('f[GroupJasaID]', @$dropdown_group, set_value('f[GroupJasaID]', @$item->GroupJasaID, TRUE), [
									'id' => 'GroupJasaID', 
									'class' => 'form-control select2'
								]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:service_category').' *', 'KategoriJasaID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php @$dropdown_category[''] = lang('global:select-none') ?>
								<?php echo form_dropdown('f[KategoriJasaID]', @$dropdown_category, set_value('f[KategoriJasaID]', @$item->KategoriJasaID, TRUE), [
									'id' => 'KategoriJasaID', 
									'class' => 'form-control select2'
								]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:plafon_category'), 'KategoriPlafon', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php $dropdown_plafon_cateogry[''] = lang('global:select-none');?>
							<?php echo form_dropdown('f[KategoriPlafon]', $dropdown_plafon_cateogry, set_value('f[KategoriPlafon]', @$item->KategoriPlafon, TRUE), [
									'id' => 'KategoriPlafon', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
					</div>			
					<div class="col-md-6 col-xs-12">
						<div class="form-group">
							<?php echo form_label(lang('label:using_devices'), 'KodeMappingAlat', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php $dropdown_devices[''] = lang('global:select-none');?>
							<?php echo form_dropdown('f[KodeMappingAlat]', $dropdown_devices, set_value('f[KodeMappingAlat]', @$item->KodeMappingAlat, TRUE), [
									'id' => 'KodeMappingAlat', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:cost_rs_percent'), 'CostRSPersen', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[CostRSPersen]', set_value('f[CostRSPersen]', @$item->CostRSPersen, TRUE), [
										'id' => 'CostRSPersen', 
										'placeholder' => '',
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:cost_rs_value'), 'CostRSRupiah', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[CostRSRupiah]', set_value('f[CostRSRupiah]', @$item->CostRSRupiah, TRUE), [
										'id' => 'CostRSRupiah', 
										'placeholder' => '',
										'required' => 'required',
										'class' => 'form-control mask_number'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:posting_group'), 'KelompokPostingan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[KelompokPostingan]', $dropdown_posting_group, set_value('f[KelompokPostingan]', @$item->KelompokPostingan, TRUE), [
									'id' => 'KelompokPostingan', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:insentif_source'), 'ModelInsentif', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[ModelInsentif]', $dropdown_insentif_source, set_value('f[ModelInsentif]', @$item->ModelInsentif, TRUE), [
									'id' => 'ModelInsentif', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:polyclinic'), 'PoliKlinik', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[PoliKlinik]', $dropdown_polyclinic, set_value('f[PoliKlinik]', @$item->PoliKlinik, TRUE), [
									'id' => 'PoliKlinik', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:status'), '', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9 col-xs-12">
								<div class="row">								
									<div class="col-md-6">
										<div class="checkbox">
											<label for="Aktif">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[Aktif]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[Aktif]', 1, (boolean) @$item->Aktif, [
													'id' => 'Aktif', 
												]); ?>
												<?php echo lang('global:active') ?>
											</label>
										</div>
									</div>
									<?php /*?><div class="col-md-6">
										<div class="checkbox">
											<label for="Paket">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[Paket]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[Paket]', 1, (boolean) @$item->Paket, [
													'id' => 'Paket', 
												]); ?>
												<?php echo lang('label:service_package') ?>
											</label>
										</div>
									</div><?php */?>
									<?php /*?><div class="col-md-6">
										<div class="checkbox">
											<label for="JasaReproduksi">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[JasaReproduksi]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[JasaReproduksi]', 1, (boolean) @$item->JasaReproduksi, [
													'id' => 'JasaReproduksi', 
												]); ?>
												<?php echo lang('label:reproductive_clinic') ?>
											</label>
										</div>
									</div><?php */?>
									<?php /*?><div class="col-md-6">
										<div class="checkbox">
											<label for="Manual">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[Manual]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[Manual]', 1, (boolean) @$item->Manual, [
													'id' => 'Manual', 
												]); ?>
												<?php echo lang('label:manual_fare') ?>
											</label>
										</div>
									</div><?php */?>
									<div class="col-md-6">
										<div class="checkbox">
											<label for="HariRawatInap">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[HariRawatInap]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[HariRawatInap]', 1, (boolean) @$item->HariRawatInap, [
													'id' => 'HariRawatInap', 
												]); ?>
												<?php echo lang('label:inpatient_day') ?>
											</label>
										</div>
									</div>
									<?php /*?><div class="col-md-6">
										<div class="checkbox">
											<label for="MCU">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[MCU]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[MCU]', 1, (boolean) @$item->MCU, [
													'id' => 'MCU', 
												]); ?>
												<?php echo lang('label:mcu') ?>
											</label>
										</div>
									</div><?php */?>
									<div class="col-md-6">
										<div class="checkbox">
											<label for="AutoSystemRI">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[AutoSystemRI]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[AutoSystemRI]', 1, (boolean) @$item->AutoSystemRI, [
													'id' => 'AutoSystemRI', 
												]); ?>
												<?php echo lang('label:auto_system') ?>
											</label>
										</div>
									</div>
									<?php /*?><div class="col-md-6">
										<div class="checkbox">
											<label for="KSO">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[KSO]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[KSO]', 1, (boolean) @$item->KSO, [
													'id' => 'KSO', 
												]); ?>
												<?php echo lang('label:kso') ?>
											</label>
										</div>
									</div><?php */?>
									<?php /*?><div class="col-md-6">
										<div class="checkbox">
											<label for="Var_KategoriOperasi">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[Var_KategoriOperasi]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[Var_KategoriOperasi]', 1, (boolean) @$item->Var_KategoriOperasi, [
													'id' => 'Var_KategoriOperasi', 
												]); ?>
												<?php echo lang('label:operation_category_variables') ?>
											</label>
										</div>
									</div><?php */?>
									<?php /*?><div class="col-md-6">
										<div class="checkbox">
											<label for="Var_Cito">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[Var_Cito]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[Var_Cito]', 1, (boolean) @$item->Var_Cito, [
													'id' => 'Var_Cito', 
												]); ?>
												<?php echo lang('label:cyto_variable') ?>
											</label>
										</div>
									</div><?php */?>
									<div class="col-md-6">
										<div class="checkbox">
											<label for="WithDokter">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[WithDokter]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[WithDokter]', 1, (boolean) @$item->WithDokter, [
													'id' => 'WithDokter', 
												]); ?>
												<?php echo lang('label:must_doctor') ?>
											</label>
										</div>
									</div>
									<?php /*?><div class="col-md-6">
										<div class="checkbox">
											<label for="MenggunakanAlat">
												<?php echo form_input([
														'type' => 'hidden',
														'name' => 'f[MenggunakanAlat]',
														'value' => 0,
													]); ?>
												<?php echo form_checkbox('f[MenggunakanAlat]', 1, (boolean) @$item->MenggunakanAlat, [
													'id' => 'MenggunakanAlat', 
												]); ?>
												<?php echo lang('label:using_helper_devices') ?>
											</label>
										</div>
									</div><?php */?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr/>				
				<ul class="nav nav-tabs nav-justified">
					<li class="active"><a href="#post-price-detail" data-toggle="tab"><i class="fa fa-money"></i> <strong><?php echo lang("subtitle:price_detail")?></strong></a></li>
					<li class=""><a href="#post-bhp-detail" data-toggle="tab"><i class="fa fa-medkit"></i> <strong><?php echo lang("subtitle:bhp_detail")?></strong></a></li>
					<li class=""><a href="#post-section-detail" data-toggle="tab"><i class="fa fa-hospital-o"></i> <strong><?php echo lang("subtitle:section_detail")?></strong></a></li>
					<li class=""><a href="#post-lab-detail" data-toggle="tab"><i class="fa fa-eyedropper"></i> <strong><?php echo lang("subtitle:lab_detail")?></strong></a></li>
				</ul>
				<div class="tab-content">
					<div id="post-price-detail" class="tab-pane active">
						<?php echo modules::run("{$nameroutes}/services/price/index", $item) ?>
					</div>
					<div id="post-bhp-detail" class="tab-pane">
						<?php echo modules::run("{$nameroutes}/services/bhp/index", $item) ?>
					</div>
					<div id="post-section-detail" class="tab-pane">
						<?php echo modules::run("{$nameroutes}/services/section/index", $item) ?>
					</div>
					<div id="post-lab-detail" class="tab-pane">
						<?php echo modules::run("{$nameroutes}/services/test/index", $item) ?>
					</div>
				</div>	
				
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<!-- <button class="btn btn-info" type="button" onclick="window.location='<?php echo current_url(); ?>';"><?php echo lang( 'buttons:refresh' ) ?></button>  -->
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Tutup</button> 
							<button class="btn btn-success" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';"><?php echo lang( 'buttons:create' ) ?></button> 
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
		var _form = $("#form_service");
		var _form_actions = {
				init: function(){
					$( "select#GroupJasaID" ).on('change', function(){
						
						_target = $('select#KategoriJasaID');
						$.ajax({
								url: '<?php echo base_url( "service/service_category/dropdown_html" ) ?>/' + $(this).val(),
								dataType: 'json',
								type: 'GET',
								data: {"parent_id": $(this).val()},
								beforeSend: function( xhr, settings ){
									
										_target.html("");
										$("<option></option>")
											.val("0")
											.text("Loading...")
											.attr('selected', 'selected')
											.appendTo( _target );
										_target.trigger('change');
									},
								success: function(response, status, xhr) {
										_target.html( response ).trigger('change');;     
									},
								error: function(xhr, msg) {}
							});
					});
					
				}
			}
						
		$( document ).ready(function(e) {
								
				_form_actions.init();
						
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var data_post = {};
					data_post['service'] = {
							JasaName : _form.find('#JasaName').val(),
							JasaNameEnglish : _form.find('#JasaNameEnglish').val(),
							GroupJasaID : _form.find('#GroupJasaID').val(),
							KategoriJasaID : _form.find('#KategoriJasaID').val(),
							KategoriPlafon : _form.find('#KategoriPlafon').val(),
							PoliKlinik : _form.find('#PoliKlinik').val(),						
							KodeMappingAlat : _form.find('#KodeMappingAlat').val(),
							KelompokPostingan : _form.find('#KelompokPostingan').val(),
							ModelInsentif : _form.find('#ModelInsentif').val(),
							CostRSPersen : _form.find('#CostRSPersen').val() || 0,
							CostRSRupiah : _form.find('#CostRSRupiah').val() || 0,
							AutoSystemRI : _form.find('#AutoSystemRI').is(':checked') ? 1 : 0,
							MCU : _form.find('#MCU').is(':checked') ? 1 : 0,
							KSO : _form.find('#KSO').is(':checked') ? 1 : 0,
							Var_KategoriOperasi : _form.find('#Var_KategoriOperasi').is(':checked') ? 1 : 0,
							Var_Cito : _form.find('#Var_Cito').is(':checked') ? 1 : 0,
							WithDokter : _form.find('#WithDokter').is(':checked') ? 1 : 0,						
							Aktif : _form.find('#Aktif').is(':checked') ? 1 : 0,
							MenggunakanAlat : _form.find('#MenggunakanAlat').is(':checked') ? 1 : 0,
							JasaReproduksi : _form.find('#JasaReproduksi').is(':checked') ? 1 : 0,
							HariRawatInap : _form.find('#HariRawatInap').is(':checked') ? 1 : 0,
							Manual : _form.find('#Manual').is(':checked') ? 1 : 0,
							Paket : _form.find('#Paket').is(':checked') ? 1 : 0,
						};
						
					<?php if(config_item('bpjs_bridging') == 'TRUE'): ?>
						data_post['service']['JasaIDBPJS'] = _form.find('#JasaIDBPJS').val();
					<?php endif; ?>
						
					console.log($('#dt_form_price').DataTable().data().any());
					if( $('#dt_form_price').DataTable().data().any() ) { 
						data_post['service_price'] = {};
						var	dt_form_service_price = $('#dt_form_price').DataTable().rows().data();
						var _error_price = false;
						$.each(dt_form_service_price, function(index, value){
							if(value.component_detail == null || value.component_detail == [] || $.isEmptyObject(value.component_detail)){
								$.alert_error('<?php echo lang('message:empty_price')?>');
								_error_price = true; 
								return false;
							}
							data_post['service_price'][index] = value;
						});
						if(_error_price) return false;
					} else {
						$.alert_error('<?php echo lang('message:empty_price')?>');
						return false;
					}
					
					if( $('#dt_form_bhp').DataTable().data().any() ) { 
						data_post['service_bhp'] = {};
						var	dt_form_service_bhp = $('#dt_form_bhp').DataTable().rows().data();
						$.each( dt_form_service_bhp, function(index, value){
							data_post['service_bhp'][index] = value;
						});
					}
					
					if( $('#dt_form_section').DataTable().data().any()  ) { 
						data_post['service_section'] = {};
						var	dt_form_service_section = $('#dt_form_section').DataTable().rows().data();
						$.each( dt_form_service_section, function(index, value){
							data_post['service_section'][index] = value;
						});
					} else {
						$.alert_error('<?php echo lang('message:empty_section')?>');
						return false;
					}
					
					if( $('#dt_form_test').DataTable().data().any() ) { 
						data_post['service_test'] = {};
						var	dt_form_service_test = $('#dt_form_test').DataTable().rows().data();
						$.each( dt_form_service_test, function(index, value){
							data_post['service_test'][index] = value;
						});
					}
					
					$.post( _form.prop("action"), data_post, function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("{$nameroutes}/update"); ?>/"+ response.id;
							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
