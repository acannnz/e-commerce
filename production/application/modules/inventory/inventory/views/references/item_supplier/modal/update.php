<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
		"id" => "form_crud__create", 
		"name" => "form_crud__create", 
		"role" => "form"
	]); ?>
<div class="modal-body">
	<div class="form-group">
		<?php echo form_label('Barang *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
			<div class="row lookupbox7-form-control">
            	<div class="col-sm-4 col-xs-12">
                	<?php echo form_input('t[Kode_Barang]', set_value('t[Kode_Barang]', @$m_item->Kode_Barang, TRUE), [
							'placeholder' => '', 
							'readonly' => 'readonly',
							'class' => 'form-control'
						]); ?>
                </div>
                <div class="col-sm-6 col-xs-12">
                	<?php echo form_input('t[Nama_Barang]', set_value('t[Nama_Barang]', @$m_item->Nama_Barang, TRUE), [
							'placeholder' => '', 
							'class' => 'form-control lookupbox7-input-search'
						]); ?>
                </div>
                <div class="col-sm-2 col-xs-12">
                	<?php echo form_button([
							'type' => 'button',
							'content' => '...',
							'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
						]); ?>
                	<?php echo form_hidden('f[Barang_ID]', set_value('f[Barang_ID]', @$item->Barang_ID, TRUE)); ?>
					<?php echo form_hidden('f[IDJenisBarang]', set_value('f[IDJenisBarang]', '', TRUE)); ?>
                </div>
            </div>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Supplier *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
			<div class="row lookupbox7-form-control">
            	<div class="col-sm-4 col-xs-12">
                	<?php echo form_input('t[Kode_Supplier]', set_value('t[Kode_Supplier]', @$m_supplier->Kode_Supplier, TRUE), [
							'placeholder' => '', 
							'readonly' => 'readonly',
							'class' => 'form-control'
						]); ?>
                </div>
                <div class="col-sm-6 col-xs-12">
                	<?php echo form_input('t[Nama_Supplier]', set_value('t[Nama_Supplier]', @$m_supplier->Nama_Supplier, TRUE), [
							'placeholder' => '', 
							'class' => 'form-control lookupbox7-input-search'
						]); ?>
                </div>
                <div class="col-sm-2 col-xs-12">
                	<?php echo form_button([
							'type' => 'button',
							'content' => '...',
							'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
						]); ?>
                	<?php echo form_hidden('f[SupplierID]', set_value('f[SupplierID]', @$item->SupplierID, TRUE)); ?>
                </div>
            </div>
    	</div>
    </div>
    <hr>
    <div class="form-group">
		<?php echo form_label('Harga Beli *', 'input_price', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[Harga]', (float) set_value('f[Harga]', @$item->Harga, TRUE), [
                    'id' => 'input_price', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Tanggal Beli Terakhir*', 'input_date', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input('f[Tgl_Beli_Terakhir]', set_value('f[Tgl_Beli_Terakhir]', @$item->Tgl_Beli_Terakhir, TRUE), [
                    'id' => 'input_date', 
                    'placeholder' => 'dd/mm/yyyy', 
                    'class' => 'form-control datepicker'
                ]); ?>
    	</div>
    </div>
    <hr>
    <div class="form-group">
		<?php echo form_label('Kerjasama', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_hidden('f[Kerjasama]', 0); ?>
            <?php echo form_checkbox([
                    'id' => 'checkbox_join',
					'name' => 'f[Kerjasama]',
                    'value' => 1,
                    'checked' => (1 == $item->Kerjasama),
                    'class' => 'checkbox'
                ]).' '.form_label('<b>Ya</b>', 'checkbox_join'); ?>
        </div>
    </div>
    <div class="form-group">
		<?php echo form_label('Minimum Order', 'input_min_order', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input([
                    'id' => 'input_min_order',
					'name' => 'f[MinOrder]',
					'value' => (int) set_value('f[MinOrder]', @$item->MinOrder, TRUE),
					'type' => 'number', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Minimum Stok', 'input_min_stock', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input([
                    'id' => 'input_min_stock',
					'name' => 'f[MinStok]',
					'value' => (int) set_value('f[MinStok]', @$item->MinStok, TRUE),
					'type' => 'number', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
</div>
<div class="modal-footer">
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
</div>
<?php echo form_close() ?>
<script src="<?php echo site_url("themes/bracketadmin/vendor/lookupbox7/jquery.lookupbox7.js"); ?>"></script>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				$('.datepicker').datepicker({format: 'yyyy-mm-dd'});
				
				var _form = $( 'form[name="form_crud__create"]' );
				_form.find('input[type="checkbox"].checkbox,input[type="radio"].radio').iCheck({
						checkboxClass: 'icheckbox_square-blue',
						radioClass: 'iradio_square-blue',
						increaseArea: '20%'
					});
				
				_form.find('input[name="f[Barang_ID]"]').lookupbox7({
						remote: '<?php echo site_url('inventory/references/item/lookup_collection'); ?>',
						title: 'Daftar Pilihan Barang',
						columns: [
								{data: "Kode_Barang", orderable: true, searchable: true, width: "70px"},
								{data: "Nama_Barang", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Barang'],
						order: [[1, 'asc']],
						placeholder: 'Ketik cari barang',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="t[Kode_Barang]"]').val(v.Kode_Barang);
								_form.find('input[name="t[Nama_Barang]"]').val(v.Nama_Barang);
								_form.find('input[name="f[Barang_ID]"]').val(v.Barang_ID);
								_form.find('input[name="f[IDJenisBarang]"]').val(v.JenisBarangID);
								_form.find('input[name="f[Harga]"]').val(mask_number.currency_remove(v.Harga_Beli));
							}
					});
				_form.find('input[name="f[SupplierID]"]').lookupbox7({
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
					});
				
				_form.appForm({
						onSuccess: function(result){
								try{
									$("#dt_ref_section").DataTable().ajax.reload();
								} catch(e){
									location.reload(); 
								}
							}
					});
			});
	})( jQuery );
//]]>
</script>
