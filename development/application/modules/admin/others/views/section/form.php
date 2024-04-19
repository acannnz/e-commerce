<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open( $form_action, [
		'id' => 'form_section', 
		'name' => 'form_section', 
		'rule' => 'form', 
		'class' => ''
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
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:section_update') : lang('heading:section_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:code').' *', 'SectionID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[SectionID]', set_value('f[SectionID]', @$item->SectionID, TRUE), [
										'id' => 'SectionID', 
										'placeholder' => '', 
										'readonly' => 'readonly', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<?php 
							if(config_item('bpjs_bridging') == 'TRUE')
								echo modules::run('bpjs/poly/form_mapping', @$item->SectionIDBPJS);
						?>
						<div class="form-group">
							<?php echo form_label(lang('label:name').' *', 'SectionName', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[SectionName]', set_value('f[SectionName]', @$item->SectionName, false), [
										'id' => 'SectionName', 
										'placeholder' => '', 
										'required' => 'required', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:person_incharge').'*', 'PenanggungJawab', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[PenanggungJawab]', set_value('f[PenanggungJawab]', @$item->PenanggungJawab, TRUE), [
										'id' => 'PenanggungJawab', 
										'placeholder' => '', 
										'required' => 'required', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:service_type'), 'TipePelayanan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[TipePelayanan]', $dropdown_services_type, set_value('f[TipePelayanan]', @$item->TipePelayanan, TRUE), [
										'id' => 'TipePelayanan', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:section_group'), 'KelompokSection', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[KelompokSection]', $dropdown_section_group, set_value('f[KelompokSection]', @$item->KelompokSection, false), [
										'id' => 'KelompokSection', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:polyclinic'), 'PoliKlinik', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[PoliKlinik]', $dropdown_polyclinic, set_value('f[PoliKlinik]', @$item->PoliKlinik, TRUE), [
										'id' => 'TipePelayanan', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:business_unit'), 'UnitBisnisID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[UnitBisnisID]', $dropdown_business, set_value('f[UnitBisnisID]', @$item->UnitBisnisID, TRUE), [
										'id' => 'UnitBisnisID', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:services'), 'Pelayanan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[Pelayanan]', $dropdown_services, set_value('f[Pelayanan]', @$item->Pelayanan, TRUE), [
										'id' => 'Pelayanan', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
					</div>
					<div class="col-md-6 col-xs-12">
						<div class="form-group">
							<?php echo form_label(lang('label:evidence_code').' *', 'KodeNoBukti', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[KodeNoBukti]', set_value('f[KodeNoBukti]', @$item->KodeNoBukti, TRUE), [
										'id' => 'KodeNoBukti', 
										'placeholder' => '', 
										'required' => 'required', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:pharmacy_ip'), 'IPFarmasi', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[IPFarmasi]', set_value('f[IPFarmasi]', @$item->IPFarmasi, TRUE), [
										'id' => 'IPFarmasi', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:tt_amount'), 'JumlahTT', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[JumlahTT]', set_value('f[JumlahTT]', @$item->JumlahTT, TRUE), [
										'id' => 'JumlahTT', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:in_mutation_account'), 'MutasiMasukAkun_ID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[MutasiMasukAkun_ID]',
												'value' => set_value('f[MutasiMasukAkun_ID]', @$item->MutasiMasukAkun_ID, TRUE),
												'id' => 'MutasiMasukAkun_ID',
												'class' => 'MutasiMasukAkun_ID'
											]); ?>
											
									<?php echo form_input('NamaMutasiMasukAkun_ID', set_value('f[NamaMutasiMasukAkun_ID]', @$account_in->Akun_No.' '.@$account_in->Akun_Name, TRUE), [
												'id' => 'NamaMutasiMasukAkun_ID', 
												'readonly' => 'readonly',
												'class' => 'form-control MutasiMasukAkun_ID'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_account_in ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" data-target-class="MutasiMasukAkun_ID"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:out_mutation_account'), 'MutasiKeluarAkun_ID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[MutasiKeluarAkun_ID]',
												'value' => set_value('f[MutasiKeluarAkun_ID]', @$item->MutasiKeluarAkun_ID, TRUE),
												'id' => 'MutasiKeluarAkun_ID',
												'class' => 'MutasiKeluarAkun_ID'
											]); ?>
											
									<?php echo form_input('NamaMutasiKeluarAkun_ID', set_value('f[NamaMutasiKeluarAkun_ID]', @$account_out->Akun_No.' '.@$account_out->Akun_Name, TRUE), [
												'id' => 'NamaMutasiKeluarAkun_ID', 
												'readonly' => 'readonly',
												'class' => 'form-control MutasiKeluarAkun_ID'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_account_out ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" data-target-class="MutasiKeluarAkun_ID"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:drug_revenue_account'), 'PendapatanObatAkun_ID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[PendapatanObatAkun_ID]',
												'value' => set_value('f[PendapatanObatAkun_ID]', @$item->PendapatanObatAkun_ID, TRUE),
												'id' => 'PendapatanObatAkun_ID',
												'class' => 'PendapatanObatAkun_ID'
											]); ?>
											
									<?php echo form_input('NamaPendapatanObatAkun_ID', set_value('f[NamaPendapatanObatAkun_ID]', @$account_drug_revenue->Akun_No.' '.@$account_drug_revenue->Akun_Name, TRUE), [
												'id' => 'NamaPendapatanObatAkun_ID', 
												'readonly' => 'readonly',
												'class' => 'form-control PendapatanObatAkun_ID'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_account_drug_revenue ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" data-target-class="PendapatanObatAkun_ID"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<?php /*?><div class="form-group">
							<?php echo form_label(lang('label:customer'), 'Customer_ID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[Customer_ID]',
												'value' => set_value('f[Customer_ID]', @$item->Customer_ID, TRUE),
												'id' => 'Customer_ID',
												'class' => 'Customer_ID'
											]); ?>
											
									<?php echo form_input('NamaCustomer_ID', set_value('f[NamaCustomer_ID]', @$customer->Nama_Customer, TRUE), [
												'id' => 'NamaCustomer_ID', 
												'readonly' => 'readonly',
												'class' => 'form-control Customer_ID'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_customer ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:customer'))?>" data-act="ajax-modal" data-modal-lg="1" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" data-target-class="Customer_ID"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div><?php */?>
						<div class="form-group">
							<?php echo form_label('Opsi', '', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-6">
										<?php echo form_hidden('f[StatusAktif]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'StatusAktif',
												'name' => 'f[StatusAktif]',
												'value' => 1,
												'checked' => set_value('f[StatusAktif]', (boolean) @$item->StatusAktif, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('global:active').'</b>', 'StatusAktif'); ?>
									</div>
									<div class="col-md-6">
										<?php echo form_hidden('f[RIOnly]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'RIOnly',
												'name' => 'f[RIOnly]',
												'value' => 1,
												'checked' => set_value('f[RIOnly]', (boolean) @$item->RIOnly, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('label:ri_only').'</b>', 'RIOnly'); ?>
									</div>
									<div class="col-md-6">
										<?php echo form_hidden('f[RBayi]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'RBayi',
												'name' => 'f[RBayi]',
												'value' => 1,
												'checked' => set_value('f[RBayi]', (boolean) @$item->RBayi, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('label:baby_room').'</b>', 'RBayi'); ?>
									</div>
									<div class="col-md-6">
										<?php echo form_hidden('f[ICU]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'ICU',
												'name' => 'f[ICU]',
												'value' => 1,
												'checked' => set_value('f[ICU]', (boolean) @$item->ICU, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('label:icu').'</b>', 'ICU'); ?>
									</div>
									<div class="col-md-6">
										<?php echo form_hidden('f[BlokCoPay]', 0); ?>
										<?php echo form_checkbox([
												'id' => 'BlokCoPay',
												'name' => 'f[BlokCoPay]',
												'value' => 1,
												'checked' => set_value('f[BlokCoPay]', (boolean) @$item->BlokCoPay, TRUE),
												'class' => 'checkbox'
											]).' '.form_label('<b>'. lang('label:block_co_payment').'</b>', 'BlokCoPay'); ?>
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
							<button id="js-btn-submit" type="submit" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">New</button> 
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Close</button> 
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
		var _form = $("#form_section");
				
		$( document ).ready(function(e) {
			
				_form.find('a.btn-clear').on('click', function(e){
						var _target_class = $(this).data('target-class');
						$('.'+ _target_class).val('');
					});
				
				_form.on("submit", function(e){
					e.preventDefault();		
							
					$.post( _form.prop("action"), _form.serializeArray(), function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url($nameroutes); ?>";
							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
