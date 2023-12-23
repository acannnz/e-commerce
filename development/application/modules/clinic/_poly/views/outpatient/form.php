<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open(current_url(), array("name" => "form_poly")); ?>
<?php if (@$item->ProsesPayment == 1 || (!empty($item->StatusBayar) && @$item->StatusBayar == "Sudah Bayar")) : ?>
	<h3 class="subtitle well">Status Data: <span class='text-info'><?php echo $item->ProsesPayment ? 'Proses' : $item->StatusBayar ?></span> di Kasir.</h3>
<?php endif; ?>
<?php if (@$item->Batal == 1) : ?>
	<h3 class="subtitle well">Status Data: <span class='text-danger'>Dibatalkan</span>.</h3>
<?php endif; ?>
<div class="panel panel-info panel-collapsed">
	<div class="panel-heading panel-collapse">
		<h3 class="panel-title"><?php echo lang('poly:patient_label') ?></h3>
		<ul class="panel-btn">
			<li><a href="javascript:;" class="btn btn-info panel-collapse" title="Tampilkan"><i class="fa fa-angle-down"></i></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input type="hidden" id="SectionID" name="f[SectionID]" value="<?php echo $item->SectionID; ?>" />
					<input type="hidden" id="PasienKTP" name="f[PasienKTP]" value="<?php echo !empty($item->PasienKTP) ? $item->PasienKTP : $item->PasienKTP ?>" />
					<label class="col-lg-3 control-label"><?php echo lang('poly:evidence_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:registration_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="RegNo" name="f[RegNo]" value="<?php echo !empty($item->NoReg) ? $item->NoReg : $item->RegNo ?>" placeholder="" class="form-control" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:mr_number_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:patient_name_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:type_patient_label') ?></label>
					<div class="col-lg-6">
						<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control" disabled="disabled">
							<?php if (!empty($option_patient_type)) : foreach ($option_patient_type as $row) : ?>
									<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
							<?php endforeach;
							endif; ?>
						</select>
					</div>
					<div class="col-lg-3">
						<select id="JenisKelamin" name="p[JenisKelamin]" class="form-control patient" disabled>
							<option value="F" <?php echo @$item->JenisKelamin == "F"  ? "selected" : NULL  ?>>Perempuan</option>
							<option value="M" <?php echo @$item->JenisKelamin == "M"  ? "selected" : NULL  ?>>Laki-laki</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:address_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="Alamat" name="p[Alamat]" value="<?php echo @$item->Alamat ?>" class="form-control patient" disabled>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:dob_label') ?></label>
					<div class="col-lg-3">
						<input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$item->TglLahir ?>" placeholder="" class="form-control datepicker patient" disabled>
					</div>
					<div class="col-lg-1">
						<input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" placeholder="" class="form-control" readonly>
					</div>
					<label class="col-lg-1 control-label"><?php echo lang('poly:year_label') ?></label>
					<div class="col-lg-1">
						<input type="text" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>" placeholder="" class="form-control" readonly>
					</div>
					<label class="col-lg-1 control-label"><?php echo lang('poly:month_label') ?></label>
					<div class="col-lg-1">
						<input type="text" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>" placeholder="" class="form-control" readonly>
					</div>
					<label class="col-lg-1 control-label"><?php echo lang('poly:day_label') ?></label>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:phone_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="Phone" value="<?php echo @$item->Phone ?>" placeholder="" class="form-control cooperation" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:id_number_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NoIdentitas" value="<?php echo @$item->NoIdentitas ?>" placeholder="" class="form-control cooperation" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:company_code_label') ?></label>
					<div class="col-lg-9">
						<input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>" class="cooperation">
						<input type="hidden" id="KdKelas" name="f[KdKelas]" value="<?php echo @$item->KdKelas ?>" class="cooperation">
						<input type="text" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->Kode_Customer ?>" placeholder="" class="form-control cooperation" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:company_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPerusahaan" value="<?php echo @$item->NamaPerusahaan ?>" placeholder="" class="form-control cooperation" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('poly:card_number_label') ?></label>
					<div class="col-md-6">
						<input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control cooperation cooperation_card" readonly>
					</div>
					<label class="col-lg-2 control-label text-center"><?php echo lang('poly:group_label') ?></label>
					<div class="col-lg-1">
						<select id="Klp" name="p[Klp]" class="form-control patient" disabled>
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
			</div>
		</div>
	</div>
