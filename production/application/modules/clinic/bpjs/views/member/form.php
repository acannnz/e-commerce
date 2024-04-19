<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<div id="memberNumberBPJSArea">
	<?php echo form_input([
		'type' => 'hidden',
		'name' => 'BPJSMemberObject',
		'value' => '',
		'id' => 'BPJSMemberObject',
		'class' => 'member_bpjs',
	]); ?>
	<?php echo form_input([
		'type' => 'hidden',
		'name' => 'BPJSAktif',
		'value' => '',
		'id' => 'BPJSAktif',
		'class' => 'member_bpjs',
	]); ?>

	<?php echo form_input([
		'type' => 'hidden',
		'name' => 'BPJSKetAktif',
		'value' => '',
		'id' => 'BPJSKetAktif',
		'class' => 'member_bpjs',
	]); ?>
	<?php echo form_input([
		'type' => 'hidden',
		'name' => 'NIKAktif',
		'value' => '',
		'id' => 'NIKAktif',
		'class' => 'member_NIK',
	]); ?>

	<?php echo form_input([
		'type' => 'hidden',
		'name' => 'NIKKetAktif',
		'value' => '',
		'id' => 'NIKKetAktif',
		'class' => 'member_NIK',
	]); ?>
	<div class="form-group">
		<?php echo form_input([
			'type' => 'text',
			'name' => 'f[NoAnggota]',
			'value' => set_value('f[NoAnggota]', @$mapping->code, TRUE),
			'id' => 'NoAnggota',
			'maxlength' => 13,
			'class' => 'form-control member_bpjs',
			'title' => 'Nomor BPJS',
			'placeholder' => 'Nomor BPJS',
		]); ?>
	</div>
	<?php echo form_input([
		'type' => 'text',
		'value' => set_value('f[NoAnggotaNIK]', @$mapping->code, TRUE),
		'id' => 'NoAnggotaNIK',
		'maxlength' => 16,
		'class' => 'form-control member_bpjs',
		'title' => 'Nomor NIK',
		'placeholder' => 'Nomor NIK',
	]); ?>
	<!-- <p id="BPJSInfo"></p>
	<p id="NIKInfo"></p> -->
