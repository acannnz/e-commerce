<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php /*?> ref: https://css-tricks.com/drag-and-drop-file-uploading/ <?php */?>
<div class="row">
    <div class="col-md-12">
        <?php echo form_open_multipart(current_url(), array('id' => 'form_files_cardiology_acyanotic_ekg_upload', 'name' => 'form_files_cardiology_acyanotic_ekg_upload', 'class' => 'drop-box text-center')); ?>
        <div class="drop-box__input">
			<svg viewBox="0 0 50 43" height="43" width="50" xmlns="http://www.w3.org/2000/svg" class="drop-box__icon"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
            <input type="file" id="input_userfile" name="userfile" data-multiple-caption="{count} files selected" class="drop-box__file" />
            <label for="input_userfile"><strong>Choose a file</strong><span class="drop-box__dragndrop"> or drag it here</span>.</label>
            <button class="btn btn-primary drop-box__button" type="submit">Upload</button>
        </div>
        <div class="drop-box__uploading">Uploading&hellip;</div>
        <div class="drop-box__success">Done!</div>
        <div class="drop-box__error">Error! <span></span>.</div>
		<?php echo form_close() ?>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		var is_advanced_upload = function(){
				var div = document.createElement( 'div' );
				return ( ( 'draggable' in div ) || ( 'ondragstart' in div && 'ondrop' in div ) ) && 'FormData' in window && 'FileReader' in window;
			}();
		
		$.fn.extend({
				form_files_cardiology_acyanotic_ekg_upload: function( remote_server ){
						if( ! this.size() ){ return this; }
						
						var _this = this;
						var _form = this;
						
						var _input = _form.find( 'input[type="file"]' );
						var _label = _form.find( 'label' );
						var _error_msg = _form.find( '.drop-box__error > span' );
						var _restart = _form.find( '.drop-box__restart' );
						var _dropped_files = false;
							
						var show_files = function( files ){
									_label.text( files.length > 1 ? ( _input.attr( 'data-multiple-caption' ) || '' ).replace( '{count}', files.length ) : files[ 0 ].name );
								},
							uploaded_files = function( files ){
									try{
										var _form_v = $( "form#form_files_cardiology_acyanotic_ekg" );
										var img = _form_v.find( "img#file_chart_usg_img" );
										var box_img = _form_v.find( "a#file_chart_usg_thumbnail" );
										box_img.attr( "href", "javascript:;" );
										img.attr( "src", "<?php echo base_url( "resource/patients/cardiology/acyanotic" ) ?>/" + (files.file_name || "default_picture_os.jpg") + "?rund=" + (new Date()).getTime() );											
										setTimeout(function(){ $( "#ajax-modal" ).modal( "hide" ); }, 600)
									} catch(ex){}
								};
			
						// letting the server side to know we are going to make an Ajax request
						_form.append( '<input type="hidden" name="ajax" value="1" />' );
			
						// automatically submit the form on file select
						_input.on( 'change', function( e ){
								show_files( e.target.files );
							});
						
						// drag&drop files if the feature is available
						if( is_advanced_upload ){
							_form
								.addClass( 'has-advanced-upload' ) // letting the CSS part to know drag&drop is supported by the browser
								.on( 'drag dragstart dragend dragover dragenter dragleave drop', function( e ){
										// preventing the unwanted behaviours
										e.preventDefault();
										e.stopPropagation();
									})
								.on( 'dragover dragenter', function(){
										_form.addClass( 'is-dragover' );
									})
								.on( 'dragleave dragend drop', function(){
										_form.removeClass( 'is-dragover' );
									})
								.on( 'drop', function( e ){
										_dropped_files = e.originalEvent.dataTransfer.files; // the files that were dropped
										show_files( _dropped_files );
									});
						}
						
						// if the form was submitted
						_form.on( 'submit', function( e ){
								// preventing the duplicate submissions if the current one is in progress
								if( _form.hasClass( 'is-uploading' ) ) return false;
				
								_form.addClass( 'is-uploading' ).removeClass( 'is-error' );
								
								// ajax file upload for modern browsers
								if( is_advanced_upload ){
									e.preventDefault();
				
									// gathering the form data
									var ajax_data = new FormData( _form.get( 0 ) );
									if( _dropped_files ){
										$.each( _dropped_files, function( i, file ){
												ajax_data.append( _input.attr( 'name' ), file );
											});
									}
				
									// ajax request
									$.ajax({
											url: _form.attr( 'action' ),
											type: _form.attr( 'method' ),
											data: ajax_data,
											dataType: 'json',
											cache: false,
											contentType: false,
											processData: false,
											complete: function(){
													_form.removeClass( 'is-uploading' );
												},
											success: function( data ){
													_form.addClass( data.status == 'success' ? 'is-success' : 'is-error' );
													if( ! (data.status == 'success') ){ _error_msg.text( data.message || 'Upload failed!' ); }
													else { uploaded_files( data["item"] || {} ); }
												},
											error: function(){
													alert( 'Error. Please, contact the webmaster!' );
												}
										});
								
								// fallback Ajax solution upload for older browsers
								} else {
									var iframe_name	= 'uploadiframe' + new Date().getTime();
									var _iframe	= $( '<iframe name="' + iframe_name + '" style="display: none;"></iframe>' );
				
									$( 'body' ).append( _iframe );
									_form.attr( 'target', iframe_name );
				
									_iframe.one( 'load', function(){
											var data = $.parseJSON( _iframe.contents().find( 'body' ).text() );
											_iframe.remove();
											
											_form.removeClass( 'is-uploading' ).addClass( data.status == 'success' ? 'is-success' : 'is-error' ).removeAttr( 'target' );
											if( ! (data.status == 'success') ){ _error_msg.text( data.message || 'Upload failed!' ); }
											else { uploaded_files( data["item"] || {} ); }											
										});
								}
							});
						
						// restart the form if has a state of error/success			
						_restart.on( 'click', function( e ){
								e.preventDefault();
								
								_form.removeClass( 'is-error is-success' );
								_input.trigger( 'click' );
							});
			
						// Firefox focus bug fix for file input
						_input
							.on( 'focus', function(){ _input.addClass( 'has-focus' ); })
							.on( 'blur', function(){ _input.removeClass( 'has-focus' ); });
						
						return this;
					}
			});
		
		$( document ).ready(function(e) {
            	$( "form[name=\"form_files_cardiology_acyanotic_ekg_upload\"]" )
					.form_files_cardiology_acyanotic_ekg_upload( "<?php echo @$form_action ?>" );
        	});
	})( jQuery );
//]]>
</script>

