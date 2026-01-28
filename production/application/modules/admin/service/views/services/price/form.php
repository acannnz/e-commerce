
<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open( @$form_action, [
		'id' => 'form_service_price', 
		'name' => 'form_service_price', 
		'rule' => 'form', 
		'class' => 'form-horizontal'
	]); ?>
<div class="panel-body table-responsive">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<?php echo form_label(lang('label:class').' *', 'KelasID', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_dropdown('p[KelasID]', $dropdown_class, set_value('p[KelasID]', @$item->KelasID, TRUE), [
						'id' => 'KelasID', 
						'placeholder' => '', 
						'class' => 'form-control select2'
					]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:patient_type').' *', 'JenisPasienID', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_dropdown('p[JenisPasienID]', $dropdown_patient_type, set_value('p[JenisPasienID]', @$item->JenisPasienID, TRUE), [
						'id' => 'JenisPasienID', 
						'placeholder' => '', 
						'class' => 'form-control'
					]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:specialist').' *', 'SpesialisID', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_dropdown('p[SpesialisID]', $dropdown_specialist, set_value('p[SpesialisID]', @$item->SpesialisID, TRUE), [
						'id' => 'SpesialisID', 
						'placeholder' => '', 
						'class' => 'form-control select2'
					]); ?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<div class="checkbox">
						<label for="SubSpesialis">
							<?php echo form_input([
									'type' => 'hidden',
									'name' => 'p[SubSpesialis]',
									'value' => 0,
								]); ?>
							<?php echo form_checkbox('p[SubSpesialis]', 1, (boolean) @$item->SubSpesialis, [
								'id' => 'SubSpesialis', 
							]); ?>
							<?php echo lang('label:sub_specialist') ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:doctor'), 'DokterID', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<div class="input-group">
						<?php echo form_input([
									'type' => 'hidden',
									'name' => 'p[DokterID]',
									'value' => set_value('p[DokterID]', @$item->DokterID, TRUE),
									'id' => 'DokterID',
									'class' => 'DokterID'
								]); ?>
								
						<?php echo form_input('NamaDokter', set_value('p[NamaDokter]', @$doctor->Nama_Supplier, TRUE), [
									'id' => 'NamaDokter', 
									'readonly' => 'readonly',
									'class' => 'form-control DokterID'
								]); ?>
						<span class="input-group-btn">
							<a href="javascript:;" data-action-url="<?php echo @$lookup_doctor ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:doctor'))?>" data-toggle="lookup-ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
							<a href="javascript:;" data-target-class="DokterID"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:operation_category').' *', 'KategoriOperasiID', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_dropdown('p[KategoriOperasiID]', $dropdown_operation_category, set_value('p[KategoriOperasiID]', @$item->KategoriOperasiID, TRUE), [
						'id' => 'KategoriOperasiID', 
						'placeholder' => '', 
						'class' => 'form-control select2'
					]); ?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<div class="row">
						<div class="col-md-6">
							<div class="checkbox">
								<label for="PasienKTP">
									<?php echo form_input([
											'type' => 'hidden',
											'name' => 'p[PasienKTP]',
											'value' => 0,
										]); ?>
									<?php echo form_checkbox('p[PasienKTP]', 1, (boolean) @$item->PasienKTP, [
										'id' => 'PasienKTP', 
									]); ?>
									<?php echo lang('label:pasien_ktp') ?>
								</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="checkbox">
								<label for="Cyto">
									<?php echo form_input([
											'type' => 'hidden',
											'name' => 'p[Cyto]',
											'value' => 0,
										]); ?>
									<?php echo form_checkbox('p[Cyto]', 1, (boolean) @$item->Cyto, [
										'id' => 'Cyto', 
									]); ?>
									<?php echo lang('label:cyto') ?>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:location').' *', 'Lokasi', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_dropdown('p[Lokasi]', $dropdown_location, set_value('p[Lokasi]', @$item->Lokasi, TRUE), [
						'id' => 'Lokasi', 
						'placeholder' => '', 
						'class' => 'form-control'
					]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:detail_insentif'), 'InsentifDetail', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[InsentifDetail]', set_value('p[InsentifDetail]', @$item->InsentifDetail, TRUE), [
							'id' => 'InsentifDetail', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:component_insentif'), 'InsentifKomponen', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[InsentifKomponen]', set_value('p[InsentifKomponen]', @$item->InsentifKomponen, TRUE), [
							'id' => 'InsentifKomponen', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
		</div>			
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<?php echo form_label(lang('label:old_price'), 'Harga_Lama', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[Harga_Lama]', set_value('p[Harga_Lama]', @$item->Harga_Lama, TRUE), [
							'id' => 'Harga_Lama', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:new_price'), 'Harga_Baru', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[Harga_Baru]', set_value('p[Harga_Baru]', @$item->Harga_Baru, TRUE), [
							'id' => 'Harga_Baru', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:old_price_hc'), 'HargaHC_Lama', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaHC_Lama]', set_value('p[HargaHC_Lama]', @$item->HargaHC_Lama, TRUE), [
							'id' => 'HargaHC_Lama', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:new_price_hc'), 'HargaHC_Baru', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[HargaHC_Baru]', set_value('p[HargaHC_Baru]', @$item->HargaHC_Baru, TRUE), [
							'id' => 'HargaHC_Baru', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control mask-number'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:discount_hc'), 'DiscHCUmum', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[DiscHCUmum]', set_value('p[DiscHCUmum]', @$item->DiscHCUmum, TRUE), [
							'id' => 'HargaHC_Baru', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:new_price_date'), 'TglHargaBaru', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[TglHargaBaru]', set_value('p[TglHargaBaru]', @$item->TglHargaBaru, TRUE), [
							'id' => 'TglHargaBaru', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control datepicker'
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
				<?php echo form_label(lang('label:new_price_bpjs_date'), 'TglHargaBaruBPJS', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('p[TglHargaBaruBPJS]', set_value('p[TglHargaBaruBPJS]', @$item->TglHargaBaruBPJS, TRUE), [
							'id' => 'TglHargaBaruBPJS', 
							'placeholder' => '',
							'required' => 'required',
							'class' => 'form-control datepicker'
						]); ?>
				</div>
			</div>
		</div>
	</div>
	
	<hr/>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group text-right">
				<button class="btn btn-warning" type="button" data-dismiss="modal"><?php echo lang( 'buttons:close' ) ?></button> 
				<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _row_index = '<?php echo @$row_index ?>';
		var _service_price_data = $("#dt_form_price").DataTable().row( _row_index ).data();
		console.log(_service_price_data);
		var _form = $("#form_service_price");
		var _form_actions = {
				init: function(){
					
					if( _row_index != ''){
						$.each(_service_price_data, function(key, val){
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
					
					mask_number.init();
				}
			}
						
		$( document ).ready(function(e) {
								
				_form_actions.init();
						
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var service_price_data = {
							ListHargaID : 0,
							KelasID : _form.find('select[name="p[KelasID]"]').val(),
							JenisPasienID : _form.find('select[name="p[JenisPasienID]"]').val(),
							PasienKTP : _form.find('input[name="p[PasienKTP]"]').is(':checked') ? 1 : 0,
							KategoriOperasiID : _form.find('select[name="p[KategoriOperasiID]"]').val(),
							DokterID : _form.find('input[name="p[DokterID]"]').val(),
							Harga_Lama : _form.find('input[name="p[Harga_Lama]"]').val(),
							Harga_Baru : _form.find('input[name="p[Harga_Baru]"]').val(),
							HargaHC_Lama : _form.find('input[name="p[HargaHC_Lama]"]').val(),
							HargaHC_Baru : _form.find('input[name="p[HargaHC_Baru]"]').val(),
							TglHargaBaru : _form.find('input[name="p[TglHargaBaru]"]').val(),
							SpesialisID : _form.find('select[name="p[SpesialisID]"]').val(),
							//SubSpesialisID : _form.find('input[name="p[SubSpesialisID]"]').val(),
							Cyto : _form.find('input[name="p[Cyto]"]').is(':checked') ? 1 : 0,
							Lokasi : _form.find('select[name="p[Lokasi]"]').val(),
							DiscHCUmum : _form.find('input[name="p[DiscHCUmum]"]').val(),
							SubSpesialis : _form.find('input[name="p[SubSpesialis]"]').is(':checked') ? 1 : 0,
							HargaBPJS : _form.find('input[name="p[HargaBPJS]"]').val(),
							HargaBPJS_Lama : _form.find('input[name="p[HargaBPJS_Lama]"]').val(),
							TglHargaBaruBPJS : _form.find('input[name="p[TglHargaBaruBPJS]"]').val(),
							InsentifKomponen : _form.find('input[name="p[InsentifKomponen]"]').val(),
							InsentifDetail : _form.find('input[name="p[InsentifDetail]"]').val(),
							//TglHargaBaruHC : _form.find('input[name="p[TglHargaBaruHC]"]').val(),
							//MappingInHealth : _form.find('input[name="p[MappingInHealth]"]').val(),
							NamaKelas : _form.find('select[name="p[KelasID]"] option:selected').html(),
							JenisKerjasama : _form.find('select[name="p[JenisPasienID]"] option:selected').html(),
							SpesialisName : _form.find('select[name="p[SpesialisID]"] option:selected').html(),
							NamaDokter : _form.find('input[name="NamaDokter"]').val(),
							KategoriOperasiName : _form.find('select[name="p[KategoriOperasiID]"] option:selected').html(),
						}
					
					if(_row_index == ''){
						service_price_data['component_detail'] = []; // set default
						$("#dt_form_price").DataTable().row.add( service_price_data ).draw();
					}else {
						$.each(service_price_data, function(key, val){
							_service_price_data[key] = val;
						});
						
						$("#dt_form_price").DataTable().row( _row_index ).data( _service_price_data ).draw();
					}
					
					$("#ajaxModal").modal("hide");
					
				});
				
				

			});

	})( jQuery );
//]]>
</script>