</div>
<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var bpjsMemberActions = {
			init: function() {

				var checkBPJSIntertval = 0;
				var checkNIKIntertval = 0;

				var noAnggota = $("#memberNumberBPJSArea").find("#NoAnggota"),
					noAnggotaNIK = $("#memberNumberBPJSArea").find("#NoAnggotaNIK")
				jenisKerjasama = $("#JenisKerjasamaID");

				noAnggota.data("value", noAnggota.val());
				noAnggotaNIK.data("value", noAnggotaNIK.val());
				if (noAnggota.val().length == 13) {
					console.log(noAnggota);
					checkMemberBPJS(noAnggota.val());
				}

				if (noAnggotaNIK.val().length == 16) {
					checkMemberNIK(noAnggotaNIK.val());
				}

				checkNIKIntertval = setInterval(startCheckNIKIntertval, 100);
				checkBPJSIntertval = setInterval(startCheckBPJSIntertval, 100);

				function startCheckBPJSIntertval() {
					var noAnggotaData = noAnggota.data("value"),
						noAnggotaVal = noAnggota.val(),
						jenisKerjasamaData = jenisKerjasama.data("value"),
						jenisKerjasamaVal = jenisKerjasama.val();

					if (jenisKerjasamaData !== jenisKerjasamaVal) {
						jenisKerjasama.data("value", jenisKerjasama.val());

						if (jenisKerjasamaVal == 9) {
							$('#memberNumberIKSArea').hide();
							$('#memberNumberIKSArea').find('#NoAnggota').prop('disabled', true);

							$('#memberNumberBPJSArea').show();
							$('#memberNumberBPJSArea').find('#NoAnggota').prop('disabled', false);
						} else {
							$('#memberNumberBPJSArea').hide();
							$('#memberNumberBPJSArea').find('#NoAnggota').prop('disabled', true);

							$('#memberNumberIKSArea').show();
							$('#memberNumberIKSArea').find('#NoAnggota').prop('disabled', false);
						}
					}

					if (noAnggotaVal.length == 13 && noAnggotaData !== noAnggotaVal && jenisKerjasamaVal == 9) {
						noAnggota.data("value", noAnggotaVal);
						checkMemberBPJS(noAnggotaVal);
					}
				}

				function startCheckNIKIntertval() {
					// console.log('asdas');

					var noAnggotaNIKData = noAnggotaNIK.data("value"),
						noAnggotaNIKVal = noAnggotaNIK.val(),
						jenisKerjasamaData = jenisKerjasama.data("value"),
						jenisKerjasamaVal = jenisKerjasama.val();

					if (noAnggotaNIKVal.length == 16 && noAnggotaNIKData !== noAnggotaNIKVal && jenisKerjasamaVal == 9) {
						noAnggotaNIK.data("value", noAnggotaNIKVal);
						checkMemberNIK(noAnggotaNIKVal);
					}
				}

				function stopCheckBPJSInterval() {
					clearInterval(checkBPJSIntertval);
				}

				function checkMemberBPJS(memberNumber) {
					$.ajax({
						url: "<?php echo config_item('bpjs_api_baseurl') . "/member/bpjs/" ?>" + memberNumber,
						type: "GET",
						dataType: 'json',
						beforeSend: function(request) {
							request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');

							$('#BPJSAktif').val('');
							$('#BPJSKetAktif').val('');
							$('#BPJSInfo').html('<?php echo lang('global:ajax_loading') ?>');
							$('#BPJSInfo').removeAttr('class');
							$('#BPJSInfo').data('bpjs', '');
							$('#BPJSInfo').data('bpjsnourut', '');
						}
					}).done(function(response) {

						if (response.status == false) {
							$.alert_error('Data member BPJS tidak ditemukan');
							$('#BPJSInfo').html('Data member BPJS tidak ditemukan');
							$('#BPJSInfo').addClass('text-danger');
							return false;
						}

						if (response.data.aktif == false) {
							$.alert_error('Status member BPJS : Tidak Aktif, ' + response.data.ketAktif);
							$('#BPJSInfo').html('BPJS Tidak Aktif, ' + response.data.ketAktif);
							$('#BPJSInfo').addClass('text-danger');
							return false;
						}

						$('#BPJSAktif').val(response.data.aktif);
						$('#BPJSKetAktif').val(response.data.ketAktif);
						$('#BPJSMemberObject').data('bpjs', response.data);

						if ($('#NoIdentitas').val() == '') {
							$('#NoIdentitas').val(response.data.noKTP);
						}

						if ($('#Phone').val() == '') {
							$('#Phone').val(response.data.noHP);
						}

						if ($('#NamaPasien').val() == '') {
							$('#NamaPasien').val(response.data.nama);
						}

						if ($('#JenisKelamin').val() == '') {
							if (response.data.sex == 'L') {
								$('#JenisKelamin').val('F');
							} else {
								$('#JenisKelamin').val('M');
							}
						}

						if ($('#TglLahir').val() == '') {
							_tglLahir = response.data.tglLahir.split("-").reverse().join("-");
							$('#TglLahir').val(_tglLahir);
							getAge(_tglLahir);
						}

						$.alert_success('Status member BPJS : ' + response.data.ketAktif + '<br>' + 'Nama Provider : ' + response.data.kdProviderPst.nmProvider + '<br>' + response.data.jnsKelas.nama);
						$('#BPJSInfo').html('BPJS ' + response.data.ketAktif);
						$('#BPJSInfo').addClass('text-success');

					}).fail(function() {
						$.alert_error('Terjadi kesalahan dengan server BPJS');
						$('#BPJSInfo').html('Terjadi kesalahan dengan server BPJS');
						$('#BPJSInfo').addClass('text-danger');
					})
				}

				function checkMemberNIK(memberNumber) {
					$.ajax({
						url: "<?php echo config_item('bpjs_api_baseurl') . "/member/nik/" ?>" + memberNumber,
						type: "GET",
						dataType: 'json',
						beforeSend: function(request) {
							request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');

							$('#NIKAktif').val('');
							$('#NIKKetAktif').val('');
							$('#NIKInfo').html('<?php echo lang('global:ajax_loading') ?>');
							$('#NIKInfo').removeAttr('class');
							$('#NIKInfo').data('NIK', '');
							$('#NIKInfo').data('NIKnourut', '');
						}
					}).done(function(response) {

						if (response.status == false) {
							$.alert_error('Data member NIK tidak ditemukan');
							$('#NIKInfo').html('Data member NIK tidak ditemukan');
							$('#NIKInfo').addClass('text-danger');
							return false;
						}

						if (response.data.aktif == false) {
							$.alert_error('Status member NIK : Tidak Aktif, ' + response.data.ketAktif);
							$('#NIKInfo').html('NIK Tidak Aktif, ' + response.data.ketAktif);
							$('#NIKInfo').addClass('text-danger');
							return false;
						}

						$('#BPJSAktif').val(response.data.aktif);
						$('#BPJSKetAktif').val(response.data.ketAktif);
						$('#BPJSMemberObject').data('bpjs', response.data);

						if ($('#NoIdentitas').val() == '') {
							$('#NoIdentitas').val(response.data.noKTP);
						}

						if ($('#Phone').val() == '') {
							$('#Phone').val(response.data.noHP);
						}

						if ($('#NamaPasien').val() == '') {
							$('#NamaPasien').val(response.data.nama);
						}

						if ($('#JenisKelamin').val() == '') {
							if (response.data.sex == 'L') {
								$('#JenisKelamin').val('F');
							} else {
								$('#JenisKelamin').val('M');
							}
						}

						if ($('#TglLahir').val() == '') {
							_tglLahir = response.data.tglLahir.split("-").reverse().join("-");
							$('#TglLahir').val(_tglLahir);
							getAge(_tglLahir);
						}

						$.alert_success('Status member NIK : ' + response.data.ketAktif + '<br>' + 'Nama Provider : ' + response.data.kdProviderPst.nmProvider + '<br>' + response.data.jnsKelas.nama);
						$('#NIKInfo').html('NIK ' + response.data.ketAktif);
						$('#NIKInfo').addClass('text-success');

					}).fail(function() {
						$.alert_error('Terjadi kesalahan dengan server BPJS');
						$('#NIKInfo').html('Terjadi kesalahan dengan server BPJS');
						$('#NIKInfo').addClass('text-danger');
					})
				}


				function getAge(dateString) {
					console.log(dateString);
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

					if (monthNow >= monthDob) {
						var monthAge = monthNow - monthDob;
					} else {
						yearAge--;
						var monthAge = 12 + monthNow - monthDob;
					}

					if (dateNow >= dateDob) {
						var dateAge = dateNow - dateDob;
					} else {
						monthAge--;
						var dateAge = 31 + dateNow - dateDob;

						if (monthAge < 0) {
							monthAge = 11;
							yearAge--;
						}
					}

					$("#UmurThn").val(yearAge);
					$("#UmurBln").val(monthAge);
					$("#UmurHr").val(dateAge);

				}
			}
		};



		$(document).ready(function(e) {
			bpjsMemberActions.init();

		});
	})(jQuery);
	//]]>
</script>