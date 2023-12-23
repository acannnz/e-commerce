<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-file"></i> <?php echo lang('change_username')?></h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(base_url('auth/change_username')); ?>
            <div class="form-group">
                <label class="control-label"><?php echo lang('new_username')?></label>
                <input type="text" class="form-control" name="username" placeholder="<?php echo lang('new_username')?>" required>
            </div>
            <div class="form-group">
            	<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-check"></i> <?php echo lang('change_username')?></button>
            </div>
            <input type="hidden" name="r_url" value="<?php echo uri_string()?>">
        <?php echo form_close() ?>    
    </div>
</div>

