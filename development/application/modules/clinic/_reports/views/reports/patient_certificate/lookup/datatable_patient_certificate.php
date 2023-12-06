<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Lookup Pasien </h4>
		</div>
		<div class="modal-body">
			
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user-md"></i></span>
							<input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
							<div class="input-group-btn">
								<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table id="dt-lookup-common-patient" class="table table-sm table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th></th>
							<th><?php echo 'NRM' ?></th>
							<th><?php echo 'Nama Pasien' ?></th>
							<th><?php echo 'Alamat' ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<?php echo lang('patients:referrer_lookup_helper') ?>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
	//<![CDATA[
	(function($) {
		$.fn.extend({
			DT_Lookup_CommonPatient: function() {
				var _this = this;

				if ($.fn.DataTable.isDataTable(_this.attr("id"))) {
					return _this
				}

				var _datatable = _this.DataTable({
					dom: 'tip',
					lengthMenu: [15, 30, 60],
					processing: true,
					serverSide: false,
					paginate: true,
					ordering: true,
					order: [
						[1, 'asc']
					],
					searching: true,
					info: true,
					responsive: true,
					//scrollCollapse: true,
					//scrollY: "200px",
					ajax: {
						url: "<?php echo base_url("common/patients/datatable_collection") ?>",
						type: "POST",
						data: function(params) {}
					},
					columns: [{
							data: "NRM",
							className: "actions",
							orderable: false,
							searchable: false,
							width: "70px",
							render: function(val, type, row) {
								var data = row;
								var json = JSON.stringify(data).replace(/"/g, '\\"');
								return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang("buttons:apply") ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang("buttons:apply") ?></span></a>";
							}
						},
						{
							data: "NRM",
							width: "70px",
							orderable: true,
							render: function(val, type, row) {
								return "<b>" + val + "</b>"
							}
						},
						{
							data: "NamaPasien",
							orderable: true
						},
						{
							data: "Alamat",
							orderable: true
						},
					]
				});

				return _this
			}
		});

		var _datatable = $("#dt-lookup-common-patient").DT_Lookup_CommonPatient();

		datatable_searchable.init( _datatable );

	})(jQuery);
	//]]>
</script>