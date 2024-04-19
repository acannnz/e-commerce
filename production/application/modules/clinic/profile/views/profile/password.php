<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-file"></i> <?php echo lang('account_details')?></h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(base_url().'auth/change_password'); ?>
            <div class="form-group">
                <label><?php echo lang('old_password')?> <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="old_password" placeholder="<?php echo lang('old_password')?>" required>
            </div>
            <div class="form-group">
                <label><?php echo lang('new_password')?> <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="new_password" placeholder="<?php echo lang('new_password')?>" required>
            </div>
            <div class="form-group">
                <label><?php echo lang('confirm_password')?> <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="confirm_new_password" placeholder="<?php echo lang('confirm_password')?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-check"></i> <?php echo lang('change_password')?></button>
            </div>
            <input type="hidden" name="r_url" value="<?php echo uri_string()?>">
        <?php echo form_close() ?>
    </div>
</div>