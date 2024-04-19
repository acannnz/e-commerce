<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>

<style>
	.datepicker{z-index:999999 !important;}
</style>

<?php echo form_open( $form_action, [
		'id' => 'form_personal', 
		'name' => 'form_personal', 
		'rule' => 'form', 
		'class' => ''
	]); ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-body table-responsive">	
				<ul class="nav nav-tabs nav-justified">
					<li class="active"><a href="#post-personal" data-toggle="tab"><i class="fa fa-user-circle"></i> <strong><?php echo lang("subtitle:personal")?></strong></a></li>
					<li class=""><a href="#post-environment" data-toggle="tab"><i class="fa fa-envira"></i> <strong><?php echo lang("subtitle:environment")?></strong></a></li>
					<li class=""><a href="#post-obgyn" data-toggle="tab"><i class="fa fa-venus"></i> <strong><?php echo lang("subtitle:obgyn")?></strong></a></li>
					<li class=""><a href="#post-immunization" data-toggle="tab"><i class="fa fa-heart"></i> <strong><?php echo lang("subtitle:immunization")?></strong></a></li>
				</ul>
				<div class="tab-content">
					<div id="post-personal" class="tab-pane active">
						<?php echo @$view_form_personal ?>
					</div>
					<div id="post-environment" class="tab-pane">
						<?php echo @$view_form_environment ?>
					</div>
					<div id="post-obgyn" class="tab-pane">
						<?php echo @$view_form_obgyn ?>
					</div>
					<div id="post-immunization" class="tab-pane">
						<?php echo @$view_form_immunization ?>
					</div>
				</div>	
				
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
							<button class="btn btn-default" type="button" data-dismiss="modal">Close</button> 
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
		var _form = $("#form_personal");
		
		$( document ).ready(function(e) {
				_form_actions.init();
						
				$("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var data_post = {};
						data_post['personal'] = {
								PersonalName : _form.find('input[name=\"f[PersonalName]\"]').val(),
								PersonalGender : _form.find('select[name=\"f[PersonalGender]\"]').val(),
								PersonalBirthPlace : _form.find('input[name=\"f[PersonalBirthPlace]\"]').val(),
								PersonalBirthDate : _form.find('input[name=\"f[PersonalBirthDate]\"]').val(),
								PersonalAge : _form.find('input[name=\"f[PersonalAge]\"]').val(),
								PersonalNationality : _form.find('select[name=\"f[PersonalNationality]\"]').val(),
								PersonalReligion : _form.find('select[name=\"f[PersonalReligion]\"]').val(),
								PersonalIDType : _form.find('select[name=\"f[PersonalIDType]\"]').val(),
								PersonalIDNumber : _form.find('input[name=\"f[PersonalIDNumber]\"]').val(),
								//PersonalPicture : _form.find("#PersonalPicture")[0].files[0], //_form.find('input[name=\"f[PersonalPicture]\"]').val(),
								PersonalProfession : _form.find('input[name=\"f[PersonalProfession]\"]').val(),
								PersonalEducation : _form.find('input[name=\"f[PersonalEducation]\"]').val(),
								//PersonalFirstVisitDate : _form.find('input[name=\"f[PersonalFirstVisitDate]\"]').val(),
								PersonalAddress : _form.find('input[name=\"f[PersonalAddress]\"]').val(),
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
								PhoneNumber : _form.find('input[name=\"f[PhoneNumber]\"]').val(),
								MobileNumber : _form.find('input[name=\"f[MobileNumber]\"]').val(),
								EmailAddress : _form.find('input[name=\"f[EmailAddress]\"]').val(),
								FacebookId : _form.find('input[name=\"f[FacebookId]\"]').val(),
								InstagramId : _form.find('input[name=\"f[InstagramId]\"]').val(),
								TwitterId : _form.find('input[name=\"f[TwitterId]\"]').val(),
								Allergies : _form.find('input[name=\"f[Allergies]\"]').val(),
								BPJSNo : _form.find('input[name=\"f[BPJSNo]\"]').val(),
								BPJSFaskes1No : _form.find('input[name=\"f[BPJSFaskes1No]\"]').val(),
								BPJSFaskes1Name : _form.find('input[name=\"f[BPJSFaskes1Name]\"]').val(),
								BPJSFaskes2No : _form.find('input[name=\"f[BPJSFaskes2No]\"]').val(),
								BPJSFaskes2Name : _form.find('input[name=\"f[BPJSFaskes2Name]\"]').val(),
								BPJSStatus : _form.find('input[id=\"BPJSStatus\"]').is(":checked") ? _form.find('input[id=\"BPJSStatus\"]').val() : 0,
								Note : _form.find('textarea[name=\"f[Note]\"]').val(),
								Status : _form.find('input[id=\"Status\"]').is(":checked") ? _form.find('input[id=\"Status\"]').val() : 0,
							};
							
							data_post['relation'] = {
								Relation : _form.find('select[name=\"f[Relation]\"]').val(),
								Index : _form.find('select[name=\"f[Index]\"]').val(),
								HusbandPersonalId : _form.find('select[name=\"f[HusbandPersonalId]\"]').val(),
								WifePersonalId : _form.find('select[name=\"f[WifePersonalId]\"]').val(),
								Note : _form.find('textarea[name=\"f[RelationNote]\"]').val(),
								Status : _form.find('input[id=\"RelationStatus\"]').is(":checked") ? _form.find('input[id=\"RelationStatus\"]').val() : 0,
							};
							
							data_post['additional'] = {
								IsPatriarch : _form.find('input[id=\"IsPatriarch\"]').is(":checked") ? _form.find('input[id=\"IsPatriarch\"]').val() : 0,
							};
							
							data_post['environment'] = {
								house_wall : $('input[name="e[house_wall]"]:checked').val(),
								house_floor : $('input[name="e[house_floor]"]:checked').val(),
								house_lighting : $('input[name="e[house_lighting]"]:checked').val(),
								house_krpl : $('input[name="e[house_krpl]"]:checked').val(),
								waste_disposal : $('input[name="e[waste_disposal]"]:checked').val(),
								sewer : $('input[name="e[sewer]"]:checked').val(),
								water_source : $('input[name="e[water_source]"]:checked').val(),
								toilet : $('input[name="e[toilet]"]:checked').val() == 'etc' ? $('input[name="e[toiletEtcText]"]').val() : $('input[name="e[toilet]"]:checked').val(),
								dietary_staple_food : $('input[name="e[dietary_staple_food]"]').is(':checked') ? $('input[name="e[dietary_staple_food_text]"]').val() : 0,
								dietary_side_dishes : $('input[name="e[dietary_side_dishes]"]:checked').val() || 0,
								dietary_vegetables : $('input[name="e[dietary_vegetables]"]:checked').val() || 0,
								dietary_fruits : $('input[name="e[dietary_fruits]"]:checked').val() || 0,
								dietary_milk : $('input[name="e[dietary_milk]"]:checked').val() || 0,								
							};
							
							data_post['obgyn'] = {
								born_status : $('input[name="o[born_status]"]:checked').val(),
								condition_status : $('input[name="o[condition_status]"]:checked').val(),
								obgyn_by : $('input[name="o[obgyn_by]"]').val(),
							};
	
							data_post['immunization'] = {
								BCG_1 : $('input[name="i[BCG_1]"]:checked').val() || 0,
								DPT_1 : $('input[name="i[DPT_1]"]:checked').val() || 0,
								DPT_2 : $('input[name="i[DPT_2]"]:checked').val() || 0,
								DPT_3 : $('input[name="i[DPT_3]"]:checked').val() || 0,
								polio_1 : $('input[name="i[polio_1]"]:checked').val() || 0,
								polio_2 : $('input[name="i[polio_2]"]:checked').val() || 0,
								polio_3 : $('input[name="i[polio_3]"]:checked').val() || 0,
								polio_4 : $('input[name="i[polio_4]"]:checked').val() || 0,
								hepatitis_b_1 : $('input[name="i[hepatitis_b_1]"]:checked').val() || 0,
								hepatitis_b_2 : $('input[name="i[hepatitis_b_2]"]:checked').val() || 0,
								hepatitis_b_3 : $('input[name="i[hepatitis_b_3]"]:checked').val() || 0,
								campak_1 : $('input[name="i[campak_1]"]:checked').val() || 0,
								DT_1 : $('input[name="i[DT_1]"]:checked').val() || 0,
								DT_2 : $('input[name="i[DT_2]"]:checked').val() || 0,
							};
					
					var data = new FormData();
					$.each($('#PersonalPicture')[0].files, function(i, v) {
						data.append('PersonalPicture', v);
					});					
					
					$.each(data_post, function(i, v) {
						$.each(v, function(key, val) {
							data.append(i +'['+ key +']', val);
						});
					});
						
					$.ajax({
						type: "POST",
						url: _form.prop("action"),
						dataType: 'json',
						data: data,
						contentType: false, 
						processData: false, 
						cache : false,
						success: function( response ){
							
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							$.alert_success( response.message );
							var id = response.id;
							
							setTimeout(function(){
														
								document.location.href = "<?php echo base_url($nameroutes); ?>";
								
								}, 300 );
						}
					});
				});

			});

	})( jQuery );
//]]>
</script>
