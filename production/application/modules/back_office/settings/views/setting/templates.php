<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
	<div class="col-lg-3 col-md-12">
        <p><strong><?php echo lang( 'templates:select_template_helper' ) ?></strong></p>
        <div class="form-group">
            <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                	<?php echo lang( 'templates:select_template_button' ) ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
					<?php foreach ($templates[$template_group] as $email) :?>
                    <li><a href="<?php echo base_url( "settings/templates/index/{$template_group}/$email" )?>"><?php echo lang( "templates:email_template_{$email}" ) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo lang( 'templates:template_panel_heading' ) ?></h3>
                <ul class="panel-btn">
                    <li><a href="javascript:;" class="btn btn-default" onClick="dev_panel_fullscreen($(this).parents('.panel')); return false;"><i class="fa fa-compress"></i></a></li>
                </ul>
            </div>                                                                              
            <div class="panel-body">
            	<?php echo form_open_multipart( current_url(), array("id" => "form_settings_templates", "name" => "form_settings_templates")); ?>
                <div class="form-group">
                    <label><?php echo lang('subject') ?> <span class="text-danger">*</span></label><br />
                    <input class="form-control" name="subject" value="<?php echo $this->applib->get_any_field('email_templates',array('email_group' => $template_email), 'subject')?>" />
                </div>        
                <div class="form-group">
                    <label><?php echo lang('message') ?> <span class="text-danger">*</span></label><br />
                    <textarea class="form-control" name="email_template"><?php echo $this->applib->get_any_field('email_templates', array('email_group' => $template_email), 'template_body')?></textarea>
                </div>
                <div class="form-group">
                	<button class="btn btn-warning"><i class="fa fa-floppy-o"></i> <?php echo lang('button:save_changes')?></button>
                </div>
                <input type="hidden" name="email" value="<?php echo $template_email; ?>">
                <input type="hidden" name="settings" value="templates">
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">// <![CDATA[
(function( $ ){
		$(document).ready(function(e) {
            	try{ $( "textarea[name=\"email_template\"]" ).summernote({height: 320}) } catch(e){}
        	});
	})( jQuery )
// ]]></script>
