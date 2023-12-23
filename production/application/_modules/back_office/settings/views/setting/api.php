<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( current_url(), array("id" => "form_settings_api", "name" => "form_settings_api")); ?>
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="page-subtitle">
            <h3><?php echo lang( 'api:api_subtitle' ) ?></h3>
            <p><?php echo lang( 'api:api_subtitle_helper' ) ?></p>
        </div>
		<?php echo validation_errors(); ?>
        <div class="form-group">
            <label class="control-label"><?php echo lang('api:x_api_host')?> <span class="text-danger">*</span></label>
            <input type="text" name="x_api_host" value="<?php echo $this->config->item('x_api_host') ?>" placeholder="<?php echo lang( 'eg' ) ?>: https://sample.com/api/" class="form-control" >
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo lang('api:x_api_key')?> <span class="text-danger">*</span></label>
            <input type="text" name="x_api_key" value="<?php echo $this->config->item('x_api_key') ?>" class="form-control" >
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo lang('api:x_api_enable')?></label><br>      
            <label class="switch">
                <input type="hidden" name="x_api_enable" value="off" />
                <input type="checkbox" name="x_api_enable" value="on" <?php if($this->config->item('x_api_enable') == 'TRUE'){ echo "checked=\"checked\""; } ?>>
                <span></span></label>
        </div>        
    </div>    
</div>
<div class="row">
	<div class="col-lg-4 col-md-12">
    	<button class="btn btn-warning"><i class="fa fa-floppy-o"></i> <?php echo lang('button:save_changes')?></button>
    </div>
</div>
<input type="hidden" name="settings" value="api">
<?php echo form_close() ?>
