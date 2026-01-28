<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( current_url(), array("id" => "form_settings_email", "name" => "form_settings_email")); ?>
<div class="row">
    <div class="col-lg-12 col-md-12">
    	<?php echo validation_errors(); ?>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'email:email_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<div class="form-group">
                    <label class="control-label"><?php echo lang('email:company_email')?> <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" value="<?php echo $this->config->item('company_email')?>" name="company_email" data-type="email" data-required="true">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('email:use_postmark')?></label>
                    <br />          
                    <label class="switch">
                        <input type="hidden" value="off" name="use_postmark" />
                        <input type="checkbox" <?php if(config_item('use_postmark') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="use_postmark" value="on">
                        <span></span></label>
                    <br /><em><?php echo lang('email:portmark_help_text')?></em>
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
    </div>
    <div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'email:smtp_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<div class="form-group">
                    <label class="control-label"><?php echo lang('email:email_protocol')?> <span class="text-danger">*</span></label>
                    <select name="protocol" class="form-control">
                        <?php $prot = config_item('protocol'); ?>
                        <option value="mail"<?php echo ($prot == "mail" ? ' selected="selected"' : '')?>><?php echo lang('email:php_mail')?></option>
                        <option value="smtp"<?php echo ($prot == "smtp" ? ' selected="selected"' : '')?>><?php echo lang('email:smtp')?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('email:smtp_host')?></label>
                    <input type="text" name="smtp_host" value="<?php echo $this->config->item('smtp_host')?>" placeholder="<?php echo lang( 'eg' ) ?>: smtp.domain.com" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('email:smtp_user')?></label>
                    <input type="text" name="smtp_user" value="<?php echo $this->config->item('smtp_user')?>" placeholder="<?php echo lang( 'eg' ) ?>: user@domain.com" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('email:smtp_pass')?></label>
                    <input type="password" class="form-control" value="<?php echo $this->encrypt->decode($this->config->item('smtp_pass'));?>" name="smtp_pass">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('email:smtp_port')?></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('smtp_port')?>" name="smtp_port">
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
<input type="hidden" name="settings" value="email">
<?php echo form_close() ?>
