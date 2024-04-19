<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart('settings/update'); ?>
<div class="dev-viewport-panel">
	<button class="btn btn-default"><i class="fa fa-floppy-o"></i> <?php echo lang('save_changes')?></button>
</div>
<div class="dev-viewport-form">
    <h4 class="text-primary"><i class="fa fa-envelope-o"></i> <?php echo lang('alert_settings')?></h4>
    <hr>
    <div class="row">
        <div class="col-lg-12">
        	<?php echo validation_errors(); ?>
			<input type="hidden" name="settings" value="<?php echo $load_setting?>">
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('email_account_details')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="email_account_details" />
						<input type="checkbox" <?php if(config_item('email_account_details') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_account_details">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('email_staff_tickets')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="email_staff_tickets" />
						<input type="checkbox" <?php if(config_item('email_staff_tickets') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_staff_tickets">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('notify_bug_assignment')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="notify_bug_assignment" />
						<input type="checkbox" <?php if(config_item('notify_bug_assignment') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_assignment">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('notify_bug_comments')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="notify_bug_comments" />
						<input type="checkbox" <?php if(config_item('notify_bug_comments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_comments">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('notify_bug_status')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="notify_bug_status" />
						<input type="checkbox" <?php if(config_item('notify_bug_status') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_status">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('notify_project_assignments')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="notify_project_assignments" />
						<input type="checkbox" <?php if(config_item('notify_project_assignments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_assignments">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('notify_project_comments')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="notify_project_comments" />
						<input type="checkbox" <?php if(config_item('notify_project_comments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_comments">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('notify_project_files')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="notify_project_files" />
						<input type="checkbox" <?php if(config_item('notify_project_files') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_files">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('notify_task_assignments')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="notify_task_assignments" />
						<input type="checkbox" <?php if(config_item('notify_task_assignments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_task_assignments">
						<span></span>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-5 control-label"><?php echo lang('notify_message_received')?></label>
				<div class="col-lg-7">
					<label class="switch">
						<input type="hidden" value="off" name="notify_message_received" />
						<input type="checkbox" <?php if(config_item('notify_message_received') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_message_received">
						<span></span>
					</label>
				</div>
			</div>
        </div>
	</div>
</div>
<?php echo form_close() ?>

