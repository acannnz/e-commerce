<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
			<h4 class="modal-title"><?php echo lang('edit_user')?></h4>
		</div>
		<?php
        if (!empty($user_details)) {
        foreach ($user_details as $key => $user) { ?>
		<?php echo form_open(base_url( "users/accounts/edit/{$user->user_id}" ) ); ?>
		<div class="modal-body">
			 <input type="hidden" name="user_id" value="<?php echo $user->user_id?>">
			 <input type="hidden" name="company" value="<?php echo $user->company?>">
			 
			 <div class="form-group">
				<label class="col-lg-3 control-label"><?php echo lang('full_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?php echo $user->fullname?>" name="fullname">
				</div>
				</div>
				
			      <?php
			      $role = Applib::login_info($user->user_id)->role_id;
			      if ($role == '3') { ?>
			      <div class="form-group">
			        <label class="col-lg-3 control-label"><?php echo lang('department')?> </label>
			        <div class="col-lg-8">
			        	<select  name="department" class="form-control" >
                        	<?php $dept = Applib::get_table_field(Applib::$departments_table,array('deptid'=>$user->department),'deptname'); ?>
                        	<option value="" label="<?php echo lang('global:select-empty') ?>"<?php if( $dept == 0 ): ?> selected<?php endif ?>><?php echo lang('global:select-empty') ?></option>
                            <?php $departments = $this->db->get(Applib::$departments_table)->result(); ?>
                            <?php if( !empty($departments) ): ?>
                            <?php foreach( $departments as $d ): ?>
                            <option value="<?php echo $d->deptid?>"<?php echo ($dept == $d->deptid ? ' selected="selected"' : '')?>><?php echo $d->deptname ?></option>
                            <?php endforeach ?>
                            <?php endif ?>
			          	</select> 
			          </div>
			      </div>
			      <?php } ?>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?php echo lang('phone')?> </label>
				<div class="col-lg-4">
					<input type="text" class="form-control" value="<?php echo $user->phone?>" name="phone">
				</div>
				</div>
                         
            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo lang('language')?></label>
                <div class="col-lg-5">
                    <select name="language" class="form-control">
                    <?php foreach ($languages as $lang) : ?>
                    <option value="<?php echo $lang->name?>"<?php echo ($user->language == $lang->name ? ' selected="selected"' : '')?>><?php echo   ucfirst($lang->name)?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                    <label class="col-lg-3 control-label"><?php echo lang('locale')?></label>
                    <div class="col-lg-5">
                            <select class="select2-option form-control" name="locale">
                            <?php foreach ($locales as $loc) : ?>
                            <option lang="<?php echo $loc->code?>" value="<?php echo $loc->locale?>"<?php echo ($user->locale == $loc->locale ? ' selected="selected"' : '')?>><?php echo $loc->name?></option>
                            <?php endforeach; ?>
                            </select>
                    </div>
            </div>
		</div>
        <div class="modal-footer">
            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> <?php echo lang('buttons:reset') ?></button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> <?php echo lang('save_changes') ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo lang('buttons:cancel') ?></button>
        </div>
        <?php echo form_close() ?>
		<?php } } ?>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
    $(".select2-option").select2();
</script>