<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?= form_open( $form_action, [
		'id' => 'form_pcare', 
		'name' => 'form_pcare', 
		'rule' => 'form', 
		'class' => ''
	]); ?>
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-info">
			<div class="panel-heading">                
				<h3 class="panel-title"><?= lang('heading:general_information'); ?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:registration_number') ?></label>
						<label id="NoReg" class="col-md-8">: <?= @$item->NoReg ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:date') ?></label>
						<label id="TglDaftar" class="col-md-8">: <?= @$item->TglDaftar ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:visite_number') ?></label>
						<label id="NoKunjungan" class="col-md-8">: <?= @$item->NoKunjungan ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:member_number') ?></label>
						<label id="NoKartu" class="col-md-8">: <?= @$item->NoKartu ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:queue') ?></label>
						<label id="NoUrut" class="col-md-8">: <?= @$item->NoUrut ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:service_type') ?></label>
						<label id="ServiceType" class="col-md-8">: <?= 'Rawat Jalan' ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:poly') ?></label>
						<label id="SectionName" class="col-md-8">: <?= @$item->SectionName ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:nrm') ?></label>
						<label id="NRM" class="col-md-8">: <?= @$item->NRM ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:name') ?></label>
						<label id="NamaPasien" class="col-md-8">: <?= @$item->NamaPasien ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:dob') ?></label>
						<label id="TglLahir" class="col-md-8">: <?= @$item->TglLahir ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:gender') ?></label>
						<label id="JenisKelamin" class="col-md-8">: <?= @$item->JenisKelamin ? lang('label:male') : lang('label:female') ?></label>
					</div>
					<div class="col-md-12">
						<label class="col-md-4 control-label"><?= lang('label:address') ?></label>
						<label id="Alamat" class="col-md-8">: <?= @$item->Alamat ?></label>
					</div>
				</div>
				<hr/>
				<div class="row">
					<button class="btn btn-default btn-block" type="button" onclick="window.location='<?= base_url("{$nameroutes}") ?>';"><i class="fa fa-arrow-left"></i> <?= lang( 'buttons:back' ) ?></button> 
				</div>
			</div>
		</div>
	</div>	
		
	<div class="col-md-8">
		<div class="panel panel-info">
			<div class="panel-heading">                
				<h3 class="panel-title"><?= lang('heading:examination_information'); ?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label"><?= lang('label:complaint') ?></label>
							<div class="col-md-9">
								<textarea id="keluhan" class="form-control"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= lang('label:therapy') ?></label>
							<div class="col-md-9">
								<textarea id="terapi" class="form-control"><?= @$item->Therapi ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= lang('label:diagnosis') ?></label>
							<div class="col-md-9">
								<div class="input-group">
									<span id="kdDiag1" class="input-group-addon"><?= @$item->icd[0] ?></span>
									<input type="text" id="nmDiag1" class="form-control" value="<?= @$item->icdName[0] ?>" readonly/>
									<div class="input-group-btn">
										<a href="<?= base_url("{$nameroutes}/lookup/lookup_icd/1") ?>" data-toggle="lookup-ajax-modal" class="btn btn-info"><i class="fa fa-search"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= lang('label:diagnosis') ?></label>
							<div class="col-md-9">
								<div class="input-group">
									<span id="kdDiag2" class="input-group-addon"><?= @$item->icd[1] ?></span>
									<input type="text" id="nmDiag2" class="form-control" value="<?= @$item->icdName[1] ?>" readonly/>
									<div class="input-group-btn">
										<a href="<?= base_url("{$nameroutes}/lookup/lookup_icd/2") ?>" data-toggle="lookup-ajax-modal" class="btn btn-info"><i class="fa fa-search"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= lang('label:diagnosis') ?></label>
							<div class="col-md-9">
								<div class="input-group">
									<span id="kdDiag3" class="input-group-addon"><?= @$item->icd[2] ?></span>
									<input type="text" id="nmDiag3" class="form-control" value="<?= @$item->icdName[2] ?>" readonly/>
									<div class="input-group-btn">
										<a href="<?= base_url("{$nameroutes}/lookup/lookup_icd/3") ?>" data-toggle="lookup-ajax-modal" class="btn btn-info"><i class="fa fa-search"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= lang('label:consciousness') ?></label>
							<div class="col-md-9">
								<select id="kesadaran" class="form-control">
									<option value="">Loading...</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= lang('label:medical_personnel') ?></label>
							<div class="col-md-9">
								<select id="dokter" class="form-control" disabled>
									<option value="">Loading...</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= lang('label:checkout') ?></label>
							<div class="col-md-9">
								<select id="checkout" class="form-control">
									<option value="">Loading...</option>
								</select>
							</div>
						</div>
						<div id="checkoutHiddenArea">
							<input type="hidden" id="kdppk" name="kdppk">						
							
							<input type="hidden" id="spesialis" name="spesialis" value="0">
							<input type="hidden" id="kdSpesialis" name="kdSpesialis">
							<input type="hidden" id="kdSubSpesialis1" name="kdSubSpesialis1">
							<input type="hidden" id="kdSarana" name="kdSarana">
							
							<input type="hidden" id="khusus" name="khusus" value="0">
							<input type="hidden" id="kdKhusus" name="kdKhusus">
							<input type="hidden" id="kdSubSpesialis" name="kdSubSpesialis">
						</div>
						<div class="form-group" style="margin-bottom:0">
							<label class="col-md-3 control-label" style="line-height:2"><?= lang('label:ppk_referral') ?></label>
							<label class="col-md-9" style="line-height:2">: <span id="providerRujukLanjut"></span></label>
						</div>
						<div class="form-group" style="margin-bottom:0">
							<label class="col-md-3 control-label" style="line-height:2"><?= lang('label:specialist') ?></label>
							<label class="col-md-9" style="line-height:2">: <span id="poliRujukLanjut" ></span></label>
						</div>
						<div class="form-group" style="margin-bottom:0">
							<label class="col-md-3 control-label" style="line-height:2"><?= lang('label:note') ?></label>
							<label class="col-md-9" style="line-height:2">: <span id="catatan"></span></label>
						</div>
						<div class="form-group" style="margin-bottom:0">
							<label class="col-md-3 control-label" style="line-height:2"><?= lang('label:dateof_visit') ?></label>
							<label class="col-md-9" style="line-height:2">: <span id="tglEstRujuk"></span></label>
						</div>
					</div>
					<div class="col-md-6">                        
						<div class="form-group">
							<label class="col-md-3">Fisik</label>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-6">
										<label>Tinggi Badan</label>
										<div class="input-group">
											<input type="number" id="vitalHeight" name="v[Height]" min="0" placeholder="" class="form-control">
											<span class="input-group-addon help-block">CM</span>
										</div>
									</div>
									<div class="col-md-6">
										<label>Berat Badan</label>
										<div class="input-group">
											<input type="number" id="vitalWeight" name="v[Weight]" min="0" placeholder="placeholder" class="form-control">
											<span class="input-group-addon help-block">KG</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Suhu Tubuh</label>
										<div class="input-group">
											<input type="number" id="vitalTemperature" name="v[Temperature]" value="<?= @$item->Temperature  ?>" min="0" placeholder="" class="form-control">
											<span class="input-group-addon help-block">C<sup>o</sup></span>
										</div>
									</div>
									<div class="col-md-6">
										<label>Skala Nyeri</label>
										<div class="input-group">
											<select id="vitalPain" name="v[Pain]" class="form-control">
												<?php $i=0; while($i <= 10):?>
												<option value="<?= $i ?>" <?= (@$item->Pain == $i) ? 'selected' : NULL ?>><?= $i ?></option>
												<?php $i++; endwhile;?>
											</select>
											<span class="input-group-addon help-block">0-10</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3">Tekanan Darah</label>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-12">
										<label>Sistole/Diastole</label>
										<div class="input-group">
											<input type="number" id="vitalSystolic" name="v[Systolic]" placeholder="" min="90" max="120" class="form-control" />
											<span class="input-group-addon">/</span>
											<input type="number" id="vitalDiastolic" name="v[Diastolic]" placeholder="" min="60" max="80" class="form-control" />
											<span class="input-group-addon help-block">MM/HG</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Detak Jantung/Menit</label>
										<div class="input-group">
											<input type="number" id="vitalHeartRate" name="v[HeartRate]" placeholder="" min="60" max="100" class="form-control">
											<span class="input-group-addon help-block">BPM</span>
										</div>
									</div>
									<div class="col-md-6">
										<label>Frekuensi Pernapasan</label>
										<div class="input-group">
											<input type="number" id="vitalRespiratoryRate" name="v[RespiratoryRate]" placeholder="" class="form-control">
											<span class="input-group-addon help-block">RPM</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">                        
										<label>Saturasi Oksigen (SATS)</label>
										<div class="input-group">
											<input type="number" id="vitalOxygenSaturation" name="v[OxygenSaturation]" value="<?= @$item->OxygenSaturation ?>" placeholder="" min="0" max="100" class="form-control">
											<span class="input-group-addon help-block"> % </span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<ul id="tab-poly" class="nav nav-tabs">
						<li class="active"><a href="#tab-service" data-toggle="tab"><b><i class="fa fa-stethoscope"></i> <?= lang('label:service')?></b></a></li>
						<li><a href="#tab-drug" data-toggle="tab"><b><i class="fa fa-medkit"></i> <?= lang('label:drug')?></b></a></li>
						<li><a href="#tab-mcu" data-toggle="tab"><b><i class="fa fa-flask"></i> <?= lang('label:pen_diagnotic')?></b></a></li>
					</ul>
					<div class="tab-content">
						<div id="tab-service" class="tab-pane tab-pane-padding active">
							<?php echo modules::run("bpjs/pcare/service", $item->NoReg); ?>
						</div>
						<div id="tab-drug" class="tab-pane tab-pane-padding">
							<?php echo modules::run("bpjs/pcare/drug", $item->NoReg); ?>
						</div>
						<div id="tab-mcu" class="tab-pane tab-pane-padding">
							<?php //echo modules::run("bpjs/pcare/mcu", $item->NoReg); ?>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6 text-center">
						<a href="javascript:;" id="btn-export-visite" class="btn btn-info"><i class="fa fa-print"></i> Kunjungan</a>
						<a href="<?= @$export_history_visite ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Riwayat</a>
						<a href="javascript:;" id="btn-export-referral" class="btn btn-info"><i class="fa fa-print"></i> Rujukan</a>
					</div>
					<div class="col-md-6">
						<button id="js-btn-submit" type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> <?= lang( 'buttons:save' ) ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var dataVisite = {};
		var pcare = {
			init: function(){
				$.alert_success('Sedang proses Get data Pcare');
				pcare.getConsciousness( pcare.getDoctor );
				
				$('#checkout').on('change', function(){
					if($(this).val() == 4)
					{
						ajax_modal.show('<?php echo base_url("{$nameroutes}/lookup/lookup_referral") ?>');
					}
				});
				
				$('#btn-export-visite').on('click', function(){
					pcare.exportVisite();
				});
				
				$('#btn-export-referral').on('click', function(){
					pcare.exportReferral();
				});
			},
			getVisite: function(){
				$.ajax({
					url: "<?= $get_visite_url ?>",
					type: "GET",
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?= config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {
					if(response.status == false){
						$.alert_error('Gagal get Kunjungan Pcare : '+ response.message);
						return false;							
					}
					
					( response.status == true )
						? $.alert_success('Kunjungan Pcare ditemukan')
						: $.alert_error('Kunjungan Pcare tidak ditemukan : '+ response.message);
										
					$.each(response.collection, function(i, v){
						if(v.noKunjungan == '<?= $item->NoKunjungan ?>'){
							$('#keluhan').val( v.keluhan );
							$('#terapi').val( v.terapi || $('#terapi').val() );
							$('#kesadaran').val( v.kesadaran.kdSadar );
							$('#dokter').val( v.dokter.kdDokter );
							$('#checkout').val( v.statusPulang.kdStatusPulang );
							$('#vitalHeight').val( v.tinggiBadan );
							$('#vitalWeight').val( v.beratBadan );
							$('#vitalDiastolic').val( v.diastole );
							$('#vitalSystolic').val( v.sistole );
							$('#vitalHeartRate').val( v.heartRate );
							$('#vitalRespiratoryRate').val( v.respRate );							
							$('#providerRujukLanjut').html( v.providerRujukLanjut.nmProvider );
							$('#poliRujukLanjut').html( v.poliRujukLanjut.nmPoli );
							$('#catatan').html( v.catatan );
							$('#tglEstRujuk').html( v.tglEstRujuk );
							
							dataVisite = v;
						}
					});
										
				}).fail(function() {
					$.alert_error('Terjadi kesalahan dengan server Pcare, ketika Get Kunjugan Pcare');
				});
			},
			getConsciousness: function( nextFn ){
				$.ajax({
					url: "<?= $get_consciousness_url ?>",
					type: "GET",
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?= config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {
					var _option ='';
					$.each(response.collection, function(index, value){
						_option += '<option value="'+ value.kdSadar +'">'+ value.nmSadar +'</option>';
					});
					
					$('#kesadaran').html(_option);
					
					nextFn( pcare.getCheckout );
					
				}).fail(function() {
					$.alert_error('Terjadi kesalahan dengan server Pcare, Ketika get Status Pulang');
				});
			},
			getDoctor: function( nextFn ){
				$.ajax({
					url: "<?= $get_doctor_url ?>",
					type: "GET",
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?= config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {
					var _option ='';
					$.each(response.collection, function(index, value){
						_option += '<option value="'+ value.kdDokter +'">'+ value.nmDokter +'</option>';
					});
					
					$('#dokter').html(_option);
					
					nextFn( pcare.getVisite );
					
				}).fail(function() {
					$.alert_error('Terjadi kesalahan dengan server Pcare, Ketika get Status Pulang');
				});
			},
			getCheckout: function( nextFn ){
				$.ajax({
					url: "<?= $get_checkout_url ?>",
					type: "GET",
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?= config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {
					var _option ='';
					$.each(response.collection, function(index, value){
						_option += '<option value="'+ value.kdStatusPulang +'">'+ value.nmStatusPulang +'</option>';
					});
					
					$('#checkout').html(_option);
					
					nextFn();
					
				}).fail(function() {
					$.alert_error('Terjadi kesalahan dengan server Pcare, Ketika get Status Pulang');
				});
			},
			exportVisite: function(){		
									
				var _form = document.createElement('form');
				_form.method = 'POST';
				_form.action = '<?= $export_visite ?>';
				_form.target='_blank';
				
				var _input = document.createElement('input');
				_input.type = 'hidden';
				_input.name = 'visite';
				_input.value = JSON.stringify(dataVisite);
				_form.appendChild(_input);
				
				document.body.appendChild(_form);
				_form.submit();
			},
			exportReferral: function(){
				$.alert_success('Sedang proses Get data Rujukan');
				$.ajax({
					url: "<?= $get_referral_url ?>",
					type: "GET",
					dataType: 'json',
					beforeSend: function( request ) {
						request.setRequestHeader("X-API-KEY", '<?= config_item('bpjs_api_key') ?>');
					}
				}).done(function(response) {					
					var _data = response.data;
					
					if(_data.diag2 == null){
						_data.diag2 = {
							kdDiag: $('#kdDiag2').val(),
							nmDiag: $('#nmDiag2').val() 
						}
					}
					
					if(_data.diag3 == null){
						_data.diag3 = {
							kdDiag: $('#kdDiag3').val(),
							nmDiag: $('#nmDiag3').val() 
						}
					}
					
					if($('#providerRujukLanjut').val() != ''){
						_data.providerRujukLanjut = $('#providerRujukLanjut').html();
					}
										
					var _form = document.createElement('form');
					_form.method = 'POST';
    				_form.action = '<?= $export_referral ?>';
					_form.target='_blank';
					
					var _input = document.createElement('input');
					_input.type = 'hidden';
					_input.name = 'referral';
					_input.value = JSON.stringify(_data);
					_form.appendChild(_input);
					
					document.body.appendChild(_form);
					_form.submit();
				});
			},
		}
		
	var visite = <?php print_r(json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));?>;
	var state = {
		addVisite : {status: (visite.NoKunjungan !== null && visite.NoKunjungan !== '') ? true : false, data: {noKunjungan: visite.NoKunjungan}},
		saveVisite : {status: (visite.NoKunjungan !== null && visite.NoKunjungan !== '')},
	}
	
	var _form = $( "#form_pcare" );
	var _btn_process = $("#js-btn-submit");
	var bpjsVisiteProcess = {
			init: function(){					
					_form.on("submit", function(e){
						e.preventDefault();		
						_btn_process.addClass('disabled');
						bpjsVisiteProcess.updateVisite();
					});	
				},
			updateVisite: function(){			
					$.alert_success('Proses update Kunjunga Lokal Pcare');
					
					var postData = {
						noKunjungan: visite.NoKunjungan,
						noKartu: visite.NoKartu,
						tglDaftar: visite.TglDaftar,
						//kdPoli: visite.CheckoutState == 4 ? null : visite.KdPoli, // jika rujuk lanjut, maka null
						kdPoli: visite.KdPoli,
						keluhan: $('#keluhan').val(),
						kdSadar: '01', //required
						sistole: $('#vitalSystolic').val(), //required
						diastole: $('#vitalDiastolic').val(), //required
						beratBadan: $('#vitalWeight').val(), //required
						tinggiBadan: $('#vitalHeight').val(), //required
						respRate: $('#vitalRespiratoryRate').val(), //required
						heartRate: $('#vitalHeartRate').val(), //required
						terapi: $('#terapi').val(),
						kdStatusPulang: $('#checkout').val(), //required
						tglPulang: visite.TglDaftar,
						kdDokter: visite.KdDokter,
						kdDiag1: $('#kdDiag1').html() || null, //required
						kdDiag2: $('#kdDiag2').html() || null,
						kdDiag3: $('#kdDiag3').html() || null,
						kdPoliRujukInternal: null,
						kdTacc: 0,
						alasanTacc: null,
					}
					
					// jika rujuk lanjut
					if($('#checkout').val() == 4){
						postData['rujukLanjut'] = {
							tglEstRujuk: $('#tglEstRujuk').html(),
							kdppk: $('#kdppk').val(),
						}
						
						if($('#spesialis').val() == 1){
							postData['rujukLanjut']['subSpesialis'] = {
								kdSubSpesialis1: $('#kdSubSpesialis1').val(),
								kdSarana : $('#kdSarana').val()
							}						
						}
						
						if($('#khusus').val() == 1){
							postData['rujukLanjut']['khusus'] = {
								kdKhusus: $('#kdKhusus').val(),
								kdSubSpesialis: $('#kdSubSpesialis').val(),
								catatan: $('#catatan').html()
							}
						}
					}
							
					$.ajax({
						url: "<?php echo @$update_visite_url ?>",
						type: "PUT",
						data: postData,
						dataType: 'json',
						beforeSend: function( request ) {
							request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
						}
					}).done(function(response) {
	
						if(response.status == false){
							_btn_process.removeClass('disabled');
							$.alert_error('Tambah Kunjungan BPJS gagal : '+ response.message);
							return false;							
						}
						
						state.addVisite.status = true;
						state.addVisite.data = response.data;
						
						$.alert_success('Tambah Kunjungan BPJS berhasil : '+ response.message);
						
						bpjsVisiteProcess.saveVisite();
						
					}).fail(function() {
						$.alert_error('Terjadi kesalahan dengan server BPJS, ketika Add Kunjungan');
					});
				},
			saveVisite: function(){					
					$.alert_success('Proses simpan Kunjungan Lokal Pcare');
									
					postData = {
						examination:{
							Therapi : $('#terapi').val()
						},
						integration: {
							CheckoutState: $('#checkout').val(),
						},
						checkout : {},
						vital: {
							Systolic: $('#vitalSystolic').val(),
							Diastolic: $('#vitalDiastolic').val(),
							Weight: $('#vitalWeight').val(),
							Height: $('#vitalHeight').val(),
							RespiratoryRate: $('#vitalRespiratoryRate').val(),
							HeartRate: $('#vitalHeartRate').val(),
							Temperature: $('#vitalTemperature').val(),
							OxygenSaturation: $('#vitalOxygenSaturation').val(),
							Pain: $('#vitalPain').val(),
						},
						diagnosys: [
							$('#kdDiag1').html(),
							$('#kdDiag2').html(),
							$('#kdDiag3').html()
						]
					}
					
					if( $('#checkout').val() == 4 ) {
						
						postData['checkout']['CheckoutReferralDestination'] = $('#kdppk').val();
						
						if($('#spesialis').val() == 1) {
							postData['checkout']['CheckoutReferralCondition'] = 'spesialis';
							postData['checkout']['CheckoutReferralDate'] = $('#tglEstRujuk').html();
							postData['checkout']['CheckoutReferralSpecialist'] = $('#kdSpesialis').val();
							postData['checkout']['CheckoutReferralSubSpecialist'] = $('#kdSubSpesialis1').val();
							postData['checkout']['CheckoutReferralAcomodation'] = $('#kdSarana').val();
						}
						
						if($('#khusus').val() == 1) {
							postData['checkout']['CheckoutReferralCondition'] = 'khusus';
							postData['checkout']['CheckoutReferralDate'] = $('#tglEstRujuk').html();
							postData['checkout']['CheckoutReferralSpecialist'] = $('#kdKhusus').val();
							postData['checkout']['CheckoutReferralSubSpecialist'] = $('#kdSubSpesialis').val();
							postData['checkout']['CheckoutReferralNote'] = $('#catatan').html();
						}
					}
					
					$.ajax({
						url: "<?php echo @$form_action ?>",
						type: "POST",
						data: postData,
						dataType: 'json',
					}).done(function(response) {
	
						if(response.status == false){
							_btn_process.removeClass('disabled');
							$.alert_error('Update Kunjungan gagal : '+ response.message);
							return false;							
						}
						
						state.saveVisite.status = true;
						
						$.alert_success('Update Kunjungan berhasil : '+ response.message);
						
						bpjsVisiteProcess.afterPost();
						
					}).fail(function() {
						$.alert_error('Terjadi kesalahan dengan server Lokal, ketika update Kunjungan');
					});
				},
			afterPost: function(){
					setTimeout(function(){
						document.location.reload();
						}, 300 );	
				},
		};
				
		$( document ).ready(function(e) {			
			pcare.init();
			bpjsVisiteProcess.init();
		});

	})( jQuery );
//]]>
</script>
