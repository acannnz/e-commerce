<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="modal-dialog">
	<div class="modal-content">
        <div class="modal-header bg-danger"> 
            <?php /*?><button type="button" class="close" data-dismiss="modal">&times;</button><?php */?> 
            <h4 class="modal-title"><?php echo lang('delete_user')?></h4>
        </div>
		<?php echo form_open( base_url("users/accounts/delete/{$user_id}") ); ?>
        <div class="modal-body">
            <p><?php echo lang('delete_user_warning')?></p>            
            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
            <?php $redirect = 'users/account'; ?>
            <input type="hidden" name="r_url" value="<?php echo base_url()?><?php echo $redirect?>">
        
        </div>
        <div class="modal-footer"> 
            <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo lang('close')?></a>
            <button type="submit" class="btn btn-danger"><?php echo lang('delete_button')?></button>
        </div>
        <?php echo form_close() ?>
	</div>
</div>