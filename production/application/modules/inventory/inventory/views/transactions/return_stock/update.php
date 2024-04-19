<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
//print_r($item_lookup);exit;
?>
<?php echo form_open(site_url("{$nameroutes}/update_post"), [
	'id' => 'form_update_return',
	'name' => 'form_update_return',
	'rule' => 'form',
	'class' => ''
]); ?>
<input type="hidden" id="Retur_ID" value="<?php echo $item->Retur_ID ?>" />
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang('heading:item_unit_list'); ?></h3>
			</div>
			<div class="panel-body table-responsive">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<?php echo form_label(lang('label:date') . ' *', 'input_date', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Tgl_Retur]', set_value('f[Tgl_Retur]', $tgl_retur, TRUE), [
									'id' => 'Tgl_Retur',
									'placeholder' => '',
									'required' => 'required',
									'class' => 'form-control datepicker'
								]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:request_number') . ' *', 'No_Retur', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_Retur]', set_value('f[No_Retur]', $gen_retur_number, TRUE), [
									'id' => 'input_request_number',
									'placeholder' => '',
									'required' => 'required',
									'class' => 'form-control'
								]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:keterangan') . ' *', 'input_keterangan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Keterangan]', set_value('f[Keterangan]', @$item->Keterangan, TRUE), [
									'id' => 'Keterangan',
									'placeholder' => '',
									'required' => '',
									'class' => 'form-control'
								]); ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?php echo form_label(lang('label:warehouse') . ' *', 'input_section_id', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('h[Gudang_ID]', $dropdown_section, '1368', [
									'id' => 'input_section_id',
									'placeholder' => '',
									'required' => 'required',
									'class' => 'form-control'
								]); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo "Kode Supplier" ?></label>
							<div class="col-md-9">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user-md"></i></span>
									<input type="text" id="Kode_Supplier" name="h[Kode_Supplier]" value="<?php echo @$supplier->Kode_Supplier ?>" placeholder="" class="form-control" readonly>
									<div class="input-group-btn">
										<a href="#" id="add_supplier" data-act="ajax-modal" data-toggle="modal" data-dismiss="modal" data-action-url="<?php echo @$lookup_supplier ?>" data-title="<?php echo lang('header:lookup_supplier') ?>" class="btn btn-info"><?php echo "Pick!" ?></a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo "Nama Supplier" ?> <span class="text-danger">*</span></label>
							<div class="col-md-9">
								<input type="text" id="Nama_Supplier" name="Nama_Supplier" value="<?php echo @$supplier->Nama_Supplier ?>" placeholder="" class="form-control" required readonly>
							</div>
						</div>
					</div>
				</div>
				<hr />
				<div class="row">
					<table id="dt_trans_return_stock" class="table table-bordered table-hover" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th></th>
								<th><?php echo lang('label:item_code') ?></th>
								<th><?php echo lang('label:item_name') ?></th>
								<th><?php echo lang('label:item_konversion') ?></th>
								<th><?php echo lang('label:item_unit') ?></th>
								<th><?php echo lang('label:item_qty') ?></th>
								<th><?php echo "Harga" ?></th>
								<th><?php echo "Total" ?></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot class="dtFilter">
							<tr>
								<th></th>
								<th><?php echo lang('label:item_code') ?></th>
								<th><?php echo lang('label:item_name') ?></th>
								<th><?php echo lang('label:item_konversion') ?></th>
								<th><?php echo lang('label:item_unit') ?></th>
								<th><?php echo lang('label:item_qty') ?></th>
								<th><?php echo "Harga" ?></th>
								<th><?php echo "Total" ?></th>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="form-group">
					<?php /*?><a href="#" data-action-url="<?php echo @$item_lookup ?>" id="add_charge" data-act="ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Barang</b></a><?php */ ?>
				</div>
				<div class="form-group">
					<br /><br />
					<!--<div class="row">-->
					<?php /*?><button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
                        <button id="print" disabled="disabled" type="submit" class="btn btn-success"><?php echo 'Print'; ?></button>
                        <button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';") ?>">New</button> 
                        <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';") ?>">Close</button> <?php */ ?>
					<!--</div>-->
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php echo form_hidden('mass_action', ''); ?>
<?php echo form_close() ?>
<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var _datatable;
		var _datatable_populate;
		var _datatable_actions = {
			edit: function(row, data, index) {

				switch (this.index()) {
					case 0:
						try {
							if (confirm("<?php echo lang('charts:remove_service_confirm') ?>")) {
								_datatable_actions.remove(data, function() {
									_datatable.ajax.reload()
								}, row)
							}
						} catch (ex) {}
						break;

					case 9:

						var _input = $("<input type=\"number\" value=\"" + Number(data.Qty_Permintaan || 0) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">");
						var discount;
						var total;
						this.empty().append(_input);

						_input.trigger("focus");
						_input.on("blur", function(e) {
							e.preventDefault();
							if (_input.val() < 0) {
								alert("Angka input tidak Valid!");
								_input.val("0");
							}
							try {
								data.Qty_Permintaan = this.value || this.value;
								data.Jumlah_Total = Number(data.Qty_Permintaan) * Number(data.Harga_Beli);
								_datatable.row(row).data(data);
								_datatable_actions.calculate_balance();

							} catch (ex) {}
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
			calculate_balance: function(params, fn, scope) {

				var _form = $("form[name=\"form_create_return\"]");
				var _form_balance = _form.find("input[id=\"grand_total\"]");
				var _form_credit = _form.find("input[id=\"credit\"]");
				var _form_balance = _form.find("input[id=\"balance\"]");
				var _form_submit = _form.find("button[id=\"btn-submit\"]");

				var tol_debit = 0,
					tol_credit = 0,
					tol_balance = 0;

				var rows = _datatable.rows().nodes();
				//console.log(rows);
				for (var i = 0; i < rows.length; i++) {

					tol_debit = tol_debit + Number($(rows[i]).find("td:eq(11)").html());

				}

				//tol_balance = tol_debit - tol_credit;

				//_form_debit.val(tol_debit);	
				//_form_credit.val(tol_credit);
				$("#input_total").val(tol_debit);

				if (tol_balance == 0) {
					_form_balance.removeClass("text-danger");
					_form_submit.removeAttr("disabled");
				} else {
					_form_balance.addClass("text-danger");
					_form_submit.attr("disabled");
				}

			},
			add_row: function(params, fn, scope) {
				_datatable.row.add({}).draw(false);
			}
		};

		$.fn.extend({
			dt_services: function() {
				var _this = this;
				if ($.fn.dataTable.isDataTable(_this.attr("id"))) {
					return _this
				}
				_datatable = _this.DataTable({
					dom: 'tip',
					processing: true,
					serverSide: false,
					paginate: true,
					ordering: false,
					searching: true,
					info: true,
					responsive: true,
					scrollCollapse: true,
					ajax: {
						url: "<?php echo base_url("{$nameroutes}/datatable_collection") ?>",
						type: "POST",
						data: function(params) {
							params.Retur_ID = $("#Retur_ID").val();
						}
					},
					columns: [{
							data: "Kode_Barang",
							className: "actions text-center",
							render: function(val, type, row, meta) {
								return String("<a href=\"javascript:;\" title=\"<?php echo lang("buttons:remove") ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
							}
						},
						//{data: "Permintaan_ID"},	
						{
							data: "Kode_Barang",
							className: "",
						},
						{
							data: "Nama_Barang",
							className: ""
						},
						{
							data: "Konversi",
							className: ""
						},
						{
							data: "Kode_Satuan"
						},
						{
							data: "Qty_Retur",
							className: "text-center",
						},
						{
							data: "Harga_Retur",
							render: $.fn.dataTable.render.number(',', '.', 2, '')
						},
						{
							data: "Jumlah_Total",
							render: $.fn.dataTable.render.number(',', '.', 2, '')
						}

					],
					columnDefs: [{
						"targets": ["No_Penerimaan", "Barang_ID", "PPn", "Retur_ID"],
						"visible": true,
						"searchable": false
					}],
					"drawCallback": function(settings) {
						//dev_layout_alpha_content.init(dev_layout_alpha_settings);
					},
					createdRow: function(row, data, index) {
						//_datatable_actions.get_component_service( data );

						$("td", row).on("dblclick", function(e) {
							e.preventDefault();
							var elem = $(e.target);
							_datatable_actions.edit.call(elem, row, data, index);
						});

						$("a.btn-remove", row).on("click", function(e) {
							e.preventDefault();
							var elem = $(e.target);

							if (confirm("<?php echo lang('poly:delete_confirm_message') ?>")) {
								_datatable_actions.remove(data, function() {
									_datatable.ajax.reload()
								}, row)
							}
						})
					}
				});

				$("#dt_trans_return_stock_length select, #dt_trans_return_stock_filter input")
					.addClass("form-control");

				return _this
			},
		});



		$(document).ready(function(e) {
			$("#dt_trans_return_stock").dt_services();


			$("form[name=\"form_create_return\"]").on("submit", function(e) {
				e.preventDefault();

				var data_post = $(this).serializeArray();
				var details = [];

				var table_data = $("#dt_trans_return_stock").DataTable().rows().data();
				table_data.each(function(value, index) {
					var detail = {
						Qty_Retur: value.Qty_Retur,
						Harga_Retur: parseInt(value.Harga_Retur),
						PPn: value.Permintaan_ID || 0,
						Barang_ID: value.Barang_ID,
						Kode_Satuan: value.Kode_Satuan,
						JenisBarangID: value.JenisBarangID,
						Qty_Retur_Stok: 0,
						Kode_Satuan_Stok: value.Kode_Satuan,
						No_Penerimaan: '',
					}
					details.push($.param(detail));
				});

				data_post.push({
					name: "details",
					value: details
				});
				//console.log(data_post);

				$.post($(this).attr("action"), data_post, function(response, status, xhr) {
					console.log(response);
					//var response = $.parseJSON(response);

					if ("error" == response.status) {
						alert(response.message + response.Nama_Barang);
						return false
					}

					//$.alert_success("<?php echo lang('global:created_successfully') ?>");

					var Permintaan_ID = response.Permintaan_ID;

					<?php /*?>setTimeout(function(){
													
							document.location.href = "<?php echo base_url("{$nameroutes}/update"); ?>?id="+ Permintaan_ID;
							
							}, 30 );
						<?php */ ?>
				})
			});

		});

	})(jQuery);
	//]]>
</script>