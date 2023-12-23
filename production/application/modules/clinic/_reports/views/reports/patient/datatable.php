<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<h1><?= "Daftar pasien" ?></h1>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<button type="submit" id="button-submit" class="btn btn-info pull-right"><b><i class="fa fa-print" aria-hidden="true"></i> <?php echo 'Export' ?></b></button>
				</div>
			</div>
		</div>
		<hr style="margin-top: 0;">
		<?php echo form_close() ?>
		<div class="row">
			<div class="table-responsive">
				<table id="dt-patient" class="table table-sm" width="100%">
					<thead>
						<tr>
							<th>NRM</th>
							<th>NoKartu</th>
							<th>NamaPasien</th>
							<th>JenisKelamin</th>
							<th>Agama</th>
							<th>TglLahir</th>
							<th>NoIdentitas</th>
							<th>TempatLahir</th>
							<th>Alamat</th>
							<th>Phone</th>
							<th>Email</th>
							<th>Pekerjaan</th>
							<th>PenanggungNama</th>
							<th>PenanggungAlamat</th>
							<th>PenanggungKTP</th>
							<th>PenanggungPhone</th>
							<th>PenanggungHubungan</th>
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

				$("#option_doctor").on("change", function() {

					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);
				});

				$("#btn-clear-filter").on("click", function() {

					$(".dt-filter").val("");
					$("#dt-patient").DataTable()
						.columns()
						.search('')
						.draw(true);

				});
			},
			reload_table: function() {
				$("#dt-patient").DataTable().ajax.reload();
			}
		};

		$.fn.extend({
			DataTable_reservations: function() {
				var _this = this;

				_datatable = _this.DataTable({
					dom: 'lfrtip',
					processing: true,
					serverSide: true,
					paginate: true,
					ordering: true,
					order: [
						[0, 'asc']
					],
					searching: true,
					info: true,
					responsive: true,
					lengthChange: true,
					lengthMenu: [15, 45, 75, 100],
					orderCellsTop: true,
					ajax: {
						url: "<?php echo base_url("{$nameroutes}/patient_collection") ?>",
						type: "POST",
						data: function(params) {
							params.date = $("#for_date_from").val();
							params.doctor = $("#option_doctor").val();
						}
					},
					fnDrawCallback: function(settings) {
						$(window).trigger("resize");
					},
					columns: [{
							data: "NRM"
						},
						{
							data: "NoKartu"
						},
						{
							data: "NamaPasien",
						},
						{
							data: "JenisKelamin",
							render: function(val, type, row) {
								return (val == 'F') ? 'Perempuan' : 'Laki-laki';
							}
						},
						{
							data: "Agama",
							render: function(val, type, row) {
								var agama;
								switch (val) {
									case 'BD':
										agama = 'BUDHA';
										break;
									case 'HD':
										agama = 'HINDU';
										break;
									case 'IS':
										agama = 'ISLAM';
										break;
									case 'KC':
										agama = 'KONGHUCU';
										break;
									case 'KR':
										agama = 'KRISTEN';
										break;
									case 'KT':
										agama = 'KHATOLIK';
										break;
									default:
										agama = '-';
										break;
								}

								return agama;
							}
						},
						{
							data: "TglLahir",
							render: function(val, type, row) {
								return '<strong>' + (moment(val).format("DD-MM-YYYY")) + '</strong>';
							}
						},
						{
							data: "NoIdentitas",
						},
						{
							data: "TempatLahir",
						},
						{
							data: "Alamat",
						},
						{
							data: "Phone",
						},
						{
							data: "Email",
						},
						{
							data: "Pekerjaan",
						},
						{
							data: "PenanggungNama",
						},
						{
							data: "PenanggungAlamat",
						},
						{
							data: "PenanggungKTP",
						},
						{
							data: "PenanggungPhone",
						},
						{
							data: "PenanggungHubungan",
						},

					]
				});

				$("#dt-patient_length select, #dt-patient_filter input").addClass("form-control");
				// $(".dt-button").addClass("btn btn-success");
				// $(".dt-buttons").addClass("text-right");

				return _this
			}
		});

		$(document).ready(function(e) {
			$('#dt-patient thead tr:eq(1) th').each(function() {
				var title = $(this).text();
				$(this).html('<input type="text" class="dt-filter" placeholder="Cari ' + title + '" />');
			});

			$("#dt-patient").DataTable_reservations();

			$('#dt-patient thead').on('keyup', ".dt-filter", function() {
				$("#dt-patient").DataTable()
					.column($(this).parent().index())
					.search($(this).val())
					.draw();
			});

			search_datatable.init();

		});

	})(jQuery);
	//]]>
</script>