</div>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo sprintf('%s: (%s) %s', lang('poly:examination_label'), @$item->NoBukti, @$item->NamaPasien); ?></h3>
		<ul class="panel-btn">
			<?php if (config_item('use_websocket') == 'TRUE') : ?>
				<li><button type="botton" id="queue_calling" class="btn btn-info" title=""><i class="fa fa-bell" id="icon_panggil" aria-hidden="true"></i> Panggil Antrean</button></li>
			<?php endif; ?>
			<li><a href="javascript:;" class="btn btn-success panel-collapse" title="Tampilkan"><i class="fa fa-angle-down"></i></a></li>

		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?php echo lang('poly:doctor_label') ?></label>
					<div class="input-group">
						<input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$item->DokterID ?>" class="doctor_sender">
						<input type="text" id="DocterName" value="<?php echo @$item->NamaDokter ?>" placeholder="" class="form-control">
						<span class="input-group-btn">
							<a href="<?php echo @$lookup_supplier ?>" data-toggle="lookup-ajax-modal" class="btn btn-default"><i class="fa fa-search"></i></a>
							<a href="javascript:;" id="clear_doctor" class="btn btn-default"><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>

			<?php /*?><div class="form-group">
					<label class="col-lg-3 control-label">Kecelakaan</label>
					<div class="col-lg-9">
						<select id="Kecelakaan" name="f[Kecelakaan]" class="form-control">
							<option value="None" <?php echo @$item->Kecelakaan == "None"  ? "selected" : NULL  ?>>None</option>
							<option value="Lalu Lintas" <?php echo @$item->Kecelakaan == "Lalu Lintas"  ? "selected" : NULL  ?>>Lalu Lintas</option>
							<option value="Kerja" <?php echo @$item->Kecelakaan == "Kerja"  ? "selected" : NULL  ?>>Kerja</option>
							<option value="Rumah Tangga" <?php echo @$item->Kecelakaan == "Rumah Tangga"  ? "selected" : NULL  ?>>Rumah Tangga</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-3">
					</div>
					<div class="col-md-3">
						<div class="checkbox">
							<input type="hidden" name="f[KasusPertama]" value="0" >
							<input type="checkbox" id="KasusPertama" name="f[KasusPertama]" value="1" <?php echo @$item->KasusPertama == 1 ? "Checked" : NULL ?> class=""><label for="KasusPertama">Kasus Pertama</label>
						</div>
					</div>
					<div class="col-md-3">
						<div class="checkbox">
							<input type="hidden" name="f[DOA]" value="0" >
							<input type="checkbox" id="DOA" name="f[DOA]" value="1" <?php echo @$item->DOA == 1 ? "Checked" : NULL ?> class=""><label for="DOA">DOA</label>
						</div>
					</div>
				</div>        
				<div class="form-group">
					<div class="col-md-3">
					</div>
					<div class="col-md-3">
						<div class="checkbox">
							<input type="hidden" name="f[Dentist]" value="0" >
							<input type="checkbox" id="Dentist" name="f[Dentist]" value="1" <?php echo @$item->Dentist == 1 ? "Checked" : NULL ?> class=""><label for="Dentist">Dentist</label>
						</div>
					</div>
					<div class="col-md-1">
						<div class="checkbox">
							<input type="hidden" name="f[DC]" value="0" >
							<input type="checkbox" id="DC" name="f[DC]" value="1" <?php echo @$item->DC == 1 ? "Checked" : NULL ?> class="" ><label for="DC">DC</label>
						</div>
					</div>
					<div class="col-md-1">
						<input type="text" id="DC_Hari" name="f[DC_Hari]" value="<?php echo @$item->DC_Hari ?>" placeholder="" class="form-control">            
					</div>
					<label class="control-label col-md-1">Hari</label>
				</div>        <?php */ ?>
		</div>

		<div class="row">
			<hr />
		</div>
		<ul id="tab-poly" class="nav nav-tabs nav-justified">
			<li class="active"><a href="#poly-tab3" data-toggle="tab"><i class="fa fa-heartbeat"></i> Rekam Medis</a></li>
			<?php if ($item->SectionID == "SEC010") : ?>
				<li><a href="#poly-tab2" data-toggle="tab"><i class="fa fa-stethoscope"></i> Odontogram</a></li>
			<?php endif; ?>
			<li><a href="#poly-tab1" data-toggle="tab"><i class="fa fa-stethoscope"></i> Jasa</a></li>
			<li><a href="#poly-tab5" data-toggle="tab"><i class="fa fa-medkit"></i> Resep</a></li>
			<!-- <li><a href="#poly-tab6" data-toggle="tab"><i class="fa fa-flask"></i> Penunjang</a></li> 
			<li><a href="#poly-tab7" data-toggle="tab"><i class="fa fa-paperclip"></i> BHP</a></li> -->
			<!-- <li><a href="#poly-tab8" data-toggle="tab"><i class="fa fa-files-o"></i> Memo</a></li> -->
			<li><a href="#poly-tab4" data-toggle="tab"><i class="fa fa-user"></i> Perawat</a></li>
			<li><a href="#poly-tab10" data-toggle="tab"><i class="fa fa-file"></i> FIle</a></li>
			<li><a href="#poly-tab9" data-toggle="tab"><i class="fa fa-wheelchair"></i> CheckOut</a></li>
		</ul>
		<div class="tab-content">
			<div id="poly-tab1" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/service_outpatient/index", @$item, @$is_edit) ?>
			</div>
			<?php if ($item->SectionID == "SEC010") : ?>
				<div id="poly-tab2" class="tab-pane tab-pane-padding">
					<?php echo modules::run("poly/dentist/odontogram/index") ?>
				</div>
			<?php elseif ($item->SectionID == "SEC008") : ?>
				<div id="poly-tab3" class="tab-pane tab-pane-padding active">
					<?php echo modules::run("{$nameroutes}s/medical_record/index_obgyn", @$item->NoReg, @$item->NRM, @$item->NoBukti, @$is_edit) ?>
				</div>
			<?php elseif ($item->SectionID == "SEC082") : ?>
				<div id="poly-tab3" class="tab-pane tab-pane-padding active">
					<?php echo modules::run("{$nameroutes}s/medical_record/index_anak", @$item->NoReg, @$item->NRM, @$item->NoBukti, @$is_edit) ?>
				</div>
			<?php elseif ($item->SectionID == "SEC085") : ?>
				<div id="poly-tab3" class="tab-pane tab-pane-padding active">
					<?php echo modules::run("{$nameroutes}s/medical_record/index_penyakit_dalam", @$item->NoReg, @$item->NRM, @$item->NoBukti, @$is_edit) ?>
				</div>
			<?php elseif ($item->SectionID == "SEC083") : ?>
				<div id="poly-tab3" class="tab-pane tab-pane-padding active">
					<?php echo modules::run("{$nameroutes}s/medical_record/index_tht", @$item->NoReg, @$item->NRM, @$item->NoBukti, @$is_edit) ?>
				</div>
			<?php else : ?>
				<div id="poly-tab3" class="tab-pane tab-pane-padding active">
					<?php echo modules::run("{$nameroutes}s/medical_record/index", @$item->NoReg, @$item->NRM, @$item->NoBukti, @$is_edit) ?>
				</div>
			<?php endif; ?>
			<div id="poly-tab4" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/nurse/index", @$item->NoBukti, @$is_edit) ?>
			</div>
			<div id="poly-tab5" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/prescriptions/index", $item->NoReg, $item->SectionID) ?>
			</div>
			<div id="poly-tab6" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/helpers/index", @$item) ?>
			</div>
			<div id="poly-tab7" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/consumables/index", $item->NoReg, $item->SectionID) ?>
			</div>
			<div id="poly-tab8" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/memo/index", $item->NoReg, $item->SectionID) ?>
			</div>
			<div id="poly-tab9" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/checkout/index", $item->NoReg, $item->SectionID) ?>
			</div>
			<div id="poly-tab10" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/file/index", @$item->NoBukti, $item->SectionID, @$item->NRM) ?>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-4">
				<a href="<?php echo base_url($nameroutes) ?>" class="btn btn-default btn-block"><i class="fa fa-arrow-left"></i> <?php echo lang('buttons:back') ?></a>
			</div>
			<?php if ($item->Batal == 0 && (@$item->ProsesPayment == 0 || (!empty($item->StatusBayar) && @$item->StatusBayar != "Belum"))) : ?>
				<div class="col-lg-4">
					<?php if (@$is_edit) : ?>
						<a href="<?php echo @$cancel_link ?>" class="btn btn-danger btn-block" data-toggle="lookup-ajax-modal"><i class="fa fa-times"></i> <?php echo lang('buttons:cancel') ?> Pemeriksaan</a>
					<?php endif; ?>
				</div>
				<div class="col-lg-4">
					<button type="submit" class="btn btn-primary btn-block" id="js-btn-submit"><i class="fa fa-save"></i> <?php echo lang('buttons:submit') ?></button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<?php echo Modules::run("poly/outpatient/note_patient", true, @$item->NRM) ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#modalNotePasien").modal('show');
	});
	//<![CDATA[
	(function($) {
		// var socket = new WebSocket('ws://localhost:8080');
		<?php if (config_item('use_websocket') == 'TRUE') : ?>
			var socket = new WebSocket('ws://' + '<?= config_item('websocket_ip') ?>' + ':8080');
			socket.onclose = function(e) {
				$("#icon_panggil").css("color", "red");
				$("#queue_calling").addClass('disabled', true);
				$("#icon_panggil").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
				$.alert_error('Tidak dapat memanggil antrian, Websocket tidak aktif,  silahkan hubungi IT Support!');
			};

			socket.onopen = function(e) {
				$("#icon_panggil").css("color", "#fff");
				console.log('Websocket connected!')
			};
		<?php endif; ?>

		var _form = {
			init: function() {

			},
			createExamination: function() {

				try {
					var data_post = {};
					data_post['patient'] = {};
					data_post['rj'] = {};
					data_post['diagnosis'] = {};
					data_post['service'] = {};
					data_post['service_component'] = {};
					data_post['service_consumable'] = {};
					data_post['vital'] = {};
					data_post['soap'] = {};
					data_post['nurse'] = {};
					data_post['helper'] = {};
					data_post['checkout'] = {};
					data_post['consult'] = {};
					data_post['odontogram'] = {};

					data_post['patient'] = {
						RiwayatAlergi: $('#RiwayatAlergi').val(),
						RiwayatPenyakit: $('#RiwayatPenyakit').val(),
						RiwayatObat: $('#RiwayatObat').val(),
					}

					data_post['rj'] = {
						NoBukti: $("#NoBukti").val(),
						RegNo: $("#RegNo").val(),
						JenisKerjasamaID: $("#JenisKerjasamaID").val(),
						SectionID: "<?php echo $item->SectionID; ?>",
						SectionAsalID: "<?php echo $item->SectionAsalID; ?>",
						DokterID: $("#DokterID").val(),
						Symptom: $("#Symptom").val(),
						Therapi: $("#Therapi").val(),
						Kecelakaan: $("#Kecelakaan").val(),
						DOA: $("#DOA:checked").val() || 0,
						DC: $("#DC:checked").val() || 0,
						DC_Hari: $("#DC_Hari").val() == 1 ? $("#DC_Hari").val() : '',
						KasusPertama: $("#KasusPertama:checked").val() || 0,
						Umur_Th: $("#UmurThn").val(),
						Umur_Bln: $("#UmurBln").val(),
						Umur_Hr: $("#UmurHr").val(),
						NRM: $("#NRM").val(),
						Gender: $("#JenisKelamin").val(),
						NamaPasien: $("#NamaPasien").val(),
						JenisKelamin: $("#JenisKelamin").val(),
						TglLahir: $("#TglLahir").val(),
						CustomerKerjasamaID: $("#CustomerKerjasamaID").val(),
						KdKelas: $("#KdKelas").val(),
						UserID: "<?php echo $user->User_ID ?>",
						TindakLanjutCekUpUlang: $("#TindakLanjutCekUpUlang:checked").val() || 0,
						TglCekUp: $("#TglCekUp").val(),
						TindakLanjut_KonsulMedik: $("#TindakLanjut_KonsulMedik:checked").val() || 0,
						TindakLanjut_RI: $("#TindakLanjut_RI:checked").val() || 0,
						Konsul_DOkterID: $("#TindakLanjut_RI").is(':checked') ? $("#Konsul_DOkterID").val() : '',
					}

					data_post['checkout'] = {
						PxKeluar_Pulang: $("#PxKeluar_Pulang:checked").val() || 0,
						PxKeluar_Dirujuk: $("#PxKeluar_Dirujuk:checked").val() || 0,
						PxKeluar_DirujukKeterangan: $("#PxKeluar_DirujukKeterangan").val(),
						PxMeninggal: $("#PxMeninggal:checked").val() || 0,
						MeninggalSblm48: $("#MeninggalSblm48:checked").val() || 0,
						MeninggalStl48: $("#MeninggalStl48:checked").val() || 0,
						MeninggalTgl: $("#Meninggal:checked").val() == 1 ? $("#MeninggalTgl").val() : '',
						MeninggalJam: $("#Meninggal:checked").val() == 1 ? $("#MeninggalJam").val() : '',
					}

					var dt_diagnosis = $("#dt_diagnosis").DataTable().rows().data();
					if (dt_diagnosis) {
						dt_diagnosis.each(function(value, index) {
							var detail = {
								NOBukti: $("#NoBukti").val(),
								KodeICD: value.KodeICD,
								Keterangan: '',
								Ditanggung: 1,
								NoKartu: $("#NoAnggota").val(),
								JenisKerjasamaID: $("#JenisKerjasamaID").val(),
							}

							data_post['diagnosis'][index] = detail;
						});
					}

					var dt_services = $("#dt_services").DataTable().rows().data();
					if (dt_services) {
						var service_component_temp = $("#service_component").data("component");
						var service_consumable_temp = $("#service_consumable").data("consumable");

						dt_services.each(function(value, index) {
							var detail = {
								NoBukti: $("#NoBukti").val(),
								JasaID: value.JasaID,
								DokterID: value.DokterID || "XX",
								KelasAsalID: "xx",
								KelasID: "xx",
								Titip: 0,
								ListHargaID: value.ListHargaID,
								Qty: value.Qty,
								Tarif: value.Tarif,
								Keterangan: $('#DocterName').val() || 'XX',
								UserID: value.User_id,
								NoKartu: $("#NoAnggota").val(),
								NRM: $("#NRM").val(),
								JenisKerjasamaID: $("#JenisKerjasamaID").val(),
								Waktu: "<?php echo date("Y-m-d H:i:s") ?> ",
								Jam: "<?php echo date("Y-m-d H:i:s") ?> ",
							}

							data_post['service'][index] = detail;

							if (service_component_temp[value.JasaID]) {
								// service component
								data_post['service_component'][value.JasaID] = {};
								$.each(service_component_temp[value.JasaID], function(key, val) {
									data_post['service_component'][value.JasaID][key] = {
										NoBukti: $("#NoBukti").val(),
										//Nomor : no_comp,
										JasaID: value.JasaID,
										KomponenID: val.KomponenID,
										Harga: val.HargaBaru,
										KelompokAkun: val.KelompokAkun,
										PostinganKe: val.PostinganKe,
										HargaOrig: val.HargaAwal,
										HargaAwal: val.HargaAwal,
										HargaAwalOrig: val.HargaAwal,
										HargaOrigMA: val.HargaAwal,
										ListHargaID: val.ListHargaID,
									}
								});
							}

							if (service_consumable_temp[value.JasaID]) {
								// service service_consumable
								data_post['service_consumable'][value.JasaID] = {};
								$.each(service_consumable_temp[value.JasaID], function(key, val) {
									data_post['service_consumable'][value.JasaID][key] = {
										NoBUkti: $("#NoBukti").val(),
										//Nomor : no_bhp,
										JasaID: value.JasaID,
										Barang_ID: val.Barang_ID,
										Satuan: val.Satuan,
										Qty: val.Qty,
										Disc: val.Disc,
										Harga: val.Harga,
										HPP: val.HPP,
										RI: 0,
										KelasID: "xx",
										PasienKTP: $("#PasienKTP").val(),
										Stok: val.Stok,
										Ditanggung: 1,
										JenisBarangId: 0,
										Qty_JasaID: 1,
										HargaOrig: val.HargaOrig,
									}
								});
							}
						});
					}

					data_post['vital'] = {
						IdVitalSigns: $('#vitalIdVitalSigns').val(),
						Height: $('#vitalHeight').val(),
						Weight: $('#vitalWeight').val(),
						Temperature: $('#vitalTemperature').val(),
						Systolic: $('#vitalSystolic').val(),
						Diastolic: $('#vitalDiastolic').val(),
						HeartRate: $('#vitalHeartRate').val(),
						RespiratoryRate: $('#vitalRespiratoryRate').val(),
						OxygenSaturation: $('#vitalOxygenSaturation').val(),
						Pain: $('#vitalPain').val(),
						lingkarPerut: $('#lingkarPerut').val(),
						Hpht: $('#Hpht').val(),
						Rwt_Menstruasi: $('#Rwt_Menstruasi').val(),
						Rwt_Kehamilan: $('#Rwt_Kehamilan').val(),
						Rwt_Persalinan_Sebelumnya: $('#Rwt_Persalinan_Sebelumnya').val(),
						Rwt_KB: $('#Rwt_KB').val(),
						BB_Lahir: $('#BB_Lahir').val(),
						lingkarKepala: $('#lingkarKepala').val(),
						Rwt_Kelahiran: $('#Rwt_Kelahiran').val(),
					};

					data_post['soap'] = {
						IdSOAPNotes: $('#soapIdSOAPNotes').val(),
						Subjective: $('#soapSubjective').val(),
						Objective: $('#soapObjective').val(),
						Assessment: $('#soapAssessment').val(),
						Plan: $('#soapPlan').val(),
						Tindakan: $('#soapTindakan').val(),
					};

					var dt_nurses = $("#dt_nurses").DataTable().rows().data();
					if (dt_nurses) {
						dt_nurses.each(function(value, index) {
							var detail = {
								NoBukti: $("#NoBukti").val(),
								PerawatID: value.Kode_Supplier,
								Kategori: 'Jaga',
							}

							data_post['nurse'][index] = detail;
						});
					}

					var dt_helpers = $("#dt_helpers").DataTable().rows().data();
					if (dt_helpers) {
						dt_helpers.each(function(value, index) {
							var detail = {
								NoBuktiHeader: $("#NoBukti").val(),
								NoBuktiMemo: value.NoBuktiMemo,
								DokterID: value.DokterID,
								SectionID: "<?php echo $item->SectionID; ?>",
								Tanggal: "<?php echo date("Y-m-d") ?>",
								Jam: "<?php echo date("Y-m-d H:i:s") ?> ",
								SectionTujuanID: value.SectionTujuanID,
								Memo: value.Memo,
								UserID: "<?php echo $user->User_ID ?>",
								NoReg: $("#RegNo").val(),
								JenisKerjasamaID: $("#JenisKerjasamaID").val(),
								UmurThn: $("#UmurThn").val(),
								UmurBln: $("#UmurBln").val(),
								UmurHr: $("#UmurHr").val(),
							}

							data_post['helper'][index] = detail;
						});
					}

					if ($("#TindakLanjut_KonsulMedik:checked").val() == 1) { // Jika Pasien di konsul medik ke section lain
						var dt_checkout = $("#dt_checkout").DataTable().rows().data();
						dt_checkout.each(function(value, index) {
							var detail = {
								NoBukti: $("#NoBukti").val(),
								NoReg: $("#RegNo").val(),
								SectionID: value.Konsul_SectionID,
								DokterID: value.Konsul_DOkterID,
								WaktuID: value.WaktuID,
								Waktu: value.Waktu,
								NoUrut: value.NoUrut,
								JenisKerjasamaID: $("#JenisKerjasamaID").val(),
								UmurThn: $("#UmurThn").val(),
								UmurBln: $("#UmurBln").val(),
								UmurHr: $("#UmurHr").val(),
								KelasID: "xx",
							}

							data_post['consult'][index] = detail;
						});
					}

					//jika ada odontogram
					var dt_odontogram = $("#dt_odontogram").DataTable().rows().data();
					if (dt_odontogram) {
						dt_odontogram.each(function(value, index) {
							var detail = {
								NoBukti: $("#NoBukti").val(),
								NoReg: $("#RegNo").val(),
								NRM: $("#NRM").val(),
								Kode_Supplier: $("#DokterID").val(),
								SectionID: "<?php echo $item->SectionID; ?>",
								Tooth: value.Tooth,
								Odontogram_ID: value.Odontogram_ID,
								Note: value.Note,
							}

							data_post['odontogram'][index] = detail;
						});
					}

					$.post($("form[name=\"form_poly\"]").attr("action"), data_post, function(response, status, xhr) {
						if ("error" == response.status) {
							$.alert_error(response.message);
							return false
						}
						if (!response.NoBukti) {
							$.alert_error("Proses simpan data Dihentikan karena Pasien ini sedang ada proses pembayaran di KASIR!!!.");
							return false
						}

						$.alert_success(response.message);
						if (typeof bpjsBridging !== 'undefined' && $('#JenisKerjasamaID').val() == 9) {
							bpjsCheckout.createCheckout(_form.afterPost);
						} else {
							_form.afterPost();
						}
					});

				} catch (e) {
					console.log(e);
				}
			},
			afterPost: function() {
				//refresh antrian di TV DISPLAY
				<?php if (config_item('use_websocket') == 'TRUE') : ?>
					socket.send('queue_refresh');
				<?php endif; ?>
				setTimeout(function() {
					location.reload();
					//document.location.href = "<?php echo base_url("{$nameroutes}"); ?>";
				}, 300);
			}
		}

		$(document).ready(function(e) {
			<?php if (@$item->ProsesPayment == 1 || (!empty($item->StatusBayar) && @$item->StatusBayar == "Sudah Bayar")) : ?>
				$("form[name=\"form_poly\"]").find("a[id^='add_'], .btn-remove").remove();
			<?php endif; ?>

			//JIKA KLIK TOMBOL PANGGIL ANTREAN
			<?php if (config_item('use_websocket') == 'TRUE') : ?>
				$("#queue_calling").on("click", function(e) {
					e.preventDefault();
					var NoReg = "<?php echo @$item->NoReg ?>";
					Section = "<?php echo @$item->SectionID ?>";

					$.alert_warning('Panggilan masuk dalam antrian!');
					socket.send(['queue_calling', NoReg, Section]);
					//CEK ANTRIAN SUDAH TAMPIL PADA TV DISPLAY / BELUM

					socket.onmessage = function(e) {
						var _response_display = e.data.split(',');
						// console.log(_response_display)
						if (_response_display[0] == "called_queue" && _response_display[1] == NoReg) {
							$.alert_success('Berhasil memanggil antrean!');
						}
					}

				})
			<?php endif; ?>

			$("form[name=\"form_poly\"]").on("submit", function(e) {
				e.preventDefault();

				// if (!response.NoBukti) {
				// 	$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
				// 	return false
				// }

				// VALIDASI BPJS
				// if ($('#vitalHeight').val() < 30 || $('#vitalHeight').val() > 250) {
				// 	$.alert_error("TINGGI BADAN HARUS DIATAS 30 DAN BIBAWAH 250 !!!")
				// 	return false
				// }
				// if ($('#vitalWeight').val() < 2 || $('#vitalWeight').val() > 300) {
				// 	$.alert_error("BERAT BADAN HARUS DIATAS 2 DAN BIBAWAH 300 !!!")
				// 	return false
				// }
				if ($('#vitalTemperature').val() < 25 || $('#vitalTemperature').val() > 45) {
					$.alert_error("SUHU TUBUH HARUS DIATAS 25 DAN BIBAWAH 45 !!!")
					return false
				}
				if ($('#vitalSystolic').val() < 40 || $('#vitalSystolic').val() > 250) {
					$.alert_error("TEKANAN DARAH SYSTOLIC HARUS DIATAS 40 DAN BIBAWAH 250 !!!")
					return false
				}
				if ($('#vitalDiastolic').val() < 30 || $('#vitalDiastolic').val() > 180) {
					$.alert_error("TEKANAN DARAH DIASTOLIC HARUS DIATAS 30 DAN BIBAWAH 180 !!!")
					return false
				}
				if ($('#vitalHeartRate').val() < 30 || $('#vitalHeartRate').val() > 160) {
					$.alert_error("DETAK JANTUNG HARUS DIATAS 30 DAN BIBAWAH 160 !!!")
					return false
				}
				if ($('#vitalRespiratoryRate').val() < 5 || $('#vitalRespiratoryRate').val() > 70) {
					$.alert_error("FREKUENSI PERNAPASAN HARUS DIATAS DAN BIBAWAH !!!")
					return false
				}
				if ($('#lingkarPerut').val() < 25 || $('#lingkarPerut').val() > 300) {
					$.alert_error("LINGKAR PERUT HARUS DIATAS 25 DAN BIBAWAH 300 !!!")
					return false
				}

				if (!confirm("<?php echo lang("poly:save_confirm_message") ?>")) {
					return false;
				}

				if ($("#TindakLanjut_RI").is(':checked') && $('#Konsul_DOkterID').val() == '') {
					$.alert_warning('Dokter Konsultasi Rawat Inap dibutuhkan');
					return false;
				}

				_form.createExamination();
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

		});

	})(jQuery);
	//]]>
</script>