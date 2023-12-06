<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url(), array("name" => "form_poly") ); ?>
<?php if ( @$registration->ProsesPayment == 1 || (!empty($registration->StatusBayar) && @$registration->StatusBayar == "Sudah Bayar" ) ): ?>
	<h3 class="subtitle well">Status Data: <span class='text-info'><?php echo $registration->ProsesPayment ? 'Proses' : $registration->StatusBayar ?></span> di Kasir.</h3>
<?php endif; ?>
<?php if ( @$item->Batal == 1  ): ?>
	<h3 class="subtitle well">Status Data: <span class='text-danger'>Dibatalkan</span>.</h3>
<?php endif; ?>
<div class="panel panel-info panel-collapsed">
	<div class="panel-heading panel-collapse">
		<h3 class="panel-title"><?php echo lang('laboratory:patient_label') ?></h3>
		<ul class="panel-btn">
			<li><a href="javascript:;" class="btn btn-info panel-collapse" title="Tampilkan"><i class="fa fa-angle-down"></i></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<input type="hidden" id="SectionID" value="<?php echo @$item->SectionID ?>">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:evidence_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:registration_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" placeholder="" class="form-control" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:mr_number_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:patient_name_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:address_label') ?></label>
					<div class="col-lg-9">
						<textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" disabled><?php echo @$item->Alamat ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:gender_label') ?></label>
					<div class="col-lg-9">
						<select id="JenisKelamin" name="p[JenisKelamin]" class="form-control patient" disabled>
							<option value="F" <?php echo @$item->JenisKelamin == "F"  ? "selected" : NULL  ?>>Perempuan</option>
							<option value="M" <?php echo @$item->JenisKelamin == "M"  ? "selected" : NULL  ?>>Laki-laki</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:dob_label') ?></label>
					<div class="col-lg-3">
						<input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$item->TglLahir ?>" placeholder="" class="form-control datepicker patient" disabled>
					</div>
					<label class="col-lg-1 control-label text-center"><?php echo lang('laboratory:age_label') ?></label>
					<div class="col-lg-1">
						<input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" placeholder="" class="form-control" readonly>
					</div>
					<label class="col-lg-1 control-label"><?php echo lang('laboratory:year_label') ?></label>
					<div class="col-lg-1">
						<input type="text" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>" placeholder="" class="form-control" readonly>
						<input type="hidden" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>">
					</div>
					<label class="col-lg-1 control-label"><?php echo lang('laboratory:month_label') ?></label>
				</div>    
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:type_patient_label') ?></label>
					<div class="col-lg-9">
						<input type="hidden" id="JenisKerjasamaID" name="f[JenisKerjasamaID]" value="<?php echo @$item->JenisKerjasamaID ?>">
						<input type="text" id="JenisKerjasama"  value="<?php echo @$item->JenisKerjasama ?>" placeholder="" class="form-control" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('laboratory:company_label') ?></label>
					<div class="col-lg-9">
						<input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>">
						<input type="hidden" id="KdKelas" name="f[KdKelas]" value="<?php echo @$item->KelasID ?>">
						<input type="hidden" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->KodePerusahaan ?>">
						<input type="text" id="NamaPerusahaan"  value="<?php echo @$item->NamaPerusahaan ?>" placeholder="" class="form-control" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('laboratory:card_number_label') ?></label>
					<div class="col-md-9">
						<input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control" disabled />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo sprintf('%s: (%s) %s', lang('laboratory:examination_label'), @$item->NoBukti, @$item->NamaPasien); ?></h3>
		<ul class="panel-btn">
			<li><a href="javascript:;" class="btn btn-success panel-collapse" title="Tampilkan"><i class="fa fa-angle-down"></i></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">	
			<ul id="tab-poly" class="nav nav-tabs nav-justified">
				<li class="active"><a href="#poly-tab1" data-toggle="tab"><i class="fa fa-stethoscope"></i> Jasa</a></li>
				<li><a href="#poly-tab2" data-toggle="tab"><i class="fa fa-medkit"></i> BHP</a></li>
				<li><a href="#poly-tab3" data-toggle="tab"><i class="fa fa-arrow-circle-right"></i> Transfer &amp; Rujukan</a></li>
			</ul>
			<div class="tab-content">
				<div id="poly-tab1" class="tab-pane tab-pane-padding active">
					<?php echo modules::run("laboratory/laboratories/service_helper/index", @$item, @$is_edit ) ?>
				</div>
				<div id="poly-tab2" class="tab-pane tab-pane-padding">
					<?php echo modules::run("laboratory/laboratories/consumables/index", @$item->NoReg, @$item->SectionID ) ?>
				</div>
				 <div id="poly-tab3" class="tab-pane tab-pane-padding">
					<div class="row">
						<div class="col-md-4">
							<div class="page-subtitle">
								<h3><i class="fa fa-user-md fa-flip-horizontal"></i> Petugas Medis</h3>
								<p>Informasi Petugas Medis</p>
							</div>
							<div class="form-group">
								<label class="control-label">Dokter Pengirim</label>
								<div class="input-group">
									<input type="hidden" id="DokterPengirimID" name="f[DokterPengirimID]" value="<?php echo @$item->DokterPengirimID ?>" class="doctor_sender">
									<input type="text" id="DokterPengirimName" value="<?php echo @$item->DokterPengirimName ?>" placeholder="" class="form-control">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_doctor_sender ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Supplier/Vendor Pengirim</label>
								<div class="input-group">
									<input type="hidden" id="SupplierPengirimID" name="f[SupplierPengirimID]" value="<?php echo @$item->SupplierPengirimID ?>" class="doctor_sender">
									<input type="text" id="SupplierPengirimName" value="<?php echo @$item->SupplierPengirimName ?>" placeholder="" class="form-control">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_supplier_sender ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Dokter Jaga</label>
								<div class="input-group">
									<input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$item->DokterID ?>" class="doctor_sender">
									<input type="text" id="DocterName" value="<?php echo @$item->DokterName ?>" placeholder="" class="form-control">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_supplier ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Analis</label>
								<div class="input-group">
									<input type="hidden" id="AnalisID" name="f[AnalisID]" value="<?php echo @$item->AnalisID ?>" class="doctor_sender">
									<input type="text" id="AnalisDocterName" value="<?php echo @$item->AnalisName ?>" placeholder="" class="form-control">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_supplier_analis ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="page-subtitle">
								<h3><i class="fa fa-wheelchair"></i> Transfer </h3>
								<p>Informasi Data Transfer</p>
							</div>
							<div class="form-group">
								<label class="control-label">Section</label>
								<select id="TransferSectionID" name="TransferSectionID" class="form-control">
									<option value=""></option>
									<?php if (!empty($option_section)): foreach($option_section as $k => $v):?>
									<option value="<?php echo $k ?>" <?php echo $k == @$item->TransferSectionID ? "selected" : NULL ?>><?php echo $v?></option>
									<?php endforeach;endif;?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Dokter Transfer</label>
								<div class="input-group">
									<input type="hidden" id="TransferDokterID" name="f[TransferDokterID]" value="<?php echo @$item->TransferDokterID ?>" class="doctor_sender">
									<input type="text" id="TransferDocterName" value="<?php echo @$item->TransferDokterName ?>" placeholder="" class="form-control">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_supplier_transfer ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="page-subtitle">
								<h3><i class="fa fa-ambulance fa-flip-horizontal"></i> Rujuk</h3>
								<p>Informasi Data Rujuk</p>
							</div>
							<div class="form-group">
								<div class="checkbox" style="margin-top:0 !important">
									<input type="hidden" name="Dirujuk" value="0" />
									<input type="checkbox" id="Dirujuk" name="Dirujuk" value="1"><label for="Dirujuk" class="col-lg-3 control-label">Dirujuk Ke Vendor</label>
								</div>
								<div class="input-group">
									<input type="hidden" id="DirujukVendorID" name="f[DirujukVendorID]" value="<?php echo @$item->DirujukVendorID ?>" class="doctor_sender">
									<input type="text" id="DirujukVendorName" value="<?php echo @$item->DirujukVendorName ?>" placeholder="" class="form-control">
									<span class="input-group-btn">
										<a href="<?php echo @$lookup_vendor_dirujuk ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Alasan</label>
								<select id="AlasanDirujukID" name="AlasanDirujukID" class="form-control">
									<option value=""></option>
									<?php if (!empty($option_alasan_dirujuk)): foreach($option_alasan_dirujuk as $row):?>
									<option value="<?php echo $row->AlasanDirujukID?>" <?php echo $row->AlasanDirujukID == @$item->AlasanDirujukID ? "selected" : NULL ?>><?php echo $row->AlasanDirujuk?></option>
									<?php endforeach;endif;?>
								</select>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label class="control-label">Tanggal Kirim</label>
										<input type="text" id="TglKirim" name="TglKirim" value="<?php echo @$item->TglKirim ?>" class="form-control datepicker" />
									</div>
									<div class="col-md-6">
										<label class="control-label">Tanggal Terima</label>
										<input type="text" id="TglTerima" name="TglTerima" value="<?php echo @$item->TglTerima ?>" class="form-control datepicker" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
		<?php if(@$is_edit): ?>
			<a href="<?php echo base_url("{$nameroutes}/laboratories/examination_result/process/{$item->NoBukti}")?>" data-toggle="lookup-ajax-modal" class="btn btn-info" ><i class="fa fa-list"></i> Hasil Pemeriksaan</a>
		<?php endif; ?>
    	<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>  <?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> <?php echo lang( 'buttons:reset' ) ?></button>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = {
		init: function(){
			$("form[name=\"form_poly\"]").on("submit", function(e){
				e.preventDefault();	
				
				if( !confirm("<?php echo lang("laboratory:save_confirm_message")?>") ){
					return false;
				}
							
				_form.createExamination();
			});
		},
		createExamination: function(){			
			try{
				var data_post = { };
					data_post['lab'] = {};
					data_post['service'] = {};
					data_post['service_component'] = {};
					data_post['service_consumable'] = {};
					data_post['checkout'] = {};
					
				/*
					INSERT INTO SIMtrRJ (NoBukti, Tanggal, Jam, NRM, NamaPasien, JenisKelamin, Umur_TH" & _
					", Umur_Bln, Umur_Hr, Keterangan, Accept, DokterID, AnalisID, JenisPasien, RawatInap, LokasiPasien" & _
					", RegNo, PakeReg, KamarNo, SectionID, adahasil, Rujukan" & _
					", SectionAsalID,UserID,KdKelas,nomor,PPN,MCU,NoMCU,SupplierPengirimID,TransferSectionID," & _
					" TransferDokterID,JenisKerjasamaID,DirujukVendorID,Dirujuk" & _
					 ",KategoriPlafon,NamaKasus,SudahTerpakai,NilaiPlafon,TerpakaiRIPerTahun" & _
					",TerpakaiRIPerOpname,PlafonRIPerTahun,PlafonRIPerOpname,PlafonRINominal,
					TerpakaiNominalRI,Cito,RujukanDariVendorID,KeteranganPemeriksaan,TglLahir,
					CustomerKerjasamaID,Diagnosa,AlasanDiRujukID,TglKirim,TglTerima,TglInput)"
					
				*/
					
				data_post['lab'] = {
					NoBukti : $("#NoBukti").val(),
					RegNo : $("#NoReg").val(),
					JenisKerjasamaID : $("#JenisKerjasamaID").val(),
					SectionID : "<?php echo $item->SectionID; ?>",
					SectionAsalID : "<?php echo $item->SectionAsalID; ?>",
					DokterID : $("#DokterID").val(),
					AnalisID : $("#AnalisID").val(),
					TransferDokterID : $("#TransferDokterID").val(),
					DokterPengirimID : $("#DokterPengirimID").val(),
					SupplierPengirimID : $("#SupplierPengirimID").val(),
					TransferSectionID : $("#TransferSectionID").val(),
					Dirujuk : $("#Dirujuk").is(':checked') ? 1 : 0,
					DirujukVendorID : $("#DirujukVendorID").val(),
					AlasanDiRujukID : $("#AlasanDirujukID").val(),
					TglKirim : $("#TglKirim").val(),
					TglTerima : $("#TglTerima").val(),
					RujukanDariVendorID : $("#RujukanDariVendorID").val(),
					KeteranganPemeriksaan : $("#KeteranganPemeriksaan").val(),
					Symptom : $("#Symptom").val(),
					Therapi : $("#Therapi").val(),
					Cito: 0,
					Kecelakaan : $("#Kecelakaan").val(),
					DOA : $("#DOA:checked").val() || 0,
					DC : $("#DC:checked").val() || 0,
					DC_Hari : $("#DC_Hari").val() == 1 ? $("#DC_Hari").val() : '',
					KasusPertama : $("#KasusPertama:checked").val() || 0,
					Umur_Th : $("#UmurThn").val(),
					Umur_Bln : $("#UmurBln").val(),
					Umur_Hr : $("#UmurHr").val(),
					NRM : $("#NRM").val(),
					Gender : $("#JenisKelamin").val(),
					NamaPasien : $("#NamaPasien").val(),
					JenisKelamin : $("#JenisKelamin").val(),
					TglLahir : $("#TglLahir").val(),
					CustomerKerjasamaID : $("#CustomerKerjasamaID").val(),
					KdKelas: $("#KdKelas").val(),
					UserID : "<?php echo $user->User_ID?>",
					TindakLanjutCekUpUlang : $("#TindakLanjutCekUpUlang:checked").val() || 0,
					TglCekUp : $("#TglCekUp").val(),
					TindakLanjut_KonsulMedik : $("#TindakLanjut_KonsulMedik:checked").val() || 0,
					TindakLanjut_RI : $("#TindakLanjut_RI:checked").val() || 0,
					Konsul_DOkterID : $("#TindakLanjut_RI").is(':checked') ? $("#Konsul_DOkterID").val() : '',
				}
				
				data_post['checkout'] = {
					PxKeluar_Pulang : $("#PxKeluar_Pulang:checked").val() || 0,							
					PxKeluar_Dirujuk : $("#PxKeluar_Dirujuk:checked").val() || 0,
					PxKeluar_DirujukKeterangan : $("#PxKeluar_DirujukKeterangan").val(),
					PxMeninggal : $("#PxMeninggal:checked").val() || 0,
					MeninggalSblm48 : $("#MeninggalSblm48:checked").val() || 0,
					MeninggalStl48 : $("#MeninggalStl48:checked").val() || 0,
					MeninggalTgl : $("#Meninggal:checked").val() == 1 ? $("#MeninggalTgl").val() : '',
					MeninggalJam : $("#Meninggal:checked").val() == 1 ? $("#MeninggalJam").val() : '',
				}
				
				var dt_services = $( "#dt_services" ).DataTable().rows().data();
				if ( dt_services )
				{					
					dt_services.each(function (value, index) {
						var detail = {
							NoBukti : $("#NoBukti").val(),
							JasaID	: value.JasaID,
							DokterID	: value.DokterID || "XX",
							KelasAsalID	: "XX",
							KelasID : "XX",
							Titip : 0,
							ListHargaID : value.ListHargaID,
							Qty : value.Qty,
							Tarif : value.Tarif,
							Keterangan: $('#DocterName').val() || 'XX',
							UserID : value.User_id,
							NoKartu : $("#NoAnggota").val(),
							NRM : $("#NRM").val(),
							JenisKerjasamaID : $("#JenisKerjasamaID").val(),
							Waktu : "<?php echo date("Y-m-d H:i:s") ?> ",
							Jam : "<?php echo date("Y-m-d H:i:s") ?> ",
						}
						
						data_post['service'][index] = detail;
						
						if ( value.component_temp )
						{
							// service component
							data_post['service_component'][value.JasaID] = {};
							$.each(value.component_temp, function (key, val) {
								data_post['service_component'][value.JasaID][key] = {
									NoBukti : $("#NoBukti").val(),
									//Nomor : no_comp,
									JasaID : value.JasaID,
									KomponenID : val.KomponenID,
									Harga : val.HargaBaru,
									KelompokAkun : val.KelompokAkun,
									PostinganKe : val.PostinganKe,
									HargaOrig : val.HargaAwal,
									HargaAwal : val.HargaAwal,
									HargaAwalOrig : val.HargaAwal,
									HargaOrigMA : val.HargaAwal,
									ListHargaID : val.ListHargaID,
								}
							});
						}
						
						if ( value.consumable_temp )
						{
							// service service_consumable
							data_post['service_consumable'][value.JasaID] = {};
							$.each(value.consumable_temp, function (key, val) {
								data_post['service_consumable'][value.JasaID][key] = {
									NoBUkti : $("#NoBukti").val(),
									//Nomor : no_bhp,
									JasaID : value.JasaID,
									Barang_ID : val.Barang_ID,
									Satuan : val.Satuan,
									Qty : val.Qty,
									Disc : val.Disc,
									Harga : val.Harga,
									HPP : val.HPP,
									RI : 0,
									KelasID : "xx",
									PasienKTP : $("#PasienKTP").val(),
									Stok : val.Stok,
									Ditanggung : 1,
									JenisBarangId : 0,
									Qty_JasaID : 1,
									HargaOrig : val.HargaOrig,
								}
							});
						}
					});
				}
				
				$.post($("form[name=\"form_poly\"]").attr("action"), data_post, function( response, status, xhr ){
					if( "error" == response.status ){
						$.alert_error(response.status);
						return false
					}							
					if( !response.NoBukti ){
						$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
						return false
					}
					
					$.alert_success( response.message );
					_form.afterPost();
				});
										
			} catch (e){ console.log(e);}
		},
		afterPost: function(){
			setTimeout(function(){														
				document.location.href = "<?php echo base_url("{$nameroutes}"); ?>";								
				}, 300 );
		}
	}
	
	$( document ).ready(function(e) {			
							
		_form.init();
			
		function getAge(dateString) {
		  var now = new Date();
		  var today = new Date(now.getYear(),now.getMonth(),now.getDate());
		
		  var yearNow = now.getYear();
		  var monthNow = now.getMonth();
		  var dateNow = now.getDate();
			// yyyy-mm-dd
		  var dob = new Date(dateString.substring(0,4), //yyyy
							 dateString.substring(5,7)-1, //mm               
							 dateString.substring(8,10)    //dd            
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
			var monthAge = 12 + monthNow -monthDob;
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

})( jQuery );
//]]>
</script>