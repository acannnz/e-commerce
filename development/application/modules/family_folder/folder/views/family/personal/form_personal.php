<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>

<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:name') ?></label>
			<div class="col-lg-9">
				<?php echo form_input('f[PersonalName]', set_value('f[PersonalName]', @$personal->PersonalName, TRUE), [
						'id' => 'PersonalName', 
						'placeholder' => '',
						'class' => 'form-control'
					]); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:relegion') ?></label>
			<div class="col-lg-9">
				<select id="PersonalReligion" name="f[PersonalReligion]" class="form-control" >
					<option value="BD" <?php echo @$personal->PersonalReligion == "BD"  ? "selected" : NULL  ?>>BUDHA</option>
					<option value="HD" <?php echo @$personal->PersonalReligion == "HD"  ? "selected" : NULL  ?>>HINDU</option>
					<option value="IS" <?php echo @$personal->PersonalReligion == "IS"  ? "selected" : NULL  ?>>ISLAM</option>
					<option value="KC" <?php echo @$personal->PersonalReligion == "KC"  ? "selected" : NULL  ?>>KONGHUCU</option>
					<option value="KR" <?php echo @$personal->PersonalReligion == "KR"  ? "selected" : NULL  ?>>KRISTEN</option>
					<option value="KT" <?php echo @$personal->PersonalReligion == "KT"  ? "selected" : NULL  ?>>KHATOLIK</option>
					<option value="LL" <?php echo @$personal->PersonalReligion == "LL"  ? "selected" : NULL  ?>>LAIN-LAIN</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:gender') ?></label>
			<div class="col-lg-9">
				<select id="PersonalGender" name="f[PersonalGender]" class="form-control" >
					<option value="M" <?php echo @$personal->PersonalGender == "M"  ? "selected" : NULL  ?>><?php echo lang('global:male')?></option>
					<option value="F" <?php echo @$personal->PersonalGender == "F"  ? "selected" : NULL  ?>><?php echo lang('global:female')?></option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:birth_place') ?></label>
			<div class="col-lg-9">
				<input type="text" id="PersonalBirthPlace" name="f[PersonalBirthPlace]" value="<?php echo @$personal->PersonalBirthPlace ?>" placeholder="" class="form-control" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:birth_date') ?></label>
			<div class="col-lg-3">
				<input type="text" id="PersonalBirthDate" name="f[PersonalBirthDate]" value="<?php echo @$personal->PersonalBirthDate ?>" placeholder="" class="form-control datepicker" data-act='datepicker'>
			</div>
			<label class="col-lg-3 control-label text-center"><?php echo lang('label:age') ?></label>
			<div class="col-lg-3">
				<input type="text" id="PersonalAge" name="f[PersonalAge]" value="<?php echo @$personal->PersonalAge ?>" placeholder="" class="form-control" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:country') ?></label>
			<div class="col-lg-9">
				<select id="PersonalNationality" name="f[PersonalNationality]" class="form-control" >
					<?php if(!empty($option_nationality)): foreach($option_nationality as $key => $val):?>
					<option value="<?php echo $key ?>" <?php echo $key == @$personal->PersonalNationality ? "selected" : NULL  ?>><?php echo $val ?></option>
					<?php endforeach; endif;?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:id_type') ?></label>
			<div class="col-lg-9">
				<select id="PersonalIDType" name="f[PersonalIDType]" class="form-control" >
					<option value="KTP" <?php echo @$personal->PersonalIDType == "KTP"  ? "selected" : NULL  ?>>KTP</option>
					<option value="SIM" <?php echo @$personal->PersonalIDType == "SIM"  ? "selected" : NULL  ?>>SIM</option>
					<option value="KP" <?php echo @$personal->PersonalIDType == "KP"  ? "selected" : NULL  ?>>Kartu Pelajar</option>
					<option value="Visa" <?php echo @$personal->PersonalIDType == "Visa"  ? "selected" : NULL  ?>>Visa</option>
					<option value="LL" <?php echo @$personal->PersonalIDType == "LL"  ? "selected" : NULL  ?>>Lain-Lain</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:id_number') ?></label>
			<div class="col-lg-9">
				<input type="text" id="PersonalIDNumber" name="f[PersonalIDNumber]" value="<?php echo @$personal->PersonalIDNumber ?>" placeholder="" class="form-control" >
			</div>
		</div>		
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:profession') ?></label>
			<div class="col-lg-9">
				<input type="text" id="PersonalProfession" name="f[PersonalProfession]" value="<?php echo @$personal->PersonalProfession ?>" placeholder="" class="form-control" >
			</div>
		</div>					
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:education') ?></label>
			<div class="col-lg-9">
				<input type="text" id="PersonalEducation" name="f[PersonalEducation]" value="<?php echo @$personal->PersonalEducation ?>" placeholder="" class="form-control" >
			</div>
		</div>		
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:picture') ?></label>
			<div class="col-lg-9">
				<input type="file" id="PersonalPicture" name="f[PersonalPicture]" value="<?php echo @$personal->PersonalPicture ?>" placeholder="" class="form-control" >
			</div>
		</div>		
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:allergies') ?></label>
			<div class="col-lg-9">
				<input type="text" id="Allergies" name="f[Allergies]" value="<?php echo @$personal->Allergies ?>" placeholder="" class="form-control" >
			</div>
		</div>						
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:note') ?></label>
			<div class="col-lg-9">
				<textarea id="Note" name="f[Note]" placeholder="" class="form-control"><?php echo @$personal->Note ?></textarea>
			</div>
		</div>						
	</div>
	
	<div class="col-md-6">
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:address') ?></label>
			<div class="col-lg-9">
				<input type="text" id="PersonalAddress" name="f[PersonalAddress]" value="<?php echo @$personal->PersonalAddress ?>" placeholder="" class="form-control" required>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:postalcode') ?></label>
			<div class="col-lg-9">
				<input type="text" id="PostalCode" name="f[PostalCode]" value="<?php echo @$personal->PostalCode ?>" placeholder="" class="form-control" required>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:country') ?></label>
			<div class="col-lg-9">
				<select id="CountryId" name="f[CountryId]" class="form-control" >
					<?php if(!empty($option_country)): foreach($option_country as $key => $val):?>
					<option value="<?php echo $key ?>" <?php echo $key == @$personal->CountryId ? "selected" : NULL  ?>><?php echo $val ?></option>
					<?php endforeach; endif;?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:province') ?></label>
			<div class="col-lg-9">
				<select id="ProvinceId" name="f[ProvinceId]" class="form-control" >
					<option value=""><?php echo lang('select:province_no_select') ?></option>
					<?php if(!empty($option_province)): foreach($option_province as $key => $val):?>
					<option value="<?php echo $key ?>" <?php echo $key == @$personal->ProvinceId ? "selected" : NULL  ?>><?php echo $val ?></option>
					<?php endforeach; endif;?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:county') ?></label>
			<div class="col-lg-9">
				<select id="CountyId" name="f[CountyId]" class="form-control" >
					<?php if(!empty($option_county)): foreach($option_county as $key => $val):?>
					<option value="<?php echo $key ?>" <?php echo $key == @$personal->CountyId ? "selected" : NULL  ?>><?php echo $val ?></option>
					<?php endforeach; endif;?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:district') ?></label>
			<div class="col-lg-9">
				<select id="DistrictId" name="f[DistrictId]" class="form-control" >
					<?php if(!empty($option_district)): foreach($option_district as $key => $val):?>
					<option value="<?php echo $key ?>" <?php echo $key == @$personal->DistrictId ? "selected" : NULL  ?>><?php echo $val ?></option>
					<?php endforeach; endif;?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:village') ?></label>
			<div class="col-lg-9">
				<select id="VillageId" name="f[VillageId]" class="form-control" >
					<?php if(!empty($option_village)): foreach($option_village as $key => $val):?>
					<option value="<?php echo $key ?>" <?php echo $key == @$personal->VillageId ? "selected" : NULL  ?>><?php echo $val ?></option>
					<?php endforeach; endif;?>
				</select>
			</div>
		</div>
		<?php /*?><div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:area') ?></label>
			<div class="col-lg-9">
				<select id="AreaId" name="f[AreaId]" class="form-control" >
					<?php if(!empty($option_area)): foreach($option_area as $key => $val):?>
					<option value="<?php echo $key ?>" <?php echo $key == @$personal->AreaId ? "selected" : NULL  ?>><?php echo $val ?></option>
					<?php endforeach; endif;?>
				</select>
			</div>
		</div><?php */?>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:phone') ?></label>
			<div class="col-lg-9">
				<input type="text" id="PhoneNumber" name="f[PhoneNumber]" value="<?php echo @$personal->PhoneNumber ?>" placeholder="" class="form-control" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:mobile') ?></label>
			<div class="col-lg-9">
				<input type="text" id="MobileNumber" name="f[MobileNumber]" value="<?php echo @$personal->MobileNumber ?>" placeholder="" class="form-control" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:email') ?></label>
			<div class="col-lg-9">
				<input type="email" id="EmailAddress" name="f[EmailAddress]" value="<?php echo @$personal->EmailAddress ?>" placeholder="" class="form-control" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:facebook') ?></label>
			<div class="col-lg-9">
				<input type="text" id="FacebookId" name="f[FacebookId]" value="<?php echo @$personal->FacebookId ?>" placeholder="" class="form-control" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:instagram') ?></label>
			<div class="col-lg-9">
				<input type="text" id="InstagramId" name="f[InstagramId]" value="<?php echo @$personal->InstagramId ?>" placeholder="" class="form-control" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:twitter') ?></label>
			<div class="col-lg-9">
				<input type="text" id="TwitterId" name="f[TwitterId]" value="<?php echo @$personal->TwitterId ?>" placeholder="" class="form-control" >
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php echo lang('global:status')?></label>
			<div class="col-sm-9">
				<div class="ckbox ckbox-success">
					<input type="checkbox" id="StatusHidden" name="f[Status]" value="0">
					<input type="checkbox" id="Status" name="f[Status]" value="1" <?php echo @$personal->Status == 1 ? 'checked' : NULL?>>
					<label for="Status"><?php echo lang('global:active')?></label>
				</div>
			</div>
		</div>
	</div>
	</div>
	
	<hr/>
	
	<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<div class="col-lg-12">
				<h4 class="subtitle"><?php echo lang('label:relation')?></h4>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:relation') ?></label>
			<div class="col-lg-9">
				<select id="Relation" name="f[Relation]" class="form-control">
					<option value="HUSBAND" <?php echo @$relation->Relation == 'HUSBAND' ? 'selected' : NULL?>><?php echo lang('label:husband')?></option>
					<option value="WIFE" <?php echo @$relation->Relation == 'WIFE' ? 'selected' : NULL?>><?php echo lang('label:wife')?></option>
					<option value="SIBLING" <?php echo @$relation->Relation == 'SIBLING' ? 'selected' : NULL?>><?php echo lang('label:sibling')?></option>
					<option value="CHILD" <?php echo @$relation->Relation == 'CHILD' ? 'selected' : NULL?>><?php echo lang('label:child')?></option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:from_husband') ?></label>
			<div class="col-lg-9">
				<select id="HusbandPersonalId" name="f[HusbandPersonalId]" class="form-control">
					<option value="" ><?php echo lang('global:select-empty')?></option>
					<?php foreach( $option_husband as $key => $val ): ?>
					<option value="<?php echo $key ?>" <?php echo @$key == @$relation->HusbandPersonalId ? 'selected' : NULL?>><?php echo $val ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:from_wife') ?></label>
			<div class="col-lg-9">
				<select id="WifePersonalId" name="f[WifePersonalId]" class="form-control">
					<option value="" ><?php echo lang('global:select-empty')?></option>
					<?php foreach( $option_wife as $key => $val ): ?>
					<option value="<?php echo $key ?>" <?php echo @$key == @$relation->WifePersonalId ? 'selected' : NULL?>><?php echo $val ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:index') ?></label>
			<div class="col-lg-9">
				<select id="Index" name="f[Index]" class="form-control">
					<option value="0" ><?php echo lang('global:select-empty')?></option>
					<?php for( $i = 1; $i <= 10; $i++ ): ?>
					<option value="<?php echo $i ?>" <?php echo @$i == @$relation->Index ? 'selected' : NULL?>><?php echo $i ?></option>
					<?php endfor; ?>
				</select>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:note') ?></label>
			<div class="col-lg-9">
				<textarea id="RelationNote" name="f[RelationNote]" placeholder="" class="form-control"><?php echo @$relation->Note ?></textarea>
			</div>
		</div>				
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php echo lang('global:status')?></label>
			<div class="col-sm-3">
				<div class="ckbox ckbox-success">
					<input type="hidden" id="RelationStatusHidden" name="f[RelationStatusHidden]" value="0">
					<input type="checkbox" id="RelationStatus" name="f[RelationStatus]" value="1" <?php echo @$relation->Status == 1 ? 'checked' : NULL?>>
					<label for="RelationStatus"><?php echo lang('global:active')?></label>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="ckbox ckbox-success">
					<input type="checkbox" id="IsPatriarch" name="f[IsPatriarch]" value="1" <?php echo @$item->PersonalIdKK == @$personal->Id ? 'checked' : NULL?>>
					<label for="IsPatriarch"><?php echo lang('label:patriarch')?></label>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="form-group">
			<div class="col-lg-12">
				<h4 class="subtitle"><?php echo lang('label:bpjs')?></h4>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:bpjs_number') ?></label>
			<div class="col-lg-9">
				<input type="text" id="BPJSNo" name="f[BPJSNo]" value="<?php echo @$personal->BPJSNo ?>" placeholder="" class="form-control" >
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:bpjs_faskes1_no') ?></label>
			<div class="col-lg-9">
				<input type="text" id="BPJSFaskes1No" name="f[BPJSFaskes1No]" value="<?php echo @$personal->BPJSFaskes1No ?>" placeholder="" class="form-control" >
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:bpjs_faskes1_name') ?></label>
			<div class="col-lg-9">
				<input type="text" id="BPJSFaskes1Name" name="f[BPJSFaskes1Name]" value="<?php echo @$personal->BPJSFaskes1Name ?>" placeholder="" class="form-control" >
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:bpjs_faskes2_no') ?></label>
			<div class="col-lg-9">
				<input type="text" id="BPJSFaskes2No" name="f[BPJSFaskes2No]" value="<?php echo @$personal->BPJSFaskes2No ?>" placeholder="" class="form-control" >
			</div>
		</div>	
		<div class="form-group">
			<label class="col-lg-3 control-label"><?php echo lang('label:bpjs_faskes2_name') ?></label>
			<div class="col-lg-9">
				<input type="text" id="BPJSFaskes2Name" name="f[BPJSFaskes2Name]" value="<?php echo @$personal->BPJSFaskes2Name ?>" placeholder="" class="form-control" >
			</div>
		</div>	
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php echo lang('label:bpjs_status')?></label>
			<div class="col-sm-9">
				<div class="ckbox ckbox-success">
					<input type="hidden" id="BPJSStatusHidden" name="f[BPJSStatus]" value="0" >
					<input type="checkbox" id="BPJSStatus" name="f[BPJSStatus]" value="1" <?php echo @$personal->BPJSStatus == 1 ? 'checked' : NULL?>>
					<label for="BPJSStatus"><?php echo lang('global:active')?></label>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_personal");
		_form_actions = {
				init: function(){
					
						$( "select#CountryId" ).locale_chosen( "select#ProvinceId", "populate_province", "Select a Province" );
						$( "select#ProvinceId" ).locale_chosen( "select#CountyId", "populate_county", "Select a County" );
						$( "select#CountyId" ).locale_chosen( "select#DistrictId", "populate_district", "Select a District" );
						$( "select#DistrictId" ).locale_chosen( "select#VillageId", "populate_village", "Select a Village" );
						//$( "select#VillageId" ).locale_chosen( "select#AreaId", "populate_area", "Select a Area" );
						
						$(".datepicker").datepicker({format: "yyyy-mm-dd"});
						$("#PersonalBirthDate").on("changeDate", function (e) {
					
							age = _form_actions.getAge( $(this).val() );
							$("#PersonalAge").val( age.years );
						});
								
				
					},
				getAge: function( dateString ) {
					
						var now = new Date();
						var today = new Date(now.getYear(),now.getMonth(),now.getDate());
						
						var yearNow = now.getYear();
						var monthNow = now.getMonth();
						var dateNow = now.getDate();
						// yyyy-mm-dd
						var dob = new Date(dateString.substring(0, 4), //yyyy
										 dateString.substring(5, 7) - 1, //mm               
										 dateString.substring(8, 10)    //dd            
										 );
						
						var yearDob = dob.getYear();
						var monthDob = dob.getMonth();
						var dateDob = dob.getDate();
						var age = {};
						var ageString = "";
						var yearString = "";
						var monthString = "";
						var dayString = "";
						
						yearAge = yearNow - yearDob;
						
						if (monthNow >= monthDob){
							var monthAge = monthNow - monthDob;
						} else {
							yearAge--;
							var monthAge = 12 + monthNow -monthDob;
						}
						
						if (dateNow >= dateDob){
							var dateAge = dateNow - dateDob;
						} else {
							monthAge--;
							var dateAge = 31 + dateNow - dateDob;
							
							if (monthAge < 0) {
								monthAge = 11;
								yearAge--;
							}
						}
						
						return age = {
							years: yearAge,
							months: monthAge,
							days: dateAge
						};
					},
			};
		
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
									url: '<?php echo base_url( "folder/zones" ) ?>/' + endpoint + '/' + sup_id,
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
					},
			});
		
		
		$( document ).ready(function(e) {
				_form_actions.init();

			});

	})( jQuery );
//]]>
</script>
