<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
		"id" => "form_crud__update", 
		"name" => "form_crud__update", 
		"role" => "form"
	]); ?>
<div class="modal-body">
	<div class="well well-sm">
        <div class="form-group">
            <?php echo form_label('Lokasi', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
                <p class="form-control-static"><?php echo @$m_section->SectionName; ?></p>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Kode Barang', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
                <p class="form-control-static"><?php echo @$m_item->Kode_Barang; ?></p>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Nama Barang', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
                <p class="form-control-static"><?php echo @$m_item->Nama_Barang; ?></p>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Jenis Barang', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
                <p class="form-control-static"><?php echo @$m_type->NmJenis; ?></p>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Satuan', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
                <p class="form-control-static"><?php echo @$m_satuan->Nama_Satuan; ?></p>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Quantitas Stok', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
            <div class="col-sm-8 col-xs-12">
                <p class="form-control-static"><?php echo (int) @$m_item->Qty_Stok; ?></p>
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="form-group">
		<?php echo form_label('Minimum Stok', 'input_min_stock', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input([
                    'id' => 'input_min_stock',
					'name' => 'f[Min_Stok]',
					'value' => (int) set_value('f[Min_Stok]', @$item->Min_Stok, TRUE),
					'type' => 'number', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Maximum Stok', 'input_max_stock', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input([
                    'id' => 'input_max_stock',
					'name' => 'f[Max_Stok]',
					'value' => (int) set_value('f[Max_Stok]', @$item->Max_Stok, TRUE),
					'type' => 'number', 
                    'class' => 'form-control'
                ]); ?>
    	</div>
    </div>
    <div class="form-group">
		<?php echo form_label('Death Stok', 'input_death_stock', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_input([
                    'id' => 'input_death_stock',
					'name' => 'f[Death_Stok]',
					'value' => (int) set_value('f[Death_Stok]', @$item->Death_Stok, TRUE),
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
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud__update"]' );
				
				_form.appForm({
						onSuccess: function(result){
								try{
									$("#dt_ref_item_location").DataTable().ajax.reload();
								} catch(e){
									location.reload(); 
								}
							}
					});
			});
	})( jQuery );
//]]>
</script>
