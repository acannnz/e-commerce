 <?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open_multipart( @$form_action, [
		'id' => 'form_import', 
		'name' => 'form_import', 
		'rule' => 'form', 
		'class' => 'form-horizontal'
	]); ?>

<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="panel panel-default">
            <div class="panel-heading">                
                <h3 class="panel-title"><?php echo lang('heading:import'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
					<div class="form-group">
						<?php echo form_label(lang('label:import_type').' *', 'import_type', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-6">
						<?php echo form_dropdown('f[import_type]', @$dropdown_import_type, '', [
								'id' => 'import_type', 
								'placeholder' => '', 
								'class' => 'form-control',
								'required' => 'required'
							]); ?>
						</div>
					</div>
					<div class="form-group">
						<?php echo form_label(lang('label:file').' *', 'file', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-6">
							<?php echo form_upload('file', '', [
									'id' => 'file', 
									'placeholder' => '', 
									'class' => 'form-control',
									'required' => 'required'
								]); ?>
						</div>
					</div>
					<div class="form-group">
							<?php echo form_label('', '', ['class' => 'col-md-3 control-label']) ?>
							<div class="col-sm-6 col-xs-12">
								<label><?php echo form_radio([
										'name' => 'f[action]',
										'value' => 'preview',
										'checked' => TRUE,
										'class' => 'radio'
									]).' '.lang('buttons:preview')?></label> 
								<label><?php echo form_radio([
										'name' => 'f[action]',
										'value' => 'process',
										'class' => 'radio'
									]).' '.lang('buttons:process')?></label>
							</div>
						</div>
					<hr/>
					<div class="form-group">
						<div class="col-md-offset-3 col-md-6">
							<button id="btn-submit" type="submit" class="btn btn-primary btn-block"><?php echo lang( 'buttons:process' ) ?></button>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_import");						
		$( document ).ready(function(e) {							
			_form.on('submit', function(){
				$('#btn-submit').prop('disabled', 'disabled');
			});
		});

	})( jQuery );
//]]>
</script>
