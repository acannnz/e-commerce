<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>

<input type="hidden" id="tglEstRujuk" name="tglEstRujuk">
<input type="hidden" id="kdppk" name="kdppk">

<input type="hidden" id="spesialis" name="spesialis" value="0">
<input type="hidden" id="kdSpesialis" name="kdSpesialis">
<input type="hidden" id="kdSubSpesialis1" name="kdSubSpesialis1">
<input type="hidden" id="kdSarana" name="kdSarana">

<input type="hidden" id="khusus" name="khusus" value="0">
<input type="hidden" id="kdKhusus" name="kdKhusus">
<input type="hidden" id="kdSubSpesialis" name="kdSubSpesialis">
<input type="hidden" id="catatan" name="catatan">


<script type="text/javascript">
//<![CDATA[
	var bpjsBridging = true;	
	var bpjsCheckout = {
		createCheckout: function( fn = false ){
			
			if($('#PxKeluar_Dirujuk').is(':checked'))
			{
				if($('#spesialis').val() == 1){
					var postData = {
						CheckoutState : 4,
						CheckoutReferralDestination : $('#kdppk').val(),
						CheckoutReferralCondition : 'spesialis',
						CheckoutReferralDate : $('#tglEstRujuk').val(),
						CheckoutReferralSpecialist : $('#spesialis').val(),
						CheckoutReferralSubSpecialist : $('#kdSubSpesialis1').val(),
						CheckoutReferralAcomodation : $('#kdSarana').val(),
					}
				}else if($('#khusus').val() == 1){
					var postData = {
						CheckoutState : 4,
						CheckoutReferralDestination : $('#kdppk').val(),
						CheckoutReferralCondition : 'khusus',
						CheckoutReferralDate : $('#tglEstRujuk').val(),
						CheckoutReferralSpecialist : $('#kdKhusus').val(),
						CheckoutReferralSubSpecialist : $('#kdSubSpesialis').val(),
						CheckoutReferralNote : $('#catatan').val()
					}
				}
			}
			else if($('#PxKeluar_Pulang').is(':checked')){
				var postData = {
					CheckoutState : 3,
				}
			}else if($('#PxMeninggal').is(':checked')){
				var postData = {
					CheckoutState : 1,
				}
			}
			
			$.ajax({
				url: "<?php echo $create_url ?>",
				type: "POST",
				data: {f : postData},
				dataType: 'json',
			}).done(function(response) {

				if(response.status == false){
					$.alert_error(response.message);
					return false;							
				}
				
				$.alert_success(response.message);
				
				if($.isFunction(fn))
				{
					fn();
				}
											
			}).fail(function() {
				$.alert_error('Terjadi kesalahan dengan Sistem, ketika simpan checkout Kunjungan');
				_btn_process.removeClass('disabled');
			});
		}
	}
//]]>
</script>
