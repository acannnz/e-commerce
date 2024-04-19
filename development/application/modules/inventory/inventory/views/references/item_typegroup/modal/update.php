<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
		"id" => "form_crud__update", 
		"name" => "form_crud__update",
		"role" => "form"
	]); ?>
<div class="modal-body">
	<div class="form-group">
		<?php echo form_label('Jenis *', 'input_type', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
			<?php echo form_input('f[KelompokJenis]', set_value('f[KelompokJenis]', @$item->KelompokJenis, TRUE), [
                    'id' => 'input_type', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
        </div>
    </div>
    <div class="form-group">
		<?php echo form_label('Kelompok *', 'input_group', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_dropdown('f[Kelompok]', $populate_group, set_value('f[Kelompok]', @$item->Kelompok, TRUE), [
                    'id' => 'input_group', 
                    'placeholder' => '',
                    'class' => 'select2',
                    'style' => ''
                ]); ?>
        </div>
    </div>
    <div class="form-group">
		<?php echo form_label('Pengadaan *', 'input_procurement', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
            <?php echo form_dropdown('f[JenisPengadaanID]', $populate_precurement, set_value('f[JenisPengadaanID]', @$item->JenisPengadaanID, TRUE), [
                    'id' => 'input_procurement', 
                    'placeholder' => '',
                    'class' => 'select2',
                    'style' => ''
                ]); ?>
        </div>
    </div>    
    <hr>    
    <div class="form-group">
		<?php echo form_label('ID Akun Pembelian', 'input_akun_pembelian', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
			<?php echo form_input('f[Akun_ID]', set_value('f[Akun_ID]', @$item->Akun_ID, TRUE), [
                    'id' => 'input_akun_pembelian', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
        </div>
    </div>
    <hr>
    <div class="form-group">
		<?php echo form_label('Akun Mutasi', 'input_akun_mutasi', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
        <div class="col-sm-8 col-xs-12">
			<?php echo form_input('f[Akun_ID_Mutasi]', set_value('f[Akun_ID_Mutasi]', @$item->Akun_ID_Mutasi, TRUE), [
                    'id' => 'input_akun_mutasi', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
        </div>
    </div>
    <div class="form-group">
		<div class="col-sm-8 col-sm-offset-4 col-xs-12">
            <?php echo form_hidden('f[TidakPostingMutasi]',0); ?>
            <?php echo form_checkbox([
                    'id' => 'checkbox_mutasi',
					'name' => 'f[TidakPostingMutasi]',
                    'value' => 1,
                    'checked' => (1 == @$item->TidakPostingMutasi),
                    'class' => 'checkbox'
                ]).' '.form_label('<b>Mutasi tidak diposting</b>', 'checkbox_mutasi'); ?>
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
			'content' => '<i class="fa fa-pencil" aria-hidden="true"></i> ' . lang('button:update'),
			'class' => 'btn btn-success'
		]); ?>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud__update"]' );
				_form.find('input[type="checkbox"].checkbox,input[type="radio"].radio').iCheck({
						checkboxClass: 'icheckbox_square-blue',
						radioClass: 'iradio_square-blue',
						increaseArea: '20%'
					});
				_form.find("select.select2").select2({
						minimumResultsForSearch: -1
					});
				
				_form.appForm({
						onSuccess: function(result){
								try{
									$("#dt_ref_item_type").DataTable().ajax.reload();
								} catch(e){
									location.reload(); 
								}
							}
					});
			});
	})( jQuery );
//]]>
</script>

