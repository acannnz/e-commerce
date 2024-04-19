<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( base_url( "settings/fields/department" ), array("id" => "form_settings_fields_department", "name" => "form_settings_fields_department")); ?>
<div class="row">
	<div class="col-lg-6 col-md-12">
        <div class="page-subtitle">
            <h3><?php echo lang( 'fields:department_subtitle' ) ?></h3>
            <p><?php echo lang( 'fields:department_subtitle_helper' ) ?></p>
        </div> 
        
        <div class="form-group">
            <label class="control-label"><?php echo lang('fields:department') ?></label><br>
            <div class="input-group">
                <select name="department_id" class="form-control" required>
                    <?php foreach ($collection as $item): ?>
                    <option value="<?php echo @$item->id ?>"><?php echo @$item->department ?></option>
                    <?php endforeach ?>
                </select>
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-info"><?php echo lang('button:select_department')?></button>
                </div> 
            </div>
    	</div>
    </div>
</div>
<?php echo form_close() ?>