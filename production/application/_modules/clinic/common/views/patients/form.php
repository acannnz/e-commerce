<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//echo 123; exit;
?>

<?php echo form_open_multipart( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang('patients:heading')?></h3>
			</div>
            <div class="panel-body">
				<div class="row">
					<h3 class="page-subtitle"><?php echo lang('patients:general_subtitle') ?></h3>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:mr_number_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NRM" name="NRM" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control"readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:mr_number_label') ?> Lama</label>
							<div class="col-lg-9">
								<input type="text" id="NRMLama" name="NRMLama" value="<?php echo @$item->NRMLama ?>" placeholder="" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:type_label') ?></label>
							<div class="col-lg-9">
								<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control">
									<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
									<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:name_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NamaPasien" name="f[NamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:alias_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NamaAlias" name="f[NamaAlias]" value="<?php echo @$item->NamaAlias ?>" placeholder="" class="form-control patient">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:id_number_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NoIdentitas" name="f[NoIdentitas]" value="<?php echo @$item->NoIdentitas ?>" placeholder="" class="form-control patient" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:relegion_label') ?></label>
							<div class="col-lg-9">
								<select id="Agama" name="f[Agama]" class="form-control patient" >
									<option value="BD" <?php echo @$item->Agama == "BD"  ? "selected" : NULL  ?>>BUDHA</option>
									<option value="HD" <?php echo @$item->Agama == "HD"  ? "selected" : NULL  ?>>HINDU</option>
									<option value="IS" <?php echo @$item->Agama == "IS"  ? "selected" : NULL  ?>>ISLAM</option>
									<option value="KC" <?php echo @$item->Agama == "KC"  ? "selected" : NULL  ?>>KONGHUCU</option>
									<option value="KR" <?php echo @$item->Agama == "KR"  ? "selected" : NULL  ?>>KRISTEN</option>
									<option value="KT" <?php echo @$item->Agama == "KT"  ? "selected" : NULL  ?>>KHATOLIK</option>
									<option value="LL" <?php echo @$item->Agama == "LL"  ? "selected" : NULL  ?>>LAIN-LAIN</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:gender_label') ?></label>
							<div class="col-lg-9">
								<select id="JenisKelamin" name="f[JenisKelamin]" class="form-control patient" >
									<option value="F" <?php echo @$item->JenisKelamin == "F"  ? "selected" : NULL  ?>>P</option>
									<option value="M" <?php echo @$item->JenisKelamin == "M"  ? "selected" : NULL  ?>>L</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:birth_date_label') ?></label>
							<div class="col-lg-3">
								<input type="hidden" id="UmurSaatInput" name="f[UmurSaatInput]" value="<?php echo @$item->UmurSaatInput ?>" />
								<input type="text" id="TglLahir" name="f[TglLahir]" value="<?php echo @$item->TglLahir ?>" placeholder="" class="form-control datepicker patient" >
							</div>
							<label class="col-lg-1 control-label"><?php echo lang('patients:age_label') ?></label>
							<div class="col-lg-1">
								<input type="text" id="UmurThn" name="UmurThn" value="<?php echo @$item->UmurThn ?>" placeholder="" class="form-control" >
							</div>
							<label class="col-lg-1 control-label"><?php echo lang('patients:year_label') ?></label>
							<div class="col-lg-1">
								<input type="text" id="UmurBln" name="UmurBln" value="<?php echo @$item->UmurBln ?>" placeholder="" class="form-control" >
								<input type="hidden" id="UmurHr" name="UmurHr" value="<?php echo @$item->UmurHr ?>">
							</div>
							<label class="col-lg-1 control-label"><?php echo lang('patients:month_label') ?></label>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:birth_place_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="TempatLahir" name="f[TempatLahir]" value="<?php echo @$item->TempatLahir ?>" placeholder="" class="form-control patient" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:phone_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="Phone" name="f[Phone]" value="<?php echo @$item->Phone ?>" placeholder="" class="form-control patient" >
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:country_label') ?></label>
							<div class="col-lg-9">
								<select id="NationalityID" name="f[NationalityID]" class="form-control patient" >
									<?php if(!empty($option_nationality)): foreach($option_nationality as $row):?>
									<option value="<?php echo $row->NationalityID ?>" <?php echo $row->NationalityID == @$item->NationalityID ? "selected" : NULL  ?>><?php echo $row->Nationality ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:address_label') ?></label>
							<div class="col-lg-9">
								<textarea id="Alamat" name="f[Alamat]" placeholder="" class="form-control patient" ><?php echo @$item->Alamat ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:province_label') ?></label>
							<div class="col-lg-9">
								<select id="PropinsiID" name="f[PropinsiID]" class="form-control patient" >
									<?php if(!empty($option_province)): foreach($option_province as $row):?>
									<option value="<?php echo $row->Propinsi_ID ?>" <?php echo $row->Propinsi_ID == @$item->PropinsiID ? "selected" : NULL  ?>><?php echo $row->Nama_Propinsi ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:county_label') ?></label>
							<div class="col-lg-9">
								<select id="KabupatenID" name="f[KabupatenID]" class="form-control patient" >
									<?php if(!empty($option_county)): foreach($option_county as $row):?>
									<option value="<?php echo $row->Kabupaten_ID ?>" <?php echo $row->Kabupaten_ID == @$item->KabupatenID ? "selected" : NULL  ?>><?php echo $row->Nama_Kabupaten ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:district_label') ?></label>
							<div class="col-lg-9">
								<select id="KecamatanID" name="f[KecamatanID]" class="form-control patient" >
									<?php if(!empty($option_district)): foreach($option_district as $row):?>
									<option value="<?php echo $row->KecamatanID ?>" <?php echo $row->KecamatanID == @$item->KecamatanID ? "selected" : NULL  ?>><?php echo $row->KecamatanNama ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:village_label') ?></label>
							<div class="col-lg-9">
								<select id="DesaID" name="f[DesaID]" class="form-control patient" >
									<?php if(!empty($option_village)): foreach($option_village as $row):?>
									<option value="<?php echo $row->DesaID ?>" <?php echo $row->DesaID == @$item->DesaID ? "selected" : NULL  ?>><?php echo $row->DesaNama ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:area_label') ?></label>
							<div class="col-lg-9">
								<select id="BanjarID" name="f[BanjarID]" class="form-control patient" >
									<?php if(!empty($option_area)): foreach($option_area as $row):?>
									<option value="<?php echo $row->BanjarID ?>" <?php echo $row->BanjarID == @$item->BanjarID ? "selected" : NULL  ?>><?php echo $row->BanjarNama ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>  
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:profession_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="Pekerjaan" name="f[Pekerjaan]" value="<?php echo @$item->Pekerjaan ?>" placeholder="" class="form-control patient" >
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-3 control-label">Opsi</label>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="hidden" name="f[PasienVVIP]" value="0" >
									<input type="checkbox" id="PasienVVIP" name="f[PasienVVIP]" value="1" <?php echo @$item->PasienVVIP == 1 ? "Checked" : NULL ?> class=" patient" ><label for="PasienVVIP">Pasien VVIP</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="hidden" name="f[PasienKTP]" value="0" >
									<input type="checkbox" id="PasienKTP" name="f[PasienKTP]" value="1" <?php echo @$item->PasienKTP == 1 ? "Checked" : NULL ?> class=" patient" ><label for="PasienKTP">Pasien KTP</label>
								</div>
							</div>
						</div>    
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo 'Upload LES' ?></label>
							<div class="col-lg-9">
								<input type="file" id="PasienLes" name="PasienLes" value="<?php echo @$item->FileLes ?>" placeholder=""><br>
								<?php if(!empty(@$item->FileLes)): ?>
									<a href="<?php echo base_url("resource/patients/les/{$item->FileLes}") ?>" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> Download Les</a> <?php echo @$item->FileLes ?>
								<?php endif; ?>
							</div>
						</div>	  			
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6">
						<h3 class="page-subtitle"><?php echo lang('patients:cooperation_subtitle') ?></h3>
						<div class="form-group">
							<label class="col-lg-3 control-label">Opsi</label>
							<div class="col-md-9">
								<div class="checkbox">
									<input type="hidden" name="f[AnggotaBaru]" value="0" >
									<input type="checkbox" id="AnggotaBaru" name="f[AnggotaBaru]" value="1" class="" ><label for="AnggotaBaru">Anggota Baru</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:company_label') ?></label>
							<div class="col-lg-9">
								<input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>" class="cooperation">
								<input type="hidden" id="KdKelas" name="f[KdKelas]" value="<?php echo @$item->KdKelas ?>"  class="cooperation">
								<div class="input-group">
									<input type="text" id="Nama_Customer"  value="<?php echo @$cooperation->Nama_Customer ?>" placeholder="" class="form-control cooperation" >
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_cooperation ?>" id="lookup_cooperation" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_cooperation" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('patients:card_number_label') ?></label>
							<div class="col-md-9">
								<div class="input-group">
									<input type="text" id="NoKartu" name="f[NoKartu]" value="<?php echo @$item->NoKartu ?>" placeholder="" class="form-control cooperation cooperation_card" >
									<span class="input-group-btn">
										<a href="<?php echo $lookup_patient_cooperation_card ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_cooperation_card" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:group_label') ?></label>
							<div class="col-lg-9">
								<select id="Klp" name="f[Klp]" class="form-control">
									<option value="E" <?php echo @$item->Klp == "E"  ? "selected" : NULL  ?>>E</option>
									<option value="S" <?php echo @$item->Klp == "S"  ? "selected" : NULL  ?>>S</option>
									<option value="C1" <?php echo @$item->Klp == "C1"  ? "selected" : NULL  ?>>C1</option>
									<option value="C2" <?php echo @$item->Klp == "C2"  ? "selected" : NULL  ?>>C2</option>
									<option value="C3" <?php echo @$item->Klp == "C3"  ? "selected" : NULL  ?>>C3</option>
									<option value="C4" <?php echo @$item->Klp == "C4"  ? "selected" : NULL  ?>>C4</option>
									<option value="C5" <?php echo @$item->Klp == "C5"  ? "selected" : NULL  ?>>C5</option>
									<option value="C5" <?php echo @$item->Klp == "C5"  ? "selected" : NULL  ?>>C6</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('patients:member_number_e_label') ?></label>
							<div class="col-lg-6">
								<input type="text" id="NoANggotaE" name="f[NoANggotaE]" value="<?php echo @$item->NoANggotaE ?>" placeholder="" class="form-control patient" >
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<h3 class="page-subtitle">Riwayat Pasien</h3>
						<div class="form-group">
							<label class="col-lg-3 control-label">Riwayat Alergi</label>
							<div class="col-lg-9">
								<textarea id="RiwayatAlergi" name="f[RiwayatAlergi]" placeholder="" class="form-control" ><?php echo @$item->RiwayatAlergi ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Riwayat Penyakit</label>
							<div class="col-lg-9">
								<textarea id="RiwayatPenyakit" name="f[RiwayatPenyakit]" placeholder="" class="form-control" ><?php echo @$item->RiwayatPenyakit ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Riwayat Obat</label>
							<div class="col-lg-9">
								<textarea id="RiwayatObat" name="f[RiwayatObat]" placeholder="" class="form-control" ><?php echo @$item->RiwayatObat ?></textarea>
							</div>
						</div>
					</div>
					<div class="col-lg-12 text-right">
						<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
						<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
						<?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
					</div>
				</div>
			</div>
		</div>
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
								
				//$( "select#select_country" ).locale_chosen( "select#select_province", "populate_province", "Select a Province" );
				//$( "select#select_province" ).locale_chosen( "select#select_county", "populate_county", "Select a County" );
				//$( "select#select_county" ).locale_chosen( "select#select_district", "populate_district", "Select a District" );
				//$( "select#select_district" ).locale_chosen( "select#select_area", "populate_area", "Select a Area" );
				
				function getAge(dateString) {
				  var now = new Date();
				  var today = new Date(now.getYear(),now.getMonth(),now.getDate());
				
				  var yearNow = now.getYear();
				  var monthNow = now.getMonth();
				  var dateNow = now.getDate();
					// yyyy-mm-dd
				  var dob = new Date(dateString.substring(0,4), //yyyy
									 dateString.substring(5,7)-1, //mm               
									 dateString.substring(8,10)    //dd            
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
				
				  if (monthNow >= monthDob)
					var monthAge = monthNow - monthDob;
				  else {
					yearAge--;
					var monthAge = 12 + monthNow -monthDob;
				  }
				
				  if (dateNow >= dateDob)
					var dateAge = dateNow - dateDob;
				  else {
					monthAge--;
					var dateAge = 31 + dateNow - dateDob;
				
					if (monthAge < 0) {
					  monthAge = 11;
					  yearAge--;
					}
				  }
				
				  age = {
					  years: yearAge,
					  months: monthAge,
					  days: dateAge
					  };
				  
				  return age;
				 
				}
	
				age = getAge( '<?php echo $item->TglLahir ?>' );
					
				$("#UmurSaatInput").val( age.years );
				$("#UmurThn").val( age.years );
				$("#UmurBln").val( age.months );
				$("#UmurHr").val( age.days );
				
				
				$("#TglLahir").on("change blur", function(){
					dob = $( this ).val();
					
					age = getAge( dob );
					
					$("#UmurSaatInput").val( age.years );
					$("#UmurThn").val( age.years );
					$("#UmurBln").val( age.months );
					$("#UmurHr").val( age.days );
					
				});
				
				
			});
	})( jQuery );
//]]>
</script>