<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open($form_action, ["id" => "form_admin_modal__delete", "name" => "form_admin_modal__delete", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body">
    <div class="form-group" style="margin: 27px 0px;">
    	<p style="margin-bottom: 0;line-height: 100%;font-size: 14px;"><?php echo lang('message:delete_confirm')?></p>            
    	<?php echo form_hidden('id', $item->id); ?>
	</div>
</div>
<div class="modal-footer">
    <?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'button',
			'content' => '<span class="fa fa-close"></span> ' . lang('button:cancel'),
			'class' => 'btn btn-default',
			'data-dismiss' => 'modal'
		]); ?>
    <?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'submit',
			'content' => '<span class="fa fa-times"></span> ' . lang('button:delete'),
			'class' => 'btn btn-danger'
		]); ?>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[id="form_admin_modal__delete"]');			
				_form.appForm({onSuccess: function(result){ location.reload(); }});
			});
	})( jQuery );
//]]>
</script>