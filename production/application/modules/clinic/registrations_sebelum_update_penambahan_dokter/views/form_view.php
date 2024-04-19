<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("name" => "form_registrations") ); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang('registrations:edit_heading') ?></h3>
			</div>
            <div class="panel-body">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<h3 class="subtitle text-center"><?php echo lang('registrations:registration_number_label').': '.@$item->NoReg ?></h3>
						<hr/>  
					</div>
				</div>    			  
				<div class="row">
					<div class="col-md-6">
						<h3 class="subtitle"><?php echo lang('registrations:patient_label') ?></h3>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:reservation_number_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NoReservasi" name="f[NoReservasi]" value="<?php echo @$item->NoReservasi ?>" placeholder="" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:mr_number_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$patient->NRM ?>" placeholder="" class="form-control mask_nrm" maxlength="8">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:type_patient_label') ?></label>
							<div class="col-lg-9">
								<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control">
									<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
									<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:patient_name_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$patient->NamaPasien ?>" placeholder="" class="form-control patient" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:alias_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NamaAlias" name="p[NamaAlias]" value="<?php echo @$patient->NamaAlias ?>" placeholder="" class="form-control patient">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:identify_number_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NoIdentitas" name="p[NoIdentitas]" value="<?php echo @$patient->NoIdentitas ?>" placeholder="" class="form-control patient" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:religion_label') ?></label>
							<div class="col-lg-3">
								<select id="Agama" name="p[Agama]" class="form-control patient " disabled>
									<option value="BD" <?php echo @$patient->Agama == "BD"  ? "selected" : NULL  ?>>BUDHA</option>
									<option value="HD" <?php echo @$patient->Agama == "HD"  ? "selected" : NULL  ?>>HINDU</option>
									<option value="IS" <?php echo @$patient->Agama == "IS"  ? "selected" : NULL  ?>>ISLAM</option>
									<option value="KC" <?php echo @$patient->Agama == "KC"  ? "selected" : NULL  ?>>KONGHUCU</option>
									<option value="KR" <?php echo @$patient->Agama == "KR"  ? "selected" : NULL  ?>>KRISTEN</option>
									<option value="KT" <?php echo @$patient->Agama == "KT"  ? "selected" : NULL  ?>>KHATOLIK</option>
									<option value="LL" <?php echo @$patient->Agama == "LL"  ? "selected" : NULL  ?>>LAIN-LAIN</option>
								</select>
							</div>
							<label class="col-lg-3 control-label text-center"><?php echo lang('registrations:gender_label') ?></label>
							<div class="col-lg-3">
								<select id="JenisKelamin" name="p[JenisKelamin]" class="form-control patient " disabled>
									<option value="F" <?php echo @$patient->JenisKelamin == "F"  ? "selected" : NULL  ?>><?php echo lang('global:female')?></option>
									<option value="M" <?php echo @$patient->JenisKelamin == "M"  ? "selected" : NULL  ?>><?php echo lang('global:male')?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:dob_label') ?></label>
							<div class="col-lg-3">
								<input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$patient->TglLahir ?>" placeholder="" class="form-control datepicker patient" readonly>
							</div>
							<label class="col-lg-3 control-label text-center"><?php echo lang('registrations:year_label') ?></label>
							<div class="col-lg-3">
								<input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" placeholder="" class="form-control" readonly>
								<input type="hidden" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>" placeholder="" class="form-control" readonly>
								<input type="hidden" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:birth_place_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="TempatLahir" name="p[TempatLahir]" value="<?php echo @$patient->TempatLahir ?>" placeholder="" class="form-control patient" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:country_label') ?></label>
							<div class="col-lg-3">
								<select id="NationalityID" name="p[NationalityID]" class="form-control patient " disabled>
									<?php if(!empty($option_nationality)): foreach($option_nationality as $row):?>
									<option value="<?php echo $row->NationalityID ?>" <?php echo $row->NationalityID == @$patient->NationalityID ? "selected" : NULL  ?>><?php echo $row->Nationality ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
							<label class="col-lg-3 control-label text-center"><?php echo lang('registrations:phone_label') ?></label>
							<div class="col-lg-3">
								<input type="text" id="Phone" name="p[Phone]" value="<?php echo @$patient->Phone ?>" placeholder="" class="form-control patient" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:address_label') ?></label>
							<div class="col-lg-9">
								<textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" readonly><?php echo @$patient->Alamat ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:province_label') ?></label>
							<div class="col-lg-9">
								<select id="PropinsiID" name="p[PropinsiID]" class="form-control patient " disabled>
									<option value=""><?php echo lang('global:select-none')?></option>
									<?php if(!empty($option_province)): foreach($option_province as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$patient->PropinsiID ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:county_label') ?></label>
							<div class="col-lg-9">
								<select id="KabupatenID" name="p[KabupatenID]" class="form-control patient " disabled>
									<option value=""><?php echo lang('global:select-none')?></option>
									<?php if(!empty($option_county)): foreach($option_county as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$patient->KabupatenID ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:district_label') ?></label>
							<div class="col-lg-9">
								<select id="KecamatanID" name="p[KecamatanID]" class="form-control patient " disabled>
									<option value=""><?php echo lang('global:select-none')?></option>
									<?php if(!empty($option_district)): foreach($option_district as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$patient->KecamatanID ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:village_label') ?></label>
							<div class="col-lg-9">
								<select id="DesaID" name="p[DesaID]" class="form-control patient " disabled>
									<option value=""><?php echo lang('global:select-none')?></option>
									<?php if(!empty($option_village)): foreach($option_village as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$patient->DesaID ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<?php /*?><div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:area_label') ?></label>
							<div class="col-lg-9">
								<select id="BanjarID" name="p[BanjarID]" class="form-control patient " disabled>
									<option value=""><?php echo lang('global:select-none')?></option>
									<?php if(!empty($option_area)): foreach($option_area as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$patient->BanjarID ? "selected" : NULL  ?>><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div><?php */?>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:job_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="Pekerjaan" name="p[Pekerjaan]" value="<?php echo @$patient->Pekerjaan ?>" placeholder="" class="form-control patient" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Opsi</label>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="hidden" name="p[PasienVVIP]" value="0" >
									<input type="checkbox" id="PasienVVIP" name="p[PasienVVIP]" value="1" <?php echo @$patient->PasienVVIP == 1 ? "Checked" : NULL ?> class=" patient" disabled><label for="PasienVVIP">Pasien VVIP</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="hidden" name="p[PasienKTP]" value="0" >
									<input type="checkbox" id="PasienKTP" name="p[PasienKTP]" value="1" <?php echo @$item->PasienKTP == 1 ? "Checked" : NULL ?> class=" patient" disabled><label for="PasienKTP">Pasien KTP</label>
								</div>
							</div>
						</div>        
					</div>
					
					<div class="col-md-6">
						<!-- Penanggung Pasien -->
						<h3 class="subtitle"><?php echo lang('registrations:insurer_subtitle') ?></h3>
						<div class="form-group">
							<label class="col-lg-3 control-label">Opsi</label>
							<div class="col-lg-3">
								<div class="checkbox">
									<input type="hidden" name="f[PenanggungIsPasien]" value="0" >
									<input type="checkbox" id="PenanggungIsPasien" name="f[PenanggungIsPasien]" value="1" <?php echo @$item->PenanggungIsPasien == 1 ? "Checked" : NULL ?>><label for="PenanggungIsPasien" class="col-lg-3 control-label"><?php echo lang('registrations:is_patient_label') ?></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:mr_number_label') ?></label>
							<div class="col-lg-9">
								<div class="input-group">
									<input type="hidden" id="PenanggungNoKartu" class="insurer" name="f[PenanggungNoKartu]" value="<?php echo @$item->PenanggungNoKartu ?>">
									<input type="text" id="PenanggungNRM" name="f[PenanggungNRM]" value="<?php echo @$item->PenanggungNRM ?>" placeholder="" class="form-control insurer" readonly>
									<span class="input-group-btn">
										<a href="<?php echo $lookup_insurer ?>" id="lookup_insurer" data-toggle="lookup-ajax-modal" class="btn btn-default disabled" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_insurer" data-target="insurer" class="btn btn-default disabled" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:name_label') ?> </label>
							<div class="col-lg-9">
								<input type="text" id="PenanggungNama" name="f[PenanggungNama]" value="<?php echo @$item->PenanggungNama ?>" placeholder="" class="form-control insurer" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:address_label') ?></label>
							<div class="col-lg-9">
								<textarea id="PenanggungAlamat" name="f[PenanggungAlamat]" placeholder="" class="form-control insurer"><?php echo @$item->PenanggungAlamat ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('id_type:ktp') ?></label>
							<div class="col-lg-9">
								<input type="text" id="PenanggungNoKTP" name="f[PenanggungNoKTP]" value="<?php echo @$item->PenanggungNoKTP ?>" placeholder="" class="form-control insurer" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:phone_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="PenanggungTelp" name="f[PenanggungTelp]" value="<?php echo @$item->PenanggungTelp ?>" placeholder="" class="form-control insurer" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:relationship_label') ?></label>
							<div class="col-lg-9">
								<select id="PenanggungHubungan" name="f[PenanggungHubungan]" class="form-control insurer ">
									<option value=""></option>
									<option value="Pasien Sendiri" <?php echo @$item->PenanggungHubungan == "Pasien Sendiri"  ? "selected" : NULL  ?>>Pasien Sendiri</option>
									<option value="Orang Tua" <?php echo @$item->PenanggungHubungan == "Orang Tua"  ? "selected" : NULL  ?>>Orang Tua	</option>
									<option value="Suami/Istri" <?php echo @$item->PenanggungHubungan == "Suami/Istri"  ? "selected" : NULL  ?>>Suami/Istri</option>
									<option value="Anak" <?php echo @$item->PenanggungHubungan == "Anak"  ? "selected" : NULL  ?>>Anak</option>
									<option value="Saudara Kandung" <?php echo @$item->PenanggungHubungan == "Saudara Kandung"  ? "selected" : NULL  ?>>Saudara Kandung</option>
									<option value="Teman" <?php echo @$item->PenanggungHubungan == "Teman"  ? "selected" : NULL  ?>>Teman</option>
									<option value="Lainnya" <?php echo @$item->PenanggungHubungan == "Lainnya"  ? "selected" : NULL  ?>>Lainnya</option>
								</select>
							</div>
						</div>
						<div class="form-group"><hr/></div>
						<!-- Kerja Sama-->
						<h3 class="subtitle"><?php echo lang('registrations:cooperation_subtitle') ?></h3>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:company_code_label') ?></label>
							<div class="col-lg-9">
								<div class="input-group">
									<input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>" class="cooperation">
									<input type="hidden" id="KdKelas" name="f[KdKelas]" value="<?php echo @$item->KdKelas ?>"  class="cooperation">
									<input type="text" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->KodePerusahaan ?>" placeholder="" class="form-control cooperation">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_cooperation ?>" id="lookup_cooperation" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_cooperation" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:company_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="Nama_Customer"  value="<?php echo @$cooperation->Nama_Customer ?>" placeholder="" class="form-control cooperation" disabled="disabled">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Opsi</label>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="hidden" name="p[AnggotaBaru]" value="0" >
									<input type="checkbox" id="AnggotaBaru" name="p[AnggotaBaru]" value="1" class="" ><label for="AnggotaBaru">Anggota Baru</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('registrations:card_number_label') ?></label>
							<div class="col-md-6">
								<div class="input-group">
									<input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control cooperation cooperation_card" >
									<span class="input-group-btn">
										<a href="<?php echo $lookup_patient_cooperation_card ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_cooperation_card" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
							<label class="col-lg-2 control-label text-center"><?php echo lang('registrations:group_label') ?></label>
							<div class="col-lg-1">
								<select id="Klp" name="p[Klp]" class="form-control ">
									<option value="E" <?php echo @$patient->Klp == "E"  ? "selected" : NULL  ?>>E</option>
									<option value="S" <?php echo @$patient->Klp == "S"  ? "selected" : NULL  ?>>S</option>
									<option value="C1" <?php echo @$patient->Klp == "C1"  ? "selected" : NULL  ?>>C1</option>
									<option value="C2" <?php echo @$patient->Klp == "C2"  ? "selected" : NULL  ?>>C2</option>
									<option value="C3" <?php echo @$patient->Klp == "C3"  ? "selected" : NULL  ?>>C3</option>
									<option value="C4" <?php echo @$patient->Klp == "C4"  ? "selected" : NULL  ?>>C4</option>
									<option value="C5" <?php echo @$patient->Klp == "C5"  ? "selected" : NULL  ?>>C5</option>
									<option value="C5" <?php echo @$patient->Klp == "C5"  ? "selected" : NULL  ?>>C6</option>
								</select>
							</div>
						</div>
						<div class="form-group"><hr/></div>
						<!-- Pertanggungan Kedua -->
						<h3 class="subtitle"><?php echo lang('registrations:second_insurer_subtitle') ?></h3>
						<div class="form-group">
							<label class="col-lg-3 control-label">Opsi</label>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="hidden" name="f[PertanggunganKeduaIKS]" value="" >
									<input type="checkbox" id="PertanggunganKeduaIKS" class="second_insurer" name="f[PertanggunganKeduaIKS]" value="1" <?php echo @$item->PertanggunganKeduaIKS == 1 ? "Checked" : NULL ?>><label for="PertanggunganKeduaIKS">IKS</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:company_code_label') ?></label>
							<div class="col-lg-9">
								<div class="input-group">
									<input type="hidden" id="PertanggunganKeduaCustomerKerjasamaID" class="second_insurer" name="f[PertanggunganKeduaCustomerKerjasamaID]" value="<?php echo  @$item->PertanggunganKeduaCustomerKerjasamaID ?>" >
									<input type="hidden" id="PertanggunganKeduaCompanyID" class="second_insurer" name="f[PertanggunganKeduaCompanyID]" value="<?php echo @$item->PertanggunganKeduaCompanyID ?>" >
									<input type="text" id="PertanggunganKeduaCompanyNama" value="<?php echo @$second_insurer->Nama_Customer ?>" placeholder="" class="form-control second_insurer" >
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_second_insurer ?>" data-toggle="lookup-ajax-modal" class="btn btn-default second_insurer disabled" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_second_insurer" class="btn btn-default second_insurer disabled" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('registrations:card_number_label') ?></label>
							<div class="col-md-9">
								<div class="input-group">
									<input type="text" id="PertanggunganKeduaNoKartu" name="f[PertanggunganKeduaNoKartu]" value="<?php echo @$item->PertanggunganKeduaNoKartu ?>" placeholder="" class="form-control second_insurer second_insurer_card">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_patient_second_insurer_card ?>" data-toggle="lookup-ajax-modal" class="btn btn-default second_insurer disabled" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_second_insurer_card" class="btn btn-default second_insurer disabled" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6">
						<!-- Asal Pasien -->
						<h3 class="subtitle"><?php echo lang('registrations:patient_origin_subtitle') ?></h3>
						<div class="form-group">
							<label class="col-lg-3 control-label">Opsi</label>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="hidden" name="f[Rujukan]" value="0" >
									<input type="checkbox" id="Rujukan" name="f[Rujukan]" value="1" <?php echo @$item->Rujukan == 1 ? "Checked" : NULL ?>><label for="Rujukan">Rujukan</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="hidden" name="f[Pribadi]" value="0" >
									<input type="checkbox" id="Pribadi" name="f[Pribadi]" value="1" <?php echo @$item->Pribadi == 1 ? "Checked" : NULL ?>><label for="Pribadi">Pribadi</label>
								</div>
							</div>
						</div>        
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:doctor_sender_label') ?></label>
							<div class="col-lg-9">
								<div class="input-group">
									<input type="hidden" id="DokterKirimID" name="f[DokterKirimID]" value="<?php echo @$item->DokterKirimID ?>" class="doctor_sender">
									<input type="text" id="doctor_origin" value="<?php echo @$doctor->Nama_Supplier ?>" placeholder="" class="form-control doctor_sender" disabled="disabled">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_supplier ?>/doctor" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_doctor_sender" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:vendor_sender_label') ?></label>
							<div class="col-lg-9">
								<div class="input-group">
									<input type="hidden" id="VendorID_Referensi" name="f[VendorID_Referensi]" value="<?php echo @$item->VendorID_Referensi ?>" class="vendor_sender">
									<input type="text" id="vendor_origin" value="<?php echo @$vendor->Nama_Supplier ?>" placeholder="" class="form-control vendor_sender" disabled="disabled">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_supplier ?>/vendor" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_vendor_sender" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('registrations:memo_label') ?></label>
							<div class="col-lg-9">
								<textarea id="Keterangan" name="f[Keterangan]" placeholder="" class="form-control"><?php echo @$item->Keterangan ?></textarea>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<!-- Section Tujuan-->
						<h3 class="subtitle"><?php echo lang('registrations:destionation_subtitle') ?></h3>
						<div class="form-group">
							<div class="col-md-6 ">
								<a href="<?php echo @$lookup_section ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary"><i class="fa fa-search"></i> Cari Section</a>
							</div>
							<div class="col-md-6 text-right">
								<a href="<?php echo @$lookup_doctor_schedule ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo lang('buttons:view_doctor_schedule')?></a>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="dt_registration_section" class="table table-sm table-bordered" width="100%">
										<thead>
											<tr>
												<th></th>
												<th><?php echo lang("registrations:section_label") ?></th>
												<th><?php echo lang("registrations:doctor_label") ?></th>                        
												<th><?php echo lang("registrations:time_label") ?></th>                        
												<th><?php echo lang("registrations:no_label") ?></th>                        
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-12 text-right">
						<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
						<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
						<button type="reset" id="print" class="btn btn-success">Cetak Label</button>
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
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						switch( this.index() ){
							case 0:
								
								try{
									if( confirm( "<?php echo lang('registrations:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
								
							break;
							
							case 2:
								try{
									index = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo base_url("registrations/lookup_doctor_section") ?>/"+ index)
								} catch(ex){}
							break;

							
							case 3:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.WaktuID ? data.WaktuID : ''
								_input.load( "<?php echo base_url("registrations/time_dropdown") ?>/" + _value, function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
								/*_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data );
										} catch(ex){}
									});*/
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.WaktuID = _selected.waktuid || 0;
											data.Keterangan = _selected.keterangan || 'NONE';
											
											_datatable.row( row ).data( data );
											_datatable_actions.get_queue( row, data );
										} catch(ex){console.log(ex);}
									});
							break;
							
						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				get_queue: function( scope, params ){
					
						
						$.post('<?php echo @$get_queue_link ?>', params, function( response, status, xhr ){
							
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							params.NoUrut =  response.queue;
							_datatable.row( scope ).data( params );
							
						});
						
					},
				calculate_balance: function(params, fn, scope){
										
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
							}
						).draw(false);
						
						
					}
					
					
			};
		
		$.fn.extend({
				dt_registration_section: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: false,
								responsive: true,
								<?php if (!empty($is_edit)):?>
								data: <?php print_r(json_encode($section_destination, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								<?php /*?><?php if (isset($is_edit)) : ?>
								ajax: {
										url: "<?php echo $populate_url ?>",
										type: "POST",
										data: function( params ){},
										dataSrc: function( response ){
												
												_datatable_populate = response.data || [];
												return _datatable_populate;
											}
									},
								<?php endif; ?><?php */?>
								columns: [
										{ 
											data: "SectionID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "SectionName", 
											className: "", 
										},
										{ data: "Nama_Supplier", className: "text-left" },
										{ data: "Keterangan", className: "text-center", },
										{ data: "NoUrut", className: "text-center", },
									
										
									],
								columnDefs  : [
										{
											"targets": ["DokterID","WaktuID","SectionID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback : function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								fnRowCallback : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
										var index = iDisplayIndexFull + 1;
										$('td:eq(0)',nRow).html(index);
										return nRow;					
									},
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												//_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												/*if( confirm( "<?php echo lang('registrations:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}*/
											})
									}
							} );
							
						$( "#dt_registration_section_length select, #dt_registration_section_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_registration_section" ).dt_registration_section();
			
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
	
				
			});

	})( jQuery );
//]]>
</script>