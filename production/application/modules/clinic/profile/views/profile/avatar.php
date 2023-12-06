<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="page-subtitle">
	<h3><i class="fa fa-file"></i> <?php echo lang('avatar_image')?></h3>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<?php echo form_open_multipart(base_url('profile/changeavatar') ); ?>
            <div class="form-group">
                <label class="control-label"><?php echo lang('use_gravatar')?></label>
                <div class="col-md-12">
                    <label class="switch">
                        <input type="checkbox"name="use_gravatar">
                        <span></span>
                    </label>
                </div>
            </div>            
            <div class="form-group">
                <label class="control-label"><?php echo lang('avatar_image')?></label>
                <input type="file" class="filestyle" data-buttonText="<?php echo lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="userfile">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-check"></i> <?php echo lang('change_avatar')?></button>
            </div>
            <input type="hidden" name="r_url" value="<?php echo uri_string()?>">
        <?php echo form_close() ?>
    </div>
</div>


