<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( current_url(), array("id" => "form_settings_general", "name" => "form_settings_general")); ?>
<div class="row">
	<div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'general:company_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'general:company_subtitle_helper' ) ?></p>
                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:company_name') ?> <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" value="<?php echo $this->config->item('company_name') ?>" required>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:company_legal_name') ?> <span class="text-danger">*</span></label>
                    <input type="text" name="company_legal_name" class="form-control" value="<?php echo $this->config->item('company_legal_name') ?>" required>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:company_vat') ?></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('company_vat') ?>" name="company_vat">
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
    </div>
    
    <div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'general:location_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'general:location_subtitle_helper' ) ?></p>
                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:company_address') ?> <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="company_address" required><?php echo $this->config->item('company_address') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:city') ?></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('company_city') ?>" name="company_city">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:zip_code') ?> </label>
                    <input type="text" class="form-control"  value="<?php echo $this->config->item('company_zip_code') ?>" name="company_zip_code">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:country') ?></label>
                    <select class="form-control" style="width:210px" name="company_country" >
                        <optgroup label="<?php echo lang('general:selected_country') ?>">
                            <option value="<?php echo $this->config->item('company_country') ?>"><?php echo $this->config->item('company_country') ?></option>
                        </optgroup>
                        <optgroup label="<?php echo lang('general:other_countries') ?>">
                            <?php foreach ($countries as $country): ?>
                                <option value="<?php echo $country->value?>"><?php echo $country->value?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
    </div>
    
    <div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang( 'general:contact_subtitle' ) ?></h3>
            </div>
            <div class="panel-body">
            	<p><?php echo lang( 'general:contact_subtitle_helper' ) ?></p>
                
            	<div class="form-group">
                    <label class="control-label"><?php echo lang('general:contact_person') ?> </label>
                    <input type="text" class="form-control"  value="<?php echo $this->config->item('contact_person') ?>" name="contact_person">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:company_phone') ?></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('company_phone') ?>" name="company_phone">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:company_email') ?></label>
                    <input type="email" class="form-control" value="<?php echo $this->config->item('company_email') ?>" name="company_email">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:company_domain') ?></label>
                    <input type="text" class="form-control" value="<?php echo $this->config->item('company_domain') ?>" name="company_domain">
                </div>
                
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:facebook_page') ?></label>
                    <input type="text" name="facebook_page" value="<?php echo $this->config->item('facebook_page') ?>" placeholder="<?php echo lang( 'eg' ) ?>: https://facebook.com/{alias}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:twitter_page') ?></label>
                    <input type="text" name="twitter_page" value="<?php echo $this->config->item('twitter_page') ?>" placeholder="<?php echo lang( 'eg' ) ?>: https://twitter.com/{alias}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo lang('general:googleplus_page') ?></label>
                    <input type="text" name="googleplus_page" value="<?php echo $this->config->item('googleplus_page') ?>" placeholder="<?php echo lang( 'eg' ) ?>: https://plus.google.com/{ID}" class="form-control">
                </div>
            </div><!-- // end panel body -->
        </div><!-- // end panel -->
    </div>
    <div class="col-lg-12 col-md-12">
    	<button class="btn btn-warning"><i class="fa fa-floppy-o"></i> <?php echo lang('button:save_changes') ?></button>
    </div>
</div>
<?php echo form_close() ?>

