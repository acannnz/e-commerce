<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( @$form_url, array("name" => "form_registrations") ); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{heading}}</h3>
			</div>
            <div class="panel-body">
				<ul id="tab-poly" class="nav nav-tabs nav-justified">
					<li class="active"><a href="#poly-tab1" data-toggle="tab"><b><i class="fa fa-wheelchair"></i> Data Pasien</b></a></li>
					<li><a href="#poly-tab2" data-toggle="tab"><b><i class="fa fa-users"></i> Data Penanggung</b></a></li>
					<li><a href="#poly-tab3" data-toggle="tab"><b><i class="fa fa-heartbeat"></i> Tanda Vital</b></a></li>
					<li><a href="#poly-tab4" data-toggle="tab"><b><i class="fa fa-stethoscope"></i> Data Kunjungan</b></a></li>
				</ul>
				<div class="tab-content">
					<div id="poly-tab1" class="tab-pane tab-pane-padding active">
						<div class="page-subtitle">
							<i class="fa fa-book pull-left text-info"></i>
							<h3 class="text-info"><?php echo 'Registration Information' ?></h3>
							<p><?php echo 'Informasi Reservasi Pasien'?></p>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo lang('registrations:registration_number_label') ?></label>
									<input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" placeholder="" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6">								
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:reservation_number_label') ?></label>
									<div class="input-group">
										<input type="text" id="NoReservasi" name="f[NoReservasi]" value="<?php echo @$item->NoReservasi ?>" placeholder="" class="form-control">
										<span class="input-group-btn">
											<a href="<?php echo @$lookup_reservation ?>" id="lookup_patient"  data-toggle="lookup-ajax-modal" class="btn btn-default lookup_patient" ><i class="fa fa-search"></i></a>
											<a href="javascript:;" id="clear_reservaton" class="btn btn-default" ><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="page-subtitle margin-top-30">
							<i class="fa fa-user pull-left text-info"></i>
							<h3 class="text-info"><?php echo 'General Information' ?></h3>
							<p><?php echo 'Informasi umum tentang Pasien'?></p>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo lang('registrations:mr_number_label') ?></label>
									<label class="control-label">
										<div class="checkbox" style="margin:0">
											<input type="checkbox" id="PasienBaru" name="f[PasienBaru]" value="1" <?php echo @$item->PasienBaru ?>>
											<label for="PasienBaru"><?php echo lang('registrations:new_patient_label') ?></label>
										</div>
									</label>
									<div class="input-group">
										<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$patient->NRM ?>" placeholder="" class="form-control mask_nrm patient" maxlength="8">
										<span class="input-group-btn">
											<a href="<?php echo @$lookup_patients ?>" id="lookup_patients" data-toggle="lookup-ajax-modal" class="btn btn-default lookup_patient" ><i class="fa fa-search"></i> <sup>(f3)</sup></a>
											<a href="javascript:;" id="clear_patient" class="btn btn-default" ><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
							</div>
							<?php /*?><div class="col-md-6">
								<label class="col-lg-3 control-label"><?php echo 'NRM Lama' ?></label>
								<input type="text" id="NRMLama" name="p[NRMLama]" value="<?php echo @$patient->NRMLama ?>" placeholder="" class="form-control">
							</div><?php */?>							
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:patient_name_label') ?></label>
									<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$patient->NamaPasien ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:alias_label') ?></label>
									<input type="text" id="NamaAlias" name="p[NamaAlias]" value="<?php echo @$patient->NamaAlias ?>" placeholder="" class="form-control patient">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:gender_label') ?></label>
									<select id="JenisKelamin" name="p[JenisKelamin]" class="form-control patient" >
										<option value="">-- Pilih --</option>
										<option value="M" <?php echo @$patient->JenisKelamin == "M"  ? "selected" : NULL  ?>><?php echo lang('global:male')?></option>
										<option value="F" <?php echo @$patient->JenisKelamin == "F"  ? "selected" : NULL  ?>><?php echo lang('global:female')?></option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:religion_label') ?></label>
									<select id="Agama" name="p[Agama]" class="form-control patient " >
										<option value="">-- Pilih --</option>
										<option value="BD" <?php echo @$patient->Agama == "BD"  ? "selected" : NULL  ?>>BUDHA</option>
										<option value="HD" <?php echo @$patient->Agama == "HD"  ? "selected" : NULL  ?>>HINDU</option>
										<option value="IS" <?php echo @$patient->Agama == "IS"  ? "selected" : NULL  ?>>ISLAM</option>
										<option value="KC" <?php echo @$patient->Agama == "KC"  ? "selected" : NULL  ?>>KONGHUCU</option>
										<option value="KR" <?php echo @$patient->Agama == "KR"  ? "selected" : NULL  ?>>KRISTEN</option>
										<option value="KT" <?php echo @$patient->Agama == "KT"  ? "selected" : NULL  ?>>KHATOLIK</option>
										<option value="LL" <?php echo @$patient->Agama == "LL"  ? "selected" : NULL  ?>>LAIN-LAIN</option>
									</select>
								</div>
							</div>		
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo 'Tanggal Lahir' ?></label>
									<div class="input-group">
										<span class="input-group-btn">
											<a href="javascript:;" id="clear_patient" class="btn btn-default" ><i class="fa fa-calendar"></i></a>
										</span>
										<input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$patient->TglLahir ?>" placeholder="" class="form-control datepicker patient">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:age_label') ?></label>
									<div class="input-group">
										<input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" class="form-control text-right patient" readonly>
										<span class="input-group-addon" style="padding:6px 6px !important"><?php echo lang('registrations:year_label') ?></span>
										<input type="text" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>" class="form-control text-right patient" readonly>
										<span class="input-group-addon" style="padding:6px 6px !important"><?php echo lang('registrations:month_label') ?></span>
										<input type="text" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>" class="form-control text-right patient" readonly>
										<span class="input-group-addon" style="padding:6px 6px !important"><?php echo lang('registrations:day_label') ?></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:identify_number_label') ?></label>
									<input type="text" id="NoIdentitas" name="p[NoIdentitas]" value="<?php echo @$patient->NoIdentitas ?>" placeholder="" class="form-control patient" >
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:birth_place_label') ?></label>
									<input type="text" id="TempatLahir" name="p[TempatLahir]" value="<?php echo @$patient->TempatLahir ?>" placeholder="" class="form-control patient" >
								</div>
							</div>	
							<div class="col-md-3">								
								<div class="form-group">
									<label class="control-label">Opsi</label>
									<div class="row">
										<div class="col-md-6">
											<div class="checkbox">
												<input type="hidden" name="p[PasienKTP]" value="0" >
												<input type="checkbox" id="PasienKTP" name="p[PasienKTP]" value="1" <?php echo @$item->PasienKTP == 1 ? "Checked" : NULL ?> class=" patient" ><label for="PasienKTP">Pasien KTP</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="checkbox">
												<input type="hidden" name="p[PasienVVIP]" value="0" >
												<input type="checkbox" id="PasienVVIP" name="p[PasienVVIP]" value="1" <?php echo @$patient->PasienVVIP == 1 ? "Checked" : NULL ?> class=" patient" ><label for="PasienVVIP">Pasien VVIP</label>
											</div>
										</div>
									</div>
								</div>        
							</div>			
						</div>
						
						<div class="page-subtitle margin-top-30">
							<i class="fa fa-map-marker pull-left text-info"></i>
							<h3 class="text-info"><?php echo lang('registrations:address_label') ?> Information</h3>
							<p><?php echo 'Silakan lengkapi Informasi Alamat jika diperlukan'?></p>
						</div>
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:address_label') ?></label>
									<input type="text" id="Alamat" name="p[Alamat]" value="<?php echo @$patient->Alamat ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:country_label') ?></label>
									<select id="NationalityID" name="p[NationalityID]" class="form-control patient">
										<?php if(!empty($option_nationality)): foreach($option_nationality as $row):?>
										<option value="<?php echo $row->NationalityID ?>" <?php echo $row->NationalityID == @$patient->NationalityID ? "selected" : NULL  ?>><?php echo $row->Nationality ?></option>
										<?php endforeach; endif;?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:province_label') ?></label>
									<?php echo form_dropdown('p[PropinsiID]', @$list_provinsi, set_value('p[PropinsiID]', @$regional->ProvinsiId, TRUE), [
										'id' => 'Provinsi',
										'placeholder' => '',
										'class' => 'form-control patient regional'
									]); ?>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:county_label') ?></label>
									<div class="input-group">
										<input type="text" id="Kabupaten" name="p[KabupatenID]" value="<?php echo @$regional->KabupatenNama ?>" placeholder="KABUPATEN" class="form-control patient regional" disabled>
										<span class="input-group-btn">
											<a href="<?php echo @$regional_lookup ?>" id="regional_looukp" data-toggle="lookup-ajax-modal" class="btn btn-default"><i class="fa fa-search"></i></a>
											<a href="javascript:;" id="clear_regional" class="btn btn-default"><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:district_label') ?></label>
									<input id="Kecamatan" name="p[KecamatanID]" class="form-control patient regional" value="<?= @$regional->KecamatanNama ?>" placeholder="KECAMATAN" disabled></input>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:village_label') ?></label>
									<input id="Desa" name="p[DesaID]" class="form-control patient regional" value="<?= @$regional->DesaNama ?>" placeholder="DESA" disabled></input>
									<input id="KodeRegional" name="p[KodeRegional]" class="form-control patient regional hidden"></input>
								</div>
							</div>
						</div>
						<div class="page-subtitle margin-top-30">
							<i class="fa fa-phone pull-left text-info"></i>
							<h3 class="text-info"><?php echo lang('registrations:contact_label') ?> Information</h3>
							<p><?php echo 'Silakan lengkapi Informasi Kontak jika diperlukan'?></p>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:phone_label') ?></label>
									<input type="text" id="Phone" name="p[Phone]" value="<?php echo @$patient->Phone ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:email_label') ?></label>
									<input type="text" id="Email" name="p[Email]" value="<?php echo @$patient->Email ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:job_label') ?></label>
									<input type="text" id="Pekerjaan" name="p[Pekerjaan]" value="<?php echo @$patient->Pekerjaan ?>" placeholder="" class="form-control patient">
								</div>
							</div>
						</div>
						<div class="row">
							<?php /*?><div class="form-group">
								<label class="col-lg-3 control-label"><?php echo lang('registrations:area_label') ?></label>
								<div class="col-lg-9">
									<select id="BanjarID" name="p[BanjarID]" class="form-control patient " >
										<option value=""><?php echo lang('global:select-none')?></option>
										<?php if(!empty($option_area)): foreach($option_area as $key => $val):?>
										<option value="<?php echo $key ?>" <?php echo $key == @$patient->BanjarID ? "selected" : NULL  ?>><?php echo $val ?></option>
										<?php endforeach; endif;?>
									</select>
								</div>
							</div><?php */?>
						</div>
						<hr/>
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<a href="<?php echo base_url('registrations')?>" class="btn btn-default btn-block"><i class="fa fa-arrow-left"></i> <?php echo lang( 'buttons:back' ) ?></a>
								</div>
								<div class="col-md-4">
									<?php if(@$is_edit):?>
										<a href="<?php echo @$cancel_link ?>" data-toggle="form-ajax-modal" class="btn btn-danger btn-block" <?= (@$item->Batal == 1) ? "disabled":null ?>><i class="fa fa-trash"></i> <?php echo lang( 'buttons:cancel' ) ?></a>
									<?php endif; ?>
								</div>
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-primary btn-block btn-next"><?php echo lang( 'buttons:next' ) ?> <i class="fa fa-arrow-right"></i></a>
								</div>						
							</div>
						</div>
					</div>
					<div id="poly-tab2" class="tab-pane tab-pane-padding">	
						<!-- Penanggung Pasien -->
						<div class="page-subtitle">
							<i class="fa fa-users pull-left text-info"></i>
							<h3 class="text-info"><?php echo lang('registrations:insurer_subtitle') ?> Information</h3>
							<p><?php echo 'Silakan lengkapi Informasi Penanggung jika diperlukan'?></p>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">	
									<label class="col-md-3 control-label"><?php echo lang('registrations:mr_number_label') ?></label>
									<label class="control-label">
										<div class="checkbox" style="margin:0">
											<input type="hidden" name="f[PenanggungIsPasien]" value="0" >
											<input type="checkbox" id="PenanggungIsPasien" name="f[PenanggungIsPasien]" value="1" <?php echo @$item->PenanggungIsPasien == 1 ? "Checked" : NULL ?>>
											<label for="PenanggungIsPasien"><?php echo lang('registrations:is_patient_label') ?></label>
										</div>
									</label>
									<div class="input-group">
										<input type="hidden" id="PenanggungNoKartu" class="insurer" name="f[PenanggungNoKartu]" value="<?php echo @$item->PenanggungNoKartu ?>">
										<input type="text" id="PenanggungNRM" name="f[PenanggungNRM]" value="<?php echo @$item->PenanggungNRM ?>" placeholder="" class="form-control insurer" readonly>
										<span class="input-group-btn">
											<a href="<?php echo @$lookup_insurer ?>" id="lookup_insurer" data-toggle="lookup-ajax-modal" class="btn btn-default disabled" ><i class="fa fa-search"></i></a>
											<a href="javascript:;" id="clear_insurer" data-target="insurer" class="btn btn-default disabled" ><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo lang('registrations:name_label') ?> </label>
									<input type="text" id="PenanggungNama" name="f[PenanggungNama]" value="<?php echo @$item->PenanggungNama ?>" placeholder="" class="form-control insurer" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:address_label') ?></label>
									<input type="text" id="PenanggungAlamat" name="f[PenanggungAlamat]" value="<?php echo @$item->PenanggungAlamat ?>" placeholder="" class="form-control insurer" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('id_type:ktp') ?></label>
									<input type="text" id="PenanggungNoKTP" name="f[PenanggungNoKTP]" value="<?php echo @$item->PenanggungNoKTP ?>" placeholder="" class="form-control insurer" >
								</div>
							</div>								
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:phone_label') ?></label>
									<input type="text" id="PenanggungTelp" name="f[PenanggungTelp]" value="<?php echo @$item->PenanggungTelp ?>" placeholder="" class="form-control insurer" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:relationship_label') ?></label>
									<select id="PenanggungHubungan" name="f[PenanggungHubungan]" class="form-control insurer ">
										<option value="">-- Pilih --</option>
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
						</div>
						<hr/>
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-default btn-block btn-previous"><i class="fa fa-arrow-left"></i> <?php echo lang( 'buttons:back' ) ?></a>
								</div>
								<div class="col-md-4">
									<?php if(@$is_edit):?>
										<a href="<?php echo @$cancel_link ?>" data-toggle="form-ajax-modal" class="btn btn-danger btn-block" <?= (@$item->Batal == 1) ? "disabled":null ?>><i class="fa fa-trash"></i> <?php echo lang( 'buttons:cancel' ) ?></a>
									<?php endif; ?>
								</div>
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-primary btn-block btn-next"><?php echo lang( 'buttons:next' ) ?> <i class="fa fa-arrow-right"></i></a>
								</div>						
							</div>
						</div>
					</div>
					
					<div id="poly-tab3" class="tab-pane tab-pane-padding">
						<div class="page-subtitle ">
							<i class="fa fa-heartbeat pull-left text-info"></i>
							<h3 class="text-info"><?php echo lang('registrations:vital_sign') ?> Tanda Vital Information</h3>
							<p><?php echo 'Silakan lengkapi Informasi Tanda Vital'?></p>
						</div>
						<div class="row">
							<div class="col-md-3">                        
								<div class="form-group">
									<label>Tinggi Badan</label>
									<div class="form-group input-group">
										<input type="number" id="vitalHeight" name="v[Height]" value="<?php echo $vital->Height ?>" min="0" placeholder="" class="form-control">
										<span class="input-group-addon help-block">CM</span>
									</div>
								</div>
							</div>
							<div class="col-md-3">                        
								<div class="form-group">
									<label>Berat Badan</label>
									<div class="form-group input-group">
										<input type="number" id="vitalWeight" name="v[Weight]" value="<?php echo $vital->Weight ?>" min="0" placeholder="placeholder" class="form-control">
										<span class="input-group-addon help-block">KG</span>
									</div>
								</div>
							</div>
							<div class="col-md-3">                        
								<div class="form-group">
									<label>Suhu Tubuh</label>
									<div class="form-group input-group">
										<input type="number" id="vitalTemperature" name="v[Temperature]" value="<?php echo $vital->Temperature ?>" min="0" placeholder="" class="form-control">
										<span class="input-group-addon help-block">C</span>
									</div>
								</div>
							</div>
							<div class="col-md-3">                        
								<div class="form-group">
									<label>Tekanan Darah</label>
									<div class="form-group input-group">
										<input type="number" id="vitalSystolic" name="v[Systolic]" value="<?php echo $vital->Systolic ?>" placeholder="" class="form-control" />
										<span class="input-group-addon">/</span>
										<input type="number" id="vitalDiastolic" name="v[Diastolic]" value="<?php echo $vital->Diastolic ?>" placeholder="" class="form-control" />
										<span class="input-group-addon help-block">MM/HG</span>
									</div>
								</div>
							</div>
							<div class="col-md-3">                        
								<div class="form-group">
									<label>Detak Jantung per Menit</label>
									<div class="form-group input-group">
										<input type="number" id="vitalHeartRate" name="v[HeartRate]" value="<?php echo $vital->HeartRate ?>" placeholder="" class="form-control">
										<span class="input-group-addon help-block">BPM</span>
									</div>
								</div>
							</div>
							<div class="col-md-3">                        
								<div class="form-group">
									<label>Frekuensi Pernapasan</label>
									<div class="form-group input-group">
										<input type="number" id="vitalRespiratoryRate" name="v[RespiratoryRate]" value="<?php echo $vital->RespiratoryRate ?>" placeholder="" class="form-control">
										<span class="input-group-addon help-block">RPM</span>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">                        
									<label>Saturasi Oksigen (SATS)</label>
									<div class="form-group input-group">
										<input type="number" id="vitalOxygenSaturation" name="v[OxygenSaturation]" value="<?php echo $vital->OxygenSaturation ?>" placeholder="" class="form-control">
										<span class="input-group-addon help-block"> % </span>
									</div>
								</div>
							</div>
							<div class="col-md-3">                        
								<div class="form-group">
									<label>Skala Nyeri</label>
									<div class="form-group input-group">
										<select id="vitalPain" name="v[Pain]" class="form-control">
											<?php $i=0; while($i <= 10):?>
											<option value="<?php echo $i ?>" <?php echo ($vital->Pain == $i) ? 'selected' : NULL ?>><?php echo $i ?></option>
											<?php $i++; endwhile;?>
										</select>
										<span class="input-group-addon help-block">0-10</span>
									</div>
								</div>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-default btn-block btn-previous"><i class="fa fa-arrow-left"></i> <?php echo lang( 'buttons:back' ) ?></a>
								</div>
								<div class="col-md-4">
									<?php if(@$is_edit):?>
										<a href="<?php echo @$cancel_link ?>" data-toggle="form-ajax-modal" class="btn btn-danger btn-block" <?= (@$item->Batal == 1) ? "disabled":null ?>><i class="fa fa-trash"></i> <?php echo lang( 'buttons:cancel' ) ?></a>
									<?php endif; ?>
								</div>
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-primary btn-block btn-next"><?php echo lang( 'buttons:next' ) ?> <i class="fa fa-arrow-right"></i></a>
								</div>						
							</div>
						</div>
					</div>
					
					<div id="poly-tab4" class="tab-pane tab-pane-padding">
						<div class="row">
							<div class="col-md-6">
								<!-- Section Tujuan-->
								<div class="page-subtitle ">
									<i class="fa fa-stethoscope pull-left text-info"></i>
									<h3 class="text-info"><?php echo lang('registrations:destionation_subtitle') ?> Information</h3>
									<p><?php echo 'Silakan lengkapi Informasi Section Tujuan'?></p>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label"><?php echo lang('registrations:type_patient_label') ?></label>
												<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control">
													<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
													<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
													<?php endforeach; endif;?>
												</select>
											</div>
										</div>	
									</div>
									<div class="row">
										<div class="col-md-6">
											<label class="control-label"><?php echo "Tipe Pelayanan" ?></label>
											<select id="TipePelayanan" name="TipePelayanan" class="form-control">
												<option value="RawatJalan" <?php echo @$item->RawatJalan == 1  ? "selected" : NULL  ?>>Rawat Jalan</option>
												<!-- <option value="RawatInap" <?php echo @$item->RawatInap == 1  ? "selected" : NULL  ?>>Rawat Inap</option> -->
											</select>
										</div>								
										<div class="col-md-6">
											<?php echo form_label('Kelas *', 'KdKelas', ['class' => 'control-label']) ?>
											<?php echo form_dropdown('f[KdKelas]', @$option_class, set_value('f[KdKelas]', @$item->KdKelas, TRUE), [
												'id' => 'KdKelas', 
												'placeholder' => '',
												'required' => 'required', 
												'class' => 'form-control'
											]); ?>
										</div>
									</div>	
								</div>
								<div class="form-group">
									<span class="btn-group btn-group-justified">
										<a href="<?php echo @$lookup_section ?>" id="find_section" data-toggle="lookup-ajax-modal" class="btn btn-primary"><i class="fa fa-search"></i> Cari Section <sup>(f2)</sup></a>
										<a href="<?php echo @$lookup_doctor_schedule ?>" data-toggle="lookup-ajax-modal" class="btn btn-info"><i class="fa fa-clock-o"></i> <?php echo lang('buttons:doctor_schedule')?></a>
									</span>
								</div>
								<div class="form-group">
									<div class="table-responsive">
										<table id="dt_registration_section" class="table table-sm table-bordered" width="100%">
											<thead>
												<tr>
													<th></th>
													<th><?php echo lang("registrations:section_label") ?></th>               
													<th><?php echo lang("registrations:time_label") ?></th>                        
													<th><?php echo lang("registrations:no_label") ?></th>                        
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
								<div id="room_selection" class="form-group">
									<div class="row">
										<div class="col-md-12">
											<label class="control-label"><?php echo lang('registrations:room_label') ?></label>
										</div>
										<div class="col-md-6">								
											<div class="input-group">
												<input type="hidden" id="NoBed" name="f[NoBed]" value="<?php echo @$item->NoBed ?>" placeholder="" class="form-control room">
												<input type="hidden" id="NoKamar" name="f[NoKamar]" value="<?php echo @$item->NoKamar ?>" placeholder="" class="form-control room">
												<input type="text" id="Kamar" name="Kamar" value="<?php echo @$item->NoKamar ?>" placeholder="" class="form-control room">
												<span class="input-group-btn">
													<a href="<?php echo @$lookup_room ?>" data-toggle="lookup-ajax-modal" class="btn btn-default room" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" id="clear_room" class="btn btn-default room" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
										<div class="col-md-6">
											<a href="javascript:;" class="btn btn-info btn-block"><b><i class="fa fa-bed"></i> Update Status Kamar</b></a>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<!-- Kerja Sama-->
								<div class="page-subtitle">
									<i class="fa fa-handshake-o pull-left text-info"></i>
									<h3 class="text-info"><?php echo lang('registrations:cooperation_subtitle') ?> Information</h3>
									<p><?php echo 'Silakan lengkapi Informasi Perusahaan Kerjasama jika diperlukan'?></p>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label class="control-label"><?php echo lang('registrations:company_label') ?></label>
											<div class="input-group">
												<input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>" class="cooperation">
												<input type="hidden" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->KodePerusahaan ?>" placeholder="" class="cooperation">
												<input type="text" id="Nama_Customer"  value="<?php echo @$cooperation->Nama_Customer ?>" placeholder="" class="form-control cooperation" disabled="disabled">
												<span class="input-group-btn">
													<a href="<?php echo @$lookup_cooperation ?>" id="lookup_cooperation" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" id="clear_cooperation" class="btn btn-default" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
										<div class="col-md-6">
											<label class="col-md-3 control-label"><?php echo lang('registrations:card_number_label') ?></label>
											<label class="control-label">
												<div class="checkbox" style="margin:0">
													<input type="hidden" name="p[AnggotaBaru]" value="0" >
													<input type="checkbox" id="AnggotaBaru" name="p[AnggotaBaru]" value="1" class="" <?php echo (@$patient->AnggotaBaru == 1) ? 'checked':NULL ?>><label for="AnggotaBaru">Anggota Baru</label>
												</div>
											</label>
											<?php if(config_item('bpjs_bridging') == 'TRUE')
													echo modules::run('bpjs/member/form_mapping', @$item->NoAnggota);
											?>			
											<div id="memberNumberIKSArea" class="input-group">
												<input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control cooperation cooperation_card" >
												<span class="input-group-btn">
													<a href="<?php echo $lookup_patient_cooperation_card ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" id="clear_cooperation_card" class="btn btn-default" ><i class="fa fa-times"></i></a>
												</span>
											</div>
											<?php /*?><label class="col-lg-2 control-label text-center"><?php echo lang('registrations:group_label') ?></label>
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
											</div><?php */?>
										</div>
									</div>
								</div>
								<!-- Pertanggungan Kedua -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label class="col-md-6 control-label"><?php echo lang('registrations:second_company_label') ?></label>
											<label class="control-label">
												<div class="checkbox" style="margin:0;">
													<input type="hidden" name="f[PertanggunganKeduaIKS]" value="" >
													<input type="checkbox" id="PertanggunganKeduaIKS" class="second_insurer" name="f[PertanggunganKeduaIKS]" value="1" <?php echo @$item->PertanggunganKeduaIKS == 1 ? "Checked" : NULL ?>><label for="PertanggunganKeduaIKS">IKS</label>
												</div>
											</label>
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
										<div class="col-md-6">								
											<label class="control-label"><?php echo lang('registrations:card_number_label') ?></label>
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
		
								<!-- Asal Pasien -->
								<?php /*?><div class="page-subtitle margin-top-30">
									<i class="fa fa-h-square pull-left text-info"></i>
									<h3 class="text-info"><?php echo lang('registrations:patient_origin_subtitle') ?> Information</h3>
									<p><?php echo 'Silakan lengkapi Informasi Asal Pasien jika diperlukan'?></p>
								</div>
								<div class="form-group">
									<label class="col-md-3">
										<div class="checkbox" style="margin:0">
											<input type="hidden" name="f[Rujukan]" value="0" >
											<input type="checkbox" id="Rujukan" name="f[Rujukan]" value="1" <?php echo @$item->Rujukan == 1 ? "Checked" : NULL ?>><label for="Rujukan">Rujukan</label>
										</div>
									</label>
									<label class="col-md-3">
										<div class="checkbox" style="margin:0">
											<input type="hidden" name="f[Pribadi]" value="0" >
											<input type="checkbox" id="Pribadi" name="f[Pribadi]" value="1" <?php echo @$item->Pribadi == 1 ? "Checked" : NULL ?>><label for="Pribadi">Pribadi</label>
										</div>
									</label>
								</div>        
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label class="control-label"><?php echo lang('registrations:doctor_sender_label') ?></label>
											<div class="input-group">
												<input type="hidden" id="DokterKirimID" name="f[DokterKirimID]" value="<?php echo @$item->DokterKirimID ?>" class="doctor_sender">
												<input type="text" id="doctor_origin" value="<?php echo @$doctor->Nama_Supplier ?>" placeholder="" class="form-control doctor_sender">
												<span class="input-group-btn">
													<a href="<?php echo @$lookup_supplier ?>/doctor" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" id="clear_doctor_sender" class="btn btn-default" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
										<div class="col-md-6">
											<label class="control-label"><?php echo lang('registrations:vendor_sender_label') ?></label>
											<div class="input-group">
												<input type="hidden" id="VendorID_Referensi" name="f[VendorID_Referensi]" value="<?php echo @$item->VendorID_Referensi ?>" class="vendor_sender">
												<input type="text" id="vendor_origin" value="<?php echo @$vendor->Nama_Supplier ?>" placeholder="" class="form-control vendor_sender" >
												<span class="input-group-btn">
													<a href="<?php echo @$lookup_supplier ?>/vendor" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" id="clear_vendor_sender" class="btn btn-default" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
								</div><?php */?>
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:memo_label') ?></label>
									<textarea id="Keterangan" name="f[Keterangan]" placeholder="" class="form-control" rows="5"><?php echo @$item->Keterangan ?></textarea>
								</div>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-default btn-block btn-previous"><i class="fa fa-arrow-left"></i> <?php echo lang( 'buttons:back' ) ?></a>
								</div>
								<div class="col-md-4">
									<?php if(@$is_edit):?>
										<a href="<?php echo @$cancel_link ?>" data-toggle="form-ajax-modal" class="btn btn-danger btn-block" <?= (@$item->Batal == 1) ? "disabled":null ?>><i class="fa fa-trash"></i> <?php echo lang( 'buttons:cancel' ) ?></a>
									<?php endif; ?>
								</div>
								<div class="col-md-4">
									<?php if(! @$is_view):?>
										<button type="submit" id="btn_save" class="btn btn-primary btn-block " <?= (@$item->Batal == 1) ? "disabled":null ?>><b><i class="fa fa-save"></i> <?php echo lang( 'buttons:submit' ) ?></b></button>
									<?php endif; ?>
								</div>						
								<?php /*?><button type="button" id="print" class="btn btn-success">Cetak Label</button><?php */?>
							</div>
						</div>
					</div> <!-- #tab-4 -->
					
				</div>				
			</div>
			<?php if(@$is_edit):?>
				<div class="panel-body">
					<div class="pull-right">
						<button type="button" id="btn-print-label" class="btn btn-success"><i class="fa fa-print"></i> <?php echo 'Cetak Label' ?></button>
					</div>
				</div>
			<?php endif; ?>
		</div>
		
	</div>
