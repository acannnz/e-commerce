<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), array("id" => "form_prescriptions")) ?>
<div class="modal-dialog modal-xlg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Form Resep</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">No Resep</label>
						<input type="text" id="NoResep" name="NoResep" value="<?php echo @$item->NoResep ?>" class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Apotek Tujuan </label>
						<select id="Farmasi_SectionID" name="Farmasi_SectionID" class="form-control ">
							<?php if (!empty($option_pharmacy)) : foreach ($option_pharmacy as $row) : ?>
									<option value="<?php echo $row->SectionID ?>" <?php echo $row->SectionID == @$item->Farmasi_SectionID ? "selected" : NULL ?>><?php echo $row->SectionName ?></option>
							<?php endforeach;
							endif; ?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Dokter</label>
						<div class="input-group">
							<input type="hidden" id="prescriptionDokterID" name="DokterID" class="form-control" value="<?php echo @$item->DokterID ?>" />
							<input type="text" id="prescriptionDoctorName" name="prescriptionDoctorName" class="form-control" value="<?php echo @$item->NamaDokter ?>" readonly />
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_supplier ?>" id="lookup_supplier" data-toggle="lookup-ajax-modal" class="btn btn-default"><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_supplier" class="btn btn-default"><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
			</div>
			<?php /*?><div class="form-group">
					<label class="control-label col-md-3">Paket Obat</label>
					<div class="col-md-9">
						<div class="input-group">
						<input type="hidden" id="Paket" name="Paket" value="<?php echo @$item->Paket ?>" />
						<input type="text" id="Package_name" name="Package_name" class="form-control" value="<?php echo @$package->Nama_Paket ?>" />
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_package ?>" id="lookup_supplier_prescription" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_supplier_prescription" class="btn btn-default" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div><?php */ ?>

			<?php /*?><div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-md-3">Jumlah</label>
                        <div class="col-md-9">
                            <input type="hid" id="Jumlah" name="Jumlah" class="form-control" value="<?php echo @$item->Jumlah?>"  readonly="readonly" />
                        </div>
                    </div>
                </div><?php */ ?>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-4">
							<div class="checkbox" style="margin-top:0 !important">
								<input type="checkbox" id="CheckTambahRacikan" value="1" class=""><label for="CheckTambahRacikan"> Racikan</label>
							</div>
						</label>
						<label class="col-md-4">
							<div class="checkbox" style="margin-top:0 !important">
								<input type="hidden" name="IncludeJasa" value="0">
								<input type="checkbox" id="IncludeJasa" name="IncludeJasa" value="1" <?php echo @$item->IncludeJasa == 1 ? "Checked" : NULL ?> class=""><label for="IncludeJasa">Include Jasa</label>
							</div>
						</label>
						<label class="col-md-4">
							<div class="checkbox" style="margin-top:0 !important">
								<input type="hidden" name="Cyto" value="0">
								<input type="checkbox" id="Cyto" name="Cyto" value="1" <?php echo @$item->Cyto == 1 ? "Checked" : NULL ?> class=""><label for="Cyto">Cyto</label>
							</div>
						</label>
						<div class="input-group">
							<input type="text" id="NamaResepObat" placeholder="" class="form-control detail_form" readonly>
							<span class="input-group-btn">
								<a href="javascript:;" id="BtnTambahRacikan" class="btn btn-primary disabled"><i class="fa fa-plus"> Racikan</i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Nama Obat</label>
						<div class="input-group">
							<input type="hidden" id="product_object" data-product="{}" class="detail_form">
							<input type="text" id="Nama_Barang" placeholder="" class="form-control detail_form typeahead" autocomplete="off">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_product ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" title="Cari obat..."><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="detail_form" class="btn btn-default btn-clear" title="Bersihkan info obat..."><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Qty</label>
						<input type="number" id="JmlObat" name="d[JmlObat]" placeholder="" class="form-control detail_form" min="1">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Stok</label>
						<input type="number" id="Stok" name="d[Stok]" placeholder="" class="form-control detail_form" readonly>
					</div>
				</div>
			</div>
			<div class="row">

				<?php /*
				<div class="col-md-3">
                    <div class="form-group">	
                        <label class="control-label">Dosis</label>
						<select id="Dosis" name="d[Dosis]" class="form-control detail_form select2">
							<option value="">-- Pilih --</option>
							<?php if(!empty($option_dosis)): foreach($option_dosis as $row):?>
							<option value="<?php echo $row->Dosis ?>" <?php echo $row->Dosis == @$item->Dosis ? "selected" : NULL  ?>><?php echo $row->Dosis ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div> */ ?>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Dosis</label>
						<input type="text" id="Dosis" name="d[Dosis]" placeholder="" class="form-control detail_form">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Aturan Pakai</label>
						<input type="text" id="Dosis2" name="d[Dosis2]" placeholder="" class="form-control detail_form">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">&nbsp;</label>
						<a href="javascript:;" id="add_product" class="btn btn-primary btn-block">Tambah</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="dt_products" class="table table-bordered" width="100%">
							<thead>
								<tr>
									<th></th>
									<th>Kode</th>
									<th>Nama</th>
									<th>Satuan</th>
									<th>Dosis</th>
									<th>Aturan Pakai</th>
									<th>Qty</th>
									<th>Stok</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<span hidden id="Jumlah"></span>
		<div class="modal-footer">
			<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-danger btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
				</div>
				<div class="col-md-6">
					<button type="submit" id="submit_prescriptions" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<?php echo form_close() ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/default/assets/js/plugins/bootstrap-typeahead/bootstrap-typeahead.css">
