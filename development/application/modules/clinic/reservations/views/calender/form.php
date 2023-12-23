<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), array("id" => "form_reservations", "name" => "form_reservations")); ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<style>
	#calendar {
		max-width: 100%;
		margin: 0 auto;
		font-size: 12px !important;
	},
	.fc-direction-ltr .fc-daygrid-event.fc-event-end, .fc-direction-rtl .fc-daygrid-event.fc-event-start {
		margin-right: 2px;
		font-size: 10px !important;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo "Calender Reservasi" ?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('reservations:doctor_name_label') ?> <span class="text-danger"></span></label>
							<div class="col-lg-9">
								<input type="hidden" id="UntukDokterID" name="f[UntukDokterID]" value="<?php echo @$item->UntukDokterID ?>" placeholder="" class="form-control">
								<div class="input-group">
									<input type="text" id="NamaDokter" name="p[NamaDokter]" value="<?php echo @$doctor->Nama_Supplier ?>" placeholder="" class="form-control">
									<span class="input-group-btn">
										<a href="<?php echo $lookup_doctor ?>" data-toggle="lookup-ajax-modal" class="btn btn-success"><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="" class="btn btn-danger"><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<a href="javascript:;" id="refresh" class="btn btn-info" style="width:100%;"><i class="fa fa-refresh"></i> REFRESH</a>
						</div>
					</div>
				</div>
				<div class="row">
					<div id='calendar'></div>
				</div>
			</div>
			<!-- Start popup dialog box -->
			<div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
				<div class="modal-dialog modal-md" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modalLabel">Add Reservasi</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="img-container">
								<div class="form-group">
									<label class="col-lg-3 control-label">Pasien Baru</label>
									<div class="col-lg-3">
										<div class="checkbox">
											<input type="checkbox" id="PasienBaru" name="f[PasienBaru]" value="1" <?php echo $item->PasienBaru == 1 ? "checked" : NULL ?>><label for="PasienBaru">&nbsp;</label>
										</div>
									</div>
								</div>
								<div class="form-group" id="patient_nrm">
									<label class="col-sm-3 control-label">Nama <span class="text-danger">*</span></label>
									<div class="col-sm-9"> 
										<div class="input-group">
											<input type="hidden" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control create-reservasi" readonly>
											<input type="hidden" id="NoUrut" name="f[NoUrut]" value="<?php echo @$item->NoUrut ?>" placeholder="" class="form-control create-reservasi" readonly>
											<input type="text" id="NamaPasien" name="f[Nama]" value="<?php echo @$item->Nama ?>" placeholder="" class="form-control create-reservasi" required="required">
											<span class="input-group-btn" >
												<a href="<?php echo $lookup_patient ?>" id="btn_lookup_patient" data-toggle="lookup-ajax-modal" class="btn btn-success"><i class="fa fa-search"></i></a>
												<a href="javascript:;" id="clear_patient" class="btn btn-danger"><i class="fa fa-times"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Tanggal <span class="text-danger">*</span></label>
									<div class="col-sm-9">  
										<div class="form-group">
											<input type="date" name="f[Tanggal]" id="UntukTanggal" value="<?php echo @$item->UntukTanggal ?>" class="form-control create-reservasi" placeholder="Tanggal">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo lang('reservations:section_label') ?> <span class="text-danger">*</span></label>
									<div class="col-lg-9">
										<select id="UntukSectionID" name="f[UntukSectionID]" class="form-control create-reservasi" required>
											<option value="">-- Section Tujuan --</option>
											<?php if (!empty($option_section)) : foreach ($option_section as $row) : ?>
													<option value="<?php echo $row->SectionID ?>" <?php echo $row->SectionID == @$item->UntukSectionID ? "selected" : NULL  ?>><?php echo $row->SectionName ?></option>
											<?php endforeach;
											endif; ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Jam <span class="text-danger">*</span></label>
									<div class="col-sm-9">   
										<div class="form-group">
											<input type="text" id="Waktu" name="f[Waktu]" value="<?php echo substr(@$item->Waktu, 11, 5) ?>" placeholder="" class="form-control create-reservasi" autocomplete="off">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Pesan</label>
									<div class="col-sm-9">  
										<div class="form-group">
											<textarea id="Memo" name="f[Memo]" placeholder="" class="form-control create-reservasi"><?php echo @$item->Memo ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary"><?php echo lang('buttons:submit') ?></button>
						</div>
					</div>
				</div>
			</div>
			<!-- End popup dialog box -->
		</div>
	</div>
</div>
<script>
	
	var calendarEl = document.getElementById('calendar');
	var events = new Array();
	var calendar = new FullCalendar.Calendar(calendarEl, {
		headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay'
		},
		initialDate: "<?= date('Y-m-d') ?>",
		navLinks: true, // can click day/week names to navigate views
		selectable: true,
		selectMirror: true,
		editable: false,
		dayMaxEvents: true, // allow "more" link when too many events
		eventClick: function(args) {
			// Parse the date string using Moment.js
			var date = moment(args.event.start);

			// Format the date in your desired format (e.g., "MMMM Do YYYY, h:mm a")
			var formattedDate = date.format("dddd, MMMM DD YYYY, h:mm A");
			alert('Nama Pasien : ' + args.event._def.title + '\nTanggal : ' + formattedDate + '\nMEMO : ' + args.event._def.extendedProps.description);
		},
		events: events,
		select: function(event) {  
			$('#event_entry_modal').modal('show');
			var Tanggal = event.startStr;
			$('#UntukTanggal').val(Tanggal);
		},
		eventRender: function(event, element, view) { 
			element.bind('click', function() {
					alert(event.event_id);
				});
		}
	}); //end fullCalendar block

		
	$(document).ready(function() {
		$("form[name=\"form_reservations\"]").on("submit", function(e) {
			e.preventDefault();
			var data_post = {};

			
			data_post = {
				UntukDokterID: $('#UntukDokterID').val(),
				Nama: $('#NamaPasien').val(),
				UntukTanggal: $('#UntukTanggal').val(),
				UntukSectionID: $('#UntukSectionID').val(),
				Waktu: $('#Waktu').val(),
				Memo: $('#Memo').val(),
				NoUrut: $('#NoUrut').val(),
				NRM: $('#NRM').val(),
			}

					
			$.post($("form[name=\"form_reservations\"]").attr("action"), data_post, function(response, status, xhr) {
				if ("error" == response.status) {
					$.alert_error(response.message);
					return false
				}

				$.alert_success(response.message);

				$('.create-reservasi').val("");
				$('#refresh').trigger('click');
				$('.close').trigger('click');
			});
		});

		$("input[type='checkbox']").on('change', function() {
			if ($('#PasienBaru').is(":checked")) {
				$('#btn_lookup_patient').hide();
			} else {
				$('#btn_lookup_patient').show();
			}
		});

		$("#clear_patient").on('click', function() {
			$("#NRM").val("");
			$("#NamaPasien").val("");
		});

		$("#UntukTanggal").datetimepicker({
			format: "YYYY-MM-DD"
		}).on("dp.change", function(e) {
			var weekday = ["MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU"];
			var d = new Date($(this).val());
			var dayName = weekday[d.getDay()];

			$("#UntukHari").val(dayName);
			get_reservation_queue();

		});

		$('#Waktu').datetimepicker({
			// datepicker: false,
			format: 'HH:mm'
		});

		// get Antrian Reservasi
		$("#UntukSectionID, #Waktu").on("change", function(e) {
			get_reservation_queue();

		});

		function get_reservation_queue() {
			if ($("#UntukSectionID").val() == "" || $("#UntukDokterID").val() == "" || $("#UntukTanggal").val() == "" || $("#Waktu").val() == "") {
				return false;
			}

			var data_post = {
				f: {
					"UntukSectionID": $("#UntukSectionID").val(),
					"UntukDokterID": $("#UntukDokterID").val(),
					"UntukTanggal": $("#UntukTanggal").val(),
					"Waktu": $("#Waktu").val(),
				}
			}
			$.get("<?php echo $get_reservation_queue ?>", data_post, function(response, status, xhr) {
				var NoUrut = response.NoUrut;
				$("#NoUrut").val(NoUrut);
			});

		}

		function get_reservation() {
			if ($("#UntukDokterID").val() == "" || $("#UntukTanggal").val() == "" || $("#Waktu").val() == "") {
				return false;
			}

			var data_post = {
				f: {
					"UntukDokterID": $("#UntukDokterID").val(),
					"UntukTanggal": $("#UntukTanggal").val(),
					"Waktu": $("#Waktu").val(),
				}
			}
			$.get("<?php echo $get_reservation ?>", data_post, function(response, status, xhr) {
				console.log(response);
				if (response == 1) {
					alert("Jam Ini Sudah Terisi Pasien!");
					return false;
				};
			});

		}

		calendar.render();
		$("#refresh").on("click", function(e) {
			if ($("#UntukDokterID").val() == '') {
				$.alert_error('Silahkan Pilih Dokter Terlebih Dahulu.');
				return false;
			}

			try {
				
				var data_post = {
					"DokterID": $("#UntukDokterID").val(),
				}
				$.post("<?php echo $get_calender ?>", data_post, function(response, status, xhr) {
					calendar.removeAllEvents();

					$.each(response, function(index, item) { 
						calendar.addEvent({
							title: item.title,
							start: item.start,
							color: item.color,
							description: item.description,
						});

					});

				});

			} 
			catch (e) {
				$.alert_error(e);
				return false;
			}
		});

	})
</script>