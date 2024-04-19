<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>
	#calendar {
		max-width: 100%;
		margin: 0 auto;
		font-size: 100;
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
						<a href="javascript:;" id="refresh" class="btn btn-info btn-block"><i class="fa fa-refresh"></i> REFRESH</a>
					</div>
				</div>
				<div class="row">
					<div id='calendar'></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var calendarEl = document.getElementById('calendar');

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
		events: [],
		eventClick: function(args) {
			// Parse the date string using Moment.js
			var date = moment(args.event.start);

			// Format the date in your desired format (e.g., "MMMM Do YYYY, h:mm a")
			var formattedDate = date.format("dddd, MMMM DD YYYY, h:mm A");
			alert('Nama Pasien : ' + args.event._def.title + '\nTanggal : ' + formattedDate + '\nMEMO : ' + args.event._def.extendedProps.description);
		},

	});

	$(document).ready(function() {
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

			} catch (e) {
				$.alert_error(e);
				return false;
			}
		});


	})
</script>