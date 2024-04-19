<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//echo 123; exit;
?>
<?php echo form_open(current_url(), array("name" => "form_patient")); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang('patients:heading') ?></h3>
			</div>
			<div class="panel-body">
				<ul id="tab-poly" class="nav nav-tabs nav-justified">
					<li class="active"><a href="#poly-tab1" data-toggle="tab"><b><i class="fa fa-wheelchair"></i> Data Pasien</b></a></li>
					<li><a href="#poly-tab2" data-toggle="tab"><b><i class="fa fa-users"></i> Data Penanggung</b></a></li>
					<li><a href="#poly-tab3" data-toggle="tab"><b><i class="fa fa-users"></i> Perusahaan Kerjasama </b></a></li>
				</ul>
				<div class="tab-content">
					<div id="poly-tab1" class="tab-pane tab-pane-padding active">
						<div class="page-subtitle margin-top-30">
							<i class="fa fa-user pull-left text-info"></i>
							<h3 class="text-info"><?php echo 'General Information' ?></h3>
							<p><?php echo 'Informasi umum tentang Pasien' ?></p>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo lang('registrations:mr_number_label') ?></label>
									<input type="text" id="NRM" name="NRM" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control mask_nrm patient" maxlength="8">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo lang('patients:mr_number_label') . ' Lama' ?></label>
									<input type="text" id="NRMLama" name="f[NRMLama]" value="<?php echo @$item->NRMLama ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:patient_name_label') ?></label>
									<input type="text" id="NamaPasien" name="f[NamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:alias_label') ?></label>
									<input type="text" id="NamaAlias" name="f[NamaAlias]" value="<?php echo @$item->NamaAlias ?>" placeholder="" class="form-control patient">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:gender_label') ?></label>
									<select id="JenisKelamin" name="f[JenisKelamin]" class="form-control patient">
										<option value="">-- Pilih --</option>
										<option value="M" <?php echo @$item->JenisKelamin == "M"  ? "selected" : NULL  ?>><?php echo lang('global:male') ?></option>
										<option value="F" <?php echo @$item->JenisKelamin == "F"  ? "selected" : NULL  ?>><?php echo lang('global:female') ?></option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:religion_label') ?></label>
									<select id="Agama" name="f[Agama]" class="form-control patient ">
										<option value="">-- Pilih --</option>
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
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo 'Tanggal Lahir' ?></label>
									<div class="input-group">
										<span class="input-group-btn">
											<a href="javascript:;" id="clear_patient" class="btn btn-default"><i class="fa fa-calendar"></i></a>
										</span>
										<input type="text" id="TglLahir" name="f[TglLahir]" value="<?php echo @$item->TglLahir ?>" placeholder="" class="form-control datepicker patient">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:age_label') ?></label>
									<div class="input-group">
										<input type="text" id="UmurThn" name="f[UmurSaatInput]" value="<?php echo @$item->UmurThn ?>" class="form-control text-right patient" readonly>
										<span class="input-group-addon" style="padding:6px 6px !important"><?php echo lang('registrations:year_label') ?></span>
										<input type="text" id="UmurBln" name="UmurBln" value="<?php echo @$item->UmurBln ?>" class="form-control text-right patient" readonly>
										<span class="input-group-addon" style="padding:6px 6px !important"><?php echo lang('registrations:month_label') ?></span>
										<input type="text" id="UmurHr" name="UmurHr" value="<?php echo @$item->UmurHr ?>" class="form-control text-right patient" readonly>
										<span class="input-group-addon" style="padding:6px 6px !important"><?php echo lang('registrations:day_label') ?></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:identify_number_label') ?></label>
									<input type="text" id="NoIdentitas" name="f[NoIdentitas]" value="<?php echo @$item->NoIdentitas ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:birth_place_label') ?></label>
									<input type="text" id="TempatLahir" name="f[TempatLahir]" value="<?php echo @$item->TempatLahir ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Opsi</label>
									<div class="row">
										<div class="col-md-6">
											<div class="checkbox">
												<input type="hidden" name="f[PasienKTP]" value="0">
												<input type="checkbox" id="PasienKTP" name="f[PasienKTP]" value="1" <?php echo @$item->PasienKTP == 1 ? "Checked" : NULL ?> class=" patient"><label for="PasienKTP">Pasien KTP</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="checkbox">
												<input type="hidden" name="f[PasienVVIP]" value="0">
												<input type="checkbox" id="PasienVVIP" name="f[PasienVVIP]" value="1" <?php echo @$item->PasienVVIP == 1 ? "Checked" : NULL ?> class=" patient"><label for="PasienVVIP">Pasien VVIP</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="page-subtitle margin-top-30">
							<i class="fa fa-map-marker pull-left text-info"></i>
							<h3 class="text-info"><?php echo lang('registrations:address_label') ?> Information</h3>
							<p><?php echo 'Silakan lengkapi Informasi Alamat jika diperlukan' ?></p>
						</div>
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:address_label') ?></label>
									<input type="text" id="Alamat" name="f[Alamat]" value="<?php echo @$item->Alamat ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:country_label') ?></label>
									<select id="NationalityID" name="f[NationalityID]" class="form-control patient">
										<?php if (!empty($option_nationality)) : foreach ($option_nationality as $row) : ?>
												<option value="<?php echo $row->NationalityID ?>" <?php echo $row->NationalityID == @$item->NationalityID ? "selected" : NULL  ?>><?php echo $row->Nationality ?></option>
										<?php endforeach;
										endif; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:province_label') ?></label>
									<?php echo form_dropdown('f[PropinsiID]', @$list_provinsi, set_value('f[PropinsiID]', @$regional->ProvinsiId, TRUE), [
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
										<input type="text" id="Kabupaten" name="f[KabupatenID]" value="<?php echo @$regional->KabupatenNama ?>" placeholder="KABUPATEN" class="form-control patient regional" disabled>
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
									<input id="Kecamatan" name="f[KecamatanID]" class="form-control patient regional" value="<?= @$regional->KecamatanNama ?>" placeholder="KECAMATAN" disabled></input>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:village_label') ?></label>
									<input id="Desa" name="f[DesaID]" class="form-control patient regional" value="<?= @$regional->DesaNama ?>" placeholder="DESA" disabled></input>
									<input id="KodeRegional" name="f[KodeRegional]" class="form-control patient regional hidden" value="<?= @$item->KodeRegional ?>"></input>
								</div>
							</div>
						</div>
						<div class="page-subtitle margin-top-30">
							<i class="fa fa-phone pull-left text-info"></i>
							<h3 class="text-info"><?php echo lang('registrations:contact_label') ?> Information</h3>
							<p><?php echo 'Silakan lengkapi Informasi Kontak jika diperlukan' ?></p>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:phone_label') ?></label>
									<input type="text" id="Phone" name="f[Phone]" value="<?php echo @$item->Phone ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:email_label') ?></label>
									<input type="text" id="Email" name="f[Email]" value="<?php echo @$item->Email ?>" placeholder="" class="form-control patient">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:job_label') ?></label>
									<input type="text" id="Pekerjaan" name="f[Pekerjaan]" value="<?php echo @$item->Pekerjaan ?>" placeholder="" class="form-control patient">
								</div>
							</div>
						</div>
						<hr />
						<div class="row">
							<div class="form-group">
								<div class="col-md-8">
								</div>
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-primary btn-block btn-next"><?php echo lang('buttons:next') ?> <i class="fa fa-arrow-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div id="poly-tab2" class="tab-pane tab-pane-padding">
						<!-- Penanggung Pasien -->
						<div class="page-subtitle">
							<i class="fa fa-users pull-left text-info"></i>
							<h3 class="text-info"><?php echo lang('registrations:insurer_subtitle') ?> Information</h3>
							<p><?php echo 'Silakan lengkapi Informasi Penanggung jika diperlukan' ?></p>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo lang('registrations:mr_number_label') ?></label>
									<label class="control-label">
										<div class="checkbox" style="margin:0">
											<input type="hidden" name="f[PenanggungIsPasien]" value="0">
											<input type="checkbox" id="PenanggungIsPasien" name="f[PenanggungIsPasien]" value="1" <?php echo @$item->PenanggungIsPasien == 1 ? "Checked" : NULL ?>>
											<label for="PenanggungIsPasien"><?php echo lang('registrations:is_patient_label') ?></label>
										</div>
									</label>
									<div class="input-group">
										<input type="hidden" id="PenanggungNoKartu" class="insurer" name="f[PenanggungNoKartu]" value="<?php echo @$item->PenanggungNoKartu ?>">
										<input type="text" id="PenanggungNRM" name="f[PenanggungNRM]" value="<?php echo @$item->PenanggungNRM ?>" placeholder="" class="form-control insurer" readonly>
										<span class="input-group-btn">
											<a href="<?php echo @$lookup_insurer ?>" id="lookup_insurer" data-toggle="lookup-ajax-modal" class="btn btn-default disabled"><i class="fa fa-search"></i></a>
											<a href="javascript:;" id="clear_insurer" data-target="insurer" class="btn btn-default disabled"><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo lang('registrations:name_label') ?> </label>
									<input type="text" id="PenanggungNama" name="f[PenanggungNama]" value="<?php echo @$item->PenanggungNama ?>" placeholder="" class="form-control insurer">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:address_label') ?></label>
									<input type="text" id="PenanggungAlamat" name="f[PenanggungAlamat]" value="<?php echo @$item->PenanggungAlamat ?>" placeholder="" class="form-control insurer">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('id_type:ktp') ?></label>
									<input type="text" id="PenanggungKTP" name="f[PenanggungKTP]" value="<?php echo @$item->PenanggungKTP ?>" placeholder="" class="form-control insurer">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:phone_label') ?></label>
									<input type="text" id="PenanggungPhone" name="f[PenanggungPhone]" value="<?php echo @$item->PenanggungPhone ?>" placeholder="" class="form-control insurer">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"><?php echo lang('registrations:relationship_label') ?></label>
									<select id="PenanggungHubungan" name="f[PenanggungHubungan]" class="form-control insurer ">
										<option value=""></option>
										<option value="Pasien Sendiri" <?php echo @$item->PenanggungHubungan == "Pasien Sendiri"  ? "selected" : NULL  ?>>Pasien Sendiri</option>
										<option value="Orang Tua" <?php echo @$item->PenanggungHubungan == "Orang Tua"  ? "selected" : NULL  ?>>Orang Tua </option>
										<option value="Suami/Istri" <?php echo @$item->PenanggungHubungan == "Suami/Istri"  ? "selected" : NULL  ?>>Suami/Istri</option>
										<option value="Anak" <?php echo @$item->PenanggungHubungan == "Anak"  ? "selected" : NULL  ?>>Anak</option>
										<option value="Saudara Kandung" <?php echo @$item->PenanggungHubungan == "Saudara Kandung"  ? "selected" : NULL  ?>>Saudara Kandung</option>
										<option value="Teman" <?php echo @$item->PenanggungHubungan == "Teman"  ? "selected" : NULL  ?>>Teman</option>
										<option value="Lainnya" <?php echo @$item->PenanggungHubungan == "Lainnya"  ? "selected" : NULL  ?>>Lainnya</option>
									</select>
								</div>
							</div>
						</div>
						<hr />
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-default btn-block btn-previous"><i class="fa fa-arrow-left"></i> <?php echo lang('buttons:back') ?></a>
								</div>
								<div class="col-md-4">
									<?php if (@$is_edit) : ?>
										<a href="<?php echo @$cancel_link ?>" data-toggle="form-ajax-modal" class="btn btn-danger btn-block" <?= (@$item->Batal == 1) ? "disabled" : null ?>><i class="fa fa-trash"></i> <?php echo lang('buttons:cancel') ?></a>
									<?php endif; ?>
								</div>
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-primary btn-block btn-next"><?php echo lang('buttons:next') ?> <i class="fa fa-arrow-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div id="poly-tab3" class="tab-pane tab-pane-padding">
						<div class="row">
							<div class="col-md-6">
								<!-- Section Tujuan-->
								<div class="page-subtitle ">
									<i class="fa fa-stethoscope pull-left text-info"></i>
									<h3 class="text-info"><?php echo "Perusahaan Kerja Sama" ?> Information</h3>
									<p><?php echo 'Silakan lengkapi Informasi Section Tujuan' ?></p>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label"><?php echo lang('registrations:type_patient_label') ?></label>
												<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control">
													<?php if (!empty($option_patient_type)) : foreach ($option_patient_type as $row) : ?>
															<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
													<?php endforeach;
													endif; ?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label class="control-label"><?php echo lang('registrations:company_label') ?></label>
											<div class="input-group">
												<input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>" class="cooperation">
												<input type="hidden" id="KdKelas" name="f[KdKelas]" value="<?php echo @$item->KdKelas ?>" class="cooperation">
												<input type="hidden" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->KodePerusahaan ?>" placeholder="" class="cooperation">
												<input type="text" id="Nama_Customer" value="<?php echo @$item->Nama_Customer ?>" placeholder="" class="form-control cooperation" disabled="disabled">
												<span class="input-group-btn">
													<a href="<?php echo @$lookup_cooperation ?>" id="lookup_cooperation" data-toggle="lookup-ajax-modal" class="btn btn-default"><i class="fa fa-search"></i></a>
													<a href="javascript:;" id="clear_cooperation" class="btn btn-default"><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
										<div class="col-md-6">
											<label class="col-md-3 control-label"><?php echo lang('registrations:card_number_label') ?></label>
											<label class="control-label">
												<div class="checkbox" style="margin:0">
													<input type="hidden" name="f[AnggotaBaru]" value="0">
													<input type="checkbox" id="AnggotaBaru" name="f[AnggotaBaru]" value="1" class="" <?php echo (@$patient->AnggotaBaru == 1) ? 'checked' : NULL ?>><label for="AnggotaBaru">Anggota Baru</label>
												</div>
											</label>
											<div id="memberNumberIKSArea" class="input-group">
												<input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control cooperation cooperation_card">
												<span class="input-group-btn">
													<a href="<?php echo $lookup_patient_cooperation_card ?>" data-toggle="lookup-ajax-modal" class="btn btn-default"><i class="fa fa-search"></i></a>
													<a href="javascript:;" id="clear_cooperation_card" class="btn btn-default"><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<!-- Kerja Sama-->
								<div class="page-subtitle">
									<i class="fa fa-handshake-o pull-left text-info"></i>
									<h3 class="text-info">Information <?php echo "Riwayat Pasien" ?> </h3>
									<p><?php echo 'Silakan lengkapi Informasi Perusahaan Kerjasama jika diperlukan' ?></p>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-lg-3 control-label">Riwayat Alergi</label>
										<div class="col-lg-9">
											<textarea id="RiwayatAlergi" name="f[RiwayatAlergi]" placeholder="" class="form-control"><?php echo @$item->RiwayatAlergi ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-3 control-label">Riwayat Penyakit</label>
										<div class="col-lg-9">
											<textarea id="RiwayatPenyakit" name="f[RiwayatPenyakit]" placeholder="" class="form-control"><?php echo @$item->RiwayatPenyakit ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-3 control-label">Riwayat Obat</label>
										<div class="col-lg-9">
											<textarea id="RiwayatObat" name="f[RiwayatObat]" placeholder="" class="form-control"><?php echo @$item->RiwayatObat ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr />
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<a href="javascript:;" class="btn btn-default btn-block btn-previous"><i class="fa fa-arrow-left"></i> <?php echo lang('buttons:back') ?></a>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
									<a type="submit" id="submit" class="btn btn-primary btn-block"><?php echo lang('buttons:submit') ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr />
			</div>
		</div>
	</div>
</div>

<?php echo form_close() ?>

<script type="text/javascript">
	//<![CDATA[
	var dataPost = [];
	var _is_success_patient = false;


	$("#submit").on("click", function(datapost, e) {

		var _form = $("form[name=\"form_patient\"]");

		if ($.isFunction(e)) {
			e(dataPost);
		}

		$.each(_form.serializeArray(), function(i, value) {
			dataPost.push(value); // push data form registrasi ke data post
		});


		setTimeout(function() {

			$.alert_warning("Data Sedang Diproses, Mohon Menunggu!");

			$.post(_form.attr("action"), dataPost, function(response, status, xhr) {

				if ("error" == response.status) {
					$.alert_error(response.message);
					return false
				}

				if ("success" == response.status) {
					$.alert_success("Data Pasien Berhasil Disimpan!");
					setTimeout(function() {
						document.location.href = "<?php echo base_url("common/patients"); ?>";
					}, 500);
				}

				$.alert_eror("Silahkan Menghubungi IT!");

			});
		}, 1000);

	});

	(function($) {
		$(document).ready(function() {

			<?php if (isset($is_ajax_request)) : ?>
				try {
					dev_forms.init()
				} catch (e) {}
			<?php endif ?>

			$("#TglLahir").on("change blur", function() {
				dob = $(this).val();

				age = getAge(dob);

				$("#UmurSaatInput").val(age.years);
				$("#UmurThn").val(age.years);
				$("#UmurBln").val(age.months);
				$("#UmurHr").val(age.days);

			});

			function getAge(dateString) {
				var now = new Date();
				var today = new Date(now.getYear(), now.getMonth(), now.getDate());

				var yearNow = now.getYear();
				var monthNow = now.getMonth();
				var dateNow = now.getDate();
				// yyyy-mm-dd
				var dob = new Date(dateString.substring(0, 4), //yyyy
					dateString.substring(5, 7) - 1, //mm               
					dateString.substring(8, 10) //dd            
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
					var monthAge = 12 + monthNow - monthDob;
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

			age = getAge('<?php echo $item->TglLahir ?>');

			$("#UmurSaatInput").val(age.years);
			$("#UmurThn").val(age.years);
			$("#UmurBln").val(age.months);
			$("#UmurHr").val(age.days);

			console.log('asdada');
		});
		$('.btn-next').click(function() {
			$('.nav-tabs > .active').next('li').find('a').trigger('click');
		});

		$('.btn-previous').click(function() {
			$('.nav-tabs > .active').prev('li').find('a').trigger('click');
		});

		$("#PenanggungIsPasien").on("change", function() {
			if ($(this).is(':checked')) {
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

		$("#clear_insurer").on("click", function() {
			_form.find(".insurer").val('');
		});

	})(jQuery);
	//]]>
</script>