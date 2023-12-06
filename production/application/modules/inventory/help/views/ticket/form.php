<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
    	<?php echo form_open_multipart( current_url(), array("id" => "form_help_ticket", "name" => "form_help_ticket") ); ?>
        <div class="well well-sm">
            <p><?php echo lang( "help:ticket_helper" ) ?></p>
            <div class="row">
                <div class="form-group col-md-6">
                    <?php echo form_label(lang('help:mail_to_label')." *", 'input_mail_to', ['class' => 'control-label']) ?>
                    <?php echo form_input('f[mail_to]', set_value('f[mail_to]', '', TRUE), [
                            'id' => 'input_mail_to', 
                            'placeholder' => lang("help:mail_to_placeholder"), 
                            'class' => 'form-control'
                        ]); ?>
                </div>
                <div class="form-group col-md-6">
                    <?php echo form_label(lang('help:mail_cc_label'), 'input_mail_cc', ['class' => 'control-label']) ?>
                    <?php echo form_input('f[mail_cc]', set_value('f[mail_cc]', '', TRUE), [
                            'id' => 'input_mail_cc', 
                            'placeholder' => lang("help:mail_cc_placeholder"), 
                            'class' => 'form-control'
                        ]); ?>
                </div>
                <div class="form-group col-md-6">
                    <?php echo form_label(lang('help:mail_subject_label')." *", 'input_mail_subject', ['class' => 'control-label']) ?>
                    <?php echo form_input('f[mail_subject]', set_value('f[mail_subject]', '', TRUE), [
                            'id' => 'input_mail_subject', 
                            'placeholder' => '', 
                            'class' => 'form-control'
                        ]); ?>
                </div>
                <div class="form-group col-md-6">
                    <?php echo form_label(lang('help:mail_attachment_label'), 'input_userfile', ['class' => 'control-label']) ?>                                         
                    <input type="file" name="userfile" class="file" title="<?php echo lang("help:mail_choose_file_label") ?>">                                        
                </div>
                <div class="form-group col-md-12">
                    <?php echo form_label(lang('help:mail_message_label'), 'input_mail_message', ['class' => 'control-label']) ?>
                    <?php echo form_textarea([
                            'id' => 'input_mail_message', 
                            'name' => 'f[mail_message]',
                            'value' => set_value('f[mail_message]', '', TRUE),
                            'placeholder' => '', 
                            'wrap' => 'virtual',
                            'rows' => 5,
                            'class' => 'form-control'
                        ]); ?>
                </div>
                <div class="form-group col-md-12">
                    <label class="checkbox-inline">
                        <?php echo form_checkbox('send_copy', 1, (TRUE == set_value('send_copy', 0, TRUE))); ?>
                        <?php echo lang('help:send_copy_label'); ?>
                    </label>
                </div>
            	<div class="form-group col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo form_button([
                                    'name' => 'btn_reset',
                                    'id' => 'btn_reset',
                                    'value' => 'reset',
                                    'type' => 'reset',
                                    'content' => '<i class="fa fa-undo" aria-hidden="true"></i> ' . lang('buttons:reset'),
                                    'class' => 'btn btn-block btn-warning'
                                ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo form_button([
                                    'name' => 'btn_submit',
                                    'id' => 'btn_submit',
                                    'value' => 'submit',
                                    'type' => 'submit',
                                    'content' => '<i class="fa fa-paper-plane" aria-hidden="true"></i> ' . lang('buttons:send'),
                                    'class' => 'btn btn-block btn-info'
                                ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php echo form_hidden('f[mail_from]', @$mail->mail_from); ?>
        <?php echo form_close() ?> 
    </div>
</div>