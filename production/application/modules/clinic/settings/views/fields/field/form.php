<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( current_url(), array("id" => "form_settings_field", "name" => "form_settings_field")); ?>
<div class="row">
	<div class="col-lg-12 col-md-12">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('fields:field_name') ?> <span class="text-danger">*</span></label>
            <input type="text" name="name" value="<?php echo @$item->name ?>" placeholder="<?php echo lang( 'eg' ) ?>: first_name" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo lang('fields:field_label') ?> <span class="text-danger">*</span></label>
            <input type="text" name="label" value="<?php echo @$item->label ?>" placeholder="<?php echo lang( 'eg' ) ?>: First Name" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo lang('fields:field_type') ?> <span class="text-danger">*</span></label>
            <select name="type" class="form-control">
            	<option value="text"><?php echo lang('fields:text_field')?></option>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo lang('fields:state') ?></label><br>
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
        <a href="<?php echo base_url( "settings/fields/delete/{$department->id}/{$item->id}" ) ?>" data-toggle="ajax-modal" class="btn btn-danger"><?php echo lang('button:delete')?></a>
    	<?php endif ?>
        <a href="javascript:;" data-dismiss="modal" class="btn btn-default"><?php echo lang('button:cancel')?></a>
    </div>
</div>
<input type="hidden" name="settings" value="departments">
<?php echo form_close() ?>