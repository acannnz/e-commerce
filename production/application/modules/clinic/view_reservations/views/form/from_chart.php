<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form_chart_registration", "name" => "form_chart_registration") ); ?>

<?php if( isset($is_modal) ):?>
<div class="row">
	<div class="col-md-6 col-sm-12">
    	<button type="submit" title="<?php echo lang( 'buttons:submit' ) ?>" class="btn btn-block btn-primary"><i class="fa fa-save"></i> <?php echo lang( 'buttons:submit' ) ?></button>
    </div>
    <div class="col-md-6 col-sm-12">
    	<button type="reset" title="<?php echo lang( 'buttons:reset' ) ?>" class="btn btn-block btn-warning"><i class="fa fa-undo"></i> <?php echo lang( 'buttons:reset' ) ?></button>
    </div>
</div>
<hr class="margin-top-5">
<?php endif ?>

<?php if( isset($is_modal) ){ echo Modules::run("system/alert"); }  ?>

<div class="page-subtitle">
	<i class="fa fa-user pull-left text-info"></i>
    <h3 class="text-info"><?php echo lang('registrations:registration_subtitle') ?></h3>
	<p><?php echo lang('registrations:registration_subtitle_helper') ?></p>
</div>
<div class="row">
	<?php if( isset($from_chart) ): ?>
    <div class="col-md-12">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:registration_number_label') ?> <span class="text-danger">*</span></label>
            <input type="text" id="registration_number" name="f[registration_number]" value="<?php echo @$item->registration_number ?>" placeholder="" class="form-control">
        </div>
    </div>
	<?php else: ?>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:registration_number_label') ?> <span class="text-danger">*</span></label>
            <input type="text" id="registration_number" name="f[registration_number]" value="<?php echo @$item->registration_number ?>" placeholder="" class="form-control">
        </div>
    </div>
    <div class="col-md-8">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:reservation_number_label') ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
                <input type="text" id="reservation_number" name="f[reservation_number]" value="<?php echo @$item->reservation_number ?>" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<a href="<?php echo base_url("registrations/lookup_reservations") ?>" data-toggle="lookup-ajax-modal" title="<?php echo lang('buttons:pick_reservation') ?>" class="btn btn-success"><?php echo lang('buttons:pick_reservation') ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>
<div class="row">
	<div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:for_date_label') ?> <span class="text-danger">*</span></label>
            <div class="input-group">
            	<div class="input-group-addon">
                	<i class="fa fa-calendar"></i>
                </div>
            	<input type="text" id="registration_date" name="f[registration_date]" value="<?php echo @$item->registration_date ?>" placeholder="" class="form-control datepicker">
        	</div>
        </div>
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:for_time_label') ?></label>
            <div class="input-group">
            	<div class="input-group-addon">
                	<i class="fa fa-clock-o"></i>
                </div>
            	<input type="text" id="registration_time" name="f[registration_time]" value="<?php echo @$item->registration_time ?>" placeholder="" class="form-control timepicker">
        	</div>
        </div>
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:queue_label') ?></label>
            <input type="number" id="schedule_queue" name="f[schedule_queue]" value="<?php echo (int) @$item->schedule_queue ?>" placeholder="" class="form-control">
        </div>
    </div>
</div>

<div class="page-subtitle margin-top-30">
	<i class="fa fa-user pull-left text-info"></i>
    <h3 class="text-info"><?php echo lang('registrations:personal_subtitle') ?></h3>
	<p><?php echo lang('registrations:personal_subtitle_helper') ?></p>
</div>
<div class="row">
	<div class="col-md-8">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:name_label') ?> <span class="text-danger">*</span></label>
            <input type="text" id="personal_name" name="f[personal_name]" value="<?php echo @$item->personal_name ?>" placeholder="" class="form-control" required>
        </div>
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:mr_number_label') ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
                <input type="text" id="mr_number" name="f[mr_number]" value="<?php echo @$item->mr_number ?>" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<a href="<?php echo base_url("examinations/lookup_patients") ?>" data-toggle="lookup-ajax-modal" title="<?php echo lang('buttons:pick_patient') ?>" class="btn btn-success"><?php echo lang('buttons:pick_patient') ?></a>
                </div>
            </div>
        </div>
    </div>    
</div>

<div class="row">
	<div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:birth_date_label') ?></label>
            <div class="input-group">
            	<div class="input-group-addon">
                	<i class="fa fa-calendar"></i>
                </div>
            	<input type="text" id="personal_birth_date" name="f[personal_birth_date]" value="<?php echo @$item->personal_birth_date ?>" placeholder="" class="form-control datepicker">
        	</div>
        </div>
    </div>
    <div class="col-md-2">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:gender_label') ?></label>
            <br>                               
            <div class="radio radio-inline">
                <input type="radio" id="personal_gender_male" name="f[personal_gender]" value="MALE"<?php if("MALE" == @$item->personal_gender){echo " checked";} ?>>
                <label for="personal_gender_male"><?php echo lang('gender:male') ?></label>
            </div>
            <div class="radio radio-inline">
                <input type="radio" id="personal_gender_female" name="f[personal_gender]" value="FEMALE"<?php if("FEMALE" == @$item->personal_gender){echo " checked";} ?>>
                <label for="personal_gender_female"><?php echo lang('gender:female') ?></label>
            </div>
        </div>
    </div>
    <div class="col-md-2">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:age_label') ?></label>
            <input type="number" id="personal_age" name="f[personal_age]" value="<?php echo (int) @$item->personal_age ?>" placeholder="" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:nationality_label') ?></label>
            <?php echo form_dropdown(
					'f[personal_nationality]', 
					(array(0 => lang('registrations:nationality_no_select')) + $options_nationality), 
					@$item->personal_nationality, 
					'id="" class="form-control"'
				); ?>
        </div>
    </div>
