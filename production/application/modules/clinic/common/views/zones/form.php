<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:code_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <input type="text" id="code" name="f[code]" value="<?php echo @$item->code ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:title_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <textarea id="service_title" name="f[service_title]" placeholder="" wrap="virtual" class="form-control" required><?php echo @$item->service_title ?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:description_label') ?></label>
    <div class="col-lg-6">
        <textarea id="service_description" name="f[service_description]" placeholder="" wrap="virtual" class="form-control"><?php echo @$item->service_description ?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:price_label') ?></label>
    <div class="col-lg-6">
        <input type="text" id="service_price" name="f[service_price]" value="<?php echo (float) @$item->service_price ?>" placeholder="" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('icd:state_label')?></label>
    <div class="col-lg-6">
        <label class="switch">
            <input type="hidden" value="0" name="f[state]" />
            <input type="checkbox" <?php if(@$item->state == 1){ echo "checked=\"checked\""; } ?> name="f[state]" value="1">
            <span></span>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
    	<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
    </div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
//]]>
</script>