<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( current_url(), array("id" => "form_settings_department", "name" => "form_settings_department")); ?>
<div class="row">
	<div class="col-lg-12 col-md-12">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('departments:department') ?> <span class="text-danger">*</span></label>
            <input type="text" name="department" value="<?php echo @$item->department ?>" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo lang('departments:description') ?> <span class="text-danger">*</span></label>
            <textarea name="description" wrap="virtual" class="form-control"><?php echo @$item->description ?></textarea>
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo lang('departments:state') ?></label><br>
            <label class="switch">
                <input type="hidden" name="state" value="0" />
                <input type="checkbox" <?php if(@$item->state == 1){ echo "checked=\"checked\""; } ?> name="state" value="1">
                <span></span></label>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12">
    	<button class="btn btn-info"><i class="fa fa-floppy-o"></i> <?php echo lang('button:save')?></button>
        <?php if( @$item->id ): ?>
        <a href="<?php echo base_url( "settings/departments/delete/{$item->id}" ) ?>" data-toggle="ajax-modal" class="btn btn-danger"><?php echo lang('button:delete')?></a>
    	<?php endif ?>
        <a href="javascript:;" data-dismiss="modal" class="btn btn-default"><?php echo lang('button:cancel')?></a>
    </div>
</div>
<input type="hidden" name="settings" value="departments">
<?php echo form_close() ?>