<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_family', 
		'name' => 'form_family', 
		'rule' => 'form', 
		'class' => ''
	]); ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">                
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <a href="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        <i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:family_update'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6 col-xs-12">
                        <div class="form-group">
                        <?php echo form_label(lang('label:no_family').' *', 'NoFamily', ['class' => 'control-label col-md-3']) ?>
                        <div class="col-md-9">
							<?php echo form_input('f[NoFamily]', set_value('f[NoFamily]', @$item->NoFamily, TRUE), [
									'id' => 'NoFamily', 
									'placeholder' => '', 
									'readonly' => 'readonly',
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:no_kk'), 'NoKK', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[NoKK]', set_value('f[NoKK]', @$item->NoKK, TRUE), [
										'id' => 'NoKK', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                           <?php echo form_label(lang('label:family_reff'), 'ReffNoFamily', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">									
								<?php echo form_input('f[ReffNoFamily]', set_value('f[ReffNoFamily]', @$item->ReffNoFamily, TRUE), [
										'id' => 'ReffNoFamily', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
                            </div>
	                    </div>
						
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:address') ?></label>
							<div class="col-lg-9">
								<input type="text" id="Address" name="f[Address]" value="<?php echo @$item->Address ?>" placeholder="" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:postalcode') ?></label>
							<div class="col-lg-9">
								<input type="text" id="PostalCode" name="f[PostalCode]" value="<?php echo @$item->PostalCode ?>" placeholder="" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:country') ?></label>
							<div class="col-lg-9">
								<select id="CountryId" name="f[CountryId]" class="form-control" >
									<?php if(!empty($option_country)): foreach($option_country as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$item->CountryId ? "selected" : NULL  ?>><?php echo $val ?></option>
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
									<option value="<?php echo $key ?>" <?php echo $key == @$item->ProvinceId ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:county') ?></label>
							<div class="col-lg-9">
								<select id="CountyId" name="f[CountyId]" class="form-control" >
									<?php if(!empty($option_county)): foreach($option_county as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$item->CountyId ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:district') ?></label>
							<div class="col-lg-9">
								<select id="DistrictId" name="f[DistrictId]" class="form-control" >
									<?php if(!empty($option_district)): foreach($option_district as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$item->DistrictId ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:village') ?></label>
							<div class="col-lg-9">
								<select id="VillageId" name="f[VillageId]" class="form-control" >
									<?php if(!empty($option_village)): foreach($option_village as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$item->VillageId ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<?php /*?><div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:area') ?></label>
							<div class="col-lg-9">
								<select id="AreaId" name="f[AreaId]" class="form-control" >
									<?php if(!empty($option_area)): foreach($option_area as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$item->AreaId ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div><?php */?>
                	</div>
					<div class="col-md-6">
						<div class="form-group">
							<?php echo form_label(lang('label:note'), 'Note', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_textarea([
										'name' => 'f[Note]', 
										'value' => set_value('f[Note]', @$item->Note, TRUE),
										'id' => 'Note', 
										'placeholder' => '',
										'rows' => 3,
										'class' => 'form-control'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:patriarch')?></label>
							<div class="col-md-9">
								<select id="PersonalIdKK" name="f[PersonalIdKK]" class="form-control">
									<?php foreach( @$option_personal as $key => $val ): ?>
									<option value="<?php echo $key ?>" <?php echo $item->PersonalIdKK == $key ? 'selected' : NULL?>><?php echo $val ?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo lang('global:status')?></label>
						  	<div class="col-sm-9">
								<div class="ckbox ckbox-success">
									<input type="checkbox" id="StatusHidden" name="f[Status]" value="0">
									<input type="checkbox" id="Status" name="f[Status]" value="1" <?php echo @$item->Status == 1 ? 'checked' : NULL?>>
									<label for="Status"><?php echo lang('global:active')?></label>
								</div>
						  	</div>
						</div>
					</div>
                </div>
	
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">New</button> 
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Close</button> 
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_family");
		_form_actions = {
				init: function(){
						$('input[name=\"f[ReffNoFamily]\"]').family_reff();
					
						$( "select#CountryId" ).locale_chosen( "select#ProvinceId", "populate_province", "Select a Province" );
						$( "select#ProvinceId" ).locale_chosen( "select#CountyId", "populate_county", "Select a County" );
						$( "select#CountyId" ).locale_chosen( "select#DistrictId", "populate_district", "Select a District" );
						$( "select#DistrictId" ).locale_chosen( "select#VillageId", "populate_village", "Select a Village" );
						//$( "select#VillageId" ).locale_chosen( "select#AreaId", "populate_area", "Select a Area" );
						
					}
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
				family_reff: function(){
						var _input = this;
						
						_input.typeahead({
								items: "all",
								minLength: 2,
								source: function( query, process ){
										var _this = this;
										 	_this.items = {};
										
										$.ajax({
												url: "<?php echo $family_reff_search_url; ?>",
												type: 'POST',
												data: {query: query, FamilyId: <?php echo $item->Id ?>},
												dataType: 'JSON',
												success: function( result ){
													_this.items = result.map(function( dt ) {
															return JSON.stringify( dt );
														});
													
													var _options = result.map(function( dt ) {
															return (dt.NoFamily +' | '+ dt.NoKK +' | '+ dt.PersonalName || "");
														});
													
													return process( _options )
												}
											})
									},
								 updater: function( label ){
										var _items = JSON.parse("[" + this.items + "]");
										var _item = $.grep(_items, function( dt ){
												return ($.trim(label) == $.trim(dt.NoFamily +' | '+ dt.NoKK +' | '+ dt.PersonalName))
											})[0] || {};
											
										_input.data('FamilyId', _item.Id );
										
										return label
									}

							});
							
						return _input;
					}
			});
		
		
		$( document ).ready(function(e) {
				_form_actions.init();
						
				$("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var data_post = {};
							data_post['family'] = {
								NoKK : _form.find('input[name=\"f[NoKK]\"]').val(),
								PersonalIdKK : _form.find('select[name=\"f[PersonalIdKK]\"]').val(),
								ReffNoFamily : _form.find('input[name=\"f[ReffNoFamily]\"]').val() != '' ? _form.find('input[name=\"f[ReffNoFamily]\"]').data('FamilyId') : null,
								Address : _form.find('input[name=\"f[Address]\"]').val(),
								PostalCode : _form.find('input[name=\"f[PostalCode]\"]').val(),
								CountryId : _form.find('select[name=\"f[CountryId]\"]').val(),
								CountryName : $('#CountryId option:selected').text(),
								ProvinceId : _form.find('select[name=\"f[ProvinceId]\"]').val(),
								ProvinceName : $('#ProvinceId option:selected').text(),
								CountyId : _form.find('select[name=\"f[CountyId]\"]').val(),
								CountyName : $('#CountyId option:selected').text(),
								DistrictId : _form.find('select[name=\"f[DistrictId]\"]').val(),
								DistrictName : $('#DistrictId option:selected').text(),
								VillageId : _form.find('select[name=\"f[VillageId]\"]').val(),
								VillageName : $('#VillageId option:selected').text(),
								//AreaId : _form.find('select[name=\"f[AreaId]\"]').val(),
								//AreaName : $('#AreaId option:selected').text(),
								Note : _form.find('textarea[name=\"f[Note]\"]').val(),
								Status : _form.find('input[id=\"Status\"]').is(":checked") ? _form.find('input[id=\"Status\"]').val() : 0,
							};
							
					$.post( _form.prop("action"), data_post, function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("{$nameroutes}/update"); ?>/"+ id;
							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
