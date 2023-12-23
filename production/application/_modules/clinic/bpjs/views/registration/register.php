<script type="text/javascript">
//<![CDATA[
		var bpjsBridgingRegistration = true;
		var reg = <?php print_r(json_encode(@$reg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));?>;
		var state = {
			addRegistration : {status: false, data:[]},
			saveRegistration : {status: false},
			removeRegistration : {status: false},
			deleteRegistration : {status: false}
		};
		
		var _prepare_insert = {};
		var bpjsAddRegistration = {
				post: function(dataPost){
					
						var bpjsMember = $("#BPJSMemberObject").data('bpjs');
						var bpjsNoUrut = $("#BPJSMemberObject").data('bpjsnourut');
						
						$.alert_warning('Mohon jangan tutup halaman, sedang proses Add Pendaftaran BPJS!');
						
						if(bpjsNoUrut !== '' && bpjsNoUrut !== null && typeof bpjsNoUrut !== 'undefined'){
							$.alert_success('Tambah pendaftaran BPJS sudah dilakukan');
							bpjsAddRegistration.saveRegistration(_prepare_insert);
							return false;
						}
						
						if(state.addRegistration.status === true){
							$.alert_success('Tambah pendaftaran BPJS sudah dilakukan');
							bpjsAddRegistration.saveRegistration(_prepare_insert);
							return false;
						}
								
						var sectionDestination = $( "#dt_registration_section" ).DataTable().row(0).data();
						
						if(bpjsMember == '' && bpjsMember == null && typeof bpjsMember === 'undefined'){
							$.alert_error('Data anggota BPJS tidak sesuai');
							return false;
						}
						
						if(sectionDestination.SectionIDBPJS === '' && sectionDestination.SectionIDBPJS === null && typeof sectionDestination.SectionIDBPJS === 'undefined'){
							$.alert_error(sectionDestination.SectionName +' tidak ditanggung BPJS');
							return false;
						}
						
						if(sectionDestination.Kode_Supplier_BPJS === '' && sectionDestination.Kode_Supplier_BPJS === null && typeof sectionDestination.Kode_Supplier_BPJS === 'undefined'){
							$.alert_error('Dokter '+ sectionDestination.Nama_Supplier +' tidak ditanggung BPJS');
							return false;
						}
						
						$.ajax({
							url: "<?php echo $add_url ?>",
							type: "POST",
							data: {
								kdProviderPeserta: bpjsMember.kdProviderPst.kdProvider,
								tglDaftar: '<?php echo date('d-m-Y') ?>',
								noKartu: bpjsMember.noKartu,
								kdPoli: sectionDestination.SectionIDBPJS,
								keluhan: null,
								kunjSakit: true,
								sistole: $('#vitalSystolic').val() || 0,
								diastole: $('#vitalDiastolic').val() || 0,
								beratBadan: $('#vitalWeight').val() || 0,
								tinggiBadan: $('#vitalHeight').val() || 0,
								respRate: $('#vitalRespiratoryRate').val() || 0,
								heartRate: $('#vitalHeartRate').val() || 0,
								rujukBalik: 0,
								kdTkp: $('#TipePelayanan').val() == 'RawatJalan' ? 10 : 20,
							},
							dataType: 'json',
							beforeSend: function( request ) {
								request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
							}
						}).done(function(response) {
							
							if(response.status == false){
								$.alert_error('Tambah pendaftaran BPJS gagal : '+ response.message);
								return false;
							}
							
							$.alert_success('Tambah pendaftaran BPJS berhasil : '+ response.message);
							state.addRegistration.status = true;
							state.addRegistration.data = response.data;
							
							$('#BPJSMemberObject').data('bpjsnourut', response.data.noUrut);		
							_prepare_insert = {
								NoReg : dataPost['NoReg'],
								NoUrut : response.data.noUrut,
								NoKartu : bpjsMember.noKartu || reg.NoKartu,
								SectionID : sectionDestination.SectionID,
								SectionIDIntegrasi: sectionDestination.SectionIDBPJS,
								DokterID : sectionDestination.DokterID,
								DokterIDIntegrasi : sectionDestination.Kode_Supplier_BPJS,
								APITYPE : '<?php echo config_item('bpjs_api_type')?>'
							};				
																			
							bpjsAddRegistration.saveRegistration(_prepare_insert);
							
						}).fail(function() {
							$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Add Pendaftaran');
						});
					},
				saveRegistration: function(_prepare_insert = ''){
						if(_prepare_insert === '' && _prepare_insert === null && _prepare_insert === [] && typeof _prepare_insert === 'undefined')
							return false;	
						
						if(state.saveRegistration.status === true)
							return false;
						
						$.alert_warning('Mohon jangan tutup halaman, sedang proses Simpan Pendaftaran BPJS!');
												
						$.ajax({
							url: "<?php echo $save_url ?>",
							type: "POST",
							data: {f : _prepare_insert},
							dataType: 'json',
						}).done(function(response) {
	
							if(response.status == false){
								$.alert_error(response.message);
								return false;							
							}
							
							$.alert_success(response.message);
							state.saveRegistration.status = true;
							
							_form_actions.afterPost();
														
						}).fail(function() {
							$.alert_error('Terjadi kesalahan dengan Sistem, ketika Simpan Pendapaftaran');
							_btn_process.removeClass('disabled');
						});
						
					}
			};
		
		var _prepare_delete = {};
		var bpjsRemoveRegistration = {
				post: function(data, fn){
					
						if(state.removeRegistration.status === true){
							$.alert_success('hapus pendaftaran BPJS sudah dilakukan');
							bpjsAddRegistration.deleteRegistration(_prepare_delete);
							return false;
						}
						
						$.alert_warning('Mohon jangan tutup halaman, sedang proses Hapus Pendaftaran BPJS!');						
					
						$.ajax({
							url: "<?php echo $remove_url ?>",
							type: "DELETE",
							data: {
								noKartu : reg.NoKartu,
								tglDaftar : reg.TglDaftar,
								noUrut : reg.NoUrut,
								kdPoli : reg.KdPoli,
							},
							dataType: 'json',
							beforeSend: function( request ) {
								request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
							}
						}).done(function(response) {
							
							if(response.status == false){
								$.alert_error('Hapus pendaftaran BPJS gagal : '+ response.message);
								return false;
							}
							
							$.alert_success('Hapus pendaftaran BPJS berhasil : '+ response.message);
							state.removeRegistration.status = true;
							
							_prepare_delete = {
								NoReg : reg.NoReg,
								SectionID : reg.SectionID,
								APITYPE : '<?php echo config_item('bpjs_api_type')?>'
							};				
																			
							bpjsRemoveRegistration.deleteRegistration(_prepare_delete, fn);
							
						}).fail(function() {
							$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Hapus Pendaftaran');
						});
					},
				deleteRegistration: function(_prepare_delete = '', fn){
						if(_prepare_delete === '' && _prepare_delete === null && _prepare_delete === [] && typeof _prepare_delete === 'undefined')
							return false;	
						
						if(state.deleteRegistration.status === true)
							return false;
							
						$.alert_warning('Mohon jangan tutup halaman, sedang proses Hapus Pendaftaran BPJS!');
												
						$.ajax({
							url: "<?php echo $delete_url ?>",
							type: "POST",
							data: {f : _prepare_delete},
							dataType: 'json',
						}).done(function(response) {
	
							if(response.status == false){
								$.alert_error(response.message);
								return false;							
							}
							
							$.alert_success(response.message);
							state.deleteRegistration.status = true;
							
							if($.isFunction(fn))
							{
								fn();
							}
														
						}).fail(function() {
							$.alert_error('Terjadi kesalahan dengan Sistem, ketika Hapus Pendapaftaran');
							_btn_process.removeClass('disabled');
						});
						
					}
			};
			
//]]>
</script>
