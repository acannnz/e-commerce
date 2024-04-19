<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open($form_action, [
		"id" => "form_crud__delete", 
		"name" => "form_crud__delete", 
		"role" => "form"
	]); ?><div class="modal-body">
    <div class="form-group" style="margin: 27px 0px;">
    	<p style="margin-bottom: 0;line-height: 100%;font-size: 14px;"><?php echo lang('global:delete_confirm')?></p>   
        <?php echo form_hidden('confirm', 1); ?>
	</div>
</div>
<div class="modal-footer">
	<?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'submit',
			'content' => '<i class="fa fa-trash-o" aria-hidden="true"></i> ' . lang('buttons:delete'),
			'class' => 'btn btn-danger'
		]); ?>

    <?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'button',
			'content' => '<i class="fa fa-times" aria-hidden="true"></i> ' . lang('buttons:close'),
			'class' => 'btn btn-default',
			'data-dismiss' => 'modal'
		]); ?>
	</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud__delete"]' );
			});
	})( jQuery );
//]]>
</script>