<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_user', 
		'name' => 'form_user', 
		'rule' => 'form', 
		'class' => ''
	]); ?>

<div class="row">
	<div class="col-md-offset-1 col-md-10">
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
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:user_update') : lang('heading:user_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-3 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:username').' *', 'Username', ['class' => 'control-label']) ?>
							<?php echo form_input('f[Username]', set_value('f[Username]', @$item->Username, TRUE), [
									'id' => 'Username', 
									'placeholder' => '', 
									'required' => 'required', 
									'class' => 'form-control'
								]); ?>
                        </div>
					</div>
					<div class="col-md-3 col-xs-12">
						<div class="form-group">
							<?php echo form_label(lang('label:name').' *', 'Nama_Asli', ['class' => 'control-label']) ?>
							<?php echo form_input('f[Nama_Asli]', set_value('f[Nama_Asli]', @$item->Nama_Asli, TRUE), [
									'id' => 'Nama_Asli', 
									'placeholder' => '', 
									'required' => 'required', 
									'class' => 'form-control'
								]); ?>
						</div>
					</div>
					<div class="col-md-3 col-xs-12">
						<div class="form-group">
							<?php echo form_label(lang('label:nick_name').' *', 'Nama_Singkat', ['class' => 'control-label']) ?>
							<?php echo form_input('f[Nama_Singkat]', set_value('f[Nama_Singkat]', @$item->Nama_Singkat, TRUE), [
									'id' => 'Nama_Singkat', 
									'placeholder' => '', 
									'required' => 'required', 
									'class' => 'form-control'
								]); ?>
						</div>
					</div>
					<div class="col-md-3 col-xs-12">
						<div class="form-group">
							<?php echo form_label('Opsi', 'Status_Aktif', ['class' => 'control-label']) ?>
							<?php echo form_hidden('f[Status_Aktif]', 0); ?>
							<div class="row">
								<div class="col-md-12">
									<?php echo form_checkbox([
											'id' => 'Status_Aktif',
											'name' => 'f[Status_Aktif]',
											'value' => 1,
											'checked' => set_value('f[Status_Aktif]', (boolean) @$item->Status_Aktif, TRUE),
											'class' => 'checkbox'
										]).' '.form_label('<b>'. lang('global:active').'</b>', 'Status_Aktif'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<?php if(@$is_edit): ?>
					<div class="col-md-3 col-xs-12">
						<div class="form-group">
							<?php echo form_label('Old '.lang('label:password').' *', 'PasswordWebOld', ['class' => 'control-label']) ?>
							<?php echo form_password('c[OldPasswordWeb]', NULL, [
									'id' => 'PasswordWebOld', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
						</div>
					</div>
					<?php endif; ?>
					<div class="col-md-3 col-xs-12">
						<div class="form-group">
							<?php echo form_label(lang('label:password').' *', 'PasswordWeb', ['class' => 'control-label']) ?>
							<?php echo form_password('f[PasswordWeb]', NULL, [
									'id' => 'PasswordWeb', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
						</div>
                    </div>
					<div class="col-md-3 col-xs-12">
						<div class="form-group">
							<?php echo form_label(lang('label:confirm_password').' *', 'PasswordWebConfirm', ['class' => 'control-label']) ?>
							<?php echo form_password('c[PasswordWeb]', NULL, [
									'id' => 'PasswordWebConfirm', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<h3>Role</h3>
					<div class="col-md-4 col-xs-12">
						<ul>
							<?php foreach($role_collection as $item): ?>
								<li>
									<?php echo form_checkbox([
											'id' => $item->Group_ID,
											'name' => 'role[]',
											'value' => $item->Group_ID,
											'checked' => in_array($item->Group_ID, @$role_selected),
											'class' => 'checkbox'
										]).' '.form_label('<b>'. $item->Nama_Group.'</b>', $item->Group_ID); ?>
								</li>
							<?php endforeach;?>
						</ul>
					</div>
				</div>
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
		var _form = $("#form_user");
				
		$( document ).ready(function(e) {
				
				_form.on("submit", function(e){
					e.preventDefault();									
					$.post( _form.prop("action"), _form.serializeArray(), function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						$.alert_success( response.message );
						setTimeout(function(){													
							document.location.href = "<?php echo base_url($nameroutes); ?>";
						}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
