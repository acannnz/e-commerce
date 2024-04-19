<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open($form_action, [
		"id" => "form_crud__cancel", 
		"name" => "form_crud__cancel", 
		"role" => "form"
	]); ?>
<div class="modal-body">
    <div class="form-group" style="margin: 27px 0px;">
    	<p style="margin-bottom: 0;line-height: 100%;font-size: 14px;"><?php echo lang('confirm:cancel_audit')?></p>   
        <?php echo form_hidden('confirm', 1); ?>
	</div>
</div>
<div class="modal-footer">
    <?php echo form_button([
			'name' => '',
			'id' => 'btn-dismiss',
			'value' => '',
			'type' => 'button',
			'content' => '<i class="fa fa-times" aria-hidden="true"></i> ' . lang('buttons:close'),
			'class' => 'btn btn-default',
			'data-dismiss' => 'modal'
		]); ?>
	<?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'submit',
			'content' => '<i class="fa fa-trash-o" aria-hidden="true"></i> ' . lang('button:cancel'),
			'class' => 'btn btn-danger'
		]); ?>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud__cancel"]' );
				_form.appForm({
						onSuccess: function(result){ 
								if( "error" == result.status ){
									$.alert_error( result.message );
									return false
								}
								
								$.alert_success( result.message );
								$('#dt_trans_posting_list').DataTable().ajax.reload();
							}
					});	
			});
	})( jQuery );
//]]>
</script>