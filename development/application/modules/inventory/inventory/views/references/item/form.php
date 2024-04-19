<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>
		.select2-container .select2-choice {
			display: contents!important;
			line-height: 20px!important;
		}
		.select2-custom{
			display: block!important;
		}
			
</style>
<?php echo form_open($form_action, [
		"id" => "form_crud__create", 
		"name" => "form_crud__create", 
		"role" => "form"
	]); ?>
<div class="row">
	<div class="col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="panel panel-default panel-form">
            <div class="panel-heading">
                <?php /*?><div class="panel-btns">
                    <a href="" class="panel-close">&times;</a>
                    <a href="" class="minimize">&minus;</a>
                </div><?php */?>
                <h3 class="panel-title"><?php echo (!@$is_edit) ? 'Tambah Data Baru' : 'Perbarui Data Item'; ?></h3>
            </div>
        	<div class="panel-body" style="padding: 0px;">
        		<ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#form-general" data-toggle="tab"><i class="fa fa-barcode"></i> <strong><?php echo 'General'; ?></strong></a></li>
                    <li><a href="#form-detail" data-toggle="tab"><strong><i class="fa fa-pencil"></i> <?php echo 'Rincian'; ?></strong></a></li>
                    <li><a href="#form-location" data-toggle="tab"><strong><i class="fa fa-hospital-o"></i> <?php echo 'Lokasi'; ?></strong></a></li>
                    <li><a href="#form-package" data-toggle="tab"><strong><i class="fa fa-align-justify"></i> <?php echo 'Paket'; ?></strong></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="form-general">
                        <div class="row">
                        	<div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('Kelompok *', 'input_group', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kelompok]', @$populate_group, set_value('f[Kelompok]', @$item->Kelompok, TRUE), [
                                                'id' => 'input_group', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Jenis *', 'input_typegroup', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[KelompokJenis]', (array) @$populate_type_group, set_value('f[KelompokJenis]', @$item->KelompokJenis, TRUE), [
                                                'id' => 'input_typegroup select2', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Kode *', 'Kode_Barang', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Kode_Barang]', set_value('f[Kode_Barang]', @$item->Kode_Barang, TRUE), [
                                                'id' => 'Kode_Barang', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
								<?php 
									if(config_item('bpjs_bridging') == 'TRUE')
										echo modules::run('bpjs/drug/form_mapping', @$item->Kode_Barang_BPJS);
								?>
                                <div class="form-group">
                                    <?php echo form_label('Nama Barang *', 'Nama_Barang', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Nama_Barang]', set_value('f[Nama_Barang]', @$item->Nama_Barang, TRUE), [
                                                'id' => 'Nama_Barang', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('Kategori *', 'Kategori_Id', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kategori_Id]', $populate_category, set_value('f[Kategori_Id]', @$item->Kategori_Id, TRUE), [
                                                'id' => 'Kategori_Id', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control select2'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Sub Kategori *', 'SubKategori_Id', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[SubKategori_Id]', $populate_subcategory, set_value('f[SubKategori_Id]', @$item->SubKategori_Id, TRUE), [
                                                'id' => 'SubKategori_Id', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control select2'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Kelas *', 'Kelas_ID', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kelas_ID]', $populate_class, set_value('f[Kelas_ID]', @$item->Kelas_ID, TRUE), [
                                                'id' => 'Kelas_ID', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control select2'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Golongan *', 'GolonganID', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[GolonganID]', $populate_item_group, set_value('f[GolonganID]', @$item->GolonganID, TRUE), [
                                                'id' => 'GolonganID', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control select2'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                        	<div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('Status', 'Aktif', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_hidden('f[Aktif]', 0); ?>
                                        <?php echo form_checkbox([
                                                'id' => 'Aktif',
                                                'name' => 'f[Aktif]',
                                                'value' => 1,
                                                'checked' => set_value('f[Aktif]', (boolean) @$item->Aktif, TRUE),
                                                'class' => 'checkbox'
                                            ]).' '.form_label('<b>Aktif</b>', 'Aktif'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
                                    <?php echo form_label('Kelompok Grading *', 'KelompokGrading', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[KelompokGrading]', $populate_item_grading_group, set_value('f[KelompokGrading]', @$item->KelompokGrading, TRUE), [
                                                'id' => 'KelompokGrading', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control select2'
                                            ]); ?>
                                    </div>
                                </div>
                               <!-- <div class="form-group">
									<?php echo form_label('Opsi', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_hidden('f[FormulariumUmum]', 0); ?>
                                        <?php echo form_checkbox([
                                                'id' => 'FormulariumUmum',
                                                'name' => 'f[FormulariumUmum]',
                                                'value' => 1,
                                                'checked' => set_value('f[FormulariumUmum]', (boolean) @$item->FormulariumUmum, TRUE),
                                                'class' => 'checkbox'
                                            ]).' '.form_label('<b>Formularium Umum</b>', 'FormulariumUmum'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Opsi', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_hidden('f[Formularium]', 0); ?>
                                        <?php echo form_checkbox([
                                                'id' => 'Formularium',
                                                'name' => 'f[Formularium]',
                                                'value' => 1,
                                                'checked' => set_value('f[Formularium]', (boolean) @$item->Formularium, TRUE),
                                                'class' => 'checkbox'
                                            ]).' '.form_label('<b>Formularium JKN</b>', 'Formularium'); ?>
                                    </div>
                                </div> -->
								<div class="form-group">
									<?php echo form_label('Opsi', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
									<div class="col-sm-8 col-xs-12">
										<?php echo form_hidden('f[BarangKonsinyasi]', 0); ?>
										<?php echo form_checkbox([
											'id' => 'BarangKonsinyasi',
											'name' => 'f[BarangKonsinyasi]',
											'value' => 1,
											'checked' => set_value('f[BarangKonsinyasi]', (bool) @$item->BarangKonsinyasi, TRUE),
											'class' => 'checkbox'
										]) . ' ' . form_label('<b>Barang Konsinyasi</b>', 'BarangKonsinyasi'); ?>
									</div>
								</div>
                            </div>
                    	</div>
                    </div>
                    <div class="tab-pane" id="form-detail">
                        <div class="row">
                        	<div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
                                    <?php echo form_label('Satuan Beli *', 'Beli_Satuan_Id', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Beli_Satuan_Id]', $populate_purchase_unit, set_value('f[Beli_Satuan_Id]', @$item->Beli_Satuan_Id, TRUE), [
                                                'id' => 'Beli_Satuan_Id', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control select2'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Harga Beli *', 'Harga_Beli', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga_Beli]', set_value('f[Harga_Beli]', @$item->Harga_Beli, TRUE), [
                                                'id' => 'Harga_Beli', 
                                                'placeholder' => '', 
                                                'class' => 'form-control mask-number',
												'required' => true
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('PPN (%)', 'PPn_Persen', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[PPn_Persen]', set_value('f[PPn_Persen]', @$item->PPn_Persen, TRUE), [
                                                'id' => 'PPn_Persen', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
                                    <?php echo form_label('Satuan Stok *', 'Stok_Satuan_ID', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Stok_Satuan_ID]', $populate_stock_unit, set_value('f[Stok_Satuan_ID]', @$item->Stok_Satuan_ID, TRUE), [
                                                'id' => 'Stok_Satuan_ID', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control select2'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Konversi *', 'Konversi', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input([
                                                'id' => 'Konversi',
                                                'name' => 'f[Konversi]',
                                                'value' => set_value('f[Konversi]', @$item->Konversi, TRUE),
                                                'type' => 'text', 
                                                'class' => 'form-control',
												'required' => true
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Harga Jual *', 'Harga_Jual', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga_Jual]', set_value('f[Harga_Jual]', @$item->Harga_Jual, TRUE), [
                                                'id' => 'Harga_Jual', 
                                                'placeholder' => '', 
                                                'class' => 'form-control mask-number',
												'required' => true
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Harga Rata-Rata *', 'HRataRata', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[HRataRata]', set_value('f[HRataRata]', @$item->HRataRata, TRUE), [
                                                'id' => 'HRataRata', 
                                                'placeholder' => '', 
                                                'class' => 'form-control mask-number'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                    	</div>
                        <hr>
                        <div class="row">
                        	<div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('CN-ON Faktur (%)', 'CNOnFaktur', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[CNOnFaktur]', set_value('f[CNOnFaktur]', @$item->CNOnFaktur, TRUE), [
                                                'id' => 'CNOnFaktur', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('CN-OFF Faktur (%)', 'CNOffFaktur', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[CNOffFaktur]', set_value('f[CNOffFaktur]', @$item->CNOffFaktur, TRUE), [
                                                'id' => 'CNOffFaktur', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('Supplier *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="row lookupbox7-form-control">
                                            <div class="col-sm-8 col-xs-12">
                                                <?php echo form_hidden('f[Supplier_ID]', set_value('f[Supplier_ID]', @$item->Supplier_ID, TRUE)); ?>
                                                <?php echo form_input('t[Nama_Supplier]', set_value('t[Nama_Supplier]', @$supplier->Nama_Supplier, TRUE), [
                                                        'placeholder' => '', 
                                                        'class' => 'form-control lookupbox7-input-search'
                                                    ]); ?>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <?php echo form_button([
                                                        'type' => 'button',
                                                        'content' => '<i class="fa fa-search"></i>',
                                                        'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
                                                    ]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    	</div>
                    </div>
                    <div class="tab-pane" id="form-location">
                        <div class="row mb10">
							<div class="col-sm-12 col-xs-12">
								<?php echo form_label('', '', ['class' => 'col-sm-3 col-xs-12 text-right control-label']) ?>
								<div class="col-sm-6 col-xs-12">
									<div class="row lookupbox7-form-control">
										<div class="col-sm-8 col-xs-12">
											<?php echo form_input('t[Section_Name]', '', [
													'placeholder' => '', 
													'class' => 'form-control lookupbox7-input-search'
												]); ?>
										</div>
										<div class="col-sm-4 col-xs-12">
											<?php echo form_button([
													'type' => 'button',
													'content' => '<i class="fa fa-search"></i>',
													'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
												]); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
                        <div class="table-responsive">
                            <table id="DT_Input_Location" class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
										<th></th>
                                        <th><?php echo 'Lokasi'; ?></th>
                                        <th><?php echo 'Qty. Stok'; ?></th>
                                        <th><?php echo 'Min. Stok'; ?></th>
                                        <th><?php echo 'Max. Stok'; ?></th>
                                        <th><?php echo 'Aktif'; ?></th>
                                    </tr>
                                </thead>        
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="form-package">
                        <div class="row mb10">
							<div class="col-sm-12 col-xs-12">
								<?php echo form_label('', '', ['class' => 'col-sm-3 col-xs-12 text-right control-label']) ?>
								<div class="col-sm-6 col-xs-12">
									<div class="row lookupbox7-form-control">
										<div class="col-sm-8 col-xs-12">
											<?php echo form_input('t[Paket_Name]', '', [
													'placeholder' => '', 
													'class' => 'form-control lookupbox7-input-search'
												]); ?>
										</div>
										<div class="col-sm-4 col-xs-12">
											<?php echo form_button([
													'type' => 'button',
													'content' => '<i class="fa fa-search"></i>',
													'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
												]); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
                        <div class="table-responsive">
                            <table id="DT_Input_Package" class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
										<th></th>
                                        <th><?php echo 'Kode'; ?></th>
                                        <th><?php echo 'Nama Barang'; ?></th>
                                        <th><?php echo 'Satuan'; ?></th>
                                        <th><?php echo 'Jumlah'; ?></th>
                                    </tr>
                                </thead>        
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
            	<?php echo anchor(
							base_url($nameroutes),	
							'<i class="fa fa-times" aria-hidden="true"></i> ' . lang('button:cancel'),
							[
								'class' => 'btn btn-default',
							]
						); ?>
					<?php echo form_button([
							'name' => '',
							'id' => 'js-btn-submit',
							'value' => '',
							'type' => 'button',
							'content' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . lang('button:submit'),
							'class' => 'btn btn-primary'
						]); ?>
				<?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo site_url("themes/bracketadmin/vendor/lookupbox7/jquery.lookupbox7.js"); ?>"></script>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		function populate_typegroup(v){
			var group = this.find('select[name="f[Kelompok]"]').val();
			
			_input = this.find('select[name="f[KelompokJenis]"]');				
			_input.html('<option value="0"><?php echo lang('ajax:loading'); ?></option>');
			_input.attr('disabled', 'disabled');
			_input.load('<?php echo site_url("{$nameroutes}/get_typegroup_list") ?>',{'g': group}, function(response, status){
					_input.removeAttr('disabled');
					_input.val(v || '');
				});
		}
		
		var _datatable_location;
		var _datatable_location_actions = {
				edit: function( row, data, index ){
												
						switch( this.index() ){									
							case 3:
							
								var _input = $( "<input type=\"text\" value=\"" + parseFloat(data.Min_Stok) + "\" style=\"width:100%\"  class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Min_Stok = this.value || 0;
											_datatable_location.row( row ).data( data );
										} catch(ex){}
									});
							break;
							
							case 4:
							
								var _input = $( "<input type=\"text\" value=\"" + parseFloat(data.Max_Stok) + "\" style=\"width:100%\"  class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Max_Stok = this.value || 0;
											_datatable_location.row( row ).data( data );
										} catch(ex){}
									});
							break;
							
							case 5:
								if ( data.Aktif == 0 ) {
									var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"1\"><?php echo lang('global:active')?></option>\n<option value=\"0\" selected><?php echo lang('global:inactive')?></option>\n</select>" );
								} else if ( data.Aktif == 1 ) {
									var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"1\" selected><?php echo lang('global:active')?></option>\n<option value=\"0\"><?php echo lang('global:inactive')?></option>\n</select>" );
								}
								
								this.empty().append( _input );
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable_location.row( row ).data( data );
										} catch(ex){}
									});
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{

											data.Aktif =  $( e.target ).find( "option:selected" ).val() || 1;
											
											_datatable_location.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;
						}
						
					},
			};
		
		var _datatable_package;
		var _datatable_package_actions = {
				edit: function( row, data, index ){			
						switch( this.index() ){									
							case 4:							
								var _input = $( "<input type=\"text\" value=\"" + parseFloat(data.Jumlah) + "\" style=\"width:100%\"  class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Jumlah = this.value || 0;
											_datatable_package.row( row ).data( data );
										} catch(ex){}
									});
							break;
						}
					},
			};
		
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud__create"]' );
				_form.find('input[type="checkbox"].checkbox,input[type="radio"].radio').iCheck({
						checkboxClass: 'icheckbox_square-blue',
						radioClass: 'iradio_square-blue',
						increaseArea: '20%'
					});
					
				_form.find('select[name="f[Kelompok]"]').on('change', function(e){
						e.preventDefault();
						populate_typegroup.call(_form);
					});
				
				
				<?php /*?>_form.find('input[name="f[Barang_ID]"]').lookupbox7({
						remote: '<?php echo site_url('inventory/references/item/lookup'); ?>',
						title: 'Daftar Pilihan Barang',
						columns: [
								{data: "Kode", orderable: true, searchable: true, width: "70px"},
								{data: "Nama", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Barang'],
						order: [[1, 'asc']],
						placeholder: 'Ketik cari barang',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="t[Kode_Barang]"]').val(v.Kode);
								_form.find('input[name="t[Nama_Barang]"]').val(v.Nama);
								_form.find('input[name="f[Barang_ID]"]').val(v.Id);
							}
					});<?php */?>
					
				_form.find('input[name="t[Nama_Supplier]"]').lookupbox7({
						remote: '<?php echo site_url('inventory/references/supplier/lookup'); ?>',
						title: 'Daftar Pilihan Supplier',
						columns: [
								{data: "Kode", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Nama", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Supplier'],
						order: [[1, 'asc']],
						placeholder: 'Ketik cari supplier',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="t[Kode_Supplier]"]').val(v.Kode);
								_form.find('input[name="t[Nama_Supplier]"]').val(v.Nama);
								_form.find('input[name="f[Supplier_ID]"]').val(v.Id);
							}
					});
					
				
				_form.find('input[name="t[Section_Name]"]').lookupbox7({
						remote: '<?php echo site_url('inventory/references/section/lookup'); ?>',
						title: 'Daftar Pilihan Section',
						columns: [
								{data: "Id", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Nama", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Sections'],
						order: [[1, 'asc']],
						placeholder: 'Ketik cari section',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								var _row = {
										Lokasi_ID : v.Lokasi_ID,
										SectionName : v.Nama,
										Qty_Stok : 0,
										Min_Stok : 0,
										Max_Stok : 0,
										Aktif : 1,
									};
								_datatable_location.row.add( _row ).draw();
							}
					});
					
				_datatable_location = $('#DT_Input_Location').DataTable( {
						dom: 'tip',
						processing: true,
						serverSide: false,								
						paginate: false,
						ordering: false,
						searching: false,
						info: false,
						responsive: true,
						scrollCollapse: true,
						data: <?php print_r(json_encode(@$collection_location, JSON_NUMERIC_CHECK)); ?>,
						columns: [
								{ 
									data: "Lokasi_ID", 
									className: "actions text-center", 
									render: function( val, type, row, meta ){
											return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>")
										} 
								},
								{ data: "SectionName"},
								{ data: "Qty_Stok"},
								{ data: "Min_Stok"},
								{ data: "Max_Stok" },
								{ 
									data: "Aktif",
									render: function( val ){
										return val == 1 ? '<?php echo lang('global:active')?>' : '<?php echo lang('global:inactive')?>';
									}
								}
							],
						createdRow: function ( row, data, index ){												
								$( row ).on( "click", "td",  function(e){
										e.preventDefault();												
										var elem = $( e.target );
										_datatable_location_actions.edit.call( elem, row, data, index );
									});
									
								$( row ).on( "click", "a.btn-remove", function(e){
										e.preventDefault();												
										var elem = $( e.target );
										
										if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
											$('#DT_Input_Location').DataTable().row( row ).remove().draw();
										}
									})
							}
					} );
				
				_form.find('input[name="t[Paket_Name]"]').lookupbox7({
						remote: '<?php echo site_url('inventory/references/item/lookup_item_collection'); ?>',
						title: 'Daftar Pilihan Barang',
						columns: [
								{data: "Kode_Barang", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Nama_Barang", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Barang'],
						order: [[1, 'asc']],
						placeholder: 'Ketik cari barang',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								var _row = {
										Barang_ID : v.Barang_ID,
										Nama_Barang : v.Nama_Barang,
										Kode_Barang : v.Kode_Barang,
										Satuan : v.Kode_Satuan,
										Jumlah : 0,
									};
								_datatable_package.row.add( _row ).draw();
							}
					});
				
				_datatable_package = $('#DT_Input_Package').DataTable( {
						dom: 'tip',
						processing: true,
						serverSide: false,								
						paginate: false,
						ordering: false,
						searching: false,
						info: false,
						responsive: true,
						scrollCollapse: true,
						data: <?php print_r(json_encode(@$collection_package, JSON_NUMERIC_CHECK)); ?>,
						columns: [
								{ 
									data: "Barang_ID", 
									className: "actions text-center", 
									render: function( val, type, row, meta ){
										return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>")
									} 
								},
								{ data: "Kode_Barang"},
								{ data: "Nama_Barang"},
								{ data: "Satuan"},
								{ data: "Jumlah" },
							],
						createdRow: function ( row, data, index ){												
								$( row ).on( "click", "td",  function(e){
										e.preventDefault();												
										var elem = $( e.target );
										_datatable_package_actions.edit.call( elem, row, data, index );
									});
									
								$( row ).on( "click", "a.btn-remove", function(e){
										e.preventDefault();												
										var elem = $( e.target );
										
										if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
											$('#DT_Input_Package').DataTable().row( row ).remove().draw();
										}
									})
							}
					} );
				
				$('#js-btn-submit').on('click', function(e){
					
					var _this = $(this);
					
					_this.addClass('disabled');
					
					post_data = _form.serializeArray();
					$.each( _datatable_location.rows().data(), function( i, v){
						post_data.push({name: 'collection_location['+ i +'][Lokasi_ID]', value: v.Lokasi_ID});
						post_data.push({name: 'collection_location['+ i +'][Barang_ID]', value: v.Barang_ID});
						post_data.push({name: 'collection_location['+ i +'][Qty_Stok]', value: v.Qty_Stok});
						post_data.push({name: 'collection_location['+ i +'][Min_Stok]', value: v.Min_Stok});
						post_data.push({name: 'collection_location['+ i +'][Max_Stok]', value: v.Max_Stok});
						post_data.push({name: 'collection_location['+ i +'][Aktif]', value: v.Aktif});
						post_data.push({name: 'collection_location['+ i +'][Kode_Satuan]', value: $("#Stok_Satuan_ID option:selected").html()});
					});

					$.each( _datatable_package.rows().data(), function( i, v){
						post_data.push({name: 'collection_package['+ i +'][Barang_ID_Penyusun]', value: v.Barang_ID_Penyusun});
						post_data.push({name: 'collection_package['+ i +'][Satuan_ID]', value: v.Satuan_ID});
						post_data.push({name: 'collection_package['+ i +'][Jumlah]', value: v.Jumlah});
					});
					
					$.post( _form.prop('action'), post_data, function( response, status, xhr ){
						
							if ( response.success == false)
							{
								$.alert_error( response.message );
								return false;
							}
														
							$.alert_success( response.message );
							setTimeout(function(){		
								document.location.href = '<?php echo base_url($nameroutes); ?>';							
								}, 300 );
						}).fail(function() {
							$.alert_error( '<?php echo lang('general_error_label');?>' );
						}).always(function(){
							_this.removeClass('disabled');
						});						
				});
				// beforeSerialize
				/*_form.appForm({
						onSuccess: function(result){
								try{
									$("#dt_ref_section").DataTable().ajax.reload();
								} catch(e){
									location.reload(); 
								}
							}
					});*/
			});
	})( jQuery );
//]]>
</script>
