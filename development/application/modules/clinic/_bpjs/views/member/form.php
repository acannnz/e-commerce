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
			'type' => 'text',
			'name' => 'f[NoAnggota]',
			'value' => set_value('f[NoAnggota]', @$mapping->code, TRUE),
			'id' => 'NoAnggota',
			'maxlength' => 13,
			'class' => 'form-control member_bpjs',
		]); ?>
	<p id="BPJSInfo"></p>
</div>				
<script type="text/javascript">
//<![CDATA[
(function( $ ){				
		var bpjsMemberActions = {
				init: function(){
					
					var checkBPJSIntertval = 0;		
						
					var noAnggota = $("#memberNumberBPJSArea").find("#NoAnggota"),
						jenisKerjasama = $("#JenisKerjasamaID");
					
					noAnggota.data("value", noAnggota.val());
					
					if(noAnggota.val().length == 13){
						checkMemberBPJS(noAnggota.val());
					}								
					
					checkBPJSIntertval = setInterval(startCheckBPJSIntertval, 100);
					
					function startCheckBPJSIntertval() {
						var noAnggotaData = noAnggota.data("value"),
							noAnggotaVal = noAnggota.val(),
							jenisKerjasamaData = jenisKerjasama.data("value"),
							jenisKerjasamaVal = jenisKerjasama.val();
						
						if(jenisKerjasamaData !== jenisKerjasamaVal){
							jenisKerjasama.data("value", jenisKerjasama.val());
							
							if(jenisKerjasamaVal == 9){
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
						
						if(noAnggotaVal.length == 13 && noAnggotaData !== noAnggotaVal && jenisKerjasamaVal == 9){
							//stopCheckBPJSInterval();
							noAnggota.data("value", noAnggotaVal);
							checkMemberBPJS(noAnggotaVal);
						}
					}
					
					function stopCheckBPJSInterval(){
						clearInterval(checkBPJSIntertval);
					}
					
					function checkMemberBPJS(memberNumber){
						$.ajax({
							url: "<?php echo config_item('bpjs_api_baseurl')."/member/bpjs/" ?>"+ memberNumber,
							type: "GET",
							dataType: 'json',
							beforeSend: function( request ) {
								request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
								
								$('#BPJSAktif').val('');
								$('#BPJSKetAktif').val('');
								$('#BPJSInfo').html('<?php echo lang('global:ajax_loading')?>');
								$('#BPJSInfo').removeAttr('class');
								$('#BPJSInfo').data('bpjs', '');
								$('#BPJSInfo').data('bpjsnourut', '');
							}
						}).done(function(response) {
							
							if(response.status == false){
								$.alert_error('Data member BPJS tidak ditemukan');
								$('#BPJSInfo').html('Data member BPJS tidak ditemukan');
								$('#BPJSInfo').addClass('text-danger');
								return false;
							}
							
							if(response.data.aktif ==  false ){
								$.alert_error('Status member BPJS : Tidak Aktif, '+ response.data.ketAktif);
								$('#BPJSInfo').html('BPJS Tidak Aktif, '+ response.data.ketAktif);
								$('#BPJSInfo').addClass('text-danger');
								return false;
							}
							
							$('#BPJSAktif').val(response.data.aktif);
							$('#BPJSKetAktif').val(response.data.ketAktif);
							$('#BPJSMemberObject').data('bpjs', response.data);
							
							$.alert_success('Status member BPJS : '+ response.data.ketAktif);
							$('#BPJSInfo').html('BPJS '+ response.data.ketAktif + ", Faskes <b>"+ response.data.kdProviderPst.nmProvider +"</b>");
							$('#BPJSInfo').addClass('text-success');
							
						}).fail(function() {
							$.alert_error('Terjadi kesalahan dengan server BPJS');
							$('#BPJSInfo').html('Terjadi kesalahan dengan server BPJS');
							$('#BPJSInfo').addClass('text-danger');
						})
					}
					
					/*var inputTimer = 0;
					$('#memberNumberBPJSArea').find('#NoAnggota').on('keyup paste', function(e) {
						if(noAnggota.val() == noAnggota.data("value"))
							return;
							
						if (inputTimer)
							clearTimeout(inputTimer);
						
						if(e.target.value.length == 13)	
							inputTimer = setTimeout(startCheckBPJSIntertval, 600); 
					});*/
				}
			};
			
		
				
		$( document ).ready(function(e) {
				bpjsMemberActions.init();
				
			});
	})( jQuery );
//]]>
</script>
