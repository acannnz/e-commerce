<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open($form_action, [
		"id" => "form_crud__delete", 
		"name" => "form_crud__delete", 
		"role" => "form"
	]); ?><div class="modal-body">
    <div class="form-group" style="margin: 27px 0px;">
    	<p style="margin-bottom: 0;line-height: 100%;font-size: 14px;"><?php echo lang('message:delete_confirm')?></p>   
        <?php echo form_hidden('confirm', 1); ?>
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
			'content' => '<i class="fa fa-trash-o" aria-hidden="true"></i> ' . lang('button:delete'),
			'class' => 'btn btn-danger'
		]); ?>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud__delete"]' );
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