<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>

<?php echo form_open(site_url("{$nameroutes}/mass_action"), [
	'id' => 'form_crud__list',
	'name' => 'form_crud__list',
	'rule' => 'form',
	'class' => ''
]); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-6">
				<h3 class="panel-title"><?php echo lang('heading:pcare_list'); ?></h3>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('label:date') ?></label>
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
					<label class="control-label"><?php echo lang('label:patient') ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
						<input type="text" id="NRM" class="form-control searchable mask_nrm" placeholder="<?php echo lang('label:nrm') ?>" />
						<span class="input-group-addon"><i class="fa fa-wheelchair"></i></span>
						<input type="text" id="Nama" class="form-control searchable" placeholder="<?php echo lang('label:name') ?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo lang('label:doctor') ?></label>
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
					<label class="control-label"><?php echo lang('label:poly') ?></label>
					<select id="SectionID" class="form-control">
						<option value=""><?php echo lang("global:select-none") ?></option>
						<?php if ($option_section) : foreach ($option_section as $k => $v) : ?>
								<option value="<?php echo $k ?>"><?php echo $v ?></option>
						<?php endforeach;
						endif; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-pcare">
					<table id="dt_pcare" class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th><?php echo lang('label:registration_number') ?></th>
								<th><?php echo lang('label:visite_number') ?></th>
								<th><?php echo lang('label:date') ?></th>
								<th><?php echo lang('label:queue') ?></th>
								<th><?php echo lang('label:nrm') ?></th>
								<th><?php echo lang('label:name') ?></th>
								<th><?php echo lang('label:poly') ?></th>
								<th><?php echo lang('label:doctor') ?></th>
								<th><i class="fa fa-cog"></i></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo form_close() ?>
<script>
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
				$("#dt_pcare").DataTable().ajax.reload();
			}
		};

		$.fn.extend({
			DataTableInit: function() {

				var _this = this;
				//function code for custom search
				var _datatable = _this.DataTable({
					processing: true,
					serverSide: true,
					paginate: true,
					ordering: true,
					lengthMenu: [10, 30, 75],
					order: [
						[0, 'desc']
					],
					searching: true,
					info: true,
					responsive: true,
					ajax: {
						url: "<?php echo site_url("{$nameroutes}/datatable_collection") ?>",
						type: "POST",
						data: function(params) {
							params.date_from = $("#date_from").val();
							params.date_till = $("#date_till").val();
							params.NRM = $("#NRM").val() || "";
							params.Nama = $("#Nama").val() || "";
							params.SectionID = $("#SectionID").val() || "";
							params.DokterID = $("#DokterID").val() || "";
						}
					},
					fnDrawCallback: function(settings) {
						$(window).trigger("resize");
					},
					columns: [{
							data: 'NoReg',
							name: 'a.NoReg',
							className: 'text-center',
							render: function(val, type, row) {
								return "<b>" + val + "</b>";
							}
						},
						{
							data: 'NoBuktiIntegrasi',
							name: 'a.NoBuktiIntegrasi',
							className: 'text-center',
							render: function(val, type, row) {
								return "<b>" + val + "</b>";
							}
						},
						{
							data: 'CreatedAt',
							name: 'a.CreatedAt',
							className: 'text-center'
						},
						{
							data: 'NoUrut',
							name: 'a.NoUrut',
							className: 'text-center'
						},
						{
							data: 'NRM',
							name: 'c.NRM',
							className: 'text-center'
						},
						{
							data: 'NamaPasien',
							name: 'c.NamaPasien',
						},
						{
							data: 'SectionName',
							name: 'd.SectionName',
						},
						{
							data: 'DokterName',
							name: 'e.Supplier_Name',
						},
						{
							data: 'NoReg',
							className: "text-center",
							orderable: false,
							render: function(val, type, row) {
								if (row.NoBuktiIntegrasi == null)
									return '-';

								var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
								buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/" + val + "\" title=\"<?php echo lang('buttons:edit') ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang('buttons:edit') ?> </a>";
								buttons += "</div>";

								return buttons
							}
						}
					]
				});
				$("#dt_pcare_length select, #dt_pcare_filter input")
					.addClass("form-control");

				return _this;
			}

		});

		$(document).ready(function(e) {
			var _form = $('form[name="form_crud__list"]');
			_form.find("button[name=\"btn_search\"]").on("click", function(e) {
				$("#dt_pcare").DataTable().ajax.reload();
			});

			$("#dt_pcare").DataTableInit();
			search_datatable.init();

			$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function(e) {
				e.preventDefault();
				_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
				_form.trigger('submit');
			});
		});
	})(jQuery);
</script>