<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('registrations:list_heading') ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("registrations/create") ?>" class="btn btn-info" title="<?php echo lang('buttons:create_registration') ?>"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:create_registration') ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('registrations:date_from_label') ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
						<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
						<input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('registrations:type_label') ?></label>
					<select id="TipePelayanan" class="form-control searchable_option">
						<option value=""><?php echo lang("global:select-all") ?></option>
						<option value="RawatJalan">Rawat Jalan</option>
						<!-- <option value="RawatInap">Rawat Inap</option> -->
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('registrations:doctor_label') ?></label>
					<select id="DokterID" class="form-control searchable_option">
						<option value=""><?php echo lang("global:select-none") ?></option>
						<?php foreach ($option_doctor as $k => $v) : ?>
							<option value="<?php echo $k ?>" <?php echo ($k == $this->session->userdata('doctor_id')) ? 'selected' : NULL;  ?>><?php echo $v ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('registrations:section_label') ?></label>
					<select id="SectionID" class="form-control">
						<option value=""><?php echo lang("global:select-none") ?></option>
						<?php if ($option_section) : foreach ($option_section as $row) : ?>
								<option value="<?php echo $row->SectionID ?>"><?php echo $row->SectionName ?></option>
						<?php endforeach;
						endif; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo 'NRM / No Registrasi' ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
						<input type="text" id="NRM" class="form-control searchable mask_nrm" placeholder="<?php echo lang('registrations:mr_number_label') ?>" />
						<span class="input-group-addon"><i class="fa fa-wheelchair"></i></span>
						<input type="text" id="NoReg" class="form-control searchable" placeholder="<?php echo 'No Registrasi' ?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label text-center"><?php echo 'Nama Pasien' ?></label>
					<input type="text" id="Nama" class="form-control searchable" placeholder="<?php echo 'Nama' ?>" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo 'Alamat' ?></label>
					<input type="text" id="Alamat" class="form-control searchable" placeholder="Alamat" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="row">

					<div class="form-group">
						<label class="control-label"><?php echo 'Status Periksa' ?></label>
						<div class="row">
							<div class="col-md-4">
								<div class="checkbox">
									<input type="checkbox" id="show_already_checked" value="1" class="check-searchable">
									<label for="show_already_checked">Sudah</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="checkbox">
									<input type="checkbox" id="belum_periksa" value="1" class="check-searchable">
									<label for="belum_periksa">Belum</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="checkbox">
									<input type="checkbox" id="show_cancel" value="1" class="check-searchable">
									<label for="show_cancel">Batal</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="table-responsive">
					<table id="dt-registrations" class="table table-sm table-striped" width="100%">
						<thead>
							<tr>
								<th><?php echo lang('registrations:registration_number_label') ?></th>
								<th><?php echo lang('registrations:date_label') ?></th>
								<th><?php echo lang('registrations:reservation_number_label') ?></th>
								<th><?php echo "Jam Reservasi" ?></th>
								<th></th>
								<th><?php echo lang('registrations:mr_number_label') ?></th>
								<th><?php echo lang('registrations:name_label') ?></th>
								<th><?php echo lang('registrations:type_label') ?></th>
								<th><?php echo "Memo" ?></th>
								<th><?php echo lang('global:status') ?></th>
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

	<!-- khusus klinik bmc pasien registrasi 3 hari yang lalu -->
	<!--?php echo Modules::run( "registrations/pasien_history", true) ?> -->


	<?php echo form_close() ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modalHistoryPasien").modal('show');
		});
		//<![CDATA[
		(function($) {
			var search_datatable = {
				init: function() {
					var timer = 0;

					$(".searchable").on("keyup", function(e) {
						e.preventDefault();

						var isWordCharacter = event.key.length === 1;
						var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);

						if (isWordCharacter || isBackspaceOrDelete) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout(search_datatable.reload_table, 600);
						}

					});

					$("#SectionID, #DokterID, .check-searchable, .searchable_option").on("change", function(e) {

						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(search_datatable.reload_table, 600);

					});

					$(".datepicker").datetimepicker({
						format: "YYYY-MM-DD"
					}).on("dp.change", function(e) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(search_datatable.reload_table, 600);

					});

					$(".btn-clear").on("click", function() {
						target = $(this).data("target");

						$(target).val("");
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(search_datatable.reload_table, 600);

					});

					$("#reset").on("click", function() {

						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(search_datatable.reload_table, 600);
					});

				},
				reload_table: function() {
					$("#dt-registrations").DataTable().ajax.reload();
				}
			};

			$.fn.extend({
				DataTable_Registrations: function() {
					var _this = this;

					var _datatable = _this.DataTable({
						processing: true,
						serverSide: true,
						paginate: true,
						ordering: true,
						order: [
							[0, 'desc']
						],
						searching: false,
						info: true,
						//scrollX: true,
						lengthChange: false,
						responsive: true,
						lengthMenu: [30, 45, 75, 100],
						ajax: {
							url: "<?php echo base_url("registrations/datatable_collection") ?>",
							type: "POST",
							data: function(params) {
								params.date_from = $("#date_from").val();
								params.date_till = $("#date_till").val();

								params.NRM = $("#NRM").val() || "";
								params.Nama = $("#Nama").val() || "";
								params.NoReg = $("#NoReg").val() || "";
								params.Alamat = $("#Alamat").val() || "";

								params.SectionID = $("#SectionID").val() || "";
								params.DokterID = $("#DokterID").val() || "";

								params.TipePelayanan = $('#TipePelayanan').val();
								params.show_already_checked = $("#show_already_checked").is(":checked") ? 1 : 0;
								params.belum_periksa = $("#belum_periksa").is(":checked") ? 1 : 0;
								params.show_cancel = $("#show_cancel").is(":checked") ? 1 : 0;
							}
						},
						fnDrawCallback: function(settings) {
							$(window).trigger("resize");
						},
						columns: [{
								data: "NoReg",
								name: "a.NoReg",
								className: "text-center",
								width: "150px",
								render: function(val, type, row) {
									return "<strong class=\"text-primary\">" + val + "</strong>"
								}
							},
							{
								data: "TglReg",
								name: "TglReg",
								width: "120px",
								className: "text-center",
								render: function(val, type, row) {
									return row.TglReg + " " + row.JamReg
								}
							},
							{
								data: "NoReservasi",
								name: "NoReservasi",
								orderable: true,
								searchable: true,
								width: "180px",
								className: "text-center"
							},
							{
								data: "JamReservasi",
								name: "JamReservasi",
								render: function(val, type, row) {
									if (!row.JamReservasi || row.JamReservasi.substr(11, 5) === "00:00") {
										return "";
									} else {
										return row.JamReservasi.substr(11, 5);
									}
								}
							},

							{
								className: 'details-control',
								orderable: false,
								searchable: false,
								data: null,
								width: "32px",
								defaultContent: ''
							},
							{
								data: "NRM",
								name: "NRM",
								width: "90px",
								render: function(val, type, row) {
									return "<strong class=\"text-success\">" + val + "</strong>"
								}
							},
							{
								data: "NamaPasien",
								name: "NamaPasien",
								width: null
							},
							{
								data: "JenisKerjasama",
								name: "JenisKerjasama",
							},
							{
								data: "Keterangan",
								name: "Keterangan",
							},
							{
								data: "StatusPeriksa",
								name: "StatusPeriksa",
								searchable: false,
								render: function(val, type, row) {

									if (row.Batal == 1) {
										return "<strong class=\"text-danger\">Batal Registrasi</strong>";
									} else if (row.Batal == 0 && (val == 'Sudah' || val == 'CO')) {
										return "<strong class=\"text-success\">" + val + ' Periksa' + "</strong>";
									} else if (row.Batal == 0 && val == 'Belum') {
										return "<strong class=\"text-warning\">" + val + ' Periksa' + "</strong>";
									}
								}
							},
							{
								data: "NoReg",
								className: "",
								orderable: false,
								width: "100px",
								render: function(val, type, row) {
									var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
									buttons += "<a href=\"<?php echo base_url("registrations/edit") ?>/" + val + "\" title=\"<?php echo lang("buttons:edit") ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang("buttons:edit") ?> </a>";
									<?php /*?>	buttons += "<a href=\"<?php echo base_url("registrations/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";<?php */ ?>
									buttons += "</div>";

									return buttons
								}
							}
						]
					});

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
								_details.load("<?php echo base_url("registrations/patient_details") ?>", {
									"NoReg": _dt.NoReg
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

					$("#dt-registrations_length select, #dt-registrations_filter input")
						.addClass("form-control");

					return _this
				}
			});

			//Status Periksa
			$(document).on("change", ".check-searchable", function(e) {
				var current_id = $(this).attr('id');

				$('.check-searchable').each(function() {
					if ($(this).attr('id') != current_id) {
						$(this).attr('checked', false);
					}
				});
			});

			$(document).ready(function(e) {
				$("#dt-registrations").DataTable_Registrations();

				search_datatable.init();


			});
		})(jQuery);
		//]]>
	</script>