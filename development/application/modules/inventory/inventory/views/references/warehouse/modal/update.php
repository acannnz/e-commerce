<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, [
		"id" => "form_crud__update", 
		"name" => "form_crud__update",
		"role" => "form"
	]); ?>
<div class="modal-body">
	<div class="form-group">
		<?php echo form_label(lang('label:code').' *', 'input_code', ['class' => 'control-label']) ?>
        <?php echo form_input('f[Kode_Gudang]', set_value('f[Kode_Gudang]', $item->Kode_Gudang, TRUE), [
				'id' => 'input_name', 
				'placeholder' => '', 
				'class' => 'form-control'
			]); ?>
    </div>
    <div class="form-group">
		<?php echo form_label(lang('label:name').' *', 'input_name', ['class' => 'control-label']) ?>
        <?php echo form_input('f[Nama_Gudang]', set_value('f[Nama_Gudang]', $item->Nama_Gudang, TRUE), [
				'id' => 'input_name', 
				'placeholder' => '', 
				'class' => 'form-control'
			]); ?>
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
				_form.appForm({onSuccess: function(result){ location.reload(); }});
			});
	})( jQuery );
//]]>
</script>

