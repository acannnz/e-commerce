<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-file"></i> <?php echo lang('change_email')?></h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(base_url( "auth/change_email" ) ); ?>
            <div class="form-group">
              <label class="control-label"><?php echo lang('new_email')?></label>
              <input type="email" class="form-control" name="email" placeholder="<?php echo lang('new_email')?>" required>
            </div>
            <div class="form-group">
              <label class="control-label"><?php echo lang('password')?></label>
              <input type="password" class="form-control" name="password" placeholder="<?php echo lang('password')?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-check"></i> <?php echo lang('change_email')?></button>
            </div>
            <input type="hidden" name="r_url" value="<?php echo uri_string()?>">
        <?php echo form_close() ?>
    </div>
</div>
    
