<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
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
                <h3 class="panel-title"><?php echo 'Tambah Data Baru'; ?></h3>
            </div>
        	<div class="panel-body" style="padding: 0px;">
        		<ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#form-general" data-toggle="tab"><i class="fa fa-barcode"></i> <strong><?php echo 'General'; ?></strong></a></li>
                    <li><a href="#form-detail" data-toggle="tab"><strong><i class="fa fa-pencil"></i> <?php echo 'Rincian'; ?></strong></a></li>
                    <li><a href="#form-location" data-toggle="tab"><strong><i class="fa fa-pencil"></i> <?php echo 'Lokasi'; ?></strong></a></li>
                    <li><a href="#form-package" data-toggle="tab"><strong><i class="fa fa-pencil"></i> <?php echo 'Paket'; ?></strong></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="form-general">
                        <div class="row">
                        	<div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('Kelompok *', 'input_group', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kelompok]', $populate_group, set_value('f[Kelompok]', '', TRUE), [
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
                                        <?php echo form_dropdown('f[KelompokJenis]', [0 => 'Pilih Jenis'], set_value('f[KelompokJenis]', '', TRUE), [
                                                'id' => 'input_typegroup', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Kode *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga]', set_value('f[Harga]', '', TRUE), [
                                                'id' => 'input_price', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Nama Barang *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga]', set_value('f[Harga]', '', TRUE), [
                                                'id' => 'input_price', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('Kategori *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kategori_ID]', [0 => 'Pilih Kategori'], set_value('f[Kategori_ID]', 0, TRUE), [
                                                'id' => 'input_parent', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Sub Kategori *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kategori_ID]', [0 => 'Pilih Sub-Kategori'], set_value('f[Kategori_ID]', 0, TRUE), [
                                                'id' => 'input_parent', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Kelas *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kategori_ID]', [0 => 'Pilih Kelas'], set_value('f[Kategori_ID]', 0, TRUE), [
                                                'id' => 'input_parent', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Golongan *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kategori_ID]', [0 => 'Pilih Golongan'], set_value('f[Kategori_ID]', 0, TRUE), [
                                                'id' => 'input_parent', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                        	<div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('Status', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_hidden('f[Kerjasama]', 0); ?>
                                        <?php echo form_checkbox([
                                                'id' => 'checkbox_join',
                                                'name' => 'f[Kerjasama]',
                                                'value' => 1,
                                                'checked' => false,
                                                'class' => 'checkbox'
                                            ]).' '.form_label('<b>Aktif</b>', 'checkbox_join'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
                                    <?php echo form_label('Golongan *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kategori_ID]', [0 => 'Pilih Golongan'], set_value('f[Kategori_ID]', 0, TRUE), [
                                                'id' => 'input_parent', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Opsi', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_hidden('f[Kerjasama]', 0); ?>
                                        <?php echo form_checkbox([
                                                'id' => 'checkbox_join',
                                                'name' => 'f[Kerjasama]',
                                                'value' => 1,
                                                'checked' => false,
                                                'class' => 'checkbox'
                                            ]).' '.form_label('<b>Formularium Umum</b>', 'checkbox_join'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Opsi', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_hidden('f[Kerjasama]', 0); ?>
                                        <?php echo form_checkbox([
                                                'id' => 'checkbox_join',
                                                'name' => 'f[Kerjasama]',
                                                'value' => 1,
                                                'checked' => false,
                                                'class' => 'checkbox'
                                            ]).' '.form_label('<b>Formularium JKN</b>', 'checkbox_join'); ?>
                                    </div>
                                </div>
                            </div>
                    	</div>
                    </div>
                    <div class="tab-pane" id="form-detail">
                        <div class="row">
                        	<div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
                                    <?php echo form_label('Satuan Beli *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kategori_ID]', [0 => 'Pilih Satuan'], set_value('f[Kategori_ID]', 0, TRUE), [
                                                'id' => 'input_parent', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('Harga Beli *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga]', set_value('f[Harga]', '', TRUE), [
                                                'id' => 'input_price', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo form_label('PPN (%)', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga]', set_value('f[Harga]', '', TRUE), [
                                                'id' => 'input_price', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
                                    <?php echo form_label('Satuan Stok *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_dropdown('f[Kategori_ID]', [0 => 'Pilih Satuan'], set_value('f[Kategori_ID]', 0, TRUE), [
                                                'id' => 'input_parent', 
                                                'placeholder' => '',
                                                'required' => 'required', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Konversi *', 'input_min_order', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input([
                                                'id' => 'input_min_order',
                                                'name' => 'f[MinOrder]',
                                                'value' => set_value('f[MinOrder]', 1, TRUE),
                                                'type' => 'number', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Harga Pokok *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga]', set_value('f[Harga]', '', TRUE), [
                                                'id' => 'input_price', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('Harga Rata-Rata *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga]', set_value('f[Harga]', '', TRUE), [
                                                'id' => 'input_price', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                            </div>
                    	</div>
                        <hr>
                        <div class="row">
                        	<div class="col-sm-6 col-xs-12">
                            	<div class="form-group">
									<?php echo form_label('CN-ON Faktur (%)', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga]', set_value('f[Harga]', '', TRUE), [
                                                'id' => 'input_price', 
                                                'placeholder' => '', 
                                                'class' => 'form-control'
                                            ]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php echo form_label('CN-OFF Faktur (%)', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo form_input('f[Harga]', set_value('f[Harga]', '', TRUE), [
                                                'id' => 'input_price', 
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
                                                <?php echo form_input('t[Nama_Barang]', set_value('t[Nama_Barang]', '', TRUE), [
                                                        'placeholder' => '', 
                                                        'class' => 'form-control lookupbox7-input-search'
                                                    ]); ?>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <?php echo form_button([
                                                        'type' => 'button',
                                                        'content' => '...',
                                                        'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
                                                    ]); ?>
                                                <?php echo form_hidden('f[Barang_ID]', set_value('f[Barang_ID]', '', TRUE)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    	</div>
                    </div>
                    <div class="tab-pane" id="form-location">
                        <div class="row">
                        	<div class="col-sm-12 col-xs-12">
                            	
                            </div>
                    	</div>
                        <div class="table-responsive">
                            <table id="DT_Input_Location" class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th><?php echo 'Lokasi'; ?></th>
                                        <th><?php echo 'Qty. Stok'; ?></th>
                                        <th><?php echo 'Min. Stok'; ?></th>
                                        <th><?php echo 'Max. Stok'; ?></th>
                                        <th><?php echo 'Aktif'; ?></th>
                                    </tr>
                                </thead>        
                                <tbody>
                                	<tr>
                                    	<td>empty</td>
                                        <td>empty</td>
                                        <td>empty</td>
                                        <td>empty</td>
                                        <td>empty</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="form-package">
                        <div class="row">
                        	<div class="col-sm-12 col-xs-12">
                            	
                            </div>
                    	</div>
                        <div class="table-responsive">
                            <table id="DT_Input_Package" class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th><?php echo 'Kode'; ?></th>
                                        <th><?php echo 'Nama Barang'; ?></th>
                                        <th><?php echo 'Satuan'; ?></th>
                                        <th><?php echo 'Jumlah'; ?></th>
                                    </tr>
                                </thead>        
                                <tbody>
                                	<tr>
                                    	<td>empty</td>
                                        <td>empty</td>
                                        <td>empty</td>
                                        <td>empty</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
            	<?php echo form_button([
							'name' => '',
							'id' => '',
							'value' => '',
							'type' => 'button',
							'content' => '<i class="fa fa-times" aria-hidden="true"></i> ' . lang('button:cancel'),
							'class' => 'btn btn-default',
							'data-dismiss' => 'modal'
						]); ?>
					<?php echo form_button([
							'name' => '',
							'id' => '',
							'value' => '',
							'type' => 'submit',
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
				<?php /*?>_form.find('input[name="f[SupplierID]"]').lookupbox7({
						remote: '<?php echo site_url('inventory/references/supplier/lookup'); ?>',
						title: 'Daftar Pilihan Supplier',
						columns: [
								{data: "Kode", orderable: true, searchable: true, width: "70px"},
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
								_form.find('input[name="f[SupplierID]"]').val(v.Id);
							}
					});<?php */?>
				
				<?php /*?>_form.appForm({
						onSuccess: function(result){
								try{
									$("#dt_ref_section").DataTable().ajax.reload();
								} catch(e){
									location.reload(); 
								}
							}
					});<?php */?>
			});
	})( jQuery );
//]]>
</script>
