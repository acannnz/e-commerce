<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_room', 
		'name' => 'form_room', 
		'rule' => 'form', 
		'class' => ''
	]); ?>

<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="panel panel-default">
            <div class="panel-heading">                
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <a href="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        <i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:room_update') : lang('heading:room_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-12 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:room_number').' *', 'NoKamar', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[NoKamar]', set_value('f[NoKamar]', @$item->NoKamar, FALSE), [
										'id' => 'NoKamar', 
										'placeholder' => '', 
										'required' => 'required', 
										'class' => 'form-control',
										'autocomplete' => 'off'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:sal').' *', 'SalID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[SalID]', $dropdown_sal, set_value('f[SalID]', @$item->SalID, TRUE), [
										'id' => 'SalID', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:floor_number').' *', 'NoLantai', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input([
										'name' => 'f[NoLantai]', 
										'value' => set_value('f[NoLantai]', @$item->NoLantai, TRUE),
										'id' => 'NoLantai', 
										'placeholder' => '', 
										'required' => 'required', 
										'class' => 'form-control',
										'type' => 'number',
										'autocomplete' => 'off'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:class').' *', 'KelasID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[KelasID]', $dropdown_class, set_value('f[KelasID]', @$item->KelasID, TRUE), [
										'id' => 'KelasID', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label('Opsi', 'Tambahan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-3">
								<?php echo form_hidden('f[Tambahan]', 0); ?>
								<?php echo form_checkbox([
										'id' => 'Tambahan',
										'name' => 'f[Tambahan]',
										'value' => 1,
										'checked' => set_value('f[Tambahan]', (boolean) @$item->Tambahan, TRUE),
										'class' => 'checkbox'
									]).' '.form_label('<b>'. lang('label:added').'</b>', 'Tambahan'); ?>
							</div>
							<div class="col-md-3">
								<?php echo form_hidden('f[DipakaiBOR]', 0); ?>
								<?php echo form_checkbox([
										'id' => 'DipakaiBOR',
										'name' => 'f[DipakaiBOR]',
										'value' => 1,
										'checked' => set_value('f[DipakaiBOR]', (boolean) @$item->DipakaiBOR, TRUE),
										'class' => 'checkbox'
									]).' '.form_label('<b>'. lang('label:bor').'</b>', 'DipakaiBOR'); ?>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button id="js-btn-submit" type="submit" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">New</button> 
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Close</button> 
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
		var _form = $("#form_room");
				
		$( document ).ready(function(e) {
				
				_form.on("submit", function(e){
					e.preventDefault();		
							
					$.post( _form.prop("action"), _form.serializeArray(), function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url($nameroutes); ?>";
							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