</div>

<div class="page-subtitle margin-top-30">
	<i class="fa fa-map-marker pull-left text-info"></i>
    <h3 class="text-info"><?php echo lang('registrations:address_subtitle') ?></h3>
	<p><?php echo lang('registrations:address_subtitle_helper') ?></p>
</div>

<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:address_label') ?><span class="text-danger">*</span></label>
            <input type="text" id="personal_address" name="f[personal_address]" value="<?php echo @$item->personal_address ?>" placeholder="" class="form-control" required>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:country_label') ?></label>
            <?php echo form_dropdown(
					'f[country_id]', 
					(array(0 => lang('registrations:country_no_select')) + $options_country), 
					@$item->country_id, 
					'id="select_country" class="form-control"'
				); ?>
        </div>
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:province_label') ?></label>
            <?php echo form_dropdown(
					'f[province_id]', 
					(array(0 => lang('registrations:province_no_select')) + $options_province), 
					@$item->province_id, 
					'id="select_province" class="form-control"'
				); ?>
        </div>
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:county_label') ?></label>
            <?php echo form_dropdown(
					'f[county_id]', 
					(array(0 => lang('registrations:county_no_select')) + $options_county), 
					@$item->county_id, 
					'id="select_county" class="form-control"'
				); ?>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:district_label') ?></label>
            <?php echo form_dropdown(
					'f[district_id]', 
					(array(0 => lang('registrations:district_no_select')) + $options_district), 
					@$item->district_id, 
					'id="select_district" class="form-control"'
				); ?>
        </div>
    </div>
    <div class="col-md-8">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:area_label') ?></label>
            <?php echo form_dropdown(
					'f[area_id]', 
					(array(0 => lang('registrations:area_no_select')) + $options_area), 
					@$item->area_id, 
					'id="select_area" class="form-control"'
				); ?>
        </div>
    </div>
</div>

<div class="page-subtitle margin-top-30">
	<i class="fa fa-phone pull-left text-info"></i>
    <h3 class="text-info"><?php echo lang('registrations:contact_subtitle') ?></h3>
	<p><?php echo lang('registrations:contact_subtitle_helper') ?></p>
</div>
<div class="row">
	<div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:phone_label') ?></label>
            <input type="tel" id="phone_number" name="f[phone_number]" value="<?php echo @$item->phone_number ?>" placeholder="" class="form-control">
        </div>	        
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:email_label') ?></label>
            <input type="email" id="email_address" name="f[email_address]" value="<?php echo @$item->email_address ?>" placeholder="" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label class="control-label"><?php echo lang('registrations:profession_label') ?></label>
            <input type="text" id="personal_profession" name="f[personal_profession]" value="<?php echo @$item->personal_profession ?>" placeholder="" class="form-control">
        </div>
    </div>
</div>

<?php if( isset($is_modal) ):?>
<hr>
<div class="row">
	<div class="col-md-6 col-sm-12">
    	<button type="submit" title="<?php echo lang( 'buttons:submit' ) ?>" class="btn btn-block btn-primary"><i class="fa fa-save"></i> <?php echo lang( 'buttons:submit' ) ?></button>
    </div>
    <div class="col-md-6 col-sm-12">
    	<button type="reset" title="<?php echo lang( 'buttons:reset' ) ?>" class="btn btn-block btn-warning"><i class="fa fa-undo"></i> <?php echo lang( 'buttons:reset' ) ?></button>
    </div>
</div>
    <?php else: ?>
<div class="row margin-top-30">
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button>
    </div>
</div>
<?php endif ?>

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
		
		$.fn.form_chart_registration = function(){
				if( ! this.size() ){ return this }
				
				this.on("submit", function(e){
						<?php if( isset($is_modal) ): ?>
						e.preventDefault();
						
						var _form = $( this );
						form_ajax_modal.post(function(){
								setTimeout(function(){
										form_ajax_modal.hide();
										window.location = "<?php echo base_url( "examinations" ) ?>";
									}, 1700)
							}, _form);
						<?php endif ?>
					});
				
				return this
			};
		
		$( document ).ready(function(){
				try{ 
					dev_forms.init();
					
					$(".datepicker").datetimepicker({format: "YYYY-MM-DD"});
					
					$( "select#select_country" ).locale_chosen( "select#select_province", "populate_province", "Select a Province" );
					$( "select#select_province" ).locale_chosen( "select#select_county", "populate_county", "Select a County" );
					$( "select#select_county" ).locale_chosen( "select#select_district", "populate_district", "Select a District" );
					$( "select#select_district" ).locale_chosen( "select#select_area", "populate_area", "Select a Area" );
					
					$( "form[name=\"form_chart_registration\"]").form_chart_registration();
					
					<?php if( ! isset($is_modal) ): ?>
					$( window ).trigger( "resize" );
					<?php endif ?>
				}catch(e){}	
			});
	})( jQuery );
//]]>
</script>