<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-file"></i> <?php echo lang('profile_details')?></h3>
	</div>
	<div class="panel-body">
        <?php echo form_open(uri_string()); ?>
            <?php echo validation_errors(); ?>    
            <div class="form-group">
              <label class="control-label"><?php echo lang('full_name')?> <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="fullname" value="<?php echo @$profile->fullname?>" required>
            </div>    
            <div class="form-group">
                <label class="control-label"><?php echo lang('phone')?></label>
                <input type="text" class="form-control" name="phone" value="<?php echo @$profile->phone?>">
            </div>    
            <div class="form-group">
                <label class="control-label"><?php echo lang('language')?></label>
                <select name="language" class="form-control">
                    <?php foreach ($languages as $lang) : ?>
                    <option value="<?php echo $lang->name?>"<?php echo (@$profile->language == $lang->name ? ' selected="selected"' : '')?>><?php echo   ucfirst($lang->name)?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo lang('locale')?></label>
                <select class="select2-option form-control" name="locale">
                    <?php foreach ($locales as $loc) : ?>
                    <option value="<?php echo $loc->locale?>"<?php echo (@$profile->locale == $loc->locale ? ' selected="selected"' : '')?>><?php echo $loc->name?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">    
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-check"></i> <?php echo lang('update_profile')?></button>
            </div>
        <?php echo form_close() ?>
    </div>
</div>


