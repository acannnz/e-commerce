<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart( base_url( "settings/fields/index/{$department_id}" ), array("id" => "form_settings_fields", "name" => "form_settings_fields")); ?>
<div class="row">
	<div class="col-lg-4 col-md-12">
    	<div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><?php echo lang( "fields:department_heading" ) ?></h3></div>
            <div class="panel-body">
           		<p><strong><?php echo lang( 'fields:department' ) ?> :</strong> <?php echo $department->department ?><br>
                <strong>ID :</strong> <?php echo sprintf( "%04d", $department->id ) ?></p>
                <p class="margin-bottom-0"><a href="<?php echo base_url( "settings/fields/create/{$department->id}" ) ?>" data-toggle="ajax-modal" class="btn btn-info margin-bottom-0"><?php echo lang('button:create')?></a></p>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><?php echo lang( "fields:list_heading" ) ?></h3></div>
            <div class="panel-body">
				<?php if( ! empty($fields) ): ?>
				<?php foreach( $fields as $i => $field ): ?>
                <a href="<?php echo base_url( "settings/fields/update/{$department_id}/{$field->id}" ) ?>" data-toggle="ajax-modal" class="label <?php echo ((1 == $field->state) ? 'label-success' : 'label-danger') ?>"><?php echo $field->name ?></a>
                <?php endforeach ?>
                <?php else: ?>
                <div class="alert alert-info margin-bottom-0" role="alert"><?php echo lang( "message:no_fields" ) ?></div>
                <?php endif ?>   	
            </div>
        </div>
    </div>
</div>
<?php echo form_close() ?>
<?php return false ?>

<?php echo form_open_multipart('settings/add_custom_field'); ?>
<div class="dev-viewport-panel">
	<button class="btn btn-default"><i class="fa fa-floppy-o"></i> <?php echo lang('save_changes')?></button>
</div>
<div class="dev-viewport-form">
    <h4><i class="fa fa-cogs"></i> <?php echo lang('custom_fields')?></h4>
    <div class="row">
        <div class="col-lg-12">        	
            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo lang('custom_field_name')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                	<input type="text" class="form-control" placeholder="<?php echo lang('eg')?> <?php echo lang('user_placeholder_username')?>" name="name" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo lang('field_type')?> <span class="text-danger">*</span> </label>
                <div class="col-lg-8">
                    <select name="type" class="form-control">
                    <option value="text"><?php echo lang('text_field')?></option>
                    </select> 
                </div>
            </div>
            <hr>
			<?php
            $fields = $this->db->where(array('deptid' => $deptid))->get('fields')->result();
            if (!empty($fields)) {
            foreach ($fields as $key => $f) { ?>
            <label class="label label-danger"><a class="text-white" href="<?php echo base_url()?>settings/edit_custom_field/<?php echo $f->id?>" data-toggle="ajax-modal" title = ""><?php echo $f->name?></a></label>
            <?php } } ?>
            <input type="hidden" name="deptid" value="<?php echo $deptid; ?>">
    	</div>
    </div>
</div>
<?php echo form_close() ?>