<script type="text/javascript" src="<?php echo base_url(); ?>/themes/default/assets/js/plugins/bootstrap-typeahead/bootstrap-typeahead.js"></script>
<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var typeahead = {
			init: function() {
				$('#Nama_Barang').typeahead({
					async: true,
					minLength: 3,
					name: 'Item Drug',
					autoSelect: true,
					displayText: function(item) {
						return item.Nama_Barang || 'Empty';
					},
					afterSelect: function(_response) {
						try {
							var product_object = {
								"Barang_ID": _response.Barang_ID,
								"Disc_Persen": 0.00,
								"Dosis": "",
								"Dosis2": "",
								"Harga_Satuan": _response.Harga,
								"Jumlah": _response.Harga,
								"Kode_Barang": _response.Kode_Barang,
								"NamaResepObat": _response.Nama_Barang,
								"Nama_Barang": _response.Nama_Barang,
								"Qty": 1,
								"Satuan": _response.Satuan,
								"Stok": _response.Qty_Stok,
							};


							$("#product_object").data("product", product_object);
							if (_response.Qty_Stok < 15) {
								$("#Stok").css("background", "red");
							} else {
								$("#Stok").css("background", "transparent");
							}
							$("#Nama_Barang").val(_response.Nama_Barang);
							$("#JmlObat").val(1);
							$("#Stok").val(_response.Qty_Stok);
							$("#Harga").val(mask_number.currency_add(_response.Harga));
							$("#Disc").val(0);

						} catch (e) {
							console.log(e)
						}
					}
				});

				$('#Nama_Barang').on("keyup change", function() {
					$("#Nama_Barang").data('typeahead').source = typeahead.collection_item();
				});

			},
			collection: function() {
				return function findMatches(q, cb) {
					var matches, substringRegex;

					// an array that will be populated with substring matches
					matches = [];

					// regex used to determine if a string contains the substring `q`
					substrRegex = new RegExp(q, 'i');

					// iterate through the pool of strings and for any string that
					// contains the substring `q`, add it to the `matches` array
					data_details = $("#dt_details").DataTable().rows().data();
					data_details.each(function(value, index) {
						if (value.Barang_ID === 0 && value.Nama_Barang == value.NamaResepObat) {
							if (substrRegex.test(value.Nama_Barang)) {
								matches.push(value.Nama_Barang);
							}
						}
					});

					cb(matches);
				};
			},
			collection_item: function() {
				return function findMatches(query, processSync) {

					$.ajax({
						url: "<?php echo base_url("pharmacy/products/lookup_collection") ?>",
						type: 'GET',
						data: {
							search: {
								value: query
							},
							SectionID: $("#Farmasi_SectionID").val(),
							JenisKerjasamaID: $("#JenisKerjasamaID").val() || 2,
							CustomerKerjasamaID: $("#CustomerKerjasamaID").val() || 0,
							KTP: $("#KTP").val() || 0,
							IsEmployee: $("#IsEmployee:checked").val() || 0,
						},
						dataType: 'json',
						success: function(json) {
							// in this example, json is simply an array of strings
							processSync(json.data);
						}
					});
				}
			}
		};

		var _datatable;
		var _datatable_populate;
		// var socket = new WebSocket('ws://localhost:8080');
		<?php if (config_item('use_websocket') == 'TRUE') : ?>
			var socket = new WebSocket('ws://' + '<?= config_item('websocket_ip') ?>' + ':8080');
		<?php endif; ?>
		var _datatable_actions = {
			init: function() {
				$("#CheckTambahRacikan").on("change", function(e) {
					if ($(this).is(':checked')) {
						$("#NamaResepObat").prop("readonly", false);
						$("#BtnTambahRacikan").removeClass("disabled");
						$('#NamaResepObat').focus();
					} else {
						$("#NamaResepObat").val('');
						$("#NamaResepObat").prop("readonly", true);
						$("#BtnTambahRacikan").addClass("disabled");
					}
				});

				$("#Paket").on("change", function(e) {
					if ($(this).is(':checked')) {
						$(".package").prop("disabled");
						$("a.package").removeClass("disabled");
					} else {
						$(".package").val('');
						$(".package").prop("readonly", true);
						$("a.package").addClass("disabled");
					}
				});

				$(".btn-clear").on("click", function() {
					var clearClass = "." + $(this).prop("id");

					$(clearClass).val("");
					$(clearClass).prop("checked", false);
					$("#Stok").css("background", "transparent");
					$('#Nama_Barang').focus();
				});

				$("#add_product").on("click", function(e) {
					e.preventDefault();

					if ($("#Stok").val() <= 0) {
						$.alert_error('Stok Obat Kurang Dari 0,<br/> Silahkan Pilih Obat Yang Lain.');
						return false;
					}

					var data = $("#product_object").data("product");
					console.log(data)
					//cek jika obat sudah ada pada tabel detail
					check = $("#dt_products").DataTable().rows(function(idx, val, node) {
						return val.Barang_ID === data.Barang_ID ? true : false;
					}).data();

					if (check.any()) {
						message = "<?php echo 'Obat %s sudah ada pada daftar resep' ?>";
						$.alert_error(message.replace(/%s/g, data.Nama_Barang));
						return;
					}
					// ===========

					if ($.isEmptyObject(data)) {
						$.alert_error('Pilih Obat Sebelum ditambahkan kedalam resep');
						$('#Nama_Barang').focus();
						return false;
					}


					data.Dosis = $("#Dosis").val();
					data.Dosis2 = $("#Dosis2").val();
					data.Qty = $("#JmlObat").val();

					data.Jumlah = mask_number.currency_ceil(parseFloat(data.Qty) * parseFloat(data.Harga_Satuan) + <?php echo config_item('BiayaResepObat') ?>);

					if (data.Disc > 0) {
						data.Jumlah = data.Jumlah - (data.Jumlah * data.Disc / 100);
					}

					if ($("#CheckTambahRacikan").is(':checked')) {
						data.NamaResepObat = $("#NamaResepObat").val();
					}

					_datatable_actions.add_row(data);
					// $(".btn-clear").trigger('click');
					$('#Nama_Barang').focus();

				});

				$("#BtnTambahRacikan").on("click", function(e) {
					e.preventDefault();
					if ($("#NamaResepObat").val() == '') {
						return false;
					}

					var data = {
						"Barang_ID": 0,
						"Kode_Barang": "RACIKAN",
						"Nama_Barang": $("#NamaResepObat").val(),
						"Satuan": "RACIKAN",
						"Qty": 1,
						"Harga_Satuan": <?php echo config_item('BiayaRacikObat') ?>,
						"Disc_Persen": 0.00,
						"Jumlah": <?php echo config_item('BiayaRacikObat') ?>,
						"Stok": 0,
						"Dosis": "",
						"Dosis2": "",
						"NamaResepObat": $("#NamaResepObat").val(),
						"BiayaResep": 0.00,
					};

					_datatable_actions.add_row(data);
					$('#Nama_Barang').focus();

				});

			},
			edit: function(row, data, index) {
				switch (this.index()) {
					case 4:
						var _input = $("<input type=\"text\" style=\"width:100%\" value=\"" + data.Dosis + "\" class=\"form-control\">");
						this.empty().append(_input);

						_input.trigger("focus");
						_input.on("blur", function(e) {
							e.preventDefault();
							try {
								data.Dosis = this.value || "";
								_datatable.row(row).data(data);
								_datatable_actions.calculate_balance();
							} catch (ex) {}
						});
						break;
					case 5:
						var _input = $("<input type=\"text\" style=\"width:100%\" value=\"" + data.Dosis2 + "\" class=\"form-control\">");
						this.empty().append(_input);

						_input.trigger("focus");
						_input.on("blur", function(e) {
							e.preventDefault();
							try {
								data.Dosis2 = this.value || "";
								_datatable.row(row).data(data);
								_datatable_actions.calculate_balance();
							} catch (ex) {}
						});
						break;
					case 6:
						var _input = $("<input type=\"number\" style=\"width:100%\" value=\"" + Number(data.Qty || 0) + "\" class=\"form-control\">");
						this.empty().append(_input);

						_input.trigger("focus");
						_input.on("blur", function(e) {
							e.preventDefault();
							try {
								data.Qty = Number(this.value || 0);
								data.Jumlah = mask_number.currency_ceil(data.Qty * data.Harga_Satuan + data.BiayaResep);

								if (data.Disc_Persen > 0) {
									data.Jumlah = data.Jumlah - (data.Disc_Persen * data.Jumlah / 100);
								}
								_datatable.row(row).data(data).draw(true);
								_datatable_actions.calculate_balance();
							} catch (ex) {
								console.log(ex)
							}
						});
						break;

					case 7:
						if (data.Barang_ID != 0) {
							return false;
						}

						var _input = $("<input type=\"text\" style=\"width:100%\" value=\"" + parseFloat(data.Harga_Satuan || 0) + "\" class=\"form-control\">");
						this.empty().append(_input);

						_input.trigger("focus");
						_input.on("blur", function(e) {
							e.preventDefault();
							try {
								data.Harga_Satuan = parseFloat(this.value || 0);
								data.Jumlah = mask_number.currency_ceil(data.Qty * data.Harga_Satuan + data.BiayaResep);

								if (data.Disc_Persen > 0) {
									data.Jumlah = data.Jumlah - (data.Disc_Persen * data.Jumlah / 100);
								}
								_datatable.row(row).data(data).draw(true);
								_datatable_actions.calculate_balance();
							} catch (ex) {
								console.log(ex)
							}
						});
						break;

					case 8:
						var _input = $("<input type=\"number\" style=\"width:100%\" value=\"" + Number(data.Disc_Persen || 0) + "\" class=\"form-control\">");
						this.empty().append(_input);

						_input.trigger("focus");
						_input.on("blur", function(e) {
							e.preventDefault();
							try {
								data.Disc_Persen = Number(this.value || 0);
								data.Jumlah = mask_number.currency_ceil(data.Qty * data.Harga_Satuan + data.BiayaResep);

								if (data.Disc_Persen > 0) {
									data.Jumlah = data.Jumlah - (data.Disc_Persen * data.Jumlah / 100);
								}
								_datatable.row(row).data(data).draw(true);
								_datatable_actions.calculate_balance();
							} catch (ex) {
								console.log(ex)
							}
						});
						break;
				}
			},
			remove: function(params, fn, scope) {

				_datatable.row(scope)
					.remove()
					.draw(false);

				_datatable_actions.calculate_balance();

			},
			to_fixed: function(value, precision) {
				var power = Math.pow(10, precision || 0);
				return Math.round(value * power) / power;
			},
			calculate_balance: function() {
				try {
					var _form = $("form[id=\"form_prescriptions\"]");
					var _form_Jumlah = _form.find("#Jumlah");


					var Jumlah = 0;
					var table_data = $("#dt_products").DataTable().rows().data();
					table_data.each(function(value, index) {
						if (value.Barang_ID == 0) {
							return true;
						}

						Jumlah = mask_number.currency_ceil(Jumlah + parseFloat(value.Jumlah));

						// console.log(Jumlah);
					});

					_form_Jumlah.html(mask_number.currency_add(Jumlah));
				} catch (e) {
					console.log(e);
				}
			},
			add_row: function(params, fn, scope) {
				var data = {
					"Barang_ID": params.Barang_ID,
					"Kode_Barang": params.Kode_Barang,
					"Nama_Barang": params.Nama_Barang,
					"Satuan": params.Satuan,
					"Qty": params.Qty,
					"Disc_Persen": params.Disc_Persen,
					"Jumlah": params.Jumlah,
					"Stok": params.Stok,
					"Dosis": params.Dosis,
					"Dosis2": params.Dosis2,
					"NamaResepObat": params.NamaResepObat,
					"Harga_Satuan": params.Harga_Satuan,
					"BiayaResep": ($("#CheckTambahRacikan").is(':checked')) ? 0.00 : <?php echo config_item('BiayaResepObat') ?>,
				};


				_datatable.row.add(data).draw(true);
				_datatable_actions.calculate_balance();

				// $("#CheckTambahRacikan").attr("checked", false);
				// $("#NamaResepObat").val('');
				// $("#NamaResepObat").prop("readonly", true);
				// $("#BtnTambahRacikan").addClass("disabled");


			}


		};

		$.fn.extend({
			dt_products: function() {
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
					scrollY: "90px",
					scrollCollapse: true,
					<?php if (!empty($collection)) : ?>
						data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK)); ?>,
					<?php endif; ?>
					columns: [{
							data: "Barang_ID",
							className: "actions text-center",
							render: function(val, type, row, meta) {
								return String("<a href=\"javascript:;\" title=\"<?php echo lang("buttons:remove") ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
							}
						},
						{
							data: "Kode_Barang",
							className: "text-center",
						},
						{
							data: "Nama_Barang",
							className: ""
						},
						{
							data: "Satuan",
							className: ""
						},
						{
							data: "Dosis",
							className: ""
						},
						{
							data: "Dosis2",
							className: ""
						},
						{
							data: "Qty",
							className: "text-center"
						},
						{
							data: "Stok",
							className: "text-center"
						},
					],
					columnDefs: [{
						targets: "Jumlah, Disc_Persen, BiayaResep, Harga_Satuan",
						visible: false
					}],
					drawCallback: function(settings) {
						_datatable_actions.calculate_balance();
					},
					createdRow: function(row, data, index) {
						$(row).on("dblclick", "td", function(e) {
							e.preventDefault();
							var elem = $(e.target);
							_datatable_actions.edit.call(elem, row, data, index);
						});

						$(row).on("click", "a.btn-remove", function(e) {
							e.preventDefault();
							var elem = $(e.target);

							if (confirm("<?php echo lang('poly:delete_confirm_message') ?>")) {
								_datatable_actions.remove(data, function() {
									_datatable.ajax.reload()
								}, row)
							}
						});
					}
				});

				$("#dt_products_length select, #dt_products_filter input")
					.addClass("form-control");

				return _this
			},
		});

		function lookupbox_row_selected(response) {
			var _response = JSON.parse(response)
			if (_response) {

				try {

					$("#DokterID").val(_response.Kode_Supplier);
					$("#DocterName").val(_response.Nama_Supplier);

					$('#form-ajax-modal').remove();
					$("body").removeClass("modal-open").removeAttr("style");
				} catch (e) {
					console.log(e);
				}
			}
		}

		$(document).ready(function(e) {
			$("#dt_products").dt_products();
			_datatable_actions.init();
			typeahead.init();
			$('#Nama_Barang').focus();

			$("form[id=\"form_prescriptions\"]").on("submit", function(e) {
				e.preventDefault();

				$("#submit_prescriptions").prop("disabled", true);

				var data_post = {};
				data_post['f'] = {
					NoRegistrasi: "<?php echo @$item->NoReg ?>",
					SectionID: "<?php echo @$item->SectionID ?>",
					Tanggal: "<?php echo date("Y-m-d") ?>",
					Jam: "<?php echo date("Y-m-d H:i:s") ?>",
					NoBukti: $("#NoBukti").val(),
					Farmasi_SectionID: $("#Farmasi_SectionID").val(),
					DokterID: $("#prescriptionDokterID").val(),
					Cyto: $("#Cyto:checked").val() || 0,
					IncludeJasa: $("#IncludeJasa:checked").val() || 0,
					Paket: $("#Paket").val(),
					Jumlah: mask_number.currency_remove($('#Jumlah').html()),
					JenisKerjasamaID: $("#JenisKerjasamaID").val(),
					CompanyID: $("#KodePerusahaan").val(),
					PerusahaanID: $("#KodePerusahaan").val(),
					CustomerKerjasamaID: $("#CustomerKerjasamaID").val(),
					NRM: $("#NRM").val(),
					NoKartu: $("#NoAnggota").val(),
					KTP: $("#PasienKTP").val(),
					KomisiDokter: 0.00,
					Realisasi: 0,
					BeratBadan: $("#BeratBadan").val(),
				};

				data_post['details'] = {};

				var table_data = $("#dt_products").DataTable().rows().data();

				table_data.each(function(value, index) {
					var detail = {
						Barang_ID: value.Barang_ID,
						Satuan: value.Satuan,
						Dosis: value.Dosis,
						Dosis2: value.Dosis2,
						Qty: value.Qty,
						Harga_Satuan: value.Harga_Satuan,
						Disc_Persen: value.Disc_Persen,
						Stok: value.Stok,
						Plafon: 0,
						NamaResepObat: value.NamaResepObat,
						JenisKerjasamaID: $("#JenisKerjasamaID").val(),
						PerusahaanID: $("#KodePerusahaan").val(),
						KTP: $("#PasienKTP").val(),
						DokterID: $("#prescriptionDokterID").val(),
						KelasID: $('#KdKelas').val(),
						BiayaResep: value.BiayaResep || 0,
					}

					data_post['details'][index] = detail;
				});
				// console.log(data_post);

				$.post($(this).attr("action"), data_post, function(response, status, xhr) {
					if ("error" == response.status) {
						$.alert_error(response.message);
						$("#submit_prescriptions").prop("disabled", false);
						return false
					}

					$.alert_success(response.message);

					// Memasukan Data header reserp ke view tabel resep
					// Tambahkan NoResep dan nama supplier (nama dokter)
					data_post['f']['NoResep'] = response.NoResep;
					data_post['f']['Nama_Supplier'] = $("#prescriptionDoctorName").val();
					$("#dt_prescriptions").DataTable().row.add(data_post['f']).draw();

					<?php if (config_item('use_websocket') == 'TRUE') : ?>
						socket.send('refresh_queue');
					<?php endif ?>
					// Close Form
					$('#form-ajax-modal').remove();
					$("body").removeClass("modal-open").removeAttr("style");


				})
			});

		});

	})(jQuery);
	//]]>
</script>