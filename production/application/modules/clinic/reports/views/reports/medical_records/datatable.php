<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?= "Laporan Rekam Medis Pasien" ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Periode</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
						<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
						<input type="text" id="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">Umur</label>
					<select id="patient_age" class="form-control searchable_option">
						<option value="">-- Semua --</option>
						<option value="1">0 - 1</option>
						<option value="5">0 - 5</option>
					</select>
				</div>
			</div>
			<div class="col-md-1">
				<div class="form-group">
					<label class="control-label">&nbsp</label>
					<div class="input-group">
						<div class="checkbox">
							<input type="checkbox" id="patien_rujukan" value="" class="check-searchable">
							<label for="patien_rujukan">Rujukan</label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<button id="btn-search" type="button" class="btn btn-info btn-block"><b><i class="fa fa-search"></i> <?php echo lang("buttons:search") ?></b></button>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<button id="btn-clear-filter" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo 'Bersihkan Pencarian' ?></b></button>
				</div>
			</div>
		</div>
		<?php echo form_close() ?>
		<div class="row">
			<div class="table-responsive">
				<table id="dt-medical-records" class="table table-sm" width="100%">
					<thead>
						<tr>
							<th>Tanggal</th>
							<th>NoReg</th>
							<th>Tipe</th>
							<th>Customer</th>
							<th>Poli</th>
							<th>Dokter</th>
							<th>NRM</th>
							<th>Nama</th>
							<th>Jenis Kelamin</th>
							<th>Tgl.Lahir</th>
							<th>Umur</th>
							<th>Alamat</th>
							<th>Alergi Obat</th>
							<th>Subjektif</th>
							<th>Objektif</th>
							<th>Assesmen</th>
							<th>Perencanaan</th>
							<th>Tinggi</th>
							<th>Berat</th>
							<th>Suhu</th>
							<th>Tensi</th>
							<th>BPM</th>
							<th>RPM</th>
							<th>SATS</th>
							<th>Nyeri</th>
							<th>Rujukan</th>
						</tr>
						<tr>
							<th>Tanggal</th>
							<th>NoReg</th>
							<th>Tipe</th>
							<th>Customer</th>
							<th>Poli</th>
							<th>Dokter</th>
							<th>NRM</th>
							<th>Nama</th>
							<th>Jenis Kelamin</th>
							<th>Tgl.Lahir</th>
							<th>Umur</th>
							<th>Alamat</th>
							<th>Alergi Obat</th>
							<th>Subjektif</th>
							<th>Objektif</th>
							<th>Assesmen</th>
							<th>Perencanaan</th>
							<th>Tinggi</th>
							<th>Berat</th>
							<th>Suhu</th>
							<th>Tensi</th>
							<th>BPM</th>
							<th>RPM</th>
							<th>SATS</th>
							<th>Nyeri</th>
							<th>Rujukan</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var _datatable;
		var search_datatable = {
			init: function() {
				var timer = 0;

				$(".datepicker").datetimepicker({
					format: "YYYY-MM-DD"
				}).on("dp.change", function(e) {
					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);

				});

				$("#btn-search").on("click", function() {

					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);
				});

				$("#btn-clear-filter").on("click", function() {

					$(".dt-filter").val("");
					$("#dt-medical-records").DataTable()
						.columns()
						.search('')
						.draw(true);

				});

				$("#patient_age").on("change", function() {
					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);
				});

				$('#patien_rujukan').click(function() {
					if ($('#patien_rujukan').is(':checked')) {
						$("#patien_rujukan").val("1");
					} else {
						$("#patien_rujukan").val("");
					}

					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);
				});

			},
			reload_table: function() {
				$("#dt-medical-records").DataTable().ajax.reload();
			}
		};

		$.fn.extend({
			DataTable_reservations: function() {
				var _this = this;

				_datatable = _this.DataTable({
					dom: 'Bfrtip',
					buttons: [
						'copy', 'csv', 'excel', 'print'
					],
					processing: true,
					serverSide: false,
					paginate: true,
					ordering: true,
					order: [
						[7, 'asc']
					],
					searching: true,
					info: true,
					responsive: true,
					lengthChange: true,
					lengthMenu: [15, 45, 75, 100],
					orderCellsTop: true,
					ajax: {
						url: "<?php echo base_url("{$nameroutes}/medical_records_collection") ?>",
						type: "POST",
						data: function(params) {
							params.date_from = $("#for_date_from").val();
							params.date_till = $("#for_date_till").val();
							params.patient_age = $("#patient_age").val();
							params.patien_rujukan = $("#patien_rujukan").val();

						}
					},
					fnDrawCallback: function(settings) {
						$(window).trigger("resize");
					},
					columns: [{
							data: "Tanggal",
							className: "text-center",
							width: "100px",
							render: function(val, type, row) {
								return "<strong class=\"text-primary\">" + val + "</strong>"
							}
						},
						{
							data: "RegNo",
							className: "text-center",
							width: "130px",
							render: function(val, type, row) {
								return "<strong class=\"text-primary\">" + val + "</strong>"
							}
						},
						{
							data: "JenisKerjasama"
						},
						{
							data: "NamaCustomer",
							render: function(v, t, r) {
								if (r.JenisKerjasamaID != 3) {
									return v;
								}
								return '';
							}
						},
						{
							data: "SectionName"
						},
						{
							data: "NamaDokter"
						},
						{
							data: "NRM"
						},
						{
							data: "NamaPasien"
						},
						{
							data: "JenisKelamin"
						},
						{
							data: "TglLahir"
						},
						{
							data: "Umur"
						},
						{
							data: "Alamat",
							width: "250px",
						},
						{
							data: "RiwayatAlergi"
						},
						{
							data: "Subjective",
							width: "500px"
						},
						{
							data: "Objective",
							width: "500px"
						},
						{
							data: "Assessment",
							width: "500px"
						},
						{
							data: "Plan",
							width: "500px"
						},
						{
							data: "Height",
							render: function(v) {
								return v + ' CM';
							}
						},
						{
							data: "Weight",
							render: function(v) {
								return v + ' KG';
							}
						},
						{
							data: "Temperature",
							render: function(v) {
								return v + ' C';
							}
						},
						{
							data: "BloodPressure"
						},
						{
							data: "HeartRate"
						},
						{
							data: "RespiratoryRate"
						},
						{
							data: "OxygenSaturation",
							render: function(v) {
								return v + ' %';
							}
						},
						{
							data: "Pain"
						},
						{
							data: "PxKeluar_DirujukKeterangan"
						},
					]
				});

				$("#dt-medical-records_length select, #dt-medical-records_filter input").addClass("form-control");
				$(".dt-button").addClass("btn btn-success");
				$(".dt-buttons").addClass("text-right");
				$("#dt-medical-records_filter").remove();

				return _this
			}
		});

		$(document).ready(function(e) {
			$('#dt-medical-records thead tr:eq(1) th').each(function() {
				var title = $(this).text();
				$(this).html('<input type="text" class="dt-filter" placeholder="Cari ' + title + '" />');
			});

			$("#dt-medical-records").DataTable_reservations();

			$('#dt-medical-records thead').on('keyup', ".dt-filter", function() {
				$("#dt-medical-records").DataTable()
					.column($(this).parent().index())
					.search($(this).val())
					.draw();
			});

			search_datatable.init();

		});
	})(jQuery);
	//]]>
</script>