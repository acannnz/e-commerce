<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_sub_specialist', 
		'name' => 'form_sub_specialist', 
		'rule' => 'form', 
		'class' => ''
	]); ?>


<div class="panel-body table-responsive">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="form-group">
				<?php echo form_label(lang('label:code'), 'SubSpesialisID', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[SubSpesialisID]', set_value('c[SubSpesialisID]', @$item->SubSpesialisID, TRUE), [
							'id' => 'SubSpesialisID', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo form_label(lang('label:name'), 'SubSpesialisName', ['class' => 'control-label col-md-3']) ?>
				<div class="col-md-9">
					<?php echo form_input('c[SubSpesialisName]', set_value('c[SubSpesialisName]', @$item->SubSpesialisName, TRUE), [
							'id' => 'SubSpesialisName', 
							'placeholder' => '',
							'class' => 'form-control'
						]); ?>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group text-right">
				<button id="js-btn-submit" type="button" class="btn btn-primary" data-dismiss="modal"><?php echo lang( 'buttons:save' ) ?></button>
				<button class="btn btn-default" type="button" data-dismiss="modal"><?php echo lang( 'buttons:close' ) ?></button> 
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _row_index = '<?php echo $row_index ?>';
		var _sub_specialist_data = $("#dt_sub_specialist").DataTable().row( _row_index ).data();
		var _form = $("#form_sub_specialist");
		var _form_actions = {
				init: function(){
						
					if( _row_index == ''){return;}
					
					$.each(_sub_specialist_data, function(key, val){
						_form.find('#'+ key ).val( val );
					});
					
					
				}
			}
				
		$( document ).ready(function(e) {
				
				_form_actions.init();
					
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var sub_specialist_data = {
						SubSpesialisID : _form.find('input[name="c[SubSpesialisID]"]').val(),
						SubSpesialisName : _form.find('input[name="c[SubSpesialisName]"]').val(),
					}
					
					if(_row_index == ''){
						$("#dt_sub_specialist").DataTable().row.add( sub_specialist_data ).draw();
					}else {
						$.each(sub_specialist_data, function(key, val){
							_sub_specialist_data[key] = val;
						});
						
						$("#dt_sub_specialist").DataTable().row( _row_index ).data( _sub_specialist_data ).draw();
					}
					
					$("#ajaxModal").modal("hide");
				});

			});

	})( jQuery );
//]]>
</script>
