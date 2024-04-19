<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url() ); ?>

<div class="page-subtitle">
	<i class="fa fa-user pull-left text-info"></i>
    <h3 class="text-info"><?php echo lang('patients:general_subtitle') ?></h3>
	<p><?php echo lang('patients:general_subtitle_helper') ?></p>
</div>
<div class="form-group-one-unit margin-bottom-30">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-custom">
            	<label class="control-label"><?php echo lang('patients:mr_number_label') ?> <span class="text-danger">*</span></label>
                <div class="form-control-clear-wrap">
                    <input type="text" id="mr_number" name="f[mr_number]" value="<?php echo @$item->mr_number ?>" placeholder="" class="form-control" required>
            	</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-custom">
            	<label class="control-label"><?php echo lang('patients:type_label') ?> <span class="text-danger">*</span></label>
                <div class="form-control-clear-wrap">
                    <?php echo form_dropdown(
                            'f[type_id]', 
                            (array(0 => lang('patients:type_no_select')) + $options_type), 
                            @$item->type_id, 
                            'id="" class="form-control" required'
                        ); ?>
            	</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:state_label')?></label>
                <div class="form-control-clear-wrap">
                    <div class="radio radio-inline">
                        <input type="radio" id="state_0" name="f[state]" value="0"<?php if(0 == @$item->state){echo " checked";} ?>>
                        <label for="state_0"><?php echo lang('global:inactive') ?></label>
                    </div>
                    <div class="radio radio-inline">
                        <input type="radio" id="state_1" name="f[state]" value="1"<?php if(1 == @$item->state){echo " checked";} ?>>
                        <label for="state_1"><?php echo lang('global:active') ?></label>
                    </div>
                    <?php /*?><label class="switch">
                        <input type="hidden" value="0" name="f[state]" />
                        <input type="checkbox" <?php if(@$item->state == 1){ echo "checked=\"checked\""; } ?> name="f[state]" value="1">
                        <span></span>
                    </label><?php */?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-group-custom form-control-clear">
                <label class="control-label"><?php echo lang('patients:name_label') ?> <span class="text-danger">*</span></label>
                <div class="form-control-clear-wrap">
                	<input type="text" id="personal_name" name="f[personal_name]" value="<?php echo @$item->personal_name ?>" placeholder="" class="form-control" required>
            	</div>
            </div>
        </div>    
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-custom form-control-clear">
                <label class="control-label"><?php echo lang('patients:birth_date_label') ?></label>
                <div class="form-control-clear-wrap">
                	<input type="text" id="personal_birth_date" name="f[personal_birth_date]" value="<?php echo @$item->personal_birth_date ?>" placeholder="" class="form-control datepicker">
            	</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:gender_label') ?></label>
                <div class="form-control-clear-wrap">
                    <?php /*?><div class="radio radio-inline">
                        <input type="radio" id="personal_gender_male" name="f[personal_gender]" value="MALE"<?php if("MALE" == @$item->personal_gender){echo " checked";} ?>>
                        <label for="personal_gender_male"><?php echo lang('gender:male') ?></label>
                    </div><?php */?>
                    <div class="radio radio-inline">
                        <input type="radio" id="personal_gender_female" name="f[personal_gender]" value="FEMALE"<?php if("FEMALE" == @$item->personal_gender){echo " checked";} ?>>
                        <label for="personal_gender_female"><?php echo lang('gender:female') ?></label>
                    </div>
            	</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:age_label') ?></label>
                <div class="form-control-clear-wrap">
                	<input type="number" id="personal_age" name="f[personal_age]" value="<?php echo (int) @$item->personal_age ?>" placeholder="" class="form-control">
            	</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:nationality_label') ?></label>
                <div class="form-control-clear-wrap">
					<?php echo form_dropdown(
                            'f[personal_nationality]', 
                            (array(0 => lang('patients:nationality_no_select')) + $options_nationality), 
                            @$item->personal_nationality, 
                            'id="" class="form-control"'
                        ); ?>
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="page-subtitle">
	<i class="fa fa-map-marker pull-left text-info"></i>
    <h3 class="text-info"><?php echo lang('patients:address_subtitle') ?></h3>
	<p><?php echo lang('patients:address_subtitle_helper') ?></p>
