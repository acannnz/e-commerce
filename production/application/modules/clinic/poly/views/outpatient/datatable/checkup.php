<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="row">
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label"><?php echo lang('poly:date_from_label') ?></label>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" id="date_from_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d") ?>" />
				<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
				<input type="text" id="date_till_c" class="form-control searchable_c datepicker" value="<?php echo date("Y-m-d") ?>" />
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label"><?php echo lang('poly:patient_label') ?></label>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
				<input type="text" id="NRM_c" class="form-control searchable_c mask_nrm" placeholder="<?php echo lang('poly:mr_number_label') ?>" />
				<span class="input-group-addon"><i class="fa fa-wheelchair"></i></span>
				<input type="text" id="Nama_c" class="form-control searchable_c" placeholder="<?php echo lang('poly:name_label') ?>" />
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label"><?php echo lang('poly:doctor_label') ?></label>
			<select id="DokterID_c" class="form-control searchable_option_c">
				<option value=""><?php echo lang("global:select-none") ?></option>
				<?php foreach ($option_doctor as $k => $v): ?>
					<option value="<?php echo $k ?>" <?php echo ($k == $medics['doctor_id']) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label"><?php echo lang('poly:section_label') ?></label>
			<select id="SectionID_c" class="form-control searchable_option_c">
				<option value=""><?php echo lang("global:select-none") ?></option>
				<?php foreach ($option_section as $k => $v): ?>
					<option value="<?php echo $k ?>" <?php echo ($k == $medics['section_id']) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>
<div class="table-responsive">
	<table id="dt-data-checkups" class="table table-sm" width="100%">
		<thead>
			<tr>
				<th>NoBukti</th>
				<th>NoReg</th>
				<th>Waktu</th>
				<th>N.R.M</th>
				<th>Nama Pasien</th>
				<th>Jenis Kelamin</th>
				<th>Dokter</th>
				<th>Jenis Pasien</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var search_datatable_c = {
			init: function() {
				var timer = 0;

				$(".searchable_c").on("keyup", function(e) {
					e.preventDefault();

					var isWordCharacter = event.key.length === 1;
					var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);

					if (isWordCharacter || isBackspaceOrDelete) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(search_datatable_c.reload_table, 600);
					}
				});

				$(".searchable_option_c").on("change", function(e) {

					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable_c.reload_table, 600);

				});

				$("#date_from_c, #date_till_c").datetimepicker({
					format: "YYYY-MM-DD"
				}).on("dp.change", function(e) {
					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable_c.reload_table, 600);

				});
			},
			reload_table: function() {
				$("#dt-data-checkups").DataTable().ajax.reload();
			}
		};

		$.fn.extend({
			DataTable_DataCheckups: function() {
				var _this = this;

				var _datatable = _this.DataTable({
					processing: true,
					serverSide: true,
					paginate: true,
					ordering: true,
					order: [
						[1, 'desc']
					],
					searching: false,
					info: true,
					responsive: true,
					lengthChange: false,
					lengthMenu: [30, 45, 75, 100],
					ajax: {
						url: "<?php echo base_url("{$nameroutes}s/data_checkup/datatable_collection") ?>",
						type: "POST",
						data: function(params) {
							params.date_from = $("#date_from_c").val();
							params.date_till = $("#date_till_c").val();

							params.NRM = $("#NRM_c").val() || "";
							params.Nama = $("#Nama_c").val() || "";
							params.DokterID = $("#DokterID_c").val() || "";
							params.SectionID = $("#SectionID_c").val() || "";
						}
					},
					fnDrawCallback: function(settings) {
						$(window).trigger("resize");
					},
					columns: [{
							data: "NoBukti",
							width: "180px",
							className: "text-center",
							name: "a.NoBukti",
							render: function(val, type, row) {
								return "<strong class=\"text-primary\">" + val + "</strong>"
							}
						},
						{
							data: "RegNo",
							width: "180px",
							class: "text-center",
							name: "a.RegNo",
							render: function(val, type, row) {
								return (val) ? val : "n/a"
							}
						},
						{
							data: "Jam",
							className: "text-center",
							width: "40px",
							name: "a.Jam",
							render: function(val, type, row) {
								return val.substring(0, 19)
							}
						},
						{
							data: "NRM",
							className: "text-center",
							width: "90px",
							name: "b.NRM",
							render: function(val, type, row) {
								return "<strong class=\"text-success\">" + val + "</strong>"
							}
						},
						{
							data: "NamaPasien",
							width: null
						},
						{
							data: "JenisKelamin",
							class: "text-center"
						},
						{
							data: "Nama_Supplier",
							width: null
						},
						{
							data: "JenisKerjasama",
							class: "text-center"
						},
						{
							data: "NoBukti",
							width: "100px",
							className: "text-center",
							render: function(val, type, row) {
								if (row.Batal == 1) {
									return "<a href=\"javascript:;\" title=\"<?php echo lang("buttons:cancel") ?>\" class=\"btn btn-danger btn-xs\"><b><?php echo lang("buttons:cancel") ?></b></a>";
								} else {
									return "<a href=\"javascript:;\" title=\"Sudah Periksa\" class=\"btn btn-success btn-xs\"><b>Sudah Periksa</b></a>";
								}

								return ""

							}
						},
						{
							data: "NoBukti",
							className: "",
							orderable: false,
							width: "100px",
							render: function(val, type, row) {
								var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
								buttons += "<a href=\"<?php echo base_url("{$nameroutes}/edit") ?>/" + val + "\" title=\"Periksa Pasien\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-stethoscope\"></i> Lihat</a>";
								buttons += "</div>";

								return buttons
							}
						}
					]
				});

				$("#dt-data-checkups_length select, #dt-data-checkups_filter input")
					.addClass("form-control");

				return _this
			}
		});

		$(document).ready(function(e) {
			search_datatable_c.init();
			$("#dt-data-checkups").DataTable_DataCheckups();

		});
	})(jQuery);
	//]]>
</script>