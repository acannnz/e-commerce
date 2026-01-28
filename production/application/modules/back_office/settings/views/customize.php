<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart('settings/update'); ?>
<div class="dev-viewport-panel">
	<button class="btn btn-default"><i class="fa fa-floppy-o"></i> <?php echo lang('save_changes')?></button>
    <a href="<?php echo base_url()?>settings/?settings=customize" class="btn btn-primary pull-right"><i class="fa fa-code text"></i>
        <span class="text"><?php echo lang('custom_css')?></span>
    </a>
</div>
<div class="dev-viewport-form">
	<h4><i class="fa fa-cogs"></i> Custom CSS</h4>
    <div class="row">
        <div class="col-lg-12">
        	<style type="text/css" media="screen">
				#editor {
					position:relative;
					height:500px;
					width:auto;
					margin:0;
					border:1px solid #e0e0e0;
				}
			</style>
			<?php
			$this->load->helper('file');
			$css = read_file('./resource/css/style.css');
			?>
			<?php
			if (!is_really_writable('./resource/css/style.css'))
			{
				echo "CSS file ./resource/css/style.css not writable";
			}
			?>
			<div id="editor"><?php echo $css;?></div>
			<script src="<?php echo base_url()?>resource/js/jquery-2.1.1.min.js"></script>
			<script src="//cdn.jsdelivr.net/ace/1.1.8/min/ace.js" type="text/javascript" charset="utf-8"></script>
			<script src="//cdn.jsdelivr.net/ace/1.1.8/min/ext-beautify.js" type="text/javascript" charset="utf-8"></script>
			<script type="text/javascript">
				$(document).ready(function(){
					var editor = ace.edit("editor");
					editor.setTheme("ace/theme/monokai");
					editor.getSession().setMode("ace/mode/css");

					$("#saveeditor").click(function(){
						$('#css-area').val(editor.getSession().getValue());
						$('#css_form').submit();
					});
				});
			</script>
			<?php
			$attributes = array('class' => 'form-horizontal', 'id' => 'css_form');
			echo form_open_multipart('settings/customize', $attributes);
			?>
			<textarea style="display:none;" id="css-area" name="css-area"></textarea>
			<?php echo form_close(); ?>
        	
    	</div>
    </div>
</div>
<?php echo form_close() ?>