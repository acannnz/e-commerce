<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( current_url(), array("id" => "form_settings_theme", "name" => "form_settings_theme")); ?>
<div class="row">
	<div class="col-lg-12 col-md-12">
    	<?php echo validation_errors(); ?>
    </div>
    <div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'theme:theme_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<div class="form-group">
                    <label class="control-label"><?php echo lang('theme:system_theme') ?> <span class="text-danger">*</span></label>
                    <select name="system_theme" class="form-control">
						<?php foreach ($theme_collection as $name => $label) : ?>
                        <option value="<?php echo $name ?>" label="<?php echo $label ?>"<?php echo ($this->config->item('system_theme') == $name ? " selected=\"selected\"" : "")?>><?php echo $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('theme:site_name') ?>  <span class="text-danger">*</span></label>
                    <input type="text" name="website_name" class="form-control" value="<?php echo config_item('website_name')?>">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('theme:company_logo') ?></label>
                    <input type="file" class="filestyle" data-buttonText="<?php echo lang('theme:choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="logofile">
                    <?php if (config_item('company_logo') != '') : ?>
                    <div class="settings-image"><img src="<?php echo base_url()?>resource/images/<?php echo config_item('company_logo')?>" /></div>
                    <?php endif; ?>                        
                </div>        
                <div class="form-group">
                    <label class="control-label"><?php echo lang('theme:logo_or_icon') ?></label>
                    <select name="logo_or_icon" class="form-control">
                        <?php $logoicon = config_item('logo_or_icon'); ?>
                        <option value="icon_title"<?php echo ($logoicon == "icon_title" ? ' selected="selected"' : '')?>><?php echo lang('theme:icon')?> & <?php echo lang('theme:site_name')?></option>
                        <option value="icon"<?php echo ($logoicon == "icon" ? ' selected="selected"' : '')?>><?php echo lang('theme:icon')?></option>
                        <option value="logo_title"<?php echo ($logoicon == "logo_title" ? ' selected="selected"' : '')?>><?php echo lang('theme:logo')?> & <?php echo lang('theme:site_name')?></option>
                        <option value="logo"<?php echo ($logoicon == "logo" ? ' selected="selected"' : '')?>><?php echo lang('theme:logo')?></option>
                    </select>
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
    </div>
    <div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'theme:icon_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<div class="form-group">
                    <label class="control-label"><?php echo lang('theme:site_icon') ?></label><br />
                    <input id="site-icon" type="text" name="site_icon" class="form-control" value="<?php echo config_item('site_icon') ?>"><br />
                    <div id="icon-preview"><i class="fa <?php echo config_item('site_icon')?>"></i></div>
                </div>                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('theme:invoice_logo') ?></label><br />
                    <input type="file" class="filestyle" data-buttonText="<?php echo lang('theme:choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="invoicelogo"><br />
                    <?php if (config_item('invoice_logo') != '') : ?>
                    <div class="settings-image"><img src="<?php echo base_url()?>resource/images/logos/<?php echo config_item('invoice_logo')?>" /></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('theme:favicon') ?></label><br />
                    <input type="file" class="filestyle" data-buttonText="<?php echo lang('theme:choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="iconfile"><br />
                    <?php if (config_item('site_favicon') != '') : ?>
                    <div class="settings-image"><img src="<?php echo base_url()?>resource/images/<?php echo config_item('site_favicon')?>" /></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('theme:apple_icon') ?></label><br />
                    <input type="file" class="filestyle" data-buttonText="<?php echo lang('theme:choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="appleicon"><br />
                    <?php if (config_item('site_appleicon') != '') : ?>
                    <div class="settings-image"><img src="<?php echo base_url()?>resource/images/<?php echo config_item('site_appleicon')?>" /></div>
                    <?php endif; ?>
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->    	
    </div>
    <div class="col-lg-12 col-md-12">
    	<button class="btn btn-warning"><i class="fa fa-floppy-o"></i> <?php echo lang('button:save_changes')?></button>
    	<?php /*?><a href="<?php echo base_url()?>settings/?settings=customize" class="btn btn-primary btn-block"><i class="fa fa-code"></i> <span class="text"><?php echo lang('custom_css')?></span></a><?php */?>
    </div>
</div>
<input type="hidden" name="settings" value="theme">
<?php echo form_close() ?>



