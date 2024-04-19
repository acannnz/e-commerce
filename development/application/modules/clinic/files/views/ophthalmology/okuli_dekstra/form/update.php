<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open_multipart(current_url(), array('id' => 'form_files_ophthalmology_od', 'name' => 'form_files_ophthalmology_od')); ?>
    <h5><?php echo lang( "ophthalmology:topography_subtitle" ) ?></h5>
    <div class="form-group">
        <label class="col-md-12"><?php echo lang("ophthalmology:hirschberg_label") ?></label>
        <div class="col-md-12">
            <?php if( @$item->file_name ): ?>
            <a id="file_chart_usg_thumbnail" href="javascript:;" class="thumbnail">
                <img id="file_chart_usg_img" src="<?php echo base_url( "resource/patients/ophthalmology" ) ?>/<?php echo @$item->file_name ?><?php echo (sprintf("?rand=%s", @time())) ?>">
            </a>
            <?php else: ?>
            <a id="file_chart_usg_thumbnail" href="javascript:;" class="thumbnail">
                <img id="file_chart_usg_img" src="<?php echo base_url( "resource/patients/ophthalmology" ) ?>/<?php echo sprintf("default_picture_%s.jpg", $ophthalmology) ?>">
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
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$.fn.extend({
				form_files_ophthalmology_od: function( remote_server ){
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
						
						return this;
					}
			});
		
		$( document ).ready(function(e) {
            	$( "form[name=\"form_files_ophthalmology_od\"]" )
					.form_files_ophthalmology_od( "<?php echo @$update_action ?>" );
        	});
	})( jQuery );
//]]>
</script>

