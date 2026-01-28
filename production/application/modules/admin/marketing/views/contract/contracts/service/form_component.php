
<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open( @$form_action, [
		'id' => 'form_service_component', 
		'name' => 'form_service_component', 
		'rule' => 'form', 
		'class' => 'form-horizontal'
	]); ?>
<div class="panel-body table-responsive">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<?php echo form_label(lang('label:component').' *', 'KelasID', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<div class="input-group">
						<?php echo form_input([
									'type' => 'hidden',
									'name' => 'p[KomponenBiayaID]',
									'value' => set_value('p[KomponenBiayaID]', @$item->KomponenBiayaID, TRUE),
									'id' => 'KomponenBiayaID',
									'class' => 'KomponenBiayaID'
								]); ?>
								
						<?php echo form_input('p[KomponenName]', set_value('p[KomponenName]', @$component->KomponenName, TRUE), [
									'id' => 'KomponenName', 
									'readonly' => 'readonly',
									'class' => 'form-control KomponenBiayaID'
								]); ?>
						<span class="input-group-btn">
							<a href="javascript:;" data-action-url="<?php echo @$lookup_component ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:component'))?>" data-toggle="lookup-ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
							<a href="javascript:;" data-target-class="KomponenBiayaID"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:qty').' *', 'Qty', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[Qty]', set_value('p[Qty]', @$item->Qty, TRUE), [
							'id' => 'Qty', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:begin_price'), 'HargaAwal', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaAwal]', set_value('p[HargaAwal]', @$item->HargaAwal, TRUE), [
							'id' => 'HargaAwal', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:old_price'), 'HargaLama', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaLama]', set_value('p[HargaLama]', @$item->HargaLama, TRUE), [
							'id' => 'HargaLama', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:percent_tax_deposit'), 'PersenPajakTitipan', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[PersenPajakTitipan]', set_value('p[PersenPajakTitipan]', @$item->PersenPajakTitipan, TRUE), [
							'id' => 'PersenPajakTitipan', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:percent_insentif'), 'PersenInsentif', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[PersenInsentif]', set_value('p[PersenInsentif]', @$item->PersenInsentif, TRUE), [
							'id' => 'PersenInsentif', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<div class="checkbox">
						<label for="IncludeInsentif">
							<?php echo form_input([
									'type' => 'hidden',
									'name' => 'p[IncludeInsentif]',
									'value' => 0,
								]); ?>
							<?php echo form_checkbox('p[IncludeInsentif]', 1, (boolean) @$item->IncludeInsentif, [
									'id' => 'IncludeInsentif', 
								]); ?>
							<?php echo lang('label:include_insentif') ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:new_price'), 'HargaBaru', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaBaru]', set_value('p[HargaBaru]', @$item->HargaBaru, TRUE), [
							'id' => 'HargaBaru', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
		</div>			
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<?php echo form_label(lang('label:old_price_hc'), 'HargaHCLama', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaHCLama]', set_value('p[HargaHCLama]', @$item->HargaHCLama, TRUE), [
							'id' => 'HargaHCLama', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:new_price_hc'), 'HargaHCBaru', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaHCBaru]', set_value('p[HargaHCBaru]', @$item->HargaHCBaru, TRUE), [
							'id' => 'HargaHCBaru', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:insentif_hc'), 'PersenInsentifHC', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[PersenInsentifHC]', set_value('p[PersenInsentifHC]', @$item->PersenInsentifHC, TRUE), [
							'id' => 'PersenInsentifHC', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:old_price_bpjs'), 'HargaBPJS_Lama', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaBPJS_Lama]', set_value('p[HargaBPJS_Lama]', @$item->HargaBPJS_Lama, TRUE), [
							'id' => 'HargaBPJS_Lama', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:new_price_bpjs'), 'HargaBPJS', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaBPJS]', set_value('p[HargaBPJS]', @$item->HargaBPJS, TRUE), [
							'id' => 'HargaBPJS', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:hpp_account'), 'AkunNo', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<div class="input-group">
						<?php echo form_input([
									'type' => 'hidden',
									'name' => 'p[AkunNo]',
									'value' => set_value('p[AkunNo]', @$item->AkunNo, TRUE),
									'id' => 'AkunNo',
									'class' => 'AkunNo'
								]); ?>
								
						<?php echo form_input('p[AkunNoName]', set_value('p[AkunNoName]', @$hpp->AkunName, TRUE), [
									'id' => 'AkunNoName', 
									'readonly' => 'readonly',
									'class' => 'form-control AkunNo'
								]); ?>
						<span class="input-group-btn">
							<a href="javascript:;" data-action-url="<?php echo @$lookup_hpp ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:hpp_account'))?>" data-toggle="lookup-ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
							<a href="javascript:;" data-target-class="AkunNo"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:hpp_againts_account'), 'AkunNoLawan', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<div class="input-group">
						<?php echo form_input([
									'type' => 'hidden',
									'name' => 'p[AkunNoLawan]',
									'value' => set_value('p[AkunNoLawan]', @$item->AkunNoLawan, TRUE),
									'id' => 'AkunNoLawan',
									'class' => 'AkunNoLawan'
								]); ?>
								
						<?php echo form_input('p[AkunNoLawanName]', set_value('p[AkunNoLawanName]', @$component->KomponenName, TRUE), [
									'id' => 'AkunNoLawanName', 
									'readonly' => 'readonly',
									'class' => 'form-control AkunNoLawan'
								]); ?>
						<span class="input-group-btn">
							<a href="javascript:;" data-action-url="<?php echo @$lookup_hpp_againts ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:hpp_againts_account'))?>" data-toggle="lookup-ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
							<a href="javascript:;" data-target-class="AkunNoLawan"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<hr/>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<div class="col-md-12 text-right">
					<button class="btn btn-default" type="button" data-dismiss="modal"><?php echo lang( 'buttons:close' ) ?></button> 
					<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		var _service_price_data = $("#dt_form_price").DataTable().row( '.selected' ).data();
		var _row_index = '<?php echo @$row_index ?>';
		var _service_component_data = $("#dt_form_service_component").DataTable().row( _row_index ).data();
	
		var _form = $("#form_service_component");
		var _form_actions = {
				init: function(){
					
					if( _row_index != ''){
						$.each(_service_component_data, function(key, val){
							_form.find('#'+ key ).val( val );
						});
					}
					
					$('.datepicker').datepicker({
						format: "yyyy-mm-dd",
					});
					
					$("select.select2").select2({
						placeholder: 'Select an option',
						maximumSelectionSize: 1,
						allowClear: true,
						width: '100%',
					});
					
					_form.find('a.btn-clear').on('click', function(e){
						var _target_class = $(this).data('target-class');
						$('.'+ _target_class).val('');
					});
					
					$('#HargaAwal, #PersenInsentif, #PersenPajakTitipan').on('blur', function(){
						_form_actions.calculate_new_price();
					});
					
					$('#IncludeInsentif').on('change', function(){
						_form_actions.calculate_new_price();
					});
					
					mask_number.init();
					
				},
				calculate_new_price: function(){
					var begin_price = _form.find('input[name="p[HargaAwal]"]');
					var new_price = _form.find('input[name="p[HargaBaru]"]');
					var insentif = _form.find('input[name="p[PersenInsentif]"]');
					var include_insentif = _form.find('input[name="p[IncludeInsentif]"]');
					var	tax = _form.find('input[name="p[PersenPajakTitipan]"]');
					var begin_price_val = mask_number.currency_remove( begin_price.val() );
					var insentif_val = tax_val = new_price_val = 0;
					
					if( include_insentif.is(':checked') == false ){
						insentif_val = begin_price_val * parseFloat(insentif.val() || 0) / 100;
					}
					
					tax_val = begin_price_val * parseFloat(tax.val() || 0) / 100;
					
					new_price_val = begin_price_val + insentif_val + tax_val;
					new_price.val( mask_number.currency_add(new_price_val) );
					console.log('tax :', tax_val, ' insetif :', insentif_val, ' new price :', new_price_val );
				}
			}
						
		$( document ).ready(function(e) {
								
				_form_actions.init();
						
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var service_component_data = {
							KomponenBiayaID : _form.find('input[name="p[KomponenBiayaID]"]').val(),
							Qty : _form.find('input[name="p[Qty]"]').val(),
							HargaLama : mask_number.currency_remove( _form.find('input[name="p[HargaLama]"]').val() || 0 ),
							HargaBaru : mask_number.currency_remove( _form.find('input[name="p[HargaBaru]"]').val() || 0 ),
							HargaAwal : mask_number.currency_remove( _form.find('input[name="p[HargaAwal]"]').val() || 0 ),
							HargaAwalLama : mask_number.currency_remove( _form.find('input[name="p[HargaAwalLama]"]').val() || 0 ),
							HargaHCLama : mask_number.currency_remove( _form.find('input[name="p[HargaHCLama]"]').val() || 0 ),
							HargaHCBaru : mask_number.currency_remove( _form.find('input[name="p[HargaHCBaru]"]').val() || 0 ),
							PersenInsentifHC : _form.find('input[name="p[PersenInsentifHC]"]').val(),
							HargaBPJS : mask_number.currency_remove( _form.find('input[name="p[HargaBPJS]"]').val() || 0 ),
							HargaBPJS_Lama : mask_number.currency_remove( _form.find('input[name="p[HargaBPJS_Lama]"]').val() || 0 ),
							//HargaIKS_Lama : _form.find('input[name="p[HargaIKS_Lama]"]').val(),
							//HargaIKS_Baru : _form.find('input[name="p[HargaIKS_Baru]"]').val(),
							IncludeInsentif : _form.find('input[name="p[IncludeInsentif]"]').is(':checked') ? 1 : 0,
							PersenInsentif : _form.find('input[name="p[PersenInsentif]"]').val(),
							PersenPajakTitipan : _form.find('input[name="p[PersenPajakTitipan]"]').val(),
							Prosentase : _form.find('input[name="p[Prosentase]"]').val(),
							NilaiPersen : _form.find('input[name="p[NilaiPersen]"]').val(),
							AkunNo : _form.find('input[name="p[AkunNo]"]').val(),
							AkunNoLawan : _form.find('input[name="p[AkunNoLawan]"]').val(),
							AkunPendapatan_RJ : _form.find('input[name="p[AkunPendapatan_RJ]"]').val(),
							AkunPendapatan_RI : _form.find('input[name="p[AkunPendapatan_RI]"]').val(),							
							KomponenName : _form.find('input[name="p[KomponenName]"]').val(),
							AkunNoName : _form.find('input[name="p[AkunNoName]"]').val(),
							AkunNoLawanName : _form.find('input[name="p[AkunNoLawanName]"]').val(),
						}
					
					if(_row_index == ''){				
						
						$('#dt_form_service_component').DataTable().row.add( service_component_data ).draw();
						_service_price_data.component_detail.push( service_component_data );
					}else {
						
						service_component_data.HargaLama = _service_component_data.HargaBaru;
						service_component_data.HargaHCLama = _service_component_data.HargaHCBaru;
						service_component_data.HargaBPJS_Lama = _service_component_data.HargaBPJS;
						
						$.each(service_component_data, function(key, val){
							_service_component_data[key] = val;
						});
						
						$("#dt_form_service_component").DataTable().row( _row_index ).data( _service_component_data ).draw();
						_service_price_data.component_detail[ _row_index ] = service_component_data;
					}
					
					$("#dt_form_price").DataTable().row( '.selected' ).data( _service_price_data ).draw();
					
					$("#ajaxModal").modal("hide");
					
				});
				
				

			});

	})( jQuery );
//]]>
</script>
