<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-body">
    <div class="form-group" style="margin: 27px 0px;">
    	<p style="margin-bottom: 0;line-height: 100%;font-size: 14px;"><?php echo lang('confirm:cancel_posting_split')?></p>   
        <?php echo form_hidden('confirm', 1); ?>
	</div>
	<div class="form-group">
		<?php echo form_label(lang('global:username'), 'username',['class' => 'control-label col-md-3']); ?>
		<div class="col-md-9">
			<?php echo form_input('approver[username]', '', [
					'id' => 'username', 
					'class' => 'form-control',
					'required' => TRUE
				]); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label(lang('global:password'), 'password',['class' => 'control-label col-md-3']); ?>
		<div class="col-md-9">
			<?php echo form_password('approver[password]', '', [
					'id' => 'password', 
					'class' => 'form-control',
					'required' => TRUE
				]); ?>
		</div>
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
			'id' => 'btn-submit',
			'value' => '',
			'type' => 'button',
			'content' => '<i class="fa fa-check" aria-hidden="true"></i> ' . lang('buttons:yes'),
			'class' => 'btn btn-danger'
		]); ?>
</div>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $("form#form_crud__list");
				var _selected_data = _form.find("input[name=\"val[]\"]:checked");
				var _form_section = _form.find("select[name=\"location_id\"]");
				
				$("#btn-submit").on("click", function(e){
					e.preventDefault();	
					
					var _selectedVals = _selected_data.map(function() {
						return this.value;
					}).get();

					var data_post = {};
						data_post['confirm'] = 1;
						data_post['selected'] = _selectedVals;
						data_post['approver'] = {
								'username' : $("#username").val(),
								'password' : $("#password").val(),
							};	
						data_post['additional'] = {
							};	
					
					$.post( _form.prop('action'), data_post, function( response, status, xhr ){

						if( "error" == response.status ){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						$('#dt_trans_posting_list').DataTable().ajax.reload();
						$('#btn-dismiss').trigger('click');
						//	location.reload();
							
					});
				});
				
			});
	})( jQuery );
//]]>
</script>