<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="row">
	<div class="col-md-12 ">
		<div id="controls" class="panel panel-default">
			<div class="panel-body">
				<div class="text-center" data-toggle="buttons">
					<?php foreach ($odontogram_collection as $key => $value) : ?>
						<label id="<?= trim($value->Simbol) ?>" title="<?= $value->Keterangan ?>" data-placement="top" data-id="<?= trim($value->Odontogram_ID) ?>" class="btn btn-primary color-<?= trim($value->Simbol) ?>">
							<input type="radio" name="options" id="option1" autocomplete="off" checked><?= trim($value->Simbol) ?>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<div id="tr" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
	</div>
	<div id="tl" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	</div>
	<div id="tlr" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
	</div>
	<div id="tll" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	</div>
</div>
<div class="row">
	<div id="blr" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
	</div>
	<div id="bll" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	</div>
	<div id="br" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
	</div>
	<div id="bl" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table id="dt_odontogram" class="table table-sm table-bordered" width="100%">
						<thead>
							<tr>
								<th>Gigi</th>
								<th>Status</th>
								<th>Keterangan</th>
								<!--<th>User</th>-->
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
<script type="text/javascript">
	function replaceAll(find, replace, str) {
		return str.replace(new RegExp(find, 'g'), replace);
	}

	function createOdontogram() {
		var htmlmilkLeft = "",
			htmlmilkRight = "",
			htmlLeft = "",
			htmlRight = "",
			a = 1;
		for (var i = 9 - 1; i >= 1; i--) {
			//tooths Definitivos Cuandrante Derecho (Superior/Inferior)
			htmlRight += '<div data-name="value" id="toothindex' + i + '" style="left: -25% !important;" class="tooth">' +
				'<span style="margin-left: 45px; margin-bottom:5px; display: inline-block !important; border-radius: 10px !important;" class="label label-info">index' + i + '</span>' +
				'<div id="tindex' + i + '" class="img click">' +
				'</div>' +
				'<div id="lindex' + i + '" class="img left click">' +
				'</div>' +
				'<div id="bindex' + i + '" class="img bottom-tooth click">' +
				'</div>' +
				'<div id="rindex' + i + '" class="img good click click">' +
				'</div>' +
				'<div id="cindex' + i + '" class="center click">' +
				'</div>' +
				'</div>';
			//tooths Definitivos Cuandrante left (Superior/Inferior)
			htmlLeft += '<div id="toothindex' + a + '"  class="tooth">' +
				'<span style="margin-left: 45px; margin-bottom:5px; display: inline-block !important; border-radius: 10px !important;" class="label label-info">index' + a + '</span>' +
				'<div id="tindex' + a + '" class="img click">' +
				'</div>' +
				'<div id="lindex' + a + '" class="img left click">' +
				'</div>' +
				'<div id="bindex' + a + '" class="img bottom-tooth click">' +
				'</div>' +
				'<div id="rindex' + a + '" class="img good click click">' +
				'</div>' +
				'<div id="cindex' + a + '" class="center click">' +
				'</div>' +
				'</div>';
			if (i <= 5) {
				//tooths Temporales Cuandrante Derecho (Superior/Inferior)
				htmlmilkRight += '<div id="toothLindex' + i + '" style="left: -25%;" class="tooth-milk">' +
					'<span style="margin-left: 45px; margin-bottom:5px; display: inline-block !important; border-radius: 10px !important;" class="label label-info">index' + i + '</span>' +
					'<div id="tmilkindex' + i + '" class="img-milk top-milk click">' +
					'</div>' +
					'<div id="lmilkindex' + i + '" class="img-milk left-milk click">' +
					'</div>' +
					'<div id="bmilkindex' + i + '" class="img-milk bottom-milk click">' +
					'</div>' +
					'<div id="rmilkindex' + i + '" class="img-milk good-milk click click">' +
					'</div>' +
					'<div id="cmilkindex' + i + '" class="center-milk click">' +
					'</div>' +
					'</div>';
			}
			if (a < 6) {
				//tooths Temporales Cuandrante left (Superior/Inferior)
				htmlmilkLeft += '<div id="toothLindex' + a + '" class="tooth-milk">' +
					'<span style="margin-left: 45px; margin-bottom:5px; display: inline-block !important; border-radius: 10px !important;" class="label label-info">index' + a + '</span>' +
					'<div id="tmilkindex' + a + '" class="img-milk top-milk click">' +
					'</div>' +
					'<div id="lmilkindex' + a + '" class="img-milk left-milk click">' +
					'</div>' +
					'<div id="bmilkindex' + a + '" class="img-milk bottom-milk click">' +
					'</div>' +
					'<div id="rmilkindex' + a + '" class="img-milk good-milk click click">' +
					'</div>' +
					'<div id="cmilkindex' + a + '" class="center-milk click">' +
					'</div>' +
					'</div>';
			}
			a++;
		}
		$("#tr").append(replaceAll('index', '1', htmlRight));
		$("#tl").append(replaceAll('index', '2', htmlLeft));
		$("#tlr").append(replaceAll('index', '5', htmlmilkRight));
		$("#tll").append(replaceAll('index', '6', htmlmilkLeft));


		$("#bl").append(replaceAll('index', '3', htmlLeft));
		$("#br").append(replaceAll('index', '4', htmlRight));
		$("#bll").append(replaceAll('index', '7', htmlmilkLeft));
		$("#blr").append(replaceAll('index', '8', htmlmilkRight));
	}
	var arrayJembatan = [];


	var _datatable;

	var _datatable_populate;
	var _datatable_actions = {
		edit: function(row, data, index) {

			switch (this.index()) {

				case 2:
					_input = $(`<input type="text" id="asd" class="form-control" value="` + data.Note + `">`);

					this.empty().append(_input);
					$(_input).focus();

					_input.on("blur", function(e) {
						e.preventDefault();
						try {
							data.Note = this.value;
							$(e.target).remove();
							_datatable.row(row).data(data);

						} catch (ex) {}
					});

					break;
			}

		},
		remove: function(params, fn, scope) {
			_datatable.row(scope)
				.remove()
				.draw(true);

		},
		add_row: function(params, fn, scope) {
			_datatable.row.add({}).draw(false);
		}


	};

	$.fn.extend({
		dt_odontogram: function() {
			var _this = this;

			if ($.fn.dataTable.isDataTable(_this.attr("id"))) {
				return _this
			}

			_datatable = _this.DataTable({
				processing: true,
				serverSide: false,
				paginate: false,
				ordering: false,
				searching: false,
				info: false,
				autoWidth: false,
				responsive: true,
				<?php if (!empty(@$collection_odontogram)) : ?>
					data: <?php print_r(json_encode(@$collection_odontogram, JSON_NUMERIC_CHECK)); ?>,
				<?php endif; ?>
				columns: [{
						data: "Tooth",
						className: "text-left",
						width: "30%",
						render: function(val) {
							JenisGigi = val.includes("milk") ? "Susu " : "";

							switch (val.substring(0, 1)) {
								case 'c':
									LetakGigi = "Tengah";
									break;
								case 'r':
									LetakGigi = "Kanan";
									break;
								case 'b':
									LetakGigi = "Bawah";
									break;
								case 'l':
									LetakGigi = "Kiri";
									break;
								case 't':
									LetakGigi = "Atas";
									break;
							}

							return "Gigi " + JenisGigi + val.match(/\d+/) + " " + LetakGigi;
						}
					},
					{
						data: "Simbol",
						width: "20%",
						className: "text-center",
					},
					{
						data: "Note",
						width: "50%",
						className: "text-left",
					}
				],
				drawCallback: function(settings) {
					dev_layout_alpha_content.init(dev_layout_alpha_settings);
				},
				createdRow: function(row, data, index) {

					currentClass = $("div#" + data.Tooth).attr('class').split(' ').pop();
					exactClass = 'color-' + data.Simbol;

					if (currentClass !== exactClass) {
						$("div#" + data.Tooth).addClass(exactClass);
						$("div#" + data.Tooth).attr("data-original-title", data.Keterangan);
						$("div#" + data.Tooth).tooltip();
					}

					$(row).on("dblclick", "td", function(e) {
						e.preventDefault();
						var elem = $(e.target);
						_datatable_actions.edit.call(elem, row, data, index);
					});

				}
			});

			$("#dt_odontogram_length select, #dt_odontogram_filter input")
				.addClass("form-control");

			return _this
		},
	});

	function datatable_update(action, key, val) {
		var rowIndex;
		var check = false;

		check = $("#dt_odontogram").DataTable().rows(function(idx, data, node) {

			if (data.Tooth == key) {
				rowIndex = idx;
				data.Simbol = val.id;
				data.Odontogram_ID = val.dataID;
				params = data;

				return true;
			}
		}).data();

		if (check.any()) {
			if (action == 'update') {
				_datatable.row(rowIndex).data(params).draw();
			} else {
				_datatable.row(rowIndex).remove().draw(true);
			}
		}
	}

	$(document).ready(function() {
		$("label").tooltip();
		$("div.click").tooltip();
		createOdontogram();
		$("#dt_odontogram").dt_odontogram();

		$(".click").click(function(event) {
			var control = $("#controls").children().find('.active').attr('id');
			var img = $(this).find("input[name=img]:hidden").val();
			var id = $(this).attr('id');
			var active = "color-" + $("label.active").attr("id");
			var title = $("label.active").attr("data-original-title");
			var data_id = $("label.active").attr("data-id");

			if ($("label.active").attr("id") == undefined) {
				return false;
			}

			if ($(this).hasClass(active)) {
				$(this).removeClass(active);
				$(this).attr("data-original-title", "");
				datatable_update('delete', id, '')

			} else {
				var color = $(this).attr('class').split(' ').pop();

				if (color.includes("color")) {
					$(this).removeClass(color);
					$(this).attr("data-original-title", "");

					datatable_update('update', id, {
						id: id,
						dataID: data_id
					});

					$(this).addClass(active);
					$(this).attr("data-original-title", title);
				} else {
					$(this).addClass(active);
					$(this).attr("data-original-title", title);
					$("#dt_odontogram").DataTable().row.add({
						Tooth: id,
						Odontogram_ID: data_id,
						Simbol: active.substring(6),
						Note: "",
					}).draw(true);
				}

				$(this).tooltip();
			}

			return false;
		});
		return false;
	});
</script>