<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), ['name' => 'form_process_visite'] ); ?>
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header"> 
            <h4 class="modal-title">Prosess Sinkronisasi BPJS </h4>
			<span>Mohon jangan menutup Halaman/Sistem ketika proses sinkronisasi BPJS sedang berjalan!</span>
        </div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?php echo form_label('Kunjungan', '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">						
							<div class="progress" style="margin-top:10px">
								<div id="visite" data-target="visite" class="progress-bpjs progress-bar progress-bar-striped progress-bar-success " role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?php echo form_label('Tindakan', '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">						
							<div class="progress" style="margin-top:10px">
								<div id="service" data-target="tindakan" class="progress-bpjs progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?php echo form_label('Obat', '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">						
							<div class="progress" style="margin-top:10px">
								<div id="drug" data-target="obat" class="progress-bpjs progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<button type="button" class="btn btn-primary btn-block disabled" name="btn_process" ><b><i class="fa fa-exchange"></i> <?php echo lang('buttons:process')?></b></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="bpjsNoKunjungan" value="<?php echo @$visite->NoKunjungan ?>">
<?php echo form_close(); ?>
<script type="text/javascript">
//<![CDATA[

	var visite = <?php print_r(json_encode($visite, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));?>;
	var service = <?php print_r(json_encode($service, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));?>;
	var drug = <?php print_r(json_encode($drug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));?>;

	var state = {
		addVisite : {status: (visite.NoKunjungan !== null && visite.NoKunjungan !== '') ? true : false, data: {noKunjungan: visite.NoKunjungan}},
		saveVisite : {status: (visite.NoKunjungan !== null && visite.NoKunjungan !== '')},
		addService : {length : service.length, index: 0, progress: 0, increase: 100 / service.length * 2, status: false, data: {}},
		saveService : {length : service.length, index: 0, status: false},
		addDrug : {length : drug.length, index: 0, progress: 0, increase: 100 / drug.length * 2, status: false, data: {}},
		saveDrug : {length : drug.length, index: 0, status: false},
		serviceCollection: {data: []},
		drugCollection: {data: []},
	}
	var _form = $( "form[name=\"form_process_visite\"]" );
	var _btn_process = _form.find("button[name=\"btn_process\"]");
	var _target, _progress;
	var bpjsVisiteProcess = {
			init: function(){
					
					_btn_process.on("click", function(){
						
						_btn_process.addClass('disabled');
						
						if(state.addVisite.status === true && state.saveVisite.status === true){
							$( "#visite" ).css({'width':'100%'});
							$.alert_success('Pasien sudah proses Visite BPJS');
							bpjsVisiteProcess.getVisiteService();
						} else {
							bpjsVisiteProcess.addVisite();
						}						
					});
					
					_btn_process.trigger('click');
				},
			addVisite: function(){
				
				if(state.addVisite.status === true)
				{
					bpjsVisiteProcess.saveVisite();
					return false;
				}
									
				bpjsVisiteProcess.progressBarState( 1 );
				$.alert_success('Proses simpan Visite BPJS');
				$( "#visite" ).css({'width':'25%'});	
				
				var postData = {
					noKunjungan: visite.NoKunjungan || 0,
					noKartu: visite.NoKartu,
					tglDaftar: visite.TglDaftar,
					//kdPoli: visite.CheckoutState == 4 ? null : visite.KdPoli, // jika rujuk lanjut, maka null
					kdPoli: visite.KdPoli, // jika rujuk lanjut, maka null
					keluhan: visite.Symptom,
					kdSadar: '01', //required
					sistole: visite.Systolic, //required
					diastole: visite.Diastolic, //required
					beratBadan: visite.Weight, //required
					tinggiBadan: visite.Height, //required
					respRate: visite.RespiratoryRate, //required
					heartRate: visite.HeartRate, //required
					lingkarPerut: visite.lingkarPerut, //required
					terapi: visite.Therapi,
					kdStatusPulang: visite.CheckoutState, //required
					tglPulang: visite.TglDaftar,
					kdDokter: visite.KdDokter,
					kdDiag1: visite.icd[0] || null, //required
					kdDiag2: visite.icd[1] || null,
					kdDiag3: visite.icd[2] || null,
					kdPoliRujukInternal: null,
					kdTacc: -1,
					alasanTacc: null,
				}
				
				// jika rujuk lanjut
				if(visite.CheckoutState == 4){
					postData['rujukLanjut'] = {
						tglEstRujuk: visite.CheckoutReferralDate,
						kdppk: visite.CheckoutReferralDestination,
					}
					
					if(visite.CheckoutReferralCondition == 'spesialis'){
						postData['rujukLanjut']['subSpesialis'] = {
							kdSubSpesialis1: visite.CheckoutReferralSubSpecialist,
							kdSarana : visite.CheckoutReferralAcomodation
						}						
					}
					
					if(visite.CheckoutReferralCondition == 'khusus'){
						postData['rujukLanjut']['khusus'] = {
							kdKhusus: visite.CheckoutReferralSpecialist,
							kdSubSpesialis: visite.CheckoutReferralSubSpecialist,
							catatan: visite.CheckoutReferralNote
						}
					}
				}

						
				$.ajax({
					url: "<?php echo $add_visite_url ?>",
					type: "POST",
					data: postData,
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {

					if(response.status == false){
						bpjsVisiteProcess.progressBarState( 0 );
						$.alert_error('Tambah Kunjungan BPJS gagal : '+ response.message);
						return false;							
					}
					
					state.addVisite.status = true;
					state.addVisite.data = response.data;
					
					$.alert_success('Tambah Kunjungan BPJS berhasil : '+ response.message);
					$( "#visite" ).css({'width':'50%'});	
					
					$('#bpjsNoKunjungan').val(response.data.noKunjungan);		
					bpjsVisiteProcess.saveVisite();
					
				}).fail(function() {
					bpjsVisiteProcess.progressBarState( 0 );
					$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Add Kunjungan');
				});
			},
		saveVisite: function(){

				if(state.saveVisite.status === true)
				{
					bpjsVisiteProcess.addService();
					bpjsVisiteProcess.progressBarState( 1 );
					$( "#visite" ).css({'width':'100%'});	
					return false;
				}
				
				bpjsVisiteProcess.progressBarState( 1 );
				$.alert_success('Proses simpan Visite BPJS');
				$( "#visite" ).css({'width':'75%'});	
				
				$.ajax({
					url: "<?php echo $save_visite_url ?>",
					type: "POST",
					data: {
						f: {							
							NoBuktiIntegrasi: state.addVisite.data.noKunjungan,
						}
					},
					dataType: 'json',
				}).done(function(response) {

					if(response.status == false){
						bpjsVisiteProcess.progressBarState( 0 );
						$.alert_error('Update No Kunjungan gagal : '+ response.message);
						return false;							
					}
					
					state.saveVisite.status = true;
					
					$.alert_success('Update No Kunjungan berhasil : '+ response.message);
					$( "#visite" ).css({'width':'100%'});	
					
					bpjsVisiteProcess.addService();
					
				}).fail(function() {
					bpjsVisiteProcess.progressBarState( 0 );
					$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Add Kunjungan');
				});
			},
		addService: function(){
				
				if(service == [] || service == '' || service == null)
				{
					bpjsVisiteProcess.progressBarState( 1 );
					$( "#service" ).css({'width':'100%'});	
					bpjsVisiteProcess.addDrug();
					return false;
				}
				
				if(state.addService.status === true || state.addService.index > state.saveService.index || (service[state.addService.index].NoBuktiTindakanIntegrasi !== '' && service[state.addService.index].NoBuktiTindakanIntegrasi !== null))
				{
					bpjsVisiteProcess.saveService();
					return false;
				}
				
				if(typeof state.serviceCollection.data[ state.addService.index ] !== 'undefined' && state.serviceCollection.data[ state.addService.index ] !== '' && state.serviceCollection.data[ state.addService.index ] !== null && state.serviceCollection.data[ state.addService.index ] !== [] )
				{
					state.addService.data = state.serviceCollection.data[ state.addService.index ];
					bpjsVisiteProcess.saveService();
					return false;
				}

				bpjsVisiteProcess.progressBarState( 1 );
				$.alert_success('Proses tambah Tindakan BPJS: '+ service[state.addService.index].JasaName);
				$( "#service").css({'width': state.addService.progress +'%'});	
						
				console.log(state.addService.index);
				console.log(service);
				$.ajax({
					url: "<?php echo $add_service_url ?>",
					type: "POST",
					data: {							
						kdTindakanSK : 0,
						noKunjungan : state.addVisite.data.noKunjungan, // required
						kdTindakan : service[state.addService.index].JasaIDBPJS, // required
						biaya : service[state.addService.index].Tarif, 
						keterangan : null,
						hasil : 1
					},
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {
					if(response.status == false){
						bpjsVisiteProcess.progressBarState( 0 );
						$.alert_error('Tambah Tindakan BPJS gagal : '+ response.message);
						return false;							
					}
					
					state.addService.progress = (state.addService.index + 1) * state.addService.increase * 2 - state.addService.increase;
					state.addService.index += 1;
					state.addService.data = response.data;
					state.addService.status = (state.addService.length < state.addService.index + 1) ? true : false;
					
					$.alert_success('Tambah Tindakan BPJS berhasil : '+ response.data.kdTindakanSK);
					bpjsVisiteProcess.saveService();						
					
				}).fail(function() {
					bpjsVisiteProcess.progressBarState( 0 );
					$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Add Tindakan: '+ service[state.addService.index].JasaName);
				});
			},
		saveService: function(){

				if(state.saveService.status === true || (service[state.saveService.index].NoBuktiTindakanIntegrasi !== '' && service[state.saveService.index].NoBuktiTindakanIntegrasi !== null))
				{
					bpjsVisiteProcess.progressBarState( 1 );
					$( "#service" ).css({'width':'100%'});	
					bpjsVisiteProcess.addDrug();
					return false;
				}
				
				bpjsVisiteProcess.progressBarState( 1 );
				$.alert_success('Proses simpan Tindakan BPJS: '+ service[state.saveService.index].JasaName);
				$( "#service").css({'width': state.addService.progress +'%'});	
				
				$.ajax({
					url: "<?php echo $save_service_url ?>",
					type: "POST",
					data: {
						f: {							
							NoBuktiIntegrasi : state.addVisite.data.noKunjungan,
							NoBuktiTindakanIntegrasi : state.addService.data.kdTindakanSK,
							APITYPE : '<?php echo config_item('bpjs_api_type')?>',
							NoReg : visite.NoReg,
							NoPemeriksaan : service[state.saveService.index].NoBukti,
							JasaID : service[state.saveService.index].JasaID,
							JasaIDIntegrasi : service[state.saveService.index].JasaIDBPJS,
						}
					},
					dataType: 'json',
				}).done(function(response) {

					if(response.status == false){
						bpjsVisiteProcess.progressBarState( 0 );
						$.alert_error('Simpan Tindakan BPJS Gagal : '+ response.message);
						return false;							
					}
					
					state.addService.progress = (state.saveService.index + 1) * state.addService.increase * 2;
					state.saveService.index += 1;
					state.saveService.status = (state.saveService.length < state.saveService.index + 1) ? true : false;
					
					$.alert_success('Simpan Tindakan BPJS berhasil : '+ response.message);
					$( "#visite" ).css({'width':'100%'});	
					
					bpjsVisiteProcess.addService();
					
				}).fail(function() {
					bpjsVisiteProcess.progressBarState( 0 );
					$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Simpan Tindakan BPJS ');
				});
			},
		addDrug: function(){
				
				if(drug == [] || drug == '' || drug == null)
				{
					bpjsVisiteProcess.progressBarState( 1 );
					$( "#drug" ).css({'width':'100%'});	
					bpjsVisiteProcess.afterPost();
					return false;
				}
				
				if(state.addDrug.status === true || state.addDrug.index > state.saveDrug.index || (drug[state.addDrug.index].NoBuktiObatIntegrasi !== '' && drug[state.addDrug.index].NoBuktiObatIntegrasi !== null))
				{
					bpjsVisiteProcess.saveDrug();
					return false;
				}
				
				if(typeof state.drugCollection.data[ state.addDrug.index ] !== 'undefined' && state.drugCollection.data[ state.addDrug.index ] !== '' && state.drugCollection.data[ state.addDrug.index ] !== null && state.drugCollection.data[ state.addDrug.index ] !== [] )
				{
					state.addDrug.data = state.drugCollection.data[ state.addDrug.index ];
					bpjsVisiteProcess.saveDrug();
					return false;
				}
				
				bpjsVisiteProcess.progressBarState( 1 );
				$.alert_success('Proses add Obat BPJS: '+ drug[state.addDrug.index].NamaBarang);
				$( "#drug").css({'width': state.addDrug.progress +'%'});	
											
				$.ajax({
					url: "<?php echo $add_drug_url ?>",
					type: "POST",
					data: {							
						kdObatSK : 0,
						noKunjungan : state.addVisite.data.noKunjungan,
						racikan : drug[state.addDrug.index].NamaResepObat != drug[state.addDrug.index].NamaBarang ? true : false,
						kdRacikan : drug[state.addDrug.index].NamaResepObat == drug[state.addDrug.index].NamaBarang ? 'R.01' : drug[state.addDrug.index].NamaResepObat,
						obatDPHO : true,
						kdObat : drug[state.addDrug.index].BarangIDBPJS,
						signa1 : drug[state.addDrug.index].Signa1,
						signa2 : drug[state.addDrug.index].Signa2,
						jmlObat : drug[state.addDrug.index].JmlObat,
						jmlPermintaan : drug[state.addDrug.index].JmlObat,
						nmObatNonDPHO : ''
					},
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {

					if(response.status == false){
						bpjsVisiteProcess.progressBarState( 0 );
						$.alert_error('Add Obat BPJS gagal : '+ response.message);
						return false;							
					}
					
					state.addDrug.progress = (state.addDrug.index + 1) * state.addDrug.increase * 2 - state.addDrug.increase;
					state.addDrug.index += 1;
					state.addDrug.data = response.data;
					state.addDrug.status = (state.addDrug.length < state.addDrug.index + 1) ? true : false;
					
					$.alert_success('Add Obat BPJS berhasil : '+ response.data.kdObatSK);
					bpjsVisiteProcess.saveDrug();
					
				}).fail(function() {
					bpjsVisiteProcess.progressBarState( 0 );
					$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Add Obat BPJS');
				});
			},
		saveDrug: function(){

				if(state.saveDrug.status === true || (drug[state.saveDrug.index].NoBuktiObatIntegrasi !== '' && drug[state.saveDrug.index].NoBuktiObatIntegrasi !== null))
				{
					bpjsVisiteProcess.progressBarState( 1 );
					$( "#drug" ).css({'width':'100%'});	
					bpjsVisiteProcess.afterPost();
					return false;
				}
				
				bpjsVisiteProcess.progressBarState( 1 );
				$.alert_success('Proses simpan Obat BPJS: '+ drug[state.saveDrug.index].NamaBarang);
				$( "#drug").css({'width': state.addDrug.progress +'%'});	
				
				$.ajax({
					url: "<?php echo $save_drug_url ?>",
					type: "POST",
					data: {
						f: {							
							NoBuktiIntegrasi : state.addVisite.data.noKunjungan,
							NoBuktiObatIntegrasi : state.addDrug.data.kdObatSK,
							APITYPE : '<?php echo config_item('bpjs_api_type')?>',
							NoReg : visite.NoReg,
							NoBukti : drug[state.saveDrug.index].NoBukti,
							BarangID : drug[state.saveDrug.index].BarangID,
							BarangIDIntegrasi : drug[state.saveDrug.index].BarangIDBPJS,
						}
					},
					dataType: 'json',
				}).done(function(response) {

					if(response.status == false){
						bpjsVisiteProcess.progressBarState( 0 );
						$.alert_error('Simpan Obat BPJS Gagal : '+ response.message);
						return false;							
					}
					
					state.addDrug.progress = (state.saveDrug.index + 1) * state.addDrug.increase * 2;
					state.saveDrug.index += 1;
					state.saveDrug.status = (state.saveDrug.length < state.saveDrug.index + 1) ? true : false;
					
					$.alert_success('Simpan Obat BPJS berhasil : '+ response.message);					
					bpjsVisiteProcess.addDrug();
					
				}).fail(function() {
					bpjsVisiteProcess.progressBarState( 0 );
					$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Simpan Tindakan BPJS ');
				});
			},
		getVisiteService: function(){
				$.ajax({
					url: "<?php echo $get_visite_service_url ?>/"+ state.addVisite.data.noKunjungan,
					type: "GET",
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {
					if(response.status == false){
						$.alert_error('Get Tindakan BPJS gagal : '+ response.message);
						bpjsVisiteProcess.getVisiteDrug();
						return false;							
					}
					
					if( response.collection !== null && response.collection !== '' && response.collection !== [] )
					{
						$.each(response.collection, function(index, value){
							state.serviceCollection.data[value.kdTindakan] = value;
						});
						$.alert_success('Tindakan BPJS ditemukan');
					} else {
						$.alert_success('Tindakan BPJS tidak ditemukan');
					}
					
					bpjsVisiteProcess.getVisiteDrug();
					
				}).fail(function() {
					$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Get Tindakan BPJS');
				});
			},
		getVisiteDrug: function(){
				$.ajax({
					url: "<?php echo $get_visite_drug_url ?>/"+ state.addVisite.data.noKunjungan,
					type: "GET",
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {
					if(response.status == false){
						$.alert_error('Get Obat BPJS gagal : '+ response.message);
						bpjsVisiteProcess.addService();
						return false;							
					}
					
					if( response.collection !== null && response.collection !== '' && response.collection !== [] )
					{
						$.each(response.collection, function(index, value){
							state.drugCollection.data[value.obat.kdObat] = value;
						});
						$.alert_success('Obat BPJS ditemukan');
					} else {
						$.alert_success('Obat BPJS tidak ditemukan');
					}
					
					bpjsVisiteProcess.addService();
					
				}).fail(function() {
					$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Get Obat BPJS');
				});
			},
		afterPost: function(){
				setTimeout(function(){
					document.location.href = "<?php echo base_url("cashier/general-payment"); ?>";
					}, 300 );	
			},
		progressBarState: function( state, progress = 0 ){
					
				switch (state)
				{
					case 1: 
						$( "#"+ _target )
							.addClass('progress-bar-success')
							.removeClass('progress-bar-danger')
					break;
					case 2: 
						$( "#"+ _target )
							.addClass('progress-bar-success')
							.removeClass('progress-bar-danger')
							.css({'width':'100%'});
					break;
					case 0 :
						$( "#"+ _target )
							.addClass('progress-bar-danger')
							.removeClass('progress-bar-success');
						_btn_process.removeClass('disabled');
					break;
				}
				
			},
		};
	
	$( document ).ready(function(e) {
			bpjsVisiteProcess.init();				
			
		});
		
//]]>
</script>
