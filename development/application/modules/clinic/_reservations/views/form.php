<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "form_reservations", "name" => "form_reservations") ); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang('reservations:create_heading') ?></h3>
			</div>
            <div class="panel-body">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<h3 class="subtitle text-center"><?php echo lang('reservations:reservation_number_label').': '.@$item->NoReservasi ?></h3>
						<hr/>  
					</div>
				</div>    
				<div class="row">
					<div class="col-md-6">
						<div class="page-subtitle">
							<h3 class="text-primary"><i class="fa fa-user pull-left text-primary"></i><?php echo lang('reservations:patient_label') ?></h3>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:new_patient_label') ?></label>
							<div class="col-lg-3">
								<div class="checkbox">
								  <input type="checkbox" id="PasienBaru" name="f[PasienBaru]" value="1" <?php echo $item->PasienBaru == 1 ? "checked" : NULL ?>><label for="PasienBaru">&nbsp;</label>
								</div>
							</div>
						</div>
						<div class="form-group" id="patient_nrm">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:mr_number_label') ?></label>
							<div class="col-lg-9">
								<div class="input-group">
									<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" readonly>
									<span class="input-group-btn">
										<a href="<?php echo $lookup_patient ?>" data-toggle="lookup-ajax-modal" class="btn btn-success" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="clear_patient" class="btn btn-danger" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:patient_name_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="NamaPasien" name="f[Nama]" value="<?php echo @$item->Nama ?>" placeholder="" class="form-control" required="required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:phone_label') ?></label>
							<div class="col-lg-9">
								<input type="text" id="Phone" name="f[Phone]" value="<?php echo @$item->Phone ?>" placeholder="" class="form-control" required="required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Email</label>
							<div class="col-lg-9">
								<input type="text" id="Email" name="f[Email]" value="<?php echo @$item->email ?>" placeholder="" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:address_label') ?></label>
							<div class="col-lg-9">
								<textarea id="Alamat" name="f[Alamat]" placeholder="" class="form-control" required><?php echo @$item->Alamat ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:type_patient_label') ?></label>
							<div class="col-lg-9">
								<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control" required>
									<option value="">-- Tipe Pasien --</option>
									<?php if(!empty($option_type_patient)): foreach($option_type_patient as $row):?>
									<option value="<?php echo $row['JenisKerjasamaID'] ?>" <?php echo $row['JenisKerjasamaID'] == @$item->JenisKerjasamaID ? "selected" : NULL  ?>><?php echo $row['JenisKerjasama'] ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:memo_label') ?></label>
							<div class="col-lg-9">
								<textarea id="Memo" name="f[Memo]" placeholder="" class="form-control"><?php echo @$item->Memo ?></textarea>
							</div>
						</div>
					</div>
				
					<div class="row">
						<div class="col-lg-6">
							<div class="page-subtitle">
							<h3 class="text-primary"><i class="fa fa-user pull-left text-primary"></i><?php echo lang('reservations:destination_label') ?></h3>
						</div>
						<div class="form-group">
							<a href="<?php echo $lookup_schedule ?>" data-toggle="lookup-ajax-modal" class="btn btn-success pull-right" ><i class="fa fa-search"></i> Cari Jadwal Praktek</a>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:section_label') ?> <span class="text-danger">*</span></label>
							<div class="col-lg-9">
								<select id="UntukSectionID" name="f[UntukSectionID]" class="form-control select" required>
									<option value="">-- Section Tujuan --</option>
									<?php if(!empty($option_section)): foreach($option_section as $row):?>
									<option value="<?php echo $row->SectionID ?>" <?php echo $row->SectionID == @$item->UntukSectionID ? "selected" : NULL  ?>><?php echo $row->SectionName ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:doctor_number_label') ?> <span class="text-danger">*</span></label>
							<div class="col-lg-9">
								<div class="input-group">
									<input type="text" id="UntukDokterID" name="f[UntukDokterID]" value="<?php echo @$item->UntukDokterID ?>" placeholder="" class="form-control" required readonly>
									<span class="input-group-btn">
										<a href="<?php echo $lookup_doctor ?>" data-toggle="lookup-ajax-modal" class="btn btn-success" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="" class="btn btn-danger" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:doctor_name_label') ?> <span class="text-danger">*</span></label>
							<div class="col-lg-9">
								<input type="text" id="NamaDokter" name="p[NamaDokter]" value="<?php echo @$doctor->Nama_Supplier ?>" placeholder="" class="form-control" required readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:date_label') ?> <span class="text-danger">*</span></label>
							<div class="col-lg-3">
								<input type="text" id="UntukTanggal" name="f[UntukTanggal]" value="<?php echo @$item->UntukTanggal ?>" placeholder="" class="form-control" >
							</div>
						</div>  
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:day_label') ?> <span class="text-danger">*</span></label>
							<div class="col-lg-3">
								<input type="text" class="form-control" name="f[UntukHari]" id="UntukHari" value="<?php echo @$item->UntukHari ?>" readonly/>
							</div>
						</div>   
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:time_label') ?> <span class="text-danger">*</span></label>
							<div class="col-lg-3">
								<select id="WaktuID" name="f[WaktuID]" class="form-control select" required>
									<option value=""><?php echo lang("global:select-pick")?></option>
									<?php if(!empty($option_time)): foreach($option_time as $row):?>
									<option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>	
						</div>  
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:queue_label') ?></label>
							<div class="col-lg-1">
								<input type="text" id="NoUrut" name="f[NoUrut]" value="<?php echo @$item->NoUrut ?>" placeholder="" class="form-control" readonly>
							</div>
						</div>
						</div>
					</div>
				
				</div>
				<div class="form-group">
					<div class="col-lg-offset-10 col-lg-12">
						<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
						<?php /*?>//<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button><?php */?>
						<button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	$(document).ready(function() {
		$("input[type='checkbox']").on('change',function(){
			if ($('#PasienBaru').is(":checked"))
			{
				$('#patient_nrm').hide();
			}else{
				$('#patient_nrm').show();
			}
		});
		
		$("#clear_patient").on('click',function(){
			$("#NRM").val("");
			$("#NamaPasien").val("");
			$("#Phone").val("");
			$("#email").val("");
			$("#Alamat").val("");
			$("#JenisKerasamaID").val("");
		});

		$("#UntukTanggal").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
			var weekday = ["MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU"];
			var d = new Date( $(this).val() );
			var dayName = weekday[d.getDay()];
			
			$("#UntukHari").val( dayName );
			get_reservation_queue();
			
		});
		
		// get Antrian Reservasi
		$("#UntukSectionID, #WaktuID").on("change", function(e){
			get_reservation_queue();
			
		});
		
		function get_reservation_queue()
		{
			if ( $("#UntukSectionID").val() == "" || $("#UntukDokterID").val() == "" || $("#UntukTanggal").val() == "" || $("#WaktuID").val() == "" )
			{
				return false;
			}
			
			var data_post = { 
				f: {
					"UntukSectionID" : $("#UntukSectionID").val(),
					"UntukDokterID" : $("#UntukDokterID").val(),
					"UntukTanggal" : $("#UntukTanggal").val(),
					"WaktuID" : $("#WaktuID").val(),
				}
			}
			$.get("<?php echo $get_reservation_queue ?>", data_post, function( response, status, xhr ){
				var NoUrut = response.NoUrut;
				$("#NoUrut").val( NoUrut );			
			});
			
		}
		
		$("#form_reservations").on("submit", function(e){
			if ( $("#UntukDokterID").val() == "" )
			{
				alert("Data Dokter belum Terisi!");
				return false;
			}
			
			if ( ! $('#PasienBaru').is(":checked"))
			{
				if ( $("#NRM").val() == "" )
				{
					alert("No.RM tidak boleh kosong Jika Pasien Baru tidak dicentang!");
					return false;
				}				
			}
		});
		
	})
})( jQuery );
//]]>
</script>