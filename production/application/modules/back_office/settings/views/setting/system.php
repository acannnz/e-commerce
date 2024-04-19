<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( current_url(), array("id" => "form_settings_system", "name" => "form_settings_system")); ?>
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'system:system_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'system:system_subtitle_helper' ) ?></p>
                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:locale') ?> <span class="text-danger">*</span></label>
                    <select class="form-control" name="locale" class="form-control" required>
                        <?php foreach ($locales as $loc) : ?>
                            <option lang="<?php echo $loc->code?>" value="<?php echo $loc->locale?>"<?php echo ($this->config->item('locale') == $loc->locale ? ' selected="selected"' : '')?>><?php echo $loc->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:timezone')?> <span class="text-danger">*</span></label>
                    <select class="form-control" name="timezone" class="form-control" required>
                        <?php foreach ($timezones as $timezone => $description) : ?>
                            <option value="<?php echo $timezone?>"<?php echo ($this->config->item('timezone') == $timezone ? ' selected="selected"' : '')?>><?php echo $description?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <?php $this->applib->set_locale(); $date_format = $this->config->item('date_format'); ?>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:default_date_format')?> <span class="text-danger">*</span></label>
                    <select name="date_format" class="form-control">
                        <option value="%d-%m-%Y"<?php echo ($date_format == "%d-%m-%Y" ? ' selected="selected"' : '')?>><?php echo strftime("%d-%m-%Y", time())?></option>
                        <option value="%m-%d-%Y"<?php echo ($date_format == "%m-%d-%Y" ? ' selected="selected"' : '')?>><?php echo strftime("%m-%d-%Y", time())?></option>
                        <option value="%Y-%m-%d"<?php echo ($date_format == "%Y-%m-%d" ? ' selected="selected"' : '')?>><?php echo strftime("%Y-%m-%d", time())?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:use_gravatar')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="use_gravatar" />
                        <input type="checkbox" <?php if(config_item('use_gravatar') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="use_gravatar">
                        <span></span></label>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:cron_key')?></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('cron_key')?>" name="cron_key">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:demo_mode')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="demo_mode" />
                        <input type="checkbox" <?php if(config_item('demo_mode') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="demo_mode">
                        <span></span>
                    </label>
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
        
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'system:system_language_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'system:system_language_subtitle_helper' ) ?></p>
                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:default_language')?> <span class="text-danger">*</span></label>
                    <select name="language" class="form-control">
                        <?php foreach ($languages as $lang) : ?>
                            <option lang="<?php echo $lang->code?>" value="<?php echo $lang->name?>"<?php echo ($this->config->item('language') == $lang->name ? ' selected="selected"' : '')?>><?php echo   ucfirst($lang->name)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:enable_languages')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="enable_languages" />
                        <input type="checkbox" <?php if(config_item('enable_languages') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="enable_languages">
                        <span></span></label>
                </div>   
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
    </div>
    
    <div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'system:system_currency_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'system:system_currency_subtitle_helper' ) ?></p>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:default_currency')?> <span class="text-danger">*</span></label>
                    <select name="default_currency" class="form-control">
                        <?php $cur = $this->applib->currencies(config_item('default_currency')); ?>
                        <?php foreach ($currencies as $cur) : ?>
                            <option value="<?php echo $cur->code?>"<?php echo ($this->config->item('default_currency') == $cur->code ? ' selected="selected"' : '')?>><?php echo $cur->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:default_currency_symbol')?> <span class="text-danger">*</span></label>
                    <input type="text" name="default_currency_symbol" value="<?php echo config_item('default_currency_symbol')?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:decimal_separator')?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('decimal_separator')?>" name="decimal_separator">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:thousand_separator')?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('thousand_separator')?>" name="thousand_separator">
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
        
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'system:system_file_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'system:system_file_subtitle_helper' ) ?></p>
                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:file_max_size')?> <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('file_max_size')?>" name="file_max_size" data-type="digits" data-required="true">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:allowed_files')?></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('allowed_files')?>" name="allowed_files">
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
    </div>

    <div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'system:system_chart_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'system:system_chart_subtitle_helper' ) ?></p>
                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:enable_chart_template_label')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="enable_chart_template" />
                        <input type="checkbox" name="enable_chart_template" <?php if(config_item('enable_chart_template') == 'TRUE'){ echo "checked=\"checked\""; } ?>>
                        <span></span></label>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:enable_chart_service_label')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="enable_chart_service" />
                        <input type="checkbox" name="enable_chart_service" <?php if(config_item('enable_chart_service') == 'TRUE'){ echo "checked=\"checked\""; } ?>>
                        <span></span></label>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:enable_chart_drug_label')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="enable_chart_drug" />
                        <input type="checkbox" name="enable_chart_drug" <?php if(config_item('enable_chart_drug') == 'TRUE'){ echo "checked=\"checked\""; } ?>>
                        <span></span></label>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:enable_chart_billing_label')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="enable_chart_billing" />
                        <input type="checkbox" name="enable_chart_billing" <?php if(config_item('enable_chart_billing') == 'TRUE'){ echo "checked=\"checked\""; } ?>>
                        <span></span></label>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:enable_chart_laboratory_label')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="enable_chart_laboratory" />
                        <input type="checkbox" name="enable_chart_laboratory" <?php if(config_item('enable_chart_laboratory') == 'TRUE'){ echo "checked=\"checked\""; } ?>>
                        <span></span></label>
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->  
        
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'system:system_specialist_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'system:system_specialist_subtitle_helper' ) ?></p>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:medical_specialist_state')?></label><br />
                    <label class="switch">
                        <input type="hidden" value="off" name="medical_specialist_state" />
                        <input type="checkbox" name="medical_specialist_state" value="on" <?php if($this->config->item('medical_specialist_state') == 'TRUE'){ echo " checked=\"checked\""; } ?>>
                        <span></span></label>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('system:medical_specialist')?></label>
                    <select class="form-control" name="medical_specialist" class="form-control" required>
                        <?php foreach ($medical_specialists as $specialist_name => $specialist_label) : ?>
                        <option value="<?php echo $specialist_name ?>" label="<?php echo $specialist_label ?>"<?php echo ($this->config->item('medical_specialist') == $specialist_name ? " selected=\"selected\"" : "") ?>><?php echo $specialist_label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel --> 
    </div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12">
    	<button class="btn btn-warning"><i class="fa fa-floppy-o"></i> <?php echo lang('button:save_changes')?></button>
    </div>
</div>
<input type="hidden" name="settings" value="system">
<?php echo form_close() ?>



