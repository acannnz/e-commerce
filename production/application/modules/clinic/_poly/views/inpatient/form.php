<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url(), array("name" => "form_poly") ); ?>
<?php if ( @$item->ProsesPayment == 1 || (!empty($item->StatusBayar) && @$item->StatusBayar == "Sudah Bayar" ) ): ?>
	<h3 class="subtitle well">Status Data: <span class='text-info'><?php echo $item->ProsesPayment ? 'Proses' : $item->StatusBayar ?></span> di Kasir.</h3>
<?php endif; ?>
<?php if ( @$item->Batal == 1  ): ?>
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
				<input type="hidden" id="SectionID" name="f[SectionID]" value="<?php echo $item->SectionID; ?>" />
				<input type="hidden" id="PasienKTP" name="f[PasienKTP]" value="<?php echo !empty($item->PasienKTP) ? $item->PasienKTP : $item->PasienKTP ?>" />
				<input type="hidden" id="Kamar" name="f[Kamar]" value="<?php echo $item->Kamar; ?>" />
				<input type="hidden" id="NoBed" name="f[NoBed]" value="<?php echo $item->NoBed; ?>" />
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:registration_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="RegNo" name="f[RegNo]" value="<?php echo !empty($item->NoReg) ? $item->NoReg : $item->RegNo ?>" placeholder="" class="form-control"  readonly="readonly">
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
					<label class="col-lg-3 control-label"><?php echo lang('poly:address_label') ?></label>
					<div class="col-lg-9">
						<textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" disabled><?php echo @$item->Alamat ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:gender_label') ?></label>
					<div class="col-lg-9">
						<select id="JenisKelamin" name="p[JenisKelamin]" class="form-control patient" disabled>
							<option value="F" <?php echo @$item->JenisKelamin == "F"  ? "selected" : NULL  ?>>Perempuan</option>
							<option value="M" <?php echo @$item->JenisKelamin == "M"  ? "selected" : NULL  ?>>Laki-laki</option>
						</select>
					</div>
				</div>
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
					<label class="col-lg-3 control-label"><?php echo lang('poly:type_patient_label') ?></label>
					<div class="col-lg-9">
						<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control" disabled="disabled">
							<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
							<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:company_code_label') ?></label>
					<div class="col-lg-9">
						<input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>" class="cooperation">
						<input type="hidden" id="KelasAsalID" name="f[KelasAsalID]" value="<?php echo @$item->KelasAsalID ?>">
						<input type="hidden" id="KdKelas" name="f[KdKelas]" value="<?php echo @$item->KdKelas ?>"  class="cooperation">
						<input type="text" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->Kode_Customer ?>" placeholder="" class="form-control cooperation" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('poly:company_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPerusahaan"  value="<?php echo @$item->NamaPerusahaan ?>" placeholder="" class="form-control cooperation" disabled="disabled">
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
		<h3 class="panel-title"><?php echo sprintf('%s: (%s) %s', lang('poly:examination_label'), @$item->NoReg, @$item->NamaPasien); ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo $form_doctor_treat ?>" data-toggle="form-ajax-modal" class="btn btn-success" title="Lihat Dokter Rawat"><i class="fa fa-user-md"></i> Dokter Rawat</a></li>
			<li><a href="javascript:;" class="btn btn-success panel-collapse" title="Tampilkan"><i class="fa fa-angle-down"></i></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
		<?php /*?>        <div class="form-group">
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
				</div>        <?php */?>
			</div>
			<div class="col-md-6">
				
			</div>
		</div>
		<ul id="tab-poly" class="nav nav-tabs nav-justified">
			<li class="active"><a href="#poly-tab1" data-toggle="tab"><i class="fa fa-stethoscope"></i> Jasa</a></li>
			<li><a href="#poly-tab2" data-toggle="tab"><i class="fa fa-list"></i> Diagnosa</a></li>
			<?php /*?><li><a href="#poly-tab3" data-toggle="tab"><i class="fa fa-user"></i> Perawat</a></li><?php */?>
			<li><a href="#poly-tab4" data-toggle="tab"><i class="fa fa-medkit"></i> Resep</a></li>
			<?php /*?><li><a href="#poly-tab5" data-toggle="tab"><i class="fa fa-files-o"></i> Penunjang</a></li><?php */?>
			<li><a href="#poly-tab6" data-toggle="tab"><i class="fa fa-paperclip"></i> BHP</a></li>
			<li><a href="#poly-tab7" data-toggle="tab"><i class="fa fa-files-o"></i> Memo</a></li>
			<li><a href="#poly-tab8" data-toggle="tab"><i class="fa fa-eyedropper"></i> Penggunaan Alat</a></li>
			<li><a href="#poly-tab9" data-toggle="tab"><i class="fa fa-wheelchair"></i> CheckOut</a></li>
		</ul>
		<div class="tab-content">
			<div id="poly-tab1" class="tab-pane tab-pane-padding active">
				<?php echo modules::run("{$nameroutes}s/service_inpatient/index", @$item->NoReg, @$item->SectionID ) ?>
			</div>
			<div id="poly-tab2" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/diagnosis/index", @$item->NoReg ) ?>
			</div>
			<?php /*?><div id="poly-tab3" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/nurse/index", @$item, @$is_edit ) ?>
			</div><?php */?>
			<div id="poly-tab4" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/prescriptions/index", @$item->NoReg, @$item->SectionID ) ?>
			</div>
			<?php /*?><div id="poly-tab5" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/helpers/index", @$item ) ?>
			</div><?php */?>
			<div id="poly-tab6" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/consumables/index", @$item->NoReg, @$item->SectionID) ?>
			</div>
			<div id="poly-tab7" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/memo/index", @$item->NoReg ) ?>
			</div>
			<div id="poly-tab8" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/tool_usage/index", @$item->NoReg, @$item->SectionID ) ?>
			</div>
			<div id="poly-tab9" class="tab-pane tab-pane-padding">
				<?php echo modules::run("{$nameroutes}s/checkout/index", @$item->NoReg, @$item->SectionID) ?>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">	
			<div class="form-group">
				<div class="col-md-6">
					<a href="<?php echo base_url($nameroutes) ?>" class="btn btn-default btn-block"><i class="fa fa-arrow-left"></i> <?php echo lang( 'buttons:back' ) ?></a>
				</div>
				<div class="col-md-6">
					<?php if ( $item->Batal == 0 && ( @$item->ProsesPayment == 0 || (!empty($item->StatusBayar) && @$item->StatusBayar != "Belum" )) && @$item->StatusPeriksa != "CO" ): ?>
					<button type="submit" class="btn btn-primary btn-block" id="js-btn-submit"><i class="fa fa-save"></i> <?php echo lang( 'buttons:submit' ) ?></button>
					<?php endif; ?>
				</div>
			</div>
		</div>		
	</div>