</div>

<?php echo form_close() ?>

<?php if(config_item('bpjs_bridging') == 'TRUE' )
		echo modules::run("bpjs/registration/register", @$item->NoReg); ?>
		
<script type="text/javascript">
//<![CDATA[

// var socket = new WebSocket('ws://localhost:8080');
<?php if(config_item('use_websocket') == 'TRUE'): ?>
	var socket = new WebSocket('ws://' + '<?= config_item('websocket_ip') ?>' + ':8080');
<?php endif; ?>

var _form = $("form[name=\"form_registrations\"]");
var TipePelayanan = $("#TipePelayanan").val();
var dataPost = [];
var _is_success_registration = false;
var _form_actions = {
		init: function(){
			
				$( "select#PropinsiID" ).locale_chosen( "select#KabupatenID", "populate_county", "Select a County" );
				$( "select#KabupatenID" ).locale_chosen( "select#KecamatanID", "populate_district", "Select a District" );
				$( "select#KecamatanID" ).locale_chosen( "select#DesaID", "populate_village", "Select a Village" );
				//$( "select#DesaID" ).locale_chosen( "select#BanjarID", "populate_area", "Select a Area" );
				
				$("#TglLahir").on("dp.change", function(){
					
					age = _form_actions.getAge( $( this ).val() );
					
					$("#UmurThn").val( age.years );
					$("#UmurBln").val( age.months );
					$("#UmurHr").val( age.days );
					
				});
				
				
				// Jika Checkbox Pasien Baru Dicentang, maka akan Generate Nomor NRM
				$("#PasienBaru").on("change", function(){
					if($(this).is(':checked'))
					{														
						$("#NRM").prop("readonly", true);
						$(".lookup_patient").addClass("disabled");
						$("#clear_patient").addClass("disabled");
						$("#clear_reservaton").addClass("disabled");

						$.post('<?php echo @$gen_mrn_link ?>', {}, function( response, status, xhr ){
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}							
							$("#NRM").val( response.mrn );							
						});						
					} else {						
						_form.find(".patient").val("");					
						$("#NRM").prop("readonly", false);
						$(".lookup_patient").removeClass("disabled");
						$("#clear_reservaton").removeClass("disabled");
						$("#clear_patient").removeClass("disabled");
					}
				});


				// Penambhan titik untuk NRM 
				/*$("#NRM").on("keyup", function(e){
					var value = $(this).val();

					if (value.length % 3 == 2 && value.substr(value.length - 1, 1) !== ".")
					//if($(this).val().replace(/:/g, '').length % 2 == 0) 
					{
						if(value.length >= 6)
						{ 
							return false;
						}
						
						$(this).val(value + '.');
					} 
				});*/
				
				// SCRIPT UNTUK PENANGGUNG
				// Jika Penanggung adalah Pasien
				$("#PenanggungIsPasien").on("change", function(){
					if( $(this).is(':checked'))
					{
						$("#PenanggungNRM").prop("readonly", false);
						$("#lookup_insurer").removeClass("disabled");
						$("#clear_insurer").removeClass("disabled");
					} else {
						$("#PenanggungNRM").prop("readonly", true);
						$("#lookup_insurer").addClass("disabled");
						$("#clear_insurer").addClass("disabled");
						_form.find(".insurer").val('');
					}
					

				});
				
				// Clear Penanggung
				$("#clear_insurer").on("click", function(){
					_form.find(".insurer").val('');					
				});
				// Clear Regional
				$("#clear_regional").on("click", function(){
					_form.find(".regional").val('');					
				});
				// Clear Pasien
				$("#clear_patient").on("click", function(){
					_form.find(".patient").val('');					
				});
										
				// UNTUK Pertanggungan Kedua
				// JIka Type Pasien Adalah IKS
				$("#JenisKerjasamaID").on("change", function(){
					if ( $(this).val() == 2 ){
						_form.find(".second_insurer").val('');
						_form.find(".second_insurer").prop("checked", false);
						_form.find(".second_insurer").prop("disabled", true);
						_form.find(".second_insurer").addClass("disabled");
					} else {
						_form.find(".second_insurer").prop("disabled", false);
						_form.find(".second_insurer").removeClass("disabled");
					}
				});

				$("#PertanggunganKeduaIKS").on("change", function(){
					if( $(this).is(':checked'))
					{
						_form.find(".second_insurer").prop("disabled", false);
						_form.find(".second_insurer").removeClass("disabled");
					} else {
						_form.find(".second_insurer").val('');
						_form.find(".second_insurer").prop("disabled", true);
						_form.find(".second_insurer").addClass("disabled");
						_form.find(this).prop('disabled', false);
					}

				});

				$("#clear_second_insurer").on("click", function(){
					_form.find(".second_insurer").val('');					
				});
				
				$("#clear_second_insurer_card").on("click", function(){
					_form.find(".second_insurer_card").val('');					
				});

				$("#clear_doctor_sender").on("click", function(){
					_form.find(".doctor_sender").val('');					
				});

				$("#clear_vendor_sender").on("click", function(){
					_form.find(".vendor_sender").val('');					
				});
			
				$("#clear_cooperation").on("click", function(){
					_form.find(".cooperation").val('');					
				});

				$("#clear_cooperation_card").on("click", function(){
					_form.find(".cooperation_card").val('');					
				});
				
				if($("#TipePelayanan").val() == 'RawatJalan'){
						
					$("#KdKelas").val('XX');
					$("#KdKelas").prop('disable', true);
					$(".room").val('');
					$("#room_selection").hide();
				} else {
					$("#KdKelas").prop('disable', false);
					$("#room_selection").show();
				}
				
				$("#TipePelayanan").on("change", function(e){
					TipePelayanan = e.target.value;
					if(e.target.value == 'RawatJalan'){
						
						$("#KdKelas").val('XX');
						$("#KdKelas").prop('disable', true);
						$(".room").val('');
						$("#room_selection").hide();
					} else {
						$("#KdKelas").prop('disable', false);
						$("#room_selection").show();
					}
					
					$('#dt_registration_section').DataTable().clear().draw();
				});
				
				$('.btn-next').click(function(){
				  $('.nav-tabs > .active').next('li').find('a').trigger('click');
				});
				
				  $('.btn-previous').click(function(){
				  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
				});
				
				$( "#print" ).on( "click", function() {
					var nrm = $("#NRM").val();
					var dob = $("#TglLahir").val();
					if( confirm( "Cetak Data Label ?" ) ){
						window.open("<?php echo base_url() ?>registrations/print_report/" + nrm + "/" + dob + "/true");
					}							
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
		post: function(dataPost, fn){
			
				if( $('#JenisKerjasamaID').val() != 3 && ( $('#CustomerKerjasamaID').val() == '' || $('#CustomerKerjasamaID').val() == 0 ))
				{
					$.alert_warning('Tidak bisa menyimpan data, Perusahaan Kerja Sama belum dipilih!');
					return false;
				}	
									
				if( _is_success_registration === true )
				{
					if( $.isFunction(fn) ){
						fn(dataPost);
					}
					return false;
				}
				
				var validTime = true;
				
				$.each(_form.serializeArray(), function(i, value){
					dataPost.push(value); // push data form registrasi ke data post
				});
				
				dataPost.push({name: 'f['+ TipePelayanan +']', value: 1});
				
				var table_data = $( "#dt_registration_section" ).DataTable().rows().data();
				table_data.each(function (v, i) {
					if( v.WaktuID == '') validTime = false;
					dataPost.push({name: 'destinations['+ i +'][SectionID]', value: v.SectionID});
					dataPost.push({name: 'destinations['+ i +'][DokterID]', value: v.DokterID || 'XX'});
					dataPost.push({name: 'destinations['+ i +'][NoAntri]', value: v.NoAntri});
					dataPost.push({name: 'destinations['+ i +'][WaktuID]', value: v.WaktuID});
					dataPost.push({name: 'destinations['+ i +'][JenisKerjasamaID]', value: $("#JenisKerjasamaID").val()});
					dataPost.push({name: 'destinations['+ i +'][UmurThn]', value: $("#UmurThn").val()});
					dataPost.push({name: 'destinations['+ i +'][UmurBln]', value: $("#UmurBln").val()});
					dataPost.push({name: 'destinations['+ i +'][UmurHr]', value: $("#UmurHr").val()});
					
					dataPost.push({name: 'f[DokterRawatID]', value: v.DokterID || 'XX'});
				});
				
				if (! validTime && TipePelayanan == 'RawatJalan')
				{
					$.alert_warning( "Transaksi Tidak bisa dilanjutkan, Data Jam Pada Section Tujuan Belum Terisi!" );
					return false;
				}
				
				if (TipePelayanan == 'RawatInap' && ($('#NoKamar').val() == '' || $('#NoBed').val() == '') )
				{
					$.alert_warning( "Transaksi Tidak bisa dilanjutkan, Data Kamar Rawat Inap belum dipilih!" );
					return false;
				}
				
				if( $("#AnggotaBaru").is(":checked") )
				{
					dataPost.push({name: 'AnggotaBaru', value: 1});
				}
				
				$.post(_form.attr("action"), dataPost, function( response, status, xhr ){					
					if( "error" == response.status ){
						$.alert_error(response.message);
						return false
					}
					if( !response.NoReg ){
						$.alert_error( "Terjadi Kesalahan, Hubungi IT Support!" );
						return false
					}
					_is_success_registration = true;
					dataPost['NoReg'] = response.NoReg;
					
					if( $.isFunction(fn) ){
						fn(dataPost);
					}
				});
			},
		afterPost: function(){
				$.alert_success("Proses Registrasi berhasil dilakukan...");
				//refresh antrian di TV DISPLAY
				<?php if(config_item('use_websocket') == 'TRUE'): ?>
					socket.send('queue_refresh');
				<?php endif; ?>
				setTimeout(function(){													
					document.location.href = "<?php echo base_url("registrations"); ?>";
					}, 300 );
			}
	};
	
	var _datatable;
	var _datatable_actions = {
			edit: function( row, data, index ){
					
					switch( this.index() ){							
						case 2:
							if(TipePelayanan == 'RawatInap') return false;
							
							var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Memuat...</option>\n</select>" );
							this.empty().append( _input );
							
							var _value = data.WaktuID ? data.WaktuID : ''
							_input.load( "<?php echo base_url("registrations/time_dropdown") ?>/" + _value, function( response, status, xhr ){
									_input.trigger( "focus" )
								} );
							
							_input.on( "blur", function( e ){
									e.preventDefault();
									try{
										$( e.target ).remove();
										_datatable.row( row ).data( data );
										_datatable_actions.get_queue( row, data );
									} catch(ex){}
								});
							
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
						case 4:
							if(TipePelayanan == 'RawatInap') return false;
						
							var _input = $( "<input type=\"text\" value=\"" + data.NoAntri + "\" style=\"width:100%\"  class=\"form-control\">" );
							this.empty().append( _input );
							
							_input.trigger( "focus" );
							_input.on( "blur", function(e){
									e.preventDefault();

									try{
										
										data.NoAntri = this.value;
										_datatable.row( row ).data( data );
										
									} catch(ex){}
							});								
						break;
						
					}
				},
			check_type_service: function(index){
				if(TipePelayanan == 'RawatInap' || index == 0) 
					return true;
				<?php if(@$is_edit): ?>
				<?php else: ?>
					$.alert_error('Pelayanan Rawat Jalan hanya dapat memilih satu Section Tujuan saja');
					_datatable.row( index ).remove().draw();
					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open").removeAttr("style");
				<?php endif; ?>
			},
			remove: function( params, scope, fn ){
				
				post_data = {
					NoReg : $("#NoReg").val(),
					SectionID : params.SectionID,
					DokterID : params.DokterID,
					WaktuID : params.WaktuID
				}						
				$.post('<?php echo @$delete_registration_destination_link ?>', post_data, function( response, status, xhr ){							
					if( "error" == response.status ){
						$.alert_error(response.message);
						return false;
					}
					
					$.alert_success(response.message);
					
					var jenisKerjasama = $('#JenisKerjasamaID').val();
					if(typeof bpjsBridgingRegistration !== 'undefined' && jenisKerjasama == 9 ){
						bpjsRemoveRegistration.post(post_data);
					}
					_datatable.row( scope ).remove().draw();							
				});	
						
			},
			get_queue: function( scope, params ){		
				$("#btn_save").addClass('disabled', true);		
				$.post('<?php echo @$get_queue_link ?>', params, function( response, status, xhr ){							
					if( "error" == response.status ){
						$.alert_error(response.message);
						$("#btn_save").removeClass('disabled');		
						return false
					}							
					params.NoAntri =  response.queue;
					_datatable.row( scope ).data( params ).draw();
					$("#btn_save").removeClass('disabled');		
				});						
			},
		};

(function( $ ){
		
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
								<?php if (!empty($section_destination)):?>
								data: <?php print_r(json_encode($section_destination, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
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
										{ data: "Keterangan", className: "text-center clock", },
										{ data: "NoAntri", className: "text-center", },
									
										
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>, data yang dihapus akan hilang" ) ){
													_datatable_actions.remove( data, row)
												}
											})
											
										_datatable_actions.check_type_service(index)
									}
							} );
							
						$( "#dt_registration_section_length select, #dt_registration_section_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
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
									url: '<?php echo base_url( "registrations/zones" ) ?>/' + endpoint + '/' + sup_id,
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
		
			
		$(document).keydown(function(event) {
			if(event.which == 112) { //F1
				// $('#lookup_patients').click();
				return false;
			}
			else if(event.which == 113) { //F2
				$('#find_section').click();
				return false;
			}
			else if(event.which == 114) { //F3
				$('#lookup_patients').click();
				return false;
			}
			else if(event.which == 13) { //ENTER
				$(':submit').click();
				return false;
			}
		});

		//PRINT LABEL
		$("#btn-print-label").on("click", function(e){					
			printJS({
				printable: `<?php echo @$print_label ?>`,
				type: 'pdf',
				base64: true
			});				
		});	
		
		$( document ).ready(function(e) {
				_form_actions.init();

            	$( "#dt_registration_section" ).dt_registration_section();
												
				_form.on("submit", function(e){
					e.preventDefault();
					
					if (!confirm("Apakah Anda ingin menyimpan data ini ?"))
					{
						return false;
					}
					
					var jenisKerjasama = $('#JenisKerjasamaID').val();
									
					if(typeof bpjsBridgingRegistration !== 'undefined' && jenisKerjasama == 9 ){
						_form_actions.post(dataPost, bpjsAddRegistration.post);
					} else {
						_form_actions.post(dataPost, _form_actions.afterPost);
					}					
				});				
			});

	})( jQuery );
//]]>
</script>