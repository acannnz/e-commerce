<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
  
<?php echo form_open(base_url('logout')); ?>
<div class="modal-body">
	<p><?php echo lang('auth_logout_confirm')?></p>            
	<input type="hidden" name="confirm" value="1">
	<input type="hidden" name="r_url" value="<?php echo base_url( 'login' ) ?>">    
</div>
<div class="modal-footer"> 
	<div class="col-md-6">
		<button type="submit" class="btn btn-block btn-danger"><?php echo lang('buttons:yes')?></button>
	</div>
	<div class="col-md-6">
		<a href="#" class="btn btn-block btn-default" data-dismiss="modal"><?php echo lang('buttons:no')?></a>
	</div>  
</div>
<?php echo form_close() ?>
