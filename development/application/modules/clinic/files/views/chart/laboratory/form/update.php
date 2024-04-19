<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
    	<div class="page-subtitle">
            <i class="fa fa-square-o pull-left text-primary"></i>
            <h3><?php echo lang( "chart_file:lab_subtitle" ) ?></h3>
            <p><?php echo lang( "chart_file:lab_helper" ) ?></p>
        </div>
        <div class="row">
        	<div class="col-md-12">
				<?php echo form_open_multipart(current_url(), array('id' => 'form_files_chart_laboratory', 'name' => 'form_files_chart_laboratory')); ?>
                <div class="form-group">
                    <div class="col-md-12">
                    	<?php if( @$item->file_name ): ?>
                        <a id="file_chart_laboratory_thumbnail" href="<?php echo base_url( "resource/patients/laboratory" ) ?>/<?php echo @$item->file_name ?><?php echo (sprintf("?rand=%s", @time())) ?>" class="thumbnail fullsizable">
							<img id="file_chart_laboratory_img" src="<?php echo base_url( "resource/patients/laboratory" ) ?>/<?php echo @$item->file_name ?><?php echo (sprintf("?rand=%s", @time())) ?>">
                        </a>
                        <?php else: ?>
                        <a id="file_chart_laboratory_thumbnail" href="javascript:;" class="thumbnail">
                        	<img id="file_chart_laboratory_img" src="<?php echo base_url( "resource/patients/laboratory" ) ?>/default_picture.jpg">
                        </a>
						<?php endif ?>
                    </div>
                    <div class="col-md-6">
                    	<a href="<?php echo (isset($file_upload_action) ? $file_upload_action : "javascript:;") ?>" data-toggle="ajax-modal" title="<?php echo lang("buttons:update") ?>" class="btn btn-block btn-primary btn-file-upload"><span><?php echo lang("buttons:update") ?></span></a>
                    </div>
                    <div class="col-md-6">
                    	<a href="<?php echo (isset($file_delete_action) ? $file_delete_action : "javascript:;") ?>" data-toggle="ajax-modal" title="<?php echo lang("buttons:remove") ?>" class="btn btn-block btn-danger btn-file-delete"><span><?php echo lang("buttons:remove") ?></span></a>
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo lang("chart_file:description_label") ?> <span><?php echo lang("chart_file:description_helper") ?></span></label>
                    <textarea name="f[file_description]" wrap="virtual" placeholder="" class="form-control"><?php echo @$item->file_description; ?></textarea>  
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$.fn.extend({
				form_files_chart_laboratory: function( remote_server ){
						if( ! this.size() ){ return this; }
						
						var _form = this;
						
						_form.on( "submit", function(e){
								e.preventDefault();
								
								try{
									var form_data = _form.serializeArray();
									$.post( remote_server, form_data, function( response, status, xhr ){
											console.log( status );
										})
								} catch(e){ console.log( "Chart files ajax submit failed: " + e.message ); }
								
								return false;
							} );
						
						_form.on( "keyup keypress", function(e){
								var kc = e.keyCode || e.which;
								if( kc == 13 ){ 
									e.preventDefault();
									return false;
								}
							} );
							
						_form.find( "textarea[name=\"f[file_description]\"]" ).on("change keyup paste", function(e){
								e.preventDefault();
								_form.trigger( "submit" );
							});
							
						_form.find( "a.fullsizable" ).fullsizable();
						
						return this;
					}
			});
		
		$( document ).ready(function(e) {
            	$( "form[name=\"form_files_chart_laboratory\"]" )
					.form_files_chart_laboratory( "<?php echo @$update_action ?>" );
        	});
	})( jQuery );
//]]>
</script>

