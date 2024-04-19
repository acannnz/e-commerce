<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php echo form_open(current_url(), array("id" => "form_files_chart_laboratory_file_delete", "name" => "form_files_chart_laboratory_file_delete")); ?>
        <div class="modal-header bg-danger"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('global:delete_title')?></h4>
        </div>        
        <div class="modal-body">
            <p><?php echo lang('global:delete_confirm')?></p>            
            <input type="hidden" name="confirm" value="1">   
        </div>
        <div class="modal-footer"> 
        	<div class="col-md-6">
            	<button type="submit" class="btn btn-block btn-default"><?php echo lang('buttons:yes') ?></button>        
    		</div>
            <div class="col-md-6">
            	<a href="javascript:;" class="btn btn-block btn-danger" data-dismiss="modal"><?php echo lang('buttons:no') ?></a>
            </div>
        </div>
        <?php echo form_close() ?>
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$.fn.extend({
				form_files_chart_laboratory_file_delete: function(){
						if( ! this.size() ){ return this; }
						
						var _form = this;
						
						_form.on( "submit", function(e){
								e.preventDefault();
								
								try{
									var form_action = _form.attr( "action" );
									var form_data = _form.serializeArray();
									
									_form.find( "button[type=\"submit\"]" ).text( "<?php echo lang("global:ajax_deleting") ?>" );
									$.post( form_action, form_data, function( response, status, xhr ){
											_form.find( "button[type=\"submit\"]" ).text( "<?php echo lang("buttons:yes") ?>" );
											
											//console.log( status );
											try{
												var img = $( "img#file_chart_laboratory_img" );
												var box_img = $( "a#file_chart_laboratory_thumbnail" );
												box_img.attr( "href", "javascript:;" );
												img.attr( "src", "<?php echo base_url( "resource/patients/laboratory" ) ?>/default_picture.jpg?rund=" + (new Date()).getTime() );											
												setTimeout(function(){ $( "#ajax-modal" ).modal( "hide" ); }, 600)
											} catch(ex){}
										})
								} catch(e){ console.log( "Chart files ajax delete failed: " + e.message ); }
								
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
            	$( "form[name=\"form_files_chart_laboratory_file_delete\"]" )
					.form_files_chart_laboratory_file_delete();
        	});
	})( jQuery );
//]]>
</script>

