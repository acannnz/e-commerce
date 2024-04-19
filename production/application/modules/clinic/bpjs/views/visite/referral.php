<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>

<input type="hidden" id="tglEstRujuk" name="tglEstRujuk" value="<?= !empty(@$data->CheckoutReferralDate) ? DateTime::createFromFormat('Y-m-d', @$data->CheckoutReferralDate)->format('d-m-Y') : '' ?>">
<input type="hidden" id="kdppk" name="kdppk" value="<?= !empty(@$data->CheckoutReferralDestination) ? @$data->CheckoutReferralDestination : '' ?>">

<input type="hidden" id="spesialis" name="spesialis" value="<?= @$data->CheckoutReferralCondition == 'spesialis' ? 1 : 0 ?>">
<input type="hidden" id="kdSpesialis" name="kdSpesialis" value="<?= !empty(@$data->CheckoutReferralkdSpesialis) ? @$data->CheckoutReferralkdSpesialis : '' ?>">
<input type="hidden" id="kdSubSpesialis1" name="kdSubSpesialis1" value="<?= !empty(@$data->CheckoutReferralSubSpecialist) ? @$data->CheckoutReferralSubSpecialist : '' ?>">
<input type="hidden" id="kdSarana" name="kdSarana" value="<?= !empty(@$data->CheckoutReferralAcomodation) ? @$data->CheckoutReferralAcomodation : '' ?>">

<input type="hidden" id="khusus" name="khusus" value="<?= @$data->CheckoutReferralCondition == 'khusus' ? 1 : 0 ?>">
<input type="hidden" id="kdKhusus" name="kdKhusus" value="<?= @$data->CheckoutReferralCondition == 'khusus' ? @$data->CheckoutReferralSpecialist : '' ?>">
<input type="hidden" id="kdSubSpesialis" name="kdSubSpesialis" value="<?= @$data->CheckoutReferralCondition == 'khusus' ? @$data->CheckoutReferralSubSpecialist : '' ?>">
<input type="hidden" id="catatan" name="catatan" value="<?= !empty(@$data->CheckoutReferralNote) ? @$data->CheckoutReferralNote : '' ?>">


<script type="text/javascript">
	//<![CDATA[
	var bpjsBridging = true;
	var bpjsCheckout = {
		createCheckout: function(fn = false) {

			if ($('#PxKeluar_Dirujuk').is(':checked')) {
				if ($('#spesialis').val() == 1) {
					var postData = {
						CheckoutState: 4,
						CheckoutReferralDestination: $('#kdppk').val(),
						CheckoutReferralCondition: 'spesialis',
						CheckoutReferralDate: $('#tglEstRujuk').val(),
						CheckoutReferralSpecialist: $('#spesialis').val(),
						CheckoutReferralkdSpesialis: $('#kdSpesialis').val(),
						CheckoutReferralSubSpecialist: $('#kdSubSpesialis1').val(),
						CheckoutReferralAcomodation: $('#kdSarana').val(),
					}
				} else if ($('#khusus').val() == 1) {
					var postData = {
						CheckoutState: 4,
						CheckoutReferralDestination: $('#kdppk').val(),
						CheckoutReferralCondition: 'khusus',
						CheckoutReferralDate: $('#tglEstRujuk').val(),
						CheckoutReferralSpecialist: $('#kdKhusus').val(),
						CheckoutReferralSubSpecialist: $('#kdSubSpesialis').val(),
						CheckoutReferralNote: $('#catatan').val()
					}
				}
			} else if ($('#PxKeluar_Pulang').is(':checked')) {
				var postData = {
					CheckoutState: 3,
				}
			} else if ($('#PxMeninggal').is(':checked')) {
				var postData = {
					CheckoutState: 1,
				}
			}

			$.ajax({
				url: "<?php echo $create_url ?>",
				type: "POST",
				data: {
					f: postData
				},
				dataType: 'json',
			}).done(function(response) {

				if (response.status == false) {
					$.alert_error(response.message);
					return false;
				}

				$.alert_success(response.message);

				if ($.isFunction(fn)) {
					fn();
				}

			}).fail(function() {
				$.alert_error('Terjadi kesalahan dengan Sistem, ketika simpan checkout Kunjungan');
				// _btn_process.removeClass('disabled');
			});
		}
	}
	//]]>
</script>