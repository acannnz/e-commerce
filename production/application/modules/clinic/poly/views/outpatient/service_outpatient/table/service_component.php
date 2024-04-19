<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
	<div class="col-md-12">
		<div class="table-responsive">
			<table id="dt_service_component" class="table table-sm table-bordered" width="100%">
				<thead>
					<tr>
						<th></th>
						<th>Komponen</th>
						<th>Harga</th>
						<th>Disc%</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>


<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var _datatable;

		var indexRow = '<?php echo $indexRow ?>';
		var service_selected = $("#dt_services").DataTable().row(indexRow).data();
		var ListHargaID = service_selected.ListHargaID;
		var JasaID = service_selected.JasaID;
		// get data temporary service component
		var service_component_temp = $("#service_component").data("component");
		var JenisKerjasamaID = $("#JenisKerjasamaID").val();

		var _datatable_populate;
		var _datatable_actions = {
			edit: function(row, data, index) {
				switch (this.index()) {

					case 2:
						var _input = $("<input type=\"number\" style=\"width:100%\" value=\"" + parseFloat(data.HargaBaru || 0) + "\" class=\"form-control\">");
						this.empty().append(_input);

						_input.trigger("focus");
						_input.on("blur", function(e) {
							e.preventDefault();
							try {

								data.HargaBaru = parseFloat(this.value || 0);
								_datatable.row(row).data(data).draw();
								_datatable_actions.store_data();
								_datatable_actions.calculate_balance();
							} catch (ex) {
								console.log(ex)
							}
						});
						break;

					case 3:
						var _input = $("<input type=\"number\" style=\"width:100%\" value=\"" + Number(data.Disc || 0) + "\" class=\"form-control\">");
						this.empty().append(_input);

						_input.trigger("focus");
						_input.on("blur", function(e) {
							e.preventDefault();
							try {

								data.Disc = Number(this.value || 0);
								_datatable.row(row).data(data).draw();
								_datatable_actions.store_data();

							} catch (ex) {
								console.log(ex)
							}
						});
						break;
				}

			},
			store_data: function() {

				var data_component = _datatable.data().toArray();

				service_component_temp[JasaID] = data_component;
				console.log(service_component_temp[JasaID]);

			},
			adjust_price: function(row, data) {
				if (JenisKerjasamaID == 3 || JenisKerjasamaID == 4) {
					data.HargaBaru = data.HargaBaru;
				}

				if (JenisKerjasamaID == 2) {
					data.HargaBaru = data.HargaIKS_Baru;
				}

				if (JenisKerjasamaID == 9) {
					data.HargaBaru = data.HargaBPJS;
				}

				_datatable.row(row).data(data);

			},
			calculate_balance: function() {
				var total = 0;
				var _table = _datatable.rows().data();
				_table.each(function(value, index) {

					total = Number(total) + Number(value.HargaBaru);
				});

				$("#Tariff").val(mask_number.currency_add(total));

				_table_service = $("#dt_services").DataTable().row(indexRow).data();
				_table_service.Tarif = total;
				$("#dt_services").DataTable().row(indexRow).data(_table_service).draw();

				var dt_services = $("#dt_services").DataTable().rows().data();
				var grand_total = 0;

				dt_services.each(function(value, index) {
					grand_total += parseFloat(value.Tarif || 0);
				});

				$("#grand_total").val(mask_number.currency_add(grand_total));

			},
		};

		$.fn.extend({
			dt_service_component: function() {
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
					data: [],
					columns: [{
							data: "KomponenID",
							className: "actions text-center",
						},
						{
							data: "KomponenName",
							// className: "actions center", 
						},
						{
							data: "HargaBaru",
							className: "",
							render: function(val) {
								return mask_number.currency_add(val)
							}
							/*render: function( val, type, row ){
								
								if ( JenisKerjasamaID == 3 || JenisKerjasamaID == 4 )
								{
									return row.HargaBaru;
								}

								if ( JenisKerjasamaID == 2 )
								{
									return row.HargaIKS_Baru;
								}

								if ( JenisKerjasamaID == 9 )
								{
									return row.HargaBPJS;
								}
								return false;
							}*/
						},
						{
							data: "Disc",
							className: "text-center",

						},
					],
					columnDefs: [{
						"targets": ["ListHargaID", "KomponenID", "HargaBaru", "HargaAwal", "KelompokAkun", "PostinganKe"],
						"visible": false,
						"searchable": false
					}],
					drawCallback: function(settings) {
						dev_layout_alpha_content.init(dev_layout_alpha_settings);
					},
					fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						var index = iDisplayIndexFull + 1;
						$('td:eq(0)', nRow).html(index);
						return nRow;
					},
					createdRow: function(row, data, index) {

						//_datatable_actions.adjust_price( row, data )

						$(row).on("dblclick", "td", function(e) {
							e.preventDefault();
							var elem = $(e.target);
							_datatable_actions.edit.call(elem, row, data, index);
						});
					}
				});

				$("#dt_service_component_length select, #dt_service_component_filter input")
					.addClass("form-control");

				return _this
			},
		});



		$(document).ready(function(e) {

			// cek apakah object servcie compnent dengan jasa ini sudah ada atau belum
			if ($.isEmptyObject(service_component_temp[JasaID])) {
				service_component_temp[JasaID] = <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK)); ?>;
			}
			// console.log("Component: ", service_component_temp);

			$("#dt_service_component").dt_service_component();
			$("#dt_service_component").DataTable().clear().draw();
			$("#dt_service_component").DataTable().rows.add(service_component_temp[JasaID]).draw();


		});

	})(jQuery);
	//]]>
</script>