</div>

<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		$( document ).ready(function(e) {			
				<?php if ( @$item->ProsesPayment == 1 || (!empty($item->StatusBayar) && @$item->StatusBayar == "Sudah Bayar" ) ): ?>
				$("form[name=\"form_poly\"]").find("a[id^='add_'], .btn-remove").remove();
				<?php endif; ?>
				
				$("form[name=\"form_poly\"]").on("submit", function(e){
					e.preventDefault();	
					
					if( !confirm("<?php echo lang("poly:save_confirm_message")?>") ){
						return false;
					}
					
					if($('input[name="f[checkout]"]').is(':checked') || $('#DokterRawatID').val() != '' || $('#DiagnosaAkhirID').val() != '')
					{
						if(! $('input[name="f[checkout]"]').is(':checked')){
							$.alert_warning('Status pasien Checkuot belum diisi.');
							return false;
						}
						if($('#DokterRawatID').val() == ''){
							$.alert_warning('Dokter Rawat Checkuot belum diisi.');
							return false;
						}
						if($('#DiagnosaAkhirID').val() == ''){
							$.alert_warning('Diagnosa akhir belum diisi');
							return false;
						}
					}
					
					try{
						var data_post = { };
							data_post['inpatient'] = {};
							data_post['diagnosis'] = {};
							/*data_post['nurse'] = {};*/
							data_post['helper'] = {};
							data_post['tool_usage'] = {};

						data_post['inpatient'] = {
								NoReg : '<?php echo $item->NoReg ?>',
								SectionID : '<?php echo $item->SectionID ?>',
								NRM : '<?php echo $item->NRM ?>',
								NamaPasien : '<?php echo $item->NamaPasien ?>',
								NoKamar : '<?php echo $item->Kamar ?>',
								NoBed : '<?php echo $item->NoBed ?>',
								PxKeluar_Pulang : $("#PxKeluar_Pulang:checked").val() || 0,
								PxKeluar_Dirujuk : $("#PxKeluar_Dirujuk:checked").val() || 0,
								PxKeluar_PlgPaksa : $("#PxKeluar_PlgPaksa:checked").val() || 0,
								PxMeninggal : $("#Meninggal:checked").val() || 0,
								MeninggalSblm48 : $("#MeninggalSblm48:checked").val() || 0,
								MeninggalStl48 : $("#MeninggalStl48:checked").val() || 0,
								MeninggalTgl : $("#Meninggal:checked").val() == 1 ? $("#MeninggalTgl").val() : '',
								MeninggalJam : $("#Meninggal:checked").val() == 1 ? $("#MeninggalJam").val() : '',
								DokterRawatID : $("#DokterRawatID").val(),
								DiagnosaAkhirID : $("#DiagnosaAkhirID").val(),
								KeteranganDiagnosa : $("#KeteranganDiagnosa").val(),
							}

						var dt_diagnosis = $( "#dt_diagnosis" ).DataTable().rows().data();				
						if( dt_diagnosis)	
						{
							dt_diagnosis.each(function (value, index) {
								var detail = {
									KodeICD	: value.KodeICD,
									Keterangan : '',
									Ditanggung : 1,
									NoKartu : $("#NoAnggota").val(),
									JenisKerjasamaID : $("#JenisKerjasamaID").val(),
								}
								
								data_post['diagnosis'][index] = detail;
							});
						} 
						
						if($.isEmptyObject(data_post['diagnosis'])){
							$.alert_warning('Data Diagnosa Belum diisi!');
							return false;
						}
						
						var dt_tool_usage = $( "#dt_tool_usage" ).DataTable().rows().data();				
						if( dt_tool_usage)	
						{
							dt_tool_usage.each(function (v, i) {
								if(v.IDAlat == '') 
									return true;
								
								var detail = {
									NoBukti : '', 
									NoReg : v.NoReg, 
									SectionID : v.SectionID,
									Tanggal : v.Tanggal,
									Jam: v.Tanggal +' '+ v.Jam, 
									IDAlat : v.IDAlat,
									Jml : v.Jml,
								}
								
								data_post['tool_usage'][i] = detail;
							});
						} 
						
						/*var dt_nurses = $( "#dt_nurses" ).DataTable().rows().data();	
						if ( dt_nurses )
						{
							dt_nurses.each(function (value, index) {
								var detail = {
									PerawatID	: value.Kode_Supplier,
									Kategori : 'Jaga',
								}
								
								data_post['nurse'][index] = detail;
							});
						}*/
						
						var dt_helpers = $( "#dt_helpers" ).DataTable().rows().data();					
						if ( dt_helpers )
						{
							dt_helpers.each(function (value, index) {
								var detail = {
									NoBuktiHeader : $("#NoBukti").val(),
									NoBuktiMemo : value.NoBuktiMemo,
									DokterID	: value.DokterID,
									SectionID	: "<?php echo $item->SectionID; ?>",
									Tanggal : "<?php echo date("Y-m-d") ?>",
									Jam : "<?php echo date("Y-m-d") ?> "+ d.getHours() +":"+ d.getMinutes() +":"+ d.getSeconds(),
									SectionTujuanID	: value.SectionTujuanID,
									Memo	: value.Memo,
									UserID : "<?php echo $user->User_ID?>",
									NoReg : $("#RegNo").val(),
									JenisKerjasamaID : $("#JenisKerjasamaID").val(),
									UmurThn : $("#UmurThn").val(),
									UmurBln : $("#UmurBln").val(),
									UmurHr : $("#UmurHr").val(),
								}
								
								data_post['helper'][index] = detail;
							});
						}
												
						$.post($(this).attr("action"), data_post, function( response, status, xhr ){
							if( "error" == response.status ){
								$.alert_error(response.status);
								return false
							}							
							if( !response.NoBukti ){
								$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
								return false
							}
							
							$.alert_success( response.message );							
							setTimeout(function(){														
								document.location.href = "<?php echo base_url("{$nameroutes}"); ?>";								
								}, 300 );
							
						})	
					} catch (e){ console.log(e);}
				});
			

			});

	})( jQuery );
//]]>
</script>