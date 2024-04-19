<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
        <?php echo form_open_multipart(current_url(), array('id' => 'form_capture_personal_picture', 'name' => 'form_capture_personal_picture')); ?>
        <div class="form-group">
            <div class="col-md-12">
            	<div class="camera-viewer"></div>
            </div>
        </div>
        <div class="form-group">
        	<div class="col-md-6">
            	<button type="submit" id="" name="" title="<?php echo lang("buttons:take_picture") ?>" class="btn btn-block btn-submit btn-info"><i class="fa fa-camera"></i> <?php echo lang("buttons:take_picture") ?></button>
            </div>
            <div class="col-md-6">
            	<?php if( isset($is_ajax_request) ): ?>
                <a href="<?php echo base_url( "common/nurses/picture/{$profile_id}" ) ?>" data-toggle="ajax-modal" title="<?php echo lang("buttons:cancel") ?>" class="btn btn-block btn-cancel btn-danger"><i class="fa fa-times"></i> <?php echo lang("buttons:cancel") ?></a>
                <?php else: ?>
                <a href="<?php echo base_url( "common/nurses/picture/{$profile_id}" ) ?>" title="<?php echo lang("buttons:cancel") ?>" class="btn btn-block btn-cancel btn-danger"><i class="fa fa-times"></i> <?php echo lang("buttons:cancel") ?></a>
            	<?php endif ?>
            </div>
        </div>
        <input type="hidden" name="picture_capture" value="1">
        <?php echo form_close() ?>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$.fn.extend({
				form_picture_camera: function(){
						if( ! this.size() ){ return this; }
						
						var _this = this;
						var _camera_viewer = _this.find( ".camera-viewer");
						
						if( ! _camera_viewer.find( "> div" ).size() ){
							$( "<div></div>" ).appendTo( _camera_viewer );
						}
						
						var _camera = _camera_viewer.find( "> div" );						
						_camera.attr( "id", "camera_" + (new Date()).getTime() );						
						var camera_has = "#" + _camera.attr( "id" );
						
						var dest_width = 800;
						var dest_height = 600;
						
						Webcam.set({
								// live preview size
								width: 320,
								height: 240,
								
								// device capture size
								//dest_width: 640,
								//dest_height: 480,
								dest_width: dest_width,
								dest_height: dest_height,
								
								// final cropped size
								//crop_width: 640,
								//crop_height: 640,
								
								// format and quality
								image_format: 'jpeg',
								jpeg_quality: 90
							});							
						Webcam.attach( camera_has );
						// library is loaded
						Webcam.on( "load", function(){ 
								//
							} );
						// camera is live, showing preview image
        				// (and user has allowed access)							
						Webcam.on( "live", function(){
								//
							} );
						// an error occurred
						Webcam.on( "error", function(){
								//
							} );
							
						_this.on( "submit", function(e){
								e.preventDefault();
								
								Webcam.snap( function( data_uri ) {
										var picture_raw = String( data_uri ).replace( /^data\:image\/\w+\;base64\, /, '' );
										var picture_type = "jpg";
										var picture_width = dest_width;
										var picture_height = dest_height;
											
										var post_remote = _this.attr( "action" );
										var post_data = {
												"picture_row": picture_raw, 
												"picture_type": picture_type,
												"picture_width": picture_width,
												"picture_height": picture_height
											};
										
										$.post( post_remote, post_data, function( data, status, xhr ){
												<?php if( isset($is_ajax_request) ): ?>
												try{
													ajax_modal.show( "<?php echo base_url( "common/nurses/picture_crop/{$profile_id}" ) ?>" );
												} catch(e){}
												<?php else: ?>
												window.location = "<?php echo base_url( "common/nurses/picture_crop/{$profile_id}" ) ?>";
												<?php endif ?>									
											} );
									} );
								
								return false;
							} );	
						_this.on( "keyup keypress", function(e){
								var kc = e.keyCode || e.which;
								if( kc == 13 ){ 
									e.preventDefault();
									return false;
								}
							} );
						
						return this
					}
			});
		
		$( document ).ready(function(e) {
            	$( "form[name=\"form_capture_personal_picture\"]" ).form_picture_camera();
        	});
	})( jQuery );
//]]>
</script>


