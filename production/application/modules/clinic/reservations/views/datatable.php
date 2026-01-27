<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('reservations:list_heading') ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("reservations/create") ?>" class="btn btn-info" title="<?php echo lang('buttons:create_reservation') ?>"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:create_reservation') ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<?php /*?><div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('reservations:date_from_label') ?></label>
					<div class="col-md-3">
						<input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-01")?>" />
					</div>
					<label class="col-md-3 control-label text-center"><?php echo lang('reservations:date_till_label') ?></label>
					<div class="col-md-3">
						<input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-t") ?>" />
					</div>
				</div><?php */ ?>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('reservations:for_date_from_label') ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo @$UntukTanggal ? @$UntukTanggal : date("Y-m-d") ?>" />
						<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
						<input type="text" id="for_date_till" class="form-control searchable datepicker" value="<?php echo @$UntukTanggal ? @$UntukTanggal : date("Y-m-d") ?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('reservations:section_label') ?></label>
					<select id="SectionID" class="form-control">
						<option value=""><?php echo lang("global:select-none") ?></option>
						<?php if ($option_section) : foreach ($option_section as $row) : ?>
								<option value="<?php echo $row->SectionID ?>"><?php echo $row->SectionName ?></option>
						<?php endforeach;
						endif; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('reservations:doctor_label') ?></label>
					<select id="DokterID_w" class="form-control searchable_option_w">
						<option value=""><?php echo lang("global:select-none") ?></option>
						<?php foreach ($option_doctor as $k => $v) : ?>
							<option value="<?php echo $k ?>" <?php echo ($k == $this->session->userdata('doctor_id')) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Tampilkan</label>
					<div class="row">
						<div class="col-md-6">
							<div class="checkbox">
								<input type="checkbox" id="show_already_registration" value="1" class="check-searchable">
								<label for="show_already_registration">Sudah Registrasi</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="checkbox">
								<input type="checkbox" id="show_cancel" value="1" class="check-searchable">
								<label for="show_cancel">Batal Reservasi</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('reservations:patient_label') ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
						<input type="text" id="NRM" class="form-control searchable mask_nrm" placeholder="<?php echo lang('reservations:mr_number_label') ?>" />
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input type="text" id="Nama" class="form-control searchable" placeholder="<?php echo lang('reservations:name_label') ?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('reservations:phone_label') ?></label>
					<input type="number" id="Phone" class="form-control searchable" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('reservations:address_label') ?></label>
					<input type="text" id="Alamat" class="form-control searchable" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<button id="reset" type="reset" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:reset") ?></b></button>
				</div>
			</div>
		</div>
		<?php echo form_close() ?>
		<div class="row">
			<div class="table-responsive">
				<table id="dt-reservations" class="table table-sm" width="100%">
					<thead>
						<tr>
							<th><?php echo lang('reservations:reservation_number_label') ?></th>
							<?php /*?><th><?php echo lang('reservations:date_label') ?></th><?php */ ?>
							<th><?php echo lang('reservations:mr_number_label') ?></th>
							<th><?php echo lang('reservations:name_label') ?></th>
							<th><?php echo lang('reservations:phone_label') ?></th>
							<th><?php echo lang('reservations:section_label') ?></th>
							<th><?php echo lang('reservations:doctor_name_label') ?></th>
							<th><?php echo lang('reservations:for_label') ?></th>
							<!-- <th><?php echo lang('reservations:queue_label') ?></th> -->
							<th><?php echo "Memo" ?></th>
							<th></th>
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
		var search_datatable = {
			init: function() {
				var timer = 0;

				$(".searchable").on("keyup", function(e) {
					e.preventDefault();

					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);

				});

				$("#SectionID, .check-searchable").on("change", function(e) {

					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);

				});

				$(".datepicker").datetimepicker({
					format: "YYYY-MM-DD"
				}).on("dp.change", function(e) {
					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);

				});

				$(".btn-clear").on("click", function() {
					target = $(this).data("target");

					$(target).val("");
					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);

				});

				$("#reset").on("click", function() {

					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(search_datatable.reload_table, 400);
				});

			},
			reload_table: function() {
				$("#dt-reservations").DataTable().ajax.reload();
			}
		};

		$.fn.extend({
			DataTable_reservations: function() {
				var _this = this;

				var _datatable = _this.DataTable({
					processing: true,
					serverSide: false,
					paginate: true,
					ordering: true,
					order: [
						[0, 'asc']
					],
					searching: false,
					info: true,
					responsive: true,
					lengthChange: false,
					lengthMenu: [30, 45, 75, 100],
					//dom: "<'row'<'col-md-5'l><'col-md-7'f>r>t<'row'<'col-md-5'i><'col-md-7'p>>",
					ajax: {
						url: "<?php echo base_url("reservations/datatable_collection") ?>",
						type: "POST",
						data: function(params) {
							//params.date_from = $("#date_from").val();	
							//params.date_till = $("#date_till").val();	

							params.for_date_from = $("#for_date_from").val();
							params.for_date_till = $("#for_date_till").val();

							params.NRM = $("#NRM").val() || "";
							params.Nama = $("#Nama").val() || "";
							params.Phone = $("#Phone").val() || "";
							params.Alamat = $("#Alamat").val() || "";

							params.SectionID = $("#SectionID").val() || "";
							params.DokterID = $("#DokterID").val() || "";

							params.show_already_registration = $("#show_already_registration").is(":checked") ? 1 : 0;
							params.show_cancel = $("#show_cancel").is(":checked") ? 1 : 0;
						}
					},
					fnDrawCallback: function(settings) {
						$(window).trigger("resize");
					},
					columns: [{
							data: "NoReservasi",
							className: "text-center",
							width: "130px",
							render: function(val, type, row) {
								return "<strong class=\"text-primary\">" + val + "</strong>"
							}
						},
						/*{ 
							data: "Jam", 
							width: "100px",
							className: "text-center",
							render: function ( val, type, row ){
									return val.substr(0, 19)
								}
						},*/
						{
							orderable: false,
							searchable: false,
							data: 'NRM',
							width: "32px",
							render: function(val, type, row) {
								return "<strong class=\"text-success\">" + val + "</strong>"
							}
						},
						{
							data: "Nama",
							className: "",
							width: "200px",
						},
						{
							data: "Phone",
							width: null
						},
						{
							data: "SectionName",
							width: null
						},
						{
							data: "Nama_Supplier",
							width: null
						},
						{
							data: "UntukHari",
							width: "145px",
							className: "text-center",
							render: function(val, type, row) {
								if (row.UntukTanggal) {
									var date_parts = row.UntukTanggal.substr(0, 10).split('-');
									var d = new Date(date_parts[0], date_parts[1] - 1, date_parts[2]);
									var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
									var dayName = days[d.getDay()];
									return dayName + ", " + row.UntukTanggal.substr(0, 10) + " " + row.estimation_time
								}

								return val + ", " + row.UntukTanggal.substr(0, 10) + " " + (row.estimation_time || "")
							}

						},
						// { 
						// 	data: "NoUrut", 
						// 	className: "text-center", render: function (val, type, row){
						// 		if( row.Registrasi == 0 && row.Batal == 0 ){
						// 			return	val;
						// 		}

						// 		return ''
						// 	}
						// },
						{
							data: "Memo",
							className: "",
							width: null,
						},
						{
							data: "NoReservasi",
							className: "text-center",
							orderable: false,
							width: "180px",
							render: function(val, type, row) {
								if (row.Registrasi == 0 && row.Batal == 0) {
									var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
									buttons += "<a href=\"<?php echo base_url("reservations/edit") ?>/" + val + "\" title=\"<?php echo lang("buttons:edit") ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang("buttons:edit") ?> </a>";
									buttons += "<a href=\"<?php echo base_url("registrations/create-from-reservation") ?>/" + val + "\" target=\"_blank\" title=\"Klik Untuk Registrasi\" class=\"btn btn-success btn-xs\"> <i class=\"fa fa-user-plus\"></i> Proses</a>";
									buttons += "</div>";

									return buttons
								}

								return row.Registrasi == 1 ?
									'<span class="btn btn-success btn-xs">Registrasi</span>' :
									'<span class="btn btn-danger btn-xs">Batal</span>'
							}
						}
					]
				});

				// Array to track the ids of the details displayed rows
				var _detail_rows = [];

				_this.find('tbody').on('click', 'tr td.details-control', function(e) {
					var _tr = $(this).closest('tr');
					var _rw = _datatable.row(_tr);

					var _dt = _rw.data();
					var _ids = $.inArray(_tr.attr('id'), _detail_rows);

					if (_rw.child.isShown()) {
						_tr.removeClass('details');

						_rw.child.hide();

						// Remove from the 'open' array
						_detail_rows.splice(_ids, 1);
					} else {
						_tr.addClass('details');

						if (_rw.child() == undefined) {
							var _details = $("<div class=\"details-loader\"></div>");
							_rw.child(_details).show();
							_details.html("<span class=\"text-loader\"><?php echo lang("global:ajax_loading") ?></span>");
							_details.load("<?php echo base_url("reservations/patient_details") ?>", {
								"reg_num": _dt.registration_number
							}, function(response, status, xhr) {
								$(window).trigger("resize");
							});
						} else {
							_rw.child.show();
						}

						// Add to the 'open' array
						if (_ids === -1) {
							_detail_rows.push(_tr.attr('id'));
						}
					}

					$(window).trigger("resize");
				});

				// On each draw, loop over the `_detail_rows` array and show any child rows
				_datatable.on('draw', function() {
					$.each(_detail_rows, function(i, id) {
						$('#' + id + ' td.details-control').trigger('click');
					});
				});

				$("#dt-reservations_length select, #dt-reservations_filter input")
					.addClass("form-control");

				return _this
			}
		});

		$(document).ready(function(e) {
			$("#dt-reservations").DataTable_reservations();

			search_datatable.init();

		});
	})(jQuery);
	//]]>
</script>