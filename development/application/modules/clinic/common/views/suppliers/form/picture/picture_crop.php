<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$picture_personal_name = ($profile->personal_picture) ? $profile->personal_picture : "default_picture.jpg";
$picture_personal_type = @pathinfo($picture_personal_name, PATHINFO_EXTENSION);
?>
<div class="row">
	<div class="col-md-12">
        <?php echo form_open_multipart(current_url(), array('id' => 'form_crop_personal_picture', 'name' => 'form_crop_personal_picture')); ?>
        <div class="form-group">
            <div class="col-md-12 crop-wrapper">
            	<div class="crop-canvas"></div>
            </div>
            <?php /*?><div class="col-md-12">
            	<div class="crop-preview"></div>
            </div><?php */?>
        </div>
        <div class="form-group">
        	<div class="col-md-6">
            	<button type="submit" id="" name="" title="<?php echo lang("buttons:crop_picture") ?>" class="btn btn-block btn-submit btn-info"><i class="fa fa-crop"></i> <?php echo lang("buttons:crop_picture") ?></button>
            </div>
            <div class="col-md-6">
            	<?php if( isset($is_ajax_request) ): ?>
                <a href="<?php echo base_url( "common/suppliers/picture/{$profile_id}" ) ?>" data-toggle="ajax-modal" title="<?php echo lang("buttons:cancel") ?>" class="btn btn-block btn-cancel btn-danger"><i class="fa fa-times"></i> <?php echo lang("buttons:cancel") ?></a>
                <?php else: ?>
                <a href="<?php echo base_url( "common/suppliers/picture/{$profile_id}" ) ?>" title="<?php echo lang("buttons:cancel") ?>" class="btn btn-block btn-cancel btn-danger"><i class="fa fa-times"></i> <?php echo lang("buttons:cancel") ?></a>
            	<?php endif ?>
            </div>
        </div>
        <input type="hidden" name="picture_crop" value="1">
		<?php echo form_close() ?>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$.fn.extend({
				form_picture_crop: function( picture_name, picture_type, elem_canvas, elem_preview ){
						if( ! this.size() ){ return this; }
						
						elem_canvas = elem_canvas || ".crop-canvas";
						elem_preview = elem_preview || ".crop-preview";
						
						var _this = this;
						
						var _picture_crop = new CROP();
						_picture_crop.init({			 
								container: elem_canvas,			 
								image: "<?php echo base_url( "resource/suppliers/pictures" ) ?>/" + picture_name + "<?php echo (sprintf("?rand=%s", @time())) ?>",
								width: 240,
								height: 240,			 
								mask: true,			 
								zoom: { steps: 0.01, min: 1, max: 5 },			 
								<?php /*?>preview: { container: elem_preview, ratio: 0.4 },<?php */?>			 
							});
							
						_this.on( "submit", function(e){
								e.preventDefault();
								
								var _picture = _picture_crop.crop( 420, 420, 'png' );
								var picture_row		= String( _picture.string ).replace( /^data\:image\/\w+\;base64\, /, '' );
								//var picture_type	= _picture.type;
								var picture_type	= "jpg";
								var picture_width	= Number( _picture.width );
								var picture_height	= Number( _picture.height );
								
								var post_remote = _this.attr( "action" );
								var post_data = {
										"picture_row": picture_row, 
										"picture_type": picture_type,
										"picture_width": picture_width,
										"picture_height": picture_height
									};
								
								$.post( post_remote, post_data, function( data, status, xhr ){
										<?php if( isset($is_ajax_request) ): ?>
										try{
											ajax_modal.show( "<?php echo base_url( "common/suppliers/picture/{$profile_id}" ) ?>" );
										} catch(e){}
										<?php else: ?>
										window.location = "<?php echo base_url( "common/suppliers/picture/{$profile_id}" ) ?>";
										<?php endif ?>									
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
						
						return this;
					}
			});
		
		$( document ).ready(function(e) {
            	$( "form[name=\"form_crop_personal_picture\"]" )
					.form_picture_crop( "<?php echo $picture_personal_name ?>", "<?php echo $picture_personal_type ?>" );
        	});
	})( jQuery );
//]]>
</script>