</div>
<div class="form-group-one-unit margin-bottom-30">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-group-custom form-control-clear">
                <label class="control-label"><?php echo lang('patients:address_label') ?></label>
                <div class="form-control-clear-wrap">
                    <input type="text" id="personal_address" name="f[personal_address]" value="<?php echo @$item->personal_address ?>" placeholder="" class="form-control" required>
                    <?php /*?><textarea id="personal_address" name="f[personal_address]" placeholder="" wrap="virtual" class="form-control"><?php echo @$item->personal_address ?></textarea><?php */?>
            	</div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:country_label') ?></label>
                <?php echo form_dropdown(
                        'f[country_id]', 
                        (array(0 => lang('patients:country_no_select')) + $options_country), 
                        @$item->country_id, 
                        'id="select_country" class="form-control"'
                    ); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:province_label') ?></label>
                <div class="form-control-clear-wrap">
					<?php echo form_dropdown(
                            'f[province_id]', 
                            (array(0 => lang('patients:province_no_select')) + $options_province), 
                            @$item->province_id, 
                            'id="select_province" class="form-control"'
                        ); ?>
            	</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:county_label') ?></label>
                <div class="form-control-clear-wrap">
					<?php echo form_dropdown(
                            'f[county_id]', 
                            (array(0 => lang('patients:county_no_select')) + $options_county), 
                            @$item->county_id, 
                            'id="select_county" class="form-control"'
                        ); ?>
            	</div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:district_label') ?></label>
                <div class="form-control-clear-wrap">
					<?php echo form_dropdown(
                            'f[district_id]', 
                            (array(0 => lang('patients:district_no_select')) + $options_district), 
                            @$item->district_id, 
                            'id="select_district" class="form-control"'
                        ); ?>
            	</div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group form-group-custom">
                <label class="control-label"><?php echo lang('patients:area_label') ?></label>
                <div class="form-control-clear-wrap">
					<?php echo form_dropdown(
                            'f[area_id]', 
                            (array(0 => lang('patients:area_no_select')) + $options_area), 
                            @$item->area_id, 
                            'id="select_area" class="form-control"'
                        ); ?>
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="page-subtitle">
	<i class="fa fa-phone pull-left text-info"></i>
    <h3 class="text-info"><?php echo lang('patients:contact_subtitle') ?></h3>
	<p><?php echo lang('patients:contact_subtitle_helper') ?></p>
</div>
<div class="form-group-one-unit margin-bottom-30">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-custom form-control-clear">
                <label class="control-label"><?php echo lang('patients:phone_label') ?></label>
                <div class="form-control-clear-wrap">
                	<input type="tel" id="phone_number" name="f[phone_number]" value="<?php echo @$item->phone_number ?>" placeholder="" class="form-control">
            	</div>
            </div>	        
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-custom form-control-clear">
                <label class="control-label"><?php echo lang('patients:email_label') ?></label>
                <div class="form-control-clear-wrap">
                	<input type="email" id="email_address" name="f[email_address]" value="<?php echo @$item->email_address ?>" placeholder="" class="form-control">
            	</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-custom form-control-clear">
                <label class="control-label"><?php echo lang('patients:profession_label') ?></label>
                <div class="form-control-clear-wrap">
                	<input type="text" id="personal_profession" name="f[personal_profession]" value="<?php echo @$item->personal_profession ?>" placeholder="" class="form-control">
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="row margin-bottom-40">
	<div class="col-lg-offset-0 col-lg-12">
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
				<?php if( isset($is_ajax_request) ): ?>
				try{ dev_forms.init() }catch(e){}
				
				<?php endif ?>
				$(".datepicker").datetimepicker({format: "YYYY-MM-DD"});
				
				$( "select#select_country" ).locale_chosen( "select#select_province", "populate_province", "Select a Province" );
				$( "select#select_province" ).locale_chosen( "select#select_county", "populate_county", "Select a County" );
				$( "select#select_county" ).locale_chosen( "select#select_district", "populate_district", "Select a District" );
				$( "select#select_district" ).locale_chosen( "select#select_area", "populate_area", "Select a Area" );
			});
	})( jQuery );
//]]>
</script>