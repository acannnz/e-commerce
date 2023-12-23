<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), ["name" => "form_transfer_inpatient"] ); ?>
<div class="panel panel-info panel-collapsed">
	<div class="panel-heading panel-collapse">
		<h3 class="panel-title"><?php echo lang('registrations:patient_label'). ": ({$item->NRM}) {$patient->NamaPasien}" ?></h3>
		<ul class="panel-btn">
			<li><a href="javascript:;" class="btn btn-info panel-collapse" title="Tampilkan"><i class="fa fa-angle-down"></i></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('registrations:registration_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="RegNo" name="f[RegNo]" value="<?php echo !empty($item->NoReg) ? $item->NoReg : $item->RegNo ?>" placeholder="" class="form-control"  readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('registrations:mr_number_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('registrations:patient_name_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$patient->NamaPasien ?>" placeholder="" class="form-control patient" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('registrations:type_patient_label') ?></label>
					<div class="col-lg-9">
						<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control" disabled="disabled">
							<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
							<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('registrations:gender_label') ?></label>
					<div class="col-lg-9">
						<select id="JenisKelamin" name="p[JenisKelamin]" class="form-control patient" disabled>
							<option value="F" <?php echo @$patient->JenisKelamin == "F"  ? "selected" : NULL  ?>>Perempuan</option>
							<option value="M" <?php echo @$patient->JenisKelamin == "M"  ? "selected" : NULL  ?>>Laki-laki</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('registrations:dob_label') ?></label>
					<div class="col-lg-3">
						<input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$patient->TglLahir ?>" placeholder="" class="form-control datepicker patient" disabled>
					</div>
					<label class="col-lg-1 control-label text-center"><?php echo lang('registrations:age_label') ?></label>
					<div class="col-lg-1">
						<input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" placeholder="" class="form-control" readonly>
					</div>
					<label class="col-lg-1 control-label"><?php echo lang('registrations:year_label') ?></label>
					<div class="col-lg-1">
						<input type="text" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>" placeholder="" class="form-control" readonly>
						<input type="hidden" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>">
					</div>
					<label class="col-lg-1 control-label"><?php echo lang('registrations:month_label') ?></label>
				</div>    
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('registrations:address_label') ?></label>
					<div class="col-lg-9">
						<textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" disabled><?php echo @$patient->Alamat ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('registrations:transfer_inpatient_create_heading') ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<h3 class="subtitle text-center"><?php echo lang('registrations:registration_number_label').': '.@$item->NoReg ?></h3>
				<hr/>  
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-6">
				<!-- Section Tujuan-->
				<?php /*?><h3 class="subtitle">Section <?php echo lang('registrations:destionation_subtitle') ?></h3><?php */?>
				<div class="form-group">
					<div class="row">
						<?php echo form_input(['type' => 'hidden', 'name' => 'f[RawatInap]', 'id' => 'RawatInap', 'value' => 1]); ?>
						<div class="col-md-6">
							<?php echo form_label('Section *', 'SectionID', ['class' => 'control-label']) ?>
							<div class="input-group">
								<?php echo form_input(['type' => 'hidden', 'id' => 'SectionID', 'name' => 'SectionID', 'class' => 'section']); ?>
								<?php echo form_input(['id' => 'SectionIDName', 'class' => 'form-control section']); ?>
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_section ?>" data-toggle="lookup-ajax-modal" class="btn btn-default section" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" id="clear_section" class="btn btn-default" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
						<div class="col-md-6">
							<?php echo form_label('Kelas *', 'KdKelas', ['class' => 'control-label']) ?>
							<?php echo form_dropdown('f[KdKelas]', @$option_class, set_value('f[KdKelas]', @$item->KdKelas, TRUE), [
								'id' => 'KdKelas', 
								'placeholder' => '',
								'required' => 'required', 
								'class' => 'form-control'
							]); ?>
						</div>
					</div>	
				
					<div class="row">
						<div class="col-md-12">
							<label class="control-label"><?php echo lang('registrations:room_label') ?></label>
						</div>
						<div class="col-md-6">								
							<div class="input-group">
								<input type="hidden" id="NoBed" name="f[NoBed]" value="<?php echo @$item->NoBed ?>" placeholder="" class="form-control room">
								<input type="hidden" id="NoKamar" name="f[NoKamar]" value="<?php echo @$item->NoKamar ?>" placeholder="" class="form-control room">
								<input type="text" id="Kamar" name="Kamar" value="<?php echo @$item->NoKamar ?>" placeholder="" class="form-control room">
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_room ?>" data-toggle="lookup-ajax-modal" class="btn btn-default room" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" id="clear_room" class="btn btn-default room" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
						<div class="col-md-6">
							<a href="javascript:;" class="btn btn-info btn-block"><b><i class="fa fa-bed"></i> Update Status Kamar</b></a>
						</div>
					</div>
				
					<div class="row">
						<div class="col-md-12">
							<?php echo form_label('Dokter Rawat *', 'DokterID', ['class' => 'col-md-12 control-label']) ?>
							<div class="input-group">
								<?php echo form_input(['type' => 'hidden', 'id' => 'DokterID', 'name' => 'DokterID', 'class' => 'doctor']); ?>
								<?php echo form_input(['id' => 'DokterIDName', 'class' => 'form-control doctor']); ?>
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_doctor ?>" data-toggle="lookup-ajax-modal" class="btn btn-default doctor" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<!-- Kerja Sama-->
				<?php /*?><h3 class="subtitle"><?php echo lang('registrations:cooperation_subtitle') ?></h3><?php */?>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label class="control-label"><?php echo lang('registrations:company_label') ?></label>
							<div class="input-group">
								<input type="hidden" id="CustomerKerjasamaID" name="f[CustomerKerjasamaID]" value="<?php echo (int) @$item->CustomerKerjasamaID ?>" class="cooperation">
								<input type="hidden" id="KodePerusahaan" name="f[KodePerusahaan]" value="<?php echo @$item->KodePerusahaan ?>" placeholder="" class="cooperation">
								<input type="text" id="Nama_Customer"  value="<?php echo @$cooperation->Nama_Customer ?>" placeholder="" class="form-control cooperation" disabled="disabled">
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_cooperation ?>" id="lookup_cooperation" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" id="clear_cooperation" class="btn btn-default" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
						<div class="col-md-6">
							<label class="col-md-3 control-label"><?php echo lang('registrations:card_number_label') ?></label>
							<label class="control-label">
								<div class="checkbox" style="margin:0">
									<input type="hidden" name="p[AnggotaBaru]" value="0" >
									<input type="checkbox" id="AnggotaBaru" name="p[AnggotaBaru]" value="1" class="" ><label for="AnggotaBaru">Anggota Baru</label>
								</div>
							</label>
							<div class="input-group">
								<input type="text" id="NoAnggota" name="f[NoAnggota]" value="<?php echo @$item->NoAnggota ?>" placeholder="" class="form-control cooperation cooperation_card" >
								<span class="input-group-btn">
									<a href="<?php echo $lookup_patient_cooperation_card ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" id="clear_cooperation_card" class="btn btn-default" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
					
					<!-- Pertanggungan Kedua -->
					<div class="row">
						<div class="col-md-6">
							<label class="col-md-6 control-label"><?php echo lang('registrations:second_company_label') ?></label>
							<label class="control-label">
								<div class="checkbox" style="margin:0;">
									<input type="hidden" name="f[PertanggunganKeduaIKS]" value="" >
									<input type="checkbox" id="PertanggunganKeduaIKS" class="second_insurer" name="f[PertanggunganKeduaIKS]" value="1" <?php echo @$item->PertanggunganKeduaIKS == 1 ? "Checked" : NULL ?>><label for="PertanggunganKeduaIKS">IKS</label>
								</div>
							</label>
							<div class="input-group">
								<input type="hidden" id="PertanggunganKeduaCustomerKerjasamaID" class="second_insurer" name="f[PertanggunganKeduaCustomerKerjasamaID]" value="<?php echo  @$item->PertanggunganKeduaCustomerKerjasamaID ?>" >
								<input type="hidden" id="PertanggunganKeduaCompanyID" class="second_insurer" name="f[PertanggunganKeduaCompanyID]" value="<?php echo @$item->PertanggunganKeduaCompanyID ?>" >
								<input type="text" id="PertanggunganKeduaCompanyNama" value="<?php echo @$second_insurer->Nama_Customer ?>" placeholder="" class="form-control second_insurer" >
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_second_insurer ?>" data-toggle="lookup-ajax-modal" class="btn btn-default second_insurer disabled" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" id="clear_second_insurer" class="btn btn-default second_insurer disabled" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
						<div class="col-md-6">								
							<label class="control-label"><?php echo lang('registrations:card_number_label') ?></label>
							<div class="input-group">
								<input type="text" id="PertanggunganKeduaNoKartu" name="f[PertanggunganKeduaNoKartu]" value="<?php echo @$item->PertanggunganKeduaNoKartu ?>" placeholder="" class="form-control second_insurer second_insurer_card">
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_patient_second_insurer_card ?>" data-toggle="lookup-ajax-modal" class="btn btn-default second_insurer disabled" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" id="clear_second_insurer_card" data-target=".second_insurer" class="btn btn-default btn-clear disabled" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<label class="control-label"><?php echo lang('registrations:memo_label') ?></label>
							<textarea id="Keterangan" name="f[Keterangan]" placeholder="" class="form-control"><?php echo @$item->Keterangan ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<a href="<?php echo base_url('registration/transfer_inpatient')?>" class="btn btn-default btn-block"><?php echo lang( 'buttons:cancel' ) ?></a>
				</div>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block"><?php echo lang( 'buttons:submit' ) ?></button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("form[name=\"form_transfer_inpatient\"]");
		var _form_actions = {
				init: function(){
										
						// Btn Clear
						$(".btn-clear").on("click", function(){
							var data_target = $(this).data('target');
							_form.find(data_target).val('');					
						});
						
						// UNTUK Pertanggungan Kedua
						// JIka Type Pasien Adalah IKS
						$("#JenisKerjasamaID").on("change", function(){
							if ( $(this).val() == 2 ){
								_form.find(".second_insurer").val('');
								_form.find(".second_insurer").prop("checked", false);
								_form.find(".second_insurer").prop("disabled", true);
								_form.find(".second_insurer").addClass("disabled");
							} else {
								_form.find(".second_insurer").prop("disabled", false);
								_form.find(".second_insurer").removeClass("disabled");
							}
						});
		
						$("#PertanggunganKeduaIKS").on("change", function(){
							if( $(this).is(':checked'))
							{
								_form.find(".second_insurer").prop("disabled", false);
								_form.find(".second_insurer").removeClass("disabled");
							} else {
								_form.find(".second_insurer").val('');
								_form.find(".second_insurer").prop("disabled", true);
								_form.find(".second_insurer").addClass("disabled");
								_form.find(this).prop('disabled', false);
							}
		
						});			
						
						$( "#print" ).on( "click", function() {
							var nrm = $("#NRM").val();
							var dob = $("#TglLahir").val();
							if( confirm( "Cetak Data Label ?" ) ){
								window.open("<?php echo base_url() ?>registrations/print_report/" + nrm + "/" + dob + "/true");
							}							
						});

				
					},
			};
		
		
		$( document ).ready(function(e) {
			
				_form_actions.init();
												
				_form.on("submit", function(e){
					e.preventDefault();
					
					if( $('#JenisKerjasamaID').val() != 3 && ( $('#CustomerKerjasamaID').val() == '' || $('#CustomerKerjasamaID').val() == 0 ))
					{
						$.alert_warning('Tidak bisa menyimpan data, Perusahaan Kerja Sama belum dipilih!');
						return false;
					}	
					
					if (!confirm("Apakah Anda ingin menyimpan data ini ?"))
					{
						return false;
					}
										
					var data_post = $(this).serializeArray();	
					data_post.push({name: 'f[RawatInap]', value: 1});
					data_post.push({name: 'f[SectionID]', value: $("#SectionID").val()});
					data_post.push({name: 'f[DokterRawatID]', value: $("#DokterID").val()});
										
					data_post.push({name: 'destinations[SectionID]', value: $("#SectionID").val()});
					data_post.push({name: 'destinations[DokterID]', value: $("#DokterID").val()});
					data_post.push({name: 'destinations[JenisKerjasamaID]', value: $("#JenisKerjasamaID").val()});
					data_post.push({name: 'destinations[UmurThn]', value: $("#UmurThn").val()});
					data_post.push({name: 'destinations[UmurBln]', value: $("#UmurBln").val()});
					data_post.push({name: 'destinations[UmurHr]', value: $("#UmurHr").val()});					
										
					if ( $('#SectionID').val() == '' )
					{
						$.alert_warning( "Transaksi Tidak bisa dilanjutkan, Data Section Belum Terisi!" );
						return false;
					}
					
					if ( $('#NoKamar').val() == '' || $('#NoBed').val() == '' )
					{
						$.alert_warning( "Transaksi Tidak bisa dilanjutkan, Data Kamar Belum Terisi!" );
						return false;
					}
					
					if ( $('#DokterID').val() == '')
					{
						$.alert_warning( "Transaksi Tidak bisa dilanjutkan, Data Dokter Rawat Belum Terisi!" );
						return false;
					}		
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){					
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						if( !response.NoReg ){
							$.alert_error( "Terjadi Kesalahan, Hubungi IT Support!" );
							return false
						}
						
						$.alert_success( response.message );
						setTimeout(function(){													
							//document.location.href = "<?php echo base_url("registrations/transfer_inpatient"); ?>";
							}, 300 );
					})	
				});

								
				
			});

	})( jQuery );
//]]>
</script>