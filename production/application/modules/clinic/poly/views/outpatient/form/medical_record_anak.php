<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php
echo form_hidden('v[IdVitalSigns]', @$vital->IdVitalSigns);
echo form_hidden('soap[IdSOAPNotes]', @$soap->IdSOAPNotes);
?>
<input type="hidden" id="vitalIdVitalSigns" name="v[IdVitalSigns]" value="<?php echo @$vital->IdVitalSigns ?>" />
<input type="hidden" id="soapIdSOAPNotes" name="v[IdSOAPNotes]" value="<?php echo @$soap->IdSOAPNotes ?>" />
<div class="row">
	<div class="col-md-6">
		<div class="row">
			<div class="page-subtitle ">
				<i class="fa fa-heartbeat pull-left text-info"></i>
				<h3 class="text-info">Informasi Rekam Medis</h3>
				<p><?php echo 'Silakan lengkapi Informasi Rekam Medis Pasien' ?></p>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-6">
				<div class="page-subtitle ">
					<i class="fa fa-file-text-o pull-left text-info"></i>
					<h3 class="text-info">Informasi SOAP Pasien</h3>
					<p><?php echo 'Silakan lengkapi Informasi SOAP Pasien' ?></p>
				</div>
			</div>
			<div class="col-md-6">
				<div class="col-md-6">
					<a href="<?php echo @$lookup_drug_history ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-history"></i> Riwayat Resep</b></a>
				</div>
				<div class="col-md-6">
					<a href="<?php echo @$lookup_soap_history ?>" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-history"></i> Riwayat SOAP</b></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Catatan Pasien</label>
					<textarea id="CatatanPatient" name="p[CatatanPatient]" placeholder="" class="form-control" <?php echo !empty(@$item->Keterangan) ? 'style="color:white; background-color:red; opacity: 0.6"' : '' ?>><?php echo @$item->Keterangan ?></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Riwayat Alergi</label>
					<textarea id="RiwayatAlergi" name="p[RiwayatAlergi]" placeholder="" class="form-control"><?php echo @$item->RiwayatAlergi ?></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Riwayat Penyakit</label>
					<textarea id="RiwayatPenyakit" name="f[RiwayatPenyakit]" placeholder="" class="form-control"><?php echo @$item->RiwayatPenyakit ?></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Riwayat Obat</label>
					<textarea id="RiwayatObat" name="f[RiwayatObat]" placeholder="" class="form-control"><?php echo @$item->RiwayatObat ?></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Tinggi Badan</label>
					<div class="form-group input-group">
						<input type="number" id="vitalHeight" name="v[Height]" value="<?php echo @$vital->Height != 0 ? @$vital->Height : 0 ?>" placeholder="" class="form-control">
						<span class="input-group-addon help-block">CM</span>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Berat Badan</label>
					<div class="form-group input-group">
						<input type="number" step="any" id="vitalWeight" name="v[Weight]" value="<?php echo @$vital->Weight != 0 ? @$vital->Weight : 0 ?>" placeholder="placeholder" class="form-control">
						<span class="input-group-addon help-block">KG</span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Suhu Tubuh</label>
					<div class="form-group input-group">
						<input min="25" max="45" type="number" step="any" id="vitalTemperature" name="v[Temperature]" value="<?php echo @$vital->Temperature != 0 ? @$vital->Temperature : 36  ?>" placeholder="" class="form-control">
						<span class="input-group-addon help-block">C<sup>o</sup></span>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Tekanan Darah</label>
					<div class="form-group input-group">
						<input min="40" max="250" type="number" id="vitalSystolic" name="v[Systolic]" value="<?php echo @$vital->Systolic != 0 ? @$vital->Systolic : 120;  ?>" step="0.01" placeholder="" class="form-control" />
						<span class="input-group-addon">/</span>
						<input min="30" max="180" type="number" id="vitalDiastolic" name="v[Diastolic]" value="<?php echo @$vital->Diastolic != 0 ? @$vital->Diastolic : 74; ?>" step="0.01" placeholder="" class="form-control" />
						<span class="input-group-addon help-block">MM/HG</span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Detak Jantung per Menit</label>
					<div class="form-group input-group">
						<input min="30" max="160" type="number" id="vitalHeartRate" name="v[HeartRate]" value="<?php echo @$vital->HeartRate != 0 ? @$vital->HeartRate : 60; ?>" placeholder="" class="form-control">
						<span class="input-group-addon help-block">BPM</span>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Frekuensi Pernapasan</label>
					<div class="form-group input-group">
						<input min="5" max="70" type="number" id="vitalRespiratoryRate" name="v[RespiratoryRate]" value="<?php echo @$vital->RespiratoryRate != 0 ? @$vital->RespiratoryRate : 20 ?>" placeholder="" class="form-control">
						<span class="input-group-addon help-block">RPM</span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Saturasi Oksigen (SATS)</label>
					<div class="form-group input-group">
						<input type="number" id="vitalOxygenSaturation" name="v[OxygenSaturation]" value="<?php echo @$vital->OxygenSaturation != 0 ? @$vital->OxygenSaturation : 99 ?>" placeholder="" class="form-control">
						<span class="input-group-addon help-block"> % </span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Lingkar Perut</label>
					<div class="form-group input-group">
						<input min="25" max="300" type="number" id="lingkarPerut" name="v[lingkarPerut]" value="<?php echo @$vital->lingkarPerut != 0 ? @$vital->lingkarPerut : 99 ?>" placeholder="" class="form-control">
						<span class="input-group-addon help-block"> CM </span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Skala Nyeri</label>
					<div class="form-group input-group">
						<select id="vitalPain" name="v[Pain]" class="form-control">
							<?php $i = 0;
							while ($i <= 10) : ?>
								<option value="<?php echo $i ?>" <?php echo (@$vital->Pain == $i) ? 'selected' : NULL ?>><?php echo $i ?></option>
							<?php $i++;
							endwhile; ?>
						</select>
						<span class="input-group-addon help-block">0-10</span>
					</div>
				</div>
			</div>
		</div>
		<!-- =================== DOKTER ANAK -->
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="form-group">
						<div class="page-subtitle" style="margin-bottom: 0px!important;">
							<i class="fa fa-stethoscope pull-left text-info" style="font-size: 25px!important;"></i>
							<h3 class="text-info">Dokter Anak</h3>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label><?php echo 'Tanggal Lahir' ?></label>
						<div class="input-group">
							<span class="input-group-btn">
								<a href="javascript:;" id="clear_patient" class="btn btn-default"><i class="fa fa-calendar"></i></a>
							</span>
							<input type="text" id="TglLahir" name="f[TglLahir]" value="<?php echo @$item->TglLahir ?>" placeholder="" class="form-control datepicker patient" readonly>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label><?php echo "Umur" ?></label>
						<div class="input-group">
							<input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" class="form-control text-right patient" readonly>
							<span class="input-group-addon help-block" style="padding:3px 6px !important">Tahun</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>BERAT BADAN LAHIR</label>
						<div class="form-group input-group">
							<input type="text" id="BB_Lahir" name="v[BB_Lahir]" value="<?php echo @$vital->BB_Lahir ?>" placeholder="" class="form-control">
							<span class="input-group-addon help-block">KG</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>LINGKAR KEPALA</label>
						<div class="form-group input-group">
							<input type="text" id="lingkarKepala" name="v[lingkarKepala]" value="<?php echo @$vital->lingkarKepala ?>" placeholder="" class="form-control">
							<span class="input-group-addon help-block">CM</span>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>RIWAYAT KELAHIRAN</label>
						<textarea id="Rwt_Kelahiran" name="v[Rwt_Kelahiran]" placeholder="" class="form-control"><?php echo @$vital->Rwt_Kelahiran ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<a name="paste-vital-sign-before" id="paste-vital-sign-before" class="btn btn-primary btn-block">Tempel Pemeriksaan Fisik (O)</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Subjektif</label>
					<div class="form-group">
						<textarea id="soapSubjective" name="soap[Subjective]" placeholder="" rows="3" class="form-control"><?php echo @$soap->Subjective ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Objektif</label>
					<div class="form-group">
						<textarea id="soapObjective" name="soap[Objective]" placeholder="" rows="3" class="form-control"><?php echo @$soap->Objective ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Assesmen</label>
					<div class="form-group">
						<textarea id="soapAssessment" name="soap[Assessment]" placeholder="" rows="3" class="form-control"><?php echo @$soap->Assessment ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Perencanaan</label>
					<div class="form-group">
						<textarea id="soapPlan" name="soap[Plan]" placeholder="" rows="3" class="form-control"><?php echo @$soap->Plan ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">Tindakan</label>
					<div class="form-group">
						<textarea id="soapTindakan" name="soap[Tindakan]" placeholder="" rows="2" class="form-control"><?php echo @$soap->Tindakan ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<?php echo modules::run("poly/outpatients/diagnosis/index", @$NoBukti) ?>
	</div>
</div>


<pre id="output_div" style="display: none;">

</pre>
<script type="text/javascript">
	function soapObjective() {
		var TglLahir = $('#TglLahir').val();
		UmurThn = $('#UmurThn').val();
		BB_Lahir = $('#BB_Lahir').val();
		lingkarKepala = $('#lingkarKepala').val();
		vitalHeight = $('#vitalHeight').val();
		vitalTemperature = $('#vitalTemperature').val();
		Rwt_Kelahiran = $('#Rwt_Kelahiran').val();

		$("#output_div").html(`Status Generalis (diisi dokter) :
- Tanggal Lahir/Umur : ${TglLahir} / ${UmurThn} Tahun
- Berat Badan Lahir : ${BB_Lahir}
- Lingkar Kepala : ${lingkarKepala}
- Panjang/Tinggi Badan : ${vitalHeight}
- Temperatur : ${vitalTemperature}
- Riwayat Kelahiran : ${Rwt_Kelahiran}

`);


		$("#soapObjective").val($("#output_div").html());
	}

	$("#paste-vital-sign-before").click(function() {
		soapObjective()
	});
</script>