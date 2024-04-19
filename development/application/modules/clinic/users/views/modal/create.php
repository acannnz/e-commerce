<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php echo form_open( base_url('users/accounts/create'), array("id" => "form_create_account", "name" => "form_create_account") ); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo lang('users:create_heading')?></h4>
        </div>
        <div class="modal-body">
            <?php echo Modules::run("system/alert"); ?>
            <div class="row">
            	<div class="col-md-6 col-sm-12">
                	<div class="form-group">
                        <label><?php echo lang('full_name')?> <span class="text-danger">*</span></label>
                        <input type="text" class="input-sm form-control" value="<?php echo @$item->fullname ?>" placeholder="<?php echo lang('eg')?> <?php echo lang('user_placeholder_name')?>" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('email')?> <span class="text-danger">*</span></label>
                        <input type="email" placeholder="<?php echo lang('eg')?> <?php echo lang('user_placeholder_email')?>" name="email" value="<?php echo @$item->email ?>" class="input-sm form-control" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('phone')?> </label>
                        <input type="text" class="input-sm form-control" value="<?php echo @$item->phone ?>" name="phone" placeholder="<?php echo lang('eg')?> <?php echo lang('user_placeholder_phone')?>">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label><?php echo lang('username')?> <span class="text-danger">*</span></label>
                        <input type="text" name="username" placeholder="<?php echo lang('eg')?> <?php echo lang('user_placeholder_username')?>" value="<?php echo @$item->username ?>" class="input-sm form-control" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('password')?></label>
                        <input type="password" name="password" placeholder="<?php echo lang('password')?>" value="<?php echo @$item->password ?>" class="input-sm form-control" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('confirm_password')?></label>
                        <input type="password" name="confirm_password" placeholder="<?php echo lang('confirm_password')?>" value="<?php echo @$item->confirm_password ?>" class="input-sm form-control" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('role')?></label>
                        <?php echo form_dropdown(
								'role', 
								(array(0 => lang('global:select-pick')) + ((array) $options_role)), 
								@$item->role, 
								'id="" class="form-control"'
							); ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="r_url" value="<?php echo base_url("users/accounts/create") ?>">
        </div>
        <div class="modal-footer">
        	<div class="row">
            	<div class="col-md-6 col-sm-12">
                	<button type="submit" class="btn btn-block btn-primary"><i class="fa fa-floppy-o"></i> <?php echo lang('buttons:submit') ?></button>
                </div>
             	<div class="col-md-6 col-sm-12">
                	<button type="button" class="btn btn-block btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo lang('buttons:cancel') ?></button>
                </div>
            </div>
        </div>
        <?php echo form_close() ?>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(e){
				$( "form[name=\"form_create_account\"]" ).on("submit", function(e){
						var _form = $( this );
						
						e.preventDefault();
						form_ajax_modal.post(function(){}, _form);
					});
					
				<?php if( isset($done) ): ?>
				$( "button[data-dismiss=\"modal\"]" ).trigger( "click" );
				window.location = "<?php echo base_url('users/accounts') ?>";
				<?php endif ?>
			})
	})( jQuery );
//]]>
</script>