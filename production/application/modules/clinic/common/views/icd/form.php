<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('icd:code_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <input type="text" id="code" name="f[code]" value="<?php echo @$item->code ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('icd:version_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <div class="radio radio-inline">
            <input type="radio" id="version_1" name="f[version]" value="ICD9"<?php if("ICD9" == @$item->version){echo " checked";} ?>><label for="version_1">ICD9</label>
        </div>
        <div class="radio radio-inline">
            <input type="radio" id="version_2" name="f[version]" value="ICD10"<?php if("ICD10" == @$item->version){echo " checked";} ?>><label for="version_2">ICD10</label>
        </div>
        <div class="radio radio-inline">
            <input type="radio" id="version_3" name="f[version]" value="ICD9 to ICD10"<?php if("ICD9 to ICD10" == @$item->version){echo " checked";} ?>><label for="version_3">ICD9 to ICD10</label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('icd:long_desc_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <textarea id="long_desc" name="f[long_desc]" placeholder="" wrap="virtual" class="form-control" required><?php echo @$item->long_desc ?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('icd:short_desc_label') ?></label>
    <div class="col-lg-6">
        <input type="text" id="short_desc" name="f[short_desc]" value="<?php echo @$item->short_desc ?>" placeholder="" class="form-control">
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