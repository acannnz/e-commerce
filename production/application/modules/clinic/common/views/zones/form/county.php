<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:code_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <input type="text" id="code" name="f[code]" value="<?php echo @$item->code ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:province_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <input type="text" id="zone_name" name="f[zone_name]" value="<?php echo @$item->zone_name ?>" placeholder="" class="form-control" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:country_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <?php echo form_dropdown(
					'', 
					(array(0 => lang('zones:country_no_select')) + $options_country), 
					@$country_id, 
					'id="select_country" class="form-control"'
				); ?>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:province_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <?php echo form_dropdown(
					'f[parent_id]', 
					(array(0 => lang('zones:province_no_select')) + $options_province), 
					@$item->parent_id, 
					'id="select_province" class="form-control"'
				); ?>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('zones:description_label') ?></label>
    <div class="col-lg-6">
        <textarea id="zone_description" name="f[zone_description]" placeholder="" wrap="virtual" class="form-control"><?php echo @$item->zone_description ?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('icd:state_label')?></label>
    <div class="col-lg-6">
        <label class="switch">
            <input type="hidden" value="0" name="f[state]" />
            <input type="checkbox" <?php if(@$item->state == 1){ echo "checked=\"checked\""; } ?> name="f[state]" value="1">
            <span></span>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
    	<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
    </div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$.fn.extend({
				locale_chosen: function( target, endpoint, option_text ){
						var _this = this;
						if( !_this.size() ){return _this}
						
						var _target = jQuery( target );
						
						_this.on( "change", function(){
								if( selected = _this.val() || 0 ){
									_target.locale_populate( endpoint, selected, option_text )
								}
							});
							
						return _this;						
					},
				locale_populate: function( endpoint, sup_id, option_text ){
						var _this = this;
						if( !_this.size() ){return _this}
						
						jQuery.ajax({
									url: '<?php echo base_url( "common/zones" ) ?>/' + endpoint + '/' + sup_id,
									dataType: 'json',
									type: 'GET',
									data: {"sup_id": sup_id},
									beforeSend: function( xhr, settings ){
											//_this.get(0).options.length = 0;
											_this.html("");
											
											jQuery( "<option></option>" )
												.val("0")
												.text("Loading...")
												.appendTo( _this );
										},
									success: function(response, status, xhr) {
											var populate = jQuery( response.populate || [] );
											_this.locale_option( populate, option_text );
										},
									error: function(xhr, msg) {}
								})
							//.done(function( response, status, xhr ){})
							//.fail(function( xhr, status, msg ){})
							//.always(function( data, status, msg ){})
							//.then(function( data, status, xhr ){}, function( xhr, status, msg ){})
							;
						
						return _this;
					},
				locale_option: function( populate, option_text ){
						var _this = this;
						if( !_this.size() ){return _this}
						
						if( populate.size() ){
							_this.html("");
											
							jQuery( "<option></option>" )
								.val("0")
								.text( option_text || "Select a Option" )
								.appendTo( _this );
							
							populate.each(function(i){
									var _option = jQuery( "<option></option>" );
									_option.val( this.value );
									_option.text( this.label );
									
									_this.append( _option );
								});
						} else {
							_this.html("");
											
							jQuery( "<option></option>" )
								.val("0")
								.text("Empty")
								.appendTo( _this );
						}
						
						return _this;
					}
			});
		
		$( document ).ready(function(){
				$( "select#select_country" ).locale_chosen( "select#select_province", "populate_province", "Select a Province" );
				//$( "select#select_province" ).locale_chosen( "select#select_county", "populate_county", "Select a County" );
				//$( "select#select_county" ).locale_chosen( "select#select_district", "populate_district", "Select a District" );
				//$( "select#select_district" ).locale_chosen( "select#select_area", "populate_area", "Select a Area" );
			});
	})( jQuery );
//]]>
</script>