<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($form_action, ["id" => "form_admin_modal", "name" => "form_admin_modal", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body">
    <div class="form-group">
		<?php echo form_label(lang('label:name'), 'input_name', ['class' => 'control-label']) ?>
        <?php echo form_input('f[name]', set_value('f[name]', isset($item->name) ? $item->name : '', TRUE), [
                'id' => 'input_name', 
                'placeholder' => '', 
                'class' => 'form-control'
            ]); ?>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <?php echo form_label(lang('label:access'), 'input_access', ['class' => 'control-label']) ?>
            <?php echo form_dropdown('f[access]', $populate_access, set_value('f[access]', isset($item->access) ? $item->access : 'admin', TRUE), [
					'id' => 'input_access', 
					'placeholder' => '', 
					'class' => 'form-control'
				]); ?>
        </div>
        <div class="col-md-6">
            <?php echo form_label(lang('label:state'), 'input_state', ['class' => 'control-label']) ?>
            <?php echo form_dropdown('f[state]', $populate_state, set_value('f[state]', isset($item->state) ? $item->state : 1, TRUE), [
					'id' => 'input_state', 
					'placeholder' => '', 
					'class' => 'form-control'
				]); ?>
        </div>
    </div>
    <div class="well well-sm">
        <div class="form-group row">
            <div class="col-md-6">
                <?php echo form_label(lang('label:username'), 'input_username', ['class' => 'control-label']) ?>
                <?php echo form_input('f[username]', set_value('f[username]', isset($item->username) ? $item->username : '', TRUE), [
                        'id' => 'input_username', 
                        'placeholder' => '', 
                        'class' => 'form-control'
                    ]); ?>
            </div>
            <div class="col-md-6">
                <?php echo form_label(lang('label:password'), 'input_password', ['class' => 'control-label']) ?>
                <?php echo form_input('f[password]', set_value('f[password]', isset($item->password) ? $item->password : '', TRUE), [
                        'id' => 'input_password', 
                        'placeholder' => '', 
                        'class' => 'form-control'
                    ]); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label(lang('label:email'), 'input_email', ['class' => 'control-label']) ?>
            <?php echo form_input('f[email]', set_value('f[email]', isset($item->email) ? $item->email : '', TRUE), [
                    'id' => 'input_email', 
                    'placeholder' => '', 
                    'class' => 'form-control'
                ]); ?>
        </div>
        <div class="form-group">
			<?php echo form_label(lang('label:mobile'), 'input_mobile', ['class' => 'control-label']) ?>
            <?php echo form_input('f[mobile]', set_value('f[mobile]', isset($item->mobile) ? $item->mobile : '', TRUE), [
                    'id' => 'input_mobile', 
                    'placeholder' => '', 
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
			'content' => '<i class="fa fa-close" aria-hidden="true"></i> ' . lang('button:cancel'),
			'class' => 'btn btn-default',
			'data-dismiss' => 'modal'
		]); ?>
    <?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'submit',
			'content' => '<i class="fa fa-check-circle" aria-hidden="true"></i> ' . lang('button:submit'),
			'class' => 'btn btn-primary'
		]); ?>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[id="form_admin_modal"]' );			
				_form.appForm({
						onSuccess: function(result){ location.reload(); }
					});
			});
	})( jQuery );
//]]>
</script